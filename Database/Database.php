<?php
namespace Database;
class Database {

    private $dbh;

    public function __construct(string $host, string $name, string $user, string $pass, string $port = "3306")
    {
        try 
        {
            $this->dbh = new \PDO("mysql:host=$host;port=$port;dbname=$name", $user, $pass);
        } 
        catch(\PDOException $e) 
        {
            print "Erreur !: " . $e->getMessage() . "<br>"; // Affichage du message d'erreur
            die(); // Arrêt du script
        }
    }

    public function getConnection() {
        return $this->dbh;
    }
}