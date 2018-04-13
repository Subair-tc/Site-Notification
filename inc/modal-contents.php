<!-- Add new template Modal. -->
		<div class="add_template_block modal fade" id="add_template_block" tabindex="-1" role="dialog">
			 <div class="modal-dialog" role="document">
				<div class="modal-content">
					<form class="add_template_form" id="add_template_form" name="new_template">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title" id="myModalLabel">Add New Template</h4>
					</div>
				  <div class="modal-body">
						
						<div class="constance alert alert-info">
							<ul>
								<li>{site_url} 				=> Website URL.</li>
								<li>{user_name}				=> Profile link of the specified user.</li>
								<li>{url} 					=> URL to the specific module.</li>
								<li>{creator_name}			=> Name of the user who initiated the notification.</li>
								<li>{user_to_notify_name}	=> Name of the user to whom the notification is sent.</li>
								<li>{rareteam}				=> Name the rareTeam that notification event initiated.</li>
								<li>{rareteam_link}			=> Link to rareTeam page.</li>
								<li>{rare_team_accept_link}	=> rareTeam Invitation link for accepting the invitation.</li>
								<li>{rare_team_reject_link}	=> rareTeam Invitation link for rejecting the invitation.</li>
								
							</ul>
						</div>
						
						<div class="template_alert"></div>
						
							<div class="form-group">
								<label for="NotificationName">Notification Name</label>
								<input name="notificationName" type="text" class="form-control" id="notification_name" placeholder="Notification Name" />
							</div>
							
							<div class="form-group">
								<label for="Componentname">Component Name</label>
								<input name="componentname" type="text" class="form-control" id="componentname" placeholder="Component Name"/>
								<span class="componentname-error error-message"></span>
							</div>
							
							<div class="form-group">
								<label for="Componentaction">Component Action</label>
								<input name="componentaction" type="text" class="form-control" id="componentaction" placeholder="Component Action"/>
								<span class="componentaction-error error-message"></span>
							</div>
							<div class="form-group">
								<label for="Message">Message</label>
								<textarea  name="message" id="sn_template_message" class="form-control" placeholder="Message"></textarea>
								<span class="message-error error-message"></span>
							</div>
						
						
						<div class="alert alert-warning" role="alert">
						  <strong>please note!</strong> you can't change the Component Name and Component Action once it added.
						</div>
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<input type="submit" id="add-new-template" type="button" class="btn btn-primary" value="add new " />
				  </div>
				  
				  </form>
				</div>
			  </div>
		</div>
		
		
		
		<!-- Edit Template Modal -->
		<div class="edit_template_block modal fade" id="edit_template_block" tabindex="-1" role="dialog">
			 <div class="modal-dialog" role="document">
				<div class="modal-content">
					<form class="edit_template_form" id="edit_template_form" name="new_template">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title" id="myModalLabel">Edit Template</h4>
					</div>
				  <div class="modal-body">
						
						<div class="constance alert alert-info">
							<ul>
								<li>{site_url} 				=> Website URL.</li>
								<li>{user_name}				=> Profile link of the specified user.</li>
								<li>{url} 					=> URL to the specific module.</li>
								<li>{creator_name}			=> Name of the user who initiated the notification.</li>
								<li>{user_to_notify_name}	=> Name of the user to whom the notification is sent.</li>
								<li>{rareteam}				=> Name the rareTeam that notification event initiated.</li>
								<li>{rareteam_link}			=> Link to rareTeam page.</li>
								<li>{rare_team_accept_link}	=> rareTeam Invitation link for accepting the invitation.</li>
								<li>{rare_team_reject_link}	=> rareTeam Invitation link for rejecting the invitation.</li>
							</ul>
						</div>
						
						<div class="template_alert_edit"></div>
						
							<input type="hidden" name="activity_template_id" id="activity_template_id">
							<input type="hidden" name="is_updated_value" id="is_updated_value" value="0">
							
							<div class="form-group">
								<label for="NotificationName">Notification Name</label>
								<input name="notificationName" type="text" class="form-control" id="notification_name_edit" placeholder="Notification Name" />
							</div>
							
							<div class="form-group">
								<label for="Componentname">Component Name</label>
								<input name="componentname" type="text" class="form-control" id="componentname_edit" placeholder="Component Name" disabled/>
							</div>
							
							<div class="form-group">
								<label for="Componentaction">Component Action</label>
								<input name="componentaction" type="text" class="form-control" id="componentaction_edit" placeholder="Component Action" disabled/>
							</div>
							<div class="form-group">
								<label for="Message">Message</label>
								<textarea  name="message" id="sn_template_message_edit" class="form-control" placeholder="Message"></textarea>
								<span class="message-error_edit error-message"></span>
							</div>
						
						
						<div class="alert alert-warning" role="alert">
						  <strong>please note!</strong> you can't change the Component Name and Component Action once it added.
						</div>
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<input type="submit" id="edit-template" type="button" class="btn btn-primary" value="Edit template" />
				  </div>
				  
				  </form>
				</div>
			  </div>
		</div>