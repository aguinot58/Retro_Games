/* fichier javascript en mode strict */
"use strict"; 


function valider_mail(){

    let ancien_mdp = document.getElementById('ancien_mdp').value;
    let nouveau_mdp = document.getElementById('nouv_mdp').value;
    let conf_mdp = document.getElementById('conf_mdp').value;

    let masqueMdp = /^(?=.*[a-zA-Z])(?=.*\d)(?=.*[!@#$%^&*()_+])[A-Za-z\d][A-Za-z\d!@#$%^&*()_+]{7,19}$/;
    let mdpValide = nouveau_mdp.match(masqueMdp);

    if (empty(ancien_mdp)) {

        alert("Merci de saisir votre ancien mot de passe.");
        return false;

    } else if (empty(nouv_mdp)) {

        alert("Merci de saisir un nouveau mot de passe.");
        return false;

    } else if (empty(conf_mdp)) {

        alert("Merci de confirmer votre nouveau mot de passe.");
        return false;

    } else if (nouveau_mmdp != conf_mdp) {

        alert("Le nouveau mot de passe et le mot de passe de confirmation ne correspondent pas.");
        return false;

    } else if (nouveau_mmdp == ancien_mdp) {

        alert("Le nouveau mot de passe est identique à l'ancien mot de passe.");
        return false;

    } else if (mdpValide==null) {

        alert("Le nouveau mot de passe ne respecte pas le format demandé.");
        return false;

    } else {

        if (confirm("Etes-vous certain de vouloir modifier votre mot de passe ?") == true) {

            return true;
    
        } else {
    
            return false;
    
        }

    }

}


function valider_mail(){

    let ancien_mail = document.getElementById('ancien_mail').value;
    let nouveau_mail = document.getElementById('nouv_mail').value;
    let conf_mail = document.getElementById('conf_mail').value;

    let masqueEmail = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
    let emailValide = nouveau_mail.match(masqueEmail);

    if (empty(nouveau_mail)) {

        alert("Merci de saisir une nouvelle adresse e-mail.");
        return false;

    } else if (empty(conf_mail)) {

        alert("Merci de confirmer votre nouvelle adresse e-mail.");
        return false;

    } else if (nouveau_mail != conf_mail) {

        alert("La nouvelle adresse e-mail et l'adresse de confirmation ne correspondent pas.");
        return false;

    } else if (emailValide==null) {

        alert("La nouvelle adresse e-mail saisie n'est pas au bon format.");
        return false;

    } else if (ancien_mail==nouveau_mail) {

        alert("La nouvelle adresse e-mail est identique à l'ancienne.");
        return false;
        
    } else {

        if (confirm("Etes-vous certain de vouloir modifier votre adresse e-mail ?") == true) {

            return true;
    
        } else {
    
            return false;
    
        }

    }

}


function valider_suppr(){

    if (confirm("Etes-vous certain de vouloir supprimer votre profil ?") == true) {

        return true;

    } else {

        return false;

    }
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

