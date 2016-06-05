<?php $login="https://accounts.google.com/o/oauth2/auth?redirect_uri=".
    "http://lance.local.com/auth&response_type=code&client_id=663315718006-67cmagfdoitr0i3ra6c2bsj4vllbno8k.apps.googleusercontent.com&".
    "scope=https://www.googleapis.com/auth/userinfo.email%20https://www.googleapis.com/auth/userinfo.profile";
?>
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
                    <a href="<?php echo $login; ?>">Login <br> Authorize</a>
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
        @filters
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
                @paginator
            </div>
        </div>
    </div>
    <script>
        $(window).load(function () {
            loadListeners();
        });
    </script>