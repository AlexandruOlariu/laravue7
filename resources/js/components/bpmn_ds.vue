<template>
<div>

    <button @click="showMyData=!showMyData" >Status(tbm)</button>
    <div v-if="showMyData">
<div v-if="showPC">
        <form @submit.prevent="postPC" enctype="multipart/form-data">
        <div class="form-group">
            <label for="primestecomanda">primestecomanda</label>
            <select class="form-control" id="primestecomanda" name="primestecomanda" v-model="PC">
                <option value="1">Accept</option>
                <option value="0">Reject</option>
            </select>
        </div>
        <button class="btn btn-primary">Trimite</button>
        </form>
</div>
        <div v-if="showFBSI">

            <form @submit.prevent="postFBSI" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="faceblatulsiingredientele">faceblatulsiingredientele</label>
                    <select class="form-control" id="faceblatulsiingredientele" name="faceblatulsiingredientele" v-model="FBSI">
                        <option value="1">Accept</option>
                        <option value="0">Reject</option>
                    </select>
                </div>
                <button class="btn btn-primary">Trimite</button>
            </form>
        </div>


        <div v-if="showDLC">

            <form @submit.prevent="postDLC" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="daulacuptor">daulacuptor</label>
                    <select class="form-control" id="daulacuptor" name="daulacuptor" v-model="DLC">
                        <option value="1">Accept</option>
                        <option value="0">Reject</option>
                    </select>
                </div>
                <button class="btn btn-primary">Trimite</button>
            </form>
        </div>



    </div>
</div>
</template>

<script>

    export default {
        name: "bpmn_ds",

        props:[
            'pr'
        ],
        data:function () {
            //console.log(this.pr);
            return{
                pullrequest:this.pr,
                showMyData: false,
                PC:false,
                FBSI:false,
                DLC:false,
            }
        },
        computed:{
            showPC:function () {
                if (this.pullrequest.primestecomanda==0 && this.pullrequest.faceblatulsiingredientele==0 && this.pullrequest.daulacuptor==0)
                    return true;
                else
                    return false;
            },
            showFBSI:function () {
                if (this.pullrequest.primestecomanda==1 && this.pullrequest.faceblatulsiingredientele==0 && this.pullrequest.daulacuptor==0)
                    return true;
                else
                    return false;
            },
            showDLC:function () {
                if (this.pullrequest.primestecomanda==1 && this.pullrequest.faceblatulsiingredientele==1 && this.pullrequest.daulacuptor==0)
                    return true;
                else
                    return false;
            }
        },

        methods:{


            postPC:function () {
                this.pullrequest.primestecomanda=this.PC;
                const data1 = new FormData();
                data1.append('id',this.pullrequest.id);
                data1.append('primestecomanda',this.pullrequest.primestecomanda);
                //data1.append('_method', 'PUT');
                axios.post("./pullrequest/PrimesteComanda" , data1)
                    .then(
                        response => {
                console.log(response['data']);

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

            },
            postFBSI:function () {
                this.pullrequest.faceblatulsiingredientele=this.FBSI;
                const data1 = new FormData();
                data1.append('id',this.pullrequest.id);
                data1.append('faceblatulsiingredientele',this.pullrequest.faceblatulsiingredientele);
                //data1.append('_method', 'PUT');
                axios.post("./pullrequest/FaceBlatulsiIngredientele" , data1)
                    .then(
                        response => {
                            console.log(response['data']);

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
            },
            postDLC:function () {
                this.pullrequest.daulacuptor=this.DLC;
                const data1 = new FormData();
                data1.append('id',this.pullrequest.id);
                data1.append('daulacuptor',this.pullrequest.daulacuptor);
                //data1.append('_method', 'PUT');
                axios.post("./pullrequest/DauLaCuptor" , data1)
                    .then(
                        response => {
                            console.log(response['data']);

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
            }
        }
    }
</script>

