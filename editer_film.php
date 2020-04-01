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
if(isset($_POST['delete'])){
    $delete=$dbh->prepare("DELETE FROM joue WHERE ID_Acteur = :ID AND ID_film = :ID_film LIMIT 1");
    $delete->bindValue(':ID',$_POST['delete']);
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
        
        <div>
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
            </div>
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
            
            </body>
            </html>
            