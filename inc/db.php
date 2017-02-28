<?php 
function create_table(){
    global $wpdb;
    
    $sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}schedule` ( `schedule_id` INT NOT NULL AUTO_INCREMENT , `uid` INT NOT NULL , `time`  VARCHAR(255) NULL, `day`  VARCHAR(20) NOT NULL , `is_hired` VARCHAR(20) NOT NULL DEFAULT '0', `hirer_id` INT NOT NULL DEFAULT '0', `list_hirers` VARCHAR(255) NULL, `date_create` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`schedule_id`)) ENGINE = InnoDB;";
    $wpdb->query($sql);

    $sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}buddy_reviews` ( `review_id` INT NOT NULL AUTO_INCREMENT , `from_id` INT NOT NULL , `to_id` INT NOT NULL , `project_id` INT NOT NULL , `review`  VARCHAR(1000) NULL, `rate` INT NULL, `date_create` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`review_id`)) ENGINE = InnoDB;";

    // $wpdb->query("DROP TABLE `{$wpdb->prefix}buddy_reviews`;");
    $wpdb->query($sql);
}


function add_review($data){
    global $wpdb;
    $wpdb->insert("{$wpdb->prefix}buddy_reviews", $data);
    return $wpdb->insert_id;
}

function search_skill_by_key($key){
    global $wpdb;
    return $wpdb->get_results( "SELECT * FROM `{$wpdb->prefix}skill` WHERE skill_name LIKE '%{$key}%';", ARRAY_A );
}

function get_skill_by_skill_id($id){
    global $wpdb;
    return $wpdb->get_results( "SELECT skill_name FROM `{$wpdb->prefix}skill` WHERE id={$id};", ARRAY_A );
}

function get_skill_by_uid($uid, $limit = 100){
    global $wpdb;
    $skills_id = get_user_meta($uid, 'skills_id');
    
    return $wpdb->get_results( "SELECT * FROM `{$wpdb->prefix}skill` WHERE id in (" . implode(',',$skills_id) . ") LIMIT {$limit};", ARRAY_A );
}


function get_list_skill(){
    global $wpdb;
    return $wpdb->get_results( "SELECT * FROM `{$wpdb->prefix}skill` GROUP BY skill_name;", ARRAY_A );
}

function save_schedule($data){
    global $wpdb;
    $schedule = $wpdb->get_var("SELECT * FROM `{$wpdb->prefix}schedule` WHERE day='{$data['day']}'");
    if (!empty($schedule)) {
        return $wpdb->update("{$wpdb->prefix}schedule", $data, array('day' => $data['day']));
    }
    
    return $wpdb->insert("{$wpdb->prefix}schedule", $data);
}


function remove_schedule($where){
    global $wpdb;
    return $wpdb->delete( $wpdb->prefix.'schedule', $where );
}


function get_schedule_by_uid($uid){
    global $wpdb;
    return $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}schedule` WHERE uid={$uid};", ARRAY_A);
}

function search_schedule_by_uid($uid){
    global $wpdb;
    return $wpdb->get_results("SELECT day FROM `{$wpdb->prefix}schedule` WHERE uid={$uid} AND day >= ".strtotime('now')." LIMIT 7;", ARRAY_A);
}

function get_uids_by_skills($skill_id){
    global $wpdb;
    return $wpdb->get_results("SELECT DISTINCT uid FROM `{$wpdb->prefix}schedule` WHERE uid IN (SELECT DISTINCT user_id FROM `{$wpdb->prefix}usermeta` WHERE meta_key='skills_id' AND meta_value IN (SELECT id FROM `{$wpdb->prefix}skill` WHERE skill_name LIKE '%{$skill_id}%')) AND day >= ".strtotime('now').";", ARRAY_A);
}

function is_reviewed($uid, $project_id){
    global $wpdb;
    return count($wpdb->get_results("SELECT * FROM `{$wpdb->prefix}buddy_reviews` WHERE project_id={$project_id} AND from_id={$uid};", ARRAY_A));
}

function get_all_freelancers(){
    return get_users(array('role' => 'freelancer'));
}

function can_review($uid, $project_id){
    if (is_reviewed($uid, $project_id)) {
        return false;
    }
    global $wpdb;
    $freelancer_id = get_post_meta( $project_id, 'freelancer_id', true );
    $author_id = $wpdb->get_var("SELECT post_author FROM `{$wpdb->posts}` WHERE ID={$project_id};");
    $status = get_post_meta( $project_id, 'project_status', true );

    if ($status == 'done' || $status == 'once_review') {
        if (get_current_user_id() == $author_id || get_current_user_id() == $freelancer_id){
            return true;
        }
    }
    return false;

}

function get_all_review_by_project_id($project_id){
    global $wpdb;
    return $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}buddy_reviews` WHERE project_id={$project_id} ;", ARRAY_A);
}


function get_all_review_by_user_id($to_id){
    global $wpdb;
    return $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}buddy_reviews` WHERE to_id={$to_id} ;", ARRAY_A);
}

function get_rate($to_id){
    global $wpdb;
    return $wpdb->get_var("SELECT ROUND(AVG(rate), 1) FROM `{$wpdb->prefix}buddy_reviews` WHERE to_id={$to_id} ;");
}

create_table();