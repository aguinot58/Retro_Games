/* fichier javascript en mode strict */
"use strict"; 

function valider(){

    /* validation formulaire inscription.php */
    let masqueIdentifiant = /^[A-Z]{1}[A-Za-z0-9]{6,39}$/;
    let masqueMdp = /^(?=.*[a-zA-Z])(?=.*\d)(?=.*[!@#$%^&*()_+])[A-Za-z\d][A-Za-z\d!@#$%^&*()_+]{7,19}$/;
    let masqueEmail = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;

    let identifiantValide = document.getElementById('identifiant').value.match(masqueIdentifiant);
    let emailValide = document.getElementById('mail').value.match(masqueEmail);
    let mdpValide = document.getElementById('mdp').value.match(masqueMdp);

    if (document.getElementById('mdp').value != document.getElementById('conf_mdp').value) {
        alert("Les deux mot de passe saisis ne sont pas identiques.");
        return false;
    }

    if (emailValide == null) {
        alert("Votre adresse email ne semble pas valide.");
        return false;
    }

    if (identifiantValide == null) {
        alert("Votre identifiant doit commencer par une lettre, ne peut contenir que des lettres ou chiffres et doit faire entre 6 et 30 caractères");
        return false;
    }

    if (mdpValide == null) {
        alert("Votre mot de passe doit contenir au moins 1 majuscule, 1 chiffre, 1 caractère spécial et doit faire entre 7 et 20 caractères");
        return false;
    }

    let ident_user = document.getElementById('identifiant').value;
    let donnees = {"ident_user": ident_user};

    fetch_post('./verif_exist_user.php', donnees).then(function(response) {

        if(response=='Utilisateur déjà existant'){

            alert("Cet identifiant est déjà présent dans notre base de données - annulation");
            return false;

        } else if (response=='Erreur connexion bdd - annulation') {

            alert(reponse);
            return false;

        } else if (response=='Erreur vérification existence identifiant - annulation') {

            alert(reponse);
            return false;

        } else if (response=='Erreur identifiant - annulation') {

            alert("Une erreur est survenue concernant votre identifiant.");
            return false;

        } else if (response=='Identifiant disponible') {

            return true;

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