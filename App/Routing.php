<?php

namespace App;
use App\Src\App;
use Controller\UserController;
use Controller\TweetController;

class Routing
{
    private $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function setup() {

        $user = new UserController($this->app);
        $tweet = new TweetController($this->app);

        $this->app->get('/', [$tweet, 'mainPageHandler']); //Timeline

        $this->app->post('/', [$tweet, 'newTweetHandler']); //Timeline (new tweet & retweet)

        $this->app->post('/liked', [$tweet, 'tweetLikeHandler']); //Timeline (like/unlike)

        $this->app->get('/login', [$user, 'userLoginFormHandler']); //Login

        $this->app->post('/login', [$user, 'userLoginHandler']); //Login après échec

        $this->app->get('/signup', [$user, 'userSignupFormHandler']); //Inscription

        $this->app->post('/signup', [$user, 'userSignupHandler']); //Inscription après échec

        $this->app->get('/logout', [$user, 'userLogout']); //Déconnexion
        
        $this->app->post('/likedprofile', [$tweet, 'tweetLikeProfileHandler']); //Profil (like/unlike)

        $this->app->get('/members', [$user, 'userResearchHandler']); //Recherche

        $this->app->post('/newfollow', [$user, 'userFollowHandler']); //Recherche (follow/unfollow)

        $this->app->post('/newfollowprofile', [$user, 'userFollowProfileHandler']); //Profil (follow/unfollow)

        $this->app->get('/user/(\w+)', [$user, 'userProfileHandler']); //Profil

        $this->app->get('/editprofile', [$user, 'userEditFormHandler']); //Paramètres

        $this->app->post('/editprofile', [$user, 'userEditHandler']); //Paramètres (validation ou échec)

        $this->app->post('/editimg', [$user, 'userEditAvatar']); //Paramètres (validation/échec img)

        $this->app->post('/deletetweet', [$tweet, 'deleteTweetHandler']); //Timeline (delete)

        $this->app->post('/deletetweetprofile', [$tweet, 'deleteTweetHandlerProfile']); //Profil (delete)




        $this->app->get('/404', [$user, 'display404']); //404
    }
}