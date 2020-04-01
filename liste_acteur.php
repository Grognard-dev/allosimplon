<?php
ini_set("display_errors","1");
error_reporting(E_ALL);

$config = require "config.php";

$dbh = new PDO($config["dsn"], $config["utilisateur"], $config["mdp"]);

$liste = $dbh->prepare("SELECT * FROM Acteur");
$liste->execute();
$acteurs = $liste->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acteur</title>
</head>
<body>
    <h1>Acteurs</h1>
    <table>
        <tr>
            <th>
                ID
            </th>
            <th>
                Nom
            </th>
        </tr>
<?php foreach($acteurs as $acteur):?>
<tr>
    <td><?= $acteur['ID']?></td>
    <td><?= $acteur['Nom']?></td>
    <td><a href="editer_acteur.php?ID=<?=$acteur['ID']?>">modifier</a></td>
</tr>

<?php endforeach ?>

    </table>
    <a href="insertion_acteur.php">Ajouter un Acteur</a>
</body>
</html>