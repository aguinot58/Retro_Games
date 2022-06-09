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

    require $lien.'pages/fonctions.php';
    require $lien.'pages/conn_bdd.php';

?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="./../css/fonts.css">
        <link rel="stylesheet" href="./../css/header_footer.css">
        <link rel="stylesheet" href="./../css/profil.css">
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

        echo '<img id="leonardo_deco" src="'.$lien.'img/leonardo_deco.png" alt="Léonardo tortue ninja">
              <img id="dragon_ball_deco" src="'.$lien.'img/dragon_ball_deco.png" alt="fresque verticale de personnages de dragon ball">
              <img id="mario_kart_deco" src="'.$lien.'img/mario_kart_deco.png" alt="Mario dans un kart">';


            if ($_SESSION['logged'] == 'oui' && $_SESSION['admin'] = 'oui') {

                $ident_user = $_SESSION['user'];

                echo '<section id="section-2">
                        <div class="profil">
                            <p>Profil de : '.$ident_user.'</p>
                        </div>
                        <article class="log">
                            
                            <article class="conteneur-form">
                                <form class="form-connexion" method="POST" onsubmit="return valider_mdp()" action="'.$lien.'pages/maj_mdp_user.php">
                                    <label>Ancien mot de passe</label>
                                    <input type="password" id="ancien_mdp" name="ancien_mdp" placeholder="Ancien mot de passe" required=""/>
                                    <label>Nouveau mot de passe</label>
                                    <input type="password" id="nouv_mdp" name="nouv_mdp" placeholder="Nouveau mot de passe" required=""/>
                                    <label>Confirmer mot de passe</label>
                                    <input type="password" id="conf_mdp" name="conf_mdp" placeholder="Confirmer mot de passe" required=""/>
                                    <button class="btn-valide-form-connexion bouton" value="Submit">
                                        <img src="./../img/bouton_formulaire_coche.svg" alt="Icon coche">
                                        <span>Mettre à jour</span>
                                    </button>
                                </form>
                            </article>

                            <article class="conteneur-form">
                                <form class="form-connexion" method="POST" onsubmit="return valider_mail()" action="'.$lien.'pages/maj_mail_user.php">
                                    <label>Ancienne adresse e-mail</label>';
                            
                                    try{

                                        $sth = $conn->prepare("SELECT Mail_user FROM utilisateurs where Ident_user = '$ident_user'");
                                        $sth->execute();
                                        $ancien_mail = $sth->fetchColumn();

                                        echo        '<input type="text" id="ancien_mail" name="ancien_mail" placeholder="Ancien e-mail" value="'.$ancien_mail.'" required=""/>';

                                        /*Fermeture de la connexion à la base de données*/
                                        $sth = null;
                                        $conn = null;

                                    }
                                    /*On capture les exceptions si une exception est lancée et on affiche
                                    *les informations relatives à celle-ci*/
                                    catch(PDOException $e){
                                        write_error_log("./../log/error_log_profil.txt","Echec extraction adresse e-mail.", $e);
                                        echo 'Une erreur est survenue, merci de réessayer ultérieurement.';
                            
                                        /*Fermeture de la connexion à la base de données*/
                                        $sth = null;
                                        $conn = null;
                                    }

                            echo   '<label>Nouvelle adresse e-mail</label>
                                    <input type="text" id="nouv_mail" name="nouv_mail" placeholder="Nouvel e-mail" required=""/>
                                    <label>Confirmer adresse e-mail</label>
                                    <input type="text" id="conf_mail" name="conf_mail" placeholder="Confirmer e-mail" required=""/>
                                    <button class="btn-valide-form-connexion bouton" value="Submit">
                                        <img src="./../img/bouton_formulaire_coche.svg" alt="Icon coche">
                                        <span>Mettre à jour</span>
                                    </button>
                                </form>
                            </article>

                            <article class="conteneur-form">
                                <form class="form-connexion" method="POST" onsubmit="return valider_suppr()" action="'.$lien.'pages/suppr_user.php">
                                    <label>Supprimer votre compte ?</label>
                                    <button class="btn-valide-form-connexion bouton" value="Submit">
                                        <img src="./../img/bouton_formulaire_coche.svg" alt="Icon coche">
                                        <span>Supprimer</span>
                                    </button>
                                </form>
                            </article>

                        </article>
                    </section>';

            } else {

                echo '<section id="section-1">
                        <img id="haut-etagere" src="./../img/shelf-header.jpg" alt="haut étagère">
                        <article class="non-log">
                            <h1 >Merci de vous connecter à votre compte.</h1>
                        </article>
                    </section>';

            }

        ?>
        
        <?php
            /* imporation du footer */
            include $lien.'pages/footer.php';
        ?>

        <script src="./../js/verification.js"></script>
        <script src="./../js/index.js"></script>
    </body>
</html>