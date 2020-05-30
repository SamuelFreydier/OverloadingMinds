<?php

namespace App;
use Controller\CityController;
use Controller\RestaurantsController;
use App\Src\App;
use Controller\UserController;

class Routing
{
    private $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function setup() {

        $city = new CityController($this->app);
        $restaurant = new RestaurantsController($this->app);
        $user = new UserController($this->app);

        $this->app->get('/', [$city, 'citiesHandler']);

        $this->app->get('/city/(\d+)', [$city, 'cityHandler']);

        $this->app->get('/countries', [$city, 'countriesHandler']);

        $this->app->get('/countries/(\w+)', [$city, 'countryHandler']);

        $this->app->get('/create', [$city, 'formHandler']);

        $this->app->get('/recherche', [$city, 'searchHandlerBegin']);

        $this->app->get('/recherche/(\w+)', [$city, 'searchHandler']);
        
        //$this->app->post('/', [$city, 'createHandler']);

        $this->app->get('/restaurants', [$restaurant, 'restaurantsHandler']);

        $this->app->get('/restaurant/(\d+)', [$restaurant, 'restaurantHandler']);

        $this->app->get('/createrestaurant', [$restaurant, 'formHandler']);

        $this->app->post('/restaurants', [$restaurant, 'createHandler']);

        $this->app->get('/restaurant/(\d+)/update', [$restaurant, 'formUpdateHandler']);

        $this->app->post('/restaurant/(\d+)', [$restaurant, 'updateHandler']);

        $this->app->get('/restaurant/(\d+)/deleted', [$restaurant, 'deleteHandler']);

        $this->app->get('/login', [$user, 'userLoginFormHandler']);

        $this->app->post('/loginfinished', [$user, 'userLoginHandler']);

        $this->app->get('/signup', [$user, 'userSignupFormHandler']);

        $this->app->post('/', [$user, 'userSignupHandler']);
    }
}