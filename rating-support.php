<?php

// Plugin rating on wordpress.org
function wpsmy_rating_checker() {
	// check for admin notice dismissal
	if ( isset( $_POST['wpsmy-already-reviewed'] ) ) {
		update_option( 'wpsmy_review_notice', '' );
		if ( get_option( 'wpsmy_activation_date' ) ) {
			delete_option( 'wpsmy_activation_date' );
		}
	}

	// display admin notice after 30 days if clicked 'May be later'
	if ( isset( $_POST['wpsmy-review-later'] ) ) {
		update_option( 'wpsmy_review_notice', '' );
		update_option( 'wpsmy_activation_date', strtotime( 'now' ) );
	}

	$install_date = get_option( 'wpsmy_activation_date' );
	$past_date = strtotime( '-30 days' );

	if ( FALSE !== get_option( 'wpsmy_activation_date' ) && $past_date >= $install_date ) {
		update_option( 'wpsmy_review_notice', 'on' );
		delete_option( 'wpsmy_activation_date' );
	}
}
add_action( 'wpsmy_rating_system_action', 'wpsmy_rating_checker' );

/* Add admin notice for requesting plugin review */
function wpsmy_submit_review_notice() {
	global $wpsmy_plugin_version;

	/* Display review plugin notice if plugin updated */
	// only applies to older versions of the plugin (older than 1.9.1) where option isn't set
	// As version 2.0 is a major release, let's force users to submit review on wordpress.org
	if ( isset( $_GET['update-applied'] ) && $_GET['update-applied'] == 'true' ) {
		if ( FALSE === get_option('wpsmy_review_notice' || wpsmy_PLUGIN_VERSION == '2.0' ) ) {
			update_option( 'wpsmy_review_notice', "on" );
		}
	}

	/* Check transient that's been set on plugin activation or check if user has already submitted review */
	// if( get_transient( 'wpsmy_submit_review_transient' ) || !get_user_meta( $user_id, 'wpsmy_submit_review_dismissed' ) ) {
	if( get_option( 'wpsmy_review_notice') && get_option( 'wpsmy_review_notice' ) == "on"  ) {

		$notice_contents = '<p> Thank you for using <strong>WP Super Minify</strong>. </p>';
		$notice_contents .= '<p> Could you please do me a BIG favour and give this plugin a 5-star rating on WordPress? It won\'t take more than a minute, and help me spread the word and boost my motivation. - Dipak C. Gajjar </p>';
		$notice_contents .= '<p> <a href="#" id="wpsmy_letMeReview" class="button button-primary">Yes, you deserve it</a> &nbsp; <a href="#" id="wpsmy_willReviewLater" class="button button-primary">Maybe later</a> &nbsp; <a href="#" id="wpsmy_alredyReviewed" class="button button-primary">I already did it</a> &nbsp; <a href="#" id="wpsmy_noThanks" class="button button-primary">No, Thanks</a> </p>';
		?>
		<div class="notice notice-info is-dismissible" id="wpsmy_notice_div"> <?php _e( $notice_contents, 'wp-performance-score-booster' ); ?> </div>
		<script type="text/javascript">
			// set jQuery in noConflict mode. This helps to mitigate conflicts between jQuery scripts. jQuery conflicts are all too common with themes and plugins.
			var $j = jQuery.noConflict();
			$j(document).ready( function() {
				var loc = location.href;
				// loc += loc.indexOf("?") === -1 ? "?" : "&";
				// Yes, you deserve it
				$j("#wpsmy_letMeReview").on('click', function() {
					$j('#wpsmy_notice_div').slideUp();
					$j.ajax({
						url: loc,
						type: 'POST',
						data: {
							"wpsmy-review-later": ''
						},
						success: function(msg) {
							window.open("//wordpress.org/support/plugin/wp-super-minify/reviews/?rate=5#new-post", "_blank");
						}
					});
				});
				// Maybe later
				$j("#wpsmy_willReviewLater").on('click', function() {
					$j('#wpsmy_notice_div').slideUp();
					$j.ajax({
						url: loc,
						type: 'POST',
						data: {
							"wpsmy-review-later": ''
						}/* ,
						success: function(msg) {
							console.log("wpsmy DEBUG: Review the Plugin Later.");
						} */
					});
				});
				// I already did it
				$j("#wpsmy_alredyReviewed").on('click', function() {
					$j('#wpsmy_notice_div').slideUp();
					$j.ajax({
						url: loc,
						type: 'POST',
						data: {
							"wpsmy-already-reviewed": ''
						}
					});
				});
				// No, thanks
				$j("#wpsmy_noThanks").on('click', function() {
					$j('#wpsmy_notice_div').slideUp();
					$j.ajax({
						url: loc,
						type: 'POST',
						data: {
							"wpsmy-already-reviewed": ''
						}
					});
				});
				/* If top-right X button clicked */
				$j(document).on('click', '#wpsmy_notice_div .notice-dismiss', function() {
					$j('#wpsmy_notice_div').slideUp();
					$j.ajax({
						url: loc,
						type: 'POST',
						data: {
							"wpsmy-already-reviewed": ''
						}
					});
				});
				
			});
		</script>
		<?php
		// delete_transient( 'wpsmy_submit_review_transient' );
	}
}
add_action( 'admin_notices', 'wpsmy_submit_review_notice' );

?>
