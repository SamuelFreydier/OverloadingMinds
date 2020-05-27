<!-- ~/php/tp1/view/updateRestaurant.php -->
<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html;
            charset=utf-8" />
    </head>
    <title>Update the restaurant</title>
    <body>
    <h1>Update the restaurant</h1>

    <?php if(isset($error)) {
        echo "
           <p style='color: red'>
            An error has occured, please retry.
           </p>
        ";
    } ?>

    <form action="/restaurant/<?php echo $params['restaurant']->getId() ?>" method="POST">
        <p>
            <label>New name of the restaurant</label>
            <input type="text" name="name" value="<?php if(isset($city)) echo $city['name']; ?>">
        </p>
        <p>
            <label>New reputation of the restaurant</label>
            <input type="text" name="reputation" value="<?php if(isset($city)) echo $city['country']; ?>">
        </p>

        <button type="submit">Submit</button>
    </form>

    </body>
</html>