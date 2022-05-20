/* fichier javascript en mode strict */
"use strict";

function valider_console(){

    let nom_console = document.getElementById('nom_console').value;
    let img_logo = document.getElementById('img_logo').value;
    let img_console = document.getElementById('img_console').value;

    let tbl_format_img_logo = img_logo.split(".");
    let tbl_format_img_console = img_console.split(".");


    if (nom_console==""){
        alert("merci de saisir un nom de console");
        return false;
    } else if (img_logo==""){
        alert("merci de sélectionner un logo de console");
        return false;
    } else if (img_console==""){
        alert("merci de sélectionner une image de console");
        return false;
    } else if (tbl_format_img_logo[1]!="jpg" && tbl_format_img_logo[1]!="jpeg" && tbl_format_img_logo[1]!="png"){
        alert("merci de sélectionner un logo au format jpg ou png");
        return false;
    }else if (tbl_format_img_console[1]!="jpg" && tbl_format_img_console[1]!="jpeg" && tbl_format_img_console[1]!="png"){
        alert("merci de sélectionner une image de console au format jpg ou png");
    } else {
        return true;
    }


    let tbl_class_IF = document.getElementById('input_file').className;
    let id_comp= "";

    if ((tbl_class_IF.indexOf('d-none') > -1) == true){
        id_comp = "img_logo2";
    } else {
        id_comp = "img_logo";
    }


    if ((tbl_class_IF.indexOf('d-none') > -1) == true){
        id_comp = "img_console2";
    } else {
        id_comp = "img_console";
    }
    
}

$(document).on("click",".open_modal", function (){
    var MyId = $(this).data('id');
    let donnees = {"id_cat": MyId};

    fetch_post('./modal_modif_console.php', donnees).then(function(response){

        document.getElementById('affichage_modal').innerHTML = response;

        var myModal = new bootstrap.Modal(document.getElementById("Modif_Modal"));

        myModal.show();
    });
});

function Modif_contenu_page(){

    let tbl_class_IF = document.getElementById('input_file').className;

    if ((tbl_class_IF.indexOf('d-none') > -1) == true){

        document.getElementById('input_file').setAttribute ("class","d-flex row");
        document.getElementById('input_text').setAttribute ("class","d-none row");
        document.getElementById('img_logo3').value = "" ;

    } else {

        document.getElementById('input_file').setAttribute ("class","d-none row");
        document.getElementById('input_text').setAttribute ("class"," d-flex row");

    }

}

function Modif_contenu_page2(){

    let tbl_class_IF = document.getElementById('input_file2').className;
        
    if ((tbl_class_IF.indexOf('d-none') > -1) == true){
        
        document.getElementById('input_file2').setAttribute ("class", "d-flex row");
        document.getElementById('input_text2').setAttribute ("class", "d-none row");
        document.getElementById('img_console3').value = "";
    } else {
        
        document.getElementById('input_file2').setAttribute ("class", "d-none row");
        document.getElementById('input_text2').setAttribute ("class", "d-flex row");
        
    }
}


function valider_console2(){

    let id_cat = document.getElementById('id_cat2').value;
    let nom_console = document.getElementById('nom_console2').value;
  

    let tbl_class_IF = document.getElementById('input_file').className;
    let id_comp= "";

    if ((tbl_class_IF.indexOf('d-none') > -1) == true){
        id_comp = "img_logo3";
    } else {
        id_comp = "img_logo2";
    }

    if ((tbl_class_IF.indexOf('d-none') > -1) == true){
        id_comp = "img_console3";
    } else {
        id_comp = "img_console2";
    }

    let img_logo = document.getElementById(id_comp).value;
    let img_console = document.getElementById(id_comp).value;
    let tbl_format_img_logo = img_logo.split(".");
    let tbl_format_img_console = img_console.split(".");




    if(id_cat==""){
        alert("merci de saisir l'id de la console");
        return false;
    }else if(nom_console==""){
        alert("merci de saisir un nom de console");
        return false;
    } else if (img_logo==""){
        alert("merci de sélectionner un logo de console");
        return false;
    } else if (img_console==""){
        alert("merci de sélectionner une image de console");
        return false;
    } else if (tbl_format_img_logo[1]!="jpg" && tbl_format_img_logo[1]!="jpeg" && tbl_format_img_logo[1]!="png"){
        alert("merci de sélectionner un logo au format jpg ou png");
        return false;
    }else if (tbl_format_img_console[1]!="jpg" && tbl_format_img_console[1]!="jpeg" && tbl_format_img_console[1]!="png"){
        alert("merci de sélectionner une image de console au format jpg ou png");
    } else {
        return true;
    }
}









function Suppr_console(event){

    let type_element= event.target.id;

    let id_bouton = "";

    if (type_element ==""){
        id_bouton = event.target.name;
    }else {
        id_bouton = event.target.id;
    }

    let tb_split_id = id_bouton.split("_");
    let id_console = tb_split_id[1];
    let donnees = {"id_cat": id_console};

    fetch_post('./suppr_console.php', donnees).then(function(response){

        if(response=='suppression reussie'){

            alert('consolée supprimé !');
            window.location.href = "back_consoles.php";


        }else if (response=='erreur suppression console'){

            alert('echec de la suppression de la console - annulation');

        }else if (response=='echec connexion bdd'){

            alert('echec de la connexion à la base de données - annulation');


        }else if (response=='test if echec'){

            alert('Echec identification de la console - annulation');

        }

    });
}



function data(data){

    let text = "";

    for (var key in data) {
        text += key + "=" + data[key] + "&";
    }

    return text.trim("&");
}


function fetch_post(url, dataArray) {

    let dataObject = data(dataArray);

    return fetch(url, {
                method: "post",
                headers: {
                    "content-type": "application/x-www-form-urlencoded",
                },
                body: dataObject,
    })
    .then((response) => response.text())
    .catch((error) => console.error("error:", error));
}