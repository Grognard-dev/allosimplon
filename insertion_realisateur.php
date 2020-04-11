<?php
require "boot.php";
require "securite.php";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Création Realisateur</title>
    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    
    <form action="insertion_realisateur.php" method="POST" enctype="multipart/form-data">
    <h2 class="shadow .bg-center focus:shadow-outline focus:outline-none text-black font-bold py-2 px-4 rounded m-4 text-5xl">Insertion Realisateur</h2>
        <label class="shadow text-gray-900 border-gray-900 .bg-center focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4"><b>Nom du Realisateur</b></label><br>
        <input class="block appearance-none w-48 bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline m-4" type="text"   name="Nom">
        <br>
        <label class="shadow text-gray-900 border-gray-900 .bg-center focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4"><b>Date de naissance</b></label><br>
        <input class="block appearance-none w-48 bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline m-4" type="text"  name="Date_de_naissance">
        <br>
        <label class="shadow text-gray-900 border-gray-900 .bg-center focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4"><b>Pays d'origine</b></label><br>
        <input class="block appearance-none w-48 bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline m-4" type="text"  name="Pays_d_origine">
        <br>
        <label class="shadow text-gray-900 border-gray-900 .bg-center focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4 "><b>biographie</b></label><br>
        <textarea class="block appearance-none w-1/2 bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline m-4" rows="6" cols="100" name="biographie"></textarea>
        <br>
        <input type="hidden" name="size" value="250000" />
        <input type="file" name="photo" size=20000000 />
        <div class="bouton">
<button class="shadow bg-purple-500 hover:bg-blue-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4" type="submit" name="bouton" class="btn btn-primary mb-2">insérer</button>
</div>
    </form>
    
    <a  class="shadow bg-purple-500 hover:bg-blue-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4" href="liste_realisateur.php">Liste des Realisateurs</a>
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
        $insertion_film = $dbh->prepare ("INSERT INTO Realisateur (Nom, Date_de_naissance, Pays_d_origine, biographie) 
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
        $chemin =  'photorealisateur/' . $_FILES['photo']['name'];
        move_uploaded_file($_FILES['photo']['tmp_name'], 'photorealisateur/' . $_FILES['photo']['name']);
         if($_FILES['photo']['type'] === 'image/jpeg'){
                        $image = @imagecreatefromjpeg($chemin);
                    }elseif($_FILES['photo']['type'] === 'image/png'){
                        $image = @imagecreatefrompng($chemin);
                    }else {
                         unlink($chemin);
                        $_SESSION['flash'] = " Pas le bon format d'image, format accepter jpeg,png";
                         header('Location: insertion_realisateur.php');
                        die;
                    }
                    if($image === false){
                        unlink($chemin);
                        $_SESSION['flash'] = "Erreur de conversion d'image";
                          header('Location: insertion_realisateur.php');
                        die;
                    }
                    $return_image = imagescale($image,350);
                    if($_FILES['photo']['type'] === 'image/jpeg'){
                        imagejpeg($return_image,$chemin);
                    }elseif($_FILES['photo']['type'] === 'image/png'){
                        imagepng($return_image,$chemin);
                    }
        $requete = $dbh->prepare("UPDATE Realisateur SET photo = :photo WHERE ID_realisateur = :ID ");
        $requete->bindValue(':ID', $ID);
        $requete->bindValue(':photo', $_FILES['photo']['name']);
        
        $requete->execute();
        
    }else{  
        $erreur = "un problème de téléchargement est survenu !!";
    }
}
?>
