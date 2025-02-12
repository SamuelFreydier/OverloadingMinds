<?php

namespace App;
use App\Src\App;
use App\Routing;
use App\Src\ServiceContainer\ServiceContainer;
use Database\Database;
use Model\Finder\UserFinder;
use Model\Finder\TweetFinder;

$container = new ServiceContainer();
$app = new App($container);

$app->setService('database', new Database(
    getenv('MYSQL_ADDON_HOST'),
    getenv('MYSQL_ADDON_DB'),
    getenv('MYSQL_ADDON_USER'),
    getenv('MYSQL_ADDON_PASSWORD'),
    getenv('MYSQL_ADDON_PORT') 
));

$app->setService('userFinder', new UserFinder($app));
$app->setService('tweetFinder', new TweetFinder($app));

$routing = new Routing($app);
$routing->setup();

return $app;