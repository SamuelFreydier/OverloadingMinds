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

    public function renderTweets($tweets) { //Affichage de chaque tweet d'une liste de tweets
        foreach($tweets as $tweet) {
            $username = $this->app->getService('userFinder')->findOneById($tweet->getAuthor());
            $tweet->setAuthor(["username" => $username->getUsername(), "img" => $username->getImg()]); //On associe au tweet le nom de son créateur et son image de profil
            $tweetlikes = $this->app->getService('tweetFinder')->findOneLikesById($tweet->getId());
            $tweet->setLikes($tweetlikes->getLikes()); //On compte les likes du tweet
            $tweetrt = $this->app->getService('tweetFinder')->findNbRetweetsById($tweet->getId());
            $tweet->setNbRt($tweetrt->getNbRt()); //On compte le nombre de Rt du tweet
            if($tweet->getRetweet() !== null) { //Si c'est un RT, on fait pareil pour le tweet ciblé
                $retweeted = $this->app->getService('tweetFinder')->findOneById($tweet->getRetweet());
                $retweetlikes = $this->app->getService('tweetFinder')->findOneLikesById($retweeted->getId());
                $retweeted->setLikes($retweetlikes->getLikes());
                $retweetedNbRt = $this->app->getService('tweetFinder')->findNbRetweetsById($retweeted->getId());
                $retweeted->setNbRt($retweetedNbRt->getNbRt());
                $retweetuser = $this->app->getService('userFinder')->findOneById($retweeted->getAuthor());
                $retweeted->setAuthor(["username" => $retweetuser->getUsername(), "img" => $retweetuser->getImg()]);
                $tweet->setRetweet($retweeted); //Mise à jour du tweet ciblé dans le tweet d'origine
            }
        }
        return $tweets;
    }

    public function mainPageHandler(Request $request) { //Chargement de la timeline
        $this->logoutRedirect(); //Vérif connexion

        //On renvoie à la timeline avec l'utilisateur courant et les tweets de lui-même et des gens qu'il suit
        $author = $this->app->getService('userFinder')->findOneByUsername($_SESSION['auth']);
        $tweets = $this->app->getService('tweetFinder')->findTweetToDisplay($author->getId());
        if(!empty($tweets)) {
            $tweets = $this->renderTweets($tweets);
        }

        return $this->app->render('mainPage', ["tweets" => $tweets, "author" => $author]);
    }

    public function newTweetHandler(Request $request) { //Gestion de nouveau tweet / RT
        $this->logoutRedirect(); //Vérif connexion
        $author = $this->app->getService('userFinder')->findOneByUsername($_SESSION['auth']); //Informations liées à l'utilisateur courant
        $text = htmlspecialchars($request->getParameters('text')); //On prend le texte du nouveau tweet
        $retweet = null;
        if(isset($request->getParameters2()["id"])) { //Si on pointe sur un tweet, on associe l'id du tweet pointé au Rt
            $retweet = $request->getParameters("id");
        }
        $request = [];

        if($retweet !== null) { //Si c'est un RT
            if($this->app->getService('tweetFinder')->findRetweet($retweet, $author->getId()) === true) { //Si user courant l'a déjà retweet
                $this->app->getService('tweetFinder')->deleteRetweet($retweet, $author->getId()); //Le RT s'enlève alors
                $tweets = $this->app->getService('tweetFinder')->findTweetToDisplay($author->getId());
                $tweets = $this->renderTweets($tweets);
                return $this->app->render('mainPage', ['tweets' => $tweets, "author" => $author]);
            }
        }

        if(strlen($text) > 140) { //Si le tweet est trop grand
            $error = "Le tweet ne doit pas dépasser 140 caractères."; //Message d'erreur
            $tweets = $this->app->getService('tweetFinder')->findTweetToDisplay($author->getId());
            if(!empty($tweets)) {
                $tweets = $this->renderTweets($tweets);
            }
            return $this->app->render('mainPage', ["author" => $author, "tweets" => $tweets, "error" => $error]);
        }

        //Création de tweet/RT
        $date = date("Y-m-d H:i:s"); //Ce format est adapté au type datetime de SQL
        $tweet = [
            "text" => $text,
            "date" => $date,
            "author" => $author->getId(),
            "retweet" => $retweet //Si null, ce n'est pas un RT
        ];
        $this->app->getService('tweetFinder')->save($tweet); //Création
        
        $tweets = $this->app->getService('tweetFinder')->findTweetToDisplay($author->getId());
        $tweets = $this->renderTweets($tweets);

        if($retweet === null) { //Si RT null (n'est pas un RT)
            $lasttweetid = $this->app->getService('tweetFinder')->findLastTweet();
            $img = $_FILES['img']; //On prend l'image associée au tweet
            $ext = strtolower(substr($img['name'], -3)); 
            $allow_ext = array("jpg", "png", "gif");
            if(in_array($ext, $allow_ext)) { //Vérification de l'extension (si valide, on met le chemin de l'image dans l'emplacement img du tweet dans la BD)
                $path = "../Ressources/Images/Tweets/" . $lasttweetid->getId() . "." . $ext;
                move_uploaded_file($img['tmp_name'], $path);
                $this->app->getService('tweetFinder')->updateImg($lasttweetid->getId(), $path);
            }
            else { //Si le fichier n'est pas bon, message d'alerte
                $alert = "L'image n'a pas pu être envoyée (PNG, JPG, GIF autorisés).";
                return $this->app->render('mainPage', ['author' => $author, 'tweets' => $tweets, "alert" => $alert]);
            }
        }
        //Le tweet a été créé sans encombre
        $validation = "Le tweet a bien été envoyé.";
        return $this->app->render('mainPage', ['author' => $author, 'tweets' => $tweets, "validation" => $validation]);
    }

    public function tweetLikeHandler(Request $request) { //Like d'un tweet
        $this->logoutRedirect(); //Vérif connexion
        $id = $request->getParameters('id'); //Id du tweet à like
        $username = htmlspecialchars($_SESSION['auth']);
        $request = [];
        $userid = $this->app->getService('userFinder')->findOneByUsername($username);
        $userid = $userid->getId();
        if($this->app->getService('tweetFinder')->findTweetLiked($id, $username) === null) { //Si le tweet n'a pas été liké
            $this->app->getService('tweetFinder')->likeTweet($id, $userid); //On le like avec le user courant
        }
        else {
            $this->app->getService('tweetFinder')->unlikeTweet($id, $userid); //Sinon, on le unlike
        }

        header("Location: /"); //On redirige sur la Timeline (mainPageHandler)
        exit();
    }

    public function tweetLikeProfileHandler(Request $request) { //Pareil qu'au dessus sauf qu'on le fait dans un profil donc on veut rediriger sur ce profil
        $this->logoutRedirect(); //Vérif connexion
        $id = $request->getParameters('id');
        $username = htmlspecialchars($_SESSION['auth']);
        $user = $this->app->getService('userFinder')->findOneById($request->getParameters('userid'));
        $request = [];
        $userid = $this->app->getService('userFinder')->findOneByUsername($username);
        $userid = $userid->getId();
        if($this->app->getService('tweetFinder')->findTweetLiked($id, $username) === null) {
            $this->app->getService('tweetFinder')->likeTweet($id, $userid);
        }
        else {
            $this->app->getService('tweetFinder')->unlikeTweet($id, $userid);
        }

        header("Location: /user/".$user->getUsername()); //On redirige sur la page de profil d'avant
        exit();
    }
    
    public function deleteTweetHandler(Request $request) { //Suppression de tweet
        $this->logoutRedirect(); //Vérif connexion
        $tweetid = $request->getParameters('tweetid'); //On trouve l'id du tweet
        $tweet = $this->app->getService('tweetFinder')->findOneById($tweetid); //Et ses informations relatives
        unlink($tweet->getImg()); //On supprime l'image associée au chemin indiqué par le tweet (Une image a pour nom l'id du tweet)
        $this->app->getService('tweetFinder')->deleteTweet($tweetid); //On fait la fonction Delete (voir TweetFinder)
        
        header("Location: /"); //On redirige sur la timeline
        exit();
    }

    public function deleteTweetHandlerProfile(Request $request) { //Pareil qu'au dessus mais on redirige sur le profil courant
        $this->logoutRedirect(); //Vérif connexion
        $tweetid = $request->getParameters('tweetid');
        $tweet = $this->app->getService('tweetFinder')->findOneById($tweetid);
        unlink($tweet->getImg());
        $user =  $this->app->getService('userFinder')->findOneById($request->getParameters('userid'));
        $this->app->getService('tweetFinder')->deleteTweet($tweetid);
        
        header("Location: /user/".$user->getUsername()); //Redirection sur le profil courant
        exit();
    }


    public function logoutRedirect() { //Logout l'utilisateur si sa session n'existe pas
        if(!isset($_SESSION['auth'])) { 
            header("Location: /login"); //Redirection sur la page login
            exit();
        }
    }
}