<div class="bid-request-form no-show" id="bid-request-form">
    <div class="container">
        <div class="light-box-body">
            <div class="row">
                <a class="close" id="cancel" href="">&nbsp;</a>
                <div class="col-md-10 center">
                    <h2 class="header">Project: <?php echo $context['name']; ?></h2>
                    <form class="form-horizontal" role="form">
                        <div class="form-group">
                            <label class="control-label col-sm-5" for="comment">Comment: </label>
                            <div class="col-sm-15">
                                <textarea class="form-control" rows="5" id="comment"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <button class="btn btn-default center"
                    id="generate">Send Request</button>
        </div>
    </div>
</div>
<!----------------------------------------------------------------->
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
    <div id="category" class="row">
       <div id="featured" class="col-md-12">
            <div class="row">
                    <div class="well" style="min-height: 600px;">
                        <br>
                        <div>Project name: <?php echo $context['name']; ?></div>
                        <br>
                        <div>Description: <?php echo $context['description']; ?></div>
                        <br>
                        <div>Budget: <?php echo $context['cost']; ?></div>
                        <br>
                        <div>Tags: <?php echo $context['tags']; ?></div>
                        <br>
                        <div>Deadline: <?php echo $context['dedline']; ?></div>
                        <br>
                        <div>Expected Reit: <?php echo $context['expected_rait']; ?></div>
                        <button class="btn btn-default center" id="make-request">Make Request</button>
                    </div>

            </div>
        </div>
    </div>
    <script>
        $(window).load(function () {
            loadListeners();
        });

    </script>