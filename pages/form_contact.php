<?php

    session_start();

    if (!isset($_SESSION['Identifiant'])){
        $_SESSION['Identifiant'] = 'vide';
    }
    if (!isset($_SESSION['mdp'])){
        $_SESSION['mdp'] = 'vide';
    }

    $curPageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);

    if ($curPageName == "index.php") {
        $lien = "./";
    } else {
        $lien = "./../";
    }

?>


<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="./../css/fonts.css">
        <link rel="stylesheet" href="./../css/header_footer.css">
        <link rel="stylesheet" href="./../css/contact.css">
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <title>Retro-Games</title>
        <link rel="shortcut icon" type="image/ico" href="./../img/icone.png">
    </head>

    <body>

        <?php
            /* importation header */
            include $lien.'pages/header.php';
        ?>

            <img id="ecco_deco" src="./../img/ecco_deco.png" alt="Ecco le dauphin">
            <img id="kefka_deco" src="./../img/kefka_deco.png" alt="Kefka Final Fantasy 6">
            <img id="assassin_deco" src="./../img/assassin_deco.png" alt="Ezzio AC2">

            <section id="section-1">
                <img id="haut-etagere" src="./../img/shelf-header.jpg" alt="haut étagère">
                <div class="non-log">
                    <div class="form-gauche">
                        <h4>Formulaire de contact</h4>
                        <form id="form_contact" method="POST" onsubmit="return valider_form_contact()" action="./../pages/gest_contact.php">
                            <div class="formulaire-contact-champ">
                                <label for="nom">Nom<span class="rouge">*</span></label>
                                <input id="nom" name="nom" type="text" onkeyup="validate(this)" pattern="^[A-Za-z]{1}[A-Za-z0-9]{5,49}$" maxlength="50" placeholder="Nom" required="">
                            </div>
                            <div class="formulaire-contact-champ">
                                <label for="prenom">Prénom<span class="rouge">*</span></label>
                                <input id="prenom" name="prenom" type="text" onkeyup="validate(this)" pattern="^[A-Za-z]{1}[A-Za-z0-9]{5,49}$" maxlength="50" placeholder="Prénom" 
                                            required="">
                            </div>
                            <div class="formulaire-contact-champ">
                                <label for="email">Adresse e-mail<span class="rouge">*</span></label>
                                <input id="email" name="email" type="mail" onkeyup="validate(this)" pattern="^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$" 
                                        maxlength="255" placeholder="monAdresseMail@gmail.com" required="">
                            </div>
                            <div class="formulaire-contact-champ">
                                <label for="message" >Message<span class="rouge">*</span></label>
                                <textarea type="text" id="message" name="message" placeholder="Mon message" maxlength="500" onkeyup="validate(this)" required></textarea>
                            </div>
                            <p><span class="rouge">*</span> Champs obligatoires.</p>
                            <button class="btn-valide-form-connexion bouton" value="Submit">
                                <img src="./../img/bouton_formulaire_coche.svg" alt="Icon coche">
                                <span>Valider</span>
                            </button>
                        </form>
                    </div><!--
                    --><div class="form-droite">
                        <img src="./../img/link_deco.png" alt="Link"></img>
                    </div>
                </div>
            </section>

        <?php
            /* imporation du footer */
            include $lien.'pages/footer.php';
        ?>

        <script src="./../js/contact.js"></script>
        <script src="./../js/index.js"></script>
    </body>
</html>