
jQuery('document').ready(function() {
	
	// Addding new template
	jQuery('#add_template_form').on('submit',function(e){
		e.preventDefault();
		var componentname 		= jQuery('#componentname').val();
		var componentaction 	= jQuery('#componentaction').val();
		var message 			= jQuery('#sn_template_message').val();
		var error = 0;
		
		
		if( message == '' ){
			error =1;
			jQuery('.message-error').html('message is mandatory');
			jQuery('#sn_template_message').focus();
		} else {
			jQuery('.message-error').html('');
		}
		
		if( componentaction == '' ){
			error =1;
			jQuery('.componentaction-error').html('componentaction is mandatory');
			jQuery('#componentaction').focus();
		} else {
			jQuery('.componentaction-error').html('');
		}
		
		if( componentname == '' ){
			error =1;
			jQuery('.componentname-error').html('componentname is mandatory');
			jQuery('#componentname').focus();
		} else {
			jQuery('.componentname-error').html('');
		}
		if( error  == 1 ) {
			return;
		}
		
		jQuery('.error-message').html('');
		var formdata = jQuery( this ).serializeArray();
		/*var data = {
			action: 'sn_add_new_template',
			//items: formdata
			componentname	:	componentname,
			componentaction	:	componentaction,
			message			:	message
		}; */
		var data = {
			action: 'sn_add_new_template',
			items: formdata
		};
		jQuery.ajax({
			type: "POST",
			url: ajaxurl,
			data: data,
			success: function(response) {
				if( response == 'already_there' ) {
					
					jQuery('.template_alert').html('<div class="alert alert-warning alert-dismissible show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Failed!</strong>the template already exist.</div>');
					
				} else if( response > 0 ) {
					jQuery('#is_updated_value').val('1');
					jQuery('.template_alert').html('<div class="alert alert-success alert-dismissible show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Success!</strong>Added new Template.</div>');
					jQuery('#add_template_form').trigger("reset");
				} else {
					jQuery('.template_alert').html('<div class="alert alert-warning alert-dismissible show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Failed!</strong>Something went wrong.</div>');
				}
			}
		});
	});
	
	
	// Setting up edit template modal.
	jQuery('#edit_template_block').on('show.bs.modal', function(e) {
		var id					= jQuery(e.relatedTarget).data('id');
		var component_name 		= jQuery(e.relatedTarget).data('component_name');
		var component_action 	= jQuery(e.relatedTarget).data('component_action');
		var message 			= jQuery(e.relatedTarget).data('message');
		var notification_name	= jQuery(e.relatedTarget).data('notification_name');
		jQuery('#activity_template_id').val( id );
		jQuery('#componentname_edit').val( component_name );
		jQuery('#componentaction_edit').val( component_action );
		jQuery('#sn_template_message_edit').val( message );
		jQuery('#notification_name_edit').val( notification_name );
	});
	
	jQuery('#edit_template_block').on('hide.bs.modal', function(e) {
		var is_updated = jQuery('#is_updated_value').val();
		if( is_updated == 1 ) {
			window.location.reload(true);
		}
		jQuery('.template_alert_edit').html('');
		jQuery('.error-message').html('');
	});
	
	jQuery('#add_template_block').on('hide.bs.modal', function(e) {
		var is_updated = jQuery('#is_updated_value').val();
		if( is_updated == 1 ) {
			window.location.reload(true);
		}
		jQuery('.template_alert_edit').html('');
		jQuery('.error-message').html('');
	});
	
	
	jQuery('#add_custom_notification').on('hide.bs.modal', function(e) {
		var is_updated = jQuery('#is_updated_value').val();
		if( is_updated == 1 ) {
			window.location.reload(true);
		}
		jQuery('.template_alert_edit').html('');
		jQuery('.error-message').html('');
	});
	
	jQuery('#add_template_block').on('hide.bs.modal', function(e) {
		jQuery('.error-message').html('');
	});
	
	
	
	// Edit template option.
	
	jQuery('#edit_template_form').on('submit',function(e){
		e.preventDefault();
		var notification_name	=	jQuery('#notification_name_edit').val();
		var message 			=	jQuery('#sn_template_message_edit').val();
		if( message == '') {
			jQuery('.message-error_edit').html('message is mandatory');
			jQuery('#sn_template_message_edit').focus();
			return;
		}
		
		var notify_id 		=	jQuery('#activity_template_id').val();
		var data = {
			action				:	'sn_edit_template',
			notification_name	: 	notification_name,
			message				: 	message,
			notify_id			: 	notify_id
		};
		
		jQuery.ajax({
			type: "POST",
			url: ajaxurl,
			data: data,
			success: function(response) {
				if( false === response  ) {
					jQuery('.template_alert_edit').html('<div class="alert alert-warning alert-dismissible show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Failed!</strong>Something went wrong.</div>');
				} else {
					jQuery('#is_updated_value').val('1');
					jQuery('.template_alert_edit').html('<div class="alert alert-success alert-dismissible show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Success!</strong>Updated the  Template.</div>');
					//jQuery('#edit_template_block').modal('hide');
				}
			}
		});
		
		
	});
	
	
	// deleting notification template.
	
	jQuery('.delete_template').on('click',function(e){
		e.preventDefault();
		var template_id = jQuery(this).attr('template_id');
		var data = {
			action				:	'sn_delete_template',
			template_id			: 	template_id
		};
		
		var result = confirm("Want to delete?");
		if( ! result ) {
			return;
		}
		
		jQuery.ajax({
			type: "POST",
			url: ajaxurl,
			data: data,
			success: function(response) {
				if( false === response  ) { 
					Alert.render('someting went wrong!!!', "alert-fapvoice");
				} else {
					jQuery('.custom_notification_section_success').show();
					setTimeout(function() {
						window.location.reload(true);
					}, 500);
				}
			}
		});
		
		
	});
	
	// Adding custom notification
	jQuery('#custom_notification_form').on('submit',function(e){
		e.preventDefault();
		var component_name 		=	jQuery('#component_name').val();
		var component_action	=	jQuery('#component_action').val();
		var primary_link		=	jQuery('#primary_url').val();
		
		if( component_name == '') {
			jQuery('.component_name-error').html('Please choose component name');
			jQuery('#component_name').focus();
			return;
		}
		if( component_action == '') {
			jQuery('.component_action-error').html('Please choose component action');
			jQuery('#component_action').focus();
			return;
		}
		
		var sn_action 		= jQuery('#sn_action').val();
		var notification_id = jQuery('#notification_id').val();
		
		var data = {
			action				:	'sn_insert_custom_notification',
			component_name		: 	component_name,
			component_action	: 	component_action,
			primary_link		: 	primary_link,
			sn_action			:	sn_action,
			notification_id		:	notification_id
		};
		
		jQuery.ajax({
			type: "POST",
			url: ajaxurl,
			data: data,
			success: function(response) {
				if( false === response  ) {
					jQuery('.template_alert_notification').html('<div class="alert alert-warning alert-dismissible show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Failed!</strong>Something went wrong.</div>');
				} else {
					jQuery('#is_updated_value').val('1');
					if( sn_action == 'new_notification' ) {
						jQuery('.template_alert_notification').html('<div class="alert alert-success alert-dismissible show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Success!</strong>Added Custom Notification.</div>');
					} else {
						jQuery('.template_alert_notification').html('<div class="alert alert-success alert-dismissible show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Success!</strong>Updated Custom notification.</div>');
					}
					
					//jQuery('#edit_template_block').modal('hide');
				}
				
			}
		});
	});
	
	
	
	jQuery('#component_name').on('change',function(){
		var component_name	= jQuery(this).val();
		var data = {
			action				:	'sn_get_component_action',
			component_name		: 	component_name
		};
		jQuery.ajax({
			type: "POST",
			url: ajaxurl,
			data: data,
			success: function(response) {
				if( response < 0 ) {
					jQuery('.component_name-error').html('Please choose component name');
					jQuery('#component_name').focus();
					jQuery('#component_action').attr('disabled','true');
					jQuery('#component_action').html( '<option value="">Choose Component Action</option>' );
					
					return;
				} else {
					jQuery('#component_action').removeAttr('disabled');
					jQuery('#component_action').html( response );
				}
				
			}
		});
	
	});
	
	//on edit notification
	jQuery('#add_custom_notification').on('show.bs.modal', function(e) {
		var item_id					= jQuery(e.relatedTarget).data('item_id');
		
		
		if( item_id != undefined ) {
			jQuery('#custom_notification_title').html('Edit Notification');
			jQuery('#custom-notification-button').val('Edit');
			var primary_link 		= jQuery(e.relatedTarget).data('primary_link');
			jQuery('#primary_url').val( primary_link );
			jQuery('#sn_action').val( 'edit_notification' );
			jQuery('#notification_id').val( item_id );
		} else {
			jQuery('#custom_notification_title').html('Add New Notification');
			jQuery('#custom-notification-button').val('Add');
			jQuery('#notification_id').val( '' );
			jQuery('#sn_action').val( 'new_notification' );
		}
	});
	
	jQuery('.delete-notification').on('click',function( e ){
		e.preventDefault();
		var item_id	 = jQuery(this).attr('item_id');
		
		var data = {
			action		: 'sn_delete_activate_custom_notification',
			item_id		: item_id,
			status_to	: 0
		};
		jQuery.ajax({
			type: "POST",
			url: ajaxurl,
			data: data,
			success: function( response ) {
				if( false === response  ) { 
					Alert.render('something went wrong', "alert-fapvoice");
				} else {
					jQuery('.custom_notification_section_success').show();
					setTimeout(function() {
						window.location.reload(true);
					}, 500);
				}
				
			}
		});
		
	});
	
	
	jQuery('.activate-notification').on('click',function( e ){
		e.preventDefault();
		var item_id	 = jQuery(this).attr('item_id');
		
		var data = {
			action		: 'sn_delete_activate_custom_notification',
			item_id		: item_id,
			status_to	: 1
		};
		jQuery.ajax({
			type: "POST",
			url: ajaxurl,
			data: data,
			success: function( response ) {
				if( false === response  ) {
					Alert.render('something went wrong', "alert-fapvoice");
				} else {
					jQuery('.custom_notification_section_success').show();
					setTimeout(function() {
						window.location.reload(true);
					}, 500);
					
					
				}
				
			}
		});
		
	});
	
	
	
	// notification  settings
	
	jQuery('.notification-status').on('change',function( e ){
		
		var noti_status = jQuery(this).attr('checked');
		if( noti_status == undefined ) {
			status_to = 0;
		} else {
			status_to = 1;
		}
		var notify_id	= jQuery(this).attr('notifty-id');
		var data = {
			action		: 'sn_enable_desable_notification',
			notify_id	: notify_id,
			status_to	: status_to
		};
		jQuery.ajax({
			type: "POST",
			url: ajaxurl,
			data: data,
			success: function( response ) {
				if( false === response  ) {
					Alert.render('something went wrong', "alert-fapvoice");
				} else {
					jQuery('.custom_notification_section_success').show();
					setTimeout(function() {
						jQuery('.custom_notification_section_success').hide();
					}, 500);
				}
			}
		});
	});
	
	
	
	// data table/
	jQuery('#dataTable').DataTable();
	jQuery('#dataTable1').DataTable();
	jQuery('#notify_settings').DataTable( {
		"paging":   false,
		"ordering": false
	});
	
});