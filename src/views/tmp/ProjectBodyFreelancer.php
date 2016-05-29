<div id="wrapper" class="container">
    <div id="header" class="row">
        <div id="logo" class="col-md-4"><div class="well">Best<br>Fleelance projects</div></div>
        <div id="header-content" class="col-md-7"><div class="well"></div></div>
        <div id="header-content" class="col-md-4"><div class="well">
                <?php if(!isset($_SESSION['user_id'])) :?>
                    <a href="<?php echo $link; ?>">Login <br> Authorize</a>
                <?php else :?>
                    <a href="/logout">Logout</a>
                <?php endif; ?>
            </div></div>
    </div>
   