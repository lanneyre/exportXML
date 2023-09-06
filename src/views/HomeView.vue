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
      generateXML: function(elt:HTMLFieldSetElement|undefined|null) {
        let blocs:NodeListOf<HTMLFieldSetElement>|null; 
        let bloc:HTMLFieldSetElement;
        let niveau:number = 1;
        let xml:String = "";
        if(typeof elt !== 'undefined' && elt !== null){
          niveau = Number(elt.getAttribute("niveau"))
          bloc = elt
        } else {
          xml = '<?xml version="1.0" encoding="UTF-8"?>'+"\n"
          blocs = document.querySelectorAll("fieldset[niveau='1']");
          bloc = blocs[0];
        }
      
        let baliseO = ""
        for (let index = 0; index < niveau-1; index++) {
          baliseO += "\t";
        } 
        baliseO += "<cpf:"
        if(bloc.className === "blocArray"){
          baliseO += bloc.getAttribute('name')
        } else {
          baliseO += bloc.className
        }
        if(bloc.className == "flux") {
          baliseO += ' xmlns:cpf="urn:cdc:cpf:pc5:schema:1.0.0"'
        }
        baliseO += '>'
        //ouverture balise          
        xml += baliseO+"\n";
        //on récupère les inputs 
        const inputs:NodeListOf<HTMLInputElement>|null = bloc.querySelectorAll("input[niveau='"+niveau+"']")
        let inputsToXML:String = ""
        inputs.forEach(input => { 
          for (let index = 0; index < niveau; index++) {
            inputsToXML += "\t";
          }           
          inputsToXML += "<cpf:"+input.name+">"+input.value+"</cpf:"+input.name+">\n"
        })
        xml += ""+inputsToXML;
        
        // on parcours les fieldsets enfants de niveau +1           
        const fieldsets:NodeListOf<HTMLFieldSetElement>|null = bloc.querySelectorAll("fieldset[niveau='"+(niveau+1)+"']");
        fieldsets.forEach(fieldset => {
          console.log(fieldset);
          
          xml += ""+this.generateXML(fieldset)
        })
          
          
        for (let index = 0; index < niveau-1; index++) {
          xml += "\t";
        } 
        // fermeture balise
        if(bloc.className === "blocArray"){
          xml += "</cpf:"+bloc.getAttribute('name')+">\n";
        } else {
          xml += "</cpf:"+bloc.className+">\n";
        }

        return xml;
      }, 
      createXml: function(evt:Event){
        // console.log(this.generateXML());
        // ici on va générer un fichier téléchargeable

        const blob = new Blob([""+this.generateXML()], { type: 'text/plain' });
        const url = URL.createObjectURL(blob);
        
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', 'cpf.xml');
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        //document.getElementById("resultat").innerText = ""+this.generateXML()
        //console.log();
      }
    } 
  }
</script>

<template>
  <div id="form">
    <ComponentFromJson :dataFromJson="json" :niveau=1 />
    <pre><code id="resultat"></code></pre>
    <img src="@/assets/xml.png" alt="GenerateXML" id="GenerateXML" @click="createXml">
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