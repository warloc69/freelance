<div class="project-estimate-form no-show" id="project-estimate-form">
    <div class="container">
        <div class="light-box-body">
            <div class="row">
                <div class="col-md-10 center widget">
                    <h2 class="header">Please estimate freelancer work</h2>
                    <form class="form-horizontal" role="form">
                        <div class="form-group">
                            <label class="control-label col-sm-5" for="comment">Comment: </label>
                            <div class="col-sm-15">
                                <label for="budget"> &#128185; Reit:<span id="reit-value">50</span></label>
                                <div><input type="range" name="reit" id="reit" min="0" max="100" placeholder="Reit &#128185;:"><br></div>
                                <input type="hidden" name="project-id" id="project-id">
                            </div>
                        </div>
                    </form>
                    <button class="btn btn-default center"
                            id="estimate" name="estimate">Estimate
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>
<!----------------------------------------------------------------->
<div class="project-add-form no-show" id="project-add-form">
    <div class="container">
        <div class="light-box-body">
            <div class="row">
                <div class="col-md-10 center ">
                    <h2 class="header">Create new project:</h2>
                    <div class="well">
                        <label for="budget">Name:</label>
                        <div><input type="text" name="name" id="name" placeholder="Name &#128177;:"></div>
                        <label class="control-label col-sm-5" for="description">Description: </label>
                        <textarea class="form-control" rows="5" id="description"></textarea>
                        <br>
                        <label for="budget">Budget:</label>
                        <div><input type="text" name="budget" id="budget" placeholder="Budget &#128177;:"></div>

                        <label for="budget">Deadline:</label>
                        <div><input type="text" name="deadline" id="deadline" placeholder="Deadline &#128282;:"><br></div>
                        <br>
                        <label for="budget"> &#128185; Reit:<span id="expected-reit-value">50</span></label>
                        <div><input type="range" name="expected-reit" id="expected-reit" min="0" max="100" placeholder="Reit &#128185;:"><br></div>
                        <br>
                        <label for="budget">Tags:</label>
                        <div class="list-inline"><input type="text" name="tag" id="tag" placeholder="Tags:">
                            <button class="btn btn-default add-tags center" id="add-tags">&#10133;</button><br></div>
                        <div class="list-group" id="list-group"></div>
                    </div>
                    <button class="btn btn-default center"
                            id="create" name="create">Create
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>
<!----------------------------------------------------------------->
<div id="wrapper" class="container">
    <div id="header" class="row">
        <div id="logo" class="col-md-4">
            <div class="well">Best<br><br>Fleelance projects</div>
        </div>
        <div id="header-content" class="col-md-7 well text-center">
            Time is Money. We help You to save your time and money. <br>
            <hr>
            <form action="/changerole" method="post">
                <label class="radio-inline "><input type="radio"
                        <?php if (isset($context['user_type']) && $context['user_type'] == "PM") : ?>
                            checked
                        <?php endif; ?> name="user-role" value="PM">Project Manager</label>
                <label class="radio-inline"><input type="radio"
                        <?php if (isset($context['user_type']) && $context['user_type'] == "F") : ?>
                            checked
                        <?php endif; ?> name="user-role" value="F">Freelancer</label>
                <input type="submit" value="Change">
            </form>
        </div>
        <div id="header-content" class="col-md-4">
            <div class="well">
                <?php if (!isset($context['user_id'])) : ?>
                    <a href="<?php echo $link; ?>">Login <br> Authorize</a>
                <?php else : ?>
                    <a href="/logout">Logout</a> <br> <br>
                    <a href="/">Home</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div id="category" class="row">
        <div id="featured" class="col-md-15">
            <div class="row">
                <div class="well">
                    <div class="col-md-2 ">
                        Name
                    </div>
                    <div class="col-md-5 ">
                        Description
                    </div>
                    <div class="col-md-2 ">
                        Deadline
                    </div>
                    <div class="col-md-2 ">
                        Budget
                    </div>
                    <div class="col-md-1 " >
                        Reit
                    </div>
                    <div class="col-md-1 ">
                        Status
                    </div>
                    <?php if (isset($context['user_type']) && $context['user_type'] == "PM") : ?>
                    <div class="col-md-1 ">
                        <button class="btn btn-sm"
                                id="add">Add
                        </button>
                    </div>
                    <?php endif; ?>
                    <br>
                </div>
                <?php foreach ($context as $project) : ?>
                    <?php if (!isset($project['id'])) {
                        continue;
                    } ?>
                    <div class="well" style="display: table; min-width: 100%;">
                        <div class="col-md-2 ">
                            <a href="/project/<?php echo $project['id']; ?>">
                                <?php echo $project['name']; ?></a><br>
                            <div><?php echo $project['tags']; ?></div>
                        </div>
                        <div class="col-md-5 ">
                            <?php echo $project['description']; ?>
                        </div>
                        <div class="col-md-2 ">
                            <?php echo $project['dedline']; ?>
                        </div>
                        <div class="col-md-2 ">
                            <?php echo $project['cost']; ?>
                        </div>
                        <div class="col-md-1 " id="reit-<?php echo $project['id']; ?>">
                            <?php echo $project['reit']; ?>
                        </div>
                        <div class="col-md-1 " id="status-<?php echo $project['id']; ?>">
                            <?php if ($project['status'] == 'N') : ?>
                                New
                            <?php elseif ($project['status'] == 'A') : ?>
                                Active
                            <?php elseif ($project['status'] == 'F') : ?>
                                Finished
                            <?php endif; ?>
                        </div>
                        <?php if (isset($context['user_type']) && $context['user_type'] == "PM") : ?>
                        <div class="col-md-2 ">
                            <?php if ($project['status'] == 'A') : ?>
                                <button class="btn btn-sm finish"
                                        id="<?php echo $project['id']; ?>">Finish
                                </button>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                        <br>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <script>
        $(window).load(function () {
            loadListeners();
        });

    </script>