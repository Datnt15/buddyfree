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
}

function free_lancing_page(){
    //add title and content here - last is to call the members plugin.php template
    //add_action( 'bp_template_title', 'free_lancing_page_title' );
    add_action( 'bp_template_content', 'free_lancing_page_content' );
    bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

/*function free_lancing_page_title() {
    //echo 'My Page Title';
}*/

function free_lancing_page_content() { 
    $user = array_filter(explode('/', $_SERVER['REQUEST_URI']), function($value) { return $value !== ''; });
    $user = $user[3];
    $is_author = username_exists($user)==get_current_user_id();
    $skills = get_skill_by_uid(username_exists($user));
    require plugin_dir_path(__FILE__).'../include/freelancing_page.php';
}
//buddypress cover size

function bb_cover_image( $settings = array() ) {
    $settings['width']  = 1200;
    $settings['height'] = 300;
    return $settings;
}

add_filter( 'bp_before_xprofile_cover_image_settings_parse_args', 'bb_cover_image', 10, 1 );
add_filter( 'bp_before_groups_cover_image_settings_parse_args', 'bb_cover_image', 10, 1 );

