//fonction ajax
function ajax(url, callbackfunction, method='GET') {
    let req = new XMLHttpRequest();
    req.open(method, url, true);
    req.onload = callbackfunction;
    req.send();
}

$(document).ready(function () {

    //Au chargement de la page, on actualise le lieu et ses infos correspondant à la ville affichée
    chargementLieuVille();

    //Quand on change la ville dans le select, la liste des lieux disponibles changent et les infos du lieu également
    changeVilleSelect();

    //quand on clique sur le +, on masque les infos rempli de lieu et on decouvre des champs text permettant l'ajout d'un nouveau lieu
    cliqueSurBoutonDeroulerNouveauLieu();
    annulerAjoutLieu();

    //Quand on clique sur le bouton nouveau lieu, on declenche les verifications et l'ajout d'un nouveau lieu
    ajoutLieuAjax();

    //Quand on change le lieu, les infos (rue, latitude, longitude) changent
    changeLieuSelect();

    //Revenir sur la page d'accueil
    annulerSortie();

});

function annulerAjoutLieu(){
    $("#ouvrirAjoutLieu").click(function (e) {
        e.preventDefault();
        $(".divAjoutLieu").toggle("fast");
        $(".divLieu").toggle("fast");
    });
}

function annulerSortie(){
    $("#annulerSortie").click(function (e){
        e.preventDefault();
        $(location).attr("href", $("#annulerSortie").attr("data-path"))
        console.log($("#annulerSortie").attr("data-path"));
    })
}

function chargementLieuVille(){

    let idVilleSelect = $("#idVilleSelect").attr("data");
    console.log(idVilleSelect);
    $("#form_sortie_Ville option[value=" + idVilleSelect + "]").prop("selected", true);
    $("#form_sortie_Rue").prop('disabled', true);
    $("#form_sortie_Latitude").prop('disabled', true);
    $("#form_sortie_Longitude").prop('disabled', true);

}

function ajoutLieuAjax(){

    $("#ajoutLieu").click(function (event){
        event.preventDefault();
        let nom = $("#form_lieu_nom").val();
        let rue = $("#form_lieu_rue").val();
        let latitude = $("#form_lieu_latitude").val();
        let longitude = $("#form_lieu_longitude").val();
        let idVille = $("#form_lieu_villes_no_ville option:selected").val();
        let token = $("#form_lieu__token").val();
        console.log(latitude);
        console.log(longitude);
        $.ajax({
            "type": "POST",
            "url": $(".divAjoutLieu").attr("data-path"),
            "data": {
                "nom": nom,
                "rue": rue,
                "latitude": latitude,
                "longitude": longitude,
                "idVille": idVille,
                "token": token
            }
        })
            .done(function (Response){
                console.log(Response);
                console.log("tutu");
                let lieu = Response;
                $("#form_sortie_lieux_no_lieu").append('<option value="'+lieu.id+'">'+lieu.nom+'</option>');
                $("#form_sortie_lieux_no_lieu option[value=" + lieu.id + "]").prop("selected", true);
                $("#form_sortie_Rue").val(lieu.rue);
                $("#form_sortie_Latitude").val(lieu.latitude);
                $("#form_sortie_Longitude").val(lieu.longitude);
                $("#form_sortie_Ville option[value=" + lieu.villesNoVille.id + "]").prop("selected", true);

                $(".divAjoutLieu").toggle("fast");
                $(".divLieu").toggle("fast");
                $(".erreur").empty();
                $("#form_lieu_nom").val("");
                $("#form_lieu_rue").val("");
                $("#form_lieu_latitude").val("");
                $("#form_lieu_longitude").val("");

            })
            .fail(function (Response){
                console.log(Response.responseJSON);
                let erreurs = Response.responseJSON;
                $(".erreur").remove();
                $(".divAjoutLieu").prepend('<div class="erreur"></div>');
                for (let i=0; i<erreurs.length;i++ ){
                    console.log(erreurs[i]);
                    $(".erreur").prepend('<p>' + erreurs[i] +'</p>');
                }

            })
    })

}

function changeVilleSelect(){

    $("#form_sortie_Ville").change(function (){
        let selVal = ($("#form_sortie_Ville option:selected").val());

        ajax($(".divLieu").attr("data-path")+selVal, function (){
            // console.log(this.responseText);
            let lieux = JSON.parse(this.responseText);
            console.log(lieux);
            $("#form_sortie_lieux_no_lieu").empty();
            for (let i=0; i<lieux.length;i++ ){
                console.log(lieux[i].nom);
                $("#form_sortie_lieux_no_lieu").append('<option value="'+lieux[i].id+'">'+lieux[i].nom+'</option>');
            }
            if(lieux.length == 0){
                $("#form_sortie_Rue").val("");
                $("#form_sortie_Latitude").val("");
                $("#form_sortie_Longitude").val("");
            }else
            {
                $("#form_sortie_Rue").val(lieux[0].rue);
                $("#form_sortie_Latitude").val(lieux[0].latitude);
                $("#form_sortie_Longitude").val(lieux[0].longitude);
            }

        })
    });

}

function changeLieuSelect(){

    $("#form_sortie_lieux_no_lieu").change(function () {
        let selVal = ($("#form_sortie_lieux_no_lieu option:selected").val());

        ajax($(".sortieForm").attr("data-path")+selVal, function (){
            // console.log(this.responseText);
            let lieu = JSON.parse(this.responseText);
            console.log(lieu);
            $("#form_sortie_Rue").val(lieu.rue);
            $("#form_sortie_Latitude").val(lieu.latitude);
            $("#form_sortie_Longitude").val(lieu.longitude);
        })
    });

}

function cliqueSurBoutonDeroulerNouveauLieu(){

    $("#annulerLieu").click(function (e) {
        e.preventDefault();
        $(".divAjoutLieu").toggle("fast");
        $(".divLieu").toggle("fast");
    });

}