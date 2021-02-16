$(document).ready(function () {

    supprVille();

    cliqueSurBoutonModifier()

    modifierVilleAjax();

    rechercheAjaxKeyup();

    cliqueListeRecherche();

});

function cliqueSurBoutonModifier(){

    $('.modifier').unbind().click(function (event){
        let idVille = $(this).attr("data-ville");
        console.log(idVille);
        $("#modifVille").remove();
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

}

function modifierVilleAjax(){

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

}

function rechercheAjaxKeyup(){

    $("#searchVille").on("keyup", function(event){
        //event.preventDefault();

        //récupère la valeur de l'input
        let keyword = $("#searchVille").val();

        //ajax
        $.ajax({
            "url": $("#searchVille").attr("data-path"),
            "data": {
                "keyword": keyword
            }
        })
            .done(function(response){
                console.log(response);
                $(".ville-list").empty();
                $(".ville-list").delay(0).slideDown();
                let urlVilleDetail = $('#villedetailUrl').attr("data-path");
                for(let i = 0; i < response.length; i++){
                    $(".ville-list").append('<button class="rechercheVille" data-Ville="'+response[i].id+'">'+ response[i].nom +'</button>');
                    $(".ville-list").delay(5000).slideUp();
                }
            });
    });

}

function supprVille(){
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
}

function cliqueListeRecherche(){
    $('.containerVille').unbind().on('click', '.rechercheVille', function (event){
        console.log("titi");
        let idVille = $(this).attr("data-Ville");
        console.log(idVille);
        $("#modifVille").remove();
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
}
