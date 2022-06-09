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

    $identifiant = valid_donnees($_POST["login"]);
    $mdp = valid_donnees($_POST["password"]);

    if (empty($identifiant)) {

        $_SESSION['Identifiant'] = 'renseigne';
        header("Location:.$lien.pages/connexion.php");

    } elseif (empty($mdp)) {

        $_SESSION['mdp'] = 'renseigne';
        header("Location:.$lien.pages/connexion.php");

    } else {

        $_SESSION['Identifiant'] = 'renseigne';
        $_SESSION['mdp'] = 'renseigne';
        $_SESSION['user']= $identifiant;

        $pwd_peppered = hash_hmac("sha256", $mdp, $pepper);

        try{
                    
            //On extrait le mdp correspondant à l'identifiant
            $sth = $conn->prepare("SELECT Mdp_user FROM utilisateurs where Ident_user = '$identifiant'");
            $sth->execute();
            $mdp_hashed = $sth->fetchColumn();

            if (password_verify($pwd_peppered, $mdp_hashed)) {
                $_SESSION['logged'] = 'oui';

                // on extrait l'id de l'utilisateur
                $sth = $conn->prepare("SELECT Id_user FROM utilisateurs where Ident_user = '$identifiant'");
                $sth->execute();
                $id_user = $sth->fetchColumn();

                // on extrait la valeur de la colonne Admin pour vérifier si l'utilisateur peut accéder au back-office
                $sth = $conn->prepare("SELECT Niv_admin FROM admin where Id_user = '$id_user'");
                $sth->execute();
                $administration = $sth->fetchColumn();

                if ($administration>1){
                    $_SESSION['admin'] = 'oui';
                } else {
                    $_SESSION['admin'] = 'non';
                }

                $_SESSION['niv_admin'] = $administration;

                /*Fermeture de la connexion à la base de données*/
                $sth = null;
                $conn = null;

                header("Location:../index.php");

            } else {

                $_SESSION['logged'] = 'non';

                /*Fermeture de la connexion à la base de données*/
                $sth = null;
                $conn = null;

                header("Location:connexion.php");

            }

        }
        /*On capture les exceptions si une exception est lancée et on affiche
        *les informations relatives à celle-ci*/
        catch(PDOException $e){
            write_error_log("./../log/error_log_login.txt","Echec extraction mdp login.", $e);
            echo 'Une erreur est survenue, merci de réessayer ultérieurement.';
            /*Fermeture de la connexion à la base de données*/
            $sth = null;
            $conn = null;
        }
    }

?>