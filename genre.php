<?php
$config = require "config.php";

$dbh = new PDO($config["dsn"], $config["utilisateur"], $config["mdp"]);

if (isset($_POST['bouton'])){
        $genre = empty($_POST['genre']) ? null : $_POST['genre'];
        

        if ($genre === null ) {
            $erreur = 'Veuillez remplir tous les champs';
        }else {
            $insertion_film = $dbh->prepare ("INSERT INTO Genre (genre) 
            VALUES (:genre)") ;
            
            $insertion_film->bindValue(':genre', $genre);
            $insertion_film->execute();
        }
    }
?>
<form action="genre.php" method="POST" enctype="multipart/form-data">
<h2>Film insertion</h2>

<label><b>Nom du film</b></label>
<input class="login" type="text" placeholder="Genre" name="genre" required> <br>

<div class="bouton">
<button type="submit" name="bouton" class="btn btn-primary mb-2">insérer</button>
</div>
</form>