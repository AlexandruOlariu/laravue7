<template>
    <div>
        <button @click="showMyData=!showMyData">Show/Hide diagram</button>
        <div v-if="showMyData" style="">
            <vue-bpmn
            url="http://localhost/laravue7/storage/app/process/PullRequestProcess.bpmn"
            v-on:error="handleError"
            v-on:shown="handleShown"
            v-on:loading="handleLoading"
            ></vue-bpmn>
            <button @click="viewDiagInBrowser">viewDiagInBrowser</button>
        </div>
    </div>
</template>

<script>
    import VueBpmn from 'vue-bpmn';
    export default {
        name: "bpmn_diag",
        props:['currentpl'],
        components:{VueBpmn},
        data:function(){
            console.log(this.currentpl);
            return{
                showMyData:false,
                cpr:this.currentpl,
            }
        },
        methods:{
            handleError: function(err) {
                console.error('failed to show diagram', err);
            },
            handleShown: function() {
                console.log('diagram shown');
            },
            handleLoading: function() {
                console.log('diagram loading');
            },
            viewDiagInBrowser: function(){
                const data1 = new FormData();
                data1.append('mydata',this.cpr);

              axios.get('./pullrequest/showDiag/'+this.cpr.id,data1)
                  .then(
                    response=>{
                        window.open("./pullrequest/showDiag/"+this.cpr.id,'_blank');

                    }
                  );

            },
        }
    }
</script>

<style scoped>

</style>
