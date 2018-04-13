<?php

/* 
/* Function for inserting the notification
 */

 function sn_insert_site_notification( $user_id,$group_id, $item_id, $users_to_notify, $component_name, $component_action, $primary_link, $secondary_item_id = 0, $super_parent_id = 0,$type = 0 ) {
	global $wpdb;
	
	$logged_user = get_current_user_id();
	if ( isset( $_POST['site_notification'] ) ) {
		$user_id 			= $_POST['user_id'];
		$group_id 			= $_POST['group_id'];
		$item_id 			= $_POST['item_id'];
		$users_to_notify 	= $_POST['users_to_notify'];
		$component_name		= $_POST['component_name'];
		$component_action 	= $_POST['component_action'];
		$primary_link 		= $_POST['primary_link'];
		$secondary_item_id	= $_POST['secondary_item_id'];
		$super_parent_id	= $_POST['super_parent_id'];
	}
	if( $logged_user && ( $logged_user != $user_id || $logged_user == $users_to_notify ) ) {
		return -1;
	}
	
	// For sending all memebrs of  the team.(special case).
	if( $component_action == 'rareteam_new_post'  ) {
		$table = $wpdb->prefix.'bp_groups_members';
		$team_memebrs = $wpdb->get_results( $wpdb->prepare( "SELECT GROUP_CONCAT( user_id ) AS memebrs  FROM {$table} where group_id = %d AND is_confirmed = 1",$secondary_item_id ) );
		
		$users_to_notify = $team_memebrs[0]->memebrs;
	}
	$table = $wpdb->prefix.'site_notification';
	$data = array (
		'user_id' 				=> trim( $user_id ),
		'group_id' 				=> trim( $group_id ),
		'item_id' 				=> trim( $item_id ),
		'secondary_item_id'		=> trim( $secondary_item_id ),
		'super_parent_id'		=> trim( $super_parent_id ),
		'users_to_notify' 		=> trim( $users_to_notify ),
		'component_name' 		=> trim( $component_name ),
		'component_action' 		=> trim( $component_action ),
		'primary_link' 			=> trim( $primary_link ),
		'date_of_notification' 	=> current_time( 'Y-m-d H:i:s' ),
		'type' 					=> trim( $type ),
	);
	$return = $wpdb->insert( $table, $data );
 }
 add_action( 'wp_ajax_sn_insert_site_notification', 'sn_insert_site_notification' );
 
 
 function sn_notification_on_mention( $content, $user_id, $group_id, $activity_id ) {
	$pattern = '/[@]([\p{L}_0-9a-zA-Z-]+)/iu';
	global $wpdb,$bp;
	$rare_group_id  = $bp->groups->current_group->id;
	preg_match_all( $pattern, $content, $users );
	if ( $users ) {
		if ( !$users =  $users[1] ) {
			return $content;
		}
		foreach( (array)$users as $user ) {
			
			$table = $wpdb->prefix.'users';
			$user_data = $wpdb->get_row( $wpdb->prepare( "SELECT ID from {$table} WHERE suggestion_name = '%s' AND user_status=0", $user ) );
			
			$primary_link		= bp_activity_get_permalink($activity_id);
			
			if( $rare_group_id == 1 ) {
				sn_insert_site_notification( bp_loggedin_user_id(),$rare_group_id, $activity_id, $user_data->ID, 'courage_post', 'courage_post_mention', $primary_link );
			} /*else {
				sn_insert_site_notification( bp_loggedin_user_id(),$rare_group_id, $activity_id, $user_data->ID, 'rareteam_post', 'rareteam_post_mention', $primary_link );
			}*/
			
			
		}
		
	}
	return $content;
}
add_filter( 'bp_groups_posted_update', 'sn_notification_on_mention', 10, 4  );
//add_filter( 'bp_edit_activity_action_edit_content', 'sn_notification_on_mention' );


//add_filter( 'bp_activity_comment_content', 'sn_notification_on_mention_on_reply', 10, 1  );
function sn_notification_on_mention_on_reply( $content, $activity_id ) {
	$pattern = '/[@]([\p{L}_0-9a-zA-Z-]+)/iu';
	global $wpdb,$bp;
	$rare_group_id  = $bp->groups->current_group->id;
	$comment_id = bp_get_activity_comment_id();
	
	preg_match_all( $pattern, $content, $users );
	if ( $users ) {
		if ( !$users =  $users[1] ) {
			return $content;
		}
		$uid = '';
		foreach( (array)$users as $user ) {
			
			$table = $wpdb->prefix.'users';
			$user_data = $wpdb->get_row( $wpdb->prepare( "SELECT ID from {$table} WHERE suggestion_name = '%s' AND user_status=0", $user ) );
			
			$primary_link		= bp_activity_get_permalink($activity_id);
			
			if( $rare_group_id == 1 && $uid != $user_data->ID  ) {
				$content = sn_insert_site_notification( bp_loggedin_user_id(), $activity_id, $user_data->ID, 'courage_reply','reply_and_mention_own_post', $primary_link );
			}
			$uid = $user_data->ID;
		}
		
	}
	return $content;
}