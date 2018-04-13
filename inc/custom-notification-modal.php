<?php
	global $wpdb;
	$table = $wpdb->prefix.'site_notification_template';
	$templates = $wpdb->get_results( "SELECT DISTINCT `component_name`  FROM {$table}"  );
	
	$component_name_select = '<select class="custom-select form-control" name="component_name" id="component_name" >';
	$component_name_select .= '<option value="">Choose Component Name</option>';
	
	$component_action_select = '<select class="custom-select form-control" name="component_action" id="component_action" disabled>';
	$component_action_select .= '<option value="">Choose Component Action</option></select>';

	
	foreach ( $templates as $template ) {

		$component_name_select		.= '<option value="'.$template->component_name .'">'. $template->component_name .'</option>';
		
	}
	$component_name_select		.= '</select>';
?>

<!-- create Custom notification -->
<div class="add_custom_notification modal fade" id="add_custom_notification" tabindex="-1" role="dialog">
 <div class="modal-dialog" role="document">
	<div class="modal-content">
		<form class="custom_notification_form" id="custom_notification_form" name="custom_notification_form">
			
			<input type="hidden" name="notification_id" id="notification_id" value="" />
			<input type="hidden" name="sn_action" id="sn_action" value="new_notification" />
			
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="custom_notification_title">Add New Notification</h4>
			</div>
			<div class="modal-body">
				
				<div class="template_alert_notification"></div>
				<input type="hidden" name="is_updated_value" id="is_updated_value" value="0">
				<div class="form-group">
					<label for="Componentname">Component Name</label>
					<?php echo $component_name_select; ?>
					<span class="component_name-error error-message"></span>
				</div>
				<div class="form-group">
					<label for="Componentaction">Component Action</label>
					<?php echo $component_action_select; ?>
					<span class="component_action-error error-message"></span>
				</div>
				<div class="form-group">
					<label for="primary_url">Primary URL/username</label>
					<input name="primary_url" type="text" class="form-control" id="primary_url" placeholder="Primary URL"/>
					<span class="primary_url-error error-message"></span>
				</div>
				
				
			</div>
			<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			<input type="submit" id="custom-notification-button" type="button" class="btn btn-primary" value="Add" />
			</div>
	  
	  </form>
	</div>
  </div>
</div>