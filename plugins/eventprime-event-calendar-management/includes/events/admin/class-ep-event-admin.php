<?php
defined( 'ABSPATH' ) || exit;

/**
 * Admin class for Events related features
 */

class EventM_Events_Admin {
	/**
	 * Constructor
	 */
	public function __construct() {
        add_action( 'init', array( $this, 'includes' ) );
        add_action( 'before_delete_post', array( $this, 'ep_before_delete_events' ), 99, 2 );
        add_filter( 'post_updated_messages', array($this, 'ep_event_updated_messages') );
        add_action( 'restrict_manage_posts', array($this,'ep_events_filters') );
        add_filter( 'parse_query', array($this,'ep_events_filters_arguments'),100,1 );
        add_filter( 'months_dropdown_results', array($this,'ep_events_filters_remove_date'));
	}

	/**
	 * Includes event related admin files
	 */
	public function includes() {
		// Meta Boxes
		include_once __DIR__ . '/meta-boxes/class-ep-event-admin-meta-boxes.php';
	}

	public function ep_before_delete_events( $postid, $post ) {
		if( 'em_event' !== $post->post_type ) {
			return;
		}

		global $wpdb;
		// start process of delete event and event data
		$booking_controllers = EventM_Factory_Service::ep_get_instance( 'EventM_Booking_Controller_List' );
		$event_controllers = EventM_Factory_Service::ep_get_instance( 'EventM_Event_Controller_List' );
		$event_data = $event_controllers->get_single_event( $postid );
		// first check for recurring events
		$metaboxes_controllers = EventM_Factory_Service::ep_get_instance( 'EventM_Event_Admin_Meta_Boxes' );
		$metaboxes_controllers->ep_delete_child_events( $postid );
		// check category and tickets and delete them
		$cat_table_name = $wpdb->prefix.'eventprime_ticket_categories';
		$price_options_table = $wpdb->prefix.'em_price_options';
		// delete all ticket categories
		if( ! empty( $event_data->ticket_categories ) ) {
			foreach( $event_data->ticket_categories as $category ) {
				if( ! empty( $category->id ) ) {
					$wpdb->delete( $cat_table_name, array( 'id' => $category->id ) );
				}
			}
		}
		// delete all tickets
		if( ! empty( $event_data->all_tickets_data ) ) {
			foreach( $event_data->all_tickets_data as $ticket ) {
				if( ! empty( $ticket->id ) ) {
					$wpdb->delete( $price_options_table, array( 'id' => $ticket->id ) );
				}
			}
		}
		// delete booking of this event
		$event_bookings = $booking_controllers->get_event_bookings_by_event_id( $postid );
		if( ! empty( $event_bookings ) ) {
			foreach( $event_bookings as $booking ) {
				// delete booking
				wp_delete_post( $booking->ID, true );
			}
		}
		// delete terms relationships
		wp_delete_object_term_relationships( $postid, array( EM_EVENT_VENUE_TAX, EM_EVENT_TYPE_TAX, EM_EVENT_ORGANIZER_TAX ) );
	}
        
    public function ep_event_updated_messages($message){
        $post = get_post();
        $messages['em_event'] = array(
            0  => '', // Unused. Messages start at index 1.
            1  => esc_html__( 'Event updated.','eventprime-event-calendar-management' ),
            2  => esc_html__( 'Custom field updated.','eventprime-event-calendar-management' ),
            3  => esc_html__( 'Custom field deleted.','eventprime-event-calendar-management'),
            4  => esc_html__( 'Event updated.','eventprime-event-calendar-management' ),
            /* translators: %s: date and time of the revision */
            5  => isset( $_GET['revision'] ) ? sprintf( esc_html__( 'Event restored to revision from %s','eventprime-event-calendar-management' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
            6  => esc_html__( 'Event published.','eventprime-event-calendar-management' ),
            7  => esc_html__( 'Event saved.','eventprime-event-calendar-management' ),
            8  => esc_html__( 'Event submitted.', 'eventprime-event-calendar-management' ),
            9  => sprintf(
                esc_html__( 'Event scheduled for: <strong>%1$s</strong>.','eventprime-event-calendar-management' ),
                date_i18n( esc_html__( 'M j, Y @ G:i' ), strtotime( $post->post_date ) )
            ),
            10 => esc_html__( 'Event draft updated.','eventprime-event-calendar-management' )
        );
        $messages['em_coupon'] = array(
            0  => '', // Unused. Messages start at index 1.
            1  => esc_html__( 'Event Coupon updated.','eventprime-event-calendar-management' ),
            2  => esc_html__( 'Custom field updated.','eventprime-event-calendar-management' ),
            3  => esc_html__( 'Custom field deleted.','eventprime-event-calendar-management'),
            4  => esc_html__( 'Event Coupon updated.','eventprime-event-calendar-management' ),
            /* translators: %s: date and time of the revision */
            5  => isset( $_GET['revision'] ) ? sprintf( esc_html__( 'Event Coupon restored to revision from %s','eventprime-event-calendar-management' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
            6  => esc_html__( 'Event Coupon published.','eventprime-event-calendar-management' ),
            7  => esc_html__( 'Event Coupon saved.','eventprime-event-calendar-management' ),
            8  => esc_html__( 'Event Coupon submitted.', 'eventprime-event-calendar-management' ),
            9  => sprintf(
                esc_html__( 'Event Coupon scheduled for: <strong>%1$s</strong>.','eventprime-event-calendar-management' ),
                date_i18n( esc_html__( 'M j, Y @ G:i' ), strtotime( $post->post_date ) )
            ),
            10 => esc_html__( 'Event Coupon draft updated.','eventprime-event-calendar-management' )
        );

        return $messages;
	}
        /*
        * Adding Filter to Events
        */
        public function ep_events_filters(){
            global $typenow;
            $filter_types = array(
                'publish_date' => esc_html__( 'Created Date','eventprime-event-calendar-management' ),
                'event_date'   => esc_html__( 'Event Date','eventprime-event-calendar-management' ),
            );
            if ( $typenow == 'em_event' ) {
                wp_enqueue_style(
                    'ep-daterangepicker-css',
                    EP_BASE_URL . '/includes/events/assets/css/daterangepicker.css',
                    false, EVENTPRIME_VERSION
                );
                wp_enqueue_script(
                    'ep-daterangepicker-js',
                    EP_BASE_URL . '/includes/events/assets/js/daterangepicker.min.js',
                    array( 'jquery' ), EVENTPRIME_VERSION
                );
                wp_enqueue_script(
                    'ep-events-list-js',
                    EP_BASE_URL . '/includes/events/assets/js/ep-admin-events-list.js',
                    array( 'jquery' ), EVENTPRIME_VERSION
                );

                $selected_filter = 'publish_date';
                if( isset( $_GET['filter_type'] ) ) {
                   $selected_filter = $_GET['filter_type'];
                }?>
                <span><?php esc_html_e( 'Filter by', 'eventprime-event-calendar-management' );?>
                    <select name="filter_type" id="filter_type">
                        <?php foreach( $filter_types as $key => $type ) {?>
                            <option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $selected_filter ); ?>><?php echo esc_attr( $type ); ?></option>
                        <?php } ?>
                    </select>
                </span>
                <span><?php esc_html_e( 'Date', 'eventprime-event-calendar-management' );?>
                    <input id="event_date_picker" type="text" name="ep_filter_date" value="<?php echo isset($_GET['ep_filter_date']) ? $_GET['ep_filter_date'] : '';?>" placeholder="<?php esc_html_e( 'Select Date', 'eventprime-event-calendar-management' );?>" autocomplete="off"/>
                </span><?php 
            }
        }
    
        /*
        * Modify Filter Query
        */
        public function ep_events_filters_arguments( $query ) {
            global $pagenow;
            $post_type = isset( $_GET['post_type'] ) ? sanitize_text_field( $_GET['post_type'] ) : '';
            if ( is_admin() && $pagenow == 'edit.php' && $post_type == 'em_event' && isset( $_GET['filter_type'] ) && sanitize_text_field( $_GET['filter_type'] ) =='event_date' ) {
                if( isset( $_GET['ep_filter_date'] ) && ! empty( $_GET['ep_filter_date'] ) ) {
                    $date_range = sanitize_text_field( $_GET['ep_filter_date'] );
                    $dates = explode( ' - ', $date_range );
                    $start_date = isset( $dates[0] ) && ! empty( $dates[0] ) ? $dates[0] : '';
                    $end_date = isset( $dates[1] ) && ! empty( $dates[1] ) ? $dates[1] : '';

                    if( ! empty( $start_date ) ) {
                        $start_date = date( ep_get_datepicker_format(), strtotime( $start_date ) );
                        $start_meta = array (
                            'key'  =>   'em_start_date',
                            'value' =>   ep_date_to_timestamp( $start_date ),
                            'compare' => '>=',
                            'type'=> 'NUMERIC'
                        );
                        $query->query_vars['meta_query'][] = $start_meta;
                    }
                    if(!empty($end_date)){
                        $end_date = date( ep_get_datepicker_format(), strtotime( $end_date ) );
                        $end_meta = array (
                            'key'  =>   'em_end_date',
                            'value' =>   ep_date_to_timestamp( $end_date ),
                            'compare' => '<=',
                            'type'=> 'NUMERIC'
                        );
                        $query->query_vars['meta_query'][] = $end_meta;
                    }
                }
            }

            $post_type = isset( $_GET['post_type'] ) ? sanitize_text_field( $_GET['post_type'] ) : '';
            if ( is_admin() && $pagenow == 'edit.php' && $post_type == 'em_event' && isset( $_GET['filter_type'] ) && sanitize_text_field( $_GET['filter_type'] ) == 'publish_date' ) {
                if( isset( $_GET['ep_filter_date'] ) && ! empty( $_GET['ep_filter_date'] ) ) {
                    $date_range = sanitize_text_field( $_GET['ep_filter_date'] );
                    $dates = explode( ' - ', $date_range );
                    $start_date = isset( $dates[0] ) && ! empty( $dates[0] ) ? $dates[0] : '';
                    $end_date = isset( $dates[1] ) && ! empty( $dates[1] ) ? $dates[1] : '';

                    if( ! empty( $start_date ) ) {
                        $start_date = date( ep_get_datepicker_format(), strtotime( $start_date ) );
                        $start_publish = array (
                            'after' => $start_date,
                            'inclusive' => true,
                        );
                        $query->query_vars['date_query'][] = $start_publish;
                    }
                    if( ! empty( $end_date ) ) {
                        $end_date = date( ep_get_datepicker_format(), strtotime( $end_date ) );
                        $end_publish = array (
                            'after' => $start_date,
                            'inclusive' => true,
                        );
                        $query->query_vars['date_query'][] = $end_publish;
                    }
                }else{
                    if( $query->get('orderby') == '' ) {
                        $query->set('orderby','publish_date');
                    }
                    if( $query->get('order') == '' ) {
                        $query->set('order','desc');
                    }
                }
            }
            /*if ( is_admin() && $pagenow == 'edit.php' && $post_type == 'em_event' && isset( $_GET['ep_parent_id'] ) && !empty(sanitize_text_field($_GET['ep_parent_id']))){
                $parent_event_id = sanitize_text_field($_GET['ep_parent_id']);
                
                if(!empty($parent_event_id)){
                    //$query->query_vars['post_parent'] = intval($parent_event_id);
                    //$query->query['post_parent'] = intval($parent_event_id);
                }
                
            }*/
        }

        /*
        * Remove Date Filter
        */
        public function ep_events_filters_remove_date( $months ) {
            global $typenow;
            if ( $typenow == 'em_event' ) {
                return array();
            }
            return $months;
        }
}

new EventM_Events_Admin();
