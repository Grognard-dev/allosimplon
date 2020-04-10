<?php
require "boot.php";
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
<link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">

</head>
<body>
    <div class="flex justify-center flex-wrap">
<?php foreach($films as $film):?>
    <div class="flex flex-col bg-black">
     <td ><a class=" text-center px-4 py-2 m-2" href="fiche_film.php?ID=<?=$film['ID_film']?>"><img class="h-64 w-64" src="affiche/<?=urlencode($film["Affiche"])?>" alt=""></a>
      <?php
     if(isset($_SESSION["is_admin"]) && $_SESSION["is_admin"]):?>
    <a class="text-orange-700 text-center bg-black px-4 py-2 m-2" href="editer_film.php?ID=<?=$film['ID_film']?>">modifier</a> 
    <?php endif ?>
     </td>
    </div>
    <?php endforeach ?>
    <?php if(isset($_SESSION["is_admin"]) && $_SESSION["is_admin"]):?>
    <a class="text-orange-700 text-center bg-black px-4 py-2 m-2" class="ajouter_film" href="insertion_film.php">Ajouter un film</a>
    <?php endif ?>
    </div>
    
     </body>
    </html>
    
    
    