<template>
<div>
<button @click="showMyData=!showMyData" >View</button>
    <div v-if="showMyData">
        <div>
            {{descriere.price}}
        </div>
        <img>
            <img :src=adresa width="300px" height="300px"></img>
        </div>
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
        computed:{
            adresa:function () {
                return "storage/"+this.descriere.url;
            }
        }

    }
</script>


