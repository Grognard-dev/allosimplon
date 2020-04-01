<?php
function e($string, $flags=ENT_QUOTES){
    return htmlspecialchars ($string,$flags);
}
ini_set("display_errors","1");
error_reporting(E_ALL);

$config = require "config.php";

$dbh = new PDO($config["dsn"], $config["utilisateur"], $config["mdp"]);

$liste = $dbh->prepare("SELECT * FROM Realisateur");
$liste->execute();
$realisateurs = $liste->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Realisateur</title>
</head>
<body>
    <h1>Realisateur</h1>
    <table>
        <tr>
            <th>
                ID
            </th>
            <th>
                Nom
            </th>
        </tr>
<?php foreach($realisateurs as $realisateur):?>
<tr>
    <td><?= $realisateur['ID']?></td>
    <td><?= $realisateur['Nom']?></td>
    <td><a href="editer_realisateur.php?ID=<?=$realisateur['ID']?>">modifier</a></td>
</tr>

<?php endforeach ?>

    </table>
    <a href="insertion_realisateur.php">Ajouter un Realisateur</a>
</body>
</html>