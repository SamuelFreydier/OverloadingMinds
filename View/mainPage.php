<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="180x180" href="../Ressources/Images/Favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../Ressources/Images/Favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../Ressources/Images/Favicon/favicon-16x16.png">
    <link rel="manifest" href="../Ressources/Images/Favicon/site.webmanifest">
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
            <a href="/user/<?php echo $_SESSION['auth'] ?>"><img src="<?php echo $params['author']->getImg(); ?>" class="profilPic"/></a>
            <a href="/user/<?php echo $_SESSION['auth'] ?>" style="color: #F9F8E6; text-decoration: none;">
            <?php if(session_status() === PHP_SESSION_ACTIVE && isset($_SESSION['auth'])) {
                echo $_SESSION['auth'];
            } ?> </a>
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
            <a href="/logout"><button>Déconnexion</button></a>
        </div>
    </div>

    <!-- C'est la ou il y a la page principale -->
    <content>
        <!-- Div vide qui permet d'avoir la timeline au milieu -->
        <div class="sideBlocks"> </div>

        <!-- Div qui contient la timeline -->
        <div id="timeline">
            <!-- Div qui contient toute la partie pour ecrire un tweet-->
            <div id="tweetBox">
                <!-- Image a coté de la boite pour ecrire le tweet -->
                <img src="<?php echo $params['author']->getImg(); ?>" class="profilPic"/>
                
                <!-- Div qui contient la boite de texte et le boutton pour poster -->
                <div id="tweetPost">
                    <?php if(isset($params['error'])): ?>
                        <?php echo "<p style = 'color : red; margin-left: 20px'>" . $params['error'] . "</p>"; ?>
                    <?php endif; ?>
                    <?php if(isset($params['validation'])): ?>
                        <?php echo "<p style = 'color : green; margin-left: 20px'>" . $params['validation'] . "</p>"; ?>
                    <?php endif; ?>
                    <?php if(isset($params['alert'])): ?>
                        <?php echo "<p style = 'color : darkorange; margin-left: 20px'>" . $params['alert'] . "</p>"; ?>
                    <?php endif; ?>
                    <!-- Textarea simple, il faut sans doute rajouter des trucs pour le php -->
                    <form action="/" method="POST" enctype="multipart/form-data">
                        
                        <textarea placeholder="Quoi de neuf ?" name="text"></textarea>
                        <input type="file" name="img">
                        <!-- Boutton simple aussi -->
                        <button type="submit">Envoyer</button>
                    </form>
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
                                    <p><?php echo $tweet->getAuthor()["username"]; ?> a retweeté</p>
                                </div>

                                <!-- Code generique des tweets -->
                                <div class="tweetContainer">
                                    <!-- Image du profil avec un <a> pour cliquer sur la photo -->
                                    <a href="/user/<?php echo $tweet->getRetweet()->getAuthor()["username"]; ?>"><img src="<?php echo $tweet->getRetweet()->getAuthor()["img"]; ?>" class="profilPic"/></a>

                                    <!-- Partie principale du tweet -->
                                    <div class="tweetBody">
                                        <!-- Div avec le Username et la date du poste -->
                                        <div class="tweetInfo">
                                            <!-- Username -->
                                            <div>   <p><b><a href="/user/<?php echo $tweet->getRetweet()->getAuthor()["username"]; ?>"><?php echo $tweet->getRetweet()->getAuthor()["username"]; ?></a></b></p>   </div>
                                            <!-- Date -->
                                            <div>   <p><?php echo $tweet->getRetweet()->getDate(); ?></p>   </div>
                                        </div>

                                        <!-- le texte du tweet -->
                                        <div class="tweetText">   
                                            <p><?php echo $tweet->getRetweet()->getText(); ?></p>   
                                        </div>

                                        <?php if($tweet->getRetweet()->getImg() !== null): ?>
                                            <img src="<?php echo $tweet->getRetweet()->getImg(); ?>">
                                        <?php endif; ?>

                                        <!-- Div qui contient les likes et retweets -->
                                        <div class="tweerLikes">
                                            <?php if($tweet->getRetweet()->getAuthor()["username"] === $_SESSION['auth']): ?>
                                                <form action="/deletetweet" method="POST">
                                                    <input type="hidden" name="tweetid" value="<?php echo $tweet->getRetweet()->getId(); ?>">
                                                    <button type="submit">Supprimer</button>
                                                </form>
                                            <?php endif; ?>
                                            <!-- les retweet -->
                                            <div class="tweerLikes">
                                                <!-- Boutton pour retweeter -->
                                                <form action="/" method="POST">
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

                                <div class="tweetMainContainer">
                                </div>
                                <!-- Code generique des tweets -->
                                <div class="tweetContainer">
                                    <!-- Image du profil avec un <a> pour cliquer sur la photo -->
                                    <a href="/user/<?php echo $tweet->getAuthor()["username"]; ?>"><img src="<?php echo $tweet->getAuthor()["img"]; ?>" class="profilPic"/></a>

                                    <!-- Partie principale du tweet -->
                                    <div class="tweetBody">
                                        <!-- Div avec le Username et la date du poste -->
                                        <div class="tweetInfo">
                                            <!-- Username -->
                                            <div>   <p><b><a href="/user/<?php echo $tweet->getAuthor()["username"]; ?>"><?php echo $tweet->getAuthor()["username"]; ?></a></b></p>   </div>
                                            <!-- Date -->
                                            <div>   <p><?php echo $tweet->getDate(); ?></p>   </div>
                                        </div>

                                        <!-- le texte du tweet -->
                                        <div class="tweetText">   
                                            <p><?php echo $tweet->getText(); ?></p>   
                                        </div>

                                        <?php if($tweet->getImg() !== null): ?>
                                            <img src="<?php echo $tweet->getImg(); ?>">
                                        <?php endif; ?>

                                        <!-- Div qui contient les likes et retweets -->
                                        <div class="tweerLikes">
                                            <?php if($tweet->getAuthor()["username"] === $_SESSION['auth']): ?>
                                                <form action="/deletetweet" method="POST">
                                                    <input type="hidden" name="tweetid" value="<?php echo $tweet->getId(); ?>">
                                                    <button type="submit">Supprimer</button>
                                                </form>
                                            <?php endif; ?>
                                            <!-- les retweet -->
                                            <div class="tweerLikes">
                                                <!-- Boutton pour retweeter -->
                                                <form action="/" method="POST">
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
            </div>
            
        </div>

        <!-- Div vide qui permet d'avoir la timeline au milieu -->
        <div class="sideBlocks"></div>

    </content>
</body>
</html>