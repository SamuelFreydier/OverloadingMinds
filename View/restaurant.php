<!-- ~/php/tp1/view/restaurant.php -->
<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html;
            charset=utf-8" />
    </head>
    <title>One restaurant</title>
    <?php if(isset($params['flash'])) {
        echo "
           <p style='color: green'>
            " . $params['flash'] . " 
           </p>
        ";
    } ?>
    <body>
    <h1>Restaurant <?= $params['restaurant']->getName(); ?></h1>
        <p>
            Name of the restaurant: <?= $params['restaurant']->getName(); ?>
        </p>
        <p>
            Reputation: <?= $params['restaurant']->getReputation(); ?>
        </p>

        <p>
            <a href="/restaurant/<?php echo $params['restaurant']->getId() ?>/update">
                Update the restaurant
            </a>
        </p>
        <p>
            <a href="/restaurant/<?php echo $params['restaurant']->getId() ?>/deleted">
                Delete the restaurant
            </a>
        </p>
        <p>
            <a href="/">
                Back to list of cities
            </a>
        </p>
        <p>
            <a href="/restaurants">
                Back to list of restaurants
            </a>
        </p>
    </body>
</html>