<?php
require "boot.php";

$erreur = null;
$dbh = new PDO($config["dsn"], $config["utilisateur"], $config["mdp"]);
$edit=$dbh->prepare("SELECT * FROM utilisateur WHERE ID_utilisateur = :ID");
$edit->bindValue(':ID', $_GET['ID']);
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
            mot_de_passe = :mot_de_passe WHERE ID_utilisateur = :ID" );
            $modifier_utilisateur->bindValue(':ID', $_GET['ID']);
            $modifier_utilisateur->bindValue(':Nom', $Nom);
            $modifier_utilisateur->bindValue(':Prenom', $Prenom);
            $modifier_utilisateur->bindValue(':Pseudo', $Pseudo);
            $modifier_utilisateur->bindValue(':Email', $Email);
            $modifier_utilisateur->bindValue(':mot_de_passe', password_hash($motdepasse, PASSWORD_DEFAULT ));
            $modifier_utilisateur->execute();

            $_SESSION['flash'] = "Modification effectuer";
            header('Location: editer_utilisateur.php?ID='.$_GET['ID']);
            die;
        
    }
    

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editer Utilisateur</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <form method="POST" enctype="multipart/form-data">
<h1  class="shadow .bg-center focus:shadow-outline focus:outline-none text-black font-bold py-2 px-4 rounded m-4 text-5xl">Modification de l'user</h1>

<label class="shadow text-gray-900 border-gray-900 .bg-center focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4"><b>Nom </b></label>
<input class="block appearance-none w-48 bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline m-4 " type="text" value="<?= e($utilisateurs['Nom']) ?>" name="Nom" required> <br>

<label class="shadow text-gray-900 border-gray-900 .bg-center focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4"><b>Prenom</b></label>
<input class="block appearance-none w-48 bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline m-4 " type="text" value="<?= e($utilisateurs['Prenom']) ?>" name="Prenom" required> <br>

<label class="shadow text-gray-900 border-gray-900 .bg-center focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4"><b>Email<b></label>
<input class="block appearance-none w-48 bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline m-4 " type="text" value="<?= e($utilisateurs['Email']) ?>" name="Email" required> <br>

<label class="shadow text-gray-900 border-gray-900 .bg-center focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4"><b>Pseudo</b></label>
<br>
<input class="block appearance-none w-48 bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline m-4 " type="text" value="<?= e($utilisateurs['Pseudo']) ?>" name="Pseudo" required> <br>
<label class="shadow text-gray-900 border-gray-900 .bg-center focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4"><b>Mot de passe</b></label>
<br>
<input class="block appearance-none w-48 bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline m-4 " type="password"  name="mot_de_passe" required> <br>

<div class="bouton">
<button type="submit" name="bouton" class="shadow bg-purple-300 hover:bg-purple-500 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4" type="submit" name="bouton" class="btn btn-primary mb-2">modifier</button>
</div>
<?php if($erreur != null):?>
  <p><?=e($erreur)?></p>
<?php endif?>

<a class="shadow bg-purple-500 hover:bg-blue-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4" href="https://lefevre.simplon-charleville.fr/allosimplon/user_compte.php?ID=<?=$_GET['ID']?>">Retour a votre compte</a>
</form>

</body>
</html>