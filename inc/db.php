<?php 
function create_table(){
    global $wpdb;
    $sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}skills` ( `skill_id` INT NOT NULL AUTO_INCREMENT , `uid` INT NOT NULL , `name`  VARCHAR(50) NULL , `slug` VARCHAR(50) NULL , `parent_id` INT NULL DEFAULT '0' , `date_create` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`skill_id`)) ENGINE = InnoDB;";
    $wpdb->query($sql);

    $sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}schedule` ( `schedule_id` INT NOT NULL AUTO_INCREMENT , `uid` INT NOT NULL , `time`  VARCHAR(255) NULL, `day`  VARCHAR(20) NOT NULL , `is_hired` VARCHAR(20) NOT NULL DEFAULT '0', `hirer_id` INT NOT NULL DEFAULT '0', `list_hirers` VARCHAR(255) NULL, `date_create` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`schedule_id`)) ENGINE = InnoDB;";
    $wpdb->query($sql);

    $sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}buddy_reviews` ( `review_id` INT NOT NULL AUTO_INCREMENT , `from_id` INT NOT NULL , `to_id` INT NOT NULL , `project_id` INT NOT NULL , `review`  VARCHAR(1000) NULL, `date_create` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`review_id`)) ENGINE = InnoDB;";
    $wpdb->query($sql);
}


function add_skill($data){
    global $wpdb;
    $wpdb->insert("{$wpdb->prefix}skills", $data);
    return $wpdb->insert_id;
}

function add_review($data){
    global $wpdb;
    $wpdb->insert("{$wpdb->prefix}buddy_reviews", $data);
    return $wpdb->insert_id;
}

function remove_skill($where){
    global $wpdb;
    $sql = "DELETE FROM `{$wpdb->prefix}skills` WHERE uid={$where['uid']} AND name=(SELECT * FROM (SELECT name FROM `{$wpdb->prefix}skills` WHERE skill_id={$where['skill_id']}) as skill_name);";
    return $wpdb->query( $sql );
}

function get_skill_by_uid($uid, $limit = 100){
    global $wpdb;
    return $wpdb->get_results( "SELECT * FROM `{$wpdb->prefix}skills` WHERE uid={$uid} GROUP BY name LIMIT {$limit};", ARRAY_A );
}

function get_skill_by_skill_id($skill_id){
    global $wpdb;
    return $wpdb->get_results( "SELECT * FROM `{$wpdb->prefix}skills` WHERE skill_id={$skill_id};", ARRAY_A );
}

function get_list_skill(){
    global $wpdb;
    return $wpdb->get_results( "SELECT * FROM `{$wpdb->prefix}skills` GROUP BY name;", ARRAY_A );
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

function get_uids_by_skills($skill_str){
    $skill_str = explode(',', $skill_str);
    foreach ($skill_str as &$key) {
        $key = "'".trim($key)."'";
    }
    $skill_str = implode(',', $skill_str);
    global $wpdb;
    return $wpdb->get_results("SELECT DISTINCT uid FROM `{$wpdb->prefix}schedule` WHERE uid IN (SELECT DISTINCT uid FROM `{$wpdb->prefix}skills` WHERE name IN ({$skill_str})) AND day >= ".strtotime('now').";", ARRAY_A);
}

function is_reviewed($uid, $project_id){
    global $wpdb;
    return count($wpdb->get_results("SELECT * FROM `{$wpdb->prefix}buddy_reviews` WHERE project_id={$project_id} AND (from_id={$uid} OR to_id={$uid});", ARRAY_A));
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
create_table();