$('document').ready(function () {

	$('.notification-btn a').live('click', function (e) {

		$('body').removeClass('menu-visible ');
		$('.notification-btn .count').remove();
		e.preventDefault();
		$('#rareteam_invites').toggleClass("opened");
		$('body').toggleClass("modal-open");
		e.stopPropagation();
		var data = {
			action: 'sn_clear_latest_notification'
		};
		jQuery.ajax({
			type: "POST",
			async: true,
			url: ajaxurl,
			data: data,
			success: function (response) {

			}
		});
	});

	$('#rareteam_invites').on('click', function (e) {
		e.stopPropagation();
	});

	$(document).on('click', function () {
		if ($('#rareteam_invites').hasClass('opened')) {
			$('body').removeClass("modal-open");
		}
		$('#rareteam_invites').removeClass("opened");
	});





});


function create_site_notification(user_id, group_id, item_id, users_to_notify, component_name, component_action, primary_link, secondary_item_id, super_parent_id) {
	secondary_item_id = typeof secondary_item_id !== 'undefined' ? secondary_item_id : 0;
	super_parent_id = typeof super_parent_id !== 'undefined' ? super_parent_id : 0;

	var data = {
		action: 'sn_insert_site_notification',
		site_notification: 'notification_req',
		user_id: user_id,
		group_id: group_id,
		item_id: item_id,
		users_to_notify: users_to_notify,
		component_name: component_name,
		component_action: component_action,
		primary_link: primary_link,
		secondary_item_id: secondary_item_id,
		super_parent_id: super_parent_id
	};
	jQuery.ajax({
		type: "POST",
		async: true,
		url: ajaxurl,
		data: data,
		success: function (response) {
			// done
		}
	});
}


function clear_site_notiification(item_id, group_id, component_name, component_action, delete_action, user_id) {
	user_id = typeof user_id !== 'undefined' ? user_id : '';
	var data = {
		action: 'sn_delete_site_notification',
		item_id: item_id,
		group_id: group_id,
		component_name: component_name,
		component_action: component_action,
		delete_action: delete_action,
		user_id: user_id
	};
	jQuery.ajax({
		type: "POST",
		async: true,
		url: ajaxurl,
		data: data,
		success: function (response) {
			// done
		}
	});
}