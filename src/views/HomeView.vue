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
      alert:(msg:any) => {
        //alert(msg);
        console.log(msg);
        
      },
      generateXML: function(event:Event, elt:HTMLFieldSetElement|undefined|null) {
        let bloc:NodeListOf<HTMLFieldSetElement>|null = null;
        //this.alert("generatexml");
        if (elt === null){
          this.alert("null");
        } else if(typeof elt === 'undefined'){
          bloc = document.querySelectorAll("fieldset");
          // this.alert("undefined")
        } else {
          bloc = elt.querySelectorAll("fieldset");
          // this.alert("fieldset");
        }

        if(bloc !== null){

          // this.alert(bloc.filters)
          for (const blocChild of bloc){
            
            //this.alert(inp)
          }
          const inp = bloc[0].querySelectorAll("div.componentFromJson > div."+bloc[0].className+" > div > div.champs > input");
          //  this.alert(bloc)
          this.alert(inp)
          this.generateXML(event, bloc[0])
        }

        
      }
    } 
  }
</script>

<template>
  <div id="form">
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