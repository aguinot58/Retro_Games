/* fichier javascript en mode strict */
"use strict"; 

// on accède aux différentes parties de notre tableau
const tbody = document.querySelector('tbody');
const thx = document.querySelectorAll('th');

// écouteur d'évènement sur toutes les têtes de colonne du tableau
thx.forEach(function(th) { 

    th.addEventListener('click', function() {

        /*
            Pour classer les lignes du tableau, on va utiliser la méthode sort(). Par défaut, cette méthode va trier les valeurs en les convertissant 
            en chaines de caractères et en comparant ces chaines selon l’ordre des points de code Unicode. Cette méthode ne nous convient pas puisqu’elle 
            n’est efficace que pour trier des chaines qui ont des formes semblables (tout en minuscule ou tout en majuscule).

            Heureusement, sort() peut prendre en argument facultatif une fonction de comparaison qui va décrire la façon dont les éléments vont être comparés, 
            ce qui va nous être très utile ici. Cette fonction de comparaison va toujours devoir renvoyer un nombre en valeur de retour qui va décider de l’ordre de tri.

            Par exemple, si v1 et v2 sont deux valeurs à comparer, alors :

                Si fonctionDeComparaison(v1, v2) renvoie une valeur strictement inférieure à 0, v1 sera classé avant v2,
                Si fonctionDeComparaison(v1, v2) renvoie une valeur strictement supérieure à 0, v2 sera classé avant v1,
                Si fonctionDeComparaison(v1, v2) renvoie 0, on laisse v1 et v2 inchangés l’un par rapport à l’autre, mais triés par rapport à tous les autres éléments.

            Pour réinjecter le résultat, on va utiliser forEach() et appendChild().

            La méthode sort() a besoin d’un Array (ou d’un array-like) pour fonctionner. On utilise donc Array.from(trbx) pour créer un Array à partir de notre NodeList.
            Ensuite, pour chaque élément du tableau classé, on utilise appendChild qui va insérer les tr les unes à la suite des autres.

            La méthode sort() transmet ainsi ici automatiquement les différents éléments de Array.from(trxb) (c’est-à-dire les différents tr) 
            à la fonction compare() passée en argument.

            Or, comparer des lignes de tableau deux à deux n’a pas de sens : on veut comparer les valeurs textuelles des td d’une colonne donnée 
            entre les différentes lignes du tableau pour ensuite pouvoir ordonner les lignes entières dans un sens ou dans un autre.

            On va donc ici vouloir passer explicitement l’indice du th lié à la colonne actuellement cliqué afin de définir la colonne de référence 
            utilisée pour le tri ainsi qu’un booléen qui va nous permettre d’inverser le tri (croissant / décroissant) à chaque fois qu’un élément 
            d’en-tête sera cliqué (note : par simplicité, les éléments d’en-tête agissent comme un groupe ici et non pas indépendamment).
        */
        const trxb = tbody.querySelectorAll('tr');

        let classe = Array.from(trxb).sort(compare(Array.from(thx).indexOf(th), this.asc = !this.asc));

        classe.forEach(tr => tbody.appendChild(tr));

        /*
            La partie Array.from(thx).indexOf(th) nous permet de récupérer l’indice du th couramment cliqué. 
            On va se servir ensuite de cet indice pour savoir quelles valeurs comparer dans chaque tr.

            La partie this.asc = !this.asc permet de définir un booléen dont la valeur logique va être inversée à chaque clic sur un élément d’en-tête. 
            Avant le premier clic, this.asc n’est pas défini (et vaut donc false). Lors du premier clic, sa valeur s’inverse et il vaut donc true et etc. 
            Cela va nous permettre ensuite de choisir l’ordre de tri.
        */
      
    });

});



/*
L’idée principale de notre fonction de comparaison est la suivante : on va vouloir obtenir le contenu textuel des cellules de la colonne utilisée 
pour le tri pour les deux lignes passées par sort() et on va vouloir comparer ces deux valeurs textuelles puis renvoyer un nombre à l’issue de 
cette comparaison pour indiquer à sort() l’ordre de tri.

Notre fonction de comparaison va déjà devoir comparer les valeurs textuelles des td d’une colonne pour deux lignes différentes pour ensuite 
pouvoir ordonner les lignes. Il va donc falloir accéder à ces valeurs textuelles. On va pour cela créer une autre fonction qui va prendre une ligne 
et un numéro de colonne en entrée et qui va extraire le contenu textuel de la cellule de tableau relative à l’id passé dans cette ligne.

Maintenant qu’on possède une fonction nous permettant de récupérer le contenu textuel des td, il ne nous reste plus qu’à créer une comparaison 
qui va comparer ces deux valeurs textuelles.
*/
const compare = function(ids, asc){

    return function(row1, row2){

        const tdValue = function(row, ids){
            return row.children[ids].textContent;
        }

        const tri = function(v1, v2){
            if (v1 !== '' && v2 !== '' && !isNaN(v1) && !isNaN(v2)){
               return v1 - v2;
            }
            else {
               return v1.toString().localeCompare(v2);
            }
            return v1 !== '' && v2 !== '' && !isNaN(v1) && !isNaN(v2) ? v1 - v2 : v1.toString().localeCompare(v2);
        };

        return tri(tdValue(asc ? row1 : row2, ids), tdValue(asc ? row2 : row1, ids));
    }
};

/*
Ici, v1 et v2 représentent le contenu textuel des cellules des deux lignes pour une colonne donnée. On veut d’abord traiter deux cas : 
le cas où les cellules contiennent des nombres et le cas où elles contiennent autre chose que des nombres.

Dans le cas où nos deux valeurs sont bien des nombres, on se contente de retourner la différence entre les deux valeurs.

Il faut savoir ici que lorsqu’on passe un argument qui n’est pas du type Number à isNan(), cet argument est d’abord converti de force 
en une valeur de type Number et c’est la valeur résultante qui est utilisée pour déterminer si l’argument est NaN ou pas.

isNan() va notamment renvoyer true pour les valeurs booléennes true et false et pour la chaine de caractères vide. On va donc isoler le cas 
“chaine de caractères vide”. Comme les valeurs récupérées dans le tableau seront transformées en chaine, on n’a pas besoin d’isoler les cas true et false.

Pour tous les cas qui ne rentrent pas dans notre if, on va comparer les deux valeurs avec la méthode localeCompare(). Si la valeur v2 est considérée 
comme se situant après dans l’ordre lexicographique par rapport à v1 par localeCompare(), cette méthode renverra un nombre négatif. 
Dans le cas contraire, un nombre positif sera renvoyé.

En résumé, dans notre if comme dans notre else, si v2 est “plus grand” que v1 , une valeur négative est retournée. Dans le cas contraire, 
une valeur positive est retournée. Si les deux valeurs sont égales, 0 est retourné.

Décortiquons cette dernière ligne de code ensemble. Cette ligne est relativement condensée et contient deux ternaires. 
La partie tdValue(asc ? row1 : row2, ids), tdValue(asc ? row2 : row1, ids) permet de récupérer le contenu textuel d’une cellule de la première ligne 
puis le contenu textuel d’une cellule de la deuxième ligne ou inversement selon que asc soit évalué à true ou pas.

Grosso modo, on va exécuter tdValue(row1, ids) et tdValue(row2, ids) si asc vaut true ou tdValue(row2, ids) et tdValue(row1, ids) si asc vaut false.

Les deux résultats renvoyés par tdValue() (les valeurs textuelles des deux cellules donc) sont ensuite immédiatement passées comme arguments à tri() 
qui va les comparer et renvoyer un nombre. En fonction de si le nombre est positif, négatif ou égal à 0 la méthode sort() va finalement ordonner 
les lignes dans un sens ou dans un autre.

Ici, notre ternaire nous permet finalement de choisir quelle valeur textuelle va être utilisée en v1 et quelle autre va être utilisée en v2, ce qui va 
influer sur le résultat final.

Si asc vaut true, la valeur textuelle de la première colonne sera utilisée comme v1 et la valeur textuelle de la deuxième colonne sera utilisée comme v2.

Or, on a dit plus haut que si v2 est “plus grand” que v1 , une valeur négative est retournée par tri(). Notre fonction compare() renvoie donc 
dans ce cas une fonction function(row1, row2) qui renvoie elle même une valeur négative.

La ligne Array.from(trxb).sort(compare(Array.from(thx).indexOf(th), this.asc = !this.asc) va donc devenir Array.from(trxb).sort(function(row1, row2){return //Une valeur négative} 
et dans ce scénario sort() va classer row1 avant row2.
*/
