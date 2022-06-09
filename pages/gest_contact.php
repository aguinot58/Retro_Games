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

    $nom = valid_donnees($_POST["nom"]);
    $prenom = valid_donnees($_POST["prenom"]);
    $message = valid_donnees($_POST["message"]);
    $email = valid_donnees($_POST["email"]);

    if (!empty($nom) && !empty($prenom) && !empty($message) && !empty($email) && (filter_var($email, FILTER_VALIDATE_EMAIL)) &&
        strlen($nom)<=50 && strlen($prenom)<=50 && strlen($email)<=255 && strlen($message)<=500) {

        try{

            date_default_timezone_set('Europe/Paris');
            setlocale(LC_TIME, ['fr', 'fra', 'fr_FR']);
            $format1 = '%d/%m/%Y-%H:%M:%S';
            $date = strftime($format1);

            //On insère une partie des données reçues dans la table utilisateur
            $sth = $conn->prepare("INSERT INTO messages (Nom_msg, Prenom_msg, Mail_msg, Msg_msg, Dte_msg) VALUES(:nom, :prenom, :mail, :msg, :date_j)");
            $sth->bindParam(':nom', $nom);    
            $sth->bindParam(':prenom', $prenom);
            $sth->bindParam(':mail', $email);
            $sth->bindParam(':msg', $message); 
            $sth->bindParam(':date_j', $date); 
            $sth->execute();
            
            /*Fermeture de la connexion à la base de données*/
            $sth = null;
            $conn = null;

            //On renvoie l'utilisateur vers la page de remerciement
            header("Location:".$lien."pages/rem_contact.php");

            }
            catch(PDOException $e){
                //echo 'Impossible de traiter les données. Erreur : '.$e->getMessage();
                write_error_log("./../log/error_log_gest_contact.txt","Impossible d'injecter les données.", $e);
                echo 'Une erreur est survenue, merci de réessayer ultérieurement.';

                /*Fermeture de la connexion à la base de données*/
                $sth = null;
                $conn = null;
            }

    } else {
        //echo 'pb de données';
        echo 'Une erreur est survenue, merci de vérifier les informations saisies.';
    }

?>