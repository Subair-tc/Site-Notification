<?php
/*
* Create sub page for displaying notification log
*/

add_action( 'admin_menu', 'sn_add_admin_submenu' );
function sn_add_admin_submenu() { 
	add_submenu_page( 
			'system-notification',
			'System Notification Log',
			'Notification Log',
			'manage_options',
			'system-notification-log',
			'sn_display_notification_log'
	);
}



function sn_display_notification_log() {
	echo '<div class="site-notifications">';
	
	?>
		<div class="row">
			<div class="col-sm-12 col-xs-12">
				<h1>Notification Log</h1>
			</div>
		</div>
	<?php
	global $wpdb;
	$table = $wpdb->prefix.'site_notification';
	$notify_logs = $wpdb->get_results( "SELECT * FROM {$table} ORDER BY date_of_notification DESC" );
	if( $notify_logs ) { ?>
		<table border="0" class="form t-style table table-resposive" id="dataTable1" width="100%" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<th>ID</th>
					<th>User ID</th>
					<th>Group ID</th>
					<th>Item ID</th>
					<th>Sec Item ID</th>
					<th>Super parent ID</th>
					<th>Users to notify</th>
					<th>Component Name</th>
					<th>Component Action</th>
					<th>Date</th>
					<th>Status</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th>ID</th>
					<th>User ID</th>
					<th>Group ID</th>
					<th>Item ID</th>
					<th>Sec Item ID</th>
					<th>Super parent ID</th>
					<th>Users to notify</th>
					<th>Component Name</th>
					<th>Component Action</th>
					<th>Date</th>
					<th>Status</th>
				</tr>
			</tfoot>
				<tbody>
				<?php 
					foreach ($notify_logs as $notify_log ) { ?>
						<tr>
							<td><?php echo $notify_log->ID; ?></td>
							<td><?php echo $notify_log->user_id; ?></td>
							<td><?php echo $notify_log->group_id; ?></td>
							<td><?php echo $notify_log->item_id; ?></td>
							<td><?php echo $notify_log->secondary_item_id; ?></td>
							<td><?php echo $notify_log->super_parent_id; ?></td>
							<?php if (  $notify_log->type == 1 ) { ?>
								<td> All users </td> 
							<?php }  else { ?>
								<td><?php echo $notify_log->users_to_notify; ?></td>
							<?php } ?>
							<td><?php echo $notify_log->component_name; ?></td>
							<td><?php echo $notify_log->component_action; ?></td>
							<td><?php echo $notify_log->date_of_notification; ?></td>
							<td><?php echo $notify_log->status; ?></td>
							
						</tr>
				<?php
					}
				?>
				
				
				
			</tbody>
		</table>
	<?php 
	} else{
		echo 'log is Empty!!!';
	}
	
	echo '</div>';
}