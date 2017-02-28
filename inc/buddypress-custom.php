<?php 


add_action('bp_setup_nav', 'bp_profile_menu_freelancing', 301 );

function bp_profile_menu_freelancing() {
    global $bp;
    bp_core_new_nav_item(
        array(
            'name' => 'My Work',
            'slug' => WORKSPACE, 
            'position' => 11, 
            'default_subnav_slug' => 'published', // We add this submenu item below 
            'screen_function' => 'free_lancing_page'
        )
    );

    bp_core_new_nav_item(
        array(
            'name' => 'Reviews',
            'slug' => REVIEWS, 
            'position' => 11, 
            'default_subnav_slug' => 'published', // We add this submenu item below 
            'screen_function' => 'user_review_page'
        )
    );
}

function free_lancing_page(){
    //add title and content here - last is to call the members plugin.php template
    //add_action( 'bp_template_title', 'free_lancing_page_title' );
    add_action( 'bp_template_content', 'free_lancing_page_content' );
    bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

function user_review_page(){
    add_action( 'bp_template_content', 'user_review_page_content' );
    bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

function free_lancing_page_content() { 
    $uid = bp_displayed_user_id();
    
    $is_author = $uid==get_current_user_id();
    $skills = get_skill_by_uid($uid);
    require TEMPLATE.'freelancing_page.php';
}


function user_review_page_content() { 
    $uid = bp_displayed_user_id();
    $reviews = get_all_review_by_user_id($uid);
    require TEMPLATE.'reviews_page.php';
}
//buddypress cover size

function bb_cover_image( $settings = array() ) {
    $settings['width']  = 1200;
    $settings['height'] = 300;
    return $settings;
}

add_filter( 'bp_before_xprofile_cover_image_settings_parse_args', 'bb_cover_image', 10, 1 );
add_filter( 'bp_before_groups_cover_image_settings_parse_args', 'bb_cover_image', 10, 1 );

