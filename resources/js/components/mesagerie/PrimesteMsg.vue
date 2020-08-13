<template>
<div>
    <br><br>

    <button class="btn btn-danger" @click="getmesaje">Refresh</button>
    <table>
        <tr><td>De la</td><td>Subiect</td><td>Mesaj</td></tr>
        <tr v-for="mesaj in mesajeprimite">
            <td>{{whoisthisuser(mesaj.senderid)}}</td>
            <td>{{mesaj.subiect}}</td>
            <td>{{mesaj.message}}</td>
        </tr>
    </table>
</div>
</template>

<script>
    export default {
        name: "PrimesteMsg",
        props:[
          'acestuserid',
            'utiliz',
        ],
        data:function () {
            return{
                mesajeprimite:{},
                userme:this.acestuserid,
                totiulilizatorii:this.utiliz,
            }
        },
        created () {
            var self = this;
            setInterval(function () {
                self.getmesaje();
            }, 1000)
        },
        methods:{
            getmesaje:function () {

                axios.get('./message/'+this.userme)
                    .then(
                        response => {
                            //console.log(response.data);
                            this.mesajeprimite=response.data;
                        }
                    );

            },
            whoisthisuser:function(id){
               // console.log(this.totiulilizatorii.find(element => element.senderid = id).name);
               return  this.totiulilizatorii.find(element => element.senderid = id).name;
            }
        }
    }
</script>

<style scoped>

</style>
