<?php

namespace App;
use App\Src\App;
use App\Routing;
use App\Src\ServiceContainer\ServiceContainer;
use Database\Database;
use Model\Finder\CityFinder;
use Model\Finder\RestaurantFinder;

$container = new ServiceContainer();
$app = new App($container);

$app->setService('database', new Database(
    $host = "127.0.0.1",
    $name = "citytowns",
    $user = "root",
    $pass = ""
));

$app->setService('cityFinder', new CityFinder($app));
$app->setService('restaurantFinder', new RestaurantFinder($app));

$routing = new Routing($app);
$routing->setup();

return $app;