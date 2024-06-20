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
      // premiere tentative
      /*
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

        
      }*/
      // seconde tentative
      generateXML: function(obj):String{
        //this.alert(obj);
        let xml:String = "";

        for (const prop in obj) {
          //ouverture de balise
          xml += obj[prop] instanceof Array ? '' : '\n<cpf:' + prop
          if(prop == 'flux') {
            xml += ' xmlns:cpf="urn:cdc:cpf:pc5:schema:1.0.0"';
          }
          xml += obj[prop] instanceof Array ? '' : '>';
          if(obj[prop] instanceof Array){
            // Si c'est un tableau traitement particulier
            // récupérer les éléments dans le form

            for (let array in obj[prop]) {
              // this.alert(array)
              xml += '\n<cpf:' + prop + '>';
              xml += ''+this.generateXML(new Object(obj[prop][array]));
              xml += '\n</cpf:' + prop + '>';
            }
          } else if (typeof obj[prop] == 'object') {
            xml += ''+this.generateXML(new Object(obj[prop]));
          } else {
            // récupérer les éléments dans le form
            // donnée du json pas du formulaire 

            // pb en cas de tableau donc doublons d'id
            
            let data:HTMLInputElement|null;
            if(prop == 'idClient'){
              if(obj['certificateurs']){
                data = document.querySelectorAll("[name="+prop+"]")[0] as HTMLInputElement; 
              } else {
                data = document.querySelectorAll("[name="+prop+"]")[1] as HTMLInputElement; 
              }
            } else {
              data = document.querySelector("[name="+prop+"]");  
            }
            
            // insertion de la donnée extraite du formulaire
            xml += data?.value;
          }
          //fermeture de balise
          xml += obj[prop] instanceof Array ? '' : '</cpf:' + prop + '>';
        }
        xml = xml.replace(/<\/?[0-9]{1,}>/g, '');
        return xml;
        
      },
      generateXMLButton: function(event:Event) {
        //this.alert(this.json);
        // J'ai la structure du fichier 
        const xml = '<?xml version="1.0" encoding="UTF-8"?>' + this.generateXML(this.json);
        this.alert(xml)
      }
    } 
  }
</script>

<template>
  <div id="form">
    <ComponentFromJson :dataFromJson="json" />
    <img src="@/assets/xml.png" alt="GenerateXML" id="GenerateXML" @click="generateXMLButton">
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