<?php
require "boot.php";
require "securite.php";
$erreur = null;
$dbh = new PDO($config["dsn"], $config["utilisateur"], $config["mdp"]);
$edit=$dbh->prepare("SELECT * FROM Realisateur WHERE ID_realisateur = :ID");
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
                    move_uploaded_file($_FILES['photo']['tmp_name'], 'photorealisateur/' . $_FILES['photo']['name']);
                    $requete = $dbh->prepare("UPDATE Realisateur SET photo = :photo WHERE ID_realisateur = :ID ");
                    $requete->bindValue(':ID',$_GET['ID'] );
                    $requete->bindValue(':photo', $_FILES['photo']['name']);
                
                    $requete->execute();
                    
                }else{  
                    $erreur = "un problème de téléchargement est survenu !!";
                }
            }

            $modifier_realisateur = $dbh->prepare ("UPDATE Realisateur SET 
            Nom = :Nom, 
            Date_de_naissance = :Date_de_naissance,
            Pays_d_origine = :Pays_d_origine,
            biographie = :biographie WHERE ID_realisateur = :ID" );
            $modifier_realisateur->bindValue(':ID', $_GET['ID']);
            $modifier_realisateur->bindValue(':Nom', $Nom);
            $modifier_realisateur->bindValue(':Date_de_naissance', $Date_de_naissance);
            $modifier_realisateur->bindValue(':Pays_d_origine', $Pays_d_origine);
            $modifier_realisateur->bindValue(':biographie', $biographie);
            $modifier_realisateur->execute();

            $_SESSION['flash'] = "Modification effectuer";
            header('Location: editer_realisateur.php?ID='.$realisateurs['ID']);
            die;
        }
    }
    

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editer realisateur</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <form method="POST" enctype="multipart/form-data">
<h1>Modification du realisateur</h1>

<label class="form-titre"><b>Nom du Realisateur</b></label>
<input class="form-champs" type="text" value="<?= e($realisateurs['Nom']) ?>" name="Nom" required> <br>

<label class="form-titre"><b>Date de naissance</b></label>
<input class="form-champs" type="text" value="<?= e($realisateurs['Date_de_naissance']) ?>" name="Date_de_naissance" required> <br>

<label class="form-titre"><b>Pays_d_origine<b></label>
<input class="form-champs" type="text" value="<?= e($realisateurs['pays_d_origine']) ?>" name="pays_d_origine" required> <br>

<label class="form-titre"><b>biographie</b></label>
<br>
<textarea class="form-champs" rows="6" cols="100" class="login"  name="biographie" required><?= e($realisateurs['biographie'])?></textarea> <br>

<label><b>Photo</b></label>
<br>
<img src="photorealisateur/<?= urlencode($realisateurs['photo'])?>" alt="">
<br>
<input class="form-champs" type="hidden" name="size" value="250000" />
<input class="form-champs" type="file" name="photo" size=2000 />

<div class="bouton">
<button type="submit" name="bouton" class="btn btn-primary mb-2">modifier</button>
</div>
<?php if($erreur != null):?>
  <p><?=e($erreur)?></p>
<?php endif?>
</form>
<a href="liste_realisateur.php">Liste des Realisateurs</a>
</body>
</html>