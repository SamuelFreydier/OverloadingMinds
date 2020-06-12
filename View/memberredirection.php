<?php if($params['search'] !== ""): ?>
    <?php header("Location: http://localhost:8000/members?search=".$params['search']); exit(); ?>
<?php else: ?>
    <?php header("Location: http://localhost:8000/members"); exit(); ?>
<?php endif; ?>