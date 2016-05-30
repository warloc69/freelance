<div id="wrapper" class="container">
    <div id="header" class="row">
        <div id="logo" class="col-md-4">
            <div class="well">Best<br>Fleelance projects</div>
        </div>
        <div id="header-content" class="col-md-7">
            <div class="well"></div>
        </div>
        <div id="header-content" class="col-md-4">
            <div class="well">
                <?php if (!isset($_SESSION['user_id'])) : ?>
                    <a href="<?php echo $link; ?>">Login <br> Authorize</a>
                <?php else : ?>
                    <a href="/logout">Logout</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="row">
        <?php foreach ($context['top'] as $top) : ?>
            <div class="menu-footer col-sm-3">
                <div class="well">reit :<?php echo floor($top['avg_reit']); ?>
                    <br><?php echo $top['first_name'].' '.$top['last_name']; ?></div>
            </div>
        <?php endforeach; ?>
    </div>
    <div id="category" class="row">
        <div id="sidebar" class="col-md-3">
            <div class="well">3<br>#filters</div>
        </div>
        <div id="featured" class="col-md-12">
            <div class="row">
                <?php foreach ($context as $project) : ?>
                    <?php if (!isset($project['id'])) {
                        continue;
                    } ?>
                    <div class="well"><a href="/project/<?php echo $project['id']; ?>">
                            <?php echo $project['name']; ?></a>
                        <br>
                        <div><?php echo $project['tags']; ?></div>
                    </div>
                <?php endforeach; ?>
                <div class="text-center">
                    <ul class="pagination">
                        <?php $j = 0;
                        for ($i = 1;$i < $context['total'];$i += 10) : ?>
                            <li><a href="/?page=<?php echo $j; ?>"><?php echo $j++; ?></a></li>
                        <?php endfor; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>