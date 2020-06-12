<?php

namespace Controller;
use Controller\ControllerBase;
use App\Src\App;
use App\Src\Request\Request;

class UserController extends ControllerBase
{

    public function __construct(App $app)
    {
        parent::__construct($app);
    }

    public function renderTweets($tweets) {
        foreach($tweets as $tweet) {
            $username = $this->app->getService('userFinder')->findOneById($tweet->getAuthor());
            $tweet->setAuthor($username->getUsername());
            $tweetlikes = $this->app->getService('tweetFinder')->findOneLikesById($tweet->getId());
            $tweet->setLikes($tweetlikes->getLikes());
            $tweetrt = $this->app->getService('tweetFinder')->findNbRetweetsById($tweet->getId());
            $tweet->setNbRt($tweetrt->getNbRt());
            if($tweet->getRetweet() !== null) {
                $retweeted = $this->app->getService('tweetFinder')->findOneById($tweet->getRetweet());
                $retweetlikes = $this->app->getService('tweetFinder')->findOneLikesById($retweeted->getId());
                $retweeted->setLikes($retweetlikes->getLikes());
                $retweetedNbRt = $this->app->getService('tweetFinder')->findNbRetweetsById($retweeted->getId());
                $retweeted->setNbRt($retweetedNbRt->getNbRt());
                $retweetuser = $this->app->getService('userFinder')->findOneById($retweeted->getAuthor());
                $retweeted->setAuthor($retweetuser->getUsername());
                $tweet->setRetweet($retweeted);
            }
        }
        return $tweets;
    }

    public function userLoginFormHandler(Request $request) {
        if(isset($_SESSION['auth'])) {
            $author = $this->app->getService('userFinder')->findOneByUsername($_SESSION['auth']);
            $author = $author->getId();
            $tweets = $this->app->getService('tweetFinder')->findTweetToDisplay($author);
            $tweets = $this->renderTweets($tweets);
            return $this->app->render('formredirection', ["tweets" => $tweets]);
        }
        return $this->app->render('login');
    }

    public function userSignupFormHandler(Request $request) {
        if(isset($_SESSION['auth'])) {
            $author = $this->app->getService('userFinder')->findOneByUsername($_SESSION['auth']);
            $author = $author->getId();
            $tweets = $this->app->getService('tweetFinder')->findTweetToDisplay($author);
            $tweets = $this->renderTweets($tweets);
            return $this->app->render('formredirection', ["tweets" => $tweets]);
        }
        return $this->app->render('signup');
    }

    public function userSignupHandler(Request $request) {
        $username = $request->getParameters('username');
        $email = $request->getParameters('email');
        $password = $request->getParameters('password');
        $passwordconf = $request->getParameters('passwordconf');

        if($password != $passwordconf || empty($email) || empty($username) || empty($password)) {
            header('Location: https://overloadingminds.cleverapps.io/signup');
            exit();
        }

        $user = $this->app->getService('userFinder')->findOneByUsername($username);
        if($user->getId() !== null) {
            header('Location: https://overloadingminds.cleverapps.io/signup');
            exit();
        }
        $passwordhashed = password_hash($password, PASSWORD_DEFAULT);

        $newuser = [
            'username' => $username,
            'password' => $passwordhashed,
            'email' => $email
        ];

        $result = $this->app->getService('userFinder')->save($newuser);

        if(!isset($result)) {
            header('Location: https://overloadingminds.cleverapps.io/signup');
            exit();
        }
        session_start();
        $_SESSION['auth'] = $username;
        $author = $this->app->getService('userFinder')->findOneByUsername($_SESSION['auth']);
        $author = $author->getId();
        $tweets = $this->app->getService('tweetFinder')->findTweetToDisplay($author);
        $tweets = $this->renderTweets($tweets);
        return $this->app->render('formredirection', ["tweets" => $tweets]);
    }

    public function userLoginHandler(Request $request) {
        $username = $request->getParameters('username');
        $password = $request->getParameters('password');

        $result = $this->app->getService('userFinder')->findOneByUsername($username);
        $cities = $this->app->getService('cityFinder')->findAll();
        if($result === null) {
            $flash = "NON";
            header('Location: https://overloadingminds.cleverapps.io/login');
            exit();
        }
        if (!password_verify($password, $result->getPassword())) {
            $flash = "NON";
            header('Location: https://overloadingminds.cleverapps.io/login');
            exit();
        }
        $_SESSION['auth'] = $username;
        $flash = $username;
        header('Location: https://overloadingminds.cleverapps.io');
        $author = $this->app->getService('userFinder')->findOneByUsername($_SESSION['auth']);
        $author = $author->getId();
        $tweets = $this->app->getService('tweetFinder')->findTweetToDisplay($author);
        $tweets = $this->renderTweets($tweets);
        return $this->app->render('formredirection', ["tweets" => $tweets, "flash" => $flash]);
    }

    public function userResearchHandler(Request $request) {
        $search = "";
        if(isset($_GET['search'])) {
            $search = htmlspecialchars($_GET['search']);
        }
        $author = ($this->app->getService('userFinder')->findOneByUsername($_SESSION['auth']))->getId();
        $users = $this->app->getService('userFinder')->search($search);
        foreach($users as $user) {
            $userfollowed = $this->app->getService('userFinder')->findOneById($user->getId());
            $user->setUserFollowed($userfollowed->getUserFollowed());
            $userfollows = $this->app->getService('userFinder')->findFollows($user->getId());
            $user->setFollower($userfollows->getFollower());
            if($this->app->getService('userFinder')->isFollowedByCurrent($author, $user->getId()) === null) {
                $user->setBoolFollowed(false);
            }
            else {
                $user->setBoolFollowed(true);
            }
        }
        return $this->app->render('membresPage', ["users" => $users, "author" => $author, "search" => $search]);
    }

    public function userFollowHandler(Request $request) {
        $search = $request->getParameters('search');
        $author = ($this->app->getService('userFinder')->findOneByUsername($_SESSION['auth']))->getId();
        $usertofollow = $request->getParameters('userid');
        if($this->app->getService('userFinder')->isFollowedByCurrent($author, $usertofollow) === null) {
            $this->app->getService('userFinder')->follow($author, $usertofollow);
        }
        else {
            $this->app->getService('userFinder')->unfollow($author, $usertofollow);
        }
        $users = $this->app->getService('userFinder')->search($search);
        foreach($users as $user) {
            $userfollowed = $this->app->getService('userFinder')->findOneById($user->getId());
            $user->setUserFollowed($userfollowed->getUserFollowed());
            $userfollows = $this->app->getService('userFinder')->findFollows($user->getId());
            $user->setFollower($userfollows->getFollower());
            if($this->app->getService('userFinder')->isFollowedByCurrent($author, $user->getId()) === null) {
                $user->setBoolFollowed(false);
            }
            else {
                $user->setBoolFollowed(true);
            }
        }
        return $this->app->render('memberredirection', ["users" => $users, "author" => $author, "search" => $search]);
    }

    public function userFollowProfileHandler(Request $request) {
        $username = htmlspecialchars($request->getParameters('username'));
        $author = ($this->app->getService('userFinder')->findOneByUsername($_SESSION['auth']))->getId();
        $user = $this->app->getService('userFinder')->findOneByUsername($username);
        $userfollows = $this->app->getService('userFinder')->findFollows($user->getId());
        $user->setFollower($userfollows->getFollower());
        $author = htmlspecialchars($_SESSION['auth']);
        $author = ($this->app->getService('userFinder')->findOneByUsername($author))->getId();
        if($this->app->getService('userFinder')->isFollowedByCurrent($author, $user->getId()) === null) {
            $this->app->getService('userFinder')->follow($author, $user->getId());
        }
        else {
            $this->app->getService('userFinder')->unfollow($author, $user->getId());
        }
        
        $tweets = $this->app->getService('tweetFinder')->allTweetsFromUser($user->getId());
        if(!empty($tweets)) {
            $tweets = $this->renderTweets($tweets);
        }
        return $this->app->render('profileredirection', ["user" => $user, "author" => $author]);
    }

    public function userProfileHandler(Request $request, $username) {
        $username = htmlspecialchars($username);
        $user = $this->app->getService('userFinder')->findOneByUsername($username);
        $userfollows = $this->app->getService('userFinder')->findFollows($user->getId());
        $user->setFollower($userfollows->getFollower());
        $author = htmlspecialchars($_SESSION['auth']);
        $author = ($this->app->getService('userFinder')->findOneByUsername($author))->getId();
        if($this->app->getService('userFinder')->isFollowedByCurrent($author, $user->getId()) === null) {
            $user->setBoolFollowed(false);
        }
        else {
            $user->setBoolFollowed(true);
        }
        $tweets = $this->app->getService('tweetFinder')->allTweetsFromUser($user->getId());
        if(!empty($tweets)) {
            $tweets = $this->renderTweets($tweets);
        }
        return $this->app->render('profil', ['user' => $user, 'tweets' => $tweets, 'author' => $author]);
    }


    public function restaurantsHandler(Request $request)
    {
        $restaurants = $this->app->getService('restaurantFinder')->findAll();
        return $this->app->render('restaurants', ["restaurants" => $restaurants]);
    }

    public function restaurantHandler(Request $request, $id) {
        if(!$id) {
            return $this->app->render('404');
        }
        $restaurant = $this->app->getService('restaurantFinder')->findOneById($id);
        if($restaurant === null) {
            return $this->app->render('404');
        }
        return $this->app->render('restaurant', ["restaurant" => $restaurant]);
    }

    public function formHandler(Request $request) {
        return $this->app->render('createRestaurant', []);
    }

    public function createHandler(Request $request)
    {
        $name = $request->getParameters('name');
        $reputation = $request->getParameters('reputation');
        if($name === "" || $reputation === "") {
            $this->fail();
        }

        $this->restaurant = [
            'name' => $name,
            'reputation' => $reputation
        ];

        $result = $this->app->getService('restaurantFinder')->save($this->restaurant);
        
        if(!isset($result)) {
            $this->fail();
        }

        $restaurants = $this->app->getService('restaurantFinder')->findAll();
        $flash = "New restaurant has been sucessfully created";

        return $this->app->render('restaurants', ["restaurants" => $restaurants, "flash" => $flash]);
    }

    private function fail() {
        $error = true;
        include __DIR__ . '/../view/createRestaurant.php';
        die();
    }

    public function updateHandler(Request $request) {
        var_dump($request->getParameters2());
        $id = $_GET['id'];
        var_dump($id);
        $newname = $request->getParameters('name');
        $newreputation = $request->getParameters('reputation');
        if(!$id) {
            return $this->app->render('404');
        }
        $flash = "Restaurant has been successfully updated";
        $this->app->getService('restaurantFinder')->update($id, $newname, $newreputation);
        $newrestaurant = $this->app->getService('restaurantFinder')->findOneById($id);
        return $this->app->render('restaurant', ["restaurant" => $newrestaurant, "flash" => $flash]);
    }

    public function formUpdateHandler(Request $request, $id) {
        $restaurant = $this->app->getService('restaurantFinder')->findOneById($id);
        return $this->app->render('updateRestaurant', ["restaurant" => $restaurant]);
    }
    
    public function deleteHandler(Request $request, $id) {
        $flash = "Restaurant has been successfully removed";
        $this->app->getService('restaurantFinder')->remove($id);
        return $this->app->render('restaurantdeleted', ["flash" => $flash]);
    }
}