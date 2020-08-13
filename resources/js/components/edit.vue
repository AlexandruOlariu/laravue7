<template>
<div>
    <button @click="showMyData=!showMyData" >Edit</button>
    <div v-if="showMyData">
        <div class="form-group">
        <form @submit.prevent="submit" enctype="multipart/form-data">
            <div>
                <input type="text"class="form-control" name="name" v-model="descriere.name" >
            </div>
        <div>
            <input type="text"class="form-control"  name="price" v-model="descriere.price" >
        </div>
        <div>
            <input type="file"class="img-thumbnail" name="image" @change="getimage($event)">

        </div>
            <div>
                <button type="submit" class="btn btn-primary">Update</button>
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
    window.axios = require('axios');

    export default {
        props: [
            'campuri',
        ],
        data: function () {
            return {
                descriere: this.campuri,
                showMyData: false,
                action: '',
                imagine: [],
errors:null,
            };
        },
        methods: {
            getimage(event) {
                this.imagine = event.target.files[0];
            },
            submit() {
                const data1 = new FormData();
                data1.append('name', this.descriere.name);
                data1.append('price', this.descriere.price);

                if (this.imagine.size >= 0) {
                    data1.append('url', this.imagine);
                    console.log(this.imagine);
                }
                data1.append('_method', 'PUT');

                axios.post("./flowers/" + this.descriere.id, data1)
                    .then(
                        response => {

                            this.descriere.url=response['data'];
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


            },
        }


    }
</script>

<style scoped>

</style>
