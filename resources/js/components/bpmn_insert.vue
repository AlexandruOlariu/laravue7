<template>
    <div>
        <button @click="showMyData=!showMyData" class="btn btn-primary btn-lg active mb-2" aria-pressed="true">New pullrequest</button>
        <div v-if="showMyData">
        <div style="margin: 30px 0px">
            <h1>Create pullrequest</h1>
        </div>

        <div class="card mb-5">
            <div class="card-body">

                <form @submit.prevent="insertdata"  enctype="multipart/form-data">

                    <div class="form-group">
                        <label for="exampleFormControlInput1">Title</label>
                        <input class="form-control" name="title" id="title" type="text" v-model="mtitle" placeholder="Insereaza numele pentu pr">
                    </div>

                    <button class="btn btn-primary">Create</button>
                </form>

            </div>
        </div>

        </div>
    </div>
</template>

<script>
    export default {
        name: "bpmn_insert",
        props:[
            'prs',
        ],
        data:function () {

            return{
                showMyData:false,
                pullrequests:this.prs,
                mtitle:'',
            }
        },
        methods:{
          insertdata:function () {
              const data1 = new FormData();
              data1.append('title', this.mtitle);
              data1.append('_method', 'POST');

              axios.post("./pullrequest/create", data1)
                  .then(
                      response => {
                          this.showMyData=false;
                          //console.log(response['data']);
                          try{this.pullrequests.push(response['data'])}catch (e) {
                              console.log(e);
                          };
                          //console.log(this.pullrequests);
                          //console.log("Success in onInsert");
                      }
                  ).catch((error) => {
                  // Error 😨
                  if (error.response) {
                      /*
                       * The request was made and the server responded with a
                       * status code that falls out of the range of 2xx
                       */
                      console.log(error.response.data.errors);
                     // this.errors = error.response.data.errors;
                  }else{

                  }
              });


              //if(this.nrentr==1){setTimeout(function(){ location.reload(); }, 500);}
          },
        }
    }
</script>


