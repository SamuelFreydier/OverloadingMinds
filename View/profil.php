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
            <a href="/" style="color: #F9F8E6; height: 30px;"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
            <a href="/" style="color: #F9F8E6; text-decoration: none;">return</a>
        </div>

        <!-- Bon ba la c'est juste le nom du site -->
        <h1 style="color: #F9F8E6;"><a href="/">Overloading Minds</a></h1>

        <!-- Partie avec la bar de recherche -->
        <div id="search_container">
            <!-- J'ai mis dans un "form" mais je sait pas si ca change qque chose avec le php -->
            <form action="/members" method="GET">
                <!-- l'input est de type texte, c'est la ou l'utilisateur va ecrire. je sait pas si il falait mettre des trucs en plus pour le php -->
                <input type="text" name="search" placeholder="Search..">
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
                    <?php if($params['author'] !== $params['user']->getId()) : ?>
                            <?php if($params['user']->getBoolFollowed() === false) : ?>
                                <!-- Bouton de follow -->
                                <form action ="/newfollowprofile" method="POST">
                                    <input type="hidden" name="username" value="<?php echo $params['user']->getUsername(); ?>">
                                    <button type="submit" class="memberButton">follow</button>
                                </form>
                            <?php else: ?>
                                <form action ="/newfollowprofile" method="POST">
                                    <input type="hidden" name="username" value="<?php echo $params['user']->getUsername(); ?>">
                                    <button type="submit" class="memberButton">unfollow</button>
                                </form>
                            <?php endif; ?>
                    <?php endif; ?>
                </div>
                
                <!-- div avec le texte du profil -->
                <div id="profilInfoText">
                    <p><b><?php echo $params['user']->getUsername(); ?></b></p>
                    <p><?php echo $params['user']->getBio(); ?></p>

                    <!-- div avec les follow et tout -->
                    <div class="profilInfoStats">
                        <div class="profilInfoStats">
                            <p><?php echo $params['user']->getFollower(); ?></p>
                            <p>abonnements</p>
                        </div>
                        <div class="profilInfoStats">
                            <p><?php echo $params['user']->getUserFollowed(); ?></p>
                            <p>abonnés</p>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Div qui contient tout les tweet -->
            <div style=" background-color: #F9F8E6;">
                <?php if(!empty($params['tweets'])): ?>                
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
                                    <a href="/user/<?php echo $tweet->getRetweet()->getAuthor(); ?>"><img src="../ressources/pp.jpg" class="profilPic"/></a>
                            
                                    <!-- Partie principale du tweet -->
                                    <div class="tweetBody">
                                        <!-- Div avec le Username et la date du poste -->
                                        <div class="tweetInfo">
                                            <!-- Username -->
                                            <div>   <p><b><a href="/user/<?php echo $tweet->getRetweet()->getAuthor(); ?>"><?php echo $tweet->getRetweet()->getAuthor(); ?></a></b></p>   </div>
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
                                                <form action="/likedprofile" method="POST">
                                                    <input type="hidden" name="id" value="<?php echo $tweet->getRetweet()->getId(); ?>">
                                                    <input type="hidden" name="userid" value="<?php echo $params['user']->getId(); ?>">
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
                                    <a href="/user/<?php echo $tweet->getAuthor(); ?>"><img src="../ressources/pp.jpg" class="profilPic"/></a>
                            
                                    <!-- Partie principale du tweet -->
                                    <div class="tweetBody">
                                        <!-- Div avec le Username et la date du poste -->
                                        <div class="tweetInfo">
                                            <!-- Username -->
                                            <div>   <p><b><a href="/user/<?php echo $tweet->getAuthor(); ?>"><?php echo $tweet->getAuthor(); ?></a></b></p>   </div>
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
                <?php endif; ?>    
                <!-- fin du tweet -->


            </div>  
        </div>

        <!-- Div vide qui permet d'avoir la timeline au milieu -->
        <div class="sideBlocks"></div>

    </content>

</body>
</html>