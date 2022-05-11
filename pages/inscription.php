<?php
    session_start();

    if (!isset($_SESSION['logged'])){
        $_SESSION['logged'] = 'non';
    }

    if (!isset($_SESSION['admin'])){
        $_SESSION['admin'] = 'non';
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
        <link rel="stylesheet" href="./../css/inscription.css">
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <title>Retro-Games</title>
        <link rel="shortcut icon" type="image/ico" href="./../img/icone.png">
    </head>

    <body>
        <main>
            <?php
                /* importation header */
                include $lien.'pages/header.php';
            ?>

            <img id="jim_deco" src="./../img/jim_deco.png" alt="Earthworm Jim">
            <img id="spyro_deco" src="./../img/spyro_deco.png" alt="Spyro le dragon">
            <img id="chrono_deco" src="./../img/chrono_deco.png" alt="Chrono de Chrono Trigger">

            <section id="section-1">
                <div class="non-log">
                    <div class="form-gauche">
                        <h4>Formulaire d'inscription</h4>
                        <form method="POST" onsubmit="return valider()" action="./../pages/register.php">
                            <div class="formulaire-contact-champ">
                                <label for="identifiant">Identifiant<span class="rouge">*</span></label>
                                <input id="identifiant" name="identifiant" type="text" pattern="^[A-Za-z]{1}[A-Za-z0-9]{5,29}$" maxlength="40" placeholder="identifiant" 
                                            title="Doit commencer par une lettre et ne peut contenir que des lettres ou des chiffres - 6 caractères minimum" required="">
                            </div>
                            <div class="formulaire-contact-champ">
                                <label for="mdp">Mot de passe<span class="rouge">*</span></label>
                                <input id="mdp" name="mdp" type="password" pattern="^(?=.*[a-zA-Z])(?=.*\d)(?=.*[!@#$%^&*()_+])[A-Za-z\d][A-Za-z\d!@#$%^&*()_+]{7,19}$" 
                                            maxlength="40" placeholder="Mot de passe" 
                                            title="Doit contenir au moins 1 majuscule, 1 chiffre et un caractère spécial - 8 caractères minimum" required="">
                            </div>
                            <div class="formulaire-contact-champ">
                                <label for="conf_mdp">Confirmer le mot de passe<span class="rouge">*</span></label>
                                <input id="conf_mdp" name="conf_mdp" type="password" pattern="^(?=.*[a-zA-Z])(?=.*\d)(?=.*[!@#$%^&*()_+])[A-Za-z\d][A-Za-z\d!@#$%^&*()_+]{7,19}$" 
                                            maxlength="40" placeholder="Confirmer le mot de passe" 
                                            title="Doit cotenir au moins 1 majuscule, 1 chiffre et un caractère spécial - 8 caractères minimum" required="">
                            </div>
                            <div class="formulaire-contact-champ">
                                <label for="mail">Adresse e-mail<span class="rouge">*</span></label>
                                <input id="mail" name="email" type="mail" pattern="^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$" 
                                        maxlength="255" placeholder="monAdresseMail@gmail.com" required="">
                            </div>
                            <p><span class="rouge">*</span> Champs obligatoires.</p>
                            <button class="btn-valide-form-connexion bouton" value="Submit">
                                <img src="./../img/bouton_formulaire_coche.svg" alt="Icon coche">
                                <span>Valider</span>
                            </button>
                        </form>
                    </div><!--
                    --><div class="form-droite">
                        <img src="./../img/sephiroth_deco.png" alt="Sephiroth"></img>
                    </div>
                </div>
            </section>

            <?php
                /* imporation du footer */
                include $lien.'pages/footer.php';
            ?>

        </main>
        <script src="./../js/inscription.js"></script>
    </body>
</html>




