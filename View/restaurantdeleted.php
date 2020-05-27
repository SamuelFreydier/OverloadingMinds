<!-- ~/php/tp1/view/restaurantdeleted.php -->
<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html;
            charset=utf-8" />
    </head>
    <title>Restaurant deleted</title>
    <?php if(isset($params['flash'])) {
        echo "
           <p style='color: green'>
            " . $params['flash'] . " 
           </p>
        ";
    } ?>
    <body>
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