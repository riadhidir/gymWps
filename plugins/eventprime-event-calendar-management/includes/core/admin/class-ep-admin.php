<?php
defined( 'ABSPATH' ) || exit;

/**
 * Class for core admin use
 */
class EventM_Admin {
    /**
     * Constructor
     */
    public function __construct() {
        add_action( 'admin_init', array( $this, 'plugin_redirect' ) );
        add_action( 'init', array( $this, 'includes' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'ep_admin_enqueues' ) );
    }

    /**
     * Redirect plugin after activate
     */
    public function plugin_redirect() {
        if ( get_option( 'event_magic_do_activation_redirect', false ) ) {
            delete_option( 'event_magic_do_activation_redirect' );
            $check_for_migration = get_option( 'ep_db_need_to_run_migration' );
            $update_migration = get_option( 'ep_update_revamp_version' );
            if( ! empty( $check_for_migration ) && empty( $update_migration ) ) {
                wp_safe_redirect( admin_url( 'edit.php?post_type=em_event&page=ep-revamp-migration' ) );
            } else{
                wp_safe_redirect( admin_url( 'edit.php?post_type=em_event' ) );
            }
            exit;
        }
    }

    /**
     * Include classes for admin use
     */
    public function includes() {
        // admin menu class
        include_once __DIR__ . '/class-ep-admin-menus.php';
        include_once __DIR__ . '/class-ep-admin-notices.php';
        
    }

    /**
     * Load common scripts and styles for admin
     */
    public function ep_admin_enqueues() {
        wp_enqueue_script(
            'ep-common-script',
            EP_BASE_URL . '/includes/assets/js/ep-common-script.js',
            array( 'jquery' ), EVENTPRIME_VERSION
        );

        // localized global settings
        $global_settings = ep_get_global_settings();
        $currency_symbol = ep_currency_symbol();
        wp_localize_script(
            'ep-common-script', 
            'eventprime', 
            array(
                'global_settings' => $global_settings,
                'currency_symbol' => $currency_symbol,
                'ajaxurl'         => admin_url( 'admin-ajax.php' ),
                'trans_obj'       => EventM_Factory_Service::ep_define_common_field_errors(),
            )
        );

        wp_enqueue_script(
			'ep-admin-utility-script',
			EP_BASE_URL . 'includes/assets/js/ep-admin-common-utility.js',
			array( 'jquery', 'jquery-ui-tooltip', 'jquery-ui-dialog' ), EVENTPRIME_VERSION
        );

        wp_localize_script(
            'ep-admin-utility-script', 
            'ep_admin_utility_script', 
            array(
                'ajaxurl'   => admin_url( 'admin-ajax.php' )
            )
        );

        wp_enqueue_style(
			'ep-admin-utility-style',
			EP_BASE_URL . 'includes/assets/css/ep-admin-common-utility.css',
			false, EVENTPRIME_VERSION
        );

        wp_enqueue_style( 'ep-material-fonts', 'https://fonts.googleapis.com/icon?family=Material+Icons', array(), EVENTPRIME_VERSION );
        // register common scripts
        wp_register_script(
			'em-admin-jscolor',
			EP_BASE_URL . '/includes/assets/js/jscolor.min.js',
			false, EVENTPRIME_VERSION
		);

        wp_register_style(
			'em-admin-select2-css',
			EP_BASE_URL . '/includes/assets/css/select2.min.css',
			false, EVENTPRIME_VERSION
		);
		wp_register_script(
			'em-admin-select2-js',
			EP_BASE_URL . '/includes/assets/js/select2.full.min.js',
			false, EVENTPRIME_VERSION
		);

        wp_register_style(
		    'em-admin-jquery-ui',
		    EP_BASE_URL . '/includes/assets/css/jquery-ui.min.css',
		    false, EVENTPRIME_VERSION
        );
		// Ui Timepicker css
		wp_register_style(
		    'em-admin-jquery-timepicker',
		    EP_BASE_URL . '/includes/assets/css/jquery.timepicker.min.css',
		    false, EVENTPRIME_VERSION
        );

        // timepicker js
		wp_register_script(
		    'em-admin-timepicker-js',
		    EP_BASE_URL . '/includes/assets/js/jquery.timepicker.min.js',
		    false, EVENTPRIME_VERSION
        );

        // register toast
        wp_register_style(
            'ep-toast-css',
            EP_BASE_URL . '/includes/assets/css/jquery.toast.min.css',
            false, EVENTPRIME_VERSION
        );
        wp_register_script(
            'ep-toast-js',
            EP_BASE_URL . '/includes/assets/js/jquery.toast.min.js',
            array('jquery'), EVENTPRIME_VERSION
        );
        wp_register_script(
            'ep-toast-message-js',
            EP_BASE_URL . '/includes/assets/js/toast-message.js',
            array('jquery'), EVENTPRIME_VERSION
        );

        // Blocks style for admin
		wp_register_style(
		    'ep-admin-blocks-style',
		    EP_BASE_URL . '/includes/assets/css/ep-admin-blocks-style.css',
		    false, EVENTPRIME_VERSION
        );
    }
}

return new EventM_Admin();