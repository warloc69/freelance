<div class="row">
    <?php foreach ($context['top_users'] as $top) : ?>
        <div class="menu-footer col-sm-3">
            <div class="well">reit :<?php echo floor($top['avg_reit']); ?>
                <br><?php echo $top['first_name'].' '.$top['last_name']; ?></div>
        </div>
    <?php endforeach; ?>
</div>