<?php

function initDB2(){
  global $wpdb;
  $wpdb->query("CREATE TABLE IF NOT EXISTS `$wpdb->prefix" . "skill` (
                  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                  `skill_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                  `skill_desc` TEXT COLLATE utf8_unicode_ci NOT NULL,
                  `skill_cat_id` INT(11) NOT NULL,
                  `date_create` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;");

  $wpdb->query("CREATE TABLE IF NOT EXISTS `$wpdb->prefix" . "skill_cat` (
                  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                  `cat_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                  `cat_desc` TEXT  COLLATE utf8_unicode_ci NOT NULL,
                  `parent_id` INT(11) NOT NULL,
                  `date_create` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;");
}

initDB2();




/**
 * Register skill manage page.
 */
function register_skill_manage_page() {
    
    add_menu_page( 
        __( 'Skills' ),
        'Skills',
        'manage_options',
        'skill_management',
        'skill_page_render',
        'dashicons-admin-page',
        6
    );

}

add_action( 'admin_menu', 'register_skill_manage_page' );


function mngt_add_skill_cat($data){
	global $wpdb;
    $wpdb->insert("$wpdb->prefix"."skill_cat", $data);
}

function mngt_add_skill($data){
	global $wpdb;
    $wpdb->insert("$wpdb->prefix"."skill", $data);
}

function update_skill($data,$id){
	global $wpdb;
	$wpdb->update( 
		"$wpdb->prefix"."skill", 
		$data, 
		array( 'id' => $id )
	);
}
function update_cat($data,$id){
	global $wpdb;
	$wpdb->update( 
		"$wpdb->prefix"."skill_cat", 
		$data, 
		array( 'id' => $id )
	);
}

function get_skill_cat_list(){
	global $wpdb;
    return $wpdb->get_results("SELECT * FROM $wpdb->prefix"."skill_cat;", ARRAY_A);
}

function get_skill_list(){
	global $wpdb;
    return $wpdb->get_results("SELECT $wpdb->prefix"."skill.*, $wpdb->prefix"."skill_cat.id AS cat_id, 
    									 $wpdb->prefix"."skill_cat.cat_name  
    									 FROM  $wpdb->prefix"."skill    							
    									 INNER JOIN $wpdb->prefix"."skill_cat
										ON $wpdb->prefix"."skill.skill_cat_id = $wpdb->prefix"."skill_cat.id;",ARRAY_A);
}

function get_skill_detail($id){
	global $wpdb;
    return $wpdb->get_results("SELECT * FROM $wpdb->prefix"."skill WHERE id=$id;", ARRAY_A)[0];
}

function get_category_detail($id){
	global $wpdb;
    return $wpdb->get_results("SELECT * FROM $wpdb->prefix"."skill_cat WHERE id=$id;", ARRAY_A)[0];
}
function skill_page_render(){

	$actual_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?page=skill_management";
	

	//edit category form
	if(isset($_GET) && ($_GET["action"]) == "edit-category"){
		//echo form
		return true;
	}

	//process Add Category
	if(isset($_POST) && ($_POST["form_type"] == "Category") ){
		
		$data["cat_name"] = $_POST["cat_name"];
		$data["cat_desc"] = $_POST["cat_desc"];
		$data["parent_id"] = $_POST["cat_parent_id"];
		
		mngt_add_skill_cat($data);
	}
	//process add skill
	if(isset($_POST) && ($_POST["form_type"] == "Skill") ){
		
		$data["skill_name"] = $_POST["skill_name"];
		$data["skill_desc"] = $_POST["skill_desc"];
		$data["skill_cat_id"] = $_POST["skill_cat_id"];
		
		mngt_add_skill($data);
	}
	
	//process edit skill
	if(isset($_POST) && ($_POST["form_type"] == "edit_skill_post") ){
		
		$data["skill_name"] = $_POST["edt_skill_name"];
		$data["skill_desc"] = $_POST["edt_skill_description"];
		$data["skill_cat_id"] = $_POST["edt_cat_id"];
		
		update_skill($data,$_POST["edt_skill_id"]);
	}

	//process edit cat
	if(isset($_POST) && ($_POST["form_type"] == "edit_cat_post") ){
		
		$data["cat_name"] = $_POST["edt_cat_name"];
		$data["cat_desc"] = $_POST["edt_cat_desc"];
		$data["parent_id"]=	$_POST["edt_parent_cat_id"];
		
		
		update_cat($data,$_POST["edt_cat_id"]);
	}



	$skill_cat_list = get_skill_cat_list();	
	$skill_list = get_skill_list();
	
	wp_register_style( 'bootstrap', "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" );
	wp_register_style( 'bootstrap-theme', plugins_url( 'buddyfree/assets/vendor/bootstrap-3.3.7-dist/css/bootstrap-theme.min.css' ) );

	wp_enqueue_style( 'bootstrap' );
	wp_enqueue_style( 'bootstrap-theme' );

	wp_enqueue_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js'); 
    
    wp_enqueue_script('bootstrap-js', plugins_url('buddyfree/assets/vendor/bootstrap-3.3.7-dist/js/bootstrap.min.js'));

    
    wp_enqueue_script('jquery');
    wp_enqueue_script('bootstrap-js');


	?>

	<h1> Skills 
		<button type="button" class="btn btn-info btn-small" data-toggle="modal" data-target="#catModal" style="margin: 0px 0px 5px 20px;">Add Category</button> 
		<button type="button" class="btn btn-info btn-small" data-toggle="modal" data-target="#skillModal" style="margin: 0px 0px 5px 20px;">Add Skill</button> 
	</h1>
	
	<?php
		//Edit skill
		
		if(isset($_GET) && ($_GET["action"] == "edit-skill")){

		$sk = get_skill_detail($_GET['id']);
		
		?>
		<form class="form-inline" method="POST" action="<?php echo $actual_link;?>" >
		  
		    <input type="text" class="form-control" id="edt_skill_name" name="edt_skill_name" value="<?php echo $sk['skill_name'];?>">		  
		    <input type="text" class="form-control" id="edt_skill_description" name="edt_skill_description" value="<?php echo $sk['skill_desc'];?>">	  
		    <input type="hidden" name="edt_skill_id" value="<?php echo $sk['id'];?>">

		    <select class="form-control" id="edt_skill_cat_id" style="height: 34px;" name="edt_cat_id">
			    <option value="0">Select Category</option>
			    <?php
			    foreach ($skill_cat_list as $key => $cat) {
			    	if ($cat["id"] == $sk["skill_cat_id"]) $selected = "selected"; else $selected = " ";
			    	echo "<option value='".$cat["id"]."'".$selected.">".$cat["cat_name"]."</option>";
			    }
			    ?>						    
			</select>
		  	<input type="hidden" name="form_type" value="edit_skill_post"> 
		  	<button type="submit" class="btn btn-default">Update</button>
		</form>
		<?php
		//return true;
	}
	?>

	<?php
		//Edit Category
		

		if(isset($_GET) && ($_GET["action"] == "edit-cat")){
			
			$ct = get_category_detail($_GET["id"]);
			
		?>
		<form class="form-inline" method="POST" action="<?php echo $actual_link;?>" >
		  
		    <input type="text" class="form-control" id="edt_cat_name" name="edt_cat_name" value="<?php echo $ct['cat_name'];?>">		  
		    <input type="text" class="form-control" id="edt_cat_description" name="edt_cat_desc" value="<?php echo $ct['cat_desc'];?>">	  
		    <input type="hidden" name="edt_cat_id" value="<?php echo $ct['id'];?>">

		    <select class="form-control" id="edt_parent_cat_id" style="height: 34px;" name="edt_parent_cat_id">
			    <option value="0">Select Category</option>
			    <?php

			    foreach ($skill_cat_list as $key => $cat) {
			    	if ($cat["id"] == $ct["parent_id"]) $selected = "selected"; else $selected = " ";
			    	echo "<option value='".$cat["id"]."'".$selected.">".$cat["cat_name"]."</option>";
			    }
			    ?>						    
			</select>
		  	<input type="hidden" name="form_type" value="edit_cat_post"> 
		  	<button type="submit" class="btn btn-default">Update</button>
		</form>
		<?php
		//return true;
	}
	?>

	<!-- Add Category  Modal -->
	<div id="catModal" class="modal fade" role="dialog">
	  <div class="modal-dialog">

	    <!-- Modal content-->
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title">Add New Category</h4>
	      </div>
	      <form method="POST" action="<?php echo $actual_link;?>" id="cat-form">
			    <div class="modal-body">
					
					  <div class="form-group">
					    <input type="text" placeholder="Category Name" class="form-control" id="cat_name" name="cat_name">
					  </div>
					  <div class="form-group">
					    <textarea placeholder="Description" class="form-control" id="cat_desc" name="cat_desc"></textarea>
					  </div>	  
					  <div class="form-group">
					  	<select class="form-control" id="cat-parent" style="height: 34px;" name="cat_parent_id">
						    <option value="0">Select Parent Category</option>
						    <?php

						    foreach ($skill_cat_list as $key => $cat) {
						    	echo "<option value='".$cat["id"]."'>".$cat["cat_name"]."</option>";
						    }
						    ?>						    
						</select>
					  </div>
					  <input type="hidden" name="form_type" value="Category">
			      </div>
			      <div class="modal-footer">
			        <input type="submit" name="cat_submit" class="btn btn-default" data-dismiss="modal" value="Add Category" id="cat_submit">
			      </div>
			</form>
	    </div>

	  </div>
	</div>
	<!--END MODAL-->

	<!-- Add Skill Modal -->
	<div id="skillModal" class="modal fade" role="dialog">
	  <div class="modal-dialog">
	    <!-- Modal content-->
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title">Add New Skill</h4>
	      </div>
	      <form method="POST" action="<?php echo $actual_link;?>" id="skill-form">
			    <div class="modal-body">
					
					  <div class="form-group">
					    <input type="text" placeholder="Skill Name" class="form-control" id="skill_name" name="skill_name">
					  </div>
					  <div class="form-group">
					    <textarea placeholder="Description" class="form-control" id="skill_desc" name="skill_desc"></textarea>
					  </div>	  
					  <div class="form-group">
					  	<select class="form-control" id="skill_cat_id" style="height: 34px;" name="skill_cat_id">
						    <option value="0">Select Category</option>
						    <?php

						    foreach ($skill_cat_list as $key => $cat) {
						    	echo "<option value='".$cat["id"]."'>".$cat["cat_name"]."</option>";
						    }
						    ?>						    
						</select>
					  </div>
					  <input type="hidden" name="form_type" value="Skill">
			      </div>
			      <div class="modal-footer">
			        <input type="submit" name="skill_submit" class="btn btn-default" data-dismiss="modal" value="Add Skill" id="skill_submit">
			      </div>
			</form>
	    </div>

	  </div>
	</div>

	<!-- END MODAL -->

	<script type="text/javascript">		
		jQuery("#cat_submit").click(function(){
			jQuery("#cat-form").submit();
		});
		jQuery("#skill_submit").click(function(){
			jQuery("#skill-form").submit();
		});
		
	</script>
	
	<h3>List of Skills</h3>
	
	<table class="table table-hover">
	    <thead>
	      <tr>
	      	<th>Number</th>
	        <th>Skill</th>
	        <th>Description</th>
	        <th>Category</th>
	        <th>Edit</th>
	      </tr>
	    </thead>
	    <tbody>
	    
	    <?php
	    	$i = 1;

	    	foreach ($skill_list as $key => $skill) {
	    		
	    		echo "<tr>";
	    		echo "<td>".$i++."</td>";
	    		echo "<td>".$skill["skill_name"]."</td>";
	    		echo "<td>".$skill["skill_desc"]."</td>";
	    		echo "<td>".$skill["cat_name"]."</td>";
	    		echo "<td><a class='skill-edit-btn' href='".$actual_link."&action=edit-skill&id=".$skill["id"]."'>Edit</a></td>";
	    		echo "</tr>";
	    	}
	    ?>     
	      
	    </tbody>

	  </table>

	<h3>List of Categories</h3>
	
	<table class="table table-hover">
	    <thead>
	      <tr>
	      	<th>Number</th>
	        <th>Category Name</th>
	        <th>Description</th>
	        <th>Parent</th>
	        <th>Edit</th>
	      </tr>
	    </thead>
	    <tbody>
	    <?php
	    	$i = 1;
	    	foreach ($skill_cat_list as $key => $cat) {
	    		echo "<tr>";
	    		echo "<td>".$i++."</td>";
	    		echo "<td>".$cat["cat_name"]."</td>";
	    		echo "<td>".$cat["cat_desc"]."</td>";
	    		echo "<td>".$cat["cat_name"]."</td>";
	    		echo "<td><a class='cat-edit-btn' href='".$actual_link."&action=edit-cat&id=".$cat["id"]."'>Edit</a></td>";
	    		echo "</tr>";
	    	}
	    ?>
	     	      
	    </tbody>	    
	  </table>


	<?php
}

