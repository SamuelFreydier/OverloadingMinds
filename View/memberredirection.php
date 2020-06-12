<?php if($params['search'] !== ""): ?>
    <?php header("Location: https://overloadingminds.cleverapps.io/members?search=".$params['search']); exit(); ?>
<?php else: ?>
    <?php header("Location: https://overloadingminds.cleverapps.io/members"); exit(); ?>
<?php endif; ?>