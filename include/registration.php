<?php
	
if (is_user_logged_in()) {
	wp_safe_redirect( get_home_url() );
}
$mes = array('type' => '', 'content' => '');
if (isset($_POST['type'])) {
	$user_id = username_exists( $_POST['username'] );
	if ($user_id) {
		$mes['content'] = 'Username already exists!';
		$mes['type'] = 'warning';
	}
	if (email_exists($_POST['email'])) {
		$mes['content'] = 'Email already exists!';
		$mes['type'] = 'warning';
	}
	if ( !$user_id && email_exists($_POST['email']) == false) {
		$user_id = wp_create_user( $_POST['username'], $_POST['password'], $_POST['email'] );
		wp_update_user(
			array(
				'ID' 			=> $user_id,
				'display_name' 	=> $_POST['first_name'] . ' ' . $_POST['last_name'],
				'first_name' 	=> $_POST['first_name'],
				'last_name' 	=> $_POST['last_name'],
				'nickname' 		=> $_POST['username'],
				'role' 			=> $_POST['type']
			)
		);
		$mes['type'] = 'success';
		$mes['content'] = 'Registration successfully!';
	} 
}
?>

<div class="col-md-6 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-8 col-xs-offset-1">
	<form action="" method="POST">
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
	            <label class="control-label" for="username">
	            	Username:
	            </label>
	            <input id="username" name="username" type="text" placeholder="John_doe_15" class="form-control" required="">
	        </div>
	        
	        <!-- Textarea -->
	        <div class="form-group">
	            <label class="control-label" for="password">
	                Password
	            </label>
	            <input type="password" class="form-control" id="password" name="password" required="">
	        </div>

	        <!-- Text input-->
	        <div class="form-group">
	            <label class="control-label" for="email">
	                Email
	            </label>
	            <input id="email" name="email" type="email" placeholder="email@gmail.com" class="form-control input-md" required="">
	        </div>
			
			
	        <div class="form-group">
	            <label class="control-label" for="first_name">
	            	First name:
	            </label>
	            <input id="first_name" name="first_name" type="text" placeholder="John" class="form-control" required="">
	        </div>
	        

	        <div class="form-group">
	            <label class="control-label" for="last_name">
	            	Last name:
	            </label>
	            <input id="last_name" name="last_name" type="text" placeholder="Doe" class="form-control" required="">
	        </div>

	        <!-- Text input-->
	        <div class="form-group">
	            <label class="control-label" for="email">
	                User type:
	            </label>
	            <select class="form-control" name="type" required>
	            	<option value="freelancer" selected>Freelancer</option>
	            	<option value="employee">Employee</option>
	            </select>
	        </div>

	        <!-- Button -->
	        <div class="form-group">
	            <button class="btn btn-info">Create Account</button>
	        </div>

	    </fieldset>
	</form>
</div>