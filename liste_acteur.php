<?php
session_start();
ini_set("display_errors","1");
error_reporting(E_ALL);

$config = require "config.php";

$dbh = new PDO($config["dsn"], $config["utilisateur"], $config["mdp"]);

$liste = $dbh->prepare("SELECT * FROM Acteur");
$liste->execute();
$acteurs = $liste->fetchAll();
if(isset($_POST['delete_acteur'])){
    $delete=$dbh->prepare("DELETE FROM Acteur WHERE ID_acteur = :ID LIMIT 1");
    $delete->bindValue(':ID',$_POST['delete_acteur']);
    $delete->execute();
    $_SESSION['flash'] = "Suppression effectuée";
        header('Location: liste_acteur.php?ID='.$_SESSION['ID']);
        die;
}
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
    <td><?= $acteur['ID_acteur']?></td>
    <td><?= $acteur['Nom']?></td>
    <td><a href="editer_acteur.php?ID=<?=$acteur['ID_acteur']?>">modifier</a></td>
</tr>
 <td>
     <form method="post">
                <button type="submit" name="delete_acteur" value="<?= $acteur['ID_acteur']?>">Delete realisateur</button>
            </form>
    </td>

<?php endforeach ?>

    </table>
    <a href="insertion_acteur.php">Ajouter un Acteur</a>
     <br>
    <a href="admin.php?ID=<?=$_SESSION['ID']?>"> Retour liste Admin</a>
</body>
</html>