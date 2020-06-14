<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="180x180" href="../Ressources/Images/Favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../Ressources/Images/Favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../Ressources/Images/Favicon/favicon-16x16.png">
    <link rel="manifest" href="../Ressources/Images/Favicon/site.webmanifest">
    <?php include('../Style/login_style.php'); ?>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300&display=swap" rel="stylesheet">
    <title>Inscription - Overloading Minds</title>
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