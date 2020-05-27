<?php

namespace Model\Gateway;

use App\Src\App;

class CityGateway
{
    private $conn;

    private $id;
    private $name;
    private $country;
    private $life;

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

    public function getCountry() {
        return $this->country;
    }

    public function setCountry($country) {
        $this->country = $country;
    }

    public function getLife() {
        return $this->life;
    }

    public function setLife($life) {
        $this->life = $life;
    }

    public function insert() : void {
        $query = $this->conn->prepare('INSERT INTO city (name, country, life) VALUES (:name, :country, :life)');
        $executed = $query->execute([
            ':name' => $this->name,
            ':country' => $this->country,
            ':life' => $this->life
        ]);

        if(!$executed) throw new \Error('Insert Failed');

        $this->id = $this->conn->lastInsertId();
    }

    public function update() : void {
        if(!$this->id) throw new \Error('Instance does not exist in base');

        $query = $this->conn->prepare('UPDATE city SET name = :name, country = :country, life = :life WHERE id = :id');
        $executed = $query->execute([
            ':name' => $this->name,
            ':country' => $this->country,
            ':life' => $this->life,
            ':id' => $this->id
        ]);

        if(!$executed) throw new \Error('Update Failed');
    }

    public function delete() : void {
        if(!$this->id) throw new \Error('Instance does not exist in base');

        $query = $this->conn->prepare('DELETE FROM city WHERE id = :id');
        $executed = $query->execute([
            ':id' => $this->id
        ]);

        if(!$executed) throw new \Error('Delete Failed');
    }

    public function hydrate(Array $element) {
        $this->id = $element['id'];
        $this->name = $element['name'];
        $this->country = $element['country'];
        $this->life = $element['life'];
    }
}