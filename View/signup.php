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
        
        <form id="login_box" style="height :46vh" action ='/created' method='POST'>
            <div><input type="email" name="email" placeholder="e-mail"></div>
            <div><input type="text" name="username" placeholder="nom d'utilisateur"></div>
            <div><input type="password" name="password" placeholder="mot de passe"></div>
            <div><input type="password" name="passwordconf" placeholder="confirmer le mot de passe"></div>
            <button type="submit">Créer un compte</button>
            <a href="/login">Annuler</a>
        </form>

        <div id="Image_BottomLeft"></div>
    </content>
    
</body>
</html>