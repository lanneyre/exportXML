<script lang="ts">
  import ComponentFromJson from "../components/ComponentFromJson.vue"
  
  export default {
    data() {
      return {
        json: {}
      }
    },
    components:{
      ComponentFromJson
    },
    mounted(){
        fetch('/cdcxml.json')
            .then(r => r.json())
            .then(json => {
                this.json = json
                // this.jsonKey = Object.keys(json)
                console.log(json);
            })
    }  
  }
</script>

<template>
    <ComponentFromJson :dataFromJson="json" />
</template>
