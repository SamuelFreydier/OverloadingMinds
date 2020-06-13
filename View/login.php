<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include('../Style/login_style.php'); ?>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300&display=swap" rel="stylesheet">
    <title>Overloading Minds</title>
</head>

<body>

    <content>
        <div id="Image_TopRight"></div>

        <h1 id="Title">OVERLOADING <br>MINDS</h1>
        
        <form id="login_box" action="/loginfinished" method="POST">
            <div><input type="text" name="username" placeholder="nom d'utilisateur" value="<?php if(isset($user)) echo $user['username']; ?>"></div>
            <div><input type="password" name="password" placeholder="mot de passe"></div>
            <a href="#"">Mot de passe oublié ?</a>
            <button type="submit">Connexion</button>
            <a href="/signup">S'inscrire</a>
        </form>

        <div id="Image_BottomLeft"></div>
    </content>
    
</body>
</html>
