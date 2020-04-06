<?php
session_start();
ini_set("display_errors","1");
error_reporting(E_ALL);

$config = require "config.php";

$dbh = new PDO($config["dsn"], $config["utilisateur"], $config["mdp"]);

$liste = $dbh->prepare("SELECT * FROM Producteur");
$liste->execute();
$producteurs = $liste->fetchAll();
if(isset($_POST['delete_producteur'])){
    $delete=$dbh->prepare("DELETE FROM Producteur WHERE ID_producteur = :ID LIMIT 1");
    $delete->bindValue(':ID',$_POST['delete_producteur']);
    $delete->execute();
    $_SESSION['flash'] = "Suppression effectuée";
        header('Location: liste_producteur.php?ID='.$_SESSION['ID']);
        die;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Producteur</title>
</head>
<body>
    <h1>Producteurs</h1>
    <table>
        <tr>
            <th>
                ID
            </th>
            <th>
                Nom
            </th>
        </tr>
<?php foreach($producteurs as $producteur):?>
<tr>
    <td><?= $producteur['ID_producteur']?></td>
    <td><?= $producteur['Nom']?></td>
    <td><a href="editer_producteur.php?ID=<?=$producteur['ID_producteur']?>">modifier</a></td>
</tr>
 <td>
     <form method="post">
                <button type="submit" name="delete_producteur" value="<?= $producteur['ID_producteur']?>">Delete producteur</button>
            </form>
    </td>

<?php endforeach ?>

    </table>
    <a href="insertion_producteur.php">Ajouter un Producteur</a>
     <br>
    <a href="admin.php?ID=<?=$_SESSION['ID']?>"> Retour liste Admin</a>
</body>
</html>