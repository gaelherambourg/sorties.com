
$(document).ready(function () {

    supprimerCampus();

    modifCampusModal();

    modifierCampus();



function modifCampusModal(){
    $('#modifCampus').on('show.bs.modal', function (event){
        let idCampus = $(event.relatedTarget).data('id')
        let nomCampus = document.getElementById(idCampus).innerText
        let modal=$(this)
        modal.find('.modal-body input').val(nomCampus)
        modal.find('.modal-body p').text(idCampus)
    })
}

function modifierCampus(){
    $('.campusAModif').unbind().click(function (event){
        let idCampus = $('#idCampus').text()
        let nomCampus = $('#nomCampus').val()

        $.ajax({
            url: $('.urlCampusModif').attr('data-path'),
            data: {
                'idCampus': idCampus,
                'nomCampus': nomCampus
            }
        })
            .done(function (Response) {
                console.log(Response);
                let ligneAModif= document.getElementById(idCampus)
                ligneAModif.innerText = nomCampus
                $('#modifCampus').modal('hide')
                setTimeout(function(){
                    $('.alert').fadeOut();}, 5);
            })
    })
}
/*
function modifierCampus(){
    //Récupération de l'Id à modifier.
    $('.modifCampus').unbind().click(function (event){
        let idCampus = $(this).attr('data-campus')
        let nomCampus = $('#form_campus_nom').val();

        $.ajax({
            url: $('.urlCampusModif').attr('data-path'),
            data: {
                'idCampus': idCampus,
                'nomCampus': nomCampus
            }
        })
            .done(function (Response) {
                console.log(Response);
                location.reload();
            })
    })
}
*/

function supprimerCampus(){
    //Requête Ajax pour la suppression dynamique d'un campus.
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
                    setTimeout(function(){
                        $('.alert').fadeOut();}, 5);
                }
            })
            //Sinon, un message d'erreur est transmis à l'utilisateur.
            .fail(function (Fail){
                //Impossible de supprimer ce campus.
                $(".supprimerCampus").prepend("<div class='error'>Impossible de supprimer ce campus, lié à un ou plusieurs participants à supprimer en premier lieu.</div>");
            })

    })
}





});
