<template>
<div>
    <TrimiteMsg :utiliz="utilInreg" :ucurr="utilC"></TrimiteMsg>
    <PrimesteMsg :utiliz="utilInreg" :acestuserid="utilC"></PrimesteMsg>
</div>
</template>

<script>



    import TrimiteMsg from "./mesagerie/TrimiteMsg";
    import PrimesteMsg from "./mesagerie/PrimesteMsg";
    export default {
        props: [
            'utilizatori',
            'utilizatorcurent'
        ],
        components: {PrimesteMsg, TrimiteMsg},
        data:function () {

        return{
            utilInreg:this.utilizatori,
            utilC:this.utilizatorcurent,
        }
        },
    };
</script>
