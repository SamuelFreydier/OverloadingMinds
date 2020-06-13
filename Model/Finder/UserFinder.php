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

    public function findOneUserFollowed($username)
    {
        $query = $this->conn->prepare('SELECT u.id, u.username, u.password, u.bio, u.email, COUNT(ufu.userfollowed) AS userfollowed FROM user u INNER JOIN user_follow_user ufu ON ufu.userfollowed = u.id WHERE u.username = :username'); // Création de la requête + utilisation order by pour ne pas utiliser sort
        $query->execute([':username' => $username]); // Exécution de la requête
        $element = $query->fetch(\PDO::FETCH_ASSOC);
        if($element === false) return null;
        
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

    public function findFollows($id) {
        $query = $this->conn->prepare('SELECT u.id, u.username, u.password, u.bio, u.email, COUNT(ufu.follower) AS follower FROM user u INNER JOIN user_follow_user ufu ON ufu.follower = u.id WHERE u.id = :id'); // Création de la requête + utilisation order by pour ne pas utiliser sort
        $query->execute([':id' => $id]); // Exécution de la requête
        $element = $query->fetch(\PDO::FETCH_ASSOC);
        if($element === false) return null;
        
        $user = new UserGateway($this->app);
        $user->hydrate($element);

        return $user;
    }

    public function isFollowedByCurrent($currentuser, $followeduser) {
        $query = $this->conn->prepare('SELECT u.id FROM user u INNER JOIN user_follow_user ufu ON ufu.userfollowed = u.id WHERE ufu.userfollowed = :followeduser AND ufu.follower = :currentuser');
        $query->execute([
            ':currentuser' => $currentuser,
            ':followeduser' => $followeduser
            ]); // Exécution de la requête
        $element = $query->fetch(\PDO::FETCH_ASSOC);
        if($element === false) return null;
        else return 1;
    }

    public function follow($currentuser, $followeduser) {
        $query = $this->conn->prepare('INSERT INTO user_follow_user (follower, userfollowed) VALUES (:currentuser, :followeduser)'); // Création de la requête + utilisation order by pour ne pas utiliser sort
        return $query->execute([
            ':currentuser' => $currentuser,
            ':followeduser'=> $followeduser
        ]);
    }

    public function unfollow($currentuser, $followeduser) {
        $query = $this->conn->prepare('DELETE FROM user_follow_user WHERE follower = :currentuser AND userfollowed = :followeduser'); // Création de la requête + utilisation order by pour ne pas utiliser sort
        return $query->execute([
            ':currentuser' => $currentuser,
            ':followeduser'=> $followeduser
        ]);
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

    public function updateBio($username, $bio) {
        $query = $this->conn->prepare('UPDATE user SET bio = :bio WHERE username = :username');
        return $query->execute([
            ':bio' => $bio,
            ':username' => $username
        ]);
    }


    public function search($searchString)
    {
        $query = $this->conn->prepare('SELECT u.id, u.username, u.password, u.bio, u.email FROM user u WHERE u.username like :search ORDER BY u.username'); // Création de la requête + utilisation order by pour ne pas utiliser sort
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