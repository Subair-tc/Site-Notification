<?php




add_action( 'admin_menu', 'sn_add_admin_submenu_custom_notification' );
function sn_add_admin_submenu_custom_notification() { 
	
	add_submenu_page( 
		'system-notification',
		'Site Announcement',
		'Site Announcement',
		'manage_options',
		'site-announcement',
		'sn_custom_notification'
	);
}





function sn_custom_notification(){
	?>
	<div class="custom_notification_section_success" style="display:none"></div>
	<div class="site-notifications">
		<div class="row">
			<div class="col-sm-12 col-xs-12">
				<h1> Custom Notifications</h1>
			</div>
			<div class="col-sm-12 col-xs-12">
				<button class="btn btn-info add_custom_notification" data-toggle="modal" data-target="#add_custom_notification">Add New</button>
			</div>
		</div>
	<?php
	include_once( SITE_NOTIFICATIONS_INC . 'custom-notification-modal.php' );
	
	global $wpdb;
	$table = $wpdb->prefix.'site_notification';
	$custom_notifications = $wpdb->get_results( "SELECT id, user_id, component_name, component_action, primary_link, date_of_notification, status FROM {$table} WHERE type = 1  ORDER BY date_of_notification DESC" );
	if( $custom_notifications ) { ?>
		<table border="0" class="form t-style table table-resposive" id="dataTable1" width="100%" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<th>ID</th>
					<th>User ID</th>
					<th>Component Name</th>
					<th>Component Action</th>
					<th>Primary Link</th>
					<th>Date</th>
					<th>Status</th>
					<th>Edit / Delete</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th>ID</th>
					<th>User ID</th>
					<th>Component Name</th>
					<th>Component Action</th>
					<th>Primary Link</th>
					<th>Date</th>
					<th>Status</th>
					<th>Edit / Delete</th>
				</tr>
			</tfoot>
				<tbody>
				<?php 
					foreach ( $custom_notifications as $custom_notification ) { ?>
						<tr>
							<td><?php echo $custom_notification->id; ?></td>
							<td><?php echo $custom_notification->user_id; ?></td>
							<td><?php echo $custom_notification->component_name; ?></td>
							<td><?php echo $custom_notification->component_action; ?></td>
							<td><?php echo $custom_notification->primary_link; ?></td>
							<td><?php echo $custom_notification->date_of_notification; ?></td>
							<td><?php echo $custom_notification->status; ?></td>
							<td>
								<?php if ( $custom_notification->status ) { ?>
									<a data-item_id ="<?php echo $custom_notification->id; ?>" href="#" class="edit-notification" data-toggle="modal" data-target="#add_custom_notification" data-component_name="<?php echo $custom_notification->component_name; ?>" data-component_action="<?php echo $custom_notification->component_action; ?>" data-primary_link="<?php echo $custom_notification->primary_link; ?>">
										Edit
									</a>
									/
									<a item_id ="<?php echo $custom_notification->id; ?>"  href="#"  class="delete-notification">
										Delete
									</a>
								<?php } else { ?>
								<a item_id ="<?php echo $custom_notification->id; ?>" href="#" class="activate-notification">
										Activate
									</a>
								<?php } ?>
							</td>
							
						</tr>
				<?php
					}
				?>
				
				
				
			</tbody>
		</table>
	<?php 
	} else{
		echo 'Currently there is no custom notification added.';
	}
	
	echo '</div>';
}


function sn_insert_custom_notification( $component_name, $component_action, $primary_url = '' ) {
	global $wpdb;
	$table 		= $wpdb->prefix.'users';
	$user_id 	= get_current_user_id();
	$all_users	= $wpdb->get_row( "SELECT GROUP_CONCAT( id ) AS users  FROM {$table} WHERE 1" );
	if( isset( $_POST['component_name'] ) ) {
		$component_name		= $_POST['component_name'];
	}
	
	if( isset( $_POST['component_action'] ) ) {
		$component_action 	= $_POST['component_action'];
	}
	if( isset( $_POST['primary_link'] ) ) {
		$primary_link 	= $_POST['primary_link'];
	}
	if( $component_name	 =='' || $component_action =='' ){
		return false;
	}
	if ( isset( $_POST['sn_action'] ) ) {
		$sn_action = $_POST['sn_action'];
	}
	if( $sn_action == 'new_notification' ){
		
		$group_id 				= 0;
		$item_id 				= 0;
		$users_to_notify 		= $all_users->users;
		$secondary_item_id 		= 0;
		$super_parent_id 		= 0;
		$type 					= 1;
		
		return sn_insert_site_notification( $user_id,$group_id, $item_id, $users_to_notify, $component_name, $component_action, $primary_link, $secondary_item_id, $super_parent_id,$type );
	} elseif( $sn_action == 'edit_notification' ) {
		if ( isset( $_POST['notification_id'] ) ) {
			$notification_id = $_POST['notification_id'];
		}
		$table = $wpdb->prefix.'site_notification';
		
		$where = array(
			'id'	=> $notification_id
		);
		$data = array(
			'component_name'	=> $component_name,
			'component_action'	=> $component_action,
			'primary_link'		=> $primary_link
		);
		
		return $wpdb->update( $table, $data, $where );
		
	} else {
		return false;
	}
	die();
}
add_action( 'wp_ajax_sn_insert_custom_notification', 'sn_insert_custom_notification' );


function sn_get_component_action(){
	//echo 'dude';  exit;
	if ( isset( $_POST['component_name'] ) ) {
		$component_name = $_POST['component_name'];
		
		if( $component_name == '' ) {
			echo -1;
			die();
		}
		global $wpdb;
		$table = $wpdb->prefix.'site_notification_template';
		$templates = $wpdb->get_results( "SELECT DISTINCT component_action  FROM {$table} WHERE `component_name` = '$component_name'"  );
		$component_action_select ='<option value="">Choose Component Action</option>';
		if( $templates ) {
			foreach ( $templates as $template ) {
				$component_action_select		.= '<option value="'.$template->component_action .'">'. $template->component_action .'</option>';
			}
			echo $component_action_select;
		} else {
			echo -1;
			die();
		}
	} else {
		echo -1;
	}
	exit;
}
add_action( 'wp_ajax_sn_get_component_action', 'sn_get_component_action' );

function sn_delete_activate_custom_notification( $notification_id,$status_to ){
	if ( isset( $_POST['item_id'] ) ) {
		 $notification_id 	= $_POST['item_id'];
		 $status_to			= $_POST['status_to'];
	}
	if( ! $notification_id ) {
		return false;
	}
	
	if( current_user_can('editor') || current_user_can('administrator') ) {
		
		global $wpdb;
		$table = $wpdb->prefix.'site_notification';
		$data  = array(
			'status'	=> $status_to
		);
		$where = array(
			'id'		=> $notification_id
		);
		$return = $wpdb->update( $table, $data, $where );
		echo $wpdb->last_query;
		
	} else {
		return false;
	}
	die();
}

add_action( 'wp_ajax_sn_delete_activate_custom_notification', 'sn_delete_activate_custom_notification' );