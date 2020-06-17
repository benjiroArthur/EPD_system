<template>
    <div class="container flex">
        <div class="back-cover" @click="mode =false"></div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h6 class="text-center text-dark text-bold">PHOTOGRAPHY DICTIONARY</h6>
                <div class="card m-3 p-3">
                    <div class="card-head bg-transparent">
                        <div class="input-group md-form form-sm form-2 pl-0">
                            <input v-model="searchWord" @focus="setMode" class="form-control my-0 py-1 red-border" autocomplete="off" type="text" placeholder="Enter Word" aria-label="Search">
                            <div class="input-group-append bg-danger">
                                <span class="input-group-text text-white searchButton bg-danger" id="basic-text1" @click="getMeaning"><i class="fas fa-search"></i></span>
                            </div>
                        </div>
                    </div>
                   <div class="card-body">
                       <div v-if="filteredWord && mode" class="flex flex-column align-items-center">
                          <ul>
                              <li class="my-list py-2 border-bottom text-center text-dark" v-for="(fWord, i) in filteredWord" @click="setState(fWord)" :key="i">{{ fWord }}</li>
                          </ul>
                       </div>
                       <div v-show="word !== null">
                           <p>{{word.meaning}}</p>
                       </div>
                   </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Home",
        data(){
            return{
                mode: false,
                searchWord:'',
                onlyWords: [],
                words: {},
                word: {},
                filteredWord:[],
            }
        },
        methods: {
            index(){
                axios.get('/words').then((response) =>{
                    let data = response.data;
                    this.words = data.words;
                    this.onlyWords = data.onlyWords;
                }).catch();
            },
            filterWords(){
                this.filteredWord = this.onlyWords.filter(searchWord => {
                    return searchWord.toLowerCase().startsWith(this.searchWord.toLowerCase());
                });
            },
            setState(fWord){
                this.searchWord = fWord;
                this.mode = false;
            },
            setMode(){
                this.mode = true;
            },
            getMeaning(){
                this.word = {};
                this.$Progress.start();
                axios.get('/words/'+this.searchWord).then((response)=>{
                    this.word = response.data;
                    this.$Progress.finish();
                }).catch((error)=>{
                    console.log(error.message);
                })
            },
        },
        mounted() {
            this.index();
        },
        watch:{
            searchWord(){
                if(this.searchWord === ""){
                    return this.filteredWord = [];
                }
                this.filterWords();
            }
        },
        filters: {
            checkEmpty($val){
                if(this.searchWord !== ''){
                    return $val !== [] ? $val : 'No Words Found'
                }
            }
        },
    }
</script>
