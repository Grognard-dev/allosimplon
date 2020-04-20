<?php 
require_once "boot.php";
  
     $dbh = new PDO($config["dsn"], $config["utilisateur"], $config["mdp"]); 


class Database{
    protected $utilisateur;
    protected $password;
    protected $dsn;
   

    public function __construct($utilisateur,$password,$dsn)
    {
        $this->utilisateur = $utilisateur;
        $this->password = $password;
        $this->dsn = $dsn;
        $this->PDOConnexion();
    }

    public function PDOConnexion(){
         $dbh = new PDO($this->dsn,$this->utilisateur,$this->password);
          $dbh ->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
          $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->connect = $dbh;
    }

    public function prepareAndExecute(string $requete,array $valeurs)
    {

        $requeteprepare = $this->connect->prepare ($requete) ;
        $requeteprepare->execute($valeurs);
        return $requeteprepare;
    }

    public function lastInsertID()
    {
       return  $this->connect->lastInsertID();
    }

 
}

