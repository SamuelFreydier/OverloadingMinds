<?php

namespace Model\Gateway;

use App\Src\App;

class UserGateway
{
    private $conn;

    private $id;
    private $username;
    private $password;
    private $bio;
    private $email;

    private $follower;
    private $userfollowed;
    private $boolfollowed;

    public function __construct(App $app)
    {
        $this->conn = $app->getService('database')->getConnection();
    }

    public function getId() {
        return $this->id;
    }

    public function getUsername(){
        return $this->username;
    }

    public function setUsername($username){
        $this->username = $username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getBio() {
        return $this->bio;
    }

    public function setBio($bio) {
        $this->bio = $bio;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getUserFollowed() {
        return $this->userfollowed;
    }

    public function setUserFollowed($userfollowed) {
        $this->userfollowed = $userfollowed;
    }

    public function setFollower($follower) {
        $this->follower = $follower;
    }

    public function getFollower() {
        return $this->follower;
    }

    public function setBoolFollowed($bool) {
        $this->boolfollowed = $bool;
    }

    public function getBoolFollowed() {
        return $this->boolfollowed;
    }

    public function insert() : void {
        $query = $this->conn->prepare('INSERT INTO user (username, password) VALUES (:username, :password)');
        $executed = $query->execute([
            ':username' => $this->username,
            ':password' => $this->password
        ]);

        if(!$executed) throw new \Error('Insert Failed');

        $this->id = $this->conn->lastInsertId();
    }

    public function update() : void {
        if(!$this->id) throw new \Error('Instance does not exist in base');

        $query = $this->conn->prepare('UPDATE user SET username = :username, password = :password WHERE id = :id');
        $executed = $query->execute([
            ':username' => $this->username,
            ':password' => $this->password,
            ':id' => $this->id
        ]);

        if(!$executed) throw new \Error('Update Failed');
    }

    public function delete() : void {
        if(!$this->id) throw new \Error('Instance does not exist in base');

        $query = $this->conn->prepare('DELETE FROM user WHERE id = :id');
        $executed = $query->execute([
            ':id' => $this->id
        ]);

        if(!$executed) throw new \Error('Delete Failed');
    }

    public function hydrate(Array $element) {
        $this->id = $element['id'];
        $this->username = $element['username'];
        $this->password = $element['password'];
        $this->bio = $element['bio'];
        $this->email = $element['email'];
        if(isset($element['userfollowed'])) {
            $this->userfollowed = $element['userfollowed'];
        }
        if(isset($element['follower'])) {
            $this->follower = $element['follower'];
        };
    }
}