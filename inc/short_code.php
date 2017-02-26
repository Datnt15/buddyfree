<?php 

add_shortcode( 'find-freelancers', 'render_finding_freelancers' );
function render_finding_freelancers() {
    require TEMPLATE.'find-freelancer.php';
}



add_shortcode( 'add-project', 'render_add_project' );
function render_add_project() {
	$skills = get_list_skill();
	$mes = array('type' => '', 'content' => '');
	if (isset($_POST['project_title'])) {
        $freelancer_id = (isset($_POST['freelancer_id'])) ? $_POST['freelancer_id'] : 0;
		$project_id = wp_insert_post( array(
            'post_title'    =>  $_POST['project_title'],
            'post_type'     => 'projects',
            'post_content'  => $_POST['project_description'],
            'post_status'   => 'publish',
            'post_author'   => get_current_user_id()
        ) );
        if ($project_id) {
        	$project_metas = array(
        		'project_price' 	=> $_POST['project_price'],
        		'freelancer_id' 	=> $freelancer_id,
        		'skill_requirement' => serialize($_POST['skill_requirement']),
        		'project_status' 	=> 'pending'
        	);
        	foreach ($project_metas as $key => $value) {
        		if ( ! add_post_meta( $project_id, $key, $value, true ) ) {
					update_post_meta( $project_id, $key, $value );
				}
        	}
            if ($freelancer_id != 0) {
                $user = get_userdata($freelancer_id);
                wp_mail($user->user_email, 'NEW PROJECT GRANTED', 'Customer ' . get_user_meta( get_current_user_id(), 'last_name') . ' has granted you to his new project!' . '\t\n\n' . 'Take a look at ' . get_post_permalink($project_id ));
            }
        	
        	$mes['type'] = 'success';
        	$mes['content'] = 'Your project is posted';
        }else{
        	$mes['type'] = 'warning';
        	$mes['content'] = 'Some errors occured';
        }
	}
    require TEMPLATE.'add-project.php';
}