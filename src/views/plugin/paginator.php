<div class="text-center">
    <ul class="pagination">
        <?php $j = 0;
        if($context['total'] > 10 )
            for ($i = 1;$i < $context['total'];$i += 10) : ?>
                <li><a href="/?page=<?php echo $j; ?>"><?php echo $j++; ?></a></li>
            <?php endfor; ?>
    </ul>
</div>