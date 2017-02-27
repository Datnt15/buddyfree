<?php 
 

add_action( 'init', 'buddyfree_load_textdomain' );
/**
 * Load plugin textdomain.
 *
 * @since 1.0.0
 */
function buddyfree_load_textdomain() {
    load_plugin_textdomain( 'buddyfree', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
    ob_start();
}

function register_plugin_styles() {
	wp_register_style( 'bootstrap', plugins_url( PLUGIN_FOLDER.'/assets/vendor/bootstrap-3.3.7-dist/css/bootstrap.min.css' ) );
	wp_register_style( 'bootstrap-theme', plugins_url( PLUGIN_FOLDER.'/assets/vendor/bootstrap-3.3.7-dist/css/bootstrap-theme.min.css' ) );
    wp_register_style( 'main-css', plugins_url( PLUGIN_FOLDER.'/assets/css/main.css' ) );
    wp_register_style( 'prism-css', plugins_url( PLUGIN_FOLDER.'/assets/css/prism.css' ) );
	wp_register_style( 'schedule-css', plugins_url( PLUGIN_FOLDER.'/assets/css/schedule.css' ) );
	

	wp_enqueue_style( 'bootstrap' );
	//wp_enqueue_style( 'bootstrap-theme' );
    wp_enqueue_style( 'main-css' );
    wp_enqueue_style( 'prism-css' );
	wp_enqueue_style( 'schedule-css' );

}

add_action( 'wp_enqueue_scripts', 'register_plugin_styles' );

function register_js_script() {
    //get some external script that is needed for this script
    wp_enqueue_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js'); 
    
    wp_register_script('bootstrap-js', plugins_url(PLUGIN_FOLDER.'/assets/vendor/bootstrap-3.3.7-dist/js/bootstrap.min.js'));

    wp_register_script('jquery2-js', plugins_url(PLUGIN_FOLDER.'/assets/js/jquery2.js'));  
    wp_register_script('prism-js', plugins_url(PLUGIN_FOLDER.'/assets/js/prism.js'));    
    wp_register_script('underscore-js', plugins_url(PLUGIN_FOLDER.'/assets/js/underscore.js'));  
    wp_register_script('moment-js', plugins_url(PLUGIN_FOLDER.'/assets/js/moment.js'));  
    wp_register_script('clndr-js', plugins_url(PLUGIN_FOLDER.'/assets/js/clndr.js'));    
    wp_register_script('site-js', plugins_url(PLUGIN_FOLDER.'/assets/js/site.js')); 
    wp_register_script('main-js', plugins_url(PLUGIN_FOLDER.'/assets/js/main.js'));  
    

    wp_enqueue_script('jquery-ui-autocomplete', '');
    wp_enqueue_script('main-js');
    wp_enqueue_script('bootstrap-js');
    wp_enqueue_script('jquery2-js');
    wp_enqueue_script('prism-js');    
    wp_enqueue_script('underscore-js');    
    wp_enqueue_script('moment-js');    
    wp_enqueue_script('clndr-js');    
    wp_enqueue_script('site-js');    
     
}

add_action("wp_enqueue_scripts", "register_js_script");


// Add find freelancer page
add_action( 'after_setup_theme', 'add_search_page' );

function add_search_page(){
    if ( ! function_exists( 'post_exists' ) ) {
        require_once( ABSPATH . 'wp-admin/includes/post.php' );
    }

    
    if (!post_exists('Find Freelancer')) {
        wp_insert_post( array(
            'post_title'    =>  'Find Freelancer' ,
            'post_type'     => 'page',
            'post_content'  => '[find-freelancers]',
            'post_status'   => 'publish',
            'post_author'   => 1
        ) );
    }


    if (!post_exists('Hire')) {
        wp_insert_post( array(
            'post_title'    =>  'Hire',
            'post_type'     => 'page',
            'post_content'  => '[add-project]',
            'post_status'   => 'publish',
            'post_author'   => 1
        ) );
    }

    if (!post_exists('Registration')) {
        wp_insert_post( array(
            'post_title'    =>  'Registration',
            'post_type'     => 'page',
            'post_content'  => '[registration]',
            'post_status'   => 'publish',
            'post_author'   => 1
        ) );
    }
}

// Add user roles for freelancer and employee.
function add_roles_on_plugin_activation() {
   add_role( 'freelancer', 'Freelancer', array( 'read' => true, 'level_0' => true ) );
   add_role( 'employee', 'Employee', array( 'read' => true, 'level_0' => true ) );
}
register_activation_hook( __FILE__, 'add_roles_on_plugin_activation' );


// Add project post type

/*
* Creating a function to create our CPT
*/

function buddyfree_add_project_post_type() {

// Set UI labels for Custom Post Type
    $labels = array(
        'name'                => _x( 'Projects', 'Post Type General Name', 'buddyfree' ),
        'singular_name'       => _x( 'Project', 'Post Type Singular Name', 'buddyfree' ),
        'menu_name'           => __( 'Projects', 'buddyfree' ),
        // 'parent_item_colon'   => __( 'Parent Project', 'buddyfree' ),
        'all_items'           => __( 'All Projects', 'buddyfree' ),
        'view_item'           => __( 'View Project', 'buddyfree' ),
        'add_new_item'        => __( 'Add New Project', 'buddyfree' ),
        'add_new'             => __( 'Add New', 'buddyfree' ),
        'edit_item'           => __( 'Edit Project', 'buddyfree' ),
        'update_item'         => __( 'Update Project', 'buddyfree' ),
        'search_items'        => __( 'Search Project', 'buddyfree' ),
        'not_found'           => __( 'Not Found', 'buddyfree' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'buddyfree' ),
    );
    
// Set other options for Custom Post Type
    
    $args = array(
        'label'               => __( 'project', 'buddyfree' ),
        'description'         => __( 'Project news and reviews', 'buddyfree' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
        'hierarchical'        => true,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-hammer',
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'page',
        'taxonomies'          => array( 'category', 'topics', 'post_tag' ),
    );
    
    // Registering your Custom Post Type
    register_post_type( 'projects', $args );

}

/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/

add_action( 'init', 'buddyfree_add_project_post_type', 0 );


/* Filter the single_template with our custom function*/
add_filter('single_template', 'my_custom_template');

function my_custom_template($single) {
    global $wp_query, $post;

    /* Checks for single template by post type */
    if ($post->post_type == "projects"){
        if(file_exists(TEMPLATE . '/single-project.php'))
            return TEMPLATE . '/single-project.php';
    }
    return $single;
}