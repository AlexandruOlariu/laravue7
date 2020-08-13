<template>
    <div>
        <button @click="showMyData=!showMyData">Insert new data</button>
        <div v-if="showMyData">
            <div class="form-group">
                <form @submit.prevent="submit" enctype="multipart/form-data">
                    <div>
                        <input type="text" class="form-control" name="name" placeholder="Nume" v-model="descriere.name">
                        <div></div>
                    </div>
                    <div>
                        <input type="text" class="form-control" name="price" placeholder="Pret" v-model="descriere.price">
                        <div></div>
                    </div>
                    <div>
                        <input type="file" class="img-thumbnail" name="image" @change="getimage($event)">
                        <div></div>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary" @click="onClickButton($event)">Update</button>
                    </div>
                </form>
            </div>
        </div>
        <div v-if="errors" class="bg-red-500 text-white py-2 px-4 pr-0 rounded font-bold mb-4 shadow-lg">
            <div v-for="error in errors" >
                <p  class="text-danger">
                   {{ error[0] }}
                </p>
            </div>
        </div>
    </div>

</template>

<script>

    export default {
        props:[
            'nrentr',
        ],
        data: function () {
            return {
                nrdeelem:this.nrentr,
                descriere: {
                    name: '',
                    price: '',
                    url: '',
                },
                showMyData: false,
                errors: null,
            };

        },
        computed:{

        },
            methods: {
                getimage(event) {
                    this.imagine = event.target.files[0];
                },
                onClickButton (event) {

                    this.$emit('adaugadate', this.descriere)
                }

                    ,
               async submit() {
                    const data1 = new FormData();
                    data1.append('name', this.descriere.name);
                    data1.append('price', this.descriere.price);
                    if(this.imagine)
                    if (this.imagine.size >= 0) {
                        data1.append('url', this.imagine);
                    }
                    data1.append('_method', 'POST');
                    console.log(`Bearer ${await this.$auth.getAccessToken()}`);
                    axios.defaults.headers.common['Authorization'] = `Bearer ${await this.$auth.getAccessToken()}`;
                   await axios.post("./flowers", data1)
                        .then(
                            response => {
                                this.showMyData=false;
                                this.descriere.url=response['data'][0];
                                console.log(response['data']);
                                console.log("Success in onInsert");
                            }
                        ).catch((error) => {
                        // Error 😨
                        if (error.response) {
                            /*
                             * The request was made and the server responded with a
                             * status code that falls out of the range of 2xx
                             */
                            console.log(error.response.data.errors);
                            this.errors = error.response.data.errors;
                        }else{

                        }
                    });
                    this.errors=null;
                    this.childMessage=this.descriere;

                    if(this.nrentr==1){
                        setTimeout(function(){ location.reload(); }, 500);}
                },
            },


        }
</script>

<style scoped>

</style>
