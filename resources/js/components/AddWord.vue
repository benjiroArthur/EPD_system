<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-tools">
                            <a href="#" class="btn btn-outline-success text-center" title="Add Word" @click.prevent="openModal">Add word</a>
                        </div>
                    </div>

                        <div class="card-body table table-responsive table-borderless p-0">
                            <bootstrap-table :data="words" :options="myOptions" :columns="myColumns" sticky-header responsive borderless table-stripe />
                        </div>
                </div>
            </div>
        </div>

        <!--invoice modal-->
        <div class="modal fade" id="wordModal" tabindex="-1" role="dialog" aria-labelledby="wordModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 v-show="!editMode" class="modal-title" id="wordModalLabel">Add Word</h5>
                        <h5 v-show="editMode" class="modal-title" id="wordModalLabell">Edit Word</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="editMode === true ? editWord() : addWord()">
                            <div class="mb-3">
                                <input v-model="form.name" type="text" class="form-control" placeholder="Word"
                                       :class="{ 'is-invalid': form.errors.has('email') }" name="name" id="name" required>
                                <has-error :form="form" field="name"></has-error>
                            </div>
                            <div class="mb-3">
                                <textarea v-model="form.meaning" name="meaning" id="" cols="30" rows="10" class="form-control" placeholder="Meaning"></textarea>
                                <has-error :form="form" field="name"></has-error>
                            </div>
                            <div class="text-center">
                                <button v-show="!editMode" type="submit" class="btn btn-success">Add</button>
                                <button v-show="editMode" type="submit" class="btn btn-success">Edit</button>
                                <button type="reset" class="btn btn-danger" @click="reset">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import BootstrapTable from 'bootstrap-table/dist/bootstrap-table-vue.min';
    export default {
        name: "AddWord",
        components: {BootstrapTable},
        data(){
            return{
                editMode: false,
                words: {},
                word: {},
                form: new Form({
                    name: '',
                    meaning: '',
                }),
                form: new Form({
                    id: '',
                    name: '',
                    meaning: '',
                }),
                myOptions: {
                    search: true,
                    pagination: true,
                    toolbar: '#toolbar',
                    clickToSelect: true,
                    selectItemName: 'id',
                    index: true,

                },
                myColumns: [
                    {field: 'name', title: 'Word'},
                    {field: 'meaning', title: 'Meaning'},
                    {
                        field: 'action',
                        title: 'Action',
                        align: 'center',
                        clickToSelect: false,
                        render: function (e, value, row) {
                        },
                        formatter: function (e, value, row) {
                            return ' <a class="btn btn-sm edit" href="#"><i class="fas fa-edit text-warning"></i></a>  <a class="btn btn-sm delete" href="#"><i class="fas fa-trash text-danger"></i></a> '
                        },
                        events: {
                            'click .show': function (e, value, row) {
                                //Fire.$emit('addFiles', row);

                            },
                            'click .edit': function (e, value, row) {
                                Fire.$emit('editWord', row);
                            },
                            'click .destroy': function (e, value, row) {
                                Swal.fire({
                                    title: 'Are you sure?',
                                    text: "You won't be able to revert this!",
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Yes, delete it!'
                                }).then((result) => {
                                    if (result.value) {
                                        axios.delete('/words/' + row.id).then((response) => {
                                                Swal.fire(
                                                    'Deleted!',
                                                    response.data,
                                                    'success'
                                                );
                                        }).catch(() => {
                                            Swal.fire(
                                                'Failed!',
                                                'Word Could Not Be Deleted.',
                                                'warning'
                                            )
                                        });
                                    }

                                });
                            },
                        }
                    }
                ],
            }
        },
        methods : {
            openModal(){
                this.form.reset();
                $('#wordModal').modal('show');
            },
            reset(){
                this.form.reset();
            },
            index(){
                axios.get('/words').then((response) =>{
                    let data = response.data;
                    this.words = data.words;
                }).catch();
            },
            addWord(){
                $('#wordModal').modal('hide');
                this.$Progress.start();
                this.form.post('/words')
                .then((response)=>{
                    Fire.$emit('tableUpdate');
                    Swal.fire(
                        'Success',
                        'Word Added Successfully',
                        'success'
                    );
                    this.form.reset();
                }).catch((error)=>{
                    console.log(error.message);
                })
            },
            editWord(){
                $('#wordModal').modal('hide');
                this.$Progress.start();
                this.form.put('/words/' + this.word.id)
                .then((response)=>{
                    Fire.$emit('tableUpdate');
                    Swal.fire(
                        'Success',
                        'Word Edited Successfully',
                        'success'
                    );
                    this.form.reset();
                }).catch((error)=>{
                    console.log(error.message);
                })
            },
            editModal(row){
                this.word = row;
                this.form.fill(row);
                this.editMode = true;
                $('#wordModal').modal('show');
            },
        },
        mounted() {
            this.index();
            Fire.$on('editWord', (row) => {
                this.editModal(row);
            });
            Fire.$on('tableUpdate', ()=>{
                this.index();
            });
        }
    }
</script>

<style scoped>

</style>
