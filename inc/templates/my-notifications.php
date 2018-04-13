
<?php
/**
 Template for site notification page.
 You can copy this file into your theme directory /site-notifications/ folder to modify the contents.
*/
get_header();
if( ! is_user_logged_in() ) {
	$active_url = $_SERVER['REQUEST_URI'];
	$site_url = get_bloginfo('url');
	echo $active_url;
	wp_safe_redirect( $site_url . '/?redirect_to=' . $active_url . '&form=registerform' );
	exit;
}
?>
<div class="col-sm-12">
	<div class="container pull-left">
		<div class="notification-single-page-head"> <h1> notifications </h1> </div>
		<div class="sn_notification-single-page">

		<?php 
		$notification_content = sn_display_user_notofication('ALL',bp_loggedin_user_id(), 'ALL' );
		echo $notification_content['content'];
		?>

		</div>
	</div>
</div>

<?php
get_footer(); ?>