<template>
    <div class="form-group">
        <form @submit.prevent="submit">
        <h1>Mesagerie</h1>
        <label>Subiect</label>
        <input type="text" v-model="subiect">
        <label>Mesaj</label>
        <textarea v-model="mesaj"> </textarea>
        <label>TO</label>
        <select v-on:change="onChangeSite($event)">
            <option v-for="utilizator in utilizatori" v-bind:value=utilizator.id :key="utilizator.id" >{{utilizator.name}}</option>
        </select>
        <button class="btn badge-primary">Submit</button>
        </form>
    </div>
</template>

<script>
    export default {
        name: "TrimiteMsg",
        props:[
            'utiliz',
            'ucurr',
        ],
        data:function () {

            return {
                subiect:'',
                mesaj: '',
                to: 1,
                utilizatori: this.utiliz,
                errors: '',
                utilizatorCurent: this.ucurr,
            }
        },
        methods:{
            onChangeSite:function (e) {
               this.to=e.target.value;
        },
            submit:function () {
                const data1 = new FormData();
                data1.append('senderid', this.utilizatorCurent);
                data1.append('receverid',this.to);
                data1.append('subiect',this.subiect);
                data1.append('message', this.mesaj);
                data1.append('_method', 'POST');
                axios.post("./message", data1)
                    .then(
                        response => {

                    console.log(response.data);
                        }
                    ).catch((error) => {
                    // Error 😨
                    if (error.response) {
                        console.log(error.response.data.errors);
                        this.errors = error.response.data.errors;
                    }
                });
                data1.set('_method','');
                axios.post("./send",data1)
                    .then(
                        response => {

                            console.log(response.data);
                        }
                    ).catch((error) => {
                    // Error 😨
                    if (error.response) {
                        console.log(error.response.data.errors);
                        this.errors = error.response.data.errors;
                    }
                });




                this.errors = null;
            }
        }
    }
</script>

<style scoped>

</style>
