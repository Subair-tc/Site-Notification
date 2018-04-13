<?php

/*
 * Delete notification
 */
function sn_delete_site_notification( $item_id,$group_id, $component_name, $component_action,$delete_action, $user_id='' ) {
	
	if ( isset( $_POST['delete_action'] ) ) {
		$item_id 			= $_POST['item_id'];
		$group_id 			= $_POST['group_id'];
		$component_name		= $_POST['component_name'];
		$component_action 	= $_POST['component_action'];
		$delete_action 		= $_POST['delete_action'];
		$user_id	 		= $_POST['user_id'];
	}
	global $wpdb;
	$user = get_current_user_id();
	if ( ! $item_id || ! $component_action || ! $user ) {
		return -1;
	}
	
	$where = array();
	if ( $delete_action  == 'post_deleted' ) {
		$flag = 1;
		if ( ! is_super_admin( $user ) ) { 
			$table = $wpdb->prefix.'bp_activity';
			$post_owner = $wpdb->get_row( $wpdb->prepare( "SELECT user_id FROM {$table} WHERE id = %d ", $item_id ) );
			if( $post_owner->user_id ) {
				$flag = 0;
			}
		}
		if( $flag ) {
			
			// delete all comments to this post.
			$table = $wpdb->prefix.'site_notification';
			$wpdb->query( $wpdb->prepare( "UPDATE {$table} SET `status` = 0 WHERE `item_id` = %d OR `secondary_item_id` = %d OR `super_parent_id` = %d   ",$item_id,$item_id ,$item_id ) );

			// delete all post actions.
			$where = array(
				'item_id'	=> $item_id
			);
		} else {
			return -1;
		}
		
	} elseif ( $delete_action  == 'remove_emotion' ) { //Done
		$where = array(
			'user_id'			=> $user,
			'item_id'			=> $item_id,
			'component_name'	=> $component_name,
			'component_action'	=> $component_action
		);
	} elseif ( $delete_action  == 'remove_invite' ) { // Done
		$where = array(
				'item_id'			=> $item_id,
			);
		if ( $component_action == 'delete_invite' ) {
			
			//removing all the notification notifying him from this group. (need to delete post updates.)
			/*$table = $wpdb->prefix.'site_notification';
			$data = array(
				'group_id'	=> $group_id,
				'user_id'	=> $user
			);
			$return = $wpdb->update( $table,$data,$where );
			*/
			
			
			// delete all the notifications created by him on this group.
			$where = array(
				'users_to_notify'	=> $user_id,
				'group_id'			=> $group_id,
			);
			
		} else { // Accept or reject.
			$where = array(
				'component_name'	=> $component_name,
				'component_action'	=> $component_action,
				'users_to_notify'	=> $user,
				'group_id'			=> $group_id
			);
			
		}
	} elseif ( $delete_action  == 'delete_team' ) {  // Delete team
		
		$flag = 1;
		if ( ! is_super_admin( $user ) ) {
			$table = $wpdb->prefix.'bp_groups';
			$team_owner = $wpdb->get_row( $wpdb->prepare( "SELECT creator_id FROM {$table} WHERE id = %d ", $item_id ) );
			if( $team_owner->creator_id != $user ) {
				$flag = 0;
			}
		}
		if( $flag  ) {
			$where = array(
				'group_id'	=> $group_id
			);	
		} 
		else {
			return -1;
		}
		
		
	} elseif ( $delete_action  == 'left_team' ) {
		
		//removing all the notification notifying him from this group. (need to delete post updates.)
		
		/*$table = $wpdb->prefix.'site_notification';
		$data = array(
			'group_id'	=> $group_id,
			'user_id'	=> $user
		);
		$return = $wpdb->update( $table,$data,$where );
		*/
		
		
		// delete all the notifications created by him on this group.
		$where = array(
			'group_id'			=> $group_id,
			'users_to_notify'	=> $user
		);
		
	} elseif (  $delete_action  == 'remove_reply_notify'  ) {
			if( $group_id == 1 ){
				$where  = array(
					'group_id'			=> $group_id,
					'users_to_notify'	=> $user_id,
					'item_id'			=> $item_id,
					'component_action'	=> 'reply_courage_post'
				);
			} else {
				$where  = array(
					'group_id'			=> $group_id,
					'users_to_notify'	=> $user_id,
					'item_id'			=> $item_id,
					'component_action'	=> 'reply_rareteam_post'
				);
			}
	} elseif(  $delete_action == "delete_message_thread" ) {
		//var_dump($item_id); exit;
		
		// need to remove all the notification for the current user with this thread.
		
		$user_logged_in = get_current_user_id();
		if ( strpos($item_id, ',' ) !== false) {
			$items = explode(",",$item_id );
			$table = $wpdb->prefix.'site_notification';
			
			$wpdb->query( $wpdb->prepare( "UPDATE {$table} SET `status` = 0 WHERE `secondary_item_id` IN ( %s ) AND `users_to_notify` = %d AND `component_name` =  %s", $item_id, $user_logged_in, $component_name ) );
			
			//return $wpdb->last_query;
			return;
		} else{
			$where = array(
				'users_to_notify'	=> $user_logged_in,
				'secondary_item_id'	=> $item_id,
				'component_name'	=> $component_name
			);
		}
		
	}
	
	$table = $wpdb->prefix.'site_notification';
	$data = array(
		'status' => 0
	);
	$return = $wpdb->update( $table,$data,$where );
	//echo $wpdb->last_query;
	//echo $wpdb->last_error;
	//var_dump($return);
	//return 1;
}

add_action( 'wp_ajax_sn_delete_site_notification', 'sn_delete_site_notification' );
 