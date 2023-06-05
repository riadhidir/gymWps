<?php
/**
 * Class for return events data
 */

defined( 'ABSPATH' ) || exit;

class EventM_Event_Controller_List {
    /**
     * Term Type.
     * 
     * @var string
     */
    private $post_type = EM_EVENT_POST_TYPE;
    /**
     * Instance
     *
     * @var Instance
     */
    public static $instance = null;
    /**
     * Constructor
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Get single event data.
     * 
     * @param int $post_id Post ID.
     * 
     * @return objact $event Event Data.
     */
    public function get_single_event( $post_id, $post = null ) {
        if( empty( $post_id ) ) return;

        $event = new stdClass();
        $meta = get_post_meta( $post_id );
        foreach ( $meta as $key => $val ) {
            $event->{$key} = maybe_unserialize( $val[0] );
        }
        if( empty( $post ) ) {
            $post = get_post( $post_id );
        }
        if( $post ){
            $event->id                 = $post->ID;
            $event->name               = $post->post_title;
            $event->slug               = $post->post_name;
            $event->description        = wp_kses_post( $post->post_content );
            $event->post_status        = $post->post_status;
            $event->post_parent        = $post->post_parent;
            $event->fstart_date        = ( ! empty( $event->em_start_date ) ) ? ep_timestamp_to_date( $event->em_start_date, 'd M', 1 ) : '';
            $event->fend_date          = ( ! empty( $event->em_end_date ) ) ? ep_timestamp_to_date( $event->em_end_date, 'd M', 1 ) : '';
            $event->start_end_diff     = ep_get_event_date_time_diff( $event );
            $event->event_url          = ep_get_custom_page_url( 'events_page', $event->id, 'event' );
            $event->ticket_categories  = $this->get_event_ticket_category( $event->id );
            $event->solo_tickets       = $this->get_event_solo_ticket( $event->id );
            $event->ticket_price_range = $this->get_ticket_price_range( $event->ticket_categories, $event->solo_tickets );
            $event->all_tickets_data   = $this->get_event_all_tickets( $event );
            $event->venue_details       = ( ! empty( $event->em_venue ) ) ? EventM_Factory_Service::ep_get_venue_by_id( $event->em_venue ) : array();
            $event->event_type_details  = ( ! empty( $event->em_event_type ) ) ? EventM_Factory_Service::ep_get_event_type_by_id( $event->em_event_type ) : array();
            $event->organizer_details   = ( ! empty( $event->em_organizer ) ) ? EventM_Factory_Service::get_organizers_by_id( $event->em_organizer ) : array();
            $event->performer_details   = ( ! empty( $event->em_performer) ) ? EventM_Factory_Service::get_performers_by_id( $event->em_performer ) : array();
            $event->image_url           = $this->get_event_image_url( $event->id );
            $other_events               = EventM_Factory_Service::ep_get_child_events( $post->ID );
            $event->child_events        = array();
            if( ! empty( $other_events ) && count( $other_events ) > 0 ) {
                $other_event_data    = EventM_Factory_Service::load_event_full_data( $other_events );
                $event->child_events = $other_event_data;
            }
            $event->all_offers_data  = EventM_Factory_Service::get_event_all_offers( $event );
            $event->qr_code          = EventM_Factory_Service::get_event_qr_code( $event );
            $event->em_event_checkout_attendee_fields = EventM_Factory_Service::get_event_checkout_fields( $event );
            $event->event_in_user_wishlist = check_event_in_user_wishlist( $event->id );
        }
        return $event;
    }

    /**
     * Get post data
     */
    public function get_events_post_data( $args = array() ) {
        $default = array(
            'post_status' => 'publish',
            'order'       => 'ASC',
            'post_type'   => $this->post_type,
            'numberposts' => -1,
            'offset'      => 0,
            'meta_key'    => 'em_start_date',
            'orderby'     => 'meta_value',
        );
        $args = wp_parse_args( $args, $default );
        $posts = get_posts( $args );
        if( empty( $posts ) )
           return array();
       
        $events = array();
        foreach( $posts as $post ) {
            if( empty( $post ) || empty( $post->ID ) ) continue;
            $event = $this->get_single_event( $post->ID, $post );
            if( ! empty( $event ) ) {
                $events[] = $event;
            }
        }

        $wp_query = new WP_Query( $args );
        $wp_query->posts = $events;

        return $wp_query;
    }

    /**
     * Get data to show on the event view
     * 
     * @param object $event Event.
     * 
     * @return array Event Data.
     */
    public function get_event_data_to_views( $event ) {
        $ev = array();
        if( ! empty( $event ) && ! empty( $event->id ) ) {
            $ev['title'] = ( ! empty( $event->em_name ) ? $event->em_name : $event->name );
            $ev['id']    = $event->id;
            $ev['start'] = $ev['end'] = $ev['start_time'] = $ev['end_time'] = $ev['bg_color'] = $ev['type_text_color'] = $ev['address'] = $ev['image'] = $ev['date_custom_note'] = '';
            if( ! empty( $event->em_start_date ) ) {
                $start_date       = ep_timestamp_to_date( $event->em_start_date, 'Y-m-d', 1 );
                $ev['start']      = $start_date;
                if( ! empty( $event->em_start_time ) && ep_show_event_date_time( 'em_start_time', $event ) && empty( ep_get_global_settings( 'hide_time_on_front_calendar' ) ) ) {
                    $st_time = date( "H:i", strtotime( $event->em_start_time ) );
                    $st_time = explode( ' ', $st_time )[0];
                    $ev['start'] .= ' '. $st_time;
                }
                $ev['start_time'] = ( ! empty( $event->em_start_time ) ? $event->em_start_time : '' );
            }
            if( ! empty( $event->em_end_date ) ) {
                $end_date   = ep_timestamp_to_date( $event->em_end_date, 'Y-m-d', 1 );
                $ev['end']  = $end_date;
                if( ! empty( $event->em_start_date ) && $event->em_start_date == $event->em_end_date ) {
                    $ev['end']  = date( 'l', $event->em_start_date );
                }
                if( ! empty( $event->em_end_time ) ) {
                    $ev['end_time'] = $event->em_end_time;
                }
            }
            // event type
            if( !empty( $event->em_event_type ) ) {
                $event_type            = EventM_Factory_Service::ep_get_instance( 'EventM_Event_Type_Controller_List' );
                $single_et             = $event_type->get_single_event_type( $event->em_event_type );
                $ev['bg_color']        = ( ! empty( $single_et->em_color ) ) ? ep_hex2rgba($single_et->em_color) : '';
                $ev['type_text_color'] = ( !empty( $single_et->em_type_text_color ) ) ? $single_et->em_type_text_color : '';
            }
            // venue
            if( !empty( $event->em_venue ) ) {
                $venue         = EventM_Factory_Service::ep_get_instance( 'EventM_Venue_Controller_List' );
                $single_venue  = $venue->get_single_venue( $event->em_venue );
                $ev['venue_name'] = ( ! empty( $single_venue->em_name ) ) ? $single_venue->em_name : '';
                $ev['address'] = ( ! empty( $single_venue->em_address ) ) ? $single_venue->em_address : '';
            }
            // image
            $featured_img_url = get_the_post_thumbnail_url( $event->id );
            if( ! empty( $featured_img_url ) ) {
                $ev['image'] = $featured_img_url;
            }
            // url
            $ev['event_url'] = ep_get_custom_page_url( 'events_page', $event->id, 'event' );
            $ev['url'] = ep_get_custom_page_url( 'events_page', $event->id, 'event' );
            // if hide start date then check for custom note
            if( ! ep_show_event_date_time( 'em_start_date', $event ) ) {
                if( $event->em_event_date_placeholder != 'custom_note' ) {
                    $ev['date_custom_note'] = $event->em_event_date_placeholder;
                } else{
                    $ev['date_custom_note'] = ( ! empty( $event->em_event_date_placeholder_custom_note) ? $event->em_event_date_placeholder_custom_note : '' );
                }
            }
            
        }
        return $ev;
    }

    /**
     * Get data to show on the event view from event id
     * 
     * @param int $event_id Event ID.
     * 
     * @return array Event Data.
     */
    public function get_event_data_to_views_by_id( $event_id ) {
        $ev = array();
        if( ! empty( $event_id ) ) {
            $event = $this->get_single_event( $event_id );
            if( ! empty( $event ) ) {
                $ev = $this->get_event_data_to_views( $event );
            }
        }
        return $ev;
    }

    /**
     * Get events for front calendar view
     * 
     * @param object $events Events.
     * 
     * @return string Calender popup html.
     */
    public function get_front_calendar_view_event( $events ) {
        $cal_events = array();
        if( ! empty( $events ) && ! empty( $events ) ) {
            $new_window = ( ! empty( ep_get_global_settings( 'open_detail_page_in_new_tab' ) ) ? 'target="_blank"' : '' );
            foreach( $events as $event ) {
                $ev = $this->get_event_data_to_views( $event );
                $start_date_time = $ev['start'];
                if( ep_show_event_date_time( 'em_start_time', $event ) ) {
                    $start_date_time = explode( ' ', $start_date_time )[0];
                }
                // popup html
                $popup_html = '<div class="ep_event_detail_popup" id="ep_calendar_popup_'.esc_attr( $ev['id'] ).'" style="display:none">';
                    $popup_html .= '<a href="'.esc_url( $ev['event_url'] ).'" class="ep_event_popup_head" '.esc_attr( $new_window ).'>';
                        $popup_html .= '<div class="ep_event_popup_image">';
                            $popup_html .= '<img src="'.esc_url( $ev['image'] ).'">';
                        $popup_html .= '</div>';
                    $popup_html .= '</a>';
                    $popup_html .= '<div class="ep_event_popup_date_time_wrap ep-d-flex">';
                        $popup_html .= '<div class="ep_event_popup_date ep-d-flex ep-box-direction">';
                            if( ep_show_event_date_time( 'em_start_date', $event ) ) {
                                $popup_html .= '<span class="ep_event_popup_start_date">' .esc_html( $start_date_time ) .'</span>';
                            } else{
                                if( ! empty( $ev['date_custom_note'] ) ) {
                                    if( $ev['date_custom_note'] == 'tbd' ) {
                                        $tbd_icon_file = EP_BASE_URL .'/includes/assets/images/tbd-icon.png';
                                        $popup_html .= '<span class="ep_event_popup_start_date"><img src="'. esc_url( $tbd_icon_file ) .'" width="35" /></span>';
                                    } else{
                                        $popup_html .= '<span class="ep_event_popup_start_date">' .esc_html( $ev['date_custom_note'] ) .'</span>';
                                    }
                                }
                            }
                            if( ep_show_event_date_time( 'em_end_date', $event ) ) {
                                $popup_html .= '<span class="ep_event_popup_end_date">' .esc_html( $ev['end'] ) .'</span>';
                            }
                        $popup_html .= '</div>';
                        $popup_html .= '<div class="ep_event_popup_time ep-d-flex ep-box-direction">';
                            if( ep_show_event_date_time( 'em_start_time', $event ) ) {
                                $popup_html .= '<span class="ep_event_popup_start_time">' .esc_html( ep_convert_time_with_format( $ev['start_time'] ) ) .'</span>';
                            }
                            if( ep_show_event_date_time( 'em_end_time', $event ) ) {
                                $popup_html .= '<span class="ep_event_popup_end_time">' .esc_html( ep_convert_time_with_format( $ev['end_time'] ) ) .'</span>';
                            }
                        $popup_html .= '</div>';
                    $popup_html .= '</div>';
                    $popup_html .= '<a href="'.esc_url( $ev['event_url'] ).'" class="ep-event-modal-head" '.esc_attr( $new_window ).'>';
                        $popup_html .= '<div class="ep_event_popup_title">';
                            $popup_html .= esc_html( $ev['title'] );
                        $popup_html .= '</div>';
                    $popup_html .= '</a>';
                    if( ! empty( $ev['address'] ) ) {
                        $popup_html .= '<div class="ep_event_popup_address">';
                            $popup_html .= esc_html( $ev['address'] );
                        $popup_html .= '</div>';
                    }
                    // booking button
                    /* ob_start();
                        do_action( 'ep_event_view_event_booking_button', $event );
                        $popup_html .= ob_get_contents();
                    ob_end_clean(); */

                $popup_html .= '</div>';
                
                $ev['popup_html']=  $popup_html;

                $cal_events[] = $ev;
            }
        }
        return $cal_events;
    }

    /** 
     * Load common option for event views
     */
    public function load_event_common_options( $atts = array(), $load_more = 0 ) {
        $events_data     = array();
        $settings        = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
        $events_settings = $settings->ep_get_settings( 'events' );
        $events_data['calendar_view'] = 0;
        $events_data['display_style'] = isset( $_POST['display_style'] ) ? $_POST["display_style"] : ep_get_global_settings( 'default_cal_view' );
        if( in_array( $events_data['display_style'], array_keys( ep_get_event_calendar_views() ) ) ) {
            $events_data['calendar_view'] = 1;
        }
        if( isset( $atts['view'] ) && ! empty( $atts['view'] ) ){
            $events_data['display_style'] = $atts['view'];
        }
        
        $events_data['limit'] = isset( $atts['limit'] ) ? ( empty( $atts["limit"] ) ? EP_PAGINATION_LIMIT : $atts["limit"]) : ( empty( $events_settings->show_no_of_events_card ) ? EP_PAGINATION_LIMIT : $events_settings->show_no_of_events_card );
        // if custom event limit is existing and views are card, masonry and list
        if( ( $events_data['display_style'] == 'card' || $events_data['display_style'] == 'square_grid' || $events_data['display_style'] == 'masonry' || $events_data['display_style'] == 'staggered_grid' || $events_data['display_style'] == 'list' || $events_data['display_style'] == 'rows' ) && $events_settings->show_no_of_events_card == 'custom' ){
            $events_data['limit'] = $events_settings->card_view_custom_value;
        }
        if( ( $events_data['display_style'] == 'card' || $events_data['display_style'] == 'square_grid' || $events_data['display_style'] == 'masonry' || $events_data['display_style'] == 'staggered_grid' || $events_data['display_style'] == 'list'|| $events_data['display_style'] == 'rows' ) && $events_settings->show_no_of_events_card == 'all' ){
            $events_data['limit'] = -1;
        }
       
        if( isset( $_POST['limit'] ) && ! empty( $_POST['limit'] ) ) {
            $events_data['limit'] = $_POST['limit'];
        }
        // shortcode limit
        if( isset( $atts['show'] ) && ! empty( $atts['show'] ) ){
            $events_data['limit'] = $atts['show'];
        }
        // limit will be -1 for calendar views
        if($events_data['display_style'] == 'slider' || $events_data['display_style'] == 'month' || $events_data['display_style'] == 'week' || $events_data['display_style'] == 'day' || $events_data['display_style'] == 'listweek' ){
            $events_data['limit'] = -1;
        }
        // set query arguments
        $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
        if( $load_more ) {
            $paged = ( $_POST['paged'] ) ? $_POST['paged'] : 1;
            $paged++;
        }
        $events_data['paged'] = $paged;

        $params = array(
            'meta_key'       => 'em_start_date',
            'orderby'        => 'meta_value_num',
            'posts_per_page' => $events_data['limit'],
            'offset'         => (int) ( $paged-1 ) * $events_data['limit'],
            'paged'          => $paged,
            'meta_query'     => array( 'relation' => 'AND' ),
        );

        // if event id is set in shortcode
        if( isset( $atts['id'] ) && ! empty( $atts['id'] ) ){
            $params['p'] = absint($atts['id']);
        }
        // condition for hide upcoming events
        $hide_upcoming_events = 0;
        /* if( ep_get_global_settings( 'shortcode_hide_upcoming_events' ) == 1 ) {
            $hide_upcoming_events = 1;
        } */
        if( isset( $atts['upcoming'] ) && $atts['upcoming'] == 0 ) {
            $hide_upcoming_events = 1;
        }
        if( $hide_upcoming_events == 1 ) {
            array_push( $params['meta_query'], array(
                'key'     => 'em_start_date',
                'value'   => ep_get_current_timestamp(),
                'compare' => '<='
            ) );
        }

        // condition for hide past events
        $hide_past_events = 0;
        // from settings
        if( ep_get_global_settings( 'hide_past_events' ) == 1 ) {
            $hide_past_events = 1;
        }
		// from shortcode
		if( isset( $atts['upcoming'] ) && $atts['upcoming'] == 1 ) {
            $hide_past_events = 1;
        }
        if( $hide_past_events == 1 ) {
            array_push( $params['meta_query'], array(
                'key'     => 'em_end_date',
                'value'   => strtotime( 'today' ),
                'compare' => '>='
            ) );
        }

        $event_search_params = array();
        if( isset( $_POST['event_search_params'] ) && ! empty( $_POST['event_search_params'] ) ) {
            $event_search_params = json_decode( stripslashes( $_POST['event_search_params'] ), true );
            if( ! empty( $event_search_params ) && count( $event_search_params ) > 0 ) {
                $params = $this->create_filter_query($event_search_params, $params);
            }
        }

        // shortcode event types
        $type_ids = array();
        if( isset( $atts['types'] ) && ! empty( $atts['types'] ) ) {
            $type_ids = explode( ',', $atts['types'] );
        }
        if( isset( $_POST['event_types_ids'] ) && ! empty( $_POST['event_types_ids'] ) ) {
            $type_ids = explode( ',', $_POST['event_types_ids'] );
        }

        if ( ! empty( $type_ids ) ) {
            array_push( $params['meta_query'], array(
                'key'     => 'em_event_type',
                'value'   => $type_ids,
                'compare' => 'IN',
                'type'=>'NUMERIC'
            ) );
        }
        $events_data['types_ids'] = $type_ids;
        // shortcode event venues
        $venue_ids = array();
        if( isset( $atts['sites'] ) && ! empty( $atts['sites'] ) ) {
            $venue_ids = explode( ',', $atts['sites'] );
        }
        if( isset( $_POST['event_venues_ids'] ) && ! empty( $_POST['event_venues_ids'] ) ) {
            $venue_ids = explode( ',', $_POST['event_venues_ids'] );
        }
        if ( ! empty( $venue_ids ) ) {
            array_push( $params['meta_query'], array(
                'key'     => 'em_venue',
                'value'   => $venue_ids,
                'compare' => 'IN',
                'type'=>'NUMERIC'
            ) );
        }
        $events_data['venue_ids'] = $venue_ids;

        // individual events argument
        $events_data['i_events'] = '';
        if( isset( $atts['individual_events'] ) && ! empty( $atts['individual_events'] ) ){
            $events_data['i_events'] = $atts['individual_events'];
            $params['meta_query'] = $this->individual_events_shortcode_argument( $params['meta_query'],  $events_data['i_events'] );
        }
        // load more individual events
        if( isset( $_POST['i_events'] ) && ! empty( $_POST['i_events'] ) ) {
            $events_data['i_events'] = $_POST['i_events'];
            $params['meta_query'] = $this->individual_events_shortcode_argument( $params['meta_query'],  $_POST['i_events'] );
        }

        // render atts data from extensions
        $params = apply_filters( 'ep_events_render_attribute_data', $params, $atts ); 

        $posts = $this->get_events_post_data( $params );

        // filter for events
        // $events_data['events'] = apply_filters( 'ep_filter_front_events', $posts->posts, $params );
        $events_data['events'] = apply_filters( 'ep_filter_front_events', $posts, $atts );
        /* // filter draft events
            $events_data['events'] = array_filter( $posts, function($post) { 
            return $post->post_status !== 'draft'; 
        }); */

        // get event views
        $events_data['event_views'] = ( ! empty( ep_get_global_settings( 'front_switch_view_option' ) ) ? ep_get_global_settings( 'front_switch_view_option' ) : array() );
        // get event types
        $events_data['event_types'] = EventM_Factory_Service::ep_get_event_types( array( 'id', 'name', 'em_color' ) );
        // get performaers
        $events_data['performers']  = EventM_Factory_Service::ep_get_performers( array( 'id', 'name' ) );
        // get organizers
        $events_data['organizers']  = EventM_Factory_Service::ep_get_organizers( array( 'id', 'name' ) );
        // get organizers
        $events_data['venues']      = EventM_Factory_Service::ep_get_venues( array( 'id', 'name' ) );

        // filters and filter elements condition
        $events_data['show_event_filter'] = 1;
        if( ep_get_global_settings( 'disable_filter_options' ) == 1 ) {
            $events_data['show_event_filter'] = 0;
        }
        if( isset( $atts['disable_filter'] ) && $atts['disable_filter'] == 0 ) {
            $events_data['show_event_filter'] = 1;
        }
        // shortcode filters and filter elements condition
        if( isset( $atts['disable_filter'] ) && ! empty( $atts['disable_filter'] ) ){
            $events_data['show_event_filter'] = ( $atts['disable_filter'] == 1 ) ? 0 : $atts['disable_filter'];
        }
        $events_data['section_id'] = rand( 1, 1000 );

        $events_data['cols'] = 4;
        $show_cols = esc_attr( ep_get_global_settings( 'events_no_of_columns' ) ) ;
        if( ! empty( $show_cols ) ) {
            $events_data['cols'] = 12 / $show_cols;
        }
        // show hide filter elements
        $events_data['quick_search'] = $events_data['date_range'] = $events_data['event_type'] = $events_data['venue'] = $events_data['performer'] = $events_data['organizer'] = 1;
        if( isset( $atts['filter_elements'] ) && ! empty( $atts['filter_elements'] ) ) {
            $filter_elements_arr = explode( ',', $atts['filter_elements'] );
            if( in_array( 'quick_search', $filter_elements_arr ) ) {
                $events_data['quick_search'] = 1;
            } else{
                $events_data['quick_search'] = 0;
            }
            if( in_array( 'date_range', $filter_elements_arr ) ){
                $events_data['date_range'] = 1;
            }else{
                $events_data['date_range'] = 0;
            }
            if( in_array( 'event_type', $filter_elements_arr ) ){
                $events_data['event_type'] = 1;
            }else{
                $events_data['event_type'] = 0;
            }

            if( in_array( 'venue', $filter_elements_arr ) ){
                $events_data['venue'] = 1;
            }else{
                $events_data['venue'] = 0;
            }
             
            if( in_array( 'performer', $filter_elements_arr ) ){
                $events_data['performer'] = 1;
            }else{
                $events_data['performer'] = 0;
            }

            if( in_array( 'organizer', $filter_elements_arr ) ){
                $events_data['organizer'] = 1;
            }else{
                $events_data['organizer'] = 0;
            } 
        }
      
        return $events_data;
    }

    /**
     * Render template on the frontend
     */
    public function render_template( $atts = array() ) {
        $events_data = $this->load_event_common_options( $atts );
        $events_data['event_atts'] = $atts;

        ob_start();

        wp_enqueue_script( 'jquery-ui-datepicker' );
        wp_enqueue_script( 'jquery-ui-slider' );
        wp_enqueue_style(
		    'em-front-jquery-ui',
		    EP_BASE_URL . '/includes/assets/css/jquery-ui.min.css',
		    false, EVENTPRIME_VERSION
        );
        // enqueue select2
		wp_enqueue_style(
			'em-front-select2-css',
			EP_BASE_URL . '/includes/assets/css/select2.min.css',
			false, EVENTPRIME_VERSION
		);
		wp_enqueue_script(
			'em-front-select2-js',
			EP_BASE_URL . '/includes/assets/js/select2.full.min.js',
			array( 'jquery' ), EVENTPRIME_VERSION
		);

        // load calendar library
        wp_enqueue_style(
            'ep-front-event-calendar-css',
            EP_BASE_URL . '/includes/assets/css/ep-calendar.min.css',
            false, EVENTPRIME_VERSION
        );

        wp_enqueue_script(
            'ep-front-event-moment-js',
            EP_BASE_URL . '/includes/assets/js/moment.min.js',
            array( 'jquery' ), EVENTPRIME_VERSION
        );

        wp_enqueue_script(
            'ep-front-event-calendar-js',
            EP_BASE_URL . '/includes/assets/js/ep-calendar.min.js',
            array( 'jquery' ), EVENTPRIME_VERSION
        );

        wp_enqueue_script(
            'ep-front-event-fulcalendar-moment-js',
            EP_BASE_URL . '/includes/assets/js/fullcalendar-moment.min.js',
            array( 'jquery' ), EVENTPRIME_VERSION
        );

        wp_enqueue_script(
            'ep-front-event-fulcalendar-local-js',
            EP_BASE_URL . '/includes/assets/js/locales-all.js',
            array( 'jquery' ), EVENTPRIME_VERSION
        );
    
        wp_enqueue_style(
            'ep-front-events-css',
            EP_BASE_URL . '/includes/events/assets/css/ep-frontend-events.css',
            false, EVENTPRIME_VERSION
        );
        wp_enqueue_script(
            'ep-front-events-js',
            EP_BASE_URL . '/includes/events/assets/js/ep-frontend-events.js',
            array( 'jquery' ), EVENTPRIME_VERSION
        );

        wp_localize_script(
            'ep-front-events-js', 
            'ep_frontend', 
            array(
                '_nonce'               => wp_create_nonce('ep-frontend-nonce'),
                'ajaxurl'              => admin_url( 'admin-ajax.php', null ),
                'filters_applied_text' => esc_html__( 'Filters Applied', 'eventprime-event-calendar-management' ),
                'nonce_error'          => esc_html__( 'Please refresh the page and try again.', 'eventprime-event-calendar-management' ),
                'event_attributes'     => $events_data['event_atts']
            )
        );

        // get calendar events
        $cal_events = array();
        if( ! empty( $events_data['events']->posts ) ) {
            $cal_events = $this->get_front_calendar_view_event( $events_data['events']->posts );
        }
        
        wp_localize_script(
            'ep-front-events-js', 
            'em_front_event_object', 
            array(
                'cal_events' => $cal_events,
                'view' => $events_data['display_style'],
                'local' => ep_get_calendar_locale()
            )
        );

        // event masonry view library
        wp_enqueue_script('masonry');
        wp_enqueue_style('masonry');

        // event slide view library
        wp_enqueue_style( 'ep-responsive-slides-css' );
        wp_enqueue_script( 'ep-responsive-slides-js' );
        
		ep_get_template_part( 'events/list', null, (object)$events_data );
		return ob_get_clean();
    }

    /**
     * Render detail page
     */
    public function render_detail_template( $atts = array() ) {
        $atts                = array_change_key_case( (array) $atts, CASE_LOWER );
        $event_id            = absint( $atts['id'] );
        $post                = get_post( $event_id );
        $events_data         = array();
        $events_data['post'] = $post;
        /* $events_data['hide_upcoming_events'] = ep_get_global_settings( 'shortcode_hide_upcoming_events' );
        if ( isset( $atts['upcoming'] ) ) {
            $events_data['hide_upcoming_events'] = 1;
            if ( 1 === $atts['upcoming'] ) {
                $events_data['hide_upcoming_events'] = 0;
            }
        } */
        $events_data['event'] = $this->get_single_event( $post->ID );
        //$events_data['instance'] = $this->instance();

        ob_start();

        wp_enqueue_style(
            'ep-event-owl-slider-style',
            EP_BASE_URL . '/includes/assets/css/owl.carousel.min.css',
            false, EVENTPRIME_VERSION
        );
        wp_enqueue_style(
            'ep-event-owl-theme-style',
            EP_BASE_URL . '/includes/assets/css/owl.theme.default.min.css',
            false, EVENTPRIME_VERSION
        );
        wp_enqueue_script(
            'ep-event-owl-slider-script',
            EP_BASE_URL . '/includes/assets/js/owl.carousel.min.js',
            array( 'jquery' ), EVENTPRIME_VERSION
        );

        wp_register_script( 'em-google-map', EP_BASE_URL . '/includes/assets/js/em-map.js', array( 'jquery' ), EVENTPRIME_VERSION );
        $gmap_api_key = ep_get_global_settings( 'gmap_api_key' );
        if($gmap_api_key) {
            wp_enqueue_script(
                'google_map_key', 
                'https://maps.googleapis.com/maps/api/js?key='.$gmap_api_key.'&libraries=places&callback=Function.prototype', 
                array(), EVENTPRIME_VERSION
            );
        }

        wp_enqueue_style( 'ep-responsive-slides-css' );
        wp_enqueue_script( 'ep-responsive-slides-js' );
        
        wp_enqueue_style(
            'ep-front-single-event-css',
            EP_BASE_URL . '/includes/events/assets/css/ep-frontend-single-event.css',
            false, EVENTPRIME_VERSION
        );

        wp_enqueue_script(
            'ep-event-single-script',
            EP_BASE_URL . '/includes/events/assets/js/ep-frontend-single-event.js',
            array( 'jquery' ), EVENTPRIME_VERSION
        );
        // localized script array
        $localized_script = array(
            'event'              => $events_data['event'],
            'subtotal_text'      => esc_html__( 'Subtotal', 'eventprime-event-calendar-management' ),
            'single_event_nonce' => wp_create_nonce( 'single-event-data-nonce' ),
            'event_booking_nonce'=> wp_create_nonce( 'event-booking-nonce' ),
            'starting_from_text' => esc_html__( 'Starting from', 'eventprime-event-calendar-management' ),
            'offer_applied_text' => esc_html__( 'Offers are applied in the next step.', 'eventprime-event-calendar-management' ),
            'no_offer_text'      => esc_html__( 'No offer available.', 'eventprime-event-calendar-management' ),
            'capacity_text'      => esc_html__( 'Capacity', 'eventprime-event-calendar-management' ),
            'ticket_left_text'   => esc_html__( 'tickets left!', 'eventprime-event-calendar-management' ),
            'allow_cancel_text'  => esc_html__( 'Cancellations Allowed', 'eventprime-event-calendar-management' ),
            'min_qty_text'       => esc_html__( 'Min Qnty', 'eventprime-event-calendar-management' ),
            'max_qty_text'       => esc_html__( 'Max Qnty', 'eventprime-event-calendar-management' ),
            'event_fees_text'    => esc_html__( 'Event Fees', 'eventprime-event-calendar-management' ),
            'ticket_now_btn_text'=> esc_html__( 'Get Tickets Now', 'eventprime-event-calendar-management' ),
            'multi_offfer_applied'=> esc_html__( 'Offers Applied', 'eventprime-event-calendar-management' ),
            'one_offfer_applied' => esc_html__( 'Offer Applied', 'eventprime-event-calendar-management' ),
            'book_ticket_text'   => esc_html__( 'Book Tickets', 'eventprime-event-calendar-management' ),
            'max_offer_applied'  => esc_html__( 'Max Offer Applied', 'eventprime-event-calendar-management' ),
            'ticket_disable_login' => esc_html__( 'You need to login to book this ticket.', 'eventprime-event-calendar-management' ),
            'ticket_disable_role' => esc_html__( 'You are not authorised to book this ticket.', 'eventprime-event-calendar-management' ),
            'no_ticket_message'  => esc_html__( 'You need to select ticket(s) first.', 'eventprime-event-calendar-management' ),
            'free_text'          => esc_html__( 'Free', 'eventprime-event-calendar-management' ),
        );
        // check for child events
        if( $events_data['event']->child_events && count( $events_data['event']->child_events ) > 0 ) {
            $cal_events = $this->get_front_calendar_view_event( $events_data['event']->child_events );
            // load calendar library
            wp_enqueue_style(
                'ep-front-event-calendar-css',
                EP_BASE_URL . '/includes/assets/css/ep-calendar.min.css',
                false, EVENTPRIME_VERSION
            );
            wp_enqueue_script(
                'ep-front-event-calendar-js',
                EP_BASE_URL . '/includes/assets/js/ep-calendar.min.js',
                false, EVENTPRIME_VERSION
            );
            wp_enqueue_script(
                'ep-front-event-fulcalendar-local-js',
                EP_BASE_URL . '/includes/assets/js/locales-all.js',
                array( 'jquery' ), EVENTPRIME_VERSION
            );
            $localized_script['cal_events'] = $cal_events;
            $localized_script['local'] = ep_get_calendar_locale();
        }

        wp_localize_script(
            'ep-event-single-script', 
            'em_front_event_object', 
            array(
                'em_event_data' => $localized_script,
            )
        );

        ep_get_template_part( 'events/single-event', null, (object)$events_data );
		return ob_get_clean();
    }

    /**
     * Render single post content
     */
    public function render_post_content() {
        $atts['id'] = get_the_ID();
        return $this->render_detail_template( $atts );
    }
    
    public function insert_event_post_data($post_data){
        $metaboxes_controllers = EventM_Factory_Service::ep_get_instance( 'EventM_Event_Admin_Meta_Boxes' );
        $post_id = 0;
        if(!empty($post_data)){
            $title = isset($post_data['name']) ? sanitize_text_field($post_data['name']) : '';
            $description = isset($post_data['description']) ? $post_data['description'] : '';
            $status = isset($post_data['status']) ? $post_data['status'] : 'publish';
            $post_id = wp_insert_post(array (
                'post_type' => EM_EVENT_POST_TYPE,
                'post_title' => $title,
                'post_content' => $description,
                'post_status' => $status
            ));
        }
        if($post_id){
            $post = get_post($post_id);
            $em_name = isset($post_data['name']) ? sanitize_text_field($post_data['name']) : '';
            $em_start_date = isset($post_data['em_start_date']) ? $post_data['em_start_date'] : '';
            $em_end_date = isset($post_data['em_end_date']) ? $post_data['em_end_date'] : '';
            $em_start_time = isset($post_data['em_start_time']) ? $post_data['em_start_time'] : '';
            $em_end_time = isset($post_data['em_end_time']) ? $post_data['em_end_time'] : '';
            $em_all_day = isset($post_data['em_all_day']) ? $post_data['em_all_day'] : 0;
            
            $em_ticket_price = isset($post_data['em_ticket_price']) ? sanitize_text_field($post_data['em_ticket_price']) : '';
            $em_venue = isset($post_data['em_venue']) ? $post_data['em_venue'] : '';
            $em_performer = isset($post_data['em_performer']) ? $post_data['em_performer'] : array();
            $em_organizer = isset($post_data['em_organizer']) ? $post_data['em_organizer'] : array();
            $em_event_type = isset($post_data['em_event_type']) ? $post_data['em_event_type'] : '';
            
            $em_enable_booking = isset($post_data['em_enable_booking']) ? $post_data['em_enable_booking'] : 'bookings_off';
            $em_custom_link = isset($post_data['em_custom_link']) ? $post_data['em_custom_link'] : '';
            $em_custom_meta = isset($post_data['em_custom_meta']) ? $post_data['em_custom_meta'] : array();
            
            $em_hide_start_time = isset( $post_data['em_hide_event_start_time'] ) ? 1 : 0;
            $em_hide_event_start_date = isset( $post_data['em_hide_event_start_date'] ) ? 1 : 0;
            $em_hide_event_end_time = isset( $post_data['em_hide_event_end_time'] ) ? 1 : 0;
            $em_hide_end_date = isset( $post_data['em_hide_end_date'] ) ? 1 : 0;
		
            update_post_meta($post_id, 'em_id', $post_id );
            update_post_meta($post_id, 'em_name', $em_name);
            update_post_meta($post_id, 'em_start_date', $em_start_date);
            update_post_meta($post_id, 'em_end_date', $em_end_date);
            update_post_meta($post_id, 'em_end_time', $em_end_time);
            update_post_meta($post_id, 'em_start_time', $em_start_time);
            update_post_meta($post_id, 'em_all_day', $em_all_day);
            
            update_post_meta($post_id, 'em_ticket_price', $em_ticket_price);
            update_post_meta($post_id, 'em_venue', $em_venue);
            update_post_meta($post_id, 'em_event_type', $em_event_type);
            update_post_meta($post_id, 'em_organizer', $em_organizer);
            update_post_meta($post_id, 'em_performer', $em_performer);
            
            update_post_meta($post_id, 'em_enable_booking', $em_enable_booking);
            update_post_meta($post_id, 'em_custom_link', $em_custom_link);
            update_post_meta($post_id, 'em_custom_meta', $em_custom_meta);
            
            update_post_meta( $post_id, 'em_hide_event_start_time', $em_hide_start_time );
            update_post_meta( $post_id, 'em_hide_event_start_date', $em_hide_event_start_date );
            update_post_meta( $post_id, 'em_hide_event_end_time', $em_hide_event_end_time );
            update_post_meta( $post_id, 'em_hide_end_date', $em_hide_end_date );
            
            
            // handel recurring events request
            if( isset( $post_data['em_enable_recurrence'] ) && $post_data['em_enable_recurrence'] == 1 ) {
                update_post_meta( $post_id, 'em_enable_recurrence', 1 );
                $em_recurrence_step = (isset( $post_data['em_recurrence_step'] ) && !empty( $post_data['em_recurrence_step'] ) ) ? absint( $post_data['em_recurrence_step'] ) : 1;
                update_post_meta( $post_id, 'em_recurrence_step', $em_recurrence_step );
                if( isset( $post_data['em_recurrence_interval'] ) && ! empty( $post_data['em_recurrence_interval'] ) ) { 
                    $em_recurrence_interval = sanitize_text_field( $post_data['em_recurrence_interval'] );
                    update_post_meta( $post_id, 'em_recurrence_interval', $em_recurrence_interval );

                    // first delete old child events
                    $metaboxes_controllers->ep_delete_child_events( $post_id );

                    $em_recurrence_ends = (isset( $post_data['em_recurrence_ends'] ) && !empty( $post_data['em_recurrence_ends'] ) ) ? $post_data['em_recurrence_ends'] : 'after';
                    update_post_meta( $post_id, 'em_recurrence_ends', $em_recurrence_ends );
                    $last_date_on = $stop_after = $recurrence_limit_timestamp = $start_date_only = '';
                    if( $em_recurrence_ends == 'on' ) {
                        $last_date_on = ep_date_to_timestamp( sanitize_text_field( $post_data['em_recurrence_limit'] ) );
                        update_post_meta( $post_id, 'em_recurrence_limit', $last_date_on );
                        $recurrence_limit = new DateTime( '@' . $last_date_on );
                        //$recurrence_limit->setTime( 0,0,0,0 );
                        $recurrence_limit_timestamp = $recurrence_limit->getTimestamp();
                        // update start date format
                        $start_date_only = new DateTime( '@' . $em_start_date );
                        $start_date_only->setTime( 0,0,0,0 );
                    }
                    if( $em_recurrence_ends == 'after' ) {
                        $stop_after = absint( $post_data['em_recurrence_occurrence_time'] );
                        update_post_meta( $post_id, 'em_recurrence_occurrence_time', $stop_after );
                    }

                    $data = array( 
                        'start_date' => $em_start_date,
                        'start_time' => $em_start_time,
                        'end_date' => $em_end_date,
                        'end_time' => $em_end_time,
                        'recurrence_step' => $em_recurrence_step,
                        'recurrence_interval' => $em_recurrence_interval,
                        'last_date_on' => $last_date_on,
                        'stop_after' => $stop_after,
                        'recurrence_limit_timestamp' => $recurrence_limit_timestamp,
                        'start_date_only' => $start_date_only,
                        'em_add_slug_in_event_title' => isset($post_data['em_add_slug_in_event_title']) ? $post_data['em_add_slug_in_event_title'] : '',
                        'em_event_slug_type_options' => isset($post_data['em_event_slug_type_options']) ? $post_data['em_event_slug_type_options'] : '',
                        'em_recurring_events_slug_format' => isset($post_data['em_recurring_events_slug_format']) ? $post_data['em_recurring_events_slug_format'] : '',
                        'em_selected_weekly_day' => isset($post_data['em_selected_weekly_day0']) ? $post_data['em_selected_weekly_day'] : '',
                        'em_recurrence_monthly_weekno' => isset($post_data['em_recurrence_monthly_weekno']) ? $post_data['em_recurrence_monthly_weekno'] : '',
                        'em_recurrence_monthly_fullweekday' => isset($post_data['em_recurrence_monthly_fullweekday']) ? $post_data['em_recurrence_monthly_fullweekday'] : '',
                        'em_recurrence_monthly_day' => isset($post_data['em_recurrence_monthly_day']) ? $post_data['em_recurrence_monthly_day'] : '',
                        'em_recurrence_yearly_weekno' => isset($post_data['em_recurrence_yearly_weekno']) ? $post_data['em_recurrence_yearly_weekno'] : '',
                        'em_recurrence_yearly_fullweekday' => isset($post_data['em_recurrence_yearly_fullweekday']) ? $post_data['em_recurrence_yearly_fullweekday'] : '',
                        'em_recurrence_yearly_monthday' => isset($post_data['em_recurrence_yearly_monthday']) ? $post_data['em_recurrence_yearly_monthday'] : '',
                        'em_recurrence_yearly_day' => isset($post_data['em_recurrence_yearly_day']) ? $post_data['em_recurrence_yearly_day'] : '',
                        'em_recurrence_advanced_dates' => isset($post_data['em_recurrence_advanced_dates']) ? json_encode($post_data['em_recurrence_advanced_dates']) : '',
                        'em_recurrence_selected_custom_dates' => isset($post_data['em_recurrence_selected_custom_dates']) ? $post_data['em_recurrence_selected_custom_dates'] : '',
                    );
                                    
                    switch( $em_recurrence_interval ) {
                        case 'daily':
                            $metaboxes_controllers->ep_event_daily_recurrence( $post, $data, $post_data );
                            break;
                        case 'weekly':
                            $metaboxes_controllers->ep_event_weekly_recurrence( $post, $data, $post_data );
                            break;
                        case 'monthly':
                            $metaboxes_controllers->ep_event_monthly_recurrence( $post, $data, $post_data );
                            break;
                        case 'yearly':
                            $metaboxes_controllers->ep_event_yearly_recurrence( $post, $data, $post_data );
                            break;
                        case 'advanced':
                            $metaboxes_controllers->ep_event_advanced_recurrence( $post, $data, $post_data );
                            break;
                        case 'custom_dates':
                            $metaboxes_controllers->ep_event_custom_dates_recurrence( $post, $data, $post_data );
                            break;
                    }
                }
            }
        }
        return $post_id;
    }

    /**
     * Get recurring event calendar for the single event page modal. 
     * 
     * @param array $recurring_events Recurring event data.
     * 
     * @return void
     */
    public function get_single_event_recurring_events_calendar( $recurring_events ) {
        if( ! empty( $recurring_events ) && count( $recurring_events ) > 0 ) {
            $cal_events = $this->get_front_calendar_view_event( $recurring_events );
            // load calendar library
            wp_enqueue_style(
                'ep-front-event-calendar-css',
                EP_BASE_URL . '/includes/assets/css/ep-calendar.min.css',
                false, EVENTPRIME_VERSION
            );
            wp_enqueue_script(
                'ep-front-event-calendar-js',
                EP_BASE_URL . '/includes/assets/js/ep-calendar.min.js',
                false, EVENTPRIME_VERSION
            );
            wp_enqueue_script(
                'ep-front-event-fulcalendar-local-js',
                EP_BASE_URL . '/includes/assets/js/locales-all.js',
                array( 'jquery' ), EVENTPRIME_VERSION
            );
            wp_localize_script(
                'ep-front-single-events-js', 
                'em_front_event_object', 
                array(
                    'cal_events' => $cal_events,
                    'local' => ep_get_calendar_locale()
                )
            );
        }
    }

    /**
     * Get event ticket categories
     * 
     * @param int $event_id Event ID.
     * 
     * @return array Ticket Category with related tickets.
     */
    public function get_event_ticket_category( $event_id ) {
        if( empty( $event_id ) ) return;

        global $wpdb;
        $cat_data = array();
        $cat_table_name = $wpdb->prefix.'eventprime_ticket_categories';
        $check_ticket_category_table = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s ",
                $wpdb->dbname,
                $cat_table_name
            )
        );
        if( ! empty( $check_ticket_category_table ) ) {
            $ticket_table_name = $wpdb->prefix.'em_price_options';
            $get_cat_data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $cat_table_name WHERE `event_id` = %d ORDER BY `priority` ASC", $event_id ) );
            if( ! empty( $get_cat_data ) && count( $get_cat_data ) > 0 ) {
                foreach( $get_cat_data as $category ) {
                    // get tickets from category id and event id
                    $get_ticket_data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $ticket_table_name WHERE `event_id` = %d AND `category_id` = %d ORDER BY `priority` ASC", $event_id, $category->id ) );
                    if( ! empty( $get_ticket_data ) && count( $get_ticket_data ) > 0 ) {
                        $category->tickets = $get_ticket_data;
                    }
                    $cat_data[] = $category;
                }
            }
        }
        return $cat_data;
    }

    /** Get event tickets where category_id = 0
     * 
     * @param int $event_id Event ID.
     * 
     * @return array Ticket Data.
     */
    public function get_event_solo_ticket( $event_id ) {
        global $wpdb;
        $ticket_data = array();
        $ticket_table_name = $wpdb->prefix.'em_price_options';
        $column = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s AND COLUMN_NAME = %s ",
                $wpdb->dbname,
                $ticket_table_name,
                'category_id'
            )
        );
        if( ! empty( $column ) ) {
            $ticket_data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $ticket_table_name WHERE `event_id` = %d AND `category_id` = 0 ORDER BY `priority` ASC", $event_id ) );
        }
        return $ticket_data;
    }

    /**
	 * Get event all tickets.
	 * 
	 * @param object $event Event.
	 * 
	 * @return array Tickets Data.
	 */
	public function get_event_all_tickets( $event ) {
		$all_tickets = array();
		if( ! empty( $event ) ) {
			// get tickets from category
			$ticket_categories = $event->ticket_categories;
			if( ! empty( $ticket_categories ) && count( $ticket_categories ) > 0 ) {
				foreach( $ticket_categories as $category ) {
					if( isset( $category->tickets ) && ! empty( $category->tickets ) ) {
						$all_tickets = array_merge( $all_tickets, $category->tickets );
					}
				}
			}
			// get individual tickets
			$solo_tickets = $event->solo_tickets;
			if( ! empty( $solo_tickets ) && count( $solo_tickets ) > 0 ) {
				$all_tickets = array_merge( $all_tickets, $solo_tickets );
			}
		}
		return $all_tickets;
	}

    /**
     * get ticket price range data.
     * 
     * @param array $event_category Event Catrgory Data.
     * 
     * @param array $event_tickets Event Solo Tickets.
     * 
     * @return array Price range data.
     */
    public function get_ticket_price_range( $event_categories, $event_tickets ) {
        $tickets = $price_range = array();
        // check event categories has ticket
        if( ! empty( $event_categories ) ) {
            foreach( $event_categories as $category ) {
                if( ! empty ( $category->tickets ) ) {
                    $tickets = array_merge( $tickets, $category->tickets );
                }
            }
        }
        // merge tickets
        if( ! empty( $event_tickets ) ) {
            $tickets = array_merge( $tickets, $event_tickets );
        }
        if( ! empty( $tickets ) && count( $tickets ) > 0 ) {
            if( count( $tickets ) > 1 ) {
                $price_range['multiple'] = 1;
                $prices = array();
                foreach( $tickets as $ticket ) {
                    $prices[] = $ticket->price;
                }
                $min_price = min( $prices );
                $max_price = max( $prices );
                $price_range['min'] = $min_price;
                $price_range['max'] = $max_price;
            } else{
                $price_range['multiple'] = 0;
                foreach( $tickets as $ticket ) {
                    $price_range['price'] = $ticket->price;
                }
            }
        }
        return $price_range;
    }
     
    /*
     * load more
     */
    public function get_events_loadmore(){
        $load_more = 1;
        $events_data = $this->load_event_common_options( $atts = array(), $load_more );

        ob_start();

        if( $events_data['calendar_view'] == 1 ) {
            // get calendar events
            $cal_events = $this->get_front_calendar_view_event( $events_data['events']->posts );

            wp_localize_script(
                'ep-front-events-js', 
                'em_front_event_object', 
                array(
                    'cal_events' => $cal_events,
                    'view' => $events_data['display_style'],
                    'local' => ep_get_calendar_locale()
                )
            );
        }
        ep_get_template_part( 'events/list-load', null, (object)$events_data );

	    $data['html'] = ob_get_clean();
        $data['paged'] = $events_data['paged'];
        return $data;
    }


    /**
     * Get image url
     * 
     * @param int $event_id Event ID.
     * 
     * @return string Image URL.
     */
    public function get_event_image_url( $event_id ) {
        $image_url = EP_BASE_URL . 'includes/assets/images/dummy_image.png';
        if ( has_post_thumbnail( $event_id ) ) {
            $image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $event_id ), 'large' )[0];
        }
        return $image_url;
    }

    /**
     * Check if booking on or closed.
     * 
     * @param array $tickets Tickets Data.
     * 
     * @param object $event Event Data.
     * 
     * @return string Booking Status.
     */
    public function check_for_booking_status( $tickets, $event ) {
        $booking_status = '';
        if( ! empty( $tickets ) ) {
            // get all event bookings
            $all_event_bookings = EventM_Factory_Service::get_event_booking_by_event_id( $event->em_id, true );
            $booked_tickets_data = $all_event_bookings['tickets'];
            $min_start = $max_end = ''; $price = $start_check_off = $total_caps = $total_bookings = 0;
            $buy_ticket_text = ep_global_settings_button_title('Buy Tickets');
            $booking_closed_text = ep_global_settings_button_title('Booking closed');
            $booking_start_on_text = ep_global_settings_button_title('Booking start on');
            $free_text = ep_global_settings_button_title('Free');
            $sold_out = ep_global_settings_button_title('Sold Out');
            foreach( $tickets as $ticket ) {
                // start date
                if( ! empty( $ticket->booking_starts ) ) {
                    $starts = json_decode( $ticket->booking_starts );
                    if( ! empty( $starts ) && isset( $starts->booking_type ) ) {
                        $booking_type = $starts->booking_type;
                        if( $booking_type == 'custom_date' ) {
                            if( empty( $start_check_off ) ) {
                                if( ! empty( $starts->start_date ) ){
                                    $book_start_date = $starts->start_date;
                                    if( ! empty( $starts->start_time ) ) {
                                        $book_start_date .= ' ' . $starts->start_time;
                                        $book_start_timestamp = ep_datetime_to_timestamp( $book_start_date );
                                    }else{ // if no time then convert only date
                                        $book_start_timestamp = ep_date_to_timestamp( $book_start_date );
                                    }
                                    if( empty( $min_start ) || $book_start_timestamp < $min_start ) {
                                        $min_start = $book_start_timestamp;
                                    }
                                } else{
                                    $start_check_off = 1;
                                    $min_start = '';
                                }
                            }
                        } else if( $booking_type == 'relative_date' ) {
                            $days = 1;
                            $start_booking_days_option = 'before';
                            $event_option = 'event_start';
                            if( ! empty( $starts->em_ticket_start_booking_days ) ) {
                                $days = $starts->em_ticket_start_booking_days;
                            }
                            if( ! empty( $starts->em_ticket_start_booking_days_option ) ) {
                                $start_booking_days_option = $starts->em_ticket_start_booking_days_option;
                            }
                            if( ! empty( $starts->em_ticket_start_booking_event_option ) ) {
                                $event_option = $starts->em_ticket_start_booking_event_option;
                            }
                            $days_string  = ' days';
                            if( $days == 1 ) {
                                $days_string = ' day';
                            }
                            // + or - days
                            $days_icon = '- ';
                            if( $start_booking_days_option == 'after' ) {
                                $days_icon = '+ ';
                            }
                            if( $event_option == 'event_start' ) {
                                $book_start_date = $event->em_start_date;
                                if( ! empty( $event->em_start_time ) ) {
                                    $book_start_date = ep_timestamp_to_date( $event->em_start_date, 'Y-m-d', 1 );
                                    $book_start_date .= ' ' . $event->em_start_time;
                                    $book_start_date = ep_datetime_to_timestamp( $book_start_date );
                                }
                                $book_start_timestamp = strtotime( $days_icon . $days . $days_string, $book_start_date );
                                if( empty( $min_start ) || $book_start_timestamp < $min_start ) {
                                    $min_start = $book_start_timestamp;
                                }
                            } elseif( $event_option == 'event_end' ) {
                                $book_start_date = $event->em_end_date;
                                if( ! empty( $event->em_end_time ) ) {
                                    $book_start_date = ep_timestamp_to_date( $event->em_end_date, 'Y-m-d', 1 );
                                    $book_start_date .= ' ' . $event->em_end_time;
                                    $book_start_date = ep_datetime_to_timestamp( $book_start_date );
                                }
                                $book_start_timestamp = strtotime( $days_icon . $days . $days_string, $book_start_date );
                                if( empty( $min_start ) || $book_start_timestamp < $min_start ) {
                                    $min_start = $book_start_timestamp;
                                }
                            } else{
                                if( ! empty( $event_option ) ) {
                                    $em_event_add_more_dates = $event->em_event_add_more_dates;
                                    if( ! empty( $em_event_add_more_dates ) && count( $em_event_add_more_dates ) > 0 ) {
                                        foreach( $em_event_add_more_dates as $more_dates ) {
                                            if( $more_dates['uid'] == $event_option ) {
                                                $book_start_timestamp = $more_dates['date'];
                                                if( ! empty( $more_dates['time'] ) ) {
                                                    $date_more = ep_timestamp_to_date( $more_dates['date'] );
                                                    $date_more .= ' ' . $more_dates['time'];
                                                    $book_start_timestamp = ep_datetime_to_timestamp( $date_more );
                                                }
                                                if( empty( $min_start ) || $book_start_timestamp < $min_start ) {
                                                    $min_start = $book_start_timestamp;
                                                }
                                                break;
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            $event_option = $starts->event_option;
                            if( $event_option == 'event_start' ) {
                                $book_start_timestamp = $event->em_start_date;
                                if( ! empty( $event->em_start_time ) ) {
                                    $book_start_timestamp = ep_timestamp_to_date( $event->em_start_date, 'Y-m-d', 1 );
                                    $book_start_timestamp .= ' ' . $event->em_start_time;
                                    $book_start_timestamp = ep_datetime_to_timestamp( $book_start_timestamp );
                                }
                                if( empty( $min_start ) || $book_start_timestamp < $min_start ) {
                                    $min_start = $book_start_timestamp;
                                }
                            } elseif( $event_option == 'event_ends' ) {
                                $book_start_timestamp = $event->em_end_date;
                                if( ! empty( $event->em_end_time ) ) {
                                    $book_start_timestamp = ep_timestamp_to_date( $event->em_end_date, 'Y-m-d', 1 );
                                    $book_start_timestamp .= ' ' . $event->em_end_time;
                                    $book_start_timestamp = ep_datetime_to_timestamp( $book_start_timestamp );
                                }
                                if( empty( $min_start ) || $book_start_timestamp < $min_start ) {
                                    $min_start = $book_start_timestamp;
                                }
                            } else{
                                if( ! empty( $event_option ) ) {
                                    $em_event_add_more_dates = $event->em_event_add_more_dates;
                                    if( ! empty( $em_event_add_more_dates ) && count( $em_event_add_more_dates ) > 0 ) {
                                        foreach( $em_event_add_more_dates as $more_dates ) {
                                            if( $more_dates['uid'] == $event_option ) {
                                                $book_start_timestamp = $more_dates['date'];
                                                if( ! empty( $more_dates['time'] ) ) {
                                                    $date_more = ep_timestamp_to_date( $more_dates['date'] );
                                                    $date_more .= ' ' . $more_dates['time'];
                                                    $book_start_timestamp = ep_datetime_to_timestamp( $date_more );
                                                }
                                                if( empty( $min_start ) || $book_start_timestamp < $min_start ) {
                                                    $min_start = $book_start_timestamp;
                                                }
                                                break;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                // end date
                if( ! empty( $ticket->booking_ends ) ) {
                    $ends = json_decode( $ticket->booking_ends );
                    if( ! empty( $ends ) && isset( $ends->booking_type ) ) {
                        $booking_type = $ends->booking_type;
                        if( $booking_type == 'custom_date' ) {
                            if( ! empty( $ends->end_date ) ) {
                                $book_end_date = $ends->end_date;
                                if( ! empty( $ends->end_time ) ) {
                                    $book_end_date .= ' ' . $ends->end_time;
                                    $book_end_timestamp = ep_datetime_to_timestamp( $book_end_date );
                                } else{ // if no time then convert only date
                                    $book_end_timestamp = ep_date_to_timestamp( $book_end_date );
                                }
                                if( empty( $max_end ) || $book_end_timestamp < $max_end ) {
                                    $max_end = $book_end_timestamp;
                                }
                            }
                        } else if( $booking_type == 'relative_date' ) {
                            $days = 1;
                            $end_booking_days_option = 'before';
                            $event_option = 'event_end';
                            if( ! empty( $ends->em_ticket_end_booking_days ) ) {
                                $days = $ends->em_ticket_end_booking_days;
                            }
                            if( ! empty( $ends->em_ticket_end_booking_days_option ) ) {
                                $end_booking_days_option = $ends->em_ticket_end_booking_days_option;
                            }
                            if( ! empty( $ends->em_ticket_end_booking_event_option ) ) {
                                $event_option = $ends->em_ticket_end_booking_event_option;
                            }
                            $days_string  = ' days';
                            if( $days == 1 ) {
                                $days_string = ' day';
                            }
                            // + or - days
                            $days_icon = '- ';
                            if( $end_booking_days_option == 'after' ) {
                                $days_icon = '+ ';
                            }
                            if( $event_option == 'event_end' ) {
                                $book_end_timestamp = $event->em_end_date;
                                if( ! empty( $event->em_end_time ) ) {
                                    $book_end_timestamp = ep_timestamp_to_date( $event->em_end_date, 'Y-m-d', 1 );
                                    $book_end_timestamp .= ' ' . $event->em_end_time;
                                    $book_end_timestamp = ep_datetime_to_timestamp( $book_end_timestamp );
                                }
                                $book_end_timestamp = strtotime( $days_icon . $days . $days_string, $book_end_timestamp );
                                if( empty( $max_end ) || $book_end_timestamp < $max_end ) {
                                    $max_end = $book_end_timestamp;
                                }
                            } elseif( $event_option == 'event_end' ) {
                                $book_end_timestamp = $event->em_end_date;
                                if( ! empty( $event->em_end_time ) ) {
                                    $book_end_timestamp = ep_timestamp_to_date( $event->em_end_date, 'Y-m-d', 1 );
                                    $book_end_timestamp .= ' ' . $event->em_end_time;
                                    $book_end_timestamp = ep_datetime_to_timestamp( $book_end_timestamp );
                                }
                                $book_end_timestamp = strtotime( $days_icon . $days . $days_string, $book_end_timestamp );
                                if( empty( $max_end ) || $book_end_timestamp < $max_end ) {
                                    $max_end = $book_end_timestamp;
                                }
                            } else{
                                if( ! empty( $event_option ) ) {
                                    $em_event_add_more_dates = $event->em_event_add_more_dates;
                                    if( ! empty( $em_event_add_more_dates ) && count( $em_event_add_more_dates ) > 0 ) {
                                        foreach( $em_event_add_more_dates as $more_dates ) {
                                            if( $more_dates['uid'] == $event_option ) {
                                                $book_end_timestamp = $more_dates['date'];
                                                if( ! empty( $more_dates['time'] ) ) {
                                                    $date_more = ep_timestamp_to_date( $more_dates['date'] );
                                                    $date_more .= ' ' . $more_dates['time'];
                                                    $book_end_timestamp = ep_datetime_to_timestamp( $date_more );
                                                }
                                                if( empty( $max_end ) || $book_end_timestamp < $max_end ) {
                                                    $max_end = $book_end_timestamp;
                                                }
                                                break;
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            $event_option = $ends->event_option;
                            if( $event_option == 'event_start' ) {
                                $book_end_timestamp = $event->em_start_date;
                                if( ! empty( $event->em_start_time ) ) {
                                    $book_end_timestamp = ep_timestamp_to_date( $event->em_start_date, 'Y-m-d', 1 );
                                    $book_end_timestamp .= ' ' . $event->em_start_time;
                                    $book_end_timestamp = ep_datetime_to_timestamp( $book_end_timestamp );
                                }
                                if( empty( $max_end ) || $book_end_timestamp < $max_end ) {
                                    $max_end = $book_end_timestamp;
                                }
                            } elseif( $event_option == 'event_ends' ) {
                                $book_end_timestamp = $event->em_end_date;
                                if( ! empty( $event->em_end_time ) ) {
                                    $book_end_timestamp = ep_timestamp_to_date( $event->em_end_date, 'Y-m-d', 1 );
                                    $book_end_timestamp .= ' ' . $event->em_end_time;
                                    $book_end_timestamp = ep_datetime_to_timestamp( $book_end_timestamp );
                                }
                                if( empty( $max_end ) || $book_end_timestamp < $max_end ) {
                                    $max_end = $book_end_timestamp;
                                }
                            } else{
                                if( ! empty( $event_option ) ) {
                                    $em_event_add_more_dates = $event->em_event_add_more_dates;
                                    if( ! empty( $em_event_add_more_dates ) && count( $em_event_add_more_dates ) > 0 ) {
                                        foreach( $em_event_add_more_dates as $more_dates ) {
                                            if( $more_dates['uid'] == $event_option ) {
                                                $book_end_timestamp = $more_dates['date'];
                                                if( ! empty( $more_dates['time'] ) ) {
                                                    $date_more = ep_timestamp_to_date( $more_dates['date'] );
                                                    $date_more .= ' ' . $more_dates['time'];
                                                    $book_end_timestamp = ep_datetime_to_timestamp( $date_more );
                                                }
                                                if( empty( $max_end ) || $book_end_timestamp < $max_end ) {
                                                    $max_end = $book_end_timestamp;
                                                }
                                                break;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                // price
                if( ! empty( $ticket->price ) ) {
                    $price = $ticket->price;
                }
                // ticket total capacity
                $total_caps += $ticket->capacity;
                // ticket booked capacity
                $total_bookings += ( ! empty( $booked_tickets_data[$ticket->id] ) ? $booked_tickets_data[$ticket->id] : 0 );
            }
            
            if( $total_caps > $total_bookings ) {
                // set booking status now
                if( empty( $min_start ) && empty( $max_end ) ) {
                    if( $price > 0 ) {
                        $booking_status = array( 'status' => 'on', 'message' => $buy_ticket_text );
                    } else{
                        $booking_status = array( 'status' => 'on', 'message' => $free_text );
                    }
                } else{
                    $current_time = ep_get_current_timestamp();
                    if( empty( $min_start ) ) {
                        if( $max_end <  $current_time ) {
                            $booking_status = array( 'status' => 'on', 'message' => $buy_ticket_text );
                        } else{
                            $booking_status = array( 'status' => 'off', 'message' => $booking_closed_text );
                        }
                    } elseif( $min_start <=  $current_time ) {
                        $booking_status = array( 'status' => 'on', 'message' => $buy_ticket_text );
                    } elseif( $min_start >  $current_time ) {
                        $booking_status = array( 'status' => 'not_started', 'message' => $booking_start_on_text . ' ' . ep_timestamp_to_date( $min_start, 'd M', 1 ) );
                    } elseif( $max_end <  $current_time ) {
                        $booking_status = array( 'status' => 'off', 'message' => $booking_closed_text );
                    } elseif( $current_time >= $min_start && $current_time <= $max_end ) {
                        if( $price > 0 ) {
                            $booking_status = array( 'status' => 'on', 'message' => $buy_ticket_text );
                        } else{
                            $booking_status = array( 'status' => 'on', 'message' => $free_text );
                        }
                    }
                }
            } else{
                $booking_status = array( 'status' => 'off', 'message' => $sold_out );
            }
        }

        return $booking_status;
    }

    /**
     * Check if ticket available for booking.
     * 
     * @param object $ticket Ticket Data.
     * 
     * @param object $event Event Data.
     * 
     * @return string Booking Status.
     */
    public function check_for_ticket_available_for_booking( $ticket, $event ) {
        $booking_status = '';
        if( ! empty( $ticket ) ) {
            $current_time = ep_get_current_timestamp();
            $min_start = $max_end = '';
            // start date
            if( ! empty( $ticket->booking_starts ) ) {
                $starts = json_decode( $ticket->booking_starts );
                if( ! empty( $starts->booking_type ) ) {
                    $booking_type = $starts->booking_type;
                    if( $booking_type == 'custom_date' ) {
                        if( ! empty( $starts->start_date ) ){
                            $book_start_date = $starts->start_date;
                            if( ! empty( $starts->start_time ) ) {
                                $book_start_date .= ' ' . $starts->start_time;
                                $book_start_timestamp = ep_datetime_to_timestamp( $book_start_date );
                            } else{
                                $book_start_timestamp = ep_date_to_timestamp( $book_start_date );
                            }
                            if( empty( $min_start ) || $book_start_timestamp < $min_start ) {
                                $min_start = $book_start_timestamp;
                            }
                        }
                    } elseif( $booking_type == 'relative_date' ) {
                        if( isset( $starts->days ) && isset( $starts->days_option ) && isset( $starts->event_option ) ) {
                            $days         = $starts->days;
                            $days_option  = $starts->days_option;
                            $event_option = $starts->event_option;
                            $days_string  = ' days';
                            if( $days == 1 ) {
                                $days_string = ' day';
                            }
                            // + or - days
                            $days_icon = '- ';
                            if( $days_option == 'after' ) {
                                $days_icon = '+ ';
                            }
                            if( $event_option == 'event_start' ) {
                                $book_start_date = ep_timestamp_to_date( $event->em_start_date );
                                if( ! empty( $event->em_start_time ) ) {
                                    $book_start_date .= ' ' . $event->em_start_time;
                                }
                                $book_start_timestamp = ep_datetime_to_timestamp( $book_start_date );
                                $min_start = strtotime( $days_icon . $days . $days_string, $book_start_timestamp );
                            } elseif( $event_option == 'event_end' ) {
                                $book_start_date = ep_timestamp_to_date( $event->em_end_date );
                                if( ! empty( $event->em_end_time ) ) {
                                    $book_start_date .= ' ' . $event->em_end_time;
                                }
                                $book_start_timestamp = ep_datetime_to_timestamp( $book_start_date );
                                $min_start = strtotime( $days_icon . $days . $days_string, $book_start_timestamp );
                            } else{
                                if( ! empty( $event_option ) ) {
                                    $em_event_add_more_dates = $event->em_event_add_more_dates;
                                    if( ! empty( $em_event_add_more_dates ) && count( $em_event_add_more_dates ) > 0 ) {
                                        foreach( $em_event_add_more_dates as $more_dates ) {
                                            if( $more_dates['uid'] == $event_option ) {
                                                $min_start = $more_dates['date'];
                                                if( ! empty( $more_dates['time'] ) ) {
                                                    $date_more = ep_timestamp_to_date( $more_dates['date'] );
                                                    $date_more .= ' ' . $more_dates['time'];
                                                    $min_start = ep_datetime_to_timestamp( $date_more );
                                                }
                                                break;
                                            }
                                        }
                                    }
                                    $min_start = strtotime( $days_icon . $days . $days_string, $min_start );
                                }
                            }
                        }
                    } else{
                        if( ! empty( $starts->event_option ) ) {
                            $event_option = $starts->event_option;
                            if( $event_option == 'event_start' ) {
                                $book_start_timestamp = $event->em_start_date;
                                if( ! empty( $event->em_start_time ) ) {
                                    $book_start_timestamp = ep_timestamp_to_date( $event->em_start_date );
                                    $book_start_timestamp .= ' ' . $event->em_start_time;
                                    $book_start_timestamp = ep_datetime_to_timestamp( $book_start_timestamp );
                                }
                                $min_start = $book_start_timestamp;
                            } elseif( $event_option == 'event_ends' ) {
                                $book_start_timestamp = $event->em_end_date;
                                if( ! empty( $event->em_end_time ) ) {
                                    $book_start_timestamp = ep_timestamp_to_date( $event->em_end_date );
                                    $book_start_timestamp .= ' ' . $event->em_end_time;
                                    $book_start_timestamp = ep_datetime_to_timestamp( $book_start_timestamp );
                                }
                                $min_start = $book_start_timestamp;
                            } else{
                                if( ! empty( $event_option ) ) {
                                    $em_event_add_more_dates = $event->em_event_add_more_dates;
                                    if( ! empty( $em_event_add_more_dates ) && count( $em_event_add_more_dates ) > 0 ) {
                                        foreach( $em_event_add_more_dates as $more_dates ) {
                                            if( $more_dates['uid'] == $event_option ) {
                                                $min_start = $more_dates['date'];
                                                if( ! empty( $more_dates['time'] ) ) {
                                                    $date_more = ep_timestamp_to_date( $more_dates['date'] );
                                                    $date_more .= ' ' . $more_dates['time'];
                                                    $min_start = ep_datetime_to_timestamp( $date_more );
                                                }
                                                break;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            // end date
            if( ! empty( $ticket->booking_ends ) ) {
                $ends = json_decode( $ticket->booking_ends );
                if( ! empty( $ends->booking_type ) ) {
                    $booking_type = $ends->booking_type;
                    if( $booking_type == 'custom_date' ) {
                        if( ! empty( $ends->end_date ) ){
                            $book_end_date = $ends->end_date;
                            if( ! empty( $ends->end_time ) ) {
                                $book_end_date .= ' ' . $ends->end_time;
                                $book_end_timestamp = ep_datetime_to_timestamp( $book_end_date );
                            } else{
                                $book_end_timestamp = ep_date_to_timestamp( $book_end_date );
                            }
                            if( empty( $max_end ) || $book_end_timestamp < $max_end ) {
                                $max_end = $book_end_timestamp;
                            }
                        }
                    } elseif( $booking_type == 'relative_date' ) {
                        $days         = ( ! empty( $ends->days ) ? $ends->days : 1 );
                        $days_option  = ( ! empty( $ends->days_option ) ? $ends->days_option : 'before' );
                        $event_option = ( ! empty( $ends->event_option ) ? $ends->event_option : 'event_ends' );
                        $days_string  = ' days';
                        if( $days == 1 ) {
                            $days_string = ' day';
                        }
                        // + or - days
                        $days_icon = '- ';
                        if( $days_option == 'after' ) {
                            $days_icon = '+ ';
                        }
                        if( $event_option == 'event_start' ) {
                            $book_end_timestamp = $event->em_start_date;
                            if( ! empty( $event->em_start_time ) ) {
                                $book_end_timestamp = ep_timestamp_to_date( $event->em_start_date );
                                $book_end_timestamp .= ' ' . $event->em_start_time;
                                $book_end_timestamp = ep_datetime_to_timestamp( $book_end_timestamp );
                            }
                            $max_end = strtotime( $days_icon . $days . $days_string, $book_end_timestamp );
                        } elseif( $event_option == 'event_ends' ) {
                            $book_end_timestamp = $event->em_end_date;
                            if( ! empty( $event->em_end_time ) ) {
                                $book_end_timestamp = ep_timestamp_to_date( $event->em_end_date );
                                $book_end_timestamp .= ' ' . $event->em_end_time;
                                $book_end_timestamp = ep_datetime_to_timestamp( $book_end_timestamp );
                            }
                            $max_end = strtotime( $days_icon . $days . $days_string, $book_end_timestamp );
                        } else{
                            if( ! empty( $event_option ) ) {
                                $em_event_add_more_dates = $event->em_event_add_more_dates;
                                if( ! empty( $em_event_add_more_dates ) && count( $em_event_add_more_dates ) > 0 ) {
                                    foreach( $em_event_add_more_dates as $more_dates ) {
                                        if( $more_dates['uid'] == $event_option ) {
                                            $max_end = $more_dates['date'];
                                            if( ! empty( $more_dates['time'] ) ) {
                                                $date_more = ep_timestamp_to_date( $more_dates['date'] );
                                                $date_more .= ' ' . $more_dates['time'];
                                                $max_end = ep_datetime_to_timestamp( $date_more );
                                            }
                                            break;
                                        }
                                    }
                                }
                                $max_end = strtotime( $days_icon . $days . $days_string, $max_end );
                            }
                        }
                    } else{
                        if( ! empty( $ends->event_option ) ) {
                            $event_option = $ends->event_option;
                            if( $event_option == 'event_start' ) {
                                $book_end_timestamp = $event->em_start_date;
                                if( ! empty( $event->em_start_time ) ) {
                                    $book_end_timestamp = ep_timestamp_to_date( $event->em_start_date );
                                    $book_end_timestamp .= ' ' . $event->em_start_time;
                                    $book_end_timestamp = ep_datetime_to_timestamp( $book_end_timestamp );
                                }
                                $max_end = $book_end_timestamp;
                            } elseif( $event_option == 'event_ends' ) {
                                $book_end_timestamp = $event->em_end_date;
                                if( ! empty( $event->em_end_time ) ) {
                                    $book_end_timestamp = ep_timestamp_to_date( $event->em_end_date );
                                    $book_end_timestamp .= ' ' . $event->em_end_time;
                                    $book_end_timestamp = ep_datetime_to_timestamp( $book_end_timestamp );
                                }
                                $max_end = $book_end_timestamp;
                            } else{
                                if( ! empty( $event_option ) ) {
                                    $em_event_add_more_dates = $event->em_event_add_more_dates;
                                    if( ! empty( $em_event_add_more_dates ) && count( $em_event_add_more_dates ) > 0 ) {
                                        foreach( $em_event_add_more_dates as $more_dates ) {
                                            if( $more_dates['uid'] == $event_option ) {
                                                $max_end = $more_dates['date'];
                                                if( ! empty( $more_dates['time'] ) ) {
                                                    $date_more = ep_timestamp_to_date( $more_dates['date'] );
                                                    $date_more .= ' ' . $more_dates['time'];
                                                    $max_end = ep_datetime_to_timestamp( $date_more );
                                                }
                                                break;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            // set booking status now
            if( empty( $min_start ) && empty( $max_end ) ) {
                $booking_status = array( 'status' => 'on', 'message' => esc_html__( 'Booking On', 'eventprime-event-calendar-management' ) );
            } elseif( ! empty( $min_start ) && empty( $max_end ) ) {
                $booking_status = array( 'status' => 'not_started', 'message' => esc_html__( 'Tickets available from', 'eventprime-event-calendar-management' ) . ' ' . ep_timestamp_to_date( $min_start, 'dS M', 1 ) );
            } elseif( $min_start >  $current_time ) {
                // if time on today then modify the string
                if( $min_start < strtotime('tomorrow') ) {
                    $booking_start_string = human_time_diff( $current_time, $min_start );
                    $booking_status = array( 'status' => 'not_started', 'message' => esc_html__( 'Tickets available after', 'eventprime-event-calendar-management' ) . ' ' . $booking_start_string );
                } else{
                    if( date( 'Y-m-d', $min_start ) == date( 'Y-m-d', $max_end ) ) {
                        $booking_status = array( 'status' => 'not_started', 'message' => esc_html__( 'Tickets available from', 'eventprime-event-calendar-management' ) . ' ' . ep_timestamp_to_date( $min_start, 'dS M H:i A', 1 ) . ' ' . esc_html__( 'to', 'eventprime-event-calendar-management' ) . ' ' . ep_timestamp_to_date( $max_end, 'h:i A', 1 ) );
                    } else{
                        $booking_status = array( 'status' => 'not_started', 'message' => esc_html__( 'Tickets available from', 'eventprime-event-calendar-management' ) . ' ' . ep_timestamp_to_date( $min_start, 'dS M', 1 ) . ' ' . esc_html__( 'to', 'eventprime-event-calendar-management' ) . ' ' . ep_timestamp_to_date( $max_end, 'dS M', 1 ) );
                    }
                }
            } elseif( ! empty( $max_end ) && $max_end <  $current_time ) {
                $booking_status = array( 'status' => 'off', 'message' => esc_html__( 'Tickets no longer available', 'eventprime-event-calendar-management' ), 'expire' => 1 );
            } else{
                $booking_status = array( 'status' => 'on', 'message' => esc_html__( 'Booking On', 'eventprime-event-calendar-management' ) );
            }
        }

        return $booking_status;
    }

    /**
     * Method for filter events
     * 
     * @return Filtered Event Html.
     */
    public function get_filtered_event_content() {
        $atts = ( ! empty( $_POST['event_atts'] ) ? (array)json_decode( stripslashes( $_POST['event_atts'] ) ) : array() );
        $events_data = $this->load_event_common_options( $atts );
        
        ob_start();

        if( $events_data['calendar_view'] == 1 ) {
            // get calendar events
            $cal_events = $this->get_front_calendar_view_event( $events_data['events']->posts );
        }
        
        ep_get_template_part( 'events/view-list-load', null, (object)$events_data );

	    $data['html'] = ob_get_clean();
        $data['paged'] = $events_data['paged'];
        if( $events_data['calendar_view'] == 1 ) {
            $data['cal_events'] = $cal_events;
        }
        return $data;
    }
    
    public function create_filter_query( $event_search_params, $args ){
        foreach( $event_search_params as $params ) {
            if( $params['label'] == 'Keyword' && ! empty( $params['value'] ) ) {
                $args['s'] = sanitize_text_field( $params['value'] );
            }
            if( $params['key'] == 'days' && $params['value'] == 'next_weekend') {
                $event_start_date = strtotime('next Saturday');
                $event_end_date = strtotime('next Sunday');
                $args['meta_query'][] = array(
                    'relation'     => 'AND',
                    array(
                        'key'      => 'em_start_date',
                        'value'    => $event_start_date,
                        'compare'  => '>=',
                        'type'     => 'NUMERIC'
                    ),
                    array(
                        'key'      => 'em_end_date',
                        'value'    => $event_end_date,
                        'compare'  => '<=',
                        'type'     => 'NUMERIC'
                    )
                );
            }

            if( $params['key'] == 'days' && $params['value'] =='next_month') {
                $event_start_date = strtotime('first day of +1 month');
                $event_end_date = strtotime('last day of +1 month');
                $args['meta_query'][] =array(
                    'relation'     => 'AND',
                    array(
                        'key'      => 'em_start_date',
                        'value'    => $event_start_date,
                        'compare'  => '>=',
                        'type'     => 'NUMERIC'
                    ),
                    array(
                        'key'      => 'em_end_date',
                        'value'    => $event_end_date,
                        'compare'  => '<=',
                        'type'     => 'NUMERIC'
                    )
                );
            }

            if( $params['key'] == 'days' && $params['value'] =='next_week') {
                $event_start_date = strtotime('monday next week');
                $event_end_date = strtotime('sunday next week');
                $args['meta_query'][] = array(
                    'relation'     => 'AND',
                    array(
                        'key'      => 'em_start_date',
                        'value'    => $event_start_date,
                        'compare'  => '>=',
                        'type'     => 'NUMERIC'
                    ),
                    array(
                        'key'      => 'em_end_date',
                        'value'    => $event_end_date,
                        'compare'  => '<=',
                        'type'     => 'NUMERIC'
                    )
                );
            }
            
            if( $params['key'] == 'date_from' && !empty($params['value'])) {
                $dates_query['date_from'] = $params['value'];    
            }
            
            if( $params['key'] == 'date_to' && !empty($params['value'])) {
                $dates_query['date_to'] = $params['value'];
            }
            if( $params['key'] == 'days' && !empty($params['value'])) {
                $dates_query['days'] = $params['value'];
            }
            
            if( $params['key'] == 'event_venues' && !empty($params['value'])) {
                $event_venue_id = (int)$params['value'];
                $args['meta_query'][] =array(
                    array(
                        'key'      => 'em_venue',
                        'value'    => $event_venue_id,
                        'compare'  => '='
                    )
                );
            }

            if( $params['key'] == 'event_types' && !empty($params['value'])) {
                $event_type_id = (int)$params['value'];
                $args['meta_query'][] = array(
                    array(
                        'key'      => 'em_event_type',
                        'value'    => $event_type_id,
                        'compare'  => '='
                    )
                );
            }
            
            if( $params['key'] == 'event_performers' && !empty($params['value'])) {
                $event_performer_ids = $params['value'];
                $filter_perfomers = array('relation'     => 'OR');
                foreach ($event_performer_ids as $performer_id){
                    $filter_perfomers[]= array(
                            'key'     => 'em_performer',
                            'value'   =>  serialize( strval ( $performer_id ) ),
                            'compare' => 'LIKE'
                        );
                }
                $args['meta_query'][] = $filter_perfomers;
                    
            }
            if( $params['key'] == 'event_organizers' && !empty($params['value'])) {
                $event_organizer_ids = $params['value'];
                $filter_organizers = array('relation'     => 'OR');
                foreach ($event_organizer_ids as $org_id){
                    $filter_organizers[]= array(
                            'key'     => 'em_organizer',
                            'value'   =>  serialize( strval ( $org_id ) ),
                            'compare' => 'LIKE'
                        );
                }
                $args['meta_query'][] = $filter_organizers;
                    
            }
        }
        if( ! empty( $dates_query ) ) {
            $format = ep_get_datepicker_format();
            if(isset($dates_query['date_from']) && isset($dates_query['date_to']) && isset($dates_query['days']) && strtolower($dates_query['days']) =='all'){
                $start_date = ep_date_to_timestamp( $dates_query['date_from'] . ' 12:00 AM' );
                $end_date = ep_datetime_to_timestamp( $dates_query['date_to'] . ' 11:59 PM' );
                $args['meta_query'][] = array(
                    array(
                        'key'     => 'em_start_date',
                        'value'   => array( $start_date, $end_date ),
                        'compare' => 'BETWEEN',
                        'type'    => 'NUMERIC'
                    )
                );

            } elseif( isset( $dates_query['date_from'] ) && isset( $dates_query['date_to'] ) && isset( $dates_query['days'] ) && $dates_query['days'] != 'all' ) {
                $dates = array();
                if( strtolower( $dates_query['days'] ) == 'weekends' ) {
                    $dates = $this->getweekendDays( $dates_query['date_from'], $dates_query['date_to'] );
                } else{
                    $dates = $this->getweekDays( $dates_query['date_from'], $dates_query['date_to'] );
                }
                $date_meta_query = array( 'relation' => 'OR' );
                if( ! empty( $dates ) ) {
                    foreach( $dates as $date ) {
                        $start_date = ep_date_to_timestamp( $dates_query['date_from'] . ' 12:00 AM' );
                        $end_date = ep_datetime_to_timestamp( $dates_query['date_to'] . ' 11:59 PM' );
                        $date_meta_query[] = array(
                                'key'     => 'em_start_date',
                                'value'   => array( $start_date, $end_date ),
                                'compare' => 'BETWEEN',
                                'type'    => 'NUMERIC'
                            );
                    }
                    $args['meta_query'][]= $date_meta_query;
                }

            } elseif( isset( $dates_query['date_from'] ) ) {
                $start_date = ep_date_to_timestamp( $dates_query['date_from'] . ' 12:00 AM' );
                $args['meta_query'][] = array(
                    'key'     => 'em_start_date',
                    'value'   => $start_date,
                    'compare' => '>=',
                    'type'    => 'NUMERIC'
                );
            } elseif( isset( $dates_query['date_to'] ) ) {
                $end_date = ep_datetime_to_timestamp( $dates_query['date_to'] . ' 11:59 PM' );
                $args['meta_query'][] = array(
                    'key'     => 'em_end_date',
                    'value'   => $end_date,
                    'compare' => '<=',
                    'type'    => 'NUMERIC'
                );
            }
        }
        return $args;        
    }

    public function getweekDays($startDate, $endDate) {
        $begin = strtotime($startDate);
        $end   = strtotime($endDate);
        $dates = array();
        if ($begin > $end) {
            return $dates;
        } else {
            while ($begin <= $end) {
                $day = date("N", $begin);
                if (!in_array($day, [6,7]) ){
                    $dates[]= date("Y-m-d h:i a", $begin);
                
                }
                $begin += 86400; // +1 day
            }
            return $dates;
        }
    }
    
    public function getweekendDays($startDate, $endDate) {
        $begin = strtotime($startDate);
        $end   = strtotime($endDate);
        $dates = array();
        if ($begin > $end) {
            return $dates;
        } else {
            while ($begin <= $end) {
                $day = date("N", $begin);
                if (!in_array($day, [1,2,3,4,5]) ){
                    $dates[]= date("Y-m-d h:i a", $begin);
                
                }
                $begin += 86400; // +1 day
            }
            return $dates;
        }
    }
    
    /**
     * Get specific data from posts
     */
    public function get_events_field_data( $fields = array(), $args = array() ) {
        $response = array();
        $default = array(
            'post_status' => 'publish',
            'order'       => 'ASC',
            'post_type'   => $this->post_type,
            'numberposts' => -1,
            'offset'      => 0,
            'meta_key'    => 'em_start_date',
            'orderby'     => 'meta_value',
        );
        $args = wp_parse_args( $args, $default );
        $posts = get_posts( $args );
        if( empty( $posts ) ) return array();
       
        foreach( $posts as $post ) {
            if( empty( $post ) || empty( $post->ID ) ) continue;
            $post_data = array();
            if( ! empty( $fields ) ) {
                if( in_array( 'id', $fields, true ) ) {
                    $post_data['id'] = get_post_meta( $post->ID, 'em_id', true );
                }
                if( in_array( 'name', $fields, true ) ) {
                    $post_data['name'] = get_post_meta( $post->ID, 'em_name', true );
                }
            }
            if( ! empty( $post_data ) ) {
                $response[] = $post_data;
            }
        }
        return $response;
    }

    /**
     * Load detail page on click on the other dates
     */
    public function ep_load_other_date_event_detail( $event_id ) {
        if( ! empty( $event_id ) ) {
            $post                = get_post( $event_id );
            $events_data         = array();
            $events_data['post'] = $post;
            
            $events_data['event'] = $this->get_single_event( $post->ID );

            //do_action( 'ep_dequeue_event_scripts', array( 'ep-event-single-script' ) );

            ob_start();

            wp_enqueue_style(
                'ep-event-owl-slider-style',
                EP_BASE_URL . '/includes/assets/css/owl.carousel.min.css',
                false, EVENTPRIME_VERSION
            );
            wp_enqueue_style(
                'ep-event-owl-theme-style',
                EP_BASE_URL . '/includes/assets/css/owl.theme.default.min.css',
                false, EVENTPRIME_VERSION
            );
            wp_enqueue_script(
                'ep-event-owl-slider-script',
                EP_BASE_URL . '/includes/assets/js/owl.carousel.min.js',
                array( 'jquery' ), EVENTPRIME_VERSION
            );

            wp_register_script( 'em-google-map', EP_BASE_URL . '/includes/assets/js/em-map.js', array( 'jquery' ), EVENTPRIME_VERSION );
            $gmap_api_key = ep_get_global_settings( 'gmap_api_key' );
            if($gmap_api_key) {
                wp_enqueue_script(
                    'google_map_key', 
                    'https://maps.googleapis.com/maps/api/js?key='.$gmap_api_key.'&libraries=places&callback=Function.prototype', 
                    array(), EVENTPRIME_VERSION
                );
            }

            wp_enqueue_style( 'ep-responsive-slides-css' );
            wp_enqueue_script( 'ep-responsive-slides-js' );
            
            wp_enqueue_style(
                'ep-front-single-event-css',
                EP_BASE_URL . '/includes/events/assets/css/ep-frontend-single-event.css',
                false, EVENTPRIME_VERSION
            );

            wp_enqueue_script(
                'ep-event-single-script',
                EP_BASE_URL . '/includes/events/assets/js/ep-frontend-single-event.js',
                array( 'jquery' ), EVENTPRIME_VERSION
            );
            // localized script array
            $get_ticket_now_text = ep_global_settings_button_title('Get Tickets Now');
            $localized_script = array(
                'event'              => $events_data['event'],
                'subtotal_text'      => esc_html__( 'Subtotal', 'eventprime-event-calendar-management' ),
                'single_event_nonce' => wp_create_nonce( 'single-event-data-nonce' ),
                'event_booking_nonce'=> wp_create_nonce( 'event-booking-nonce' ),
                'starting_from_text' => esc_html__( 'Starting from', 'eventprime-event-calendar-management' ),
                'offer_applied_text' => esc_html__( 'Offers are applied in the next step.', 'eventprime-event-calendar-management' ),
                'no_offer_text'      => esc_html__( 'No offer available.', 'eventprime-event-calendar-management' ),
                'capacity_text'      => esc_html__( 'Capacity', 'eventprime-event-calendar-management' ),
                'ticket_left_text'   => esc_html__( 'tickets left!', 'eventprime-event-calendar-management' ),
                'allow_cancel_text'  => esc_html__( 'Cancellations Allowed', 'eventprime-event-calendar-management' ),
                'min_qty_text'       => esc_html__( 'Min Qnty', 'eventprime-event-calendar-management' ),
                'max_qty_text'       => esc_html__( 'Max Qnty', 'eventprime-event-calendar-management' ),
                'event_fees_text'    => esc_html__( 'Event Fees', 'eventprime-event-calendar-management' ),
                'ticket_now_btn_text'=> esc_html( $get_ticket_now_text ),
                'multi_offfer_applied'=> esc_html__( 'Offers Applied', 'eventprime-event-calendar-management' ),
                'one_offfer_applied' => esc_html__( 'Offer Applied', 'eventprime-event-calendar-management' ),
                'book_ticket_text'   => esc_html__( 'Book Tickets', 'eventprime-event-calendar-management' ),
                'max_offer_applied'  => esc_html__( 'Max Offer Applied', 'eventprime-event-calendar-management' ),
            );
            // check for child events
            if( $events_data['event']->child_events && count( $events_data['event']->child_events ) > 0 ) {
                $cal_events = $this->get_front_calendar_view_event( $events_data['event']->child_events );
                // load calendar library
                wp_enqueue_style(
                    'ep-front-event-calendar-css',
                    EP_BASE_URL . '/includes/assets/css/ep-calendar.min.css',
                    false, EVENTPRIME_VERSION
                );
                wp_enqueue_script(
                    'ep-front-event-calendar-js',
                    EP_BASE_URL . '/includes/assets/js/ep-calendar.min.js',
                    false, EVENTPRIME_VERSION
                );
                wp_enqueue_script(
                    'ep-front-event-fulcalendar-local-js',
                    EP_BASE_URL . '/includes/assets/js/locales-all.js',
                    array( 'jquery' ), EVENTPRIME_VERSION
                );
                $localized_script['cal_events'] = $cal_events;
                $localized_script['local'] = ep_get_calendar_locale();
            }

            wp_localize_script(
                'ep-event-single-script', 
                'em_front_event_object', 
                array(
                    'em_event_data' => $localized_script,
                )
            );

            ep_get_template_part( 'events/single-event-page-load-other', null, (object)$events_data );
            return ob_get_clean();
        }
    }

    public function individual_events_shortcode_argument( $meta_query, $individual_events = '' ){
        if( $individual_events == 'yesterday' ){
            
            $yesterday_dt = new DateTime('yesterday');
            $yesterday_ts = strtotime( $yesterday_dt->format('Y-m-d H:i:s') );
            array_push($meta_query,array(
                'key' => 'em_start_date',
                'value' => $yesterday_ts,
                'compare' => '>=',
                'type'=>'NUMERIC'
            ));

            $today_dt = new DateTime('today');
            $today_ts = strtotime( $today_dt->format('Y-m-d H:i:s') );
            array_push($meta_query,array(
                'key' => 'em_start_date',
                'value' => $today_ts,
                'compare' => '<',
                'type'=>'NUMERIC'
            ));

        }
        if( $individual_events == 'today' ){

            $today_dt = new DateTime('today');
            $today_ts = strtotime( $today_dt->format('Y-m-d H:i:s') );
            array_push($meta_query,array(
                'key' => 'em_start_date',
                'value' => $today_ts,
                'compare' => '>=',
                'type'=>'NUMERIC'
            ));

            $tomorrow_dt = new DateTime('tomorrow');
            $tomorrow_ts = strtotime( $tomorrow_dt->format('Y-m-d H:i:s') );
            array_push($meta_query,array(
                'key' => 'em_start_date',
                'value' => $tomorrow_ts,
                'compare' => '<',
                'type'=>'NUMERIC'
            ));
        }
        if( $individual_events == 'tomorrow' ){

            $tomorrow_dt = new DateTime('tomorrow');
            $tomorrow_ts = strtotime( $tomorrow_dt->format('Y-m-d H:i:s') );
            array_push($meta_query,array(
                'key' => 'em_start_date',
                'value' => $tomorrow_ts,
                'compare' => '>=',
                'type'=>'NUMERIC'
            ));

            $tda_tomorrow_dt = new DateTime('tomorrow');
            $tda_tomorrow_dt->modify('+1 day');
            $tda_tomorrow_ts = strtotime( $tda_tomorrow_dt->format('Y-m-d H:i:s') );
            array_push($meta_query,array(
                'key' => 'em_start_date',
                'value' => $tda_tomorrow_ts,
                'compare' => '<',
                'type'=>'NUMERIC'
            ));
        }

        if( $individual_events == 'this month' ){

            $this_month_dt = new DateTime('first day of this month');
            $this_month_ts = strtotime( $this_month_dt->format('Y-m-d 00:00:00') );
            array_push($meta_query,array(
                'key' => 'em_start_date',
                'value' => $this_month_ts,
                'compare' => '>=',
                'type'=>'NUMERIC'
            ));

            $next_month_dt = new DateTime('first day of next month');
            $next_month_ts = strtotime( $next_month_dt->format('Y-m-d 00:00:00') );
            array_push($meta_query,array(
                'key' => 'em_start_date',
                'value' => $next_month_ts,
                'compare' => '<',
                'type'=>'NUMERIC'
            ));
        }
       
        return $meta_query;
        
    }

}
