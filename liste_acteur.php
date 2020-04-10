<?php
require "boot.php";
require "securite.php";

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
    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <h1 class="shadow .bg-center focus:shadow-outline focus:outline-none text-black font-bold py-2 px-4 rounded m-4 text-5xl">Acteurs</h1>
    <table >
        <tr class="flex m-3">
            <th class="flex-initial text-black-700 text-center bg-yellow-200 px-4 py-2 m-2">
                ID
            </th>
            <th class="flex-initial text-black-700 text-center bg-purple-200 px-4 py-2 m-2">
                Nom
            </th>
        </tr>
<?php foreach($acteurs as $acteur):?>
<tr class="flex ">
    <td class="flex-initial text-black-700 text-center bg-yellow-200 px-4 py-2 m-2"><?= $acteur['ID_acteur']?></td>
    <td class="flex-initial text-black-700 text-center bg-purple-200 px-4 py-2 m-2"><?= $acteur['Nom']?></td>
    <td class="flex-initial text-black-700 text-center  px-4 py-2 m-2"><a class="shadow bg-green-400 hover:bg-green-600 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4"  href="editer_acteur.php?ID=<?=$acteur['ID_acteur']?>">modifier</a></td>
</tr>
 <td>
     <form method="post">
                <button class=" flex shadow bg-red-400 hover:bg-red-600 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4"  type="submit" name="delete_acteur" value="<?= $acteur['ID_acteur']?>">Delete realisateur</button>
            </form>
    </td>

<?php endforeach ?>

    </table>
    <a class="shadow bg-blue-500 hover:bg-blue-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4"  href="insertion_acteur.php">Ajouter un Acteur</a>
     
    <a class="shadow bg-blue-500 hover:bg-blue-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4"  href="admin.php?ID=<?=$_SESSION['ID']?>"> Retour liste Admin</a>
</body>
</html>