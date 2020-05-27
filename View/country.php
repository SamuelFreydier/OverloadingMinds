<!-- ~/php/tp1/view/cities.php -->
<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html;
            charset=utf-8" />
    </head>
    <title>All cities</title>
    <body>
    <h1>All cities from <?= $params['country']; ?></h1>
    <table>
        <?php foreach ($params['citiesFromCountry'] as $city) : ?>
        <tr>
            <td><?= $city->getName(); ?></td>
        </tr>
        
        <?php endforeach; ?>
    </table>
    </body>
</html>