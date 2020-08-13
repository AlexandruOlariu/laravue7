<template>
    <div >
        <h1>Flori</h1>
        <div v-if="hasPermissionToInsert"><ins1 :nrentr="noOfEntries" @adaugadate="adaugadate" ></ins1></div>
       <ul>
        <li v-for="floare in flori1">
            <div>
                <div>
                    <div>{{floare.name}}</div>

                </div>
                <div style="display: grid;grid-template-columns: 1fr 1fr 1fr;">
                    <div> <view1 :campuri='floare'></view1></div>
                    <div v-if="hasPermissionToEdit"> <edit1 :campuri='floare'></edit1></div>
                    <div v-if="hasPermissionToDelete"><del1 :campuri='floare' v-on:click.native="refreshcontent(floare.id)"></del1></div>
                </div>
            </div>
        </li>
       </ul>


    </div>
</template>
<script>

    import view1 from "./view";
    import edit1 from "./edit";
    import  del1 from "./del";
    import ins1 from "./ins1";
export default{
    props:[
        'flori',
        'can_update',
        'can_delete',
        'can_insert',
    ],
    components: {
        view1,
        edit1,
        del1,
        ins1,
    },
    computed: {
        hasPermissionToEdit: function(){

            return this.canupdate;
    },
        hasPermissionToDelete: function(){

            return this.candelete;
        },
        hasPermissionToInsert: function(){

            return this.caninsert;
        },

        noOfEntries:function () {
            return this.flori1.length;
        }
    },

    data:function () {
        return {
            flori1: this.flori,
            erori:this.erori,
            canupdate:this.can_update,
            candelete:this.can_delete,
            caninsert:this.can_insert,
        };
    },
    mounted(){
        console.log('Component mounted')
    },
    methods:{
        adaugadate (value) {
            if(value.name)
            this.flori1.push(value);
        },
      refreshcontent:function (id) {
          this.flori1.pop(id);
      }
    }
}
</script>
