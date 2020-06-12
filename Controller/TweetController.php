<?php

namespace Controller;
use Controller\ControllerBase;
use App\Src\App;
use App\Src\Request\Request;

class TweetController extends ControllerBase
{

    public function __construct(App $app)
    {
        parent::__construct($app);
    }

    public function renderTweets($tweets) {
        foreach($tweets as $tweet) {
            $username = $this->app->getService('userFinder')->findOneById($tweet->getAuthor());
            $tweet->setAuthor($username->getUsername());
            $tweetlikes = $this->app->getService('tweetFinder')->findOneById($tweet->getId());
            $tweet->setLikes($tweetlikes->getLikes());
            $tweetrt = $this->app->getService('tweetFinder')->findNbRetweetsById($tweet->getId());
            $tweet->setNbRt($tweetrt->getNbRt());
            if($tweet->getRetweet() !== null) {
                $retweeted = $this->app->getService('tweetFinder')->findOneById($tweet->getRetweet());
                //var_dump($retweeted);exit();
                $retweetedNbRt = $this->app->getService('tweetFinder')->findNbRetweetsById($retweeted->getId());
                $retweeted->setNbRt($retweetedNbRt->getNbRt());
                $retweetuser = $this->app->getService('userFinder')->findOneById($retweeted->getAuthor());
                $retweeted->setAuthor($retweetuser->getUsername());
                $tweet->setRetweet($retweeted);
            }
        }
        return $tweets;
    }

    public function mainPageHandler(Request $request) {
        if(!isset($_SESSION['auth'])) {
            return $this->app->render('loginredirection');
        }
        $author = $this->app->getService('userFinder')->findOneByUsername($_SESSION['auth']);
        $author = $author->getId();
        $tweets = $this->app->getService('tweetFinder')->findTweetToDisplay($author);
        $tweets = $this->renderTweets($tweets);
        return $this->app->render('mainPage', ["tweets" => $tweets]);
    }

    public function newTweetHandler(Request $request) {
        if(!isset($_SESSION['auth'])) {
            return $this->app->render('loginredirection');
        }
        $text = $request->getParameters('text');
        $retweet = null;
        if($request->getParameters('id') !== null) {
            $retweet = $request->getParameters('id');
        }
        $request = [];
        if(strlen($text) > 140) {
            return $this->app->render('mainPage');
        }

        $author = $this->app->getService('userFinder')->findOneByUsername($_SESSION['auth']);
        $author = $author->getId();
        $date = date("Y-m-d H:i:s");
        $tweet = [
            "text" => $text,
            "date" => $date,
            "author" => $author,
            "retweet" => $retweet
        ];
        $this->app->getService('tweetFinder')->save($tweet);
        $tweets = $this->app->getService('tweetFinder')->findTweetToDisplay($author);
        $tweets = $this->renderTweets($tweets);
        return $this->app->render('formredirection', ['tweets' => $tweets]);
    }

    public function tweetLikeHandler(Request $request) {
        if(!isset($_SESSION['auth'])) {
            return $this->app->render('loginredirection');
        }
        $id = $request->getParameters('id');
        $username = $_SESSION['auth'];
        $request = [];
        $userid = $this->app->getService('userFinder')->findOneByUsername($username);
        $userid = $userid->getId();
        if($this->app->getService('tweetFinder')->findTweetLiked($id, $username) === null) {
            $this->app->getService('tweetFinder')->likeTweet($id, $userid);
        }
        else {
            $this->app->getService('tweetFinder')->unlikeTweet($id, $userid);
        }
        $tweets = $this->app->getService('tweetFinder')->findTweetToDisplay($userid);
        $tweets = $this->renderTweets($tweets);
        return $this->app->render('formredirection', ['tweets' => $tweets]);
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