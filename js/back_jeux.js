/* fichier javascript en mode strict */
"use strict"; 

function valider_jeu(){

    let nom_jeux = document.getElementById('nom_jeux').value;
    let date_jeux = document.getElementById('date_jeux').value;
    let dev_jeux = document.getElementById('dev_jeux').value;
    let edit_jeux = document.getElementById('edit_jeux').value;
    let desc_jeux = document.getElementById('desc_jeux').value;
    let img_jeux = document.getElementById('img_jeux').value;
    let cat_jeux = document.getElementById('cat_jeux').value;

    let masqueDate = /^(0?\d|[12]\d|3[01])-(0?\d|1[012])-((?:19|20)\d{2})$/;
    let date_valide = date_jeux.match(masqueDate);
    let tbl_format_img = img_jeux.split(".");

    if (nom_jeux==""){
        alert("Merci de saisir un nom de jeu");
        return false;
    } else if (date_jeux==""){
        alert("Merci de saisir la date de sortie du jeu");
        return false;
    } else if (dev_jeux==""){
        alert("Merci de saisir le développeur du jeu");
        return false;
    } else if (edit_jeux==""){
        alert("Merci de saisir l'éditeur du jeu");
        return false;
    } else if (desc_jeux==""){
        alert("Merci de saisir la déscription du jeu");
        return false;
    } else if (img_jeux==""){
        alert("Merci de sélectionner la jaquette du jeu");
        return false;
    } else if (cat_jeux=="Choix"){
        alert("Merci de sélectionner la plate-forme du jeu");
        return false;
    }else if (date_valide==null){
        alert("Merci de saisir une date de sortie au format indiqué");
        return false;
    }else if (tbl_format_img[1]!="jpg" && tbl_format_img[1]!="jpeg" && tbl_format_img[1]!="png"){
        alert("Merci de sélectionner une jaquette au format jpg ou png");
        return false;
    } else {
        return true;
    }

}


$(document).on("click", ".open_modal", function () {
    var myId = $(this).data('id');
    let donnees = {"id_jeu": myId};

    fetch_post('./modal_modif_jeu.php', donnees).then(function(response) {

        document.getElementById('affichage_modal').innerHTML = response;

        var myModal = new bootstrap.Modal(document.getElementById("Modif_Modal"));
        myModal.show();

    });

});


function Modif_contenu_page(){

    let tbl_class_IF = document.getElementById('input_file').className;

    if ((tbl_class_IF.indexOf('d-none') > -1) == true){

        document.getElementById('input_file').setAttribute ("class", "d-flex row");
        document.getElementById('input_text').setAttribute ("class", "d-none row");
        document.getElementById('img_jeux3').value = "";

    } else {

        document.getElementById('input_file').setAttribute ("class", "d-none row");
        document.getElementById('input_text').setAttribute ("class", "d-flex row");

    }

}


function valider_jeu2(){

    let nom_jeux = document.getElementById('nom_jeux2').value;
    let date_jeux = document.getElementById('date_jeux2').value;
    let dev_jeux = document.getElementById('dev_jeux2').value;
    let edit_jeux = document.getElementById('edit_jeux2').value;
    let desc_jeux = document.getElementById('desc_jeux2').value;
    let cat_jeux = document.getElementById('cat_jeux2').value;
    let gestion_jeu = document.getElementById('gest_jeux').value;

    let tbl_class_IF = document.getElementById('input_file').className;
    let id_comp= "";

    if ((tbl_class_IF.indexOf('d-none') > -1) == true){
        id_comp = "img_jeux3";
    } else {
        id_comp = "img_jeux2";
    }

    let img_jeux = document.getElementById(id_comp).value;
    let masqueDate = /^(0?\d|[12]\d|3[01])-(0?\d|1[012])-((?:19|20)\d{2})$/;
    let date_valide = date_jeux.match(masqueDate);
    let tbl_format_img = img_jeux.split(".");

    if (nom_jeux==""){
        alert("Merci de saisir un nom de jeu");
        return false;
    } else if (date_jeux==""){
        alert("Merci de saisir la date de sortie du jeu");
        return false;
    } else if (dev_jeux==""){
        alert("Merci de saisir le développeur du jeu");
        return false;
    } else if (edit_jeux==""){
        alert("Merci de saisir l'éditeur du jeu");
        return false;
    } else if (desc_jeux==""){
        alert("Merci de saisir la déscription du jeu");
        return false;
    } else if (img_jeux==""){
        alert("Merci de sélectionner la jaquette du jeu");
        return false;
    } else if (cat_jeux=="Choix"){
        alert("Merci de sélectionner la plate-forme du jeu");
        return false;
    }else if (date_valide==null){
        alert("Merci de saisir une date de sortie au format indiqué");
        return false;
    }else if (tbl_format_img[1]!="jpg" && tbl_format_img[1]!="jpeg" && tbl_format_img[1]!="png"){
        alert("Merci de sélectionner une jaquette au format jpg ou png");
        return false;
    }else if (gestion_jeu==0){
        alert("Merci de sélectionner un gestionnaire pour le jeu");
        return false;
    } else {

        return true;
    }

}




function Suppr_jeu(event){

    let type_element= event.target.id;

    let id_bouton = "";

    if (type_element == ""){
        id_bouton = event.target.name;
    } else {
        id_bouton = event.target.id;
    }

    let tb_split_id = id_bouton.split("_");
    let id_jeu = tb_split_id[1];
    let donnees = {"id_jeu": id_jeu};

    fetch_post('./suppr_jeu.php', donnees).then(function(response) {

        if(response=='suppression reussie'){

            alert('Jeu supprimé !');
            window.location.href = "back_jeux.php";


        } else if (response=='erreur suppression jeu') {

            alert('Echec de la suppression du jeu - annulation');

        } else if (response=='echec connexion bdd') {

            alert('Echec de la connexion à la base de données - annulation');

        } else if (response=='test if echec') {

            alert('Echec identification du jeu - annulation');

        }

    });

}




function data(data) {

    let text = "";

    for (var key in data) {
      text += key + "=" + data[key] + "&";
    }

    return text.trim("&");
}




function fetch_post(url, dataArray) {

    let dataObject = data(dataArray);

    return fetch(url, {
             method: "post",
             headers: {
                   "Content-Type": "application/x-www-form-urlencoded",
             },
             body: dataObject,
        })
        .then((response) => response.text())
        .catch((error) => console.error("Error:", error));

}


