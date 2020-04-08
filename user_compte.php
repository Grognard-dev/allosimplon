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
</head>
<body>
    <h1><?= $compte['Nom']. ' '. $compte['Prenom']?></h1>
    
    <label><b>Nom<b></label>
    <div>
        <p><?=e($compte['Nom'])?></p>
    </div>
     <label><b>Prenom<b></label>
    <div>
        <p><?=e($compte['Prenom'])?></p>
    </div>
    <label><b>Email<b></label>
    <div>
        <p><?=e($compte['Email'])?></p>
    </div>
     <label><b>Pseudo<b></label>
    <div>
        <p><?=e($compte['Pseudo'])?></p>
    </div>
    <?php foreach($film_favoris as $requete_favori):?>
            <ul>
            <li>
             <td><?= $requete_favori['Nom_du_film']?></td>
            <td><a href="fiche_film.php?ID=<?=$requete_favori['ID_film']?>">Voir</a></td>
            <form method="post">
                <button type="submit" name="delete_film" value="<?= $requete_favori['ID_film']?>">Delete</button>
            </form>
            </li>
            </ul>
            <?php endforeach ?>
            </div>
             <form method="post">
            <?php $sth = $dbh->prepare("SELECT ID_film,Nom_du_film FROM Film ORDER BY Nom_du_film");
            $sth->execute();
            $tousfilms = $sth->fetchAll();
            echo "<select name='select_film' >";
            foreach($tousfilms as $tousfilm){
                echo   "<option value=".$tousfilm["ID_film"].">".$tousfilm["Nom_du_film"]."</option>";
            } 
            echo "</select>";
            ?>
            <div class="bouton">
            <button type="submit" name="ajout_favoris" class="btn btn-primary mb-2">ajout favoris</button>
            </div>
            </form>
    <td><a href="editer_utilisateur.php?ID=<?=$_GET["ID"]?>">modifier mes données</a></td>
    <a href="https://lefevre.simplon-charleville.fr/allosimplon?ID=<?=$_SESSION["ID"]?>">Retour a l'accueil</a>
</body>
</html>