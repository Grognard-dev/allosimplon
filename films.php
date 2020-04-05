<?php
ini_set("display_errors","1");
error_reporting(E_ALL);

$config = require "config.php";

$dbh = new PDO($config["dsn"], $config["utilisateur"], $config["mdp"]);

$liste = $dbh->prepare("SELECT * FROM Film");
$liste->execute();
$films = $liste->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Films</title>
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="index.css">
</head>
<body>
    <div>
<?php foreach($films as $film):?>
    <div class="affiche">
     <a href="fiche_film.php?ID=<?=$film['ID']?>"><img src="affiche/<?=urlencode($film["Affiche"])?>" alt=""></a>
     <?php
     if(isset($_SESSION["is_admin"]) && $_SESSION["is_admin"]):?>
     <td><a href="editer_film.php?ID=<?=$film['ID']?>">modifier</a></td>  
    <?php endif ?>
    </div>
    <?php endforeach ?>
    <?php if(isset($_SESSION["is_admin"]) && $_SESSION["is_admin"]):?>
    <a class="ajouter_film" href="insertion_film.php">Ajouter un film</a>
    <?php endif ?>
    </div>
    
     </body>
    </html>
    
    
    