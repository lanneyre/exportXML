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
                // console.log(json);
            })
    },
    methods:{
      alert:(msg:string) => {
        //alert(msg);
        console.log(msg);
        
      },
      generateXML: function(event:Event, elt:HTMLElement) {
        let bloc;
        this.alert("test")
        if (typeof elt === 'undefined'){
          bloc = document.querySelector("fieldset");
        } else {
          bloc = elt.querySelector("fieldset");
        }

      }
    } 
  }
</script>

<template>
  <div>
    <ComponentFromJson :dataFromJson="json" />
    <img src="@/assets/xml.png" alt="GenerateXML" id="GenerateXML" @click="generateXML">
  </div>
</template>

<style scoped>
  #GenerateXML{
    width: 50px;
    position: fixed;
    right: 2%;
    bottom: 2%;
  }
  #GenerateXML:hover{
    cursor: pointer;
  }
</style>