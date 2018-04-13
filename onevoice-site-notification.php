<?php
/*
Plugin Name: Onevoice Site Notification
Version: 1.2
Description: Plugin for implimenting website notification for onevoice.
Author: Subair T C
Author URI:
Plugin URI:
Text Domain: site-notification
Domain Path: /languages
*/

/*
*	Function to Create new column on activity table.
*/

/* Set constant path to the plugin directory. */
define( 'SITE_NOTIFICATIONS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

/* Set constant path to the plugin directory. */
define( 'NOTIFICATION_PAGE_LINK', get_bloginfo('url').'/my-notifications' );

define( 'SITE_NOTIFICATIONS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
/* Set the constant path to the plugin's includes directory. */
define( 'SITE_NOTIFICATIONS_INC', SITE_NOTIFICATIONS_PLUGIN_PATH . trailingslashit( 'inc' ), true );


/* including plugin settings pages*/
include_once( SITE_NOTIFICATIONS_INC . 'plugin-settings.php' );

register_activation_hook( __FILE__, 'site_notification_activate' );

add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'site_notifications_add_action_links' );

function site_notifications_add_action_links ( $links ) {
	$mylinks = array(
	'<a href="' . admin_url( 'admin.php?page=system-notification' ) . '">Settings</a>',
	);
	return array_merge( $links, $mylinks );
}


/*
*	Function to Enqueue required scripts and Style.
*/
function add_site_notification_script() {
	wp_register_script( 'site-notification', plugins_url( '/js/site-notification.js', __FILE__ ), true );
	wp_enqueue_script( 'site-notification' );
	
	wp_register_script( 'list.min', plugins_url( 'js/list.min.js', __FILE__ ), true );
	wp_enqueue_script( 'list.min' );
	
	
	wp_localize_script('site-notification', 'Ajax', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
	));
	wp_register_style( 'site-notification', plugins_url( '/css/site-notification.css', __FILE__ ) );
	wp_enqueue_style( 'site-notification' );
}

add_action( 'wp_enqueue_scripts', 'add_site_notification_script' );


/*
	Adding admin styles and scripts
*/
function add_site_notification_admin_style() {
	
	wp_register_style( 'site-noti-css', plugins_url( '/css/custom.css', __FILE__ ) );
	wp_enqueue_style( 'site-noti-css' );
	wp_register_style( 'sn_bootsrtrap', plugins_url( '/css/bootstrap.min.css', __FILE__ ) );
	wp_enqueue_style( 'sn_bootsrtrap' );
	wp_register_style( 'sn_dataTable', plugins_url( '/css/dataTables.bootstrap.min.css', __FILE__ ) );
	wp_enqueue_style( 'sn_dataTable' );
	wp_register_style( 'sn_bootstrap-toggle', plugins_url( '/css/bootstrap-toggle.min.css', __FILE__ ) );
	wp_enqueue_style( 'sn_bootstrap-toggle' );
	
	//wp_register_script( 'jquery-min-js', plugins_url( '/js/jquery.min.js', __FILE__ ), true );
	//wp_enqueue_script( 'jquery-min-js' );
	wp_register_script( 'site-noti-js', plugins_url( '/js/custom.js', __FILE__ ), true );
	wp_enqueue_script( 'site-noti-js' );
	wp_register_script( 'sn_bootstrap', plugins_url( '/js/bootstrap.min.js', __FILE__ ), true );
	wp_enqueue_script( 'sn_bootstrap' );
	wp_register_script( 'sn_dataTable', plugins_url( '/js/jquery.dataTables.js', __FILE__ ), true );
	wp_enqueue_script( 'sn_dataTable' );
	wp_register_script( 'sn_dataTable_bootstrap', plugins_url( '/js/dataTables.bootstrap.min.js', __FILE__ ), true );
	wp_enqueue_script( 'sn_dataTable_bootstrap' );
	wp_register_script( 'sn_bootstrap-toggle', plugins_url( '/js/bootstrap-toggle.min.js', __FILE__ ), true );
	wp_enqueue_script( 'sn_bootstrap-toggle' );
	
	wp_localize_script('custom-js', 'Ajax', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
	));
}

add_action( 'admin_enqueue_scripts', 'add_site_notification_admin_style' );

/* including insert functionalities*/
include_once( SITE_NOTIFICATIONS_INC . 'insert-notifications.php' );


/* including delete functionalities*/
include_once( SITE_NOTIFICATIONS_INC . 'delete-notifications.php' );

 /* including notification settings*/
include_once( SITE_NOTIFICATIONS_INC . 'notification-settings.php' );

 /* including template section*/
include_once( SITE_NOTIFICATIONS_INC . 'notification-template.php' );

 /* including display-notifications*/
include_once( SITE_NOTIFICATIONS_INC . 'display-notifications.php' );

 /* including custom notification*/
include_once( SITE_NOTIFICATIONS_INC . 'custom-notification.php' );

 /* including plugin notification log*/
include_once( SITE_NOTIFICATIONS_INC . 'notification-log.php' );


 /* including plugin notification log*/
include_once( SITE_NOTIFICATIONS_INC . 'notification-help.php' );









