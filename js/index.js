/* fichier javascript en mode strict */
"use strict"; 

function page_jeux(event){

    let id_jeux = event.target.id;

    var nom = window.location.pathname;
    nom = nom.split("/");
    nom = nom[nom.length - 1];
    nom = nom.substr(0, nom.lastIndexOf("."));
    nom = nom.replace(new RegExp("(%20|_|-)", "g"), "");

    if (nom=="index") {
        window.location.href = "./pages/jeux.php?id_jeux="+id_jeux;
    } else {
        window.location.href = "./../pages/jeux.php?id_jeux="+id_jeux;
    }

}


function page_console(event){

    let nom_console = event.target.id;

    var nom = window.location.pathname;
    nom = nom.split("/");
    nom = nom[nom.length - 1];
    nom = nom.substr(0, nom.lastIndexOf("."));
    nom = nom.replace(new RegExp("(%20|_|-)", "g"), "");

    if (nom=="index") {
        window.location.href = "./pages/categorie.php?console="+nom_console;
    } else {
        window.location.href = "./../pages/categorie.php?console="+nom_console;
    }

}