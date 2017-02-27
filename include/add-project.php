
<form class="" action="" method="POST">
    <fieldset>
        <!-- Text input-->
        <?php if ($mes['type'] != ''): ?>
        	<!-- Alert message -->
        	<div class="form-group">
	            <div class="alert alert-<?php echo $mes['type']; ?> alert-dismissible" role="alert">
				  	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
				  	<?php echo $mes['content']; ?>
	            </div>
	        </div>
        <?php endif ?>
        
        <div class="form-group">
            <label class="control-label" for="project_title">
            	Job Title (required)
            </label>
            <input id="project_title" name="project_title" type="text" placeholder="Job for PHP Dev" class="form-control input-md" required="">
        </div>
        <div class="form-group">
            <!-- <label class="control-label" for="freelancer_id">
                Freelancer (optional)
            </label> -->
            <?php if (isset($_POST['uid'])): ?>
                <input type="hidden" name="freelancer_id" value="<?php echo $_POST['uid']; ?>">
            <?php endif; ?>
            <!-- 
            <select name="freelancer_id" id="freelancer_id" class="form-control">
                <?php foreach(get_all_freelancers() as $freelancer): ?>
                    <option value="<?php //echo $freelancer->ID; ?>" <?php if (isset($_POST['uid']) && $_POST['uid'] == $freelancer->ID) //echo 'selected'; ?>>
                        <?php //echo $freelancer->data->user_nicename;?>
                    </option>
                <?php endforeach; ?>
            </select> -->
        </div>

        <!-- Textarea -->
        <div class="form-group">
            <label class="control-label" for="project_description">
                Description (required)
            </label>
            <textarea class="form-control" id="project_description" rows="10" name="project_description"></textarea>
        </div>

        <!-- Text input-->
        <div class="form-group">
            <label class="control-label" for="project_price">
                Price (optional)
            </label>
            <input id="project_price" name="project_price" type="text" placeholder="500" class="form-control input-md" required="">
        </div>

        <!-- File Button -->
        <!-- <div class="form-group">
            <label class="control-label" for="attachment">Attachment</label>
            <input id="attachment" name="attachment" class="input-file" type="file">
        </div> -->

        <div class="form-group">
            <label class="control-label">
                Skills (required)
            </label>
        </div>
        <div class="row">
            
            <?php foreach ($skills as $skill): ?>
                <label class="col-sm-6 col-md-4 col-xs-6" for="skill<?= $skill['id'];?>">
                    <input type="checkbox" name="skill_requirement[]" id="skill<?= $skill['id'];?>" value="<?= $skill['id'];?>">
                    <?php echo $skill['skill_name']; ?>
                </label>
            <?php endforeach; ?>
        </div>

        <!-- Button -->
        <div class="form-group">
            <button id="" name="" class="btn btn-info">Create Project</button>
        </div>

    </fieldset>
</form>
