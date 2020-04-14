<?php
require "boot.php";
$dbh = new PDO($config["dsn"], $config["utilisateur"], $config["mdp"]);
$liste = $dbh->prepare("SELECT * FROM Film WHERE ID_film = :ID");
$liste->bindValue(':ID', $_GET['ID']);
$liste->execute();
$films = $liste->fetch();
$requete_genres = $dbh->prepare("
SELECT *
FROM Genre
INNER JOIN type_film ON type_film.ID_Genre = Genre.ID_genre
WHERE ID_film = :ID
");
$requete_genres->bindValue(':ID',$_GET['ID']);
$requete_genres->execute();
$genres_film = $requete_genres->fetchAll();

$requete_acteurs = $dbh->prepare("
    SELECT *
    FROM Acteur
    INNER JOIN joue ON joue.ID_Acteur = Acteur.ID_acteur
    WHERE ID_film = :ID
");
$requete_acteurs->bindValue(':ID',$_GET['ID']);
$requete_acteurs->execute();
$acteurs_film = $requete_acteurs->fetchAll();
$requete_realisateurs = $dbh->prepare("
    SELECT *
    FROM Realisateur
    INNER JOIN realise ON realise.ID_realisateur = Realisateur.ID_realisateur
    WHERE ID_film = :ID
");
$requete_realisateurs->bindValue(':ID',$_GET['ID']);
$requete_realisateurs->execute();
$realisateurs_film = $requete_realisateurs->fetchAll();
$requete_producteurs = $dbh->prepare("
    SELECT *
    FROM Producteur
    INNER JOIN produit ON produit.ID_Producteur = Producteur.ID_producteur
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
    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
    <title><?= $films['Nom_du_film']?></title>
</head>
<body>
    <div class="text-center">
    <h1 class="shadow .bg-center focus:shadow-outline focus:outline-none text-black font-bold py-2 px-4 rounded m-4 text-5xl "><?= $films['Nom_du_film']?></h1>
    </div>
    <div class="flex justify-center">
        <img class="m-4" src="affiche/<?=urlencode($films['Affiche'])?>" alt="">
    </div>
    <div class="text-center">
    <label class="shadow .bg-center focus:shadow-outline focus:outline-none text-black font-bold py-2 px-4 rounded m-6 "><b>Date de sortie<b></label>
    </div>
    <div class="text-center">
        <p class="m-4"><?=$films['Date_de_sortie']?></p>
    </div>
    <div class="text-center">
     <label class="shadow text-gray-900 border-gray-900 .bg-center focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4"><b>Synopsis<b></label>
     </div>
    <div class="text-center">
        <p class="m-4"><?=$films['synopsis']?></p>
    </div>
    <div class="text-center">
    <label class="shadow text-gray-900 border-gray-900 .bg-center focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4"><b>Genre<b></label>
    </div>
     <div class="text-center">
        <?php foreach($genres_film as $genre):?>
        <ul class="text-center">
            <li class="text-center m-4">
            <?=$genre['types']?>
            </li>
        </ul>
        <?php endforeach ?>
    </div>
    <div class="text-center">
    <label class="shadow bg-purple-500  focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4"><b>Acteurs<b></label>
    </div>
    <br>
    <div class="flex justify-center">
        <?php foreach($acteurs_film as $acteur):?>
        <ul>
            <li><img  class="m-4  h-64 w-64" src="photoacteur/<?=urlencode($acteur['photo'])?>" alt="">
            <?=$acteur['Nom']?>
            </li>
        </ul>
        <?php endforeach ?>
    </div>
    <div class="text-center">
    <label class="shadow bg-purple-500  focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4"><b>Réalisateur<b></label>
    </div>
    <br>
     <div class="flex justify-center">
        <?php foreach($realisateurs_film as $realisateur):?>
        <ul>
            <li><img class="m-4  h-64 w-64" src="photorealisateur/<?=urlencode($realisateur['photo'])?>" alt="">
            <?=$realisateur['Nom']?>
            </li>
        </ul>
        <?php endforeach ?>
    </div>
    <div  class="text-center">
    <label class="shadow bg-purple-500  focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4"><b>Producteur<b></label>
    </div>
    <br>
     <div class="flex justify-center">
        <?php foreach($producteurs_film as $producteur):?>
        <ul>
            <li><img  class="m-4 h-64 w-64" src="photoproducteur/<?=urlencode($producteur['photo'])?>" alt="">
            <?=$producteur['Nom']?>
            </li>
        </ul>
        <?php endforeach ?>
    </div>
   
  
    <a class="shadow bg-blue-500 hover:bg-blue-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4" href="catalogue.php">Retourner a la Gallerie</a>
</body>
</html>