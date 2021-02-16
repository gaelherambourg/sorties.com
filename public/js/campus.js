$(document).ready(function () {

    $('.supprCampus').unbind().click(function (event){
        let idCampus = $(this).attr('data-campus');
        let ligneASupprimer = $(this).parent().parent();
        console.log(idCampus);
        console.log(ligneASupprimer);
        $.ajax({
            url : $('.supprimerCampus').attr('data-path'),
            data : {
                'idCampus' : idCampus
            }
        })
            //En cas de succès de la requête, la ligne est enlevée dynamiquement du tableau.
            .done(function (Response) {
                console.log(Response);
                console.log(Response.status);
                if (Response.status === "deleted"){
                    $(ligneASupprimer).remove();
                }
            })
            //Sinon, un message d'erreur est transmis à l'utilisateur.
            .fail(function (Fail){
                console.log("titi");
                //Impossible de supprimer ce campus.
                $(".supprVille").prepend("<div class='error'>Impossible de supprimer ce campus.</div>");
            })

    })




});