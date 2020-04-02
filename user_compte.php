<?php
session_start();
ini_set("display_errors","1");
error_reporting(E_ALL);

$config = require "config.php";

$dbh = new PDO($config["dsn"], $config["utilisateur"], $config["mdp"]);

$comptes = $dbh->prepare("SELECT * FROM utilisateur WHERE ID = :ID");
$comptes->bindValue(':ID', $_GET['ID']);
$comptes->execute();
$compte = $comptes->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $compte['Nom']?></title>
</head>
<body>
    <h1><?= $compte['Nom']. ' '. $compte['Prenom']?></h1>
    
    <label><b>Nom<b></label>
    <div>
        <p><?=$compte['Nom']?></p>
    </div>
     <label><b>Prenom<b></label>
    <div>
        <p><?=$compte['Prenom']?></p>
    </div>
    <label><b>Email<b></label>
    <div>
        <p><?=$compte['Email']?></p>
    </div>
     <label><b>Pseudo<b></label>
    <div>
        <p><?=$compte['Pseudo']?></p>
    </div>
    <td><a href="editer_utilisateur.php?ID=<?=$_SESSION["ID"]?>">modifier mes données</a></td>
    <a href="https://lefevre.simplon-charleville.fr/allosimplon?ID=<?=$_SESSION["ID"]?>">Retour a l'accueil</a>
</body>
</html>