<?php

namespace Model\Finder;
use Model\Finder\FinderInterface;
use App\Src\App;
use Model\Gateway\UserGateway;

class UserFinder implements FinderInterface
{
    private $conn;
    private $app;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->conn = $this->app->getService('database')->getConnection();
    }

    public function findAll()
    {
        $query = $this->conn->prepare('SELECT u.id, u.username, u.password, u.bio, u.email FROM user u ORDER BY u.username'); // Création de la requête + utilisation order by pour ne pas utiliser sort
        $query->execute(); // Exécution de la requête
        $elements = $query->fetchAll(\PDO::FETCH_ASSOC);

        if(count($elements) === 0) return null;

        $cities = [];
        $city = null;
        foreach($elements as $element) {
            $city = new UserGateway($this->app);
            $city->hydrate($element);

            $cities[] = $city;
        }
        
        return $cities;
    }

    public function findOneById($id)
    {
        $query = $this->conn->prepare('SELECT u.id, u.username, u.password, u.bio, u.email FROM user u WHERE u.id = :id'); // Création de la requête + utilisation order by pour ne pas utiliser sort
        $query->execute([':id' => $id]); // Exécution de la requête
        $element = $query->fetch(\PDO::FETCH_ASSOC);   
        
        if($element === null) return null;
        
        $user = new UserGateway($this->app);
        $user->hydrate($element);

        return $user;
    }

    public function findOneByUsername($username)
    {
        $query = $this->conn->prepare('SELECT u.id, u.username, u.password, u.bio, u.email FROM user u WHERE u.username = :username'); // Création de la requête + utilisation order by pour ne pas utiliser sort
        $query->execute([':username' => $username]); // Exécution de la requête
        $element = $query->fetch(\PDO::FETCH_ASSOC);
        if($element === false) return null;
        
        $user = new UserGateway($this->app);
        $user->hydrate($element);

        return $user;
    }

    public function search($searchString) : array
    {
        $query = $this->conn->prepare('SELECT c.id, c.name, c.country, c.life FROM city c WHERE c.name like :search ORDER BY c.name'); // Création de la requête + utilisation order by pour ne pas utiliser sort
        $query->execute([':search' => '%' . $searchString .  '%']); // Exécution de la requête
        $elements = $query->fetchAll(\PDO::FETCH_ASSOC);

        if(count($elements) === 0) return null;

        $cities=[];
        $city=null;
        foreach($elements as $element) {
            $city = new UserGateway($this->app);
            $city->hydrate($element);

            $cities[] = $city;
        }

        return $cities;
    }

    public function save(array $user) : bool
    {
        $query = $this->conn->prepare('INSERT INTO user (username, password, email) VALUES (:username, :password, :email)'); // Création de la requête + utilisation order by pour ne pas utiliser sort
        return $query->execute([
            ':username' => $user['username'],
            ':password'=> $user['password'],
            ':email' => $user['email']
        ]);
    }

    
}