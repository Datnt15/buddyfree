<form class="form-horizontal" action="" method="POST">
    <fieldset>
        <!-- Text input-->
        <?php if ($mes['type'] != ''): ?>
        	<!-- Alert message -->
        	<div class="form-group">
	            <label class="col-md-4 control-label" for=""></label>
	            <div class="col-md-8">
	                <div class="alert alert-<?php echo $mes['type']; ?> alert-dismissible" role="alert">
					  	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					  	<?php echo $mes['content']; ?>
					</div>
	            </div>
	        </div>
        <?php endif ?>
        <div class="form-group">
            <label class="col-md-4 control-label" for="project_title">
            	Project Title
            </label>
            <div class="col-md-8">
                <input id="project_title" name="project_title" type="text" placeholder="Job for PHP Dev" class="form-control input-md" required="">
				<?php if (isset($_POST['uid'])) : ?>
					<input type="hidden" name="freelancer_id" value="<?= $_POST['uid']; ?>">
				<?php endif; ?>
            </div>
        </div>

        <!-- Textarea -->
        <div class="form-group">
            <label class="col-md-4 control-label" for="project_description">Description</label>
            <div class="col-md-8">
                <textarea class="form-control" id="project_description" rows="10" name="project_description"></textarea>
            </div>
        </div>

        <!-- Text input-->
        <div class="form-group">
            <label class="col-md-4 control-label" for="project_price">Price</label>
            <div class="col-md-8">
                <input id="project_price" name="project_price" type="text" placeholder="500" class="form-control input-md" required="">

            </div>
        </div>

        <!-- File Button -->
        <!-- <div class="form-group">
            <label class="col-md-4 control-label" for="attachment">Attachment</label>
            <div class="col-md-8">
                <input id="attachment" name="attachment" class="input-file" type="file">
            </div>
        </div> -->

        <div class="form-group">
            <label class="col-md-4 control-label">Skills</label>
            <div class="col-md-8">
                
                <?php foreach ($skills as $skill): ?>
                    <label class="col-md-4 col-sm-6">
                        <input type="checkbox" name="skill_requirement[]" value="<?= $skill['skill_id'];?>">
                        <?php echo $skill['name']; ?>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Button -->
        <div class="form-group">
            <label class="col-md-4 control-label" for=""></label>
            <div class="col-md-4">
                <button id="" name="" class="btn btn-info">Create Project</button>
            </div>
        </div>

    </fieldset>
</form>