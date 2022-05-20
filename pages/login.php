<?php

    session_start();

    $curPageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);

    if ($curPageName == "index.php") {
        $lien = "./";
    } else {
        $lien = "./../";
    }

    require $lien.'pages/fonctions.php';

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
        
        try{

            /* Connexion à une base de données en PDO */
            $configs = include($lien.'pages/config.php');
            $servername = $configs['servername'];
            $username = $configs['username'];
            $password = $configs['password'];
            $db = $configs['database'];

            $pepper = $configs['pepper'];
            $pwd_peppered = hash_hmac("sha256", $mdp, $pepper);

            //On établit la connexion
            $conn = new PDO("mysql:host=$servername;dbname=$db", $username, $password);
            //On définit le mode d'erreur de PDO sur Exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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
                //echo 'Impossible de traiter les données. Erreur : '.$e->getMessage();
                write_error_log("./../log/error_log_login.txt","Echec extraction mdp login.", $e);
                echo 'Une erreur est survenue, merci de réessayer ultérieurement.';

                /*Fermeture de la connexion à la base de données*/
                $sth = null;
                $conn = null;
            }
        }
        catch(PDOException $e){
            // erreur de connexion à la bdd
            //echo "Erreur : " . $e->getMessage();
            write_error_log("./../log/error_log_login.txt","Impossible de se connecter à la base de données.", $e);
            echo 'Une erreur est survenue, merci de réessayer ultérieurement.';
        }
    }

?>