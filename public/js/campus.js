
$(document).ready(function () {

    suppressionCampus()

    supprCampusModal();

    modifCampusModal();

    modifierCampus();

    function supprCampusModal(){
        $('#supprCampus').on('show.bs.modal', function (event){
            let idCampus = $(event.relatedTarget).data('id')
            let nomCampus = document.getElementById(idCampus).innerText
            let modal=$(this)
            modal.find('.modal-body p').text(idCampus)
            console.log(modal.find('.modal-body p').text())
            modal.find('.modal-body a').text(nomCampus)


        })
    }

    function suppressionCampus(){
        $('.campusASuppr').unbind().click(function (event){
            console.log('oui?')
            let idCampus = $('#idCampusASuppr').text()
            console.log(idCampus)
        $.ajax({
            url : Routing.generate('supprCampus', {'idCampus' : idCampus})
        })
            //En cas de succès de la requête, la ligne est enlevée dynamiquement du tableau.
            .done(function (Response) {
                console.log(Response);
                console.log(Response.status);
                let ligneASupprimer= document.getElementById(idCampus)
                let boutonslies = ligneASupprimer.nextSibling.nextSibling || ligneASupprimer.nextElementSibling.nextElementSibling
                console.log(boutonslies)
                if (Response.status === "deleted"){
                    $(ligneASupprimer).remove();
                    $(boutonslies).remove();

            }
        })
        //Sinon, un message d'erreur est transmis à l'utilisateur.
        .fail(function (Fail){
            //Impossible de supprimer ce campus.
            $(".supprimerCampus").prepend("<div class='error'>" +
                "Impossible de supprimer ce campus, lié à un ou plusieurs participants à " +
                "supprimer en premier lieu.</div>")
        })
            $('#supprCampus').modal('hide')
            setTimeout(function(){
                $('.alert').fadeOut();}, 5);

})
}


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
        console.log(idCampus)

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




});
