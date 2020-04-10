<?php
require "boot.php";
require "securite.php";

$dbh = new PDO($config["dsn"], $config["utilisateur"], $config["mdp"]);

$liste = $dbh->prepare("SELECT * FROM Realisateur");
$liste->execute();
$realisateurs = $liste->fetchAll();
if(isset($_POST['delete_realisateur'])){
    $delete=$dbh->prepare("DELETE FROM Realisateur WHERE ID_realisateur = :ID LIMIT 1");
    $delete->bindValue(':ID',$_POST['delete_realisateur']);
    $delete->execute();
    $_SESSION['flash'] = "Suppression effectuée";
        header('Location: liste_realisateur.php?ID='.$_SESSION['ID']);
        die;
}
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
    <td><?= $realisateur['ID_realisateur']?></td>
    <td><?= $realisateur['Nom']?></td>
    <td><a href="editer_realisateur.php?ID=<?=$realisateur['ID_realisateur']?>">modifier</a></td>
    <td>
     <form method="post">
                <button type="submit" name="delete_realisateur" value="<?= $realisateur['ID_realisateur']?>">Delete realisateur</button>
            </form>
    </td>
</tr>

<?php endforeach ?>

    </table>
    <a href="insertion_realisateur.php">Ajouter un Realisateur</a>
    <br>
    <a href="admin.php?ID=<?=$_SESSION['ID']?>"> Retour liste Admin</a>
</body>
</html>