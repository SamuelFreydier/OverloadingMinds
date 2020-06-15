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

    public function findAll() //Trouve tous les users
    {
        $query = $this->conn->prepare('SELECT u.id, u.username, u.password, u.bio, u.email, u.img FROM user u ORDER BY u.username');
        $query->execute(); // Exécution de la requête
        $elements = $query->fetchAll(\PDO::FETCH_ASSOC);

        if(count($elements) === 0) return null;

        $users = [];
        $user = null;
        foreach($elements as $element) {
            $user = new UserGateway($this->app);
            $user->hydrate($element);

            $users[] = $user;
        }
        
        return $users;
    }

    public function findOneById($id) //Trouve un user avec son id
    {
        $query = $this->conn->prepare('SELECT u.id, u.username, u.password, u.bio, u.email, u.img FROM user u WHERE u.id = :id');
        $query->execute([':id' => $id]); // Exécution de la requête
        $element = $query->fetch(\PDO::FETCH_ASSOC);
        if($element === null) return null;
        
        $user = new UserGateway($this->app);
        $user->hydrate($element);

        return $user;
    }

    public function findOneUserFollowed($username) //Trouve le nombre de followers d'un user avec son nom
    {
        $query = $this->conn->prepare('SELECT u.id, u.username, u.password, u.bio, u.email, COUNT(ufu.userfollowed) AS userfollowed FROM user u INNER JOIN user_follow_user ufu ON ufu.userfollowed = u.id WHERE u.username = :username');
        $query->execute([':username' => $username]); // Exécution de la requête
        $element = $query->fetch(\PDO::FETCH_ASSOC);
        if($element === false) return null;
        
        $user = new UserGateway($this->app);
        $user->hydrate($element);

        return $user;
    }

    public function findOneByUsername($username) //Trouve un user avec son nom
    {
        $query = $this->conn->prepare('SELECT u.id, u.username, u.password, u.bio, u.email, u.img FROM user u WHERE u.username = :username');
        $query->execute([':username' => $username]); // Exécution de la requête
        $element = $query->fetch(\PDO::FETCH_ASSOC);
        if($element === false) return null;
        
        $user = new UserGateway($this->app);
        $user->hydrate($element);

        return $user;
    }

    public function findFollows($id) { //Trouve le nombre de follows d'un user avec son id
        $query = $this->conn->prepare('SELECT u.id, u.username, u.password, u.bio, u.email, COUNT(ufu.follower) AS follower FROM user u INNER JOIN user_follow_user ufu ON ufu.follower = u.id WHERE u.id = :id');
        $query->execute([':id' => $id]); // Exécution de la requête
        $element = $query->fetch(\PDO::FETCH_ASSOC);
        if($element === false) return null;
        
        $user = new UserGateway($this->app);
        $user->hydrate($element);

        return $user;
    }

    public function isFollowedByCurrent($currentuser, $followeduser) { //Indique si l'user ciblé est suivi par l'user courant
        $query = $this->conn->prepare('SELECT u.id FROM user u INNER JOIN user_follow_user ufu ON ufu.userfollowed = u.id WHERE ufu.userfollowed = :followeduser AND ufu.follower = :currentuser');
        $query->execute([
            ':currentuser' => $currentuser,
            ':followeduser' => $followeduser
            ]); // Exécution de la requête
        $element = $query->fetch(\PDO::FETCH_ASSOC);
        if($element === false) return null;
        else return 1;
    }

    public function follow($currentuser, $followeduser) { //User courant follow user ciblé
        $query = $this->conn->prepare('INSERT INTO user_follow_user (follower, userfollowed) VALUES (:currentuser, :followeduser)');
        return $query->execute([
            ':currentuser' => $currentuser,
            ':followeduser'=> $followeduser
        ]);
    }

    public function unfollow($currentuser, $followeduser) { //User courant unfollow user ciblé
        $query = $this->conn->prepare('DELETE FROM user_follow_user WHERE follower = :currentuser AND userfollowed = :followeduser');
        return $query->execute([
            ':currentuser' => $currentuser,
            ':followeduser'=> $followeduser
        ]);
    }

    public function save(array $user) : bool //Création d'un user
    {
        $query = $this->conn->prepare('INSERT INTO user (username, password, email) VALUES (:username, :password, :email)');
        return $query->execute([
            ':username' => $user['username'],
            ':password'=> $user['password'],
            ':email' => $user['email']
        ]);
    }

    public function updateBio($username, $bio) { //Update biographie de user courant
        $query = $this->conn->prepare('UPDATE user SET bio = :bio WHERE username = :username');
        return $query->execute([
            ':bio' => $bio,
            ':username' => $username
        ]);
    }

    public function updateImg($username, $path) { //Update image de profil de user courant (Chemin d'accès à l'image qui a pour nom user.ext ; ex : Samuel.png)
        $query = $this->conn->prepare('UPDATE user SET img = :path WHERE username = :username');
        return $query->execute([
            ':path' => $path,
            ':username' => $username
        ]);
    }


    public function search($searchString) //Cherche tous les users ayant dans leur nom le bout de chaîne
    {
        $query = $this->conn->prepare('SELECT u.id, u.username, u.password, u.bio, u.email, u.img FROM user u WHERE u.username like :search ORDER BY u.username');
        $query->execute([':search' => '%' . $searchString .  '%']); // Exécution de la requête
        $elements = $query->fetchAll(\PDO::FETCH_ASSOC);

        if(count($elements) === 0) return null;

        $users = [];
        $user = null;
        foreach($elements as $element) {
            $user = new UserGateway($this->app);
            $user->hydrate($element);

            $users[] = $user;
        }

        return $users;
    }

    
    
}