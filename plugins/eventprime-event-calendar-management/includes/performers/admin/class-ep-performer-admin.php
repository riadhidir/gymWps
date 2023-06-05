<?php
defined( 'ABSPATH' ) || exit;

/**
 * Admin class for Performers related features
 */

class EventM_Performers_Admin {
	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'includes' ) );
	}

	/**
	 * Includes performer related admin files
	 */
	public function includes() {
		// Meta Boxes
		include_once __DIR__ . '/meta-boxes/class-ep-performer-admin-meta-boxes.php';
	}
}

new EventM_Performers_Admin();