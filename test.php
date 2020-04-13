<?php 
require "boot.php";
$dbh = new PDO($config["dsn"], $config["utilisateur"], $config["mdp"]);

$inquery = implode(',',array_fill(0,count($_GET['choix']),'?'));

$filtre = $dbh->prepare("SELECT Affiche, Nom_du_film, type_film.ID_film FROM type_film 
INNER JOIN Film ON Film.ID_film = type_film.ID_film 
WHERE ID_genre IN (". $inquery .") 
GROUP BY Nom_du_film
ORDER BY Nom_du_film");
foreach($_GET['choix'] as $k => $id){
    $filtre->bindValue(($k+1),$id);
}
$filtre->execute();
$films = $filtre->fetchAll();


$statement = $dbh->query("SELECT * FROM Genre");
$Genres = $statement->fetchAll();
?>
<form >
<?php foreach($Genres as $Genre):?>
    <?php if(in_array($Genre['ID_genre'],$_GET['choix'])){?>
        <input type="checkbox" checked name="choix[]" value="<?=$Genre['ID_genre']?>">
   <?php }else{ ?>
   <input type="checkbox" name="choix[]" value="<?=$Genre['ID_genre']?>">
   <?php }?>
    <?=$Genre['types']?>
<?php endforeach ?>
<button name="button">filtrer</button>
</form>
<?php foreach($films as $film):?>
<?= $film['Nom_du_film'] ?><br>
<?php endforeach ?>
