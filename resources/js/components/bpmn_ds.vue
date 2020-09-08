<template>
<div>

    <button @click="showMyData=!showMyData" >Status(tbm)</button>
    <div v-if="showMyData">
<div v-if="showCDDBF">
        <form @submit.prevent="postCDDBF" enctype="multipart/form-data">
        <div class="form-group">
            <label for="CompleteazaDateDeBazaFurnizor">CompleteazaDateDeBazaFurnizor</label>
            <select class="form-control" id="CompleteazaDateDeBazaFurnizor" name="CompleteazaDateDeBazaFurnizor" v-model="CDDBF">
                <option value="1">Accept</option>
                <option value="0">Reject</option>
            </select>
        </div>
        <button class="btn btn-primary">Trimite</button>
        </form>
</div>
        <div v-if="showCCF">

            <form @submit.prevent="postCCF" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="CreeazaContFurnizor">CreeazaContFurnizor</label>
                    <select class="form-control" id="CreeazaContFurnizor" name="CreeazaContFurnizor" v-model="CCF">
                        <option value="1">Accept</option>
                        <option value="0">Reject</option>
                    </select>
                </div>
                <button class="btn btn-primary">Trimite</button>
            </form>
        </div>


        <div v-if="showVF">

            <form @submit.prevent="postVF" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="VerificareFurnizor">VerificareFurnizor</label>
                    <select class="form-control" id="VerificareFurnizor" name="VerificareFurnizor" v-model="VF">
                        <option value="1">Accept</option>
                        <option value="0">Reject</option>
                    </select>
                </div>
                <button class="btn btn-primary">Trimite</button>
            </form>
        </div>


        <div v-if="showIIC">

            <form @submit.prevent="postIIC" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="IntraInCont">IntraInCont</label>
                    <select class="form-control" id="IntraInCont" name="IntraInCont" v-model="IIC">
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

                CDDBF:false,
                CCF:false,
                VF:false,
                IIC:false,
            }
        },
        computed:{
            showCDDBF:function () {
                if (this.pullrequest.CompleteazaDateDeBazaFurnizor==0 && this.pullrequest.CreeazaContFurnizor==0 && this.pullrequest.VerificareFurnizor==0 && this.pullrequest.IntraInCont==0)
                    return true;
                else
                    return false;
            },
            showCCF:function () {
                if (this.pullrequest.CompleteazaDateDeBazaFurnizor==1 && this.pullrequest.CreeazaContFurnizor==0 && this.pullrequest.VerificareFurnizor==0 && this.pullrequest.IntraInCont==0)
                    return true;
                else
                    return false;
            },
            showVF:function () {
                if (this.pullrequest.CompleteazaDateDeBazaFurnizor==1 && this.pullrequest.CreeazaContFurnizor==1 && this.pullrequest.VerificareFurnizor==0 && this.pullrequest.IntraInCont==0)
                    return true;
                else
                    return false;
            },
            showIIC:function () {
                if (this.pullrequest.CompleteazaDateDeBazaFurnizor==1 && this.pullrequest.CreeazaContFurnizor==1 && this.pullrequest.VerificareFurnizor==1 && this.pullrequest.IntraInCont==0)
                    return true;
                else
                    return false;
            }
        },

        methods:{


            postCDDBF:function () {
                this.pullrequest.CompleteazaDateDeBazaFurnizor=this.CDDBF;
                const data1 = new FormData();
                data1.append('id',this.pullrequest.id);
                data1.append('CompleteazaDateDeBazaFurnizor',this.pullrequest.CompleteazaDateDeBazaFurnizor);
                //data1.append('_method', 'PUT');
                axios.post("./pullrequest/CompleteazaDateDeBazaFurnizor" , data1)
                    .then(
                        response => {
                console.log(response['data']);
                this.pullrequest.state="CreeazaContFurnizor";
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
            postCCF:function () {
                this.pullrequest.CreeazaContFurnizor=this.CCF;
                const data1 = new FormData();
                data1.append('id',this.pullrequest.id);
                data1.append('CreeazaContFurnizor',this.pullrequest.CreeazaContFurnizor);
                //data1.append('_method', 'PUT');
                axios.post("./pullrequest/CreeazaContFurnizor" , data1)
                    .then(
                        response => {
                            console.log(response['data']);
this.pullrequest.state="VerificareFurnizor";
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
            postVF:function () {
                this.pullrequest.VerificareFurnizor=this.VF;
                    if(this.pullrequest.VerificareFurnizor==false)
            {
                this.pullrequest.CreeazaContFurnizor=0;
                this.pullrequest.CompleteazaDateDeBazaFurnizor=0;
            }else{
                this.pullrequest.CreeazaContFurnizor=1;
                this.pullrequest.CompleteazaDateDeBazaFurnizor=1;
                
            }
            
                const data1 = new FormData();
                data1.append('id',this.pullrequest.id);
                data1.append('VerificareFurnizor',this.pullrequest.VerificareFurnizor);
                //data1.append('_method', 'PUT');
                axios.post("./pullrequest/VerificareFurnizor" , data1)
                    .then(
                        response => {
                            console.log(response['data']);
                this.pullrequest.state="IntraInCont";
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
            postIIC:function () {
                this.pullrequest.IntraInCont=this.IIC;
                const data1 = new FormData();
                data1.append('id',this.pullrequest.id);
                data1.append('IntraInCont',this.pullrequest.IntraInCont);
                //data1.append('_method', 'PUT');
                axios.post("./pullrequest/IntraInCont" , data1)
                    .then(
                        response => {
                            console.log(response['data']);
this.pullrequest.state="ProcessEnded";
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
        }
    }
</script>

