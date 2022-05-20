/* fichier javascript en mode strict */
"use strict"; 

$(document).on("click", ".open_modal", function () {
    var myId = $(this).data('id');
    let donnees = {"id_user": myId};

    fetch_post('./modal_modif_user.php', donnees).then(function(response) {

        document.getElementById('affichage_modal').innerHTML = response;

        var myModal = new bootstrap.Modal(document.getElementById("Modif_user_Modal"));
        myModal.show();

    });

});

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



function valider_user(){

    let ident_user = document.getElementById('ident_user').value;
    let etat_user = document.getElementById('etat_user').value;
    let id_user = document.getElementById('id_user').value;
    let mail_user = document.getElementById('mail_user').value;
    let niv_admin = document.getElementById('niv_admin').value;

    let masqueIdentifiant = /^[A-Z]{1}[A-Za-z0-9]{6,39}$/;
    let masqueEmail = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;

    let identifiantValide = ident_user.match(masqueIdentifiant);
    let emailValide = mail_user.match(masqueEmail);

    if (ident_user==""){
        alert("Merci de saisir un identifiant");
        return false;
    } else if (mail_user==""){
        alert("Merci de saisir une adresse e-mail");
        return false;
    } else if (etat_user==""){
        alert("Merci de selectionner la visibilité de l'utilisateur");
        return false;
    } else if (etat_user=="Choix"){
        alert("Merci de selectionner une valeur de visibilité de l'utilisateur");
        return false;
    }else if (id_user==""){
        alert("Un problème d'id est survenu");
        return false;
    } else if (niv_admin==""){
        alert("Merci de selectionner le niveau d'administration de l'utilisateur");
        return false;
    }else if (niv_admin=="Choix"){
        alert("Merci de selectionner une valeur de niveau d'administration de l'utilisateur");
        return false;
    } else if (identifiantValide==null){
        alert("L'identifiaint saisi n'est pas au bon format");
        return false;
    } else if (emailValide==null){
        alert("L'adresse e-mail saisie n'est pas au bon format");
        return false;
    } else {
        return true;
    }

}



function Suppr_user(event){

    let type_element= event.target.id;

    let id_bouton = "";

    if (type_element == ""){
        id_bouton = event.target.name;
    } else {
        id_bouton = event.target.id;
    }

    let tb_split_id = id_bouton.split("_");
    let id_user = tb_split_id[1];
    let donnees = {"id_user": id_user};

    fetch_post('./suppr_user.php', donnees).then(function(response) {

        if(response=='suppression reussie'){

            alert('Utilisateur supprimé !');
            window.location.href = "back_utilisateurs.php";


        } else if (response=='erreur suppression jeu') {

            alert('Echec de la suppression de l\'utilisateur - annulation');

        } else if (response=='echec connexion bdd') {

            alert('Echec de la connexion à la base de données - annulation');

        } else if (response=='test if echec') {

            alert('Echec identification de l\'utilisateur - annulation');

        }

    });

}

