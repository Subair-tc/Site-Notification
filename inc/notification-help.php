<?php
/*
* Create sub page for displaying notification log
*/
add_action( 'admin_menu', 'sn_add_admin_help_submenu' );
function sn_add_admin_help_submenu(  ) { 
	add_submenu_page( 
			'system-notification',
			'Help-Notification',
			'Help',
			'manage_options',
			'system-notification-help',
			'sn_notification_help'
	);
}
function sn_notification_help() { 

	define( 'SITE_NOTIFICATIONS_PLUGIN_SCREENSHOT', plugins_url('site-notification').'/images/screenshots/');
?>
	<div class="site-notifications">
		<div class="row">
			<div class="col-sm-12 col-xs-12">
				<h1 class="tect-center"> Site Notifications</h1>
			</div>
		</div>
		
		
		<div class="panel-group" id="notification-help-toggle" role="tablist" aria-multiselectable="true">
			
			<div class="row panel panel-default" id="adding-notification">
				<div class="col-sm-12 no-margin">
					<div class="panel-heading" role="tab" id="adding-notification-heading">
					  <h4 class="panel-title">
						<a role="button" data-toggle="collapse" data-parent="#notification-help-toggle" href="#adding-notification-collapse" aria-expanded="true" aria-controls="adding-notification-collapse">
						  1.  Adding notification Templates
						</a>
					  </h4>
					</div>
					
					<div class="item-content alert-info panel-collapse collapse in" id="adding-notification-collapse"  role="tabpanel" aria-labelledby="adding-notification-heading">
						<p>
							You can add/edit/delete notification templates using <b class="alert-success">'Notifications->Notification templates'</b> submenu.<br/>
						</p>
						<img class="img img-responsive" src="<?php echo SITE_NOTIFICATIONS_PLUGIN_SCREENSHOT; ?>notification-templates-menu.png" alt="notification template" />
						
						<h3> Notification Templates </h3>
						<img class="img img-responsive" src="<?php echo SITE_NOTIFICATIONS_PLUGIN_SCREENSHOT; ?>template.png" alt="notification template" />
						<br/><br/>
						<p><b>Fields used</b></p>
						<ol>
							<li> <b>Notification name:</b>  This is a display name for the notification which uniquely identifies the notification in settings and other areas.</li>
							<li> <b>Component Name: </b>This is the name of the section where the notification gets triggered. For example SMART Social Wall, rareTeam etc. White spaces are not allowed for component name. </li>
							<li> <b>Component Action:</b> This is the name of the action happens in ‘component name’. Here also white spaces are not allowed.(underscores can be used).
							<ol>
								<i>Note:
									<li>Combination of component name and component action must be unique.</li>
									<li>Combination of the component name and component action will determine the notification generated.</li>
								</i>
								</ol>
							</li>
							
							<li> <b>Message:</b>  Notification message shown to the user on front end. There are a few constant defined at the top of the form like {site_url}, {creator_name} etc. This can be included in the message. Please find the screenshot below.
								<span class="alert-success">Example for a message: {creator_name} cares about your SMART Social Wall <?php echo htmlentities('<a href= {url} >post</a>') ?>! </span> 
							</li>
						
						</ol>
						
						<h3> Adding new Templates </h3>
						<img class="img img-responsive" src="<?php echo SITE_NOTIFICATIONS_PLUGIN_SCREENSHOT; ?>new-template.png" alt="new notification template" />
						
					</div>
					
				</div>
			</div>
			 
			<div class="row panel panel-default" id="custom-notification">
				<div class="col-sm-12">
					<div class="panel-heading" role="tab" id="custom-notification-heading">
					  <h4 class="panel-title">
						<a role="button" data-toggle="collapse" data-parent="#notification-help-toggle" href="#custom-notification-collapse" aria-expanded="true" aria-controls="custom-notification-collapse">
						  2.  Sending Site Announcement
						</a>
					  </h4>
					</div>
					
					<div class="item-content alert-info panel-collapse collapse" id="custom-notification-collapse"  role="tabpanel" aria-labelledby="custom-notification-heading">
						<p>
							you can add/edit/delete custom notification using <b class="alert-success">'Notifications->custom notification'</b> submenu.<br/>
							Before adding the notification, a notification template needs to be selected if exists or to be created using the <a href="#adding-notification">'Adding notification Templates'</a>.
						</p>
						
						<h3> Custom Notification</h3>
						<img class="img img-responsive" src="<?php echo SITE_NOTIFICATIONS_PLUGIN_SCREENSHOT; ?>custom-notification.png" alt="custom notification" />
						<br/><br/>
						<p> <b>Fields used </b></p>
						<ol>
							<li> <b>Choose component name:</b> Choose the component name for the notification from the list. If the desired component name is not in the list, add a different template using <a href="#adding-notification">'Adding notification Templates'.</a></li>
							<li> <b>Choose component action:</b>  2.Component action corresponding to the chosen component name will be populated in this field, you can choose the component action from the list. </li>
							<li> <b>Primary URL:</b>  URL ( if any ) to the post/poll/questionnaire, which will replace <b>{url} constant</b> specified in notification template.  </li>
						</ol>
						
						<h3> New Custom Notification</h3>
						<img class="img img-responsive" src="<?php echo SITE_NOTIFICATIONS_PLUGIN_SCREENSHOT; ?>new-custom-notification.png" alt="new custom notification" />
						
					</div>
					
				</div>
			</div>
			
			<div class="row panel panel-default" id="notification-log">
				<div class="col-sm-12">
					
					<div class="panel-heading" role="tab" id="notification-log-heading">
					  <h4 class="panel-title">
						<a role="button" data-toggle="collapse" data-parent="#notification-help-toggle" href="#notification-log-collapse" aria-expanded="true" aria-controls="notification-log-collapse">
						  3.  Notification Log
						</a>
					  </h4>
					</div>
					
					<div class="item-content alert-info panel-collapse collapse" id="notification-log-collapse"  role="tabpanel" aria-labelledby="notification-log-heading">
						<p>
							Notification log on <b class="alert-success">'Notifications->notification log'</b> submenu.<br/>
							where all the notifications sent in the system can be viewed
						</p>
						
						<h3> Notification Log </h3>
						<img class="img img-responsive" src="<?php echo SITE_NOTIFICATIONS_PLUGIN_SCREENSHOT; ?>notification-log.png" alt="new custom notification" />
						
					</div>
					
				</div>
			</div>
			
			<div class="row panel panel-default" id="notification-settings">
				<div class="col-sm-12">
					<div class="panel-heading" role="tab" id="notification-settings-heading">
					  <h4 class="panel-title">
						<a role="button" data-toggle="collapse" data-parent="#notification-help-toggle" href="#notification-settings-collapse" aria-expanded="true" aria-controls="notification-settings-collapse">
						  4.  Notification Settings
						</a>
					  </h4>
					</div>
					
					<div class="item-content alert-info panel-collapse collapse" id="notification-settings-collapse"  role="tabpanel" aria-labelledby="notification-settings-heading">
						<p>
							Notification setting on  <b class="alert-success">'Notifications'</b> menu.<br/>
							which is a TODO. 
						</p>
						<p>Functionalities:</p>
							
						<ol>
							<li> Notification plugin version status and updates.</li>
							<li> ON/OFF functionality for all notifications.</li>
							<li> Enable/Disable feature for push notification. </li>
						</ol>
						<h3> Notification Settings wireframe</h3>
						<img class="img img-responsive" src="<?php echo SITE_NOTIFICATIONS_PLUGIN_SCREENSHOT; ?>settings.png" alt="new custom notification" />
						
					</div>
					
				</div>
			</div>
			
		</div>
	</div>
	
	
	
<?php
}