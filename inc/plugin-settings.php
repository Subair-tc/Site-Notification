<?php

function site_notification_activate() {
	global $wpdb;
	
	// Create notification Table
	$table = $wpdb->prefix.'site_notification';
	$query = "CREATE TABLE IF NOT EXISTS {$table} ( `ID` BIGINT(20) NOT NULL AUTO_INCREMENT , `user_id` BIGINT(20) NOT NULL , `group_id` BIGINT(20), `item_id` BIGINT(20) NOT NULL ,`secondary_item_id` BIGINT(20) NOT NULL DEFAULT 0 , `super_parent_id` BIGINT(20) NOT NULL DEFAULT 0, `users_to_notify` LONGTEXT NOT NULL , `component_name` VARCHAR(50) NOT NULL , `component_action` VARCHAR(50) NOT NULL ,`primary_link` VARCHAR(300) NOT NULL, `date_of_notification` DATETIME NOT NULL , `status` INT(2) NOT NULL DEFAULT 1 , `type` INT(2) NOT NULL DEFAULT '0' COMMENT '0- user 1- custom', PRIMARY KEY (`ID`) )";
	$wpdb->query( $query );
	
	// Create user reference table notification.
	$table = $wpdb->prefix.'site_notification_status';
	$query = "CREATE TABLE IF NOT EXISTS {$table} ( `ID` BIGINT(20) NOT NULL AUTO_INCREMENT , `user_id` BIGINT(20) NOT NULL , `notification_id` BIGINT(20) NOT NULL , `is_new` INT(2) NOT NULL  , `status` INT(2) NOT NULL DEFAULT 1 , PRIMARY KEY (`ID`))";
	$wpdb->query( $query );
	
	// Create table for storing notification message.
	$table = $wpdb->prefix.'site_notification_template';
	$query = "CREATE TABLE  IF NOT EXISTS {$table} ( `ID` BIGINT(20) NOT NULL AUTO_INCREMENT ,  `notification_name` LONGTEXT NOT NULL , `component_name` VARCHAR(300) NOT NULL , `component_action` VARCHAR(300) NOT NULL , `message` VARCHAR(1000) NOT NULL , PRIMARY KEY (`ID`))";
	$wpdb->query( $query );
	
	
	// trigger
	$trigger = "DELIMITER $$
DROP TRIGGER /*!50032 IF EXISTS */ `notification_insert`$$
CREATE TRIGGER `notification_insert` AFTER INSERT ON `wp_site_notification`
    FOR EACH ROW BEGIN
	CALL notification_status(NEW.`ID`);
    END;
$$

DELIMITER ;";
//mysqli_multi_query($wpdb->dbh,$trigger);
$wpdb->query( $trigger );
/* procedure */

$procedure = "DELIMITER $$
DROP PROCEDURE IF EXISTS `notification_status`$$
CREATE  PROCEDURE `notification_status`(IN _id INT)
BEGIN DECLARE users_comma_seperated VARCHAR(10000);
DECLARE strlength VARCHAR(100);
DECLARE user_id VARCHAR(100);
DECLARE new_string VARCHAR(10000);
DECLARE newvar_coma VARCHAR(100);
DECLARE user_id_len INT(6);
SELECT users_to_notify INTO users_comma_seperated FROM wp_site_notification WHERE ID = _id;
SELECT CHAR_LENGTH( users_comma_seperated ) INTO strlength;
WHILE strlength > 0 DO SELECT SUBSTRING_INDEX(users_comma_seperated, ',', 1)  INTO user_id;
SELECT CHAR_LENGTH( user_id ) INTO user_id_len;
IF user_id_len = strlength THEN SET new_string='';
ELSE SELECT SUM( user_id_len + 2 ) INTO user_id_len;
SELECT SUBSTRING( users_comma_seperated, user_id_len ) INTO new_string;
END IF;
INSERT INTO `wp_site_notification_status` (`user_id`, `notification_id`, `is_new`,`status`) VALUES ( user_id, _id, '1','1');
SELECT new_string INTO users_comma_seperated;
SELECT CHAR_LENGTH( users_comma_seperated ) INTO strlength;
END WHILE;
END$$
DELIMITER;";
//mysqli_multi_query($wpdb->dbh,$procedure);
$prr = $wpdb->query( $procedure );
//echo $wpdb->last_error;


// Creating notification single page.


	$the_page_title = 'My Notifications';
    $the_page_name = 'my-notifications';

    // the menu entry...
    delete_option("my_plugin_page_title");
    add_option("my_plugin_page_title", $the_page_title, '', 'yes');
    // the slug...
    delete_option("my_plugin_page_name");
    add_option("my_plugin_page_name", $the_page_name, '', 'yes');
    // the id...
    delete_option("my_plugin_page_id");
    add_option("my_plugin_page_id", '0', '', 'yes');

    $the_page = get_page_by_title( $the_page_title );

    if ( ! $the_page ) {

        // Create post object
        $_p = array();
        $_p['post_title'] = $the_page_title;
        $_p['post_content'] = "";
        $_p['post_status'] = 'publish';
        $_p['post_type'] = 'page';
        $_p['comment_status'] = 'closed';
        $_p['ping_status'] = 'closed';
		// the default 'Uncatrgorised'
        $_p['post_category'] = array(1); 

        // Insert the post into the database
        $the_page_id = wp_insert_post( $_p );

    }
    else {
        // the plugin may have been previously active and the page may just be trashed...

        $the_page_id = $the_page->ID;

        //make sure the page is not trashed...
        $the_page->post_status = 'publish';
        $the_page_id = wp_update_post( $the_page );

    }

    delete_option( 'my_plugin_page_id' );
    add_option( 'my_plugin_page_id', $the_page_id );

}



// set my notification template
//Template fallback
add_action("template_redirect", 'template_redirect_my_notifications');

function template_redirect_my_notifications() {
    global $wp;
    $plugindir = dirname( __FILE__ );

   if (isset($wp->query_vars["pagename"]) && $wp->query_vars["pagename"] == 'my-notifications') {
        $templatefilename = 'my-notifications.php';
        if (file_exists(TEMPLATEPATH . '/' . $templatefilename)) {
            $return_template = TEMPLATEPATH . '/site-notifications/' . $templatefilename;
        } else {
            $return_template = $plugindir . '/templates/' . $templatefilename;
        }
        do_theme_redirect_my_notifications($return_template);
    }
}

function do_theme_redirect_my_notifications($url) {
    global $post, $wp_query;
    if (have_posts()) {
        include_once($url);
        die();
    } else {
        $wp_query->is_404 = true;
    }
}

