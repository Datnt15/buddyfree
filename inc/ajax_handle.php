<?php 

/**
 * Ajax handle
 */

// adding new skill
add_action( 'wp_ajax_add_skill_ajax', 'add_skill_ajax' );
add_action( 'wp_ajax_nopriv_add_skill_ajax', 'add_skill_ajax' );

function add_skill_ajax(){

    if (isset($_POST['skill_id'])) {
        echo add_user_meta( get_current_user_id(), 'skills_id', $_POST['skill_id']);
    }
    die();
}

// Deleting user's skill
add_action( 'wp_ajax_delete_skill_ajax', 'delete_skill_ajax' );
add_action( 'wp_ajax_nopriv_delete_skill_ajax', 'delete_skill_ajax' );

function delete_skill_ajax(){

    if (isset($_POST['skill_id'])) {
        echo delete_user_meta( get_current_user_id(), 'skills_id', $_POST['skill_id'] );
    }
    die();
}

// adding new skill
add_action( 'wp_ajax_add_schedule_ajax', 'add_schedule_ajax' );
add_action( 'wp_ajax_nopriv_add_schedule_ajax', 'add_schedule_ajax' );

function add_schedule_ajax(){

    if (isset($_POST['time'])) {
        echo save_schedule(
            array(
                'uid'       	=> get_current_user_id(),
                'time'      	=> json_encode($_POST['time']),
                'day'       	=> $_POST['date'],
                'is_hired'  	=> '0',
                'hirer_id'  	=> '0',
                'list_hirers' 	=> ''
            )
        );
    }
    die();
}


// adding new skill
add_action( 'wp_ajax_get_schedule_by_id_ajax', 'get_schedule_by_id_ajax' );
add_action( 'wp_ajax_nopriv_get_schedule_by_id_ajax', 'get_schedule_by_id_ajax' );

function get_schedule_by_id_ajax(){
    echo json_encode( get_schedule_by_uid( get_current_user_id() ) );
    die();
}


// find user by skill 
add_action( 'wp_ajax_get_user_by_skill_ajax', 'get_user_by_skill_ajax' );
add_action( 'wp_ajax_nopriv_get_user_by_skill_ajax', 'get_user_by_skill_ajax' );

function get_user_by_skill_ajax(){
    if (isset($_POST['skills'])) {
        $uids = get_uids_by_skills($_POST['skills']);
        if(count($uids)):?>
            <div class="row">
                <div class="title-left col-xs-6 text-left pull-left">
                    <h4> Results (<?php echo count($uids);?>) </h4>
                </div>

            </div>
            <?php foreach ($uids as $uid): 
                $uid = $uid['uid'];
                $user = get_userdata( $uid );
                $user_url = USER_PAGE.$user->user_login;
                $available_days = search_schedule_by_uid($uid);  
                foreach ($available_days as &$day) {

                    $day = date ( "l" , $day['day'] );
                }
                $day_in_week = ['Monday','Tuesday','Wendsday','Thurdday','Friday','Saturday','Sunday'];
                ?>
                <div class="result">
                    <div class="result-header">
                        <div class="user-avatar col-xs-3 col-sm-2">
                            <a href="<?= $user_url.'/profile';?>">
                                <?php echo get_avatar($uid); ?>
                            </a>
                        </div>
                        <div class="user-title col-xs-9 col-sm-10">
                            <div class="name">
                                <a href="<?= $user_url.'/profile';?>">
                                    <?php echo $user->display_name; ?>
                                </a>
                                <span class="hire-me pull-right">Hire me</span>
                                <!-- <span class="price pull-right hidden-xs">$44/h</span> -->
                                <form method="POST" action="<?= ADD_PROJECT_URL; ?>">
                                    <input type="hidden" name="uid" value="<?= $uid; ?>">
                                </form>
                            </div>
                            <div class="rate">
                                <?php $rate = get_rate($review['from_id']); ?>
                                <span><?php echo ($rate > 0) ? $rate : '0.0';?></span>
                                <?php for ($i=1; $i <= 5; $i++) :?>
                                    <i class="fa fa-star <?php if($i <= $rate) echo 'rated';?>" aria-hidden="true"></i>
                                <?php endfor; ?>
                            </div>
                            <!-- <div class="visible-xs">
                                <span>Fee: <strong>$44/h</strong></span>
                            </div> -->

                            <div class="short-intro">
                                <div class="available-days">
                                    <?php foreach ($day_in_week as $day_name): ?>
                                        <a href="<?= $user_url . '/' . WORKSPACE; ?>" class="available-day <?php if(in_array($day_name, $available_days)) echo 'available'; ?>"><?= $day_name; ?></a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <div class="skills">
                                <?php $skills = get_skill_by_uid($uid, 6); 
                                foreach ($skills as $skill) {?>
                                    <span class="skill-text">
                                        <?php echo $skill['skill_name']; ?>
                                    </span>
                                <?php }?>
                            </div>
                        </div>
                        <!-- <div class="clearfix"></div> -->
                    </div>
                    
                </div>
            <?php endforeach; 
        else: ?>
            <div class="row">
                <div class="title-left col-xs-12 text-left pull-left">
                    <h4> There is no freelancer mark with your skill requirement</h4>
                </div>

            </div>
        <?php endif; 

    }
    die();
}


// find user by skill 
add_action( 'wp_ajax_search_skill_autocomplete', 'search_skill_autocomplete' );
add_action( 'wp_ajax_nopriv_search_skill_autocomplete', 'search_skill_autocomplete' );

function search_skill_autocomplete(){
    $skills = search_skill_by_key($_POST['key']);
    if (count($skills)) {
        foreach ($skills as $skill) {?>
            <span class="skill" data-id="<?php echo $skill['id']; ?>">
                <?php echo $skill['skill_name']; ?>
            </span>
        <?php }
    }
    else{
        echo "<h4>No result match! Try other key</h4>";
    }
    die();
}

