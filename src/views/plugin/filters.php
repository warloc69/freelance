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