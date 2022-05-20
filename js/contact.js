/* fichier javascript en mode strict */
"use strict"; 

function valider_form_contact(){

    let nom = document.getElementById('nom').value
    let prenom = document.getElementById('prenom').value
    let message = document.getElementById('message').value
    let email = document.getElementById('mail').value

    let masqueEmail = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
    let emailValide = email.match(masqueEmail);

    if (!empty(nom)){
        alert("Merci de saisir votre nom de famille.");
        return false;
    }

    if (!empty(prenom)){
        alert("Merci de saisir votre prénom.");
        return false;
    }

    if (!empty(email)){
        alert("Merci de saisir votre adresse e-mail.");
        return false;
    }

    if (emailValide == null) {
        alert("Votre adresse email ne semble pas valide.");
        return false;
    }

    return true;

}


function validate(o) {
    if (o.value.indexOf('<') > -1)
        o.value = o.value.replace('<', '');
    if (o.value.indexOf('>') > -1)
        o.value = o.value.replace('>', '');
}