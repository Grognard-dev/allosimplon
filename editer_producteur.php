<?php
session_start();
require "securite.php";
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

$edit=$dbh->prepare("SELECT * FROM Producteur WHERE ID = :ID");
$edit->bindValue(':ID', $_GET['ID']);
$edit->execute();
$producteurs=$edit->fetch();

if (isset($_POST['bouton'])){
        $Nom = empty($_POST['Nom']) ? null : $_POST['Nom'];
        $Date_de_naissance = empty($_POST['Date_de_naissance']) ? null : $_POST['Date_de_naissance'];
        $Pays_d_origine= empty($_POST['Pays_d_origine']) ? null : $_POST['Pays_d_origine'];
        $biographie = empty($_POST['biographie']) ? null : $_POST['biographie'];

        if ($Nom === null || $Date_de_naissance === null  || $Pays_d_origine === null || $biographie === null) {
            $erreur = 'Veuillez remplir tous les champs';
        }else {

            
            if (isset($_FILES['photo']))
            {  
                if ($_FILES['photo']['size'] <= 250000)
                {  
                    move_uploaded_file($_FILES['photo']['tmp_name'], 'photoproducteur/' . $_FILES['photo']['name']);
                    $requete = $dbh->prepare("UPDATE Producteur SET photo = :photo WHERE ID = :ID ");
                    $requete->bindValue(':ID',$_GET['ID'] );
                    $requete->bindValue(':photo', $_FILES['photo']['name']);
                
                    $requete->execute();
                    
                }else{  
                    $erreur = "un problème de téléchargement est survenu !!";
                }
            }

            $modifier_producteur = $dbh->prepare ("UPDATE Producteur SET 
            Nom = :Nom, 
            Date_de_naissance = :Date_de_naissance,
            Pays_d_origine = :Pays_d_origine,
            biographie = :biographie WHERE ID = :ID" );
            $modifier_producteur->bindValue(':ID', $_GET['ID']);
            $modifier_producteur->bindValue(':Nom', $Nom);
            $modifier_producteur->bindValue(':Date_de_naissance', $Date_de_naissance);
            $modifier_producteur->bindValue(':Pays_d_origine', $Pays_d_origine);
            $modifier_producteur->bindValue(':biographie', $biographie);
            $modifier_producteur->execute();

            $_SESSION['flash'] = "Modification effectuer";
            header('Location: editer_producteur.php?ID='.$producteurs['ID']);
            die;
        }
    }
    

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editer Producteur</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <form method="POST" enctype="multipart/form-data">
<h1>Modification du Producteur</h1>

<label class="form-titre"><b>Nom du Producteurs</b></label>
<input class="form-champs" type="text" value="<?= e($producteurs['Nom']) ?>" name="Nom" required> <br>

<label class="form-titre"><b>Date de naissance</b></label>
<input class="form-champs" type="text" value="<?= e($producteurs['date_de_naissance']) ?>" name="date_de_naissance" required> <br>

<label class="form-titre"><b>Pays_d_origine<b></label>
<input class="form-champs" type="text" value="<?= e($producteurs['pays_d_origine']) ?>" name="pays_d_origine" required> <br>

<label class="form-titre"><b>biographie</b></label>
<br>
<textarea class="form-champs" rows="6" cols="100" class="login"  name="biographie" required><?= e($producteurs['biographie'])?></textarea> <br>

<label><b>Photo</b></label>
<br>
<img src="photoproducteur/<?= urlencode($producteurs['photo'])?>" alt="">
<br>
<input class="form-champs" type="hidden" name="size" value="250000" />
<input class="form-champs" type="file" name="photo" size=2000 />


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
<a href="liste_producteur.php">Liste des Producteurs</a>
</body>
</html>