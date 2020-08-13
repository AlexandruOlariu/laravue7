<template>
<div>
    <button @click="this.deleteMyData">Delete</button>

</div>
</template>

<script>
    export default {
        props:[
            'campuri',
        ],
        data:function () {
            return{
                descriere:this.campuri,
                showMyData:false};
        },
        methods:{
            async deleteMyData(){
                const data1 = new FormData();

                data1.append('_method', 'DELETE');

               await axios.post("./flowers/" + this.descriere.id, data1)
                    .then(
                        response => {

                            console.log("Success in deleting");
                        }
                    )
            },
            }

    }
</script>

<style scoped>

</style>
