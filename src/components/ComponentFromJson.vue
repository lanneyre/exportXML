<script lang="ts">
    import dayjs from 'dayjs';
    export default {
        name:"ComponentFromJson",
        data() {
            return {
                
            }
        },
        props:{
            dataFromJson: Object
        },
        computed:{

        },
        methods: {
            capitalized(name:string) {
                const capitalizedFirst = name[0].toUpperCase();
                const rest = name.slice(1).toLowerCase();

                return capitalizedFirst + rest;
            },
            formatDate(dateString:string) {
                const date = dayjs(dateString);
                // console.log(date.format("YYYY-MM-DDThh:mm"));
                
                    // Then specify how you want your dates to be formatted
                return date.format("YYYY-MM-DDThh:mm");
            }, 
            displayBlock(event){
                console.log("test display", event.target.parentElement.nextSibling);
                const valeur = event.target.innerText
                if(valeur === "+"){
                    // on déplie 
                    event.target.innerText = "-"
                    event.target.parentElement.nextSibling.classList.add("active")
                } else {
                    // on replie
                    event.target.innerText = "+"
                    event.target.parentElement.nextSibling.classList.remove("active")
                }
            }, deleteBloc(event){     
                const bloc = event.target.parentElement.parentElement;
                const blocs = bloc.parentElement.children.length;

                if(blocs > 1){
                    // alert("je supprime");
                    bloc.remove();
                } else {
                    alert('Impossible de supprimer le dernier élément') 
                }

            }
        },
        mounted(){
                //console.log(typeof this.dataFromJson);
                
        }  
    }
    
</script>

<template>
  <div v-if="Object.keys(dataFromJson).length > 0">
    <div v-for="(elt, key) in dataFromJson" v-bind:key="key" >
        <!-- boucler pour afficher le formulaire en fonction du json  -->
        <!-- {{ Array.isArray(elt) }} -->
        <fieldset v-if="(typeof elt == 'object' && !Array.isArray(elt))">
            <legend>{{ capitalized(key) }} <span class="plus" @click="displayBlock">+</span></legend>
            <div class="componentFromJson">
                <ComponentFromJson :dataFromJson="elt" class="" />
            </div> 
        </fieldset>
        <div v-else-if="Array.isArray(elt)">
            <button class="btn" @click="elt.push(elt[0])">Ajouter un bloc</button>
            <fieldset v-for="(e, key) in elt" v-bind:key="key" class="blocArray">
                <legend><button class="btn" @click="deleteBloc">Retirer ce bloc</button></legend>
                <ComponentFromJson :dataFromJson="e" />
            </fieldset>
        </div>
        <div v-else>
            <label :for="key">{{ capitalized(key) }}</label>
            <input 
                :type="(typeof elt == 'string' && (key == 'horodatage' || key == 'dateInscription' || key == 'dateDebutExamen' || key == 'dateFinExamen' || key == 'dateDebutValidite' || key == 'dateFinValidite')) ? 'datetime-local' : typeof elt" 
                :name="key" 
                :id="key" />
        </div>
        
    </div>
    
  </div>
</template>


<style scoped>
fieldset, section{
    width: 100%;
    margin: 1rem 0;
    border-color: lightgray;
    border-style: double;
}
label{
    width: 40%;
    display: inline-block;
    border-bottom: 1px dashed gray;
}
input{
    width: 60%;
}
hr{
    margin: 1rem 0;
}
legend{
    padding: 0 0.5rem;
    color: darkblue;
    font-weight: bold;
    display: flex;
    align-items: center;
}

.plus{
    border: 1px solid darkblue;
    border-radius: 50%;
    width: 16px;
    height: 16px;
    display: inline-block;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-left: 10px;
    cursor: pointer;
}
.componentFromJson{
    display: none;
}
.componentFromJson.active{
    display: block;
}
.blocArray{
    border: 1px solid darkblue;
    padding: 5px;
    border-radius: 10px;
}
.btn{
    border: 1px solid darkblue;
    color: darkblue;
    border-radius: 5px;
}
</style>