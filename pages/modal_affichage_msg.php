<?php

    session_start();

    if(!empty($_POST) && array_key_exists("id_msg", $_POST)){

        $id_msg = $_POST['id_msg'];

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

                $sth = $conn2->prepare("SELECT * FROM messages WHERE Id_msg = :id_msg");
                $sth->bindParam(':id_msg', $id_msg);
                $sth->execute();
                //Retourne un tableau associatif pour chaque entrée de notre table avec le nom des colonnes sélectionnées en clefs
                $messages = $sth->fetchAll(PDO::FETCH_ASSOC);

                foreach ($messages as $message) {

                    echo '  <form class="form-ajout" method="post" enctype="multipart/form-data" onsubmit="" action="./../pages/form_msg.php">

                                <div class="row mt-1 mb-3">
                                    <div class="col">
                                        <label for="id_msg" class="form-label">Id : </label>
                                        <label class="form-label">'.$message['Id_msg'].'</label>
                                        <input type="text" class="form-control d-none" id="id_msg" name="id_msg" value="'.$message['Id_msg'].'">
                                    </div>
                                    <div class="col">
                                        <label for="dte_msg" class="form-label">Date : </label>
                                        <label class="form-label">'.$message['Dte_msg'].'</label>
                                        <input type="text" class="form-control d-none" id="dte_msg" name="dte_msg" value="'.$message['Dte_msg'].'">
                                    </div>
                                </div>
                                <div class="row mt-1 mb-3">
                                    <div class="col">
                                        <label for="nom_msg" class="form-label">Nom : </label>
                                        <label class="form-label">'.$message['Nom_msg'].'</label>
                                        <input type="text" class="form-control d-none" id="nom_msg" name="nom_msg" value="'.$message['Nom_msg'].'">
                                    </div>
                                    <div class="col">
                                        <label for="prenom_msg" class="form-label">Prénom : </label>
                                        <label class="form-label">'.$message['Prenom_msg'].'</label>
                                        <input type="text" class="form-control d-none" id="prenom_msg" name="prenom_msg" value="'.$message['Prenom_msg'].'">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-3">
                                        <label for="mail_msg" class="form-label">Adresse E-mail : </label>
                                        <label class="form-label">'.$message['Mail_msg'].'</label>
                                        <input type="text" class="form-control d-none" id="mail_msg" name="mail_msg" value="'.$message['Mail_msg'].'">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-3">
                                        <label for="msg_msg" class="form-label">Message : </label>
                                        <label class="form-label">'.$message['Msg_msg'].'</label>
                                        <textarea type="text" class="form-control d-none" id="msg_msg" name="msg_msg">'.$message['Msg_msg'].'</textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-3">
                                        <label for="rep_eff_msg" class="form-label">Réponse effectuée : </label>';

                                        if ($message['Rep_eff_msg']==0) {
                                            echo '<label class="form-label">Non</label>';
                                        } else {
                                            echo '<label class="form-label">Oui</label>';
                                        }

                    echo '              <input type="text" class="form-control d-none" id="rep_eff_msg" name="rep_eff_msg" value="'.$message['Rep_eff_msg'].'">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-center"">
                                    <button type="submit" class="btn btn-primary">Répondre</button>
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