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


    public function userLoginFormHandler(Request $request) { //Affichage du formulaire de Login
       
        $this->loginRedirect(); //Vérif connexion

        return $this->app->render('login');
    }

    public function userSignupFormHandler(Request $request) {
        
        $this->loginRedirect(); //Vérif connexion

        return $this->app->render('signup');
    }

    public function userSignupHandler(Request $request) { //Traitement du formulaire d'inscription
        
        $username = htmlspecialchars($request->getParameters('username'));
        $email = htmlspecialchars($request->getParameters('email'));
        $password = htmlspecialchars($request->getParameters('password'));
        $passwordconf = htmlspecialchars($request->getParameters('passwordconf'));


        $aValid = '_';
        if(!ctype_alnum(str_replace($aValid, '', $username))) { //Si le pseudo contient des caractères spéciaux et des espaces (seule exception : _)
            $error = "Votre nom d'utilisateur ne doit pas contenir de caractères spéciaux."; //Message d'erreur
            return $this->app->render('signup', ["error" => $error]);
        }

        if(strlen($username) > 25) { //Si le pseudo est trop long
            $error = "Votre nom d'utilisateur est trop long."; //Message d'erreur
            return $this->app->render('signup', ["error" => $error]);
        }

        if($password != $passwordconf) { //Si les mdp ne match pas
            $error = "Votre mot de passe n'a pas été confirmé."; //Message d'erreur
            return $this->app->render('signup', ["error" => $error]);
        }

        if(empty($email) || empty($username) || empty($password)) { //Si un champ est vide
            $error = "Tous les champs doivent être remplis."; //Message d'erreur
            return $this->app->render('signup', ["error" => $error]);
        }


        $user = $this->app->getService('userFinder')->findOneByUsername($username);
        if($user !== null) { //Si l'utilisateur existe déjà
            if($user->getId() !== null) {
                $error = "Ce nom d'utilisateur a déjà été pris."; //Message d'erreur
                return $this->app->render('signup', ["error" => $error]);
            }
        }

        //Chiffrement du mot de passe
        $passwordhashed = password_hash($password, PASSWORD_DEFAULT);

        $newuser = [
            'username' => $username,
            'password' => $passwordhashed,
            'email' => $email
        ];

        
        //Création du nouvel utilisateur (son image de profil par défaut est BASEIMAGE.png)
        $this->app->getService('userFinder')->save($newuser);

        session_start(); //On démarre la session pour le connecter directement
        $_SESSION['auth'] = $username; //On enregistre son nom dans la session

        header("Location: /"); //Redirection sur la timeline
        exit();
    }

    public function userLoginHandler(Request $request) { //Traitement du formulaire de login

        $username = htmlspecialchars($request->getParameters('username'));
        $password = htmlspecialchars($request->getParameters('password'));
        $result = $this->app->getService('userFinder')->findOneByUsername($username);

        if($result === null) { //Si personne n'a été trouvé
            $error = "Nom d'utilisateur ou mot de passe incorrect."; //Erreur
            return $this->app->render('login', ['error' => $error]);
        }
        if (!password_verify($password, $result->getPassword())) { //Si le mdp est incorrect
            $error = "Nom d'utilisateur ou mot de passe incorrect."; //Erreur
            return $this->app->render('login', ['error' => $error]);
        }

        session_start();
        $_SESSION['auth'] = $username; //On démarre la session avec à l'intérieur le nom de l'utilisateur
       
        header("Location: /"); //Redirection sur la timeline
        exit();
    }

    public function userResearchHandler(Request $request) { //Traitement de la recherche
        
        $this->logoutRedirect(); //Vérif connexion

        $search = "";
        if(isset($_GET['search'])) {
            $search = htmlspecialchars($_GET['search']);
        }
        $author = ($this->app->getService('userFinder')->findOneByUsername(htmlspecialchars($_SESSION['auth'])))->getId();
        $users = $this->app->getService('userFinder')->search($search); //On trouve la liste des users par rapport au bout de chaîne que l'on a donné
        if(!empty($users)) { //Si il y a au moins un user
            foreach($users as $user) { //Pour chaque user
                $userfollowed = $this->app->getService('userFinder')->findOneUserFollowed($user->getUsername());
                $user->setUserFollowed($userfollowed->getUserFollowed()); //On lui associe son nombre de followers
                $userfollows = $this->app->getService('userFinder')->findFollows($user->getId());
                $user->setFollower($userfollows->getFollower()); //On lui associe son nombre de follows
                if($this->app->getService('userFinder')->isFollowedByCurrent($author, $user->getId()) === null) { //On observe s'il est follow ou non par l'user courant
                    $user->setBoolFollowed(false);
                }
                else {
                    $user->setBoolFollowed(true);
                }
            }
        }
        return $this->app->render('membresPage', ["users" => $users, "author" => $author, "search" => $search]);
    }

    public function userFollowHandler(Request $request) { //Follow-Unfollow
        
        $this->logoutRedirect(); //Vérif connexion

        $search = $request->getParameters('search'); //On va réutiliser la recherche précédente pour afficher la même page
        $author = ($this->app->getService('userFinder')->findOneByUsername(htmlspecialchars($_SESSION['auth'])))->getId(); //User courant
        $usertofollow = $request->getParameters('userid'); //User à suivre
        if($this->app->getService('userFinder')->isFollowedByCurrent($author, $usertofollow) === null) { //Est-ce que l'user ciblé est follow par l'user courant ?
            $this->app->getService('userFinder')->follow($author, $usertofollow); //Si oui, on le follow
        }
        else {
            $this->app->getService('userFinder')->unfollow($author, $usertofollow); //Si non, on l'unfollow
        }
        
        header("Location: /members?search=".$search); //On redirige vers la même page de recherche
        exit();
    }

    public function userFollowProfileHandler(Request $request) { //Pareil qu'au dessus mais si on follow/unfollow depuis un profil
        
        $this->logoutRedirect(); //Vérif connexion

        $username = htmlspecialchars($request->getParameters('username'));
        $author = ($this->app->getService('userFinder')->findOneByUsername($_SESSION['auth']))->getId();
        $user = $this->app->getService('userFinder')->findOneByUsername($username);

        $author = htmlspecialchars($_SESSION['auth']); //User courant
        $author = ($this->app->getService('userFinder')->findOneByUsername($author))->getId();
        if($this->app->getService('userFinder')->isFollowedByCurrent($author, $user->getId()) === null) {
            $this->app->getService('userFinder')->follow($author, $user->getId());
        }
        else {
            $this->app->getService('userFinder')->unfollow($author, $user->getId());
        }

        header("Location: /user/".$username); //Redirection sur la page de profil précédente
        exit();
    }

    public function userProfileHandler(Request $request, $username) { //Affichage du profil d'un utilisateur
        
        $this->logoutRedirect(); //Vérif connexion

        $username = htmlspecialchars($username);
        $user = $this->app->getService('userFinder')->findOneByUsername($username);
        if($user === null) { //Si l'utilisateur indiqué dans la recherche n'existe pas
            return $this->app->render('404'); //Page 404
        }
        $userfollowed = $this->app->getService('userFinder')->findOneUserFollowed($username);
        $user->setUserFollowed($userfollowed->getUserFollowed()); //Nombre de followers
        $userfollows = $this->app->getService('userFinder')->findFollows($user->getId());
        $user->setFollower($userfollows->getFollower()); //Nombre de follows

        $author = htmlspecialchars($_SESSION['auth']); //User courant
        $author = ($this->app->getService('userFinder')->findOneByUsername($author))->getId();
        if($this->app->getService('userFinder')->isFollowedByCurrent($author, $user->getId()) === null) { //On observe si l'utiisateur courant follow ou non l'user ciblé
            $user->setBoolFollowed(false);
        }
        else {
            $user->setBoolFollowed(true);
        }
        $tweets = $this->app->getService('tweetFinder')->allTweetsFromUser($user->getId()); //On affiche les tweets et RT de l'utilisateur ciblé
        if(!empty($tweets)) {
            $tweets = $this->renderTweets($tweets);
        }
        return $this->app->render('profil', ['user' => $user, 'tweets' => $tweets, 'author' => $author]); //On affiche la page de profil
    }

    public function userEditFormHandler(Request $request) { //Affichage du formulaire de paramètres
        
        $this->logoutRedirect(); //Vérif connexion

        $username = htmlspecialchars($_SESSION['auth']);
        return $this->app->render('edit', ['auth' => $username]);
    }

    public function userEditHandler(Request $request) { //Traitement de l'édition de la bio
        
        $this->logoutRedirect(); //Vérif connexion

        $username = htmlspecialchars($_SESSION['auth']);
        $bio = htmlspecialchars($request->getParameters('bio'));

        if(strlen($bio) > 250) { //Si la bio dépasse 250 caractères
            $error = "La bio ne doit pas dépasser 250 caractères."; //Erreur
            return $this->app->render('edit', ["auth" => $username, "error" => $error]);
        }
        $request = [];

        $this->app->getService('userFinder')->updateBio($username, $bio); //Update

        $validation = "La bio a été mise à jour."; //Message de validation
        return $this->app->render('edit', ["validation" => $validation, "auth" => $username]); //On revient sur les paramètres
    }

    public function userEditAvatar(Request $request) { //Changement de l'image de profil

        $this->logoutRedirect(); //Vérif connexion

        $username = htmlspecialchars($_SESSION['auth']);
        $img = $_FILES['img'];
        $ext = strtolower(substr($img['name'], -3));
        $allow_ext = array("jpg", "png", "gif");
        if(in_array($ext, $allow_ext)) { //Si l'extension correspond (si c'est bien une image)
            $path = "../Ressources/Images/" . $username . "." . $ext;
            move_uploaded_file($img['tmp_name'], $path);
            $this->app->getService('userFinder')->updateImg($username, $path); //On l'ajoute à la BD sur la ligne de l'utilisateur courant, elle porte son nom.ext (ex : Samuel.png)
        }
        else { //Sinon
            $errorimg = "Votre fichier n'est pas une image."; //Erreur
            return $this->app->render('edit', ['auth' => $username, "errorimg" => $errorimg]);
        }

        $validationimg = "L'image de profil a été mise à jour."; //Validation
        return $this->app->render('edit', ['auth' => $username, "validationimg" => $validationimg]);
    }

    public function userLogout(Request $request) { //Déconnexion
        session_destroy(); //Destruction de la session
        header("Location: /login"); //Redirection sur login
        exit();
    }

    public function display404(Request $request) { //404
        return $this->app->render('404'); //Affichage de la page 404
    }


    public function logoutRedirect() { //Logout l'utilisateur si sa session n'existe pas
        if(!isset($_SESSION['auth'])) { 
            header("Location: /login"); //Redirection sur la page login
            exit();
        }
    }
    public function loginRedirect() { //Login l'utilisateur si sa session existe déjà
        if(isset($_SESSION['auth'])) { //Si l'utilisateur est déjà connecté il faut le rediriger sur la timeline
            header("Location: /"); //Redirection sur la page timeline
            exit();
        }
    }

}