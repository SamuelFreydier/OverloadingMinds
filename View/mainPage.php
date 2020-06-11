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
            <a href="#"><img src="../ressources/pp.jpg" class="profilPic"/></a>
            <a href="#" style="color: #F9F8E6; text-decoration: none;">
            <?php if(session_status() === PHP_SESSION_ACTIVE && isset($_SESSION['auth'])) {
                echo $_SESSION['auth'];
            } ?> </a>
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
        <div style="width: 30vw; height: 100%;"> </div>

        <!-- Div qui contient la timeline -->
        <div id="timeline">
            <!-- Div qui contient toute la partie pour ecrire un tweet-->
            <div id="tweetBox">
                <!-- Image a coté de la boite pour ecrire le tweet -->
                <img src="../Ressources/pp.jpg" class="profilPic"/>

                <!-- Div qui contient la boite de texte et le boutton pour poster -->
                <div id="tweetPost">
                    <!-- Textarea simple, il faut sans doute rajouter des trucs pour le php -->
                    <form action="/" method="POST">
                        <textarea placeholder="What's up ?" name="text"></textarea>

                        <!-- Boutton simple aussi -->
                        <button type="submit">post</button>
                    </form>
                </div>
            </div>

            <!-- Div qui contient tout les tweet -->
            <div style=" background-color: #F9F8E6;">


                <?php foreach ($params['tweets'] as $tweet) : ?>

                    <div>
                        <?php if ($tweet->getRetweet() !== null): ?>
                            <!-- div pour afficher qui a rt -->
                            <div class="tweetMainContainer">
                                <!-- icone de rt (a supprimer si aucun rt) -->
                                <i class="fa fa-retweet" aria-hidden="true"></i>

                                <!-- Qui a rt (a supprimer si aucun rt) -->
                                <p><?php echo $tweet->getAuthor(); ?> a retweeté</p>
                            </div>

                            <!-- Code generique des tweets -->
                            <div class="tweetContainer">
                                <!-- Image du profil avec un <a> pour cliquer sur la photo -->
                                <a href="#"><img src="../ressources/pp.jpg" class="profilPic"/></a>

                                <!-- Partie principale du tweet -->
                                <div class="tweetBody">
                                    <!-- Div avec le Username et la date du poste -->
                                    <div class="tweetInfo">
                                        <!-- Username -->
                                        <div>   <p><b><?php echo $tweet->getRetweet()->getAuthor(); ?></b></p>   </div>
                                        <!-- Date -->
                                        <div>   <p><?php echo $tweet->getRetweet()->getDate(); ?></p>   </div>
                                    </div>

                                    <!-- le texte du tweet -->
                                    <div class="tweetText">   
                                        <p><?php echo $tweet->getRetweet()->getText(); ?></p>   
                                    </div>

                                    <!-- Div qui contient les likes et retweets -->
                                    <div class="tweerLikes">
                                        <!-- les retweet -->
                                        <div class="tweerLikes">
                                            <!-- Boutton pour retweeter -->
                                            <form action="/rt" method="POST">
                                                <input type="hidden" name="text" value="<?php echo null ?>">
                                                <input type="hidden" name="id" value="<?php echo $tweet->getRetweet()->getId(); ?>">
                                                <button type="submit"><i class="fa fa-retweet fa-2x" aria-hidden="true"></i></button>
                                            </form>
                                            <!-- Compteur de rt-->
                                            <p><?php echo $tweet->getRetweet()->getNbRt(); ?></p>
                                        </div>

                                        <!-- les likes -->
                                        <div class="tweerLikes">
                                            <!-- boutton pour liker -->
                                            <form action="/liked" method="POST">
                                                <input type="hidden" name="id" value="<?php echo $tweet->getRetweet()->getId(); ?>">
                                                <button type="submit"><i class="fa fa-thumbs-up fa-2x" aria-hidden="true"></i></button>
                                            </form>
                                            <!-- compteur de likes -->
                                            <p><?php echo $tweet->getRetweet()->getLikes(); ?></p>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        <?php else: ?>
                            <!-- Code generique des tweets -->
                            <div class="tweetContainer">
                                <!-- Image du profil avec un <a> pour cliquer sur la photo -->
                                <a href="#"><img src="../ressources/pp.jpg" class="profilPic"/></a>

                                <!-- Partie principale du tweet -->
                                <div class="tweetBody">
                                    <!-- Div avec le Username et la date du poste -->
                                    <div class="tweetInfo">
                                        <!-- Username -->
                                        <div>   <p><b><?php echo $tweet->getAuthor(); ?></b></p>   </div>
                                        <!-- Date -->
                                        <div>   <p><?php echo $tweet->getDate(); ?></p>   </div>
                                    </div>

                                    <!-- le texte du tweet -->
                                    <div class="tweetText">   
                                        <p><?php echo $tweet->getText(); ?></p>   
                                    </div>

                                    <!-- Div qui contient les likes et retweets -->
                                    <div class="tweerLikes">
                                        <!-- les retweet -->
                                        <div class="tweerLikes">
                                            <!-- Boutton pour retweeter -->
                                            <form action="/rt" method="POST">
                                                <input type="hidden" name="text" value="<?php echo null ?>">
                                                <input type="hidden" name="id" value="<?php echo $tweet->getId(); ?>">
                                                <button type="submit"><i class="fa fa-retweet fa-2x" aria-hidden="true"></i></button>
                                            </form>
                                            <!-- Compteur de rt-->
                                            <p><?php echo $tweet->getNbRt(); ?></p>
                                        </div>

                                        <!-- les likes -->
                                        <div class="tweerLikes">
                                            <!-- boutton pour liker -->
                                            <form action="/liked" method="POST">
                                                <input type="hidden" name="id" value="<?php echo $tweet->getId(); ?>">
                                                <button type="submit"><i class="fa fa-thumbs-up fa-2x" aria-hidden="true"></i></button>
                                            </form>
                                            <!-- compteur de likes -->
                                            <p><?php echo $tweet->getLikes(); ?></p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        <?php endif ?>
                    </div>
                <?php endforeach; ?>
            </div>
            
        </div>

        <!-- Div vide qui permet d'avoir la timeline au milieu -->
        <div style="width: 30vw; height: 100%;"></div>

    </content>
</body>
</html>