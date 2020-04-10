<?php
require "boot.php";
require "securite.php";

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
    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <h1 class="shadow .bg-center focus:shadow-outline focus:outline-none text-black font-bold py-2 px-4 rounded m-4 text-5xl">Films</h1>
    <table class="flex justify-center flex-wrap">
        <tr class="flex">
            <th class="flex-initial text-black-700 text-center bg-yellow-200 px-4 py-2 m-2">
                ID
            </th>
            <th class="flex-initial text-black-700 text-center bg-purple-200 px-4 py-2 m-2">
                Titre du Film
            </th>
        </tr>
<?php foreach($films as $film):?>
<tr  >
    <td class="flex-initial flex-col text-black-700 text-center bg-yellow-200 px-2 py-1 m-2"><?= $film['ID_film']?></td>
    <td class="flex-initial flex-col text-black-700 text-center bg-purple-200 px-2 py-1 m-2"><?= $film['Nom_du_film']?></td>
    <td class="flex-initial text-black-700 text-center  px-4 py-1 m-2"><a class="flex-initial shadow bg-green-400 hover:bg-green-600 focus:shadow-outline focus:outline-none text-white font-bold py-2  px-4 rounded m-2" href="editer_film.php?ID=<?=$film['ID_film']?>">modifier</a>
    </td>
    <td class="flex-initial text-black-700 text-center  px-4 py-1 m-2">
     <form method="post">
                <button class=" flex-initial shadow bg-red-400 hover:bg-red-600 focus:shadow-outline focus:outline-none text-white font-bold py-1  px-4 rounded m-2" type="submit" name="delete_film" value="<?= $film['ID_film']?>">Delete film</button>
            </form>
    </td>

 

<?php endforeach ?>
 <td class="flex-initial text-black-700 text-center  px-4 py-1 m-2" >
     <a class="shadow bg-blue-500 hover:bg-blue-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4" href="insertion_film.php">Ajouter un film
 </a></td>
     
    <td class="flex-initial text-black-700 text-center  px-4 py-1 m-2">
        <a class="shadow bg-blue-500 hover:bg-blue-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4" href="admin.php?ID=<?=$_SESSION['ID']?>"> Retour liste Admin</a></td>
    </tr>
    </table>
   
</body>
</html>