<?php
header('Content-type: text/html; charset=utf-8');
require_once 'styleswitcher.php';
            $config = require "config.php";
    
            $dbh = new PDO($config["dsn"], $config["utilisateur"], $config["mdp"]);

             if (isset($_POST['bouton'])){
        $pseudo_user = empty($_POST['pseudo_user']) ? null : $_POST['pseudo_user'];
        $nom_user = empty($_POST['nom_user']) ? null : $_POST['nom_user'];
        $prenom_user = empty($_POST['prenom_user']) ? null : $_POST['prenom_user'];
        $email_user= empty($_POST['email_user']) ? null : $_POST['email_user'];
        $password_user = empty($_POST['password_user']) ? null : $_POST['password_user'];

        if ($pseudo_user === null || $nom_user === null || $prenom_user === null || $email_user === null || $password_user === null) {
            $erreur = 'Veuillez remplir tous les champs';
        }else {
            $inscription = $dbh->prepare ("INSERT INTO utilisateur (Email, mot_de_passe, Prenom, Nom, Pseudo) 
            VALUES (:Email, :mot_de_passe, :Prenom, :Nom, :Pseudo)") ;
            
            $inscription->bindValue(':Pseudo', $pseudo_user);
            $inscription->bindValue(':Email', $email_user);
            $inscription->bindValue(':mot_de_passe', password_hash($password_user, PASSWORD_DEFAULT ));
            $inscription->bindValue(':Prenom', $prenom_user);
            $inscription->bindValue(':Nom', $nom_user);
            
            $inscription->execute();
           header('Location: /allosimplon/connexion.php');
           die;
        }
    }
            ?> 



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>

    <link rel="stylesheet" href="css/reset.css">
    
    <link rel="stylesheet" media="screen, projection" type="text/css" id="css" href="<?php echo $url; ?>" />

    <!--GOOGLE FONTS-->

    <link
        href="https://fonts.googleapis.com/css?family=Baloo+Tammudu+2:400,500,600,700,800|Ubuntu:300,300i,400,400i,500,500i,700,700i&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css?family=Raleway:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i|Rubik:300,300i,400,400i,500,500i,700,700i,900,900i&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css?family=Asap:400,400i,500,500i,600,600i,700,700i|Bellota+Text:300,300i,400,400i,700,700i&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Orbitron:700,800,900|Quicksand:300,400,500,600,700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">




</head>

<body>

<?php 
include 'include/nav.php'; ?>


  <!-- zone d'inscription' -->

    <div id="container">
      

        <form action="inscription.php" method="POST">
            <h2>Inscription</h2>

            <label><b>Pseudo d'utilisateur</b></label>
            <input class="login" type="text" name="pseudo_user" required> <br>
            
            <label><b>Nom d'utilisateur</b></label>
            <input class="login" type="text" name="nom_user" required> <br>

            <label><b>Prenom d'utilisateur</b></label>
            <input class="login" type="text" name="prenom_user" required> <br>

            <label><b>Email d'utilisateur</b></label>
            <input class="login" type="text" name="email_user" required> <br>

            <label><b>Mot de passe</b></label>
            <input class="login"  type="password" placeholder="Mot de passe" name="password_user" required><br>

             <div class="bouton">
                <button type="submit" name="bouton" class="btn btn-primary mb-2">s'inscrire</button>
            </div>
            <?php if(isset($erreur)){
                echo "<p>$erreur</p>";
        }
            ?>

        </form>
    </div>


<?php 
include 'include/footer.php'; ?>

</body>
</html>