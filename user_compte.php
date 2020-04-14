<?php
session_start();
ini_set("display_errors","1");
error_reporting(E_ALL);
function e($string, $flags=ENT_QUOTES){
    return htmlspecialchars ($string,$flags);
}

$config = require "config.php";

$dbh = new PDO($config["dsn"], $config["utilisateur"], $config["mdp"]);

$comptes = $dbh->prepare("SELECT * FROM utilisateur WHERE ID_utilisateur = :ID");
$comptes->bindValue(':ID', $_GET['ID']);
$comptes->execute();
$compte = $comptes->fetch();


$requete_favoris = $dbh->prepare("
SELECT *
FROM Film
INNER JOIN favoris ON favoris.ID_film = Film.ID_film
WHERE ID_utilisateur = :ID
");
$requete_favoris->bindValue(':ID',$_GET['ID']);
$requete_favoris->execute();
$film_favoris = $requete_favoris->fetchAll();
if(isset($_POST['ajout_favoris'])){
    $requete_ajout=$dbh->prepare("INSERT INTO favoris (ID_film,ID_utilisateur) VALUES (:ID_film,:ID_utilisateur)");
    $requete_ajout->bindValue(':ID_film',$_POST['select_film']);
    $requete_ajout->bindValue(':ID_utilisateur', $_GET['ID']);
    $requete_ajout->execute();
     $_SESSION['flash'] = "Ajout effectué";
      header('Location: user_compte.php?ID='.$_SESSION["ID"]);
        die;
}
if(isset($_POST['delete_film'])){
    $delete=$dbh->prepare("DELETE FROM favoris WHERE ID_film = :ID AND ID_utilisateur = :ID_utilisateur LIMIT 1");
    $delete->bindValue(':ID',$_POST['delete_film']);
    $delete->bindValue(':ID_utilisateur',$_GET['ID']);
    $delete->execute();
    $_SESSION['flash'] = "Suppression effectuée";
        header('Location: user_compte.php?ID='.$_SESSION["ID"]);
        die;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($compte['Nom'])?></title>
    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="text-center">
    <h1 class="shadow .bg-center focus:shadow-outline focus:outline-none text-black font-bold py-2 px-4 rounded m-4 text-5xl "><?= $compte['Nom']. ' '. $compte['Prenom']?></h1>
    </div>
    <label class="shadow bg-blue-300  focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4"><b>Nom<b></label>
    <div>
        <p class="shadow text-gray-900 border-gray-900 .bg-center focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4 max-w-titre"><?=e($compte['Nom'])?></p>
    </div>
     <label class="shadow bg-blue-300  focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4"><b>Prenom<b></label>
    <div>
        <p class="shadow text-gray-900 border-gray-900 .bg-center focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4 max-w-titre"><?=e($compte['Prenom'])?></p>
    </div>
    <label class="shadow bg-blue-300  focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4"><b>Email<b></label>
    <div>
        <p class="shadow text-gray-900 border-gray-900 .bg-center focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4 max-w-titre"><?=e($compte['Email'])?></p>
    </div>
     <label class="shadow bg-blue-300  focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4"><b>Pseudo<b></label>
    <div>
        <p class="shadow text-gray-900 border-gray-900 .bg-center focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4 max-w-titre"><?=e($compte['Pseudo'])?></p>
    </div>
    <?php foreach($film_favoris as $requete_favori):?>
            <ul class=" flex justify-center">
            <li class="flex  flex-col  text-black-700 text-center bg-green-100 px-2 py-1 w-64 m-2">
             <td><?= $requete_favori['Nom_du_film']?></td>
            <td ><a class=" shadow bg-green-400 hover:bg-green-600 focus:shadow-outline focus:outline-none text-white font-bold py-2  px-4 rounded m-2" href="fiche_film.php?ID=<?=$requete_favori['ID_film']?>">Voir</a></td>
            <form method="post">
              <td> <button  class="flex-initial shadow bg-green-400 hover:bg-green-600 focus:shadow-outline focus:outline-none text-white font-bold py-2  px-4 rounded m-2" type="submit" name="delete_film" value="<?= $requete_favori['ID_film']?>">Delete</button>
              </td> 
            </form>
            </li>
            </ul>
            <?php endforeach ?>
            </div>
             <form class="m-4" method="post">
            <?php $sth = $dbh->prepare("SELECT ID_film,Nom_du_film FROM Film ORDER BY Nom_du_film");
            $sth->execute();
            $tousfilms = $sth->fetchAll();
            echo "<select class='block appearance-none w-48 bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline m-4'  name='select_film' >";
            foreach($tousfilms as $tousfilm){
                echo   "<option value=".$tousfilm["ID_film"].">".$tousfilm["Nom_du_film"]."</option>";
            } 
            echo "</select>";
            ?>
            <div class="bouton ">
            <button class="flex-initial shadow bg-green-400 hover:bg-green-600 focus:shadow-outline focus:outline-none text-white font-bold py-2  px-4 rounded m-2"   type="submit" name="ajout_favoris" class="btn btn-primary mb-2">ajout favoris</button>
            </div>
            </form>
    <td><a class="shadow bg-blue-500 hover:bg-blue-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4"  href="editer_utilisateur.php?ID=<?=$_GET["ID"]?>">modifier mes données</a></td>
    <a class="shadow bg-blue-500 hover:bg-blue-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4"  href="https://lefevre.simplon-charleville.fr/allosimplon?ID=<?=$_SESSION["ID"]?>">Retour a l'accueil</a>
</body>
</html>