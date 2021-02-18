$(document).ready(function(){

    afficherFiltre();
    }
)

function afficherFiltre(){

    //recuperation du form
   /* var form = document.getElementById('filtre')
    var btn = document.getElementById('btnFiltre')

    btn.addEventListener("click",()=>{
        // Condition pour afficher/cacher le formulaire en fonction de son état
        if(getComputedStyle(form).display == 'block'){
            form.style.display = 'none';
        }else{
            form.style.display = 'block';
        }

    })*/

    $('#btnFiltre').click(function(e){
        e.preventDefault();
        $('#filtre').toggle('slow');
        console.log("test");
    })

}