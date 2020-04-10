<?php
require "boot.php";
require "securite.php";

$dbh = new PDO($config["dsn"], $config["utilisateur"], $config["mdp"]);

$liste = $dbh->prepare("SELECT * FROM utilisateur");
$liste->execute();
$utilisateurs = $liste->fetchAll();

if(isset($_POST['delete_user'])){
    $delete=$dbh->prepare("DELETE FROM utilisateur WHERE ID_utilisateur = :ID LIMIT 1");
    $delete->bindValue(':ID',$_POST['delete_user']);
    $delete->execute();
    $_SESSION['flash'] = "Suppression effectuée";
        header('Location: liste_utilisateur.php?ID='.$_SESSION['ID']);
        die;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User</title>
</head>
<body>
    <h1>User</h1>
    <table>
        <tr>
            <th>
                ID
            </th>
            <th>
                Nom
            </th>
        </tr>
<?php foreach($utilisateurs as $utilisateur):?>
<tr>
    <td><?= e($utilisateur['ID_utilisateur'])?></td>
    <td><?= e($utilisateur['Nom'])?></td>
    <td><a href="editer_utilisateur.php?ID=<?=urlencode($utilisateur['ID_utilisateur'])?>">modifier</a></td>
    <td>
     <form method="post">
                <button type="submit" name="delete_user" value="<?= e($utilisateur['ID_utilisateur'])?>">Delete user</button>
            </form>
    </td>
</tr>

<?php endforeach ?>

    </table>
    <a href="admin.php?ID=<?=$_SESSION['ID']?>"> Retour liste Admin</a>
</body>
</html>