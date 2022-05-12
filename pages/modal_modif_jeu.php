<?php

    session_start();

    if(!empty($_POST) && array_key_exists("id_jeu", $_POST)){

        $id_jeu = $_POST['id_jeu'];

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

                $sth = $conn2->prepare("SELECT * FROM jeux INNER JOIN gestion_jeux ON jeux.Id_jeux = gestion_jeux.Id_jeux and jeux.Id_jeux = $id_jeu");
                $sth->execute();
                //Retourne un tableau associatif pour chaque entrée de notre table avec le nom des colonnes sélectionnées en clefs
                $jeux = $sth->fetchAll(PDO::FETCH_ASSOC);

                foreach ($jeux as $jeu) {

                    $timestamp = strtotime($jeu['Date_jeux']); 
                    $date_bon_format = date("d-m-Y", $timestamp );

                    echo '  <form class="form-ajout" method="post" enctype="multipart/form-data" onsubmit="return valider_jeu2()" action="./../pages/update_jeu.php">
                                        <div class="row mt-1 mb-3">
                                            <div class="col">
                                                <label for="nom_jeux2" class="form-label">Nom</label>
                                                <input type="text" class="form-control" id="nom_jeux2" name="nom_jeux2" value="'.$jeu['Nom_jeux'].'">
                                                <div id="emailHelp" class="form-text">50 caractères maximum.</div>
                                            </div>
                                            <div class="col">
                                                <label for="date_jeux2" class="form-label">Date sortie</label>
                                                <input type="text" class="form-control" id="date_jeux2" name="date_jeux2" value="'.$date_bon_format.'">
                                                <div id="emailHelp" class="form-text">Format jj-mm-aaaa</div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="dev_jeux2" class="form-label">Développeur</label>
                                                <input type="text" class="form-control" id="dev_jeux2" name="dev_jeux2" value="'.$jeu['Dev_jeux'].'">
                                                <div id="emailHelp" class="form-text">50 caractères maximum.</div>
                                            </div>
                                            <div class="col">
                                                <label for="edit_jeux2" class="form-label">Editeur</label>
                                                <input type="text" class="form-control" id="edit_jeux2" name="edit_jeux2" value="'.$jeu['Editeur_jeux'].'">
                                                <div id="emailHelp" class="form-text">50 caractères maximum.</div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="mb-3">
                                                <label for="desc_jeux2" class="form-label">Description</label>
                                                <textarea type="text" class="form-control" id="desc_jeux2" name="desc_jeux2">'.$jeu['Desc_jeux'].'</textarea>
                                                <div id="emailHelp" class="form-text">500 caractères maximum.</div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <div id="input_file" class="d-none row">
                                                    <label for="img_jeux2" class="form-label">Jaquette</label>
                                                    <div class="col">
                                                        <input class="form-control" type="file" id="img_jeux2" name="img_jeux2">
                                                    </div>
                                                </div>
                                                <div id="input_text" class="row">
                                                    <label for="img_jeux3" class="form-label">Jaquette</label>
                                                    <div class="col">
                                                        <input type="text" class="form-control" id="img_jeux3" name="img_jeux3" value="'.$jeu['Img_jeux'].'">
                                                    </div>
                                                    <div class="col">
                                                        <button type="button" class="btn btn-primary" id="btn-modif-contenu" onclick="Modif_contenu_page()">Modifier</button>
                                                    </div>
                                                </div>
                                                <div id="emailHelp" class="form-text">Pas d\'apostrophe dans le nom - remplacer les espaces par des underscores - format jpg/jpeg/png.</div>
                                            </div>
                                        </div>
                                        <div class="row mt-3 mb-3">
                                            <div class="col">
                                                <label for="cat_jeux2" class="form-label">Plate-forme</label>
                                                <select id="cat_jeux2" class="form-select" name="cat_jeux2" aria-label="Default select example">
                                                    <option>Choix</option>';
                                                
                                                        try{

                                                            //Sélectionne les valeurs dans les colonnes pour chaque entrée de la table
                                                            $sth = $conn2->prepare("SELECT Id_cat, Nom_cat FROM categories ORDER BY Id_cat ASC");
                                                            $sth->execute();
                                                            //Retourne un tableau associatif pour chaque entrée de notre table avec le nom des colonnes sélectionnées en clefs
                                                            $categories = $sth->fetchAll(PDO::FETCH_ASSOC);

                                                            // on remplit la liste de sélection de console
                                                            foreach ($categories as $categorie) {

                                                                if ($categorie['Id_cat'] == $jeu['Cat_jeux']){
                                                                    echo '<option selected value="'.$categorie['Id_cat'].'">'.$categorie['Nom_cat'].'</option>';
                                                                } else {
                                                                    echo '<option value="'.$categorie['Id_cat'].'">'.$categorie['Nom_cat'].'</option>';
                                                                }
                                                            };
                                                        }
                                                        catch(PDOException $e){
                                            
                                                            date_default_timezone_set('Europe/Paris');
                                                            setlocale(LC_TIME, ['fr', 'fra', 'fr_FR']);
                                                            $format1 = '%A %d %B %Y %H:%M:%S';
                                                            $date1 = strftime($format1);
                                                            $fichier = fopen('./../log/error_log_modif_jeux.txt', 'c+b');
                                                            fseek($fichier, filesize('./../log/error_log_modif_jeux.txt'));
                                                            fwrite($fichier, "\n\n" .$date1. " - Erreur import liste catégorie consoles. Erreur : " .$e);
                                                            fclose($fichier);
                                        
                                                            //Fermeture de la connexion à la base de données
                                                            $sth = null;
                                                            $conn2 = null;    
                                                        }
                                                            
                    echo                    '</select>
                                                </div>
                                            <div class="col">
                                                <label for="etat_jeux" class="form-label">Visibilité du jeu sur le site</label>
                                                <select id="etat_jeux" class="form-select" name="etat_jeux" aria-label="Default select example">
                                                    <option>Choix</option>';

                                                    switch($jeu['Etat_jeux']) {
                                                        case 0:
                                                            echo '<option selected> 0 </option>
                                                                    <option> 1 </option>';
                                                            break;
                                                        case 1:
                                                            echo '<option> 0 </option>
                                                                    <option  selected> 1 </option>';
                                                            break;
                                                    }

                    echo                    '</select>
                                                <div id="emailHelp" class="form-text">0 = "effacé" / 1 = "visible"</div>
                                            </div>
                                        </div>

                                        <div class="row d-flex justify-content-center mb-3">';

                                        if ($_SESSION['niv_admin'] == 3){
                                            
                                           echo '<div class="col">
                                                <label for="gest_jeux" class="form-label">Ajouté par </label>
                                                <select id="gest_jeux" class="form-select" name="gest_jeux" aria-label="Default select example">
                                                    <option>Choix</option>';
                                                
                                                        try{

                                                            //Sélectionne les valeurs dans les colonnes pour chaque entrée de la table
                                                            $sth = $conn2->prepare("SELECT Id_user, Ident_user FROM utilisateurs WHERE Id_user IN 
                                                                                    (SELECT Id_user FROM gestion_jeux WHERE Id_jeux = $id_jeu)");
                                                            $sth->execute();
                                                            //Retourne un tableau associatif pour chaque entrée de notre table avec le nom des colonnes sélectionnées en clefs
                                                            $gestions = $sth->fetchAll(PDO::FETCH_ASSOC);

                                                            foreach ($gestions as $gestion) {
                                                                $gestion_id = $gestion['Id_user'];
                                                                $gestion_ident = $gestion['Ident_user'];
                                                            }

                                                            //Sélectionne les valeurs dans les colonnes pour chaque entrée de la table
                                                            $sth = $conn2->prepare("SELECT Id_user, Ident_user FROM utilisateurs");
                                                            $sth->execute();
                                                            //Retourne un tableau associatif pour chaque entrée de notre table avec le nom des colonnes sélectionnées en clefs
                                                            $users = $sth->fetchAll(PDO::FETCH_ASSOC);

                                                            // on remplit la liste de sélection de console
                                                            foreach ($users as $user) {

                                                                if ($user['Id_user'] === $gestion_id){
                                                                    echo '<option selected value="'.$user['Id_user'].'">'.$user['Ident_user'].'</option>';
                                                                } else {
                                                                    echo '<option value="'.$user['Id_user'].'">'.$user['Ident_user'].'</option>';
                                                                }
                                                            };
                                                        }
                                                        catch(PDOException $e){
                                            
                                                            date_default_timezone_set('Europe/Paris');
                                                            setlocale(LC_TIME, ['fr', 'fra', 'fr_FR']);
                                                            $format1 = '%A %d %B %Y %H:%M:%S';
                                                            $date1 = strftime($format1);
                                                            $fichier = fopen('./../log/error_log_modif_jeux.txt', 'c+b');
                                                            fseek($fichier, filesize('./../log/error_log_modif_jeux.txt'));
                                                            fwrite($fichier, "\n\n" .$date1. " - Erreur import liste catégorie consoles. Erreur : " .$e);
                                                            fclose($fichier);
                                        
                                                            //Fermeture de la connexion à la base de données
                                                            $sth = null;
                                                            $conn2 = null;    
                                                        }

                                        echo    '</select>
                                            </div>';

                                        }
                                                            
                                        echo    '<div class="col-1">
                                                    <label for="id_jeux" class="invisible">Id du jeu</label>
                                                    <input type="text" class="form-control invisible" id="id_jeux" name="id_jeux" value="'.$id_jeu.'">
                                                </div>
                                                <div class="d-flex justify-content-center"">
                                                    <button type="submit" class="btn btn-primary">Sauvegarder</button>
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
            $fichier = fopen('./../log/error_log_modif_jeux.txt', 'c+b');
            fseek($fichier, filesize('./../log/error_log_modif_jeux.txt'));
            fwrite($fichier, "\n\n" .$date1. " - Erreur import données jeux. Erreur : " .$e);
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
        $fichier = fopen('./../log/error_log_modif_jeux.txt', 'c+b');
        fseek($fichier, filesize('./../log/error_log_modif_jeux.txt'));
        fwrite($fichier, "\n\n" .$date1. " - Impossible de se connecter à la base de données - Erreur : " .$e);
        fclose($fichier);
                                
        echo   '<article class="container">
                    <p>Une erreur est survenue lors de la connexion à la base de données.<br><br>
                    Merci de rafraichir la page, et si le problème persiste, de réessayer ultérieurement.   </p>
                </article>';
        }

    }else{
        echo 'pb id_jeu';
    }

?>