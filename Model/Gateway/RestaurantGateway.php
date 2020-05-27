<?php

namespace Model\Gateway;

use App\Src\App;

class RestaurantGateway
{
    private $conn;

    private $id;
    private $name;
    private $reputation;

    public function __construct(App $app)
    {
        $this->conn = $app->getService('database')->getConnection();
    }

    public function getId() {
        return $this->id;
    }

    public function getName(){
        return $this->name;
    }

    public function setName($name){
        $this->name = $name;
    }

    public function getReputation() {
        return $this->reputation;
    }
    
    public function setReputation($reputation) {
        $this->reputation = $reputation;
    }

    public function insert() : void {
        $query = $this->conn->prepare('INSERT INTO restaurant (name, reputation) VALUES (:name, :reputation)');
        $executed = $query->execute([
            ':name' => $this->name,
            ':reputation' => $this->reputation
        ]);

        if(!$executed) throw new \Error('Insert Failed');

        $this->id = $this->conn->lastInsertId();
    }

    public function update() : void {
        if(!$this->id) throw new \Error('Instance does not exist in base');

        $query = $this->conn->prepare('UPDATE restaurant SET name = :name, reputation = :reputation WHERE id = :id');
        $executed = $query->execute([
            ':name' => $this->name,
            ':reputation' => $this->reputation,
            ':id' => $this->id
        ]);

        if(!$executed) throw new \Error('Update Failed');
    }

    public function delete() : void {
        if(!$this->id) throw new \Error('Instance does not exist in base');

        $query = $this->conn->prepare('DELETE FROM restaurant WHERE id = :id');
        $executed = $query->execute([
            ':id' => $this->id
        ]);

        if(!$executed) throw new \Error('Delete Failed');
    }

    public function hydrate(Array $element) {
        $this->id = $element['id'];
        $this->name = $element['name'];
        $this->reputation = $element['reputation'];
    }
}