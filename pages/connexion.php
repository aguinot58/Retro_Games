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
        <link rel="stylesheet" href="./../css/connexion.css">
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

        <img id="streets_deco" src="./../img/streets_deco.png" alt="Axel Streets of Rage">
        <img id="rayman_deco" src="./../img/rayman_deco.png" alt="Rayman">
        <img id="starfox_deco" src="./../img/starfox_deco.png" alt="Starfox">

        <section id="section-1">
            <img id="haut-etagere" src="./../img/shelf-header.jpg" alt="haut étagère">
            <article class="conteneur-form-login">
                <form class="form-connexion" method="POST" action="login.php">
                    <label>Login</label>
                    <input type="text" id="login" name="login" maxlength="25" placeholder="Identifiant" required=""/>
                    <label>Password</label>
                    <input type="password" id="password" name="password" placeholder="Mot de passe" required=""/>
                    <button class="btn-valide-form-connexion" value="Submit"><img src="./../img/bouton_formulaire_coche.svg" alt="Icon coche"></button>
                </form>
            </article>
        </section>

        <?php
            /* imporation du footer */
            include $lien.'pages/footer.php';
        ?>
    </body>
</html>