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

$edit=$dbh->prepare("SELECT * FROM Realisateur WHERE ID = :ID");
$edit->bindValue(':ID', $_GET['ID']);
$edit->execute();
$realisateurs=$edit->fetch();

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

            $modifier_acteur = $dbh->prepare ("UPDATE Realisateur SET 
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
    <title>Document</title>
</head>
<body>
    <form method="POST" enctype="multipart/form-data">
<h1>Modification du realisateur</h1>

<label><b>Nom du Realisateur</b></label>
<input class="login" type="text" value="<?= e($realisateurs['Nom']) ?>" name="Nom" required> <br>

<label><b>Date de naissance</b></label>
<input class="login" type="text" value="<?= e($realisateurs['Date_de_naissance']) ?>" name="Date_de_naissance" required> <br>

<label><b>Pays_d_origine<b></label>
<input class="login" type="text" value="<?= e($realisateurs['pays_d_origine']) ?>" name="pays_d_origine" required> <br>

<label><b>biographie</b></label>
<br>
<textarea rows="6" cols="100" class="login"  name="biographie" required><?= e($realisateurs['biographie'])?></textarea> <br>

<label><b>Photo</b></label>
<br>
<img src="photorealisateur/<?= e($realisateurs['photo'])?>" alt="">
<br>
<input type="hidden" name="size" value="250000" />
<input type="file" name="photo" size=2000 />

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
</body>
</html>