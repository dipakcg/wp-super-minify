<?php

function wpsmy_clear_minified_cache() {
	if ( !current_user_can( 'manage_options' ) ) {
		return;
	}

	// Delete all files in JS and CSS cache folders
	$deleted = wpsmy_delete_files_in_directory( WPSMY_CSS_CACHE_DIR );
	$deleted += wpsmy_delete_files_in_directory( WPSMY_JS_CACHE_DIR );
	
	if ( $deleted > 0 ) {
		update_option( 'wpsmy_minified_files', [] ); // Clear saved minified files list
		echo '<div class="notice notice-success"><p>' . $deleted / 2 . ' minified files have been deleted successfully.</p></div>';
	}
	else {
		echo '<div class="notice notice-success"><p>There is no minified CSS & JS file cache found.</p></div>';
	}
}

// Helper function to delete files in a directory
function wpsmy_delete_files_in_directory( $dir ) {
	if ( !is_dir( $dir ) ) {
		return 0;
	}

	$files = glob( $dir . '*' ); // Get all files
	$deleted = 0;

	foreach ( $files as $file ) {
		if ( is_file( $file ) ) {
			unlink( $file ); // Delete file
			$deleted++;
		}
	}

	return $deleted;
}

?>
