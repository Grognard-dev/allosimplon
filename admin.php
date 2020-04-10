<?php 
require "boot.php";
require "securite.php";
$dbh = new PDO($config["dsn"], $config["utilisateur"], $config["mdp"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
</head>
<body>
    <h1>Liste du contenue</h1>
    <br>
<ul>
    <li><a href="liste_films.php?ID=<?=$_SESSION['ID']?>">Liste des films</a></li>
    <li><a href="liste_acteur.php?ID=<?=$_SESSION['ID']?>"> Liste des acteurs</a></li>
    <li><a href="liste_producteur.php?ID=<?=$_SESSION['ID']?>">Liste des producteurs</a></li>
    <li><a href="liste_realisateur.php?ID=<?=$_SESSION['ID']?>"> Liste des Réalisateur</a></li>
</ul>
<H2>Liste user</H2>
<br>
<ul>
    <li><a href="liste_utilisateur.php?ID=<?=$_SESSION['ID']?>">liste des users</a></li>
</ul>
<a href="https://lefevre.simplon-charleville.fr/allosimplon/index.php?ID=<?=$_SESSION['ID']?>">Retourner a l'accueil</a>
</body>
</html>





