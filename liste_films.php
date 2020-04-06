<?php
session_start();
ini_set("display_errors","1");
error_reporting(E_ALL);

$config = require "config.php";

$dbh = new PDO($config["dsn"], $config["utilisateur"], $config["mdp"]);

$liste = $dbh->prepare("SELECT * FROM Film");
$liste->execute();
$films = $liste->fetchAll();
if(isset($_POST['delete_film'])){
    $delete=$dbh->prepare("DELETE FROM Film WHERE ID_film = :ID LIMIT 1");
    $delete->bindValue(':ID',$_POST['delete_film']);
    $delete->execute();
    $_SESSION['flash'] = "Suppression effectuée";
        header('Location: liste_films.php?ID='.$_SESSION['ID']);
        die;
}
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
    <td><?= $film['ID_film']?></td>
    <td><?= $film['Nom_du_film']?></td>
    <td><a href="editer_film.php?ID=<?=$film['ID_film']?>">modifier</a></td>
</tr>
 <td>
     <form method="post">
                <button type="submit" name="delete_film" value="<?= $film['ID_film']?>">Delete film</button>
            </form>
    </td>

<?php endforeach ?>

    </table>
    <a href="insertion_film.php">Ajouter un film</a>
     <br>
    <a href="admin.php?ID=<?=$_SESSION['ID']?>"> Retour liste Admin</a>
</body>
</html>