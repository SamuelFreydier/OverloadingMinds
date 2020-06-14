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
            <a href="/" style="color: #F9F8E6; height: 30px;"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
            <a href="/" style="color: #F9F8E6; text-decoration: none;">Retour</a>
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
        <div class="sideBlocks"></div>

        <!-- Div qui contient le profil du User -->
        <div id="timeline">
            <?php if(!empty($params['users'])) : ?>
                <?php foreach($params['users'] as $user): ?>
                    <!-- div qui contient le profil -->
                    <div class="memberProfilInfo">
                        <!-- Div pour la pp et le bouton follow -->
                        <div id="ProfilInfoPic" >
                            <!-- La pp-->
                            <a href="/user/<?php echo $user->getUsername(); ?>"><img src="<?php echo $user->getImg(); ?>" class="profilPic" style="height: 80px; width: 80px;"/></a>
                        </div>
                        <!-- div avec le texte du profil -->
                        <div id="profilInfoText">
                            <p><b><a href="/user/<?php echo $user->getUsername(); ?>"><?php echo $user->getUsername(); ?></a></b></p>
                            <p><?php echo $user->getBio(); ?></p>
                            <!-- div avec les follow et tout -->
                            <div class="profilInfoStats">
                                <div class="profilInfoStats">
                                    <p><?php echo $user->getFollower(); ?></p>
                                    <p>abonnements</p>
                                </div>
                                <div class="profilInfoStats">
                                    <p><?php echo $user->getUserFollowed(); ?></p>
                                    <p>abonnés</p>
                                </div>
                            </div>
                        </div>
                        <?php if($params['author'] !== $user->getId()) : ?>
                            <?php if($user->getBoolFollowed() === false) : ?>
                                <!-- Bouton de follow -->
                                <form action ="/newfollow" method="POST">
                                    <input type="hidden" name="search" value="<?php echo $params['search']; ?>">
                                    <input type="hidden" name="userid" value="<?php echo $user->getId(); ?>">
                                    <button type="submit" class="memberButton">Suivre</button>
                                </form>
                            <?php else: ?>
                                <form action ="/newfollow" method="POST">
                                    <input type="hidden" name="search" value="<?php echo $params['search'] ?>">
                                    <input type="hidden" name="userid" value="<?php echo $user->getId(); ?>">
                                    <button type="submit" class="memberButton">Ne plus suivre</button>
                                </form>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <!-- fin du profil -->
                <?php endforeach; ?>
            <?php endif; ?>
             
        </div>

        <!-- Div vide qui permet d'avoir la timeline au milieu -->
        <div class="sideBlocks"></div>

    </content>

</body>
</html>