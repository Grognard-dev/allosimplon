<?php
session_start();
require "securite.php";
ini_set("display_errors","1");
error_reporting(E_ALL);
$config = require "config.php";

$dbh = new PDO($config["dsn"], $config["utilisateur"], $config["mdp"]);

if (isset($_POST['bouton'])){
    $Nom_du_film = empty($_POST['Nom_du_film']) ? null : $_POST['Nom_du_film'];
    $Date_de_sortie = empty($_POST['Date_de_sortie']) ? null : $_POST['Date_de_sortie'];
    $synopsis = empty($_POST['synopsis']) ? null : $_POST['synopsis'];
    
    if ($Nom_du_film === null || $Date_de_sortie === null  || $synopsis === null) {
        $erreur = 'Veuillez remplir tous les champs';
    }else {
        $insertion_film = $dbh->prepare ("INSERT INTO Film (Nom_du_film, Date_de_sortie, synopsis) 
        VALUES (:Nom_du_film, :Date_de_sortie, :synopsis)") ;
        
        $insertion_film->bindValue(':Nom_du_film', $Nom_du_film);
        $insertion_film->bindValue(':Date_de_sortie', $Date_de_sortie);
        $insertion_film->bindValue(':synopsis', $synopsis);
        $insertion_film->execute();
    }
    $insert_genre = $dbh->prepare("INSERT INTO type_film (ID_genre,ID_film) VALUES (:ID_genre,:ID_film)");
    foreach($_POST['types'] as $genres){
        $insert_genre->bindValue(':ID_genre',$genres['ID_genre']);
        $insert_genre->bindValue(':ID_film',$insertion_film['ID']);
        $insert_genre->execute();
    }
    
}
$requete_acteurs = $dbh->prepare("SELECT * FROM Acteur");
$requete_acteurs->execute();
$acteurs_film = $requete_acteurs->fetchAll();
if(isset($_POST['ajout_acteur'])){
    $requete_ajout=$dbh->prepare("INSERT INTO joue (ID_acteur,ID_film) VALUES (:ID_acteur,:ID_film)");
    $requete_ajout->bindValue(':ID_acteur',$_POST['select_acteur']);
    $requete_ajout->bindValue(':ID_film', $_GET['ID']);
    $requete_ajout->execute();
    $_SESSION['flash'] = "Ajout effectué";
    die;
}
$requete_genres = $dbh->prepare("SELECT * FROM Genre");
$requete_genres->execute();
$genres_film = $requete_genres->fetchAll();
if(isset($_POST['ajout_genre'])){
    $requete_ajout=$dbh->prepare("INSERT INTO type_film (ID_Genre,ID_film) VALUES (:ID_Genre,:ID_film)");
    $requete_ajout->bindValue(':ID_Genre',$_POST['select_genre']);
    $requete_ajout->bindValue(':ID_film', $_GET['ID']);
    $requete_ajout->execute();
    $_SESSION['flash'] = "Ajout effectué";
    die;
}
$requete_realisateurs = $dbh->prepare("SELECT * FROM Realisateur");
$requete_realisateurs->execute();
$realisateurs_film = $requete_realisateurs->fetchAll();
if(isset($_POST['ajout_realisateur'])){
    $requete_ajout=$dbh->prepare("INSERT INTO realise (ID_realisateur,ID_film) VALUES (:ID_realisateur,:ID_film)");
    $requete_ajout->bindValue(':ID_realisateur',$_POST['select_realisateur']);
    $requete_ajout->bindValue(':ID_film', $_GET['ID']);
    $requete_ajout->execute();
    $_SESSION['flash'] = "Ajout effectué";
    die;
}
$requete_producteurs = $dbh->prepare("SELECT * FROM Producteur");
$requete_producteurs->execute();
$producteurs_film = $requete_producteurs->fetchAll();
if(isset($_POST['ajout_producteur'])){
    $requete_ajout=$dbh->prepare("INSERT INTO produit (ID_Producteur,ID_film) VALUES (:ID_Producteur,:ID_film)");
    $requete_ajout->bindValue(':ID_Producteur',$_POST['select_producteur']);
    $requete_ajout->bindValue(':ID_film', $_GET['ID']);
    $requete_ajout->execute();
    $_SESSION['flash'] = "Ajout effectué";
    die;
}
$ID = $dbh->lastInsertId();

if (isset($_FILES['Affiche']))
{  
    if ($_FILES['Affiche']['size'] <= 250000)
    {  
        move_uploaded_file($_FILES['Affiche']['tmp_name'], 'affiche/' . $_FILES['Affiche']['name']);
        $requete = $dbh->prepare("UPDATE Film SET Affiche = :Affiche WHERE ID_film = :ID ");
        $requete->bindValue(':ID', $ID);
        $requete->bindValue(':Affiche', $_FILES['Affiche']['name']);
        
        $requete->execute();
        
    }else{  
        $erreur = "un problème de téléchargement est survenu !!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>insertion film</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<form action="insertion_film.php" method="POST" enctype="multipart/form-data">
<h2>Film insertion</h2>

<label class="form-titre"><b>Nom du film</b></label><br>
<input class="form-champs" class="login" type="text" placeholder="Nom du film" name="Nom_du_film" required> <br>

<label class="form-titre"><b>Date de sortie</b></label><br>
<input class="form-champs" class="login" type="text" placeholder="Date de sortie" name="Date_de_sortie" required> <br>

<label class="form-titre"><b>synopsis</b></label><br>
<textarea class="form-champs" rows="6" cols="100" name="synopsis" required></textarea> <br>

<input class="form-champs" type="hidden" name="size" value="250000" />
<input class="form-champs" type="file" name="Affiche" size=2000 />

<h2>Acteurs</h2>
<div id="acteur">

</div>
<button id="ajout_acteur" type="button">ajout acteur</button>



<h2>Genre</h2>
<div id="types">

</div>
<button id="ajout_genre" type="button">ajout genre</button>
<br>


<h2>Producteur</h2>
<div id="producteur">

</div>
<button id="ajout_producteur" type="button">ajout Producteur</button>
<br>


<h2>Realisateur</h2>
<div id="realisateur">

</div>
<button id="ajout_realisateur" type="button">ajout Realisateur</button>
<br>

<div class="bouton">
<button type="submit" name="bouton" class="btn btn-primary mb-2">insérer</button>
</div>

</form>
<a href="liste_films.php">Liste des films</a>

<select style="display: none" id="acteur_select_tpl">
<option value=""></option>
<?php foreach($acteurs_film as $acteur): ?>
    <option value="<?= $acteur['ID_acteur']?>"><?=$acteur['Nom']?></option>
    <?php endforeach ?>
    </select>

<select style="display: none" id="genre_select_tpl">
<option value=""></option>
<?php foreach($genres_film as $genre): ?>
    <option value="<?= $genre['ID_genre']?>"><?=$genre['types']?></option>
    <?php endforeach ?>
    </select>

<select style="display: none" id="producteur_select_tpl">
<option value=""></option>
<?php foreach($producteurs_film as $producteur): ?>
    <option value="<?= $producteur['ID_producteur']?>"><?=$producteur['Nom']?></option>
    <?php endforeach ?>
    </select>

<select style="display: none" id="realisateur_select_tpl">
<option value=""></option>
<?php foreach($realisateurs_film as $realisateur): ?>
    <option value="<?= $realisateur['ID_realisateur']?>"><?=$realisateur['Nom']?></option>
    <?php endforeach ?>
    </select>

    <script>
    var ajout_acteur = document.getElementById('ajout_acteur');
    var acteur = document.getElementById('acteur');
    var acteur_select_tpl = document.getElementById('acteur_select_tpl');
    var index = 0;
    
    ajout_acteur.addEventListener('click', function(){
        var divacteur = document.createElement('div');
        divacteur.className = 'acteur';
        
        var selectacteur = acteur_select_tpl.cloneNode(true);
        selectacteur.id = 'acteur' + index;
        selectacteur.style.display = 'block';
        selectacteur.name = 'Nom[' + index + '][ID_acteur]';
        divacteur.appendChild(selectacteur);
        acteur.appendChild(divacteur);
        
        index = index + 1;
    });

    var ajout_genre = document.getElementById('ajout_genre');
    var genres = document.getElementById('types');
    var genre_select_tpl = document.getElementById('genre_select_tpl');
    var index = 0;
    
    ajout_genre.addEventListener('click', function(){
        var divgenre = document.createElement('div');
        divgenre.className = 'genre';
        
        var selectgenre = genre_select_tpl.cloneNode(true);
        selectgenre.id = 'genre' + index;
        selectgenre.style.display = 'block';
        selectgenre.name = 'types[' + index + '][ID_genre]';
        divgenre.appendChild(selectgenre);
        genres.appendChild(divgenre);
        
        index = index + 1;
    });

     var ajout_producteur = document.getElementById('ajout_producteur');
    var producteur = document.getElementById('producteur');
    var producteur_select_tpl = document.getElementById('producteur_select_tpl');
    var index = 0;
    
    ajout_producteur.addEventListener('click', function(){
        var divproducteur = document.createElement('div');
        divproducteur.className = 'producteur';
        
        var selectproducteur = producteur_select_tpl.cloneNode(true);
        selectproducteur.id = 'producteur' + index;
        selectproducteur.style.display = 'block';
        selectproducteur.name = 'Nom[' + index + '][ID_producteur]';
        divproducteur.appendChild(selectproducteur);
        producteur.appendChild(divproducteur);
        
        index = index + 1;
    });

     var ajout_realisateur = document.getElementById('ajout_realisateur');
    var realisateur = document.getElementById('realisateur');
    var realisateur_select_tpl = document.getElementById('realisateur_select_tpl');
    var index = 0;
    
    ajout_realisateur.addEventListener('click', function(){
        var divrealisateur = document.createElement('div');
        divrealisateur.className = 'realisateur';
        
        var selectrealisateur = realisateur_select_tpl.cloneNode(true);
        selectrealisateur.id = 'realisateur' + index;
        selectrealisateur.style.display = 'block';
        selectrealisateur.name = 'Nom[' + index + '][ID_realisateur]';
        divrealisateur.appendChild(selectrealisateur);
        realisateur.appendChild(divrealisateur);
        
        index = index + 1;
    });
    </script>
    </body>
    </html>
    
    
    