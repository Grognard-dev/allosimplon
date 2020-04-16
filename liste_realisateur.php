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
    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <h1 class="shadow bg-center focus:shadow-outline focus:outline-none text-black font-bold py-2 px-4 rounded m-4 text-5xl">Realisateur</h1>
    <table  class="flex justify-center flex-wrap">
        <tr class="flex">
            <th class="flex-initial text-black-700 text-center bg-yellow-200 px-4 py-2 m-2">
                ID
            </th>
            <th class="flex-initial text-black-700 text-center bg-purple-200 px-4 py-2 m-2">
                Nom
            </th>
        </tr>
<?php foreach($realisateurs as $realisateur):?>
<tr>
    <td class="flex-initial flex-col text-black-700 text-center bg-yellow-200 px-2 py-1 m-2"><?= $realisateur['ID_realisateur']?></td>
    <td class="flex-initial flex-col text-black-700 text-center bg-purple-200 px-2 py-1 m-2"><?= $realisateur['Nom']?></td>
    <td class="flex-initial text-black-700 text-center  px-4 py-2 m-2">
        <a class="shadow bg-green-400 hover:bg-green-600 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4" href="editer_realisateur.php?ID=<?=$realisateur['ID_realisateur']?>">modifier</a></td>
    <td>
     <form method="post">
                <button class=" flex shadow bg-red-400 hover:bg-red-600 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4" type="submit" name="delete_realisateur" value="<?= $realisateur['ID_realisateur']?>">Delete realisateur</button>
            </form>
    </td>


<?php endforeach ?>

   <td class="flex-initial text-black-700 text-center  px-4 py-1 m-2">
    <a class="shadow bg-blue-500 hover:bg-blue-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4" href="insertion_realisateur.php">Ajouter un Realisateur</a>
    </td>
    
    </tr>
    </table>
    <a class="shadow bg-blue-500 hover:bg-blue-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4" href="admin.php?ID=<?=$_SESSION['ID']?>"> Retour liste Admin</a>
</body>
</html>