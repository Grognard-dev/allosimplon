<?php
session_start();
function e($string, $flags=ENT_QUOTES){
    return htmlspecialchars ($string,$flags);
}
ini_set("display_errors","1");
error_reporting(E_ALL);
$erreur = null;
$message = null;
if(isset($_SESSION['flash']) ){
    $message = $_SESSION['flash'];
    unset($_SESSION['flash']);
}
$config = require "config.php";
$dbh = new PDO($config["dsn"], $config["utilisateur"], $config["mdp"]);
$edit=$dbh->prepare("SELECT * FROM Film WHERE ID = :ID");
$edit->bindValue(':ID', $_GET['ID']);
$edit->execute();
$film=$edit->fetch();
if (isset($_POST['bouton'])){
    $Nom_du_film = empty($_POST['Nom_du_film']) ? null : $_POST['Nom_du_film'];
    $Date_de_sortie = empty($_POST['Date_de_sortie']) ? null : $_POST['Date_de_sortie'];
    $synopsis = empty($_POST['synopsis']) ? null : $_POST['synopsis'];
    
    if ($Nom_du_film === null || $Date_de_sortie === null  || $synopsis === null) {
        $erreur = 'Veuillez remplir tous les champs';
    }else {
        if (isset($_FILES['Affiche']))
        {  
            if ($_FILES['Affiche']['size'] <= 250000)
            {  
                move_uploaded_file($_FILES['Affiche']['tmp_name'], 'affiche/' . $_FILES['Affiche']['name']);
                $requete = $dbh->prepare("UPDATE Film SET Affiche = :Affiche WHERE ID = :ID ");
                $requete->bindValue(':ID',$_GET['ID'] );
                $requete->bindValue(':Affiche', $_FILES['Affiche']['name']);
                
                $requete->execute();
                
            }else{  
                $erreur = "un problème de téléchargement est survenu !!";
            }
        }
        $modifier_film = $dbh->prepare ("UPDATE Film SET 
        Nom_du_film = :Nom_du_film, 
        Date_de_sortie = :Date_de_sortie,
        synopsis = :synopsis WHERE ID = :ID" );
        $modifier_film->bindValue(':ID', $_GET['ID']);
        $modifier_film->bindValue(':Nom_du_film', $Nom_du_film);
        $modifier_film->bindValue(':Date_de_sortie', $Date_de_sortie);
        $modifier_film->bindValue(':synopsis', $synopsis);
        $modifier_film->execute();
        
        $_SESSION['flash'] = "Modification effectuer";
        header('Location: editer_film.php?ID='.$film['ID']);
        die;
    }
}
$requete_acteurs = $dbh->prepare("
SELECT *
FROM Acteur
INNER JOIN joue ON joue.ID_Acteur = Acteur.ID
WHERE ID_film = :ID
");
$requete_acteurs->bindValue(':ID',$_GET['ID']);
$requete_acteurs->execute();
$acteurs_film = $requete_acteurs->fetchAll();
if(isset($_POST['ajout_acteur'])){
    $requete_ajout=$dbh->prepare("INSERT INTO joue (ID_Acteur,ID_film) VALUES (:ID_Acteur,:ID_film)");
    $requete_ajout->bindValue(':ID_Acteur',$_POST['select']);
    $requete_ajout->bindValue(':ID_film', $_GET['ID']);
    $requete_ajout->execute();
     $_SESSION['flash'] = "Ajout effectué";
        header('Location: editer_film.php?ID='.$film['ID']);
        die;
}
$requete_genres = $dbh->prepare("
SELECT *
FROM Genre
INNER JOIN relation7 ON relation7.ID_Genre = Genre.ID
WHERE ID_film = :ID
");
$requete_genres->bindValue(':ID',$_GET['ID']);
$requete_genres->execute();
$genres_film = $requete_genres->fetchAll();
if(isset($_POST['ajout_genre'])){
    $requete_ajout=$dbh->prepare("INSERT INTO relation7 (ID_Genre,ID_film) VALUES (:ID_Genre,:ID_film)");
    $requete_ajout->bindValue(':ID_Genre',$_POST['select_genre']);
    $requete_ajout->bindValue(':ID_film', $_GET['ID']);
    $requete_ajout->execute();
     $_SESSION['flash'] = "Ajout effectué";
        header('Location: editer_film.php?ID='.$film['ID']);
        die;
}
$requete_realisateurs = $dbh->prepare("
SELECT *
FROM Realisateur
INNER JOIN realise ON realise.ID_realisateur = Realisateur.ID
WHERE ID_film = :ID
");
$requete_realisateurs->bindValue(':ID',$_GET['ID']);
$requete_realisateurs->execute();
$realisateurs_film = $requete_realisateurs->fetchAll();
if(isset($_POST['ajout_realisateur'])){
    $requete_ajout=$dbh->prepare("INSERT INTO realise (ID_realisateur,ID_film) VALUES (:ID_realisateur,:ID_film)");
    $requete_ajout->bindValue(':ID_realisateur',$_POST['select_realisateur']);
    $requete_ajout->bindValue(':ID_film', $_GET['ID']);
    $requete_ajout->execute();
     $_SESSION['flash'] = "Ajout effectué";
        header('Location: editer_film.php?ID='.$film['ID']);
        die;
}
$requete_producteurs = $dbh->prepare("
SELECT *
FROM Producteur
INNER JOIN produit ON produit.ID_Producteur = Producteur.ID
WHERE ID_film = :ID
");
$requete_producteurs->bindValue(':ID',$_GET['ID']);
$requete_producteurs->execute();
$producteurs_film = $requete_producteurs->fetchAll();
if(isset($_POST['ajout_producteur'])){
    $requete_ajout=$dbh->prepare("INSERT INTO produit (ID_Producteur,ID_film) VALUES (:ID_Producteur,:ID_film)");
    $requete_ajout->bindValue(':ID_Producteur',$_POST['select_producteur']);
    $requete_ajout->bindValue(':ID_film', $_GET['ID']);
    $requete_ajout->execute();
     $_SESSION['flash'] = "Ajout effectué";
        header('Location: editer_film.php?ID='.$film['ID']);
        die;
}
if(isset($_POST['delete'])){
    $delete=$dbh->prepare("DELETE FROM joue WHERE ID_Acteur = :ID AND ID_film = :ID_film LIMIT 1");
    $delete->bindValue(':ID',$_POST['delete']);
    $delete->bindValue(':ID_film',$_GET['ID']);
    $delete->execute();
    $_SESSION['flash'] = "Suppression effectuée";
        header('Location: editer_film.php?ID='.$film['ID']);
        die;
}
if(isset($_POST['delete_genre'])){
    $delete=$dbh->prepare("DELETE FROM relation7 WHERE ID_Genre = :ID AND ID_film = :ID_film LIMIT 1");
    $delete->bindValue(':ID',$_POST['delete_genre']);
    $delete->bindValue(':ID_film',$_GET['ID']);
    $delete->execute();
    $_SESSION['flash'] = "Suppression effectuée";
        header('Location: editer_film.php?ID='.$film['ID']);
        die;
}
if(isset($_POST['delete_realisateur'])){
    $delete=$dbh->prepare("DELETE FROM realise WHERE ID_realisateur = :ID AND ID_film = :ID_film LIMIT 1");
    $delete->bindValue(':ID',$_POST['delete_realisateur']);
    $delete->bindValue(':ID_film',$_GET['ID']);
    $delete->execute();
    $_SESSION['flash'] = "Suppression effectuée";
        header('Location: editer_film.php?ID='.$film['ID']);
        die;
}
if(isset($_POST['delete_producteur'])){
    $delete=$dbh->prepare("DELETE FROM produit WHERE ID_Producteur = :ID AND ID_film = :ID_film LIMIT 1");
    $delete->bindValue(':ID',$_POST['delete_producteur']);
    $delete->bindValue(':ID_film',$_GET['ID']);
    $delete->execute();
    $_SESSION['flash'] = "Suppression effectuée";
        header('Location: editer_film.php?ID='.$film['ID']);
        die;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Document</title>
</head>
<body>
<form method="POST" enctype="multipart/form-data">
<h1>Modification du Film</h1>

<label><b>Nom du film</b></label>
<input class="login" type="text" value="<?= e($film['Nom_du_film']) ?>" name="Nom_du_film" required> <br>

<label><b>Date de sortie</b></label>
<input class="login" type="text" value="<?= e($film['Date_de_sortie']) ?>" name="Date_de_sortie" required> <br>

<label><b>synopsis</b></label>
<br>
<textarea rows="6" cols="100" class="login"  name="synopsis" required><?= e($film['synopsis'])?></textarea> <br>

<label><b>Affiche</b></label>
<br>
<img src="affiche/<?= e($film['Affiche'])?>" alt="">
<br>
<input type="hidden" name="size" value="250000" />
<input type="file" name="Affiche" size=2000 />

<div class="bouton">
<button type="submit" name="bouton" class="btn btn-primary mb-2">modifier</button>
</div>
<?php if($erreur != null):?>
    <p><?=e($erreur)?></p>
    <?php endif?>
    <?php if($message != null):?>
        <p><?=e($message)?></p>
        <?php endif?>
        </form>
          <?php foreach($genres_film as $genre):?>
            <ul>
            <li>
            <?=$genre['genre']?>
            <form method="post">
                <button type="submit" name="delete_genre" value="<?= $genre['ID']?>">Delete</button>
            </form>
            </li>
            </ul>
            <?php endforeach ?>
            </div>
             <form method="post">
            <?php $sth = $dbh->prepare("SELECT ID,genre FROM Genre ORDER BY genre");
            $sth->execute();
            $tousgenre = $sth->fetchAll();
            echo "<select name='select_genre' >";
            foreach($tousgenre as $genre){
                echo   "<option value=".$genre["ID"].">".$genre["genre"]."</option>";
            } 
            echo "</select>";
            ?>
            <div class="bouton">
            <button type="submit" name="ajout_genre" class="btn btn-primary mb-2">ajout genre</button>
            </div>
            </form>
            
            <form method="post">
            <?php $sth = $dbh->prepare("SELECT ID,Nom FROM Acteur ORDER BY Nom");
            $sth->execute();
            $tousacteurs = $sth->fetchAll();
            echo "<select name='select' >";
            foreach($tousacteurs as $acteur){
                echo   "<option value=".$acteur["ID"].">".$acteur["Nom"]."</option>";
            } 
            echo "</select>";
            ?>
            <div class="bouton">
            <button type="submit" name="ajout_acteur" class="btn btn-primary mb-2">ajout acteur</button>
            </div>
            </form>
            
        <?php foreach($acteurs_film as $acteur):?>
            <ul>
            <li><img src="photoacteur/<?=$acteur['photo']?>" alt="">
            <?=$acteur['Nom']?>
            <form method="post">
                <button type="submit" name="delete" value="<?= $acteur['ID']?>">Delete</button>
            </form>
            </li>
            </ul>
            <?php endforeach ?>

            <form method="post">
            <?php $sth = $dbh->prepare("SELECT ID,Nom FROM Realisateur ORDER BY Nom");
            $sth->execute();
            $tousrealisateur = $sth->fetchAll();
            echo "<select name='select_realisateur' >";
            foreach($tousrealisateur as $realisateur){
                echo   "<option value=".$realisateur["ID"].">".$realisateur["Nom"]."</option>";
            } 
            echo "</select>";
            ?>
            <div class="bouton">
            <button type="submit" name="ajout_realisateur" class="btn btn-primary mb-2">ajout realisateur</button>
            </div>
            </form>
            
        <?php foreach($realisateurs_film as $realisateur):?>
            <ul>
            <li><img src="photorealisateur/<?=$realisateur['photo']?>" alt="">
            <?=$realisateur['Nom']?>
            <form method="post">
                <button type="submit" name="delete_realisateur" value="<?= $realisateur['ID']?>">Delete</button>
            </form>
            </li>
            </ul>
            <?php endforeach ?>

             <form method="post">
            <?php $sth = $dbh->prepare("SELECT ID,Nom FROM Producteur ORDER BY Nom");
            $sth->execute();
            $tousproducteurs = $sth->fetchAll();
            echo "<select name='select_producteur' >";
            foreach($tousproducteurs as $producteur){
                echo   "<option value=".$producteur["ID"].">".$producteur["Nom"]."</option>";
            } 
            echo "</select>";
            ?>
            <div class="bouton">
            <button type="submit" name="ajout_producteur" class="btn btn-primary mb-2">ajout producteur</button>
            </div>
            </form>
            
        <?php foreach($producteurs_film as $producteur):?>
            <ul>
            <li><img src="photoproducteur/<?=$producteur['photo']?>" alt="">
            <?=$producteur['Nom']?>
            <form method="post">
                <button type="submit" name="delete_producteur" value="<?= $producteur['ID']?>">Delete</button>
            </form>
            </li>
            </ul>
            <?php endforeach ?>
           
            
            </body>
            </html>
            