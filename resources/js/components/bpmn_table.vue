<template>
<div>
    <bpmn_insert :prs="pullrequests"></bpmn_insert>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">title</th>
            <th scope="col">state</th>

            <th scope="col">url</th>
            <th scope="col">Diagram</th>
        </tr>
        </thead>
        <tbody>


        <tr v-for="pullrequest in pullrequests">
            <td>{{ pullrequest.id }}</td>
            <td>{{ pullrequest.title }}</td>
            <td>{{ pullrequest.state }}</td>

            <td>
                <bpmn_ds :pr='pullrequest'></bpmn_ds>
            </td>
            <td>
                <bpmn_diag :currentpl='pullrequest'></bpmn_diag>
            </td>
        </tr>

        </tbody>
    </table>
</div>
</template>

<script>
    import Bpmn_insert from "./bpmn_insert";
    import Bpmn_diag from "./bpmn_diag"
    export default {
        name: "bpmn_table",
        components: {Bpmn_insert,Bpmn_diag},
        props:[
            'prs'
        ],
        data:function () {
            return{
                pullrequests:this.prs,
            }
        },
        methods:{
          toInt:function (data) {
              if(data==false)
                  return 0;
              else return 1;
          },

        },

    }
</script>


