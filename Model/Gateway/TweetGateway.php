<?php

namespace Model\Gateway;

use App\Src\App;

class TweetGateway
{
    private $conn;

    private $id;
    private $text;
    private $date;
    private $author;
    private $retweet;

    private $likes;
    private $nbRetweets;

    public function __construct(App $app)
    {
        $this->conn = $app->getService('database')->getConnection();
    }

    public function getId() {
        return $this->id;
    }

    public function getText(){
        return $this->text;
    }

    public function setText($text){
        $this->text = $text;
    }

    public function getDate() {
        return $this->date;
    }

    public function setDate($date) {
        $this->date = $date;
    }

    public function getAuthor() {
        return $this->author;
    }

    public function setAuthor($author) {
        $this->author = $author;
    }

    public function setRetweet($retweet) {
        $this->retweet = $retweet;
    }

    public function getRetweet() {
        return $this->retweet;
    }

    public function getLikes() {
        return $this->likes;
    }

    public function setLikes($likes) {
        $this->likes = $likes;
    }

    public function getNbRt() {
        return $this->nbRetweets;
    }

    public function setNbRt($nb) {
        $this->nbRetweets = $nb;
    }
    public function insert() : void {
        $query = $this->conn->prepare('INSERT INTO tweet (text, date, author, retweet) VALUES (:text, :date, :author, :retweet)');
        $executed = $query->execute([
            ':text' => $this->text,
            ':date' => $this->date,
            ':author' => $this->author,
            ':retweet' => $this->retweet
        ]);

        if(!$executed) throw new \Error('Insert Failed');

        $this->id = $this->conn->lastInsertId();
    }

    public function update() : void {
        if(!$this->id) throw new \Error('Instance does not exist in base');

        $query = $this->conn->prepare('UPDATE tweet SET text = :text, date = :date, author = :author, retweet = :retweet WHERE id = :id');
        $executed = $query->execute([
            ':text' => $this->text,
            ':date' => $this->date,
            ':author' => $this->author,
            ':retweet' => $this->retweet,
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
        $this->text = $element['text'];
        $this->date = $element['date'];
        $this->author = $element['author'];
        $this->retweet = $element['retweet'];
        if(isset($element['likes'])) {
            $this->likes = $element['likes'];
        }
        if(isset($element['nbRetweets'])) {
            $this->nbRetweets = $element['nbRetweets'];
        };
    }
}