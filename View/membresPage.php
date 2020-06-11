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
    <!-- Div qui s'occupe du menu en haut -->
    <div id="topMenu">
        <!-- Partie avec l'image et le bouton "profil" -->
        <div id="profilContainer">
            <!-- L'image est a l'interieur d'une balise <a> comme ca si on clique dessu ca renvoie sur le profil -->
            <a href="mainPage.html" style="color: #F9F8E6; height: 30px;"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
            <a href="mainPage.html" style="color: #F9F8E6; text-decoration: none;">return</a>
        </div>

        <!-- Bon ba la c'est juste le nom du site -->
        <h1 style="color: #F9F8E6;">Overloading Minds</h1>
        <!-- Partie avec la bar de recherche -->
        <div id="search_container">
            <!-- J'ai mis dans un "form" mais je sait pas si ca change qque chose avec le php -->
            <form>
                <!-- l'input est de type texte, c'est la ou l'utilisateur va ecrire. je sait pas si il falait mettre des trucs en plus pour le php -->
                <input type="text" placeholder="Search..">
                <!-- encore une fois je sait pas si il falait mettre des truc pour le php dans le boutton -->
                <button type="search"><i class="fa fa-search" aria-hidden="true"></i></button>
            </form>
        </div>
    </div>

    <!-- C'est la ou il y a la page principale -->
    <content>
        <!-- Div vide qui permet d'avoir la timeline au milieu -->
        <div class="sideBlocks"></div>

        <!-- Div qui contient le profil du User -->
        <div id="timeline">
            <!-- div qui contient le profil -->
            <div class="memberProfilInfo">
                <!-- Div pour la pp et le bouton follow -->
                <div id="ProfilInfoPic" >
                    <!-- La pp-->
                    <img src="../ressources/pp.jpg" class="profilPic" style="height: 80px; width: 80px;"/>
                </div>
                <!-- div avec le texte du profil -->
                <div id="profilInfoText">
                    <p><b>Polo</b></p>
                    <p>la bio du User un peut plus longue histoire de tester</p>
                    <!-- div avec les follow et tout -->
                    <div class="profilInfoStats">
                        <div class="profilInfoStats">
                            <p>7</p>
                            <p>abonnements</p>
                        </div>
                        <div class="profilInfoStats">
                            <p>1</p>
                            <p>abonnés</p>
                        </div>
                    </div>
                </div>
                <!-- Bouton de follow -->
                <button type="button" class="memberButton">follow</button>
            </div>
            <!-- fin du profil -->
             
        </div>

        <!-- Div vide qui permet d'avoir la timeline au milieu -->
        <div class="sideBlocks"></div>

    </content>

</body>
</html>