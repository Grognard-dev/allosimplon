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
        $pays_d_origine= empty($_POST['pays_d_origine']) ? null : $_POST['pays_d_origine'];
        $biographie = empty($_POST['biographie']) ? null : $_POST['biographie'];

        if ($Nom === null || $Date_de_naissance === null  || $pays_d_origine === null || $biographie === null) {
            $erreur = 'Veuillez remplir tous les champs';
        }else {

            
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK)
            {  
                if ($_FILES['photo']['size'] <= 250000)
                {  
                    $chemin =  'photorealisateur/' . $_FILES['photo']['name'];
                    move_uploaded_file($_FILES['photo']['tmp_name'], 'photorealisateur/' . $_FILES['photo']['name']);
                     if($_FILES['photo']['type'] === 'image/jpeg'){
                        $image = @imagecreatefromjpeg($chemin);
                    }elseif($_FILES['photo']['type'] === 'image/png'){
                        $image = @imagecreatefrompng($chemin);
                    }else {
                         unlink($chemin);
                        $_SESSION['flash'] = " Pas le bon format d'image, format accepter jpeg,png";
                         header('Location: editer_realisateur.php?ID='.$realisateurs['ID']);
                        die;
                    }
                    if($image === false){
                        unlink($chemin);
                        $_SESSION['flash'] = "Erreur de conversion d'image";
                          header('Location: editer_realisateur.php?ID='.$realisateurs['ID']);
                        die;
                    }
                    $return_image = imagescale($image,350);
                    if($_FILES['photo']['type'] === 'image/jpeg'){
                        imagejpeg($return_image,$chemin);
                    }elseif($_FILES['photo']['type'] === 'image/png'){
                        imagepng($return_image,$chemin);
                    }
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
            pays_d_origine = :pays_d_origine,
            biographie = :biographie WHERE ID_realisateur = :ID" );
            $modifier_realisateur->bindValue(':ID', $_GET['ID']);
            $modifier_realisateur->bindValue(':Nom', $Nom);
            $modifier_realisateur->bindValue(':Date_de_naissance', $Date_de_naissance);
            $modifier_realisateur->bindValue(':pays_d_origine', $pays_d_origine);
            $modifier_realisateur->bindValue(':biographie', $biographie);
            $modifier_realisateur->execute();

            $_SESSION['flash'] = "Modification effectuer";
            header('Location: editer_realisateur.php?ID='.$realisateurs['ID_realisateur']);
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
    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <form method="POST" enctype="multipart/form-data">
<h1  class="shadow .bg-center focus:shadow-outline focus:outline-none text-black font-bold py-2 px-4 rounded m-4 text-5xl">Modification du realisateur</h1>

<label class="shadow text-gray-900 border-gray-900 .bg-center focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4"><b>Nom du Realisateur</b></label>
<input class="block appearance-none w-48 bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline m-4 " type="text" value="<?= e($realisateurs['Nom']) ?>" name="Nom" required> <br>

<label class="shadow text-gray-900 border-gray-900 .bg-center focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4"><b>Date de naissance</b></label>
<input class="block appearance-none w-48 bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline m-4 " type="date" value="<?= e($realisateurs['Date_de_naissance']) ?>" name="Date_de_naissance" required> <br>

<label class="shadow text-gray-900 border-gray-900 .bg-center focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4"><b>Pays_d_origine<b></label>
<input class="block appearance-none w-48 bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline m-4 " type="text" value="<?= e($realisateurs['pays_d_origine']) ?>" name="pays_d_origine" required> <br>

<label class="shadow text-gray-900 border-gray-900 .bg-center focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4"><b>biographie</b></label>
<br>
<textarea class="block appearance-none w-1/2 bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline m-4 " rows="6" cols="100" class="login"  name="biographie" required><?= e($realisateurs['biographie'])?></textarea> <br>

<label><b>Photo</b></label>
<br>
<img class="m-4 h-64 w-64" src="photorealisateur/<?= urlencode($realisateurs['photo'])?>" alt="">
<br>
<input class="form-champs" type="hidden" name="size" value="250000" />
<input class="form-champs" type="file" name="photo" size=2000 />

<div class="bouton">
<button class="shadow bg-purple-300 hover:bg-purple-500 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4" type="submit" name="bouton" class="btn btn-primary mb-2">modifier</button>
</div>
<?php if($erreur != null):?>
  <p><?=e($erreur)?></p>
<?php endif?>
</form>
<a class="shadow bg-purple-500 hover:bg-blue-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4" href="liste_realisateur.php">Liste des Realisateurs</a>
</body>
</html>