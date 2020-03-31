<?php
ini_set("display_errors","1");
error_reporting(E_ALL);

$config = require "config.php";

$dbh = new PDO($config["dsn"], $config["utilisateur"], $config["mdp"]);

$liste = $dbh->prepare("SELECT * FROM Film");
$liste->execute();
$films = $liste->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Films</title>
</head>
<body>
    <h1>Films</h1>
    <table>
        <tr>
            <th>
                ID
            </th>
            <th>
                Titre du Film
            </th>
        </tr>
<?php foreach($films as $film):?>
<tr>
    <td><?= $film['ID']?></td>
    <td><?= $film['Nom_du_film']?></td>
    <td><a href="editer.php?ID=<?=$film['ID']?>">modifier</a></td>
</tr>

<?php endforeach ?>

    </table>
    <a href="insertion_film.php">Ajouter un film</a>
</body>
</html>