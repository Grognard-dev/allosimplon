<?php
ini_set("display_errors","1");
error_reporting(E_ALL);
header('Content-type: text/html; charset=utf-8');
require_once 'styleswitcher.php';

$config = require "config.php";

$dbh = new PDO($config["dsn"], $config["utilisateur"], $config["mdp"]);
$erreur = null;
 
  
if (isset($_POST['bouton'])){
    $pseudo_user = empty($_POST['pseudo_user']) ? null : $_POST['pseudo_user'];
    $password_user = empty($_POST['password_user']) ? null : $_POST['password_user'];
    
    if ($pseudo_user === null || $password_user === null) {
        echo 'Veuillez remplir tous les champs';
    }else {
        $requeteprepare = $dbh->prepare ("SELECT * FROM utilisateur WHERE  Pseudo = :Pseudo") ;
        $requeteprepare->execute(array(':Pseudo' => $pseudo_user));
        $utilisateur = $requeteprepare->fetch(PDO::FETCH_ASSOC);
        if($utilisateur === false){
            $erreur =  "login et / ou mot de passe incorrect";
        }
        
        if(!password_verify($password_user, $utilisateur["mot_de_passe"] ?? '')) {
            $erreur =  "login et / ou mot de passe incorrect";
        }

        if( $erreur === null){
            if (session_status() === PHP_SESSION_NONE){
                session_start();
            }
            session_regenerate_id();
            $_SESSION["ID"] = $utilisateur["ID"];
            $_SESSION["Pseudo"] = $utilisateur["Pseudo"];
            if($utilisateur["Admin"] === "1"){
                $_SESSION['is_admin'] = true;
            }else{
                $_SESSION['is_admin'] = false;
            }
            header('Location: /allosimplon/index.php');
            exit();
        }
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Connexion</title>

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
<?php include 'include/nav.php';?>
<!-- zone de connexion -->

<div id="container">


<form action="" method="POST">
<h2>Connexion</h2>

<?php if($erreur != null){
    echo "<p>$erreur</p>";
}
?>

<label><b>Nom d'utilisateur</b></label>
<input class="login" type="text" name="pseudo_user" required> <br>

<label><b>Mot de passe</b></label>
<input class="login"  type="password" name="password_user" required><br>

   <div class="bouton">
                <button type="submit" name="bouton" class="btn btn-primary mb-2">connexion</button>
            </div>


<?php
if(isset($_GET['erreur'])){
    $err = $_GET['erreur'];
    if($err==1 || $err==2)
    echo "<p style='color:red'>Utilisateur ou mot de passe incorrect</p>";
}
?> 


</form>
</div>


<?php 
include 'include/footer.php'; ?>

</body>
</html>