(()=> {
    let champs = document.querySelectorAll('.champs')
    champs.forEach(champ => {
        champ.addEventListener("focus", (c)=>{
            document.querySelector('#aide').innerHTML = "<h3>Format</h3>" + c.target.getAttribute('dataformat') + "<h3>Définition</h3>" + c.target.getAttribute('datadef');
            // alert(c.target.getAttribute('dataformat'))
        });
    });

    document.getElementById("save").addEventListener("click", () => {
        //alert("submit en cours");
        document.getElementById("form").submit();
    });

    let champsMoins = document.querySelectorAll('.moins')
    champsMoins.forEach(moins => {
        moins.addEventListener("click", (c) => {
            //c.preventDefault();
            if(confirm("Êtes vous sûr de vouloir supprimer cet élément ?")){
                return true;
            } 
            c.preventDefault();
            return false;
        })
    });

}
)();