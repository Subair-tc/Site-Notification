<?php
/*
* Create notification plugin admin area.
*/

 add_action( 'admin_menu', 'update_notification_add_admin_menu' );
 function update_notification_add_admin_menu() {
	add_menu_page( 'System Notification',
		'Site Notification',
		'manage_options',
		'system-notification',
		'sn_notification_settings',
		SITE_NOTIFICATIONS_PLUGIN_URL.'images/notification-green-bg.png',
		81
	);
	add_submenu_page('system-notification',
		'System Notification Settings',
		'Notification Settings',
		'manage_options',
		'system-notification' 
	);
	
 }

 
 function sn_notification_settings_comming_soon(){
	
	echo 'Coming soon!!';
	 
 }
 function sn_notification_settings(){
	?>
	<div class="site-notifications">
		<div class="row">
			<div class="col-sm-6 col-xs-12">
				<h1> Site Notifications</h1>
			</div>
		</div>
		
		
		<div class="about-notification">
			<p>
				Description about functionalities
			
			</p>
		
		</div>
		<?php sn_listing_notification_on_off_table( ); ?>
			
			
	</div>
	
	<?php
 }
 
 
 function sn_listing_notification_on_off_table() {
	echo '<h2>Enable/ Desable Notifications</h2>';
	global $wpdb;
		$table = $wpdb->prefix.'site_notification_template';
		$templates = $wpdb->get_results("SELECT ID,notification_name,status FROM {$table} ");
		if ( $templates ) { ?>
			<table border="0" class="form t-style table table-resposive" id="notify_settings" width="100%" cellpadding="0" cellspacing="0">
			
				<thead>
				<tr>
					<th>ID</th>
					<th>Notification</th>
					<th>Enable/Disable</th>
				</tr>
				</thead>
				<tfoot>
				<tr>
					<th>ID</th>
					<th>Notification</th>
					<th>Enable/Disable</th>
				</tr>
				</tfoot>
				<tbody>
					<?php /*<tr class="info">
						<td>0</td>
						<td>Push notification</td>
						<td>
							<input class="notification-status" checked data-toggle="toggle" type="checkbox" notifty-id="0"  data-on="Enabled" data-off="Disabled" data-onstyle="success" data-offstyle="danger">
						</td>
					</tr>*/ 
					?>
				<?php 
					foreach ($templates as $template ) { ?>
						<tr>
							<td><?php echo $template->ID; ?></td>
							<td><?php echo $template->notification_name; ?></td>
							<td>
								<?php if( $template->status ) {
									$checked = 'checked';
								} else {
									$checked = '';
								}

								echo '<input class="notification-status" '.$checked.'  data-toggle="toggle" type="checkbox" notifty-id="'.$template->ID.'"  data-on="Enabled" data-off="Disabled" data-onstyle="success" data-offstyle="danger">';
									
								?>
							</td>
						</tr>
				<?php
					}
				?>
			</tbody>
		</table>
		
		<?php } 
 }
 
 
 function sn_enable_desable_notification(){
	if ( isset( $_POST['notify_id'] ) && isset( $_POST['status_to'] ) ) {
		$notify_id = $_POST['notify_id'];
		$status_to = $_POST['status_to'];
		if( $status_to != 1 && $status_to != 0 ) {
			$status_to = 1;
		}

		global $wpdb;
		$table = $wpdb->prefix.'site_notification_template';
		$data = array (
			'status'	=>	$status_to
		);
		$where =  array(
			'id'		=>	$notify_id
		);
		return $wpdb->update( $table, $data, $where );
	} else {
		return false;
	}
 }
 add_action( 'wp_ajax_sn_enable_desable_notification', 'sn_enable_desable_notification' );