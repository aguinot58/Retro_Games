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

    $ident_user = valid_donnees($_POST["ident_user"]);
    $id_user = valid_donnees($_POST["id_user"]);
    $dte_rep = valid_donnees($_POST["dte_msg"]);
    $id_msg = valid_donnees($_POST["id_msg"]);
    $msg_rep = valid_donnees($_POST["msg_rep"]);

    /* Connexion à une base de données en PDO */
    $configs = include($lien.'pages/config.php');
    $servername = $configs['servername'];
    $username = $configs['username'];
    $password = $configs['password'];
    $db = $configs['database'];
    //On établit la connexion
    try{
        $conn4 = new PDO("mysql:host=$servername;dbname=$db;charset=UTF8", $username, $password);
        //On définit le mode d\'erreur de PDO sur Exception
        $conn4->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $conn4->beginTransaction();

        try {

            // insert table reponses
            // update table messages

            // envoi mail try/catch
            // si réussite : commit à la fin du try
            // si échec : rollback dans le catch

            // on insère les données dans la table reponses
            $sth = $conn4->prepare("INSERT INTO reponses (Id_msg, Id_user, Msg_rep, Dte_rep) VALUES
                        (:id_msg, :id_user, :msg_rep, :dte_rep)");  
            $sth->bindParam(':id_msg', $id_msg);
            $sth->bindParam(':id_user', $id_user); 
            $sth->bindParam(':msg_rep', $msg_rep); 
            $sth->bindParam(':dte_rep', $dte_rep); 
            $sth->execute();

            //On met à jour les données dans la table messages
            $sth = $conn4->prepare("UPDATE messages set Rep_eff_msg=1 WHERE Id_msg = :id_msg");  
            $sth->bindParam(':id_msg', $id_msg);
            $sth->execute();

            try{
                // on réalise l'envoi du mail.

                // tout d'abord, on extrait les données de la table messages pour le message auquel on répond
                $sth = $conn4->prepare("SELECT * FROM messages where Id_msg=:id_msg");
                $sth->bindParam(':id_msg', $id_msg);
                $sth->execute();
                //Retourne un tableau associatif pour chaque entrée de notre table avec le nom des colonnes sélectionnées en clefs
                $messages = $sth->fetchAll(PDO::FETCH_ASSOC);

                foreach ($messages as $message) {

                    $nom_dest = $message['Nom_msg'];
                    $prenom_dest = $message['Prenom_msg'];
                    $msg_msg = $message['Msg_msg'];
                    $destinataire = $message['Mail_msg'];
                    $dte_msg = $message['Dte_msg'];
                    break;
                
                };

                // On extrait l'adresse mail de la personne qui répond pour la définir comme adresse expéditeur
                // on doit récupérer l'id nouvellement créée du jeu que l'on vient d'injecter pour alimenter la table gestion_jeux
                $sth = $conn4->prepare("SELECT Mail_user FROM utilisateurs where Id_user = :id_user");
                $sth->bindParam(':id_user', $id_user);
                $sth->execute();
                $expediteur = $sth->fetchColumn();

                $objet_rep = "Réponse au message de contact n°".$id_msg;

                // on prépare l'en-tête du mail de réponse (format txt)
                $headers  = 'MIME-Version: 1.0' . "\n"; // Version MIME
                $headers .= 'Reply-To: '.$expediteur."\n"; // Mail de reponse
                $headers .= 'From: "Nom_de_expediteur"<'.$expediteur.'>'."\n"; // Expediteur
                $headers .= 'Delivered-to: '.$destinataire."\n"; // Destinataire
                //$headers .= 'Cc: '.$copie."\n"; // Copie Cc
                //$headers .= 'Bcc: '.$copie_cachee."\n\n"; // Copie cachée Bcc  

                // envoi du mail
                if (mail($destinataire, $objet_rep, $msg_rep, $headers)){

                    // envoi réussi, on fait le commit de nos modifications des tables reponses et messages
                    $conn4->commit();
            
                    /*Fermeture de la connexion à la base de données*/
                    $sth = null;
                    $conn4 = null;

                    $_SESSION['envoi_rep'] = true;

                    //On renvoie l'utilisateur vers la page d'administration des messages
                    header("Location:./../pages/back_msg.php");

                } else {

                    $conn4->rollBack();
            
                    date_default_timezone_set('Europe/Paris');
                    setlocale(LC_TIME, ['fr', 'fra', 'fr_FR']);
                    $format1 = '%A %d %B %Y %H:%M:%S';
                    $date1 = strftime($format1);
                    $fichier = fopen('./../log/error_log_send_msg.txt', 'c+b');
                    fseek($fichier, filesize('./../log/error_log_send_msg.txt'));
                    fwrite($fichier, "\n\n" .$date1. " - Erreur envoi mail réponse. Erreur : " .$e);
                    fclose($fichier);

                    /*Fermeture de la connexion à la base de données*/
                    $sth = null;
                    $conn4 = null;   

                }

            }
            catch(PDOException $e){

                $conn4->rollBack();
            
                date_default_timezone_set('Europe/Paris');
                setlocale(LC_TIME, ['fr', 'fra', 'fr_FR']);
                $format1 = '%A %d %B %Y %H:%M:%S';
                $date1 = strftime($format1);
                $fichier = fopen('./../log/error_log_send_msg.txt', 'c+b');
                fseek($fichier, filesize('./../log/error_log_send_msg.txt'));
                fwrite($fichier, "\n\n" .$date1. " - Erreur extract données mail réponse. Erreur : " .$e);
                fclose($fichier);

                /*Fermeture de la connexion à la base de données*/
                $sth = null;
                $conn4 = null;   

            }

        }
        catch(PDOException $e){

            $conn4->rollBack();
            
            date_default_timezone_set('Europe/Paris');
            setlocale(LC_TIME, ['fr', 'fra', 'fr_FR']);
            $format1 = '%A %d %B %Y %H:%M:%S';
            $date1 = strftime($format1);
            $fichier = fopen('./../log/error_log_send_msg.txt', 'c+b');
            fseek($fichier, filesize('./../log/error_log_send_msg.txt'));
            fwrite($fichier, "\n\n" .$date1. " - Erreur injection et/ou update durant l'envoi de la réponse. Erreur : " .$e);
            fclose($fichier);

            /*Fermeture de la connexion à la base de données*/
            $sth = null;
            $conn4 = null;    
        } 

    }
    /*On capture les exceptions et si une exception est lancée, on écrit dans un fichier log
    *les informations relatives à celle-ci*/
    catch(PDOException $e){
    //echo "Erreur : " . $e->getMessage();
    date_default_timezone_set('Europe/Paris');
    setlocale(LC_TIME, ['fr', 'fra', 'fr_FR']);
    $format1 = '%A %d %B %Y %H:%M:%S';
    $date1 = strftime($format1);
    $fichier = fopen('./../log/error_log_send_msg.txt', 'c+b');
    fseek($fichier, filesize('./../log/error_log_send_msg.txt'));
    fwrite($fichier, "\n\n" .$date1. " - Impossible de se connecter à la base de données. Erreur : " .$e);
    fclose($fichier);
        
    echo   '<article class="container">
                <p>Une erreur est survenue lors de la connexion à la base de données.<br><br>
                    Merci de rafraichir la page, et si le problème persiste, de réessayer ultérieurement.   </p>
            </article>';
    }    

?>