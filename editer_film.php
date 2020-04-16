<?php
require "boot.php";
require "securite.php";
$dbh = new PDO($config["dsn"], $config["utilisateur"], $config["mdp"]);
$edit=$dbh->prepare("SELECT * FROM Film WHERE ID_film = :ID");
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
        if (isset($_FILES['Affiche']) && $_FILES['Affiche']['error'] === UPLOAD_ERR_OK)
        {  
            if ($_FILES['Affiche']['size'] <= 250000)
            {  
                $chemin =  'affiche/' . $_FILES['Affiche']['name'];
                move_uploaded_file($_FILES['Affiche']['tmp_name'], 'affiche/' . $_FILES['Affiche']['name']);
                if($_FILES['Affiche']['type'] === 'image/jpeg'){
                        $image = @imagecreatefromjpeg($chemin);
                    }elseif($_FILES['Affiche']['type'] === 'image/png'){
                        $image = @imagecreatefrompng($chemin);
                    }else {
                         unlink($chemin);
                        $_SESSION['flash'] = " Pas le bon format d'image, format accepter jpeg,png";
                         header('Location: editer_film.php?ID='.$film['ID_film']);
                        die;
                    }
                    if($image === false){
                        unlink($chemin);
                        $_SESSION['flash'] = "Erreur de conversion d'image";
                         header('Location: editer_film.php?ID='.$film['ID_film']);
                        die;
                    }
                    $return_image = imagescale($image,350);
                    if($_FILES['Affiche']['type'] === 'image/jpeg'){
                        imagejpeg($return_image,$chemin);
                    }elseif($_FILES['Affiche']['type'] === 'image/png'){
                        imagepng($return_image,$chemin);
                    }
                $requete = $dbh->prepare("UPDATE Film SET Affiche = :Affiche WHERE ID_film = :ID ");
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
        synopsis = :synopsis WHERE ID_film = :ID" );
        $modifier_film->bindValue(':ID', $_GET['ID']);
        $modifier_film->bindValue(':Nom_du_film', $Nom_du_film);
        $modifier_film->bindValue(':Date_de_sortie', $Date_de_sortie);
        $modifier_film->bindValue(':synopsis', $synopsis);
        $modifier_film->execute();
        
        $_SESSION['flash'] = "Modification effectuer";
        header('Location: editer_film.php?ID='.$film['ID_film']);
        die;
    }
}
$requete_acteurs = $dbh->prepare("
SELECT *
FROM Acteur
INNER JOIN joue ON joue.ID_Acteur = Acteur.ID_acteur
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
        header('Location: editer_film.php?ID='.$film['ID_film']);
        die;
}
$requete_genres = $dbh->prepare("
SELECT *
FROM Genre
INNER JOIN type_film ON type_film.ID_Genre = Genre.ID_genre
WHERE ID_film = :ID
");
$requete_genres->bindValue(':ID',$_GET['ID']);
$requete_genres->execute();
$genres_film = $requete_genres->fetchAll();
if(isset($_POST['ajout_genre'])){
    $requete_ajout=$dbh->prepare("INSERT INTO type_film (ID_Genre,ID_film) VALUES (:ID_Genre,:ID_film)");
    $requete_ajout->bindValue(':ID_Genre',$_POST['select_genre']);
    $requete_ajout->bindValue(':ID_film', $_GET['ID']);
    $requete_ajout->execute();
     $_SESSION['flash'] = "Ajout effectué";
      header('Location: editer_film.php?ID='.$film['ID_film']);
        die;
}
$requete_realisateurs = $dbh->prepare("
SELECT *
FROM Realisateur
INNER JOIN realise ON realise.ID_realisateur = Realisateur.ID_realisateur
WHERE ID_film = :ID
");
$requete_realisateurs->bindValue(':ID',$_GET['ID']);
$requete_realisateurs->execute();
$realisateurs_film = $requete_realisateurs->fetchAll();
if(isset($_POST['ajout_realisateur'])){
    $requete_ajout=$dbh->prepare("INSERT INTO realise (ID_realisateur,ID_film) VALUES (:ID_realisateur,:ID_film)");
    $requete_ajout->bindValue(':ID_realisateur',$_POST['select_realisateur']);
    $requete_ajout->bindValue(':ID_film', $_GET['ID']);
    $requete_ajout->execute();
     $_SESSION['flash'] = "Ajout effectué";
        header('Location: editer_film.php?ID='.$film['ID_film']);
        die;
}
$requete_producteurs = $dbh->prepare("
SELECT *
FROM Producteur
INNER JOIN produit ON produit.ID_Producteur = Producteur.ID_producteur
WHERE ID_film = :ID
");
$requete_producteurs->bindValue(':ID',$_GET['ID']);
$requete_producteurs->execute();
$producteurs_film = $requete_producteurs->fetchAll();
if(isset($_POST['ajout_producteur'])){
    $requete_ajout=$dbh->prepare("INSERT INTO produit (ID_Producteur,ID_film) VALUES (:ID_Producteur,:ID_film)");
    $requete_ajout->bindValue(':ID_Producteur',$_POST['select_producteur']);
    $requete_ajout->bindValue(':ID_film', $_GET['ID']);
    $requete_ajout->execute();
     $_SESSION['flash'] = "Ajout effectué";
        header('Location: editer_film.php?ID='.$film['ID_film']);
        die;
}
if(isset($_POST['delete'])){
    $delete=$dbh->prepare("DELETE FROM joue WHERE ID_Acteur = :ID AND ID_film = :ID_film LIMIT 1");
    $delete->bindValue(':ID',$_POST['delete']);
    $delete->bindValue(':ID_film',$_GET['ID']);
    $delete->execute();
    $_SESSION['flash'] = "Suppression effectuée";
        header('Location: editer_film.php?ID='.$film['ID_film']);
        die;
}
if(isset($_POST['delete_genre'])){
    $delete=$dbh->prepare("DELETE FROM type_film WHERE ID_Genre = :ID AND ID_film = :ID_film LIMIT 1");
    $delete->bindValue(':ID',$_POST['delete_genre']);
    $delete->bindValue(':ID_film',$_GET['ID']);
    $delete->execute();
    $_SESSION['flash'] = "Suppression effectuée";
        header('Location: editer_film.php?ID='.$film['ID_film']);
        die;
}
if(isset($_POST['delete_realisateur'])){
    $delete=$dbh->prepare("DELETE FROM realise WHERE ID_realisateur = :ID AND ID_film = :ID_film LIMIT 1");
    $delete->bindValue(':ID',$_POST['delete_realisateur']);
    $delete->bindValue(':ID_film',$_GET['ID']);
    $delete->execute();
    $_SESSION['flash'] = "Suppression effectuée";
        header('Location: editer_film.php?ID='.$film['ID_film']);
        die;
}
if(isset($_POST['delete_producteur'])){
    $delete=$dbh->prepare("DELETE FROM produit WHERE ID_Producteur = :ID AND ID_film = :ID_film LIMIT 1");
    $delete->bindValue(':ID',$_POST['delete_producteur']);
    $delete->bindValue(':ID_film',$_GET['ID']);
    $delete->execute();
    $_SESSION['flash'] = "Suppression effectuée";
        header('Location: editer_film.php?ID='.$film['ID_film']);
        die;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editer film</title>
<link rel="stylesheet" href="css/style.css">
 <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
<form method="POST" enctype="multipart/form-data">
<h1 class="shadow .bg-center focus:shadow-outline focus:outline-none text-black font-bold py-2 px-4 rounded m-4 text-5xl">Modification du Film</h1>

<label class="shadow text-gray-900 border-gray-900 .bg-center focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4"><b>Nom du film</b></label>
<input class="block appearance-none w-48 bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline m-4 " type="text" value="<?= e($film['Nom_du_film']) ?>" name="Nom_du_film" required> <br>

<label class="shadow text-gray-900 border-gray-900 .bg-center focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4"><b>Date de sortie</b></label>
<input class="block appearance-none w-48 bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline m-4 " type="date" value="<?= e($film['Date_de_sortie']) ?>" name="Date_de_sortie" required> <br>

<label class="shadow text-gray-900 border-gray-900 .bg-center focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4"><b>synopsis</b></label>
<br>
<textarea class="block appearance-none w-1/2 bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline m-4 " rows="6" cols="100" class="login"  name="synopsis" required><?= e($film['synopsis'])?></textarea> <br>

<label><b>Affiche</b></label>
<br>
<img  class="m-4 h-64 w-64" src="affiche/<?= urlencode($film['Affiche'])?>" alt="">
<br>
<input class="form-champs" type="hidden" name="size" value="250000" />
<input class="form-champs" type="file" name="Affiche" size=2000 />

<div class="bouton">
<button class="shadow bg-purple-300 hover:bg-purple-500 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4" type="submit" name="bouton" class="btn btn-primary mb-2">modifier</button>
</div>
<?php if($erreur != null):?>
    <p><?=e($erreur)?></p>
    <?php endif?>
        </form>
          <?php foreach($genres_film as $genre):?>
            <ul>
            <li>
            <?=$genre['types']?>
            <form method="post">
                <button class="shadow bg-red-300 hover:bg-red-500 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4"  type="submit" name="delete_genre" value="<?= $genre['ID_genre']?>">Delete</button>
            </form>
            </li>
            </ul>
            <?php endforeach ?>
            </div>
             <form method="post">
            <?php $sth = $dbh->prepare("SELECT ID_genre,types FROM Genre ORDER BY types");
            $sth->execute();
            $tousgenre = $sth->fetchAll();
            echo "<select class='block appearance-none w-48 bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline m-4' name='select_genre' >";
            foreach($tousgenre as $genre){
                echo   "<option value=".$genre["ID_genre"].">".$genre["types"]."</option>";
            } 
            echo "</select>";
            ?>
            <div class="bouton">
            <button class="shadow bg-purple-300 hover:bg-purple-500 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4" type="submit" name="ajout_genre" class="btn btn-primary mb-2">ajout genre</button>
            </div>
            </form>
            
            <form method="post">
            <?php $sth = $dbh->prepare("SELECT ID_acteur,Nom FROM Acteur ORDER BY Nom");
            $sth->execute();
            $tousacteurs = $sth->fetchAll();
            echo "<select class='block appearance-none w-48 bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline m-4' name='select' >";
            foreach($tousacteurs as $acteur){
                echo   "<option value=".$acteur["ID_acteur"].">".$acteur["Nom"]."</option>";
            } 
            echo "</select>";
            ?>
            <div class="bouton">
            <button class="shadow bg-purple-300 hover:bg-purple-500 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4" type="submit" name="ajout_acteur" class="btn btn-primary mb-2">ajout acteur</button>
            </div>
            </form>
            
        <?php foreach($acteurs_film as $acteur):?>
            <ul>
            <li><img  class="m-4 h-64 w-64" src="photoacteur/<?=$acteur['photo']?>" alt="">
            <?=$acteur['Nom']?>
            <form method="post">
                <button class="shadow bg-red-300 hover:bg-red-500 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4" type="submit" name="delete" value="<?= $acteur['ID_acteur']?>">Delete</button>
            </form>
            </li>
            </ul>
            <?php endforeach ?>

            <form method="post">
            <?php $sth = $dbh->prepare("SELECT ID_realisateur,Nom FROM Realisateur ORDER BY Nom");
            $sth->execute();
            $tousrealisateur = $sth->fetchAll();
            echo "<select class='block appearance-none w-48 bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline m-4' name='select_realisateur' >";
            foreach($tousrealisateur as $realisateur){
                echo   "<option value=".$realisateur["ID_realisateur"].">".$realisateur["Nom"]."</option>";
            } 
            echo "</select>";
            ?>
            <div class="bouton">
            <button class="shadow bg-purple-300 hover:bg-purple-500 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4" type="submit" name="ajout_realisateur" class="btn btn-primary mb-2">ajout realisateur</button>
            </div>
            </form>
            
        <?php foreach($realisateurs_film as $realisateur):?>
            <ul>
            <li><img  class="m-4 h-64 w-64" src="photorealisateur/<?=$realisateur['photo']?>" alt="">
            <?=$realisateur['Nom']?>
            <form method="post">
                <button class="shadow bg-red-300 hover:bg-red-500 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4" type="submit" name="delete_realisateur" value="<?= $realisateur['ID_realisateur']?>">Delete</button>
            </form>
            </li>
            </ul>
            <?php endforeach ?>

             <form method="post">
            <?php $sth = $dbh->prepare("SELECT ID_producteur,Nom FROM Producteur ORDER BY Nom");
            $sth->execute();
            $tousproducteurs = $sth->fetchAll();
            echo "<select class='block appearance-none w-48 bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline m-4' name='select_producteur' >";
            foreach($tousproducteurs as $producteur){
                echo   "<option value=".$producteur["ID_producteur"].">".$producteur["Nom"]."</option>";
            } 
            echo "</select>";
            ?>
            <div class="bouton">
            <button class="shadow bg-purple-300 hover:bg-purple-500 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4" type="submit" name="ajout_producteur" class="btn btn-primary mb-2">ajout producteur</button>
            </div>
            </form>
            
        <?php foreach($producteurs_film as $producteur):?>
            <ul>
            <li><img  class="m-4 h-64 w-64" src="photoproducteur/<?=$producteur['photo']?>" alt="">
            <?=$producteur['Nom']?>
            <form method="post">
                <button class="shadow bg-red-300 hover:bg-red-500 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4" type="submit" name="delete_producteur" value="<?= $producteur['ID_producteur']?>">Delete</button>
            </form>
            </li>
            </ul>
            <?php endforeach ?>
            <a class="shadow bg-blue-300 hover:bg-blue-500 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4" href="liste_films.php?ID=<?=$_SESSION['ID']?>">Liste des films</a>
           <a class="shadow bg-blue-300 hover:bg-blue-500 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4" href="https://lefevre.simplon-charleville.fr/allosimplon/catalogue.php?ID=<?=$_SESSION['ID']?>">Retour a la Gallerie</a>
           
            
            </body>
            </html>
            