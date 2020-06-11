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

    <content>
        <!-- Div vide qui permet d'avoir la timeline au milieu -->
        <div class="sideBlocks"> </div>

        <!-- Div qui contient la timeline -->
        <div id="timeline">
            <!-- Div pour le profil du User -->
            <div id="profilInfo">
                <!-- Div pour la pp et le bouton follow -->
                <div id="ProfilInfoPic">
                    <!-- La pp-->
                    <img src="../ressources/pp.jpg" class="profilPic" style="height: 120px; width: 120px;"/>
                    <!-- Je sait pas comment faire le le unfollow, sans doute juste changer le texte -->
                    <button type="button">follow</button>
                </div>
                
                <!-- div avec le texte du profil -->
                <div id="profilInfoText">
                    <p><b>Polo</b></p>
                    <p>la bio du User</p>

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
            </div>


            <!-- Div qui contient tout les tweet -->
            <div style=" background-color: #F9F8E6;">

                <div>
                    <!-- div pour afficher qui a rt -->
                    <div class="tweetMainContainer">
                        <!-- icone de rt (a supprimer si aucun rt) -->
                        <i class="fa fa-retweet" aria-hidden="true"></i>

                        <!-- Qui a rt (a supprimer si aucun rt) -->
                        <p>Username a retweeté</p>
                    </div>

                    <div class="tweetContainer">
                        <!-- Image du profil avec un <a> pour cliquer sur la photo -->
                        <a href="#"><img src="../ressources/pp.jpg" class="profilPic"/></a>
                        
                        <!-- Partie principale du tweet -->
                        <div class="tweetBody">
                            <!-- Div avec le Username et la date du poste -->
                            <div class="tweetInfo">
                                <!-- Username -->
                                <div>   <p><b>Username</b></p>   </div>
                                <!-- Date -->
                                <div>   <p>date - hours/minutes</p>   </div>
                            </div>

                            <!-- le texte du tweet -->
                            <div class="tweetText">   
                                <p>message principale ici, ca doir etre un truc styl fsddd anticonstitutionnellement dddddddddd dddddddddddd dddddfffff ffffffffffff fffffffddddde 140 char max je croit</p>   
                            </div>

                            <!-- Div qui contient les likes et retweets -->
                            <div class="tweerLikes">
                                <!-- les retweet -->
                                <div class="tweerLikes">
                                    <!-- Boutton pour retweeter -->
                                    <button type="submit"><i class="fa fa-retweet fa-2x" aria-hidden="true"></i></button>
                                    <!-- Compteur de rt-->
                                    <p>2</p>
                                </div>

                                <!-- les likes -->
                                <div class="tweerLikes">
                                    <!-- boutton pour liker -->
                                    <button type="submit"><i class="fa fa-thumbs-up fa-2x" aria-hidden="true"></i></button>
                                    <!-- compteur de likes -->
                                    <p>2</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- fin du tweet -->


            </div>  
        </div>

        <!-- Div vide qui permet d'avoir la timeline au milieu -->
        <div class="sideBlocks"></div>

    </content>

</body>
</html>