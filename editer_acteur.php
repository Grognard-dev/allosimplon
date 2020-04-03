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

$edit=$dbh->prepare("SELECT * FROM Acteur WHERE ID = :ID");
$edit->bindValue(':ID', $_GET['ID']);
$edit->execute();
$acteurs=$edit->fetch();

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
                    move_uploaded_file($_FILES['photo']['tmp_name'], 'photoacteur/' . $_FILES['photo']['name']);
                    $requete = $dbh->prepare("UPDATE Acteur SET photo = :photo WHERE ID = :ID ");
                    $requete->bindValue(':ID',$_GET['ID'] );
                    $requete->bindValue(':photo', $_FILES['photo']['name']);
                
                    $requete->execute();
                    
                }else{  
                    $erreur = "un problème de téléchargement est survenu !!";
                }
            }

            $modifier_acteur = $dbh->prepare ("UPDATE Acteur SET 
            Nom = :Nom, 
            Date_de_naissance = :Date_de_naissance,
            Pays_d_origine = :Pays_d_origine,
            biographie = :biographie WHERE ID = :ID" );
            $modifier_acteur->bindValue(':ID', $_GET['ID']);
            $modifier_acteur->bindValue(':Nom', $Nom);
            $modifier_acteur->bindValue(':Date_de_naissance', $Date_de_naissance);
            $modifier_acteur->bindValue(':Pays_d_origine', $Pays_d_origine);
            $modifier_acteur->bindValue(':biographie', $biographie);
            $modifier_acteur->execute();

            $_SESSION['flash'] = "Modification effectuer";
            header('Location: editer_acteur.php?ID='.$acteurs['ID']);
            die;
        }
    }
    

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editer Acteur</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <form method="POST" enctype="multipart/form-data">
<h1>Modification de l'acteur</h1>

<label class="form-titre"><b>Nom de l'acteur</b></label>
<input class="form-champs" type="text" value="<?= e($acteurs['Nom']) ?>" name="Nom" required> <br>

<label class="form-titre"><b>Date de naissance</b></label>
<input class="form-champs" type="text" value="<?= e($acteurs['Date_de_naissance']) ?>" name="Date_de_naissance" required> <br>

<label class="form-titre"><b>Pays_d_origine<b></label>
<input class="form-champs" type="text" value="<?= e($acteurs['Pays_d_origine']) ?>" name="Pays_d_origine" required> <br>
 
<label class="form-titre"><b>biographie</b></label>
<br>
<textarea class="form-champs" rows="6" cols="100" class="login"  name="biographie" required><?= e($acteurs['biographie'])?></textarea> <br>

<label  class="form-titre">><b>Photo</b></label>
<br>
<img src="photoacteur/<?= e($acteurs['photo'])?>" alt="">
<br>
<input class="form-champs" type="hidden" name="size" value="250000" />
<br>
<input class="form-champs" type="file" name="photo" size=2000 />
<br>


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
<a href="liste_acteur.php">Liste des Acteurs</a>
</body>
</html>