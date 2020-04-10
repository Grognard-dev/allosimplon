<?php

require "boot.php";
require "securite.php";

try{
    $dbh = new PDO($config["dsn"], $config["utilisateur"], $config["mdp"]);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    if (isset($_POST['bouton'])){
        $Nom_du_film = empty($_POST['Nom_du_film']) ? null : $_POST['Nom_du_film'];
        $Date_de_sortie = empty($_POST['Date_de_sortie']) ? null : $_POST['Date_de_sortie'];
        $synopsis = empty($_POST['synopsis']) ? null : $_POST['synopsis'];
        
        if ($Nom_du_film === null || $Date_de_sortie === null  || $synopsis === null) {
            $erreur = 'Veuillez remplir tous les champs';
        }else {
            $dbh->beginTransaction();
            $insertion_film = $dbh->prepare ("INSERT INTO Film (Nom_du_film, Date_de_sortie, synopsis) 
            VALUES (:Nom_du_film, :Date_de_sortie, :synopsis)") ;
            
            $insertion_film->bindValue(':Nom_du_film', $Nom_du_film);
            $insertion_film->bindValue(':Date_de_sortie', $Date_de_sortie);
            $insertion_film->bindValue(':synopsis', $synopsis);
            $insertion_film->execute();
            
            $ID_Film = $dbh->lastInsertId();
            
            //==========================================================
            //   insertion de plusieur genres 
            //==========================================================
            if(isset($_POST['types'])){
                $insert_genre = $dbh->prepare("INSERT INTO type_film (ID_genre,ID_film) VALUES (:ID_genre,:ID_film)");
                foreach($_POST['types'] as $genre){
                    $insert_genre->bindValue(':ID_genre',$genre);
                    $insert_genre->bindValue(':ID_film',$ID_Film);
                    $insert_genre->execute();
                }
            }else{
                throw new Exception('Pas de genres');
            }
            
            //==========================================================
            //   insertion de plusieur acteur 
            //==========================================================
            
            if(isset($_POST['Nom'])){
                $insert_acteur = $dbh->prepare("INSERT INTO joue (ID_Acteur,ID_film) VALUES (:ID_Acteur,:ID_film)");
                foreach($_POST['Nom'] as $acteur){
                    $insert_acteur->bindValue(':ID_Acteur',$acteur);
                    $insert_acteur->bindValue(':ID_film',$ID_Film);
                    $insert_acteur->execute();
                }
            }
            
            //==========================================================
            //   insertion de plusieur Producteur 
            //==========================================================
            
            if(isset($_POST['Nom_producteur'])){
                $insert_producteur = $dbh->prepare("INSERT INTO produit (ID_Producteur,ID_film) VALUES (:ID_Producteur,:ID_film)");
                foreach($_POST['Nom_producteur'] as $producteur){
                    $insert_producteur->bindValue(':ID_Producteur',$producteur);
                    $insert_producteur->bindValue(':ID_film',$ID_Film);
                    $insert_producteur->execute();
                }
            }
            
            //==========================================================
            //   insertion de plusieur Realisateur 
            //==========================================================
            
            if(isset($_POST['Nom_realisateur'])){
                $insert_realisateur = $dbh->prepare("INSERT INTO realise (ID_realisateur,ID_Film) VALUES (:ID_realisateur,:ID_Film)");
                foreach($_POST['Nom_realisateur'] as $realisateur){
                    $insert_realisateur->bindValue(':ID_realisateur',$realisateur);
                    $insert_realisateur->bindValue(':ID_Film',$ID_Film);
                    $insert_realisateur->execute();
                }
            }
            
            
            //==========================================================
            //   insertion de l'Affiche
            //==========================================================
            if (isset($_FILES['Affiche']))
            {  
                if ($_FILES['Affiche']['size'] <= 2000000)
                {  
                    $chemin =  'affiche/' . $_FILES['Affiche']['name'];
                    move_uploaded_file($_FILES['Affiche']['tmp_name'],$chemin);
                    if($_FILES['Affiche']['type'] === 'image/jpeg'){
                        $image = imagecreatefromjpeg($chemin);
                    }elseif($_FILES['Affiche']['type'] === 'image/png'){
                        $image = imagecreatefrompng($chemin);
                    }else {
                        $_SESSION['flash'] = " Pas le bon format d'image, format accepter jpeg,png";
                        header('location: insertion_film.php');
                        die;
                    }
                    $return_image = imagescale($image,350);
                    if($_FILES['Affiche']['type'] === 'image/jpeg'){
                        imagejpeg($return_image,$chemin);
                    }elseif($_FILES['Affiche']['type'] === 'image/png'){
                        imagepng($return_image,$chemin);
                    }
                    
                    $requete = $dbh->prepare("UPDATE Film SET Affiche = :Affiche WHERE ID_film = :ID ");
                    $requete->bindValue(':ID', $ID_Film);
                    $requete->bindValue(':Affiche', $_FILES['Affiche']['name']);
                    
                    $requete->execute();
                    
                }else{  
                    $_SESSION['flash'] = "un problème de téléchargement est survenu !!";
                    header('location: insertion_film.php');
                    die;
                }
            }
            $dbh->commit();
        }
    }
}
catch(PDOException $p){
    $_SESSION['flash'] = "Une erreur PDO est survenue";
    header('location: insertion_film.php');
    die;
}
catch(Exception $e){
    $_SESSION['flash'] = $e->getMessage();
    header('location: insertion_film.php');
    die;
}
$requete_acteurs = $dbh->prepare("SELECT * FROM Acteur");
$requete_acteurs->execute();
$acteurs_film = $requete_acteurs->fetchAll();

$requete_genres = $dbh->prepare("SELECT * FROM Genre");
$requete_genres->execute();
$genres_film = $requete_genres->fetchAll();

$requete_realisateurs = $dbh->prepare("SELECT * FROM Realisateur");
$requete_realisateurs->execute();
$realisateurs_film = $requete_realisateurs->fetchAll();

$requete_producteurs = $dbh->prepare("SELECT * FROM Producteur");
$requete_producteurs->execute();
$producteurs_film = $requete_producteurs->fetchAll();

?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>insertion film</title>
<link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/style.css">
</head>
<body>


<form action="insertion_film.php" method="POST" enctype="multipart/form-data">
<h2 class="shadow .bg-center focus:shadow-outline focus:outline-none text-black font-bold py-2 px-4 rounded m-4 text-5xl">Page d'insertion de films</h2>
<?php flash();?>

<label class="shadow bg-blue-500 .bg-center focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4 "><b>Nom du film</b></label><br>
<input class="block appearance-none w-48 bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline m-4"  type="text" placeholder="Nom du film" name="Nom_du_film" required> <br>

<label class="shadow bg-blue-500 .bg-center focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4"><b>Date de sortie</b></label><br>
<input class="block appearance-none w-48 bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline m-4 "  type="text" placeholder="Date de sortie" name="Date_de_sortie" required> <br>

<label class="shadow bg-blue-500 .bg-center focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4"><b>synopsis</b></label><br>
<textarea class="block appearance-none w-1/2 bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline m-4 " rows="6" cols="100" name="synopsis" required></textarea> <br>

<input class="form-champs" type="hidden" name="size" value="2000000" />
<input class="form-champs" type="file" name="Affiche" size=2000 />

<h2 class="shadow bg-blue-500 .bg-center focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4 max-w-titre">Acteurs</h2>
<div id="acteur">

</div>
<button id="ajout_acteur" type="button" class="shadow bg-blue-500 hover:bg-blue-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4">ajout acteur</button>



<h2 class="shadow bg-blue-500 .bg-center focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4 max-w-titre" >Genre</h2>
<div id="types">

</div>
<button id="ajout_genre" type="button" class="shadow bg-blue-500 hover:bg-blue-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4">ajout genre</button>
<br>


<h2 class="shadow bg-blue-500 .bg-center focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4 max-w-titre">Producteur</h2>
<div id="producteur">

</div>
<button id="ajout_producteur" type="button" class="shadow bg-blue-500 hover:bg-blue-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4">ajout Producteur</button>
<br>


<h2 class="shadow bg-blue-500 .bg-center focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4 max-w-titre">Realisateur</h2>
<div id="realisateur">

</div>
<button id="ajout_realisateur" type="button" class="shadow bg-blue-500 hover:bg-blue-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4">ajout Realisateur</button>
<br>

<div class="bouton">
<button type="submit" name="bouton" class="shadow bg-purple-500 hover:bg-blue-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4">insérer</button>
</div>

</form>
<a class="shadow bg-purple-500 hover:bg-blue-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded m-4" href="liste_films.php">Liste des films</a>

<select style="display: none" id="acteur_select_tpl" class="block appearance-none w-48 bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline m-4">
<option value=""></option>
<?php foreach($acteurs_film as $acteur): ?>
    <option value="<?= $acteur['ID_acteur']?>"><?=$acteur['Nom']?></option>
    <?php endforeach ?>
    </select>
    
    <select style="display: none" id="genre_select_tpl" class="block appearance-none w-48 bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline m-4">
    <option value=""></option>
    <?php foreach($genres_film as $genre): ?>
        <option value="<?= $genre['ID_genre']?>"><?=$genre['types']?></option>
        <?php endforeach ?>
        </select>
        
        <select style="display: none" id="producteur_select_tpl" class="block appearance-none w-48 bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline m-4">
        <option value=""></option>
        <?php foreach($producteurs_film as $producteur): ?>
            <option value="<?= $producteur['ID_producteur']?>"><?=$producteur['Nom']?></option>
            <?php endforeach ?>
            </select>
            
            <select style="display: none" id="realisateur_select_tpl" class="block appearance-none w-48 bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline m-4"> 
            <option value=""></option>
            <?php foreach($realisateurs_film as $realisateur): ?>
                <option value="<?= $realisateur['ID_realisateur']?>"><?=$realisateur['Nom']?></option>
                <?php endforeach ?>
                </select>
                
                <script>
                var ajout_acteur = document.getElementById('ajout_acteur');
                var acteur = document.getElementById('acteur');
                var acteur_select_tpl = document.getElementById('acteur_select_tpl');
                var index = 0;
                
                ajout_acteur.addEventListener('click', function(){
                    var divacteur = document.createElement('div');
                    divacteur.className = 'acteur';
                    
                    var selectacteur = acteur_select_tpl.cloneNode(true);
                    selectacteur.id = 'acteur' + index;
                    selectacteur.style.display = 'block';
                    selectacteur.name = 'Nom[' + index + ']';
                    divacteur.appendChild(selectacteur);
                    acteur.appendChild(divacteur);
                    
                    index = index + 1;
                });
                
                var ajout_genre = document.getElementById('ajout_genre');
                var genres = document.getElementById('types');
                var genre_select_tpl = document.getElementById('genre_select_tpl');
                var index = 0;
                
                ajout_genre.addEventListener('click', function(){
                    var divgenre = document.createElement('div');
                    divgenre.className = 'genre';
                    
                    var selectgenre = genre_select_tpl.cloneNode(true);
                    selectgenre.id = 'genre' + index;
                    selectgenre.style.display = 'block';
                    selectgenre.name = 'types[' + index + ']';
                    divgenre.appendChild(selectgenre);
                    genres.appendChild(divgenre);
                    
                    index = index + 1;
                });
                
                var ajout_producteur = document.getElementById('ajout_producteur');
                var producteur = document.getElementById('producteur');
                var producteur_select_tpl = document.getElementById('producteur_select_tpl');
                var index = 0;
                
                ajout_producteur.addEventListener('click', function(){
                    var divproducteur = document.createElement('div');
                    divproducteur.className = 'producteur';
                    
                    var selectproducteur = producteur_select_tpl.cloneNode(true);
                    selectproducteur.id = 'producteur' + index;
                    selectproducteur.style.display = 'block';
                    selectproducteur.name = 'Nom_producteur[' + index + ']';
                    divproducteur.appendChild(selectproducteur);
                    producteur.appendChild(divproducteur);
                    
                    index = index + 1;
                });
                
                var ajout_realisateur = document.getElementById('ajout_realisateur');
                var realisateur = document.getElementById('realisateur');
                var realisateur_select_tpl = document.getElementById('realisateur_select_tpl');
                var index = 0;
                
                ajout_realisateur.addEventListener('click', function(){
                    var divrealisateur = document.createElement('div');
                    divrealisateur.className = 'realisateur';
                    
                    var selectrealisateur = realisateur_select_tpl.cloneNode(true);
                    selectrealisateur.id = 'realisateur' + index;
                    selectrealisateur.style.display = 'block';
                    selectrealisateur.name = 'Nom_realisateur[' + index + ']';
                    divrealisateur.appendChild(selectrealisateur);
                    realisateur.appendChild(divrealisateur);
                    
                    index = index + 1;
                });
                </script>
                </body>
                </html>
                
                
                