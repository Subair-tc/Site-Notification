<?php



/*
 /* Creating admin menu for managing notification template.
 */

 
 
 add_action( 'admin_menu', 'sn_add_admin_submenu_notification_templates' );
function sn_add_admin_submenu_notification_templates() { 
	
	add_submenu_page( 
		'system-notification',
		'Notification Templates',
		'Notification Templates',
		'manage_options',
		'notification-templates',
		'sn_update_notification_template'
	);
}

 
function sn_update_notification_template(){
	?>
	<div class="custom_notification_section_success" style="display:none"></div>
	<div class="site-notifications">
		<div class="row">
			<div class="col-sm-6 col-xs-12">
				<h1> Site Notifications Templates</h1>
			</div>
			<div class="col-sm-6 col-xs-12">
				<button class="btn btn-info add-new-notification" data-toggle="modal" data-target="#add_template_block">Add New</button>
			</div>
		</div>
		
		<?php
			global $wpdb;
			$table = $wpdb->prefix.'site_notification_template';
			$templates = $wpdb->get_results("SELECT * FROM {$table} ");
			if ( $templates ) { ?>
			
		<table border="0" class="form t-style table table-resposive" id="dataTable1" width="100%" cellpadding="0" cellspacing="0">
			
				<thead>
				<tr>
					<th>ID</th>
					<th>Notification</th>
					<th>Component Name</th>
					<th>Component Action</th>
					<th>Message</th>
					<th>Edit</th>
					<th>Delete</th>
				</tr>
				</thead>
				<tfoot>
				<tr>
					<th>ID</th>
					<th>Notification</th>
					<th>Component Name</th>
					<th>Component Action</th>
					<th>Message</th>
					<th>Edit</th>
					<th>Delete</th>
				</tr>
				</tfoot>
				<tbody>
				<?php 
					foreach ($templates as $template ) { ?>
						<tr>
							<td><?php echo $template->ID; ?></td>
							<td><?php echo $template->notification_name; ?></td>
							<td><?php echo $template->component_name; ?></td>
							<td><?php echo $template->component_action; ?></td>
							<td><?php echo wp_unslash( htmlspecialchars( $template->message ) ); ?></td>
							<td><a href="" data-toggle="modal" data-target="#edit_template_block" data-id="<?php echo $template->ID; ?>" data-notification_name="<?php echo $template->notification_name; ?>" data-component_name="<?php echo $template->component_name; ?>" data-component_action="<?php echo $template->component_action; ?>"  data-message="<?php echo  wp_unslash( $template->message ); ?>" >Edit</a></td>
							<td> <a class="delete_template" template_id = "<?php echo $template->ID; ?>" >Delete</td>
						</tr>
				<?php
					}
				?>
			</tbody>
		</table>
		
			<?php } ?>
		</div>
	<?php
	/* icluding modals */
	include_once( SITE_NOTIFICATIONS_INC . 'modal-contents.php' );
	
	//print_r( sn_display_user_notofication( 'LATEST', 1, 20 ) );
	//echo sn_get_number_latest_notification();
}

/*
*	Adding new message template.
*/
function sn_add_new_template(){
	
	$items = $_POST['items'];
	$notification_name 	=	$items['0']['value'];
	$component_name 	=	$items['1']['value'];
	$component_action	=	$items['2']['value'];
	$message 			=	$items['3']['value'];

	global $wpdb;
	$table = $wpdb->prefix.'site_notification_template';
	
	$query = "SELECT id FROM {$table} WHERE `component_name`= '$component_name'  AND `component_action` = '$component_action'";
	

	$get_template  = $wpdb->get_row(  $query  );

	if ( $get_template ){
		echo 'already_there';
		exit;
	} 
	
	$data = array (
		'notification_name'	=> $notification_name,
		'component_name'	=> $component_name,
		'component_action'	=> $component_action,
		'message'			=> $message
	);
	$return = $wpdb->insert( $table, $data );
	echo $return;
	exit;
}
add_action( 'wp_ajax_sn_add_new_template', 'sn_add_new_template' );

/*
*	Edit message template.
*/
function sn_edit_template(){
	$notify_id			= $_POST['notify_id'];
	$message			= $_POST['message'];
	$notification_name	= $_POST['notification_name'];
	
	global $wpdb;
	$table = $wpdb->prefix.'site_notification_template';
	$data =  array(
		'message'			=> $message,
		'notification_name'	=> $notification_name
	);
	$where =  array(
		'ID'	=> $notify_id
	
	);
	return $wpdb->update( $table, $data, $where );
	exit;	
}
add_action( 'wp_ajax_sn_edit_template', 'sn_edit_template' );

function sn_delete_template(){
	$template_id			= $_POST['template_id'];
	if( current_user_can('editor') || current_user_can('administrator') ) {
		global $wpdb;
		$table = $wpdb->prefix.'site_notification_template';
		$where =  array(
			'ID'	=> $template_id
		
		);
		return $wpdb->delete( $table, $where );
	} else {
		return false;
	}
	exit;	
}
add_action( 'wp_ajax_sn_delete_template', 'sn_delete_template' );