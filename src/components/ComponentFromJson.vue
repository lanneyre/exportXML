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
            }
        },
        mounted(){
                console.log(typeof this.dataFromJson);
                
        }  
    }
    
</script>

<template>
  <div v-if="Object.keys(dataFromJson).length > 0">
    <div v-for="(elt, key) in dataFromJson" v-bind:key="key" >
        <!-- boucler pour afficher le formulaire en fonction du json  -->
        <!-- {{ Array.isArray(elt) }} -->
        <fieldset v-if="(typeof elt == 'object' && !Array.isArray(elt))">
            <legend>{{ capitalized(key) }}</legend> 
            <ComponentFromJson :dataFromJson="elt" />
        </fieldset>
        <div v-else-if="Array.isArray(elt)">
            <div  v-for="e in elt" v-bind:key="e">
                <ComponentFromJson :dataFromJson="e" />
                <hr />
            </div>
        </div>
        <div v-else>
            <label :for="key">{{ capitalized(key) }}</label>
            <input 
                :type="(typeof elt == 'string' && (key == 'horodatage' || key == 'dateInscription' || key == 'dateDebutExamen' || key == 'dateFinExamen' || key == 'dateDebutValidite' || key == 'dateFinValidite')) ? 'datetime-local' : typeof elt" 
                :name="key" 
                :value="(typeof elt == 'string' && (key == 'horodatage' || key == 'dateInscription' || key == 'dateDebutExamen' || key == 'dateFinExamen' || key == 'dateDebutValidite' || key == 'dateFinValidite')) ? formatDate(elt) : elt" 
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
}
</style>