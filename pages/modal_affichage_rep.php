<?php

    session_start();

    if(!empty($_POST) && array_key_exists("id_rep", $_POST)){

        $id_rep = $_POST['id_rep'];

        $curPageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);

        if ($curPageName == "index.php") {
            $lien = "./";
        } else {
            $lien = "./../";
        }

        /* Connexion à une base de données en PDO */
        $configs = include($lien.'pages/config.php'); 
        $servername = $configs['servername'];
        $username = $configs['username'];
        $password = $configs['password'];
        $db = $configs['database'];
        //On établit la connexion
        try{
            $conn2 = new PDO("mysql:host=$servername;dbname=$db;charset=UTF8", $username, $password);
            //On définit le mode d'erreur de PDO sur Exception
            $conn2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            try{

                $sth = $conn2->prepare("SELECT * FROM reponses WHERE Id_rep = :id_rep");
                $sth->bindParam(':id_rep', $id_rep);
                $sth->execute();
                //Retourne un tableau associatif pour chaque entrée de notre table avec le nom des colonnes sélectionnées en clefs
                $reponses = $sth->fetchAll(PDO::FETCH_ASSOC);

                foreach ($reponses as $reponse) {

                    // on profite de récupérer les infos de la réponse pour extraire certaines informations sur l'auteur de la réponse 
                    $sth = $conn2->prepare("SELECT * FROM utilisateurs WHERE Id_user = :id_user");
                    $sth->bindParam(':id_user', $reponse['Id_user']);
                    $sth->execute();
                    //Retourne un tableau associatif pour chaque entrée de notre table avec le nom des colonnes sélectionnées en clefs
                    $auteurs = $sth->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($auteurs as $auteur) {

                        $identifiant_user = $auteur['Ident_user'];
                        $mail_expe = $auteur['Mail_user'];
                        break;
                    }

                    echo '  <form class="form-ajout" method="post" enctype="multipart/form-data" onsubmit="" action="./../pages/form_msg.php">

                                <div class="row mt-1 mb-3">
                                    <div class="col">
                                        <label for="id_rep" class="form-label">Id : </label>
                                        <label class="form-label">'.$reponse['Id_rep'].'</label>
                                        <input type="text" class="form-control d-none" id="id_rep" name="id_rep" value="'.$reponse['Id_rep'].'">
                                    </div>
                                    <div class="col">
                                        <label for="dte_rep" class="form-label">Date : </label>
                                        <label class="form-label">'.$reponse['Dte_rep'].'</label>
                                        <input type="text" class="form-control d-none" id="dte_rep" name="dte_rep" value="'.$reponse['Dte_rep'].'">
                                    </div>
                                </div>
                                <div class="row mt-1 mb-3">
                                    <div class="col">
                                        <label for="id_user" class="form-label">Auteur : </label>
                                        <label class="form-label">'.$identifiant_user.'</label>
                                        <input type="text" class="form-control d-none" id="id_user" name="id_user" value="'.$identifiant_user.'">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-3">
                                        <label for="mail_rep" class="form-label">Adresse E-mail : </label>
                                        <label class="form-label">'.$mail_expe.'</label>
                                        <input type="text" class="form-control d-none" id="mail_rep" name="mail_rep" value="'.$mail_expe.'">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-3">
                                        <label for="msg_rep" class="form-label">Message : </label>
                                        <label class="form-label">'.$reponse['Msg_rep'].'</label>
                                        <textarea type="text" class="form-control d-none" id="msg_rep" name="msg_rep">'.$reponse['Msg_rep'].'</textarea>
                                    </div>
                                </div>

                            </form>';

                    /*Fermeture de la connexion à la base de données*/
                    $sth = null;
                    $conn2 = null;

                    break;

                }

            }
            catch(PDOException $e){
                                
            date_default_timezone_set('Europe/Paris');
            setlocale(LC_TIME, ['fr', 'fra', 'fr_FR']);
            $format1 = '%A %d %B %Y %H:%M:%S';
            $date1 = strftime($format1);
            $fichier = fopen('./../log/error_log_modal_consult_msg.txt', 'c+b');
            fseek($fichier, filesize('./../log/error_log_modal_consult_msg.txt'));
            fwrite($fichier, "\n\n" .$date1. " - Erreur import données message. Erreur : " .$e);
            fclose($fichier);

            /*Fermeture de la connexion à la base de données*/
            $sth = null;
            $conn2 = null;    
            }

        }
        /*On capture les exceptions et si une exception est lancée, on écrit dans un fichier log
        *les informations relatives à celle-ci*/
        catch(PDOException $e){
        date_default_timezone_set('Europe/Paris');
        setlocale(LC_TIME, ['fr', 'fra', 'fr_FR']);
        $format1 = '%A %d %B %Y %H:%M:%S';
        $date1 = strftime($format1);
        $fichier = fopen('./../log/error_log_modal_consult_msg.txt', 'c+b');
        fseek($fichier, filesize('./../log/error_log_modal_consult_msg.txt'));
        fwrite($fichier, "\n\n" .$date1. " - Impossible de se connecter à la base de données - Erreur : " .$e);
        fclose($fichier);
                                
        echo   '<article class="container">
                    <p>Une erreur est survenue lors de la connexion à la base de données.<br><br>
                    Merci de rafraichir la page, et si le problème persiste, de réessayer ultérieurement.   </p>
                </article>';
        }

    }else{
        echo 'pb id_msg';
    }

?>