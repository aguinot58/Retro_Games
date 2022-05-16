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

            if ($id_msg=="all") {

                $sth = $conn2->prepare("SELECT * FROM reponses");

            } else {

                $sth = $conn2->prepare("SELECT * FROM reponses WHERE Id_msg = :id_msg");
                $sth->bindParam(':id_msg', $id_msg);

            }

            $sth->execute();
            //Retourne un tableau associatif pour chaque entrée de notre table avec le nom des colonnes sélectionnées en clefs
            $reponses = $sth->fetchAll(PDO::FETCH_ASSOC);

            echo '  <table class="table table-striped" id="tableau_user2">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center text-nowrap">Id_rep <img class="fleches" src="'.$lien.'img/up-and-down-arrows.png" alt="flèches de tri"></th>
                                <th scope="col" class="text-center text-nowrap">Id_msg <img class="fleches" src="'.$lien.'img/up-and-down-arrows.png" alt="flèches de tri"></th>
                                <th scope="col" class="text-center text-nowrap">Auteur <img class="fleches" src="'.$lien.'img/up-and-down-arrows.png" alt="flèches de tri"></th>
                                <th scope="col" class="text-center text-nowrap">Date <img class="fleches" src="'.$lien.'img/up-and-down-arrows.png" alt="flèches de tri"></th>
                                <th scope="col" class="text-center text-nowrap">Réponse <img class="fleches" src="'.$lien.'img/up-and-down-arrows.png" alt="flèches de tri"></th>
                                <th scope="col" class="text-center text-nowrap">Outils</th>
                            </tr>
                        </thead>

                        <tbody>';
        
            // on remplit la liste de sélection de console
            foreach ($reponses as $reponse) {
        
                echo        '<tr>
                                <th class="align-middle text-center" scope="row">'.$reponse['Id_rep'].'</th>
                                <td class="align-middle text-center">'.$reponse['Id_msg'].'</td>';

                $sth = $conn2->prepare("SELECT Ident_user FROM utilisateurs WHERE Id_user = :id_user");
                $sth->bindParam(':id_user', $reponse['Id_user']);
                $sth->execute();
                //Retourne un tableau associatif pour chaque entrée de notre table avec le nom des colonnes sélectionnées en clefs
                $ident_user = $sth->fetchColumn();

                echo '          <td class="align-middle text-center">'.$ident_user.'</td>
                                <td class="align-middle text-center">'.$reponse['Dte_rep'].'</td>
                                <td class="align-middle text-center">'.$reponse['Msg_rep'].'</td>
                                <td class="align-middle text-center">
                                    <div class="d-flex flex-row">
                                        <div>
                                            <button type="button" class="btn open_modal_rep" data-id="'.$reponse['Id_rep'].'" name="mod_'.$reponse['Id_rep'].'">
                                                <i name="mod_'.$reponse['Id_rep'].'" class="fas fa-eye" data-id="'.$reponse['Id_rep'].'" id="mod_'.$reponse['Id_rep'].'"></i>
                                            </button>
                                        </div>
                                        <div >
                                            <button type="button" class="btn" onclick="Suppr_rep(event)" name="del_'.$reponse['Id_rep'].'">
                                                <i name="del_'.$reponse['Id_rep'].'" class="fas fa-trash-can" id="del_'.$reponse['Id_rep'].'"></i>
                                            </button>
                                        </div>
                                    </div>
                                </td>';
            };
            
            echo            '</tr>
                        </tbody>
                    </table>';

            /*Fermeture de la connexion à la base de données*/
            $sth = null;
            $conn2 = null;

        }
        /*On capture les exceptions et si une exception est lancée, on écrit dans un fichier log
        *les informations relatives à celle-ci*/
        catch(PDOException $e){
        date_default_timezone_set('Europe/Paris');
        setlocale(LC_TIME, ['fr', 'fra', 'fr_FR']);
        $format1 = '%A %d %B %Y %H:%M:%S';
        $date1 = strftime($format1);
        $fichier = fopen('./../log/error_log_modal_consult_rep.txt', 'c+b');
        fseek($fichier, filesize('./../log/error_log_modal_consult_rep.txt'));
        fwrite($fichier, "\n\n" .$date1. " - Impossible de se connecter à la base de données - Erreur : " .$e);
        fclose($fichier);
                                
        echo   '<article class="container">
                    <p>Une erreur est survenue lors de la connexion à la base de données.<br><br>
                    Merci de rafraichir la page, et si le problème persiste, de réessayer ultérieurement.   </p>
                </article>';
        }    

    } else {

        echo 'pb Id rep';

    }

?>