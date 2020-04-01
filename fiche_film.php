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

$requete_acteurs = $dbh->prepare("
    SELECT *
    FROM Acteur
    INNER JOIN joue ON joue.ID_Acteur = Acteur.ID
    WHERE ID_film = :ID
");
$requete_acteurs->bindValue(':ID',$_GET['ID']);
$requete_acteurs->execute();
$acteurs = $requete_acteurs->fetchAll();
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
    <div>
        <?php foreach($acteurs as $acteur):?>
        <ul>
            <li><img src="photoacteur/<?=$acteur['photo']?>" alt="">
            <?=$acteur['Nom']?>
            </li>
        </ul>
        <?php endforeach ?>
    </div>
  
    <a href="catalogue.php">Retourner a la Gallerie</a>
</body>
</html>