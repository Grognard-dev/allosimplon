 <!--AFFICHE-->
<?php
session_start();
ini_set("display_errors","1");
error_reporting(E_ALL);

$config = require "config.php";

$dbh = new PDO($config["dsn"], $config["utilisateur"], $config["mdp"]);

$liste = $dbh->prepare("SELECT * FROM Film");
$liste->execute();
$films = $liste->fetchAll();
?>
 <div class="title-dada-affiche">
    <h2>A l'affiche</h2>
</div>

<div class="center slider">
    <?php foreach($films as $film):?>
    <a href="fiche_film.php?ID=<?=$film['ID_film']?>"><img class="h-64 w-64" src="affiche/<?=$film["Affiche"]?>" alt=""></a>
    <?php endforeach?>
 </div>



