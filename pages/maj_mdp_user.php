<?php

    session_start();

    $curPageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);

    if ($curPageName == "index.php") {
        $lien = "./";
    } else {
        $lien = "./../";
    }

    require $lien.'pages/fonctions.php';
    require $lien.'pages/conn_bdd.php';

    if(!empty($_POST)){

        $ident_user = $_SESSION['user'];
        $ancien_mdp = valid_donnees($_POST['ancien_mdp']);
        $nouveau_mdp = valid_donnees($_POST['nouv_mdp']);
        $conf_mdp = valid_donnees($_POST['conf_mdp']);

        if (!empty($ident_user) && !empty($ancien_mdp) && !empty($conf_mdp) && !empty($nouveau_mdp) && 
        preg_match("/^[A-Z]{1}[A-Za-z0-9]{6,39}$/", $ident_user) &&
        preg_match("/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[!@#$%^&*()_+])[A-Za-z\d][A-Za-z\d!@#$%^&*()_+]{7,19}$/", $nouveau_mdp)) {

            if (($nouveau_mdp === $conf_mdp)) {

                    try{

                        $ancien_pwd_peppered = hash_hmac("sha256", $ancien_mdp, $pepper);

                        $sth = $conn->prepare("SELECT Mdp_user FROM utilisateurs where Ident_user = '$ident_user'");
                        $sth->execute();
                        $ancien_mdp_actuel = $sth->fetchColumn();

                        echo $ancien_pwd_peppered.'<br>';
                        echo $ancien_mdp_actuel.'<br>';

                        if (password_verify($ancien_pwd_peppered, $ancien_mdp_actuel)==false) {

                            echo 'Votre mot de passe actuel ne correspond pas à celui présent dans la base de donnée - annulation';
        
                            /*Fermeture de la connexion à la base de données*/
                            $sth = null;
                            $conn = null;

                        } else {

                            $nouveau_pwd_peppered = hash_hmac("sha256", $nouveau_mdp, $pepper);
                            $nouveau_pwd_hashed = password_hash($nouveau_pwd_peppered, PASSWORD_ARGON2ID);

                            //On met à jour les données reçues dans la table jeux
                            $sth = $conn->prepare("UPDATE utilisateurs set Mdp_user=:nouveau_mdp WHERE Ident_user = :ident_user");
                            $sth->bindParam(':nouveau_mdp', $nouveau_pwd_hashed);    
                            $sth->bindParam(':ident_user', $ident_user);
                            $sth->execute();

                            /*Fermeture de la connexion à la base de données*/
                            $sth = null;
                            $conn = null;

                            header("Location:./../pages/profil.php");

                        }

                    }
                    catch(PDOException $e){                  
                        date_default_timezone_set('Europe/Paris');
                        setlocale(LC_TIME, ['fr', 'fra', 'fr_FR']);
                        $format1 = '%A %d %B %Y %H:%M:%S';
                        $date1 = strftime($format1);
                        $fichier = fopen('./../log/error_log_maj_mdp_user.txt', 'c+b');
                        fseek($fichier, filesize('./../log/error_log_maj_mdp_user.txt'));
                        fwrite($fichier, "\n\n" .$date1. " - Erreur maj mdp user. Erreur : " .$e);
                        fclose($fichier);

                        /*Fermeture de la connexion à la base de données*/
                        $sth = null;
                        $conn = null;
                        
                        echo   'Erreur maj mdp utilisateur - annulation';
                    }

            } else {
                echo 'Nouveau mdp et mdp de confirmation différents - annulation';
            }

        } else {
            echo 'Champs vide(s) ou problème de format au niveau du mdp - annulation';
        }

    } else {
        echo 'Erreur données transmises - annulation';
    } 

?>