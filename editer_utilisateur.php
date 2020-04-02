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

$edit=$dbh->prepare("SELECT * FROM utilisateur WHERE ID = :ID");
$edit->bindValue(':ID', $_SESSION['ID']);
$edit->execute();
$utilisateurs=$edit->fetch();

if (isset($_POST['bouton'])){
        $Nom = empty($_POST['Nom']) ? null : $_POST['Nom'];
        $Prenom = empty($_POST['Prenom']) ? null : $_POST['Prenom'];
        $Pseudo= empty($_POST['Pseudo']) ? null : $_POST['Pseudo'];
        $Email = empty($_POST['Email']) ? null : $_POST['Email'];
        $motdepasse = empty($_POST['mot_de_passe']) ? null : $_POST['mot_de_passe'];


            $modifier_utilisateur= $dbh->prepare ("UPDATE utilisateur SET 
            Nom = :Nom, 
            Prenom = :Prenom,
            Pseudo = :Pseudo,
            Email = :Email, 
            mot_de_passe = :mot_de_passe WHERE ID = :ID" );
            $modifier_utilisateur->bindValue(':ID', $_GET['ID']);
            $modifier_utilisateur->bindValue(':Nom', $Nom);
            $modifier_utilisateur->bindValue(':Prenom', $Prenom);
            $modifier_utilisateur->bindValue(':Pseudo', $Pseudo);
            $modifier_utilisateur->bindValue(':Email', $Email);
            $modifier_utilisateur->bindValue(':mot_de_passe', password_hash($motdepasse, PASSWORD_DEFAULT ));
            $modifier_utilisateur->execute();

            $_SESSION['flash'] = "Modification effectuer";
            header('Location: editer_utilisateur.php?ID='.$_SESSION['ID']);
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
<h1>Modification de l'user</h1>

<label><b>Nom </b></label>
<input class="login" type="text" value="<?= e($utilisateurs['Nom']) ?>" name="Nom" required> <br>

<label><b>Prenom</b></label>
<input class="login" type="text" value="<?= e($utilisateurs['Prenom']) ?>" name="Prenom" required> <br>

<label><b>Email<b></label>
<input class="login" type="text" value="<?= e($utilisateurs['Email']) ?>" name="Email" required> <br>

<label><b>Pseudo</b></label>
<br>
<input class="login" type="text" value="<?= e($utilisateurs['Pseudo']) ?>" name="Pseudo" required> <br>
<label><b>Mot de passe</b></label>
<br>
<input class="login" type="password"  name="mot_de_passe" required> <br>

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
<td><a href="user_compte.php?ID=<?=$_SESSION["ID"]?>">Retour a l'user</a></td>
</body>
</html>