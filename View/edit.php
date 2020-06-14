<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include('../Style/mainPage_style.php'); ?>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Overloading Minds</title>
</head>
<body>
    
    <div id="topMenu">

        <!-- Partie avec l'image et le bouton "profil" -->
        <div id="profilContainer">
            <!-- L'image est a l'interieur d'une balise <a> comme ca si on clique dessu ca renvoie sur le profil -->
            <a href="/user/<?php echo $params['auth']; ?>" style="color: #F9F8E6; height: 30px;"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
            <a href="/user/<?php echo $params['auth']; ?>" style="color: #F9F8E6; text-decoration: none;">Retour</a>
        </div>

        <!-- Bon ba la c'est juste le nom du site -->
        <h1 style="color: #F9F8E6;"><a href="/" style="color: #F9F8E6;">Overloading Minds</a></h1>

        <!-- Partie avec la bar de recherche -->
        <div id="search_container">
            <!-- J'ai mis dans un "form" mais je sait pas si ca change qque chose avec le php -->
            <form action="/members" method="GET">
                <!-- l'input est de type texte, c'est la ou l'utilisateur va ecrire. je sait pas si il falait mettre des trucs en plus pour le php -->
                <input type="text" name="search" placeholder="Recherche...">
                <!-- encore une fois je sait pas si il falait mettre des truc pour le php dans le boutton -->
                <button type="search"><i class="fa fa-search" aria-hidden="true"></i></button>
            </form>
        </div>
    </div>

    <content>
        <div class="sideBlocks"> </div>
            <div id="timeline" style="background-color: #F9F8E6; padding: 20px;">
            <div id="tweetPost">
                <h2><b>Modifier la photo de profil:</b></h2>
                <form action="/editimg" method="POST" enctype="multipart/form-data">
                    <input type="file" name="img">
                    <button type="submit">Sauvegarder</button>
                </form>
                <h2><b>Modifier la bio du profil:</b></h2>
                <form action="/editbio" method="POST">
                    <textarea placeholder="Nouvelle bio" name="bio"></textarea>
                    <button type="submit">Sauvegarder</button>
                </form>
            </div>
            </div>
        <div class="sideBlocks"></div>
    </content>

</body>
</html>