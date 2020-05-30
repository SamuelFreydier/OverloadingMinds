<!-- ~/php/tp1/view/cities.php -->
<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html;
            charset=utf-8" />
    </head>
    <title>All Cities</title>
    <body>
    <h1>All Cities</h1>
    <?php if(session_status() === PHP_SESSION_ACTIVE && isset($_SESSION['auth'])) {
        echo "
           <p style='color: green'>
            " . $_SESSION['auth'] . " 
           </p>
        ";
    } ?>
    <table>
        <?php foreach ($params['cities'] as $city) : ?>
        <tr>
            <td><a href="/city/<?php echo $city->getId() ?>"><?=
            $city->getName(); ?></a></td>
            <td><?= $city->getCountry(); ?></td>
            <td>Quality of life: <?= $city->getLife(); ?></td> <!--added property life-->
        </tr>
        
        <?php endforeach; ?>

    </table>
    <p>
        <a href="/recherche">Search cities by name</a>
    </p>
    <p>
        <a href='/create'>Create a new city</a>
    </p>
    <p>
        <a href="/countries">Countries</a>
    </p>
    <p>
        <a href="/restaurants">Restaurants</a>
    </p>

    </body>
</html>