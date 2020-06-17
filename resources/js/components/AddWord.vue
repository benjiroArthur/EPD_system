<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Add Word</div>

                    <div class="card-body">
                        <form @submit.prevent="addWord">
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
                                <button class="btn btn-success">Add</button>
                                <button class="btn btn-danger" @click="reset">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "AddWord",
        data(){
            return{
                form: new Form({
                    name: '',
                    meaning: '',
                }),
            }
        },
        methods:{
            reset(){
                this.form.reset();
            },
            addWord(){
                this.$Progress.start();
                this.form.post('/words')
                .then((response)=>{
                    Swal.fire(
                        'Success',
                        'Word Added Successfully',
                        'success'
                    );
                    this.form.reset();
                }).catch((error)=>{
                    console.log(error.message);
                })
            }
        },
    }
</script>

<style scoped>

</style>
