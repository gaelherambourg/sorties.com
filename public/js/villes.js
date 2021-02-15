$(document).ready(function () {

    $('.supprimer').unbind().click(function (event) {
        let idVille = $(this).attr("data-ville");
        let parentToRemove = $(this).parent().parent();
        console.log(idVille);
        $.ajax({

            "url": $(".supprVille").attr("data-path"),
            "data": {
                "idVille": idVille
            }
        })
            .done(function (Response) {
                console.log(Response);
                console.log(Response.status);
                if (Response.status === "deleted"){
                    $(parentToRemove).remove();
                }
            })
            .fail(function (Fail){
                    console.log("titi");
                    //Impossible de supprimer cette ville
                    $(".supprVille").prepend("<div class='error'>Impossible de supprimer cette ville, elle est déjà rattachée à des lieux, supprimez les d'abord</div>");
                })
            })

    $('.modifier').unbind().click(function (event){
        let idVille = $(this).attr("data-ville");
        console.log(idVille);

        $.ajax({

            "url": $(".urlVille").attr("data-path"),
            "data": {
                "idVille": idVille
            }
        })
        .done(function (Response) {
           console.log(Response);
           let ville = Response
           $('#ville_form_nom').val(ville.nom);
           $('#ville_form_codePostal').val(ville.codePostal);
           $('.listVille').append('<button data-v="'+ville.id+'" id="modifVille">Enregistrer</button>')
           })
    })

    $('.supprVille').unbind().on('click', '#modifVille', function (){
        let idVille = $(this).attr("data-v");
        let nomVille = $('#ville_form_nom').val();
        let cpVille = $('#ville_form_codePostal').val();
        console.log(idVille);
        $.ajax({

            "url": $(".urlVilleModif").attr("data-path"),
            "data": {
                "idVille": idVille,
                "nomVille": nomVille,
                "cpVille": cpVille
            }
        })
            .done(function (Response) {
                console.log(Response);
                location.reload();
            })

    })


});