<div class="bid-request-form no-show" id="bid-request-form">
    <div class="container">
        <div class="light-box-body">
            <div class="row">
                <a class="close" id="cancel" href="">&nbsp;</a>
                <div class="col-md-10 center">
                    <h2 class="header">Project: <?php echo $context['name']; ?></h2>
                    <form class="form-horizontal" role="form">
                        <div class="form-group">
                            <?php if (isset($_SESSION['user_type'])): ?>
                                <input type="hidden" name="logined" id="logined">
                            <?php endif; ?>
                            <label class="control-label col-sm-5" for="comment">Comment: </label>
                            <div class="col-sm-15">
                                <textarea class="form-control" rows="5" id="comment"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <button class="btn btn-default center"
                    id="generate">Send Request
            </button>
        </div>
    </div>
</div>
<div id="wrapper" class="container">
    <div id="header" class="row">
        <div id="logo" class="col-md-4">
            <div class="well">Freelance projects</div>
        </div>
        <div id="header-content" class="col-md-7">
            <div class="well"><?php echo $context['name']; ?></div>
        </div>
        <div id="header-content" class="col-md-4">
            <div class="well">
                Budget: <?php echo $context['cost']; ?>
            </div>
        </div>
    </div>
    <div id="category" class="row">
        <div id="featured" class="col-md-15">
            <div class="row">
                <div class="well" style="min-height: 600px;">
                    <br>
                    <div>Description: <?php echo $context['description']; ?></div>
                    <br>
                    <div>Tags: <?php echo $context['tags']; ?></div>
                    <br>
                    <div class="list-inline">
                        <div>Deadline: <?php echo $context['dedline']; ?></div>
                        <div>Expected Reit: <?php echo $context['expected_rait']; ?></div>
                    </div>
                    <?php if (isset($context['status']) && $context['status'] != 'F') : ?>
                        <button class="btn btn-default center" id="make-request">Make Request</button>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
    <script>
        $(window).load(function () {
            loadListeners();
        });
    </script>