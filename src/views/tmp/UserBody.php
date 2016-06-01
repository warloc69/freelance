<div id="wrapper" class="container">
    <div id="header" class="row">
        <div id="logo" class="col-md-4">
            <div class="well">Best<br>Fleelance projects</div>
        </div>
        <div id="header-content" class="col-md-7 well text-center">
            Time is Money. We help You to save your time and money. <br>
            <hr>
        </div>
        <div id="header-content" class="col-md-4">
            <div class="well">
                <?php if (!isset($_SESSION['user_id'])) : ?>
                    <a href="<?php echo $link; ?>">Login <br> Authorize</a>
                <?php else : ?>
                    <a href="/logout">Logout</a><br>
                    <a href="project">My page</a>
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
            <div class="well">
                <button class="btn btn-default filter clearfix center" id="filter">&#10004; Filter</button> <br>
                <label for="budget">Budget:</label>
                <div><input type="text" name="budget" id="budget" placeholder="Budget &#128177;:"><br></div>
                <br>
                <label for="budget">Deadline:</label>
                <div><input type="text" name="deadline" id="deadline" placeholder="Deadline &#128282;:"><br></div>
                <br>
                <label for="budget"> &#128185; Reit:<span id="reit-value">50</span></label>
                <div><input type="range" name="reit" id="reit" min="0" max="100" placeholder="Reit &#128185;:"><br></div>
                <br>
                <label for="budget">Tags:</label>
                <div class="list-inline"><input type="text" name="tag" id="tag" placeholder="Tags:">
                    <button class="btn btn-default add-tags center" id="add-tags">&#10133;</button><br></div>
                <div class="list-group" id="list-group"></div>

            </div>
        </div>

        <div id="featured" class="col-md-12">
            <div class="row">
                <?php foreach ($context as $project) : ?>
                    <?php if (!isset($project['id'])) {
                        continue;
                    } ?>
                    <div class="well" ><a href="/project/<?php echo $project['id']; ?>">
                            <?php echo $project['name']; ?></a>
                        <br>
                        <div><?php echo $project['tags']; ?></div>
                    </div>
                <?php endforeach; ?>
                <div class="text-center">
                    <ul class="pagination">
                        <?php $j = 0;
                        if($context['total'] > 10 )
                        for ($i = 1;$i < $context['total'];$i += 10) : ?>
                            <li><a href="/?page=<?php echo $j; ?>"><?php echo $j++; ?></a></li>
                        <?php endfor; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(window).load(function () {
            loadListeners();
        });

    </script>