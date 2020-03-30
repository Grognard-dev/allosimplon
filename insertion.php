<?php
header('Content-type: text/html; charset=utf-8');
require_once 'styleswitcher.php';
ini_set("display_errors","1");
error_reporting(E_ALL);

$config = require "config.php";

$dbh = new PDO($config["dsn"], $config["utilisateur"], $config["mdp"]);

     if (isset($_POST['bouton'])){
        $Nom_du_film = empty($_POST['Nom_du_film']) ? null : $_POST['Nom_du_film'];
        $Date_de_sortie = empty($_POST['Date_de_sortie']) ? null : $_POST['Date_de_sortie'];
        $Resumer_du_film= empty($_POST['Resumer_du_film']) ? null : $_POST['Resumer_du_film'];
        $synopsis = empty($_POST['synopsis']) ? null : $_POST['synopsis'];

        if ($Nom_du_film === null || $Date_de_sortie === null  || $Resumer_du_film === null || $synopsis === null) {
            $erreur = 'Veuillez remplir tous les champs';
        }else {
            $insertion = $dbh->prepare ("INSERT INTO Film (Nom_du_film, Date_de_sortie, Resumer_du_film, synopsis) 
            VALUES (:Nom_du_film, :Date_de_sortie, :Resumer_du_film, :synopsis)") ;
            
            $insertion->bindValue(':Nom_du_film', $Nom_du_film);
            $insertion->bindValue(':Date_de_sortie', $Date_de_sortie);
            $insertion->bindValue(':Resumer_du_film', $Resumer_du_film);
            $insertion->bindValue(':synopsis', $synopsis);
            $insertion->execute();
        }
    }
    var_dump($_FILES['Affiche']);
if (isset($_FILES['Affiche']) AND $_FILES['Affiche'] == 0)
{
    if ($_FILES['Affiche']['size'] <= 250000)
    {
        $fichier = pathinfo($_FILES['Affiche']);
            move_uploaded_file($_FILES['Affiche'], 'img/' . basename($_FILES['Affiche']));
            $requete = $dbh->prepare("INSERT INTO Film (Affiche) VALUES (:Affiche)");
            $requete->bindValue(':Affiche', $Affiche);
            $requete->execute(array($_FILES['Affiche']));
            
        }else{
            $erreur = "un problème de téléchargement est survenu !!";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>

    <link rel="stylesheet" href="css/reset.css">
    
    <link rel="stylesheet" media="screen, projection" type="text/css" id="css" href="<?php echo $url; ?>" />

    <!--GOOGLE FONTS-->

    <link
        href="https://fonts.googleapis.com/css?family=Baloo+Tammudu+2:400,500,600,700,800|Ubuntu:300,300i,400,400i,500,500i,700,700i&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css?family=Raleway:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i|Rubik:300,300i,400,400i,500,500i,700,700i,900,900i&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css?family=Asap:400,400i,500,500i,600,600i,700,700i|Bellota+Text:300,300i,400,400i,700,700i&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Orbitron:700,800,900|Quicksand:300,400,500,600,700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">




</head>

<body>

<?php 
include 'include/nav.php'; ?>
<form action="insertion.php" method="POST" enctype="multipart/form-data">
<h2>Film insertion</h2>

<label><b>Nom du film</b></label>
<input class="login" type="text" placeholder="Nom du film" name="Nom_du_film" required> <br>

<label><b>Date de sortie</b></label>
<input class="login" type="text" placeholder="Date de sortie" name="Date_de_sortie" required> <br>

<label><b>Resumer du film</b></label>
<input class="login" type="text-area" placeholder="Resumer du film" name="Resumer_du_film" required> <br>

<label><b>synopsis</b></label>
<input class="login" type="text-area" placeholder="synopsis" name="synopsis" required> <br>

<label><b>Affiche</b></label>
<input type="hidden" name="size" value="250000" />
<input type="file" name="Affiche" size=2000 />

<div class="bouton">
<button type="submit" name="bouton" class="btn btn-primary mb-2">insérer</button>
</div>
</form>

<?php 
include 'include/footer.php'; ?>

</body>
</html>
/*if (isset($_POST['bouton'])){
if (isset($_FILES['image']) AND $_FILES['image'] == 0)
{
    if ($_FILES['image']['size'] <= 250000)
    {
        $fichier = pathinfo($_FILES['image']);
            move_uploaded_file($_FILES['image'], '/img' . basename($_FILES['image']));
            $requete = $dbh->prepare('INSERT INTO photo (chemin) VALUES (:chemin)');
            $requete->execute(array($_FILES['image']));
            
        }else{
            $erreur = "un problème de téléchargement est survenu !!";
        }
    }
    */