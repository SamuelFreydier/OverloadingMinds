<?php

namespace Model\Finder;
use Model\Finder\FinderInterface;
use App\Src\App;
use Model\Gateway\TweetGateway;

class TweetFinder implements FinderInterface //Query SQL pour trouver des informations relatives aux tweets
{
    private $conn;
    private $app;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->conn = $this->app->getService('database')->getConnection();
    }

    public function findTweetToDisplay($id) { //Affichage des tweets dans la timeline (Tweets et RT de user courant + users suivis)
        $query = $this->conn->prepare('SELECT t.id, t.text, t.date, t.author, t.retweet, t.img FROM tweet t INNER JOIN user u ON u.id = t.author INNER JOIN user_follow_user ufu ON u.id = ufu.userfollowed WHERE ufu.follower = :id UNION SELECT t.id ,t.text, t.date, t.author, t.retweet, t.img FROM tweet t INNER JOIN user u ON u.id = t.author WHERE u.id = :id ORDER BY date DESC');
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

    public function allTweetsFromUser($id) { //Affichage des tweets sur un profil (Tweets et RT de 1 user)
        $query = $this->conn->prepare('SELECT t.id, t.text, t.date, t.author, t.retweet, t.img FROM tweet t INNER JOIN user u ON u.id = t.author WHERE u.id = :id ORDER BY date DESC');
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

    public function findTweetLiked($id, $username) { //Regarde si un tweet a été liké ou non par user courant
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

    public function likeTweet($tweetid, $userid) { //User courant like un tweet
        $query = $this->conn->prepare('INSERT INTO user_like_tweet (tweet, user) VALUES (:tweet, :user)');
        return $query->execute([
            ':tweet' => $tweetid,
            ':user' => $userid
        ]);

    }

    public function unlikeTweet($tweetid, $userid) { //User courant unlike un tweet
        $query = $this->conn->prepare('DELETE ult FROM user_like_tweet ult WHERE ult.user = :user AND ult.tweet = :tweet');
        return $query->execute([
            ':tweet' => $tweetid,
            ':user' => $userid
        ]);
    }

    public function findRetweet($tweetid, $userid) { //Regarde si user courant a déjà retweeté le tweet ciblé
        $query = $this->conn->prepare('SELECT t.id FROM tweet t WHERE t.author = :userid AND t.retweet = :tweetid');
        $query->execute([
            ':tweetid' => $tweetid,
            ':userid' => $userid
        ]);
        $element = $query->fetch(\PDO::FETCH_ASSOC);
        if(empty($element)) {
            return false;
        }
        else {
            return true;
        }
    }

    public function deleteRetweet($tweetid, $userid) { //Supprime un retweet
        $query = $this->conn->prepare('DELETE t FROM tweet t WHERE t.author = :userid AND t.retweet = :tweetid');
        return $query->execute([
            ':tweetid' => $tweetid,
            ':userid' => $userid
        ]);
        
    }

    public function deleteTweet($tweetid) { //Supprime un tweet proprement dans la base (on supprime les likes associés, puis les rt associés, puis le tweet isolé)
        $query = $this->conn->prepare('DELETE FROM user_like_tweet WHERE tweet = :tweetid;
                                       DELETE FROM tweet WHERE retweet = :tweetid;
                                       DELETE FROM tweet WHERE id = :tweetid');
        return $query->execute([
            ':tweetid' => $tweetid
        ]);
    }

    public function findAll() //Trouve tous les tweets
    {
        $query = $this->conn->prepare('SELECT t.id, t.text, t.date, t.author, t.retweet FROM tweet t ORDER BY t.date');
        $query->execute(); // Exécution de la requête
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

    public function findOneLikesById($id) //Trouve le nombre de likes d'un tweet (en donnant son id)
    {
        $query = $this->conn->prepare('SELECT t.id, t.text, t.date, t.author, t.retweet, COUNT(ult.tweet) AS likes FROM tweet t INNER JOIN user_like_tweet ult ON ult.tweet = t.id WHERE t.id = :id');
        $query->execute([':id' => $id]); // Exécution de la requête
        $element = $query->fetch(\PDO::FETCH_ASSOC);   
        
        if($element === null) return null;
        
        $tweet = new TweetGateway($this->app);
        $tweet->hydrate($element);

        return $tweet;
    }

    public function findOneById($id) { //Trouve un tweet (avec son id)
        $query = $this->conn->prepare('SELECT t.id, t.text, t.date, t.author, t.retweet, t.img FROM tweet t WHERE t.id = :id');
        $query->execute([':id' => $id]); // Exécution de la requête
        $element = $query->fetch(\PDO::FETCH_ASSOC);   
        if($element === null) return null;
        
        $tweet = new TweetGateway($this->app);
        $tweet->hydrate($element);

        return $tweet;
    }

    public function findNbRetweetsById($id) { //Trouve le nombre de rt d'un tweets (avec son id)
        $query = $this->conn->prepare('SELECT t.id, t.text, t.date, t.author, t.retweet, COUNT(t.retweet) AS nbRetweets FROM tweet t WHERE t.retweet = :id');
        $query->execute([':id' => $id]); // Exécution de la requête
        $element = $query->fetch(\PDO::FETCH_ASSOC);   
        
        if($element === null) return null;
        
        $tweet = new TweetGateway($this->app);
        $tweet->hydrate($element);

        return $tweet;
    }

    public function findByAuthor($author) //Trouve les tweets par rapport à l'id d'un user (du plus récent au plus ancien)
    {
        $query = $this->conn->prepare('SELECT t.id, t.text, t.date, t.author, t.retweet FROM tweet t WHERE t.author = :author ORDER BY t.date');
        $query->execute([':author' => $author]); // Exécution de la requête
        $elements = $query->fetchAll(\PDO::FETCH_ASSOC);
        if($elements === false) return null;
        
        $tweet = new TweetGateway($this->app);
        $tweet->hydrate($elements);

        return $tweet;
    }

    public function findLastTweet() { //Trouve le dernier tweet créé
        $query = $this->conn->prepare('SELECT t.id, t.text, t.date, t.author, t.retweet, t.img FROM tweet t ORDER BY t.id DESC');
        $query->execute();
        $element = $query->fetch(\PDO::FETCH_ASSOC);
        if($element === false) return null;

        $tweet = new TweetGateway($this->app);
        $tweet->hydrate($element);

        return $tweet;
    }

    public function updateImg($id, $path) { //Met à jour l'image d'un tweet. L'image n'est pas stockée dans la BD, seulement son chemin d'accès. Une image a pour nom l'id du tweet associé (ex : l'image du tweet 42 a pour nom 42.png ou 42.jpg)
        $query = $this->conn->prepare('UPDATE tweet SET img = :path WHERE id = :id');
        return $query->execute([
            ':path' => $path,
            ':id' => $id
        ]);
    }

    public function save(array $tweet) : bool //Création d'un tweet
    {
        $query = $this->conn->prepare('INSERT INTO tweet (text, date, author, retweet) VALUES (:text, :date, :author, :retweet)');
        return $query->execute([
            ':text' => $tweet['text'],
            ':date' => $tweet['date'],
            ':author' => $tweet['author'],
            ':retweet' => $tweet['retweet']
        ]);
    }

}