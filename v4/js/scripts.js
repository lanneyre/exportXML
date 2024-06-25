(()=> {
    let champs = document.querySelectorAll('.champs')
    champs.forEach(champ => {
        champ.addEventListener("focus", (c)=>{
            document.querySelector('#aide').innerHTML = "<h3>Format</h3>" + c.target.getAttribute('dataformat') + "<h3>Définition</h3>" + c.target.getAttribute('datadef');
            // alert(c.target.getAttribute('dataformat'))
        });
    });
}
)();