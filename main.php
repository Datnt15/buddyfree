<?php
/*
Plugin Name: Buddy Free
*/

//register bootstrap framework

/**
 * Register style sheet.
 */

include 'inc/config.php';

// Db functionp
include 'inc/db.php';
 
// Init plugin
include 'inc/init_plugin.php';


/*Add tab on member page of buddypress*/
include 'inc/buddypress-custom.php';

// Ajax handle
include 'inc/ajax_handle.php';


// Shortcode
include 'inc/short_code.php';

// Admin Skills Managerment

include 'inc/admin_manage_skill.php';