<?php

namespace Model\Finder;
use Model\Finder\FinderInterface;
use App\Src\App;
use Model\Gateway\TweetGateway;

class TweetFinder implements FinderInterface
{
    private $conn;
    private $app;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->conn = $this->app->getService('database')->getConnection();
    }

    public function findTweetToDisplay($id) {
        $query = $this->conn->prepare('SELECT t.id, t.text, t.date, t.author, t.retweet FROM tweet t INNER JOIN user u ON u.id = t.author INNER JOIN user_follow_user ufu ON u.id = ufu.userfollowed WHERE ufu.follower = :id UNION SELECT t.id ,t.text, t.date, t.author, t.retweet FROM tweet t INNER JOIN user u ON u.id = t.author WHERE u.id = :id ORDER BY date DESC');
        $query->execute([':id' => $id]);
        $elements = $query->fetchAll(\PDO::FETCH_ASSOC);

        if(count($elements) === 0) return null;

        $tweets = [];
        $tweet = null;
        foreach($elements as $element) {
            $tweet = new TweetGateway($this->app);
            $tweet->hydrate($element);

            $tweets[] = $tweet;
        }

        return $tweets;
    }

    public function allTweetsFromUser($id) {
        $query = $this->conn->prepare('SELECT t.id, t.text, t.date, t.author, t.retweet FROM tweet t INNER JOIN user u ON u.id = t.author WHERE u.id = :id ORDER BY date DESC');
        $query->execute([':id' => $id]);
        $elements = $query->fetchAll(\PDO::FETCH_ASSOC);

        if(count($elements) === 0) return null;

        $tweets = [];
        $tweet = null;
        foreach($elements as $element) {
            $tweet = new TweetGateway($this->app);
            $tweet->hydrate($element);

            $tweets[] = $tweet;
        }

        return $tweets;
    }

    public function findTweetLiked($id, $username) {
        $query = $this->conn->prepare('SELECT u.username, t.text, t.id FROM tweet t INNER JOIN user_like_tweet ult ON t.id = ult.tweet INNER JOIN user u ON u.id = ult.user WHERE t.id = :id AND u.username = :username');
        $query->execute([
            ':id' => $id,
            ':username' => $username
        ]);
        $elements = $query->fetchAll(\PDO::FETCH_ASSOC);

        if(count($elements) === 0) {
            return null;
        }
        else {
            return 1;
        }
    }

    public function likeTweet($tweetid, $userid) {
        $query = $this->conn->prepare('INSERT INTO user_like_tweet (tweet, user) VALUES (:tweet, :user)');
        return $query->execute([
            ':tweet' => $tweetid,
            ':user' => $userid
        ]);

    }

    public function unlikeTweet($tweetid, $userid) {
        $query = $this->conn->prepare('DELETE ult FROM user_like_tweet ult WHERE ult.user = :user AND ult.tweet = :tweet');
        return $query->execute([
            ':tweet' => $tweetid,
            ':user' => $userid
        ]);
    }

    public function rtTweet($tweetid, $userid) {
        $query = $this->conn->prepare('DELETE ult FROM user_like_tweet ult WHERE ult.user = :user AND ult.tweet = :tweet');
        return $query->execute([
            ':tweet' => $tweetid,
            ':user' => $userid
        ]);
    }

    public function findAll()
    {
        $query = $this->conn->prepare('SELECT t.id, t.text, t.date, t.author, t.retweet FROM tweet t ORDER BY t.date'); // Création de la requête + utilisation order by pour ne pas utiliser sort
        $query->execute(); // Exécution de la requête
        $elements = $query->fetchAll(\PDO::FETCH_ASSOC);

        if(count($elements) === 0) return null;

        $cities = [];
        $city = null;
        foreach($elements as $element) {
            $city = new TweetGateway($this->app);
            $city->hydrate($element);

            $cities[] = $city;
        }
        
        return $cities;
    }

    public function findOneLikesById($id)
    {
        $query = $this->conn->prepare('SELECT t.id, t.text, t.date, t.author, t.retweet, COUNT(ult.tweet) AS likes FROM tweet t INNER JOIN user_like_tweet ult ON ult.tweet = t.id WHERE t.id = :id'); // Création de la requête + utilisation order by pour ne pas utiliser sort
        $query->execute([':id' => $id]); // Exécution de la requête
        $element = $query->fetch(\PDO::FETCH_ASSOC);   
        
        if($element === null) return null;
        
        $tweet = new TweetGateway($this->app);
        $tweet->hydrate($element);

        return $tweet;
    }

    public function findOneById($id) {
        $query = $this->conn->prepare('SELECT t.id, t.text, t.date, t.author, t.retweet FROM tweet t WHERE t.id = :id'); // Création de la requête + utilisation order by pour ne pas utiliser sort
        $query->execute([':id' => $id]); // Exécution de la requête
        $element = $query->fetch(\PDO::FETCH_ASSOC);   
        if($element === null) return null;
        
        $tweet = new TweetGateway($this->app);
        $tweet->hydrate($element);

        return $tweet;
    }

    public function findNbRetweetsById($id) {
        $query = $this->conn->prepare('SELECT t.id, t.text, t.date, t.author, t.retweet, COUNT(t.retweet) AS nbRetweets FROM tweet t WHERE t.retweet = :id'); // Création de la requête + utilisation order by pour ne pas utiliser sort
        $query->execute([':id' => $id]); // Exécution de la requête
        $element = $query->fetch(\PDO::FETCH_ASSOC);   
        
        if($element === null) return null;
        
        $tweet = new TweetGateway($this->app);
        $tweet->hydrate($element);

        return $tweet;
    }

    public function findByAuthor($author)
    {
        $query = $this->conn->prepare('SELECT t.id, t.text, t.date, t.author, t.retweet FROM tweet t WHERE t.author = :author ORDER BY t.date'); // Création de la requête + utilisation order by pour ne pas utiliser sort
        $query->execute([':author' => $author]); // Exécution de la requête
        $elements = $query->fetchAll(\PDO::FETCH_ASSOC);
        if($elements === false) return null;
        
        $user = new TweetGateway($this->app);
        $user->hydrate($elements);

        return $user;
    }

    public function save(array $tweet) : bool
    {
        $query = $this->conn->prepare('INSERT INTO tweet (text, date, author, retweet) VALUES (:text, :date, :author, :retweet)'); // Création de la requête + utilisation order by pour ne pas utiliser sort
        return $query->execute([
            ':text' => $tweet['text'],
            ':date' => $tweet['date'],
            ':author' => $tweet['author'],
            ':retweet' => $tweet['retweet']
        ]);
    }

}