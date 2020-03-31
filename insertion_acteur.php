<?php
ini_set("display_errors","1");
error_reporting(E_ALL);
?>

<form action="insertion_acteur.php" method="POST" enctype="multipart/form-data">
<div class="formulaire">
<input type="text"  placeholder="Nom" name="Nom">
</div>
<div class="formulaire">
<input type="text"  placeholder="Date_de_naissance" name="Date_de_naissance">
</div>
<div class="formulaire">
<input type="text"  placeholder="Pays_d_origine" name="Pays_d_origine">
</div>
<div class="formulaire">
<input type="text"  placeholder="biographie" name="biographie">
</div>
<input type="hidden" name="size" value="250000" />
<input type="file" name="photo" size=20000000 />
</div>
</div>
<?php
$config = require "config.php";

$dbh = new PDO($config["dsn"], $config["utilisateur"], $config["mdp"]);

$Nom=$_POST['Nom'];
$Date_de_naissance=$_POST['Date_de_naissance'];
$Pays_d_origine=$_POST['Pays_d_origine'];
$biographie=$_POST['biographie'];

$pdoStat = $dbh->prepare("INSERT INTO Acteur (Nom, Date_de_naissance, Pays_d_origine, biographie) VALUES ( :Nom, :Date_de_naissance, :Pays_d_origine, :biographie)");
$pdoStat->execute(array(
    ':Nom' => $Nom,
    ':Date_de_naissance'=>$Date_de_naissance,
    ':Pays_d_origine'=>$Pays_d_origine,
    ':biographie'=>$biographie
));

$ID = $dbh->lastInsertId();
if (isset($_FILES['photo']))
{  
    if ($_FILES['photo']['size'] <= 25000000)
    {  
        move_uploaded_file($_FILES['photo']['tmp_name'], 'photoacteur/' . $_FILES['photo']['name']);
        $requete = $dbh->prepare("UPDATE Acteur SET photo = :photo WHERE ID = :ID ");
        $requete->bindValue(':ID', $ID);
        $requete->bindValue(':photo', $_FILES['photo']['name']);
        var_dump($_FILES['photo']['name']);
        $requete->execute();
        
    }else{  
        $erreur = "un problème de téléchargement est survenu !!";
    }
}
?>
<div class="boutonss">
<button type="submit" class="btn btn-primary mb-2">Envoyé</button>
</div>
</form>
<?php include 'editer.php'; ?>
