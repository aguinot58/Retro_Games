<?php

    session_start();

    if(!empty($_POST) && array_key_exists("id_user", $_POST)){

        $id_user = $_POST['id_user'];

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

                $sth = $conn2->prepare("SELECT * FROM utilisateurs WHERE Id_user = :id_user");
                $sth->bindParam(':id_user', $id_user);
                $sth->execute();
                //Retourne un tableau associatif pour chaque entrée de notre table avec le nom des colonnes sélectionnées en clefs
                $users = $sth->fetchAll(PDO::FETCH_ASSOC);

                foreach ($users as $user) {

                    echo '  <form class="form-ajout" method="post" enctype="multipart/form-data" onsubmit="return valider_user()" action="./../pages/update_user.php">
                                <div class="row mt-1 mb-3">
                                    <div class="col">
                                        <label for="nom_user" class="form-label">Identifiant</label>
                                        <input type="text" class="form-control" id="nom_user" name="nom_user" value="'.$user['Ident_user'].'">
                                    <div id="emailHelp" class="form-text">50 caractères maximum.</div>
                                </div>

                                <div class="col">
                                    <label for="etat_user" class="form-label">Visibilité sur le site</label>
                                    <select id="etat_user" class="form-select" name="etat_user" aria-label="Default select example">
                                        <option>Choix</option>';

                                            switch($user['Etat_user']) {
                                                case 0:
                                                    echo '<option selected> 0 </option>
                                                            <option> 1 </option>';
                                                    break;
                                                case 1:
                                                    echo '<option> 0 </option>
                                                            <option  selected> 1 </option>';
                                                    break;
                                            }

                    echo           '</select>
                                    <div id="emailHelp" class="form-text">0 = "effacé" / 1 = "visible"</div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="mb-3">
                                        <label for="mail_user" class="form-label">Adresse E-mail</label>
                                        <input type="text" class="form-control" id="mail_user" name="mail_user" value="'.$user['Mail_user'].'">
                                        <div id="emailHelp" class="form-text">255 caractères maximum.</div>
                                        </div>
                                    </div>
                                </div>
                                

                                    <div class="row mt-3 mb-3">
                                        <div class="col-3">
                                            <label for="niv_admin" class="form-label">Niveau Administration</label>
                                            <select id="niv_admin" class="form-select" name="niv_admin" aria-label="Default select example">
                                                <option>Choix</option>';
                                                
                                                    try{

                                                        //Sélectionne les valeurs dans les colonnes pour chaque entrée de la table
                                                        $sth = $conn2->prepare("SELECT Niv_admin FROM admin WHERE Id_user=:id_user");
                                                        $sth->bindParam(':id_user', $id_user);
                                                        $sth->execute();
                                                        //Retourne un tableau associatif pour chaque entrée de notre table avec le nom des colonnes sélectionnées en clefs
                                                        $niv_admin = $sth->fetchColumn();

                                                        // on remplit la liste de sélection de console
                                                        for ($i=1; $i<=3; $i++) {

                                                            if ($niv_admin == $i){
                                                                echo '<option selected value="'.$i.'">'.$i.'</option>';
                                                            } else {
                                                                echo '<option value="'.$i.'">'.$i.'</option>';
                                                            }
                                                        }
                                                    }
                                                    catch(PDOException $e){
                                            
                                                        date_default_timezone_set('Europe/Paris');
                                                        setlocale(LC_TIME, ['fr', 'fra', 'fr_FR']);
                                                        $format1 = '%A %d %B %Y %H:%M:%S';
                                                        $date1 = strftime($format1);
                                                        $fichier = fopen('./../log/error_log_modal_modif_user.txt', 'c+b');
                                                        fseek($fichier, filesize('./../log/error_log_modal_modif_user.txt'));
                                                        fwrite($fichier, "\n\n" .$date1. " - Erreur import liste niv_admin. Erreur : " .$e);
                                                        fclose($fichier);
                                        
                                                        //Fermeture de la connexion à la base de données
                                                        $sth = null;
                                                        $conn2 = null;    
                                                    }   
                                                            
                    echo                    '</select>
                                        </div>
                                    </div>
                                                            
                                <div class="col-1">
                                    <label for="id_user" class="invisible">Id utilisateur</label>
                                    <input type="text" class="form-control invisible" id="id_user" name="id_user" value="'.$id_user.'">
                                </div>

                                <div class="d-flex justify-content-center"">
                                    <button type="submit" class="btn btn-primary">Sauvegarder</button>
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
            $fichier = fopen('./../log/error_log_modal_modif_user.txt', 'c+b');
            fseek($fichier, filesize('./../log/error_log_modal_modif_user.txt'));
            fwrite($fichier, "\n\n" .$date1. " - Erreur import données utilisateur. Erreur : " .$e);
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
        $fichier = fopen('./../log/error_log_modal_modif_user.txt', 'c+b');
        fseek($fichier, filesize('./../log/error_log_modal_modif_user.txt'));
        fwrite($fichier, "\n\n" .$date1. " - Impossible de se connecter à la base de données - Erreur : " .$e);
        fclose($fichier);
                                
        echo   '<article class="container">
                    <p>Une erreur est survenue lors de la connexion à la base de données.<br><br>
                    Merci de rafraichir la page, et si le problème persiste, de réessayer ultérieurement.   </p>
                </article>';
        }

    }else{
        echo 'pb id_user';
    }

?>