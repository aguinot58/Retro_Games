<?php

    session_start();

    $curPageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);

    if ($curPageName == "index.php") {
        $lien = "./";
    } else {
        $lien = "./../";
    }

    if (isset($_POST["page"])) {
        $page_no = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
        if(!is_numeric($page_no))
            die("Error fetching data! Invalid page number!!!");
    } else {
        $page_no = 1;
    }

    require $lien.'pages/conn_bdd.php';
    $row_limit = 10;

    // get record starting position
    $start = (($page_no-1) * $row_limit);

    if ($_SESSION['niv_admin'] == 3){

        $results = $conn->prepare("SELECT * FROM jeux WHERE Etat_jeux = 1 ORDER BY Id_jeux LIMIT $start, $row_limit");

    } else {

        $ident_user = filter_var($_POST["ident_user"]);

        $sth = $conn->prepare("SELECT Id_user FROM utilisateurs where Ident_user = $ident_user");
        $sth->execute();
        $id_user = $sth->fetchColumn();

        $sth = $conn->prepare("SELECT * FROM jeux INNER JOIN gestion_jeux ON jeux.Id_jeux = gestion_jeux.Id_jeux and gestion_jeux.Id_user = $id_user WHERE Etat_jeux = 1 ORDER BY Id_jeux LIMIT $start, $row_limit");
    }

    $results->execute();

    while($row = $results->fetch(PDO::FETCH_ASSOC)) {

        $timestamp = strtotime($row['Date_jeux']); 
        $date_bon_format = date("d-m-Y", $timestamp );

        echo '<tr>
            <th scope="row" class="align-middle text-center">'.$row['Id_jeux'].'</th>
            <td class="align-middle text-center">'.$row['Nom_jeux'].'</td>
            <td class="align-middle text-center">'.$row['Cat_jeux'].'</td>
            <td class="align-middle text-center">'.$row['Dev_jeux'].'</td>
            <td class="align-middle text-center">'.$row['Editeur_jeux'].'</td>
            <td class="align-middle text-center">'.$date_bon_format.'</td>
            <td class="align-middle text-center">'.$row['Img_jeux'].'</td>
            <td class="align-middle text-center">'.$row['Etat_jeux'].'</td>
            <td class="align-middle text-center">
                <div class="d-flex flex-row">
                    <div>
                        <button type="button" class="btn open_modal" data-id="'.$row['Id_jeux'].'" name="mod_'.$row['Id_jeux'].'">
                            <i name="mod_'.$row['Id_jeux'].'" class="fas fa-pen" data-id="'.$row['Id_jeux'].'" id="mod_'.$row['Id_jeux'].'"></i>
                        </button>
                    </div>
                    <div >
                        <button type="button" class="btn" onclick="Suppr_jeu(event)" name="del_'.$row['Id_jeux'].'">
                            <i name="del_'.$row['Id_jeux'].'" class="fas fa-trash-can" id="del_'.$row['Id_jeux'].'"></i>
                        </button>
                    </div>
                </div>
            </td>
        </tr>';
    }

?>