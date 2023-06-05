<?php
defined( 'ABSPATH' ) || exit;

/**
 * Admin class for Report related features
 */

class EventM_Report_Admin {
    
	/**
	 * Constructor
	 */
	public function __construct() {
            add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_reports_scripts' ) );
            add_action( 'admin_menu', array( $this, 'menus' ),20 );
            add_action( 'ep_reports_tabs_content', array($this, 'ep_reports_tabs_content'), 10, 1 );
            add_action( 'ep_bookings_report_stat', array($this, 'ep_booking_reports_stat'), 10, 1);
            add_action( 'ep_bookings_report_bookings_list', array($this, 'ep_booking_reports_booking_list'), 10, 1);
            add_action( 'ep_booking_reports_booking_list_load_more', array($this, 'ep_booking_reports_booking_list_load_more'), 10, 1);
	}
        
        public function enqueue_admin_reports_scripts($hook){
            if( $hook && 'eventprime_page_ep-events-reports' == $hook ){
            
                wp_enqueue_style(
                'ep-daterangepicker-css',
                EP_BASE_URL . '/includes/events/assets/css/daterangepicker.css',
                false, EVENTPRIME_VERSION
            );
            wp_enqueue_script(
                'ep-moment-js',
                EP_BASE_URL . '/includes/assets/js/moment.min.js',
                array( 'jquery' ), EVENTPRIME_VERSION
            );
            wp_enqueue_script(
                'ep-daterangepicker-js',
                EP_BASE_URL . '/includes/events/assets/js/daterangepicker.min.js',
                array( 'jquery' ), EVENTPRIME_VERSION
            );

            wp_enqueue_script('google_charts', "https://www.gstatic.com/charts/loader.js", array(), EVENTPRIME_VERSION);
            wp_enqueue_style('ep-admin-reports', EP_BASE_URL . 'includes/reports/assets/css/ep-admin-reports.css', false, EVENTPRIME_VERSION);
            wp_enqueue_script( 
                    'ep-advanced-reports', 
                    EP_BASE_URL.'includes/reports/assets/js/ep-admin-reports.js',
                    array( 'jquery' ), EVENTPRIME_VERSION
                    );
            }
        }
        
        public function menus(){
            add_submenu_page( "edit.php?post_type=em_event", esc_html__( 'Reports', 'eventprime-event-calendar-management'), esc_html__('Reports', 'eventprime-event-calendar-management'), "manage_options", "ep-events-reports", array( $this, 'eventprime_reports' ),9 );
        
        }
	
        /*
         * Create reports tabs html
         */
        public function eventprime_reports(){
            $active_tab = isset( $_GET['tab'] ) && array_key_exists( sanitize_text_field($_GET['tab']), $this->ep_get_reports_tabs() ) ? sanitize_text_field($_GET['tab']) : 'booking';
            ?>
            <div class="wrap ep-admin-reports-tabs">
                <form method="post" id="ep_reports" action="<?php echo admin_url( 'admin-post.php' ); ?>" enctype="multipart/form-data">
                    <h2 class="nav-tab-wrapper">
                        <?php
                        $tab_url = remove_query_arg( array( 'section', 'sub_tab' ) );

                        foreach ( $this->ep_get_reports_tabs() as $tab_id => $tab_name ) {
                            $tab_url = add_query_arg( 
                                array( 'tab' => $tab_id),
                                $tab_url
                            );
                            $active = $active_tab == $tab_id ? ' nav-tab-active' : '';
                            if($tab_name['status'] == 'active'){
                                echo '<a href="' . esc_url( $tab_url ) . '" title="' . esc_attr( $tab_name['label'] ) . '" class="nav-tab' . $active . '">';
                                    echo esc_html( $tab_name['label'] );
                                echo '</a>';
                            }
                        }

                        ?>
                    </h2>
                    <?php $this->ep_get_reports_tabs_content( $active_tab );

                    //do_action( 'ep_setting_submit_button' );?>
                </form>
            </div><?php
        }
        
    /**
     * EventPrime reports tabs
     * return array
     */
    public function ep_get_reports_tabs() {
        $tabs = array();
        $tabs['booking']             = array('label'=>esc_html__( 'Booking', 'eventprime-event-calendar-management' ), 'status'=> 'active');
        //$tabs['events']            = array('label'=>esc_html__( 'Events', 'eventprime-event-calendar-management' ), 'status'=> 'disable');
        //$tabs['paymentspro']            = array('label'=>esc_html__( 'Payments', 'eventprime-event-calendar-management' ), 'status'=> 'active');
        //$tabs['attendeespro']            = array('label'=>esc_html__( 'Attendees', 'eventprime-event-calendar-management' ), 'status'=> 'active');
        
        return apply_filters( 'ep_admin_reports_tabs', $tabs );
        
    }
    /**
     * Return reports tabs content
     */
    public function ep_get_reports_tabs_content( $active_tab ) {
        global $wpdb, $wp_roles;
        $options = array();
	//$global_options             = get_option( EM_GLOBAL_SETTINGS );
        $settings     = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
        $gs           = $settings->ep_get_settings();
        $report_tabs = array_keys( $this->ep_get_reports_tabs() );
        ob_start();
        if( in_array( $active_tab, $report_tabs ) ){
            
            do_action( 'ep_reports_tabs_content', $active_tab );
        }
        $tab_content = ob_get_clean();

        
        echo $tab_content;
    }
    
    /*
     * Show tabs content based on tab key
     * @param $tab_key
     * return html
     */
    public function ep_reports_tabs_content($active_tab){
        $events_lists = EventM_Factory_Service::ep_get_events( array( 'id', 'name' ) );
        $data = new stdClass();
        $bookings =  new stdClass();
        $report_controllers = EventM_Factory_Service::ep_get_instance( 'EventM_Report_Controller_List' );
        $bookings_data = $report_controllers->ep_booking_reports();
        
        if($active_tab == 'booking'){
            include __DIR__ .'/tabs/bookings.php';
        }
        if($active_tab == 'paymentspro'){
            include __DIR__ .'/tabs/paymentspro.php';
        }
        if($active_tab == 'attendeespro'){
            include __DIR__ .'/tabs/attendeespro.php';
        }
    }
    
    /*
     * @param $bookings_data
     * return html
     */
    public function ep_booking_reports_stat($bookings_data){
        ob_start();
        
        include __DIR__ .'/tabs/parts/bookings/stat.php';
        
        echo ob_get_clean();
    }
    
    /*
     * @param $bookings_data
     * return html
     */
    public function ep_booking_reports_booking_list($bookings_data){
        ob_start();
        
        include __DIR__ .'/tabs/parts/bookings/booking-list.php';
        
        echo ob_get_clean();
    }
    
    /*
     * @param $bookings_data
     * return html
     */
    public function ep_booking_reports_booking_list_load_more($bookings_data){
        ob_start();
        
        include __DIR__ .'/tabs/parts/bookings/load-more-booking-list.php';
        
        echo ob_get_clean();
    }
    
}

new EventM_Report_Admin();