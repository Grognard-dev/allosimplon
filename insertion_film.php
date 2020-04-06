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

<div class="bouton">
<button type="submit" name="bouton" class="btn btn-primary mb-2">insérer</button>
</div>
</form>
<a href="liste_films.php">Liste des films</a>
</body>
</html>


