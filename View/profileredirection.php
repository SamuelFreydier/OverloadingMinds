<?php echo $params['user']->getUser(); ?>
<?php header("Location: https://overloadingminds.cleverapps.io/user/".$params['user']->getUsername()); exit(); ?>