<!-- ~/php/tp1/view/createRestaurant.php -->
<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html;
            charset=utf-8" />
    </head>
    <title>Create a restaurant</title>
    <body>
    <h1>Create a restaurant</h1>

    <?php if(isset($error)) {
        echo "
           <p style='color: red'>
            An error has occured, please retry.
           </p>
        ";
    } ?>

    <form action="/restaurants" method="POST">
        <p>
            <label>Name of the restaurant</label>
            <input type="text" name="name" value="<?php if(isset($restaurant)) echo $restaurant['name']; ?>">
        </p>
        <p>
            <label>Reputation of the restaurant</label>
            <input type="text" name="reputation" value="<?php if(isset($restaurant)) echo $restaurant['reputation']; ?>">
        </p>

        <button type="submit">Submit</button>
    </form>

    </body>
</html>