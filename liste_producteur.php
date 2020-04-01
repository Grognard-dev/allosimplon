<?php
ini_set("display_errors","1");
error_reporting(E_ALL);

$config = require "config.php";

$dbh = new PDO($config["dsn"], $config["utilisateur"], $config["mdp"]);

$liste = $dbh->prepare("SELECT * FROM Producteur");
$liste->execute();
$producteurs = $liste->fetchAll();
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
    <td><?= $producteur['ID']?></td>
    <td><?= $producteur['Nom']?></td>
    <td><a href="editer_producteur.php?ID=<?=$producteur['ID']?>">modifier</a></td>
</tr>

<?php endforeach ?>

    </table>
    <a href="insertion_producteur.php">Ajouter un Producteur</a>
</body>
</html>