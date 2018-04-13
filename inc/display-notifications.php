<?php

/*
*	Display notifications.
*/
function sn_display_user_notofication( $to_display = 'LATEST',$user_id ='', $limit= 20 ){
	if( ! $user_id  ) {
		$user_id = get_current_user_id();
	}
	
	global $wpdb;
	$table1 = $wpdb->prefix.'site_notification';
	$table2 = $wpdb->prefix.'site_notification_status';
	
	$table3 = $wpdb->prefix.'site_notification_template';
	
	if(  is_int( $limit ) ) {
		$limit_text = " LIMIT ".$limit;
	} else {
		$limit_text = "";
	}
	if( $to_display == 'LATEST' ) {
		$user_notifications = $wpdb->get_results("SELECT t1.* FROM $table1 t1 INNER JOIN $table2 t2 ON t1.ID  = t2.notification_id INNER JOIN $table3 t3 ON t1.component_action = t3.component_action WHERE t3.status = 1 AND t2.user_id = $user_id AND t2.is_new = 1 AND t1.status = 1 GROUP BY t1.ID ORDER BY  t1.date_of_notification DESC  ".$limit_text );
	} else {
		$user_notifications = $wpdb->get_results("SELECT t1.* FROM $table1 t1 INNER JOIN $table2 t2 ON t1.ID  = t2.notification_id INNER JOIN $table3 t3 ON t1.component_action = t3.component_action WHERE t3.status = 1 AND t2.user_id = $user_id AND t1.status = 1 GROUP BY t1.ID ORDER BY t1.date_of_notification DESC ".$limit_text );
	}

	if( $user_notifications ) {
		$return['total'] = count($user_notifications);
		$return['content'] = ' <ul class="site_notifications">';

		$return['content_pagination'] .= '<div id="site-notification-item"><ul class="list site_notifications">';
		
		$i = 0;
		foreach ( $user_notifications as $user_notification ) {
			// check the tonotify user is same as notification creator.
			
			if( $user_notification->user_id == $user_id  && $user_notification->type != 1 ) {
				continue;
			}
			
			// Handle reply and mention own post.  & mention and team post.
			 $next = next($user_notifications);
			   if ( false !== $next ) {
					if ( ( $user_notification->component_action == 'reply_courage_post' || $user_notification->component_action == 'reply_rareteam_post' ) && ( $user_notification->item_id == $next->item_id ) && ( $next->component_action =='reply_and_mention_own_post' ) ) {
						sn_delete_site_notification( $user_notification->item_id,$user_notification->group_id, $user_notification->component_name, $user_notification->component_action,'remove_reply_notify', $user_notification->users_to_notify );
						continue;
						
					} elseif( ( $user_notification->component_action == 'reply_and_mention_own_post'  ) && ( $user_notification->item_id == $next->item_id ) && ( $next->component_action =='reply_courage_post' || $next->component_action =='reply_rareteam_post' ) ) {
						sn_delete_site_notification( $next->item_id,$next->group_id, $next->component_name, $next->component_action,'remove_reply_notify', $next->users_to_notify );
						continue;
					}
					
			   }
			 
			$i++;
			
			$message = $wpdb->get_row("SELECT message FROM $table3 WHERE component_name =  '$user_notification->component_name' AND component_action =  '$user_notification->component_action'");
			$message 		= 	$message->message;
			
			//Site URL.
			$site_url 		=	get_bloginfo('url');
			$message 		=	str_replace( '{site_url}', $site_url, $message );
			
			//Primary Link.
			$notify_link	=	$user_notification->primary_link;
			$message 		=	str_replace( '{url}', $notify_link, $message );
			
			//username
			$notify_link	=	$user_notification->primary_link;
			if ( filter_var($notify_link, FILTER_VALIDATE_URL) === false ) {
				$user = get_user_by('login',$notify_link);
				//var_dump($user);
				if( $user ) {
					$user_name 		= '<a href="'.$site_url.'/members/'.$user->user_nicename.'">'.$user->display_name .'</a>';
					
					
				} else {
					$user_name	=	$notify_link;
				}
				$message 		=	str_replace( '{user_name}', $user_name, $message );
			}
			
			
			// Notification Creator.
			$notify_creator = 	$user_notification->user_id;
			$user_data 		= 	get_userdata( $notify_creator );
			if( $user_data ) {
				$creator_name	= 	'<a href="'.$site_url.'/members/'.$user_data->user_nicename.'">'.$user_data->display_name .'</a>';
			} else {
				$creator_name	= 'a member';
			}
			
			$message 		=	str_replace( '{creator_name}',$creator_name , $message );
			
			
			
			// rareTeam name, accept link and reject link,. //rareTeam Link.
			
			$team_id = $user_notification->group_id;
			
			$grop_table = $wpdb->prefix.'bp_groups';
			$team_admin = $wpdb->get_row( $wpdb->prepare( "SELECT name,creator_id,slug FROM {$grop_table} WHERE id=%d",$team_id ) );
			$team_link 		= get_bloginfo('url').'/groups/'.$team_admin->slug;
			
			$message 		=	str_replace( '{rareteam_link}', $team_link, $message );
			
			if( $user_notification->component_action == 'invite_to_rareteam' ) {
				$message 		=	str_replace( '{rareteam}', wp_unslash( $team_admin->name ), $message );
			} else {
				
				$message 		= str_replace( '{rareteam}', '<a href="'.$team_link.'">'.wp_unslash( $team_admin->name ).'</a>', $message );
			}
			
			$rare_team_accept_link = '<a  class="accept_invitation_btn button accept" href="" admin="'.$team_admin->creator_id.'" group="'.$user_notification->item_id.'">accept</a>';
			$message 		=	str_replace( '{rare_team_accept_link}',$rare_team_accept_link , $message );
			
			$rare_team_reject_link = '<a   class=" reject_invitation button reject" href="" admin="'.$team_admin->creator_id.'" group="'.$user_notification->item_id.'">reject</a>';
			$message 		=	str_replace( '{rare_team_reject_link}',$rare_team_reject_link , $message );
		
			$message1 		=	wp_unslash( $message );
			$message 		=	'<li>'.wp_unslash( $message ).'</li>';
			$return['content'] .= $message;
			$return['content_pagination'] .= $message;
			
			
		}
		
		$total_notifications = sn_get_number_latest_notification( $user_id, $to_count = "ALL" );
		
		$notification_link  = bp_loggedin_user_domain().'/notifications/';
		if ( $total_notifications > $limit  ) {
			$return['content'] .= '<a href="'.$notification_link.'" class="viewall-notifications">view all <i class="fa fa-angle-right"></i> </a>';
		}
		
		$return['content']	.= '</ul>';
		
		$return['content_pagination'] .= '</ul>';
		if( $total_notifications> 10 ) {
			$return['content_pagination'] .= '<ul class="pagination"></ul>';
			$return['content_pagination'] .=
			"<script type='text/javascript'>
				$('document').ready( function(){
					var monkeyList = new List('site-notification-item', {
					  valueNames: ['name'],
					  page: 10,
					  pagination: true
					});
				});
			</script>";
		}
		$return['content_pagination'] .= '</div>';	
		$return['content']	.= '<input type="hidden" name="notify_count" id="notify_count" value="'.$return['total'].'">';
		$return['count'] = $i;
	} else {
		$return['total'] = 0;
		$return['content'] = '<ul class="site_notifications no-more-notifications"><li>you don\'t have any notifications yet.</li></ul>';
		$return['content_pagination'] .='
			<div id="message" class="info">
				<p>you don\'t have any notifications yet.</p>
			</div>';
	}
	return $return;
}




/*
* Get notification Count.
*/
function sn_get_number_latest_notification( $user_id = '', $to_count = "LATEST" ) {
	if( ! $user_id  ) {
		$user_id = get_current_user_id();
	}
	global $wpdb;
	$table = $wpdb->prefix.'site_notification_status';
	$table1 = $wpdb->prefix.'site_notification';
	
	if (  $to_count ==  "LATEST" ) {
		$count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$table} t2 INNER JOIN {$table1} t1 ON t1.ID  = t2.notification_id WHERE t2.user_id = $user_id AND t1.user_id != $user_id AND t2.is_new = 1 AND t1.status = 1 ", $user_id ) );
	
	} else {
		$count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$table} t2 INNER JOIN {$table1} t1 ON t1.ID  = t2.notification_id WHERE t2.user_id = $user_id AND t1.user_id != $user_id  AND t1.status = 1 ", $user_id ) );
		
	}
	return $count;
}

/*
* Clear latest notification.
*/
function sn_clear_latest_notification( $user_id = '' ) {
	if( ! $user_id  ) {
		$user_id = get_current_user_id();
	}
	
	global $wpdb;
	$table = $wpdb->prefix.'site_notification_status';
	$where =  array(
		'user_id' => $user_id
	);
	$data = array(
		'is_new'	=> 0
	
	);
	echo $wpdb->update( $table,$data,$where );
	exit;
}
add_action( 'wp_ajax_sn_clear_latest_notification', 'sn_clear_latest_notification' );
