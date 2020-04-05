<?php
session_start();
require "securite.php";
ini_set("display_errors","1");
error_reporting(E_ALL);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Création Producteur</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    
    <form action="insertion_producteur.php" method="POST" enctype="multipart/form-data">
        <label class="form-titre"><b>Nom du Producteur</b></label><br>
        <input class="form-champs" type="text"  name="Nom">
        <br>
        <label class="form-titre"><b>Date de naissance</b></label><br>
        <input class="form-champs" type="text"  name="Date_de_naissance">
        <br>
        <label class="form-titre"><b>Pays d'origine</b></label><br>
        <input class="form-champs" type="text"   name="Pays_d_origine">
        <br>
        <label class="form-titre"><b>biographie</b></label><br>
        <textarea class="form-champs" rows="6" cols="100" name="biographie"></textarea>
        <br>
        <input class="form-champs" type="hidden" name="size" value="250000" />
        <input class="form-champs" type="file" name="photo" size=20000000 />
        <div class="bouton">
<button type="submit" name="bouton" class="btn btn-primary mb-2">insérer</button>
</div>
    </form>
    
    <a href="liste_producteur.php">Liste des Producteurs</a>
</body>
</html>

<?php
$config = require "config.php";

$dbh = new PDO($config["dsn"], $config["utilisateur"], $config["mdp"]);

if (isset($_POST['bouton'])){
    $Nom = empty($_POST['Nom']) ? null : $_POST['Nom'];
    $Date_de_naissance = empty($_POST['Date_de_naissance']) ? null : $_POST['Date_de_naissance'];
    $Pays_d_origine= empty($_POST['Pays_d_origine']) ? null : $_POST['Pays_d_origine'];
    $biographie = empty($_POST['biographie']) ? null : $_POST['biographie'];
    
    if ($Nom === null || $Date_de_naissance === null  || $Pays_d_origine === null || $biographie === null) {
        $erreur = 'Veuillez remplir tous les champs';
    }else {
        $insertion_film = $dbh->prepare ("INSERT INTO Producteur (Nom, Date_de_naissance, Pays_d_origine, biographie) 
        VALUES (:Nom, :Date_de_naissance, :Pays_d_origine, :biographie)") ;
        
        $insertion_film->bindValue(':Nom', $Nom);
        $insertion_film->bindValue(':Date_de_naissance', $Date_de_naissance);
        $insertion_film->bindValue(':Pays_d_origine', $Pays_d_origine);
        $insertion_film->bindValue(':biographie', $biographie);
        $insertion_film->execute();
    }
}
$ID = $dbh->lastInsertId();

if (isset($_FILES['photo']))
{  
    if ($_FILES['photo']['size'] <= 250000)
    {  
        move_uploaded_file($_FILES['photo']['tmp_name'], 'photoproducteur/' . $_FILES['photo']['name']);
        $requete = $dbh->prepare("UPDATE Producteur SET photo = :photo WHERE ID = :ID ");
        $requete->bindValue(':ID', $ID);
        $requete->bindValue(':photo', $_FILES['photo']['name']);
        
        $requete->execute();
        
    }else{  
        $erreur = "un problème de téléchargement est survenu !!";
    }
}
?>
