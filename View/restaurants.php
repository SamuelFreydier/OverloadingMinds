<!-- ~/php/tp1/view/restaurants.php -->
<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html;
            charset=utf-8" />
    </head>
    <title>All Restaurants</title>
    <body>
    <h1>All Restaurants</h1>
    <?php if(isset($params['flash'])) {
        echo "
           <p style='color: green'>
            " . $params['flash'] . " 
           </p>
        ";
    } ?>
    <table>
        <?php foreach ($params['restaurants'] as $restaurant) : ?>
        <tr>
            <td><a href="/restaurant/<?php echo $restaurant->getId() ?>"><?=
            $restaurant->getName(); ?></a></td>
            <td><?= $restaurant->getReputation(); ?></td>
        </tr>
        
        <?php endforeach; ?>

    </table>
    <p>
        <a href="/">Back to list of cities</a>
    </p>
    <p>
        <a href='/createrestaurant'>Create a new restaurant</a>
    </p>

    </body>
</html>