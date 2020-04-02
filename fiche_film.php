<?php
session_start();
ini_set("display_errors","1");
error_reporting(E_ALL);

$config = require "config.php";

$dbh = new PDO($config["dsn"], $config["utilisateur"], $config["mdp"]);

$liste = $dbh->prepare("SELECT * FROM Film WHERE ID = :ID");
$liste->bindValue(':ID', $_GET['ID']);
$liste->execute();
$films = $liste->fetch();
$requete_genres = $dbh->prepare("
SELECT *
FROM Genre
INNER JOIN relation7 ON relation7.ID_Genre = Genre.ID
WHERE ID_film = :ID
");
$requete_genres->bindValue(':ID',$_GET['ID']);
$requete_genres->execute();
$genres_film = $requete_genres->fetchAll();

$requete_acteurs = $dbh->prepare("
    SELECT *
    FROM Acteur
    INNER JOIN joue ON joue.ID_Acteur = Acteur.ID
    WHERE ID_film = :ID
");
$requete_acteurs->bindValue(':ID',$_GET['ID']);
$requete_acteurs->execute();
$acteurs_film = $requete_acteurs->fetchAll();
$requete_realisateurs = $dbh->prepare("
    SELECT *
    FROM Realisateur
    INNER JOIN realise ON realise.ID_realisateur = Realisateur.ID
    WHERE ID_film = :ID
");
$requete_realisateurs->bindValue(':ID',$_GET['ID']);
$requete_realisateurs->execute();
$realisateurs_film = $requete_realisateurs->fetchAll();
$requete_producteurs = $dbh->prepare("
    SELECT *
    FROM Producteur
    INNER JOIN produit ON produit.ID_Producteur = Producteur.ID
    WHERE ID_film = :ID
");
$requete_producteurs->bindValue(':ID',$_GET['ID']);
$requete_producteurs->execute();
$producteurs_film = $requete_producteurs->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $films['Nom_du_film']?></title>
</head>
<body>
    <h1><?= $films['Nom_du_film']?></h1>
    <div>
        <img src="affiche/<?=$films['Affiche']?>" alt="">
    </div>
    <label><b>Date de sortie<b></label>
    <div>
        <p><?=$films['Date_de_sortie']?></p>
    </div>
     <label><b>Synopsis<b></label>
    <div>
        <p><?=$films['synopsis']?></p>
    </div>
    <label><b>Genre<b></label>
     <div>
        <?php foreach($genres_film as $genre):?>
        <ul>
            <li>
            <?=$genre['genre']?>
            </li>
        </ul>
        <?php endforeach ?>
    </div>
    <label><b>Acteurs<b></label>
    <br>
    <div>
        <?php foreach($acteurs_film as $acteur):?>
        <ul>
            <li><img src="photoacteur/<?=$acteur['photo']?>" alt="">
            <?=$acteur['Nom']?>
            </li>
        </ul>
        <?php endforeach ?>
    </div>
    <label><b>Réalisateur<b></label>
    <br>
     <div>
        <?php foreach($realisateurs_film as $realisateur):?>
        <ul>
            <li><img src="photorealisateur/<?=$realisateur['photo']?>" alt="">
            <?=$realisateur['Nom']?>
            </li>
        </ul>
        <?php endforeach ?>
    </div>
    <label><b>Producteur<b></label>
    <br>
     <div>
        <?php foreach($producteurs_film as $producteur):?>
        <ul>
            <li><img src="photoproducteur/<?=$producteur['photo']?>" alt="">
            <?=$producteur['Nom']?>
            </li>
        </ul>
        <?php endforeach ?>
    </div>
   
  
    <a href="catalogue.php">Retourner a la Gallerie</a>
</body>
</html>