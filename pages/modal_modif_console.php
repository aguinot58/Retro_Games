<?php

    session_start();

    if(!empty($_POST) && array_key_exists("id_cat", $_POST)){

        $id_cat = $_POST['id_cat'];

        $curPageName = substr($_SERVER["SCRIPT_NAME"],strripos($_SERVER["SCRIPT_NAME"],"/")+1);

        if ($curPageName == "index.php"){
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

                $sth = $conn2->prepare("SELECT * FROM categories WHERE Id_cat = $id_cat");
                $sth->execute();

                $consoles = $sth->fetchALL(PDO::FETCH_ASSOC);


                foreach ($consoles as $console) {

                    echo '<form class="form-ajout" method="post" enctype="multipart/form-data" onsubmit="return valider_console2()" action="./../pages/update_console.php">
                                        <div class="row mt-1 mb-3">
                                            <div class="col">
                                                <label for="nom_console2" class="form-label">Nom</label>
                                                <input type="text" class="form-control" id="nom_console2" name="nom_console2" value="'.$console['Nom_cat'].'"> 
                                                <div id="emailHelp" class="form-text">50 caractères maximum.</div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <div id="input_file" class="d-none row">
                                                    <label for="img_logo2" class="form-label">Logo</label>
                                                    <div class="col">
                                                        <input class="form-control" type="file" id="img_logo2" name="img_logo2">
                                                    </div>
                                                </div>    
                                                <div id="input_text" class="row">
                                                    <label for="img_logo3" class="form-label">Logo</label>
                                                    <div class="col">
                                                        <input type="text" class="form-control" id="img_logo3" name="img_logo3" value="'.$console['Logo_cat'].'">
                                                    </div>
                                                    <div class="col">
                                                        <button type="button" class="btn btn-primary" id="btn-modif-contenu" onclick="Modif_contenu_page()">Modifier</button>
                                                    </div>
                                                </div>  
                                                <div id="emailHelp" class="form-text"> Pas d\'apostrophe dans le nom - remplacer les espaces par des underscores - format png.</div>
                                                <div id="input_file2" class="d-none row">
                                                    <label for="img_console2" class="form-label">Image console</label>
                                                    <div class="col">
                                                        <input class="form-control" type="file" id="img_console2" name="img_console2">
                                                    </div>
                                                </div>
                                                <div id="input_text2" class="row">
                                                    <label for="img_console3" class="form-label">Image console</label>
                                                    <div class="col">
                                                        <input type="text" class="form-control" id="img_console3" name="img_console3" value="'.$console['Img_cat'].'">
                                                    </div>
                                                    <div class="row-5">
                                                        <button type="button" class="btn btn-primary" id="btn-modif-contenu" onclick="Modif_contenu_page2()">Modifier</button>
                                                    </div>
                                                </div>
                                                    <div id="emailHelp" class="form-text"> Pas d\'apostrophe dans le nom - remplacer les espaces par des underscores - format png.</div>
                                                
                                            </div>
                                        </div>
                                        <div class="col">
                                            <label for="etat_cat" class="form-label">Visibilité de la console sur le site</label>
                                            <select id="etat_cat" class="form-select" name="etat_cat" aria-label="Default select example">
                                                <option>Choix<option>';

                                                switch($console['Etat_cat']){
                                                    case 0:
                                                        echo '<option selected> 0 </option>
                                                                <option> 1 </option>';
                                                        break;
                                                    case 1:
                                                        echo '<option> 0 </option>
                                                                <option selected> 1 </option>';
                                                        break;
                                                }

                                            '</select>
                                                <div id="mailHelp" class="form-text">0 = "effacé" / 1 = "visible"</div>
                                        </div>
                                    
                                        <div class="row d-flex justify-content-center mb-3">';


                                

                                            echo '<div class="col-1">
                                                    <label for="id_cat" class"invisible">Id de la console</label>
                                                    <input type"text" class="form-control invisible" id="id_cat" name="id_cat" value="'.$id_cat.'">
                                                </div>
                                                <div class="d-flex justify-content-center">
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
        echo 'pb id_cat';
    }

?>