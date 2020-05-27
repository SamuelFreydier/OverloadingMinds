<!-- ~/php/tp1/view/city.php -->
<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html;
            charset=utf-8" />
    </head>
    <title>One city</title>
    <body>
    <h1>City <?= $params['city']->getName(); ?></h1>
        <p>
            Name of the city: <?= $params['city']->getName(); ?>
        </p>
        <p>
            Country: <?= $params['city']->getCountry(); ?>
        </p>
        <p>
            Quality of life: <?= $params['city']->getLife(); ?>
        </p>

        <a href="/">
            Back to list of cities
        </a>
    </body>
</html>