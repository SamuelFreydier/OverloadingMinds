<?php

namespace Model\Finder;

use Model\Finder\FinderInterface;
use App\Src\App;
use Model\Gateway\RestaurantGateway;

class RestaurantFinder implements FinderInterface
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
        $query = $this->conn->prepare('SELECT r.id, r.name, r.reputation FROM restaurant r ORDER BY r.name'); // Création de la requête + utilisation order by pour ne pas utiliser sort
        $query->execute(); // Exécution de la requête
        $elements = $query->fetchAll(\PDO::FETCH_ASSOC);

        if(count($elements) === 0) return null;

        $restaurants = [];
        $restaurant = null;
        foreach($elements as $element) {
            $restaurant = new RestaurantGateway($this->app);
            $restaurant->hydrate($element);

            $restaurants[] = $restaurant;
        }
        
        return $restaurants;
    }

    public function findOneById($id)
    {
        $query = $this->conn->prepare('SELECT r.id, r.name, r.reputation FROM restaurant AS r WHERE r.id = :id'); // Création de la requête + utilisation order by pour ne pas utiliser sort
        $query->execute([':id' => $id]); // Exécution de la requête
        $element = $query->fetch(\PDO::FETCH_ASSOC);   
        
        if($element === null) return null;
        
        $restaurant = new RestaurantGateway($this->app);
        $restaurant->hydrate($element);

        return $restaurant;
    }

    public function save(array $restaurant) : bool
    {
        $query = $this->conn->prepare('INSERT INTO restaurant (name, reputation) VALUES (:name, :reputation)'); // Création de la requête + utilisation order by pour ne pas utiliser sort
        return $query->execute([
            ':name' => $restaurant['name'],
            ':reputation' => $restaurant['reputation']
        ]);
    }

    public function update($id, $name, $reputation) {
        $query = $this->conn->prepare('SELECT r.id, r.name, r.reputation FROM restaurant r WHERE r.id = :id'); // Création de la requête + utilisation order by pour ne pas utiliser sort
        $query->execute([':id' => $id]); // Exécution de la requête
        $element = $query->fetch(\PDO::FETCH_ASSOC);

        if($element === null) return null;

        $restaurant = new RestaurantGateway($this->app);
        $restaurant->hydrate($element);
        if($name != "") {
            $restaurant->setName($name);
        }
        if($reputation != "") {
            $restaurant->setReputation($reputation);
        }
        $restaurant->update();
    }

    public function remove($id) {
        $query = $this->conn->prepare('SELECT r.id, r.name, r.reputation FROM restaurant r WHERE r.id = :id'); // Création de la requête + utilisation order by pour ne pas utiliser sort
        $query->execute([':id' => $id]); // Exécution de la requête
        $element = $query->fetch(\PDO::FETCH_ASSOC);

        if($element === null) return null;

        $restaurant = new RestaurantGateway($this->app);
        $restaurant->hydrate($element);
        $restaurant->delete();
    }
}