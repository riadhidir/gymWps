<?php
/**
 * EventPrime Utility Functions
 */

defined( 'ABSPATH' ) || exit;

/**
 * Return social sharing fields
 */
function ep_social_sharing_fields() {
    $social_sharing_fields = array( 'facebook' => 'Facebook', 'instagram' => 'Instagram', 'linkedin' => 'Linkedin', 'twitter' => 'Twitter' );
    return $social_sharing_fields;
}

/**
 * Return week day in short
 */
function ep_get_week_day_short() {
    $short_week_days = array(
        esc_html__( 'S', 'eventprime-event-calendar-management' ),
        esc_html__( 'M', 'eventprime-event-calendar-management' ),
        esc_html__( 'T', 'eventprime-event-calendar-management' ),
        esc_html__( 'W', 'eventprime-event-calendar-management' ),
        esc_html__( 'T', 'eventprime-event-calendar-management' ),
        esc_html__( 'F', 'eventprime-event-calendar-management' ),
        esc_html__( 'S', 'eventprime-event-calendar-management' ),
    );
    return $short_week_days;
}

/**
 * Return week day in medium
 */
function ep_get_week_day_medium() {
    $medium_week_days = array(
        'mon' => esc_html__( 'Mon', 'eventprime-event-calendar-management' ),
        'tue' => esc_html__( 'Tue', 'eventprime-event-calendar-management' ),
        'wed' => esc_html__( 'Wed', 'eventprime-event-calendar-management' ),
        'thu' => esc_html__( 'Thu', 'eventprime-event-calendar-management' ),
        'fri' => esc_html__( 'Fri', 'eventprime-event-calendar-management' ),
        'sat' => esc_html__( 'Sat', 'eventprime-event-calendar-management' ),
        'sun' => esc_html__( 'Sun', 'eventprime-event-calendar-management' )
    );
    return $medium_week_days;
}
/**
 * Return full week day
 */
function ep_get_week_day_full() {
    $full_week_days = array(
        esc_html__( 'Sunday', 'eventprime-event-calendar-management' ),
        esc_html__( 'Monday', 'eventprime-event-calendar-management' ),
        esc_html__( 'Tuesday', 'eventprime-event-calendar-management' ),
        esc_html__( 'Wednesday', 'eventprime-event-calendar-management' ),
        esc_html__( 'Thursday', 'eventprime-event-calendar-management' ),
        esc_html__( 'Friday', 'eventprime-event-calendar-management' ),
        esc_html__( 'Saturday', 'eventprime-event-calendar-management' ),
    );
    return $full_week_days;
}

/**
 * Return week number
 */
function ep_get_week_number() {
    $week_number = array(
        '1' => esc_html__( 'First', 'eventprime-event-calendar-management' ),
        '2' => esc_html__( 'Second', 'eventprime-event-calendar-management' ),
        '3' => esc_html__( 'Third', 'eventprime-event-calendar-management' ),
        '4' => esc_html__( 'Fourth', 'eventprime-event-calendar-management' ),
        '5' => esc_html__( 'Last', 'eventprime-event-calendar-management' ),
    );
    return $week_number;
}

/**
 * Return current week no.
 */
function ep_get_current_week_no() {
    $date = date( "Y-m-d" );
    $first_of_month = date( "Y-m-01", strtotime( $date ) );
    $current_week_no = intval( date( "W", strtotime( $date ) ) ) - intval( date( "W", strtotime( $first_of_month ) ) ) + 1;
    return $current_week_no;
}

/**
 * Return month name
 */
function ep_get_month_name() {
    $month_name = array(
        '1'  => esc_html__( 'January', 'eventprime-event-calendar-management' ),
        '2'  => esc_html__( 'February', 'eventprime-event-calendar-management' ),
        '3'  => esc_html__( 'March', 'eventprime-event-calendar-management' ),
        '4'  => esc_html__( 'April', 'eventprime-event-calendar-management' ),
        '5'  => esc_html__( 'May', 'eventprime-event-calendar-management' ),
        '6'  => esc_html__( 'June', 'eventprime-event-calendar-management' ),
        '7'  => esc_html__( 'July', 'eventprime-event-calendar-management' ),
        '8'  => esc_html__( 'August', 'eventprime-event-calendar-management' ),
        '9'  => esc_html__( 'September', 'eventprime-event-calendar-management' ),
        '10' => esc_html__( 'October', 'eventprime-event-calendar-management' ),
        '11' => esc_html__( 'November', 'eventprime-event-calendar-management' ),
        '12' => esc_html__( 'December', 'eventprime-event-calendar-management' ),
    );
    return $month_name;
}

/**
 * Sanitize phone number
 * 
 * @param string $phone Phone
 * 
 * @return string Sanitized phone number
 */
function ep_sanitize_phone_number( $phone ) {
	return preg_replace( '/[^\d+]/', '', $phone );
}

/**
 * Return all pages list
 */
function ep_get_all_pages_list() {
    $publish_pages = array();
    $args = array(
        'sort_order' => 'asc',
        'sort_column' => 'post_title',
        'post_type' => 'page',
        'post_status' => 'publish'
    ); 
    $pages = get_pages($args);
    if(count($pages)){
        foreach($pages as $page){
            $publish_pages[$page->ID] = $page->post_title; 
        }
    }
    return $publish_pages;
}

/**
 * Return all user roles
 */
function ep_get_all_user_roles() {
    global $wp_roles;
    $userRoles = $wp_roles->roles;
    $roles = array();
    if( ! empty( $userRoles ) ) {
        foreach ( $userRoles as $key => $value ) {
            $roles[$key] = $value['name'];
        }
    }
    return $roles;
}

/**
 * Get template part(Load eventprime template)
 * 
 * @param string $slug Slug.
 * 
 * @param string $name View Name.
 * 
 * @param array $data Data.
 * 
 * @param string $ext_path File path of extension.
 */
function ep_get_template_part( $slug, $name = null, $data = array(), $ext_path = null ) {
    $file = '';
	if ( isset( $name ) ) {
		$template = $slug . '-' . $name . '.php';
        // check file in yourtheme/eventprime
        $file = locate_template( [ 'eventprime/' . $template ], false, false );
        
        if( ! $file ) {
            if( !empty( $ext_path ) ) {
                $file = $ext_path . "/views/". $template;
            } else{
                $template = ep_add_views_path_template( $template );
                $file = EP_INCLUDES_DIR . "/". $template;
            }
        }
	}

    if( ! $file ) {
	    $template = $slug . '.php';
        // check file in yourtheme/eventprime
        $file = locate_template( [ 'eventprime/' . $template ], false, false );
        
        if( ! $file ) {
            if( !empty( $ext_path ) ) {
                $file = $ext_path . "/views/". $template;
            } else{
                $template = ep_add_views_path_template( $template );
                $file = EP_INCLUDES_DIR . "/". $template;
            }
        }
    }

    // Allow 3rd party plugins to filter template file from their plugin.
	$file = apply_filters( 'ep_get_template_part', $file, $slug, $name );

    if ( $file ) {
		load_template( $file, false, $data );
	}
}

/**
 * Add views path to template path
 * 
 * @param string $template Template Path.
 * 
 * @return string
 */
function ep_add_views_path_template( $template ) {
    $pos = strpos( $template, '/' );
    if ($pos !== false) {
        $template = substr_replace($template, '/views/', $pos, strlen( '/' ));
    }
    return $template;
}

/**
 * Return column size for frontend views.
 * 
 * @param number $number Number
 * 
 * @return number Column Number
 */
function ep_check_column_size( $number = 3 ){
    switch($number){
        case 1 : $cols = 12;
        break;
        case 2 : $cols = 6;
        break;
        case 3 : $cols = 4;
        break;
        case 4 : $cols = 3;
        break;
        case 6 : $cols = 2;
        break;
        default: $cols = 4; 
    }
    return $cols;
}

/**
 * Echo print die
 */
function epd( $val ) {
    echo "<pre>";
        print_r($val);
    echo "</pre>";
    die;
}

/**
 * Convert the Hex color to RGB
 * 
 * @param string $color Color Code.
 * 
 * @param int|float $opacity Opecity.
 * 
 * @return string RGBA code.
 */
function ep_hex2rgba( $color, $opacity = false ) {
    $default = 'rgb(0,0,0)';
    if( empty( $color ) )
        return $default; 
 
    if ( $color[0] == '#' ) {
        $color = substr( $color, 1 );
    }
    if ( strlen( $color ) == 6 ) {
        $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
    } elseif ( strlen( $color ) == 3 ) {
        $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
    } else {
        return $default;
    }
    $rgb =  array_map( 'hexdec', $hex );
    if( $opacity ){
        if( abs( $opacity ) == 1 )
            $opacity = 1.0;
        $output = 'rgba( '.implode( ",",$rgb ).','.$opacity.' )';
    } else {
        $output = 'rgb( '.implode( ",",$rgb ).' )';
    }

    return $output;
}

/**
 * Get global settings data
 * 
 * @param string $meta Meta Key.
 * 
 * @return string Meta Value.
 */
function ep_get_global_settings( $meta = null ) {
    // Load global setting array from options table
    $global_options = get_option( EM_GLOBAL_SETTINGS );
    // Check if option exists 
    if ( ! empty( $global_options ) ) {
        if ( $meta !== null ) {
            if( isset( $global_options->{$meta} ) ){
                return $global_options->{$meta};
            }else {
                // Option does not exists
                return false;
            }
        }
        return $global_options;
    }
    return false;
}

/**
 * Get custom page url.
 * 
 * @param string $page Page Name
 * 
 * @param int $id Post|Term id
 * 
 * @param string $slug Page query slug
 * 
 * @param string $type Type
 * 
 * @return string URL
 */
function ep_get_custom_page_url( $page, $id = null, $slug = null, $type = 'post', $taxonomy = '' ) {
    $url = get_permalink( ep_get_global_settings( $page ) );
    if( ! empty( $id ) ) {
        if( ! empty( $slug ) ) {
            $url = add_query_arg( $slug, $id, $url );
        }
        $enable_seo_urls = ep_get_global_settings( 'enable_seo_urls' );
        if( ! empty( $enable_seo_urls ) ) {
            $url = get_permalink( $id );
            if( $type == 'term' ) {
                if( empty( $taxonomy ) ) {
                    $taxonomy = get_term( $id )->taxonomy;
                }
                $url = get_term_link( $id, $taxonomy );
            }
        }
    }
    return $url;
}
/*
 * Time zone offset
 */

function get_site_timezone_from_offset( $offset ){
    $offset = (string) $offset;
    $timezones = array(
        '-12' => 'Pacific/Auckland',
        '-11.5' => 'Pacific/Auckland', // Approx
        '-11' => 'Pacific/Apia',
        '-10.5' => 'Pacific/Apia', // Approx
        '-10' => 'Pacific/Honolulu',
        '-9.5' => 'Pacific/Honolulu', // Approx
        '-9' => 'America/Anchorage',
        '-8.5' => 'America/Anchorage', // Approx
        '-8' => 'America/Los_Angeles',
        '-7.5' => 'America/Los_Angeles', // Approx
        '-7' => 'America/Denver',
        '-6.5' => 'America/Denver', // Approx
        '-6' => 'America/Chicago',
        '-5.5' => 'America/Chicago', // Approx
        '-5' => 'America/New_York',
        '-4.5' => 'America/New_York', // Approx
        '-4' => 'America/Halifax',
        '-3.5' => 'America/Halifax', // Approx
        '-3' => 'America/Sao_Paulo',
        '-2.5' => 'America/Sao_Paulo', // Approx
        '-2' => 'America/Sao_Paulo',
        '-1.5' => 'Atlantic/Azores', // Approx
        '-1' => 'Atlantic/Azores',
        '-0.5' => 'UTC', // Approx
        '0' => 'UTC',
        '0.5' => 'UTC', // Approx
        '1' => 'Europe/Paris',
        '1.5' => 'Europe/Paris', // Approx
        '2' => 'Europe/Helsinki',
        '2.5' => 'Europe/Helsinki', // Approx
        '3' => 'Europe/Moscow',
        '3.5' => 'Europe/Moscow', // Approx
        '4' => 'Asia/Dubai',
        '4.5' => 'Asia/Tehran',
        '5' => 'Asia/Karachi',
        '5.5' => 'Asia/Kolkata',
        '5.75' => 'Asia/Katmandu',
        '6' => 'Asia/Yekaterinburg',
        '6.5' => 'Asia/Yekaterinburg', // Approx
        '7' => 'Asia/Krasnoyarsk',
        '7.5' => 'Asia/Krasnoyarsk', // Approx
        '8' => 'Asia/Shanghai',
        '8.5' => 'Asia/Shanghai', // Approx
        '8.75' => 'Asia/Tokyo', // Approx
        '9' => 'Asia/Tokyo',
        '9.5' => 'Asia/Tokyo', // Approx
        '10' => 'Australia/Melbourne',
        '10.5' => 'Australia/Adelaide',
        '11' => 'Australia/Melbourne', // Approx
        '11.5' => 'Pacific/Auckland', // Approx
        '12' => 'Pacific/Auckland',
        '12.75' => 'Pacific/Apia', // Approx
        '13' => 'Pacific/Apia',
        '13.75' => 'Pacific/Honolulu', // Approx
        '14' => 'Pacific/Honolulu',
    );
    $timezone = isset($timezones[$offset]) ? $timezones[$offset] : NULL;
    return $timezone;
}

function get_gmt_offset( $timezone = NULL ) {
    if( trim( $timezone ) != '' and $timezone != 'global'){
        $UTC = new DateTimeZone('UTC');
        $TZ = new DateTimeZone( $timezone );

        $gmt_offset_seconds = $TZ->getOffset( ( new DateTime( 'now', $UTC ) ) );
        $gmt_offset = ( $gmt_offset_seconds / HOUR_IN_SECONDS );
    }
    else $gmt_offset = get_option('gmt_offset');

    $minutes = $gmt_offset * 60;
    $hour_minutes = sprintf( "%02d", $minutes % 60 );

    // Convert the hour into two digits format
    $h = ( $minutes - $hour_minutes ) / 60;
    $hours = sprintf( "%02d", abs( $h ) );

    // Add - sign to the first of hour if it's negative
    if( $h < 0 ) $hours = '-'.$hours;

    return (substr( $hours, 0, 1 ) == '-' ? '' : '+' ).$hours.':'.( ( (int) $hour_minutes < 0 ) ? abs( $hour_minutes ) : $hour_minutes );
}

/*
 * Set get time zone
 */
function ep_get_site_timezone(){
    $userTimezone = get_option( 'timezone_string' );
    if( empty( $userTimezone ) ) {
        $offset  = (float) get_option( 'gmt_offset' );
        $userTimezone = get_site_timezone_from_offset( $offset );
    }
    if( $userTimezone == 'UTC' ) {
        $userTimezone = date_default_timezone_get();
    }
    return $userTimezone;
}

/**
 * Check if RM is activated
 */
function ep_is_registration_magic_active() {
    if ( defined( "REGMAGIC_BASIC" ) || defined( "REGMAGIC_GOLD" ) )
        return true;
    else
        return false;
}

/**
 * Convert date to timestamp
 * 
 * @param string $date Date.
 * 
 * @param string $format Date Format.
 * 
 * @return int Timestamp.
 */
function ep_date_to_timestamp( $date, $format = 'Y-m-d' ){
    $format = ep_get_datepicker_format();
    $date = DateTime::createFromFormat( $format, $date );
    if( empty( $date ) )
        return false;
    return $date->getTimestamp();
}

/**
 * Convert timestamp to date
 * 
 * @param int $timestamp Timestamp.
 * 
 * @param string $format Date Format.
 * 
 * @param int $strict Global Settings.
 * 
 * @return string $date Date.
 */
function ep_timestamp_to_date( $timestamp, $format = 'Y-m-d', $strict = 0 ){
    $date = '';
    if( ! empty( $timestamp ) ) {
        if( empty( $strict ) ) { // Not use setting format if $strict = 1
            $format = ep_get_datepicker_format();
        }
        if( ! is_int( $timestamp ) ) {
            $timestamp = (int)$timestamp;
        }
        $date = date_i18n( $format, $timestamp );
    }
    return $date;
}

/**
 * Convert datetime to timestamp
 */
function ep_datetime_to_timestamp( $datetime, $format = 'Y-m-d', $timezone = '', $full_date = 0 ){
    $format = ep_get_datepicker_format();
    $timepicker_format_arr = ep_get_global_settings( 'time_format' );
    if( ! empty( $timepicker_format_arr ) && ! empty( $format ) ) {
        $timepicker_format_arr = explode(':', $timepicker_format_arr);
        $timeformat = $timepicker_format_arr[1];
        if( $timeformat == 'H' ) {
            $format = $format . ' H:i A';
        } else {
            $format = $format . ' h:i A';
        }
    }
    
    if( ! empty( $timezone ) ) {
        $date = DateTime::createFromFormat( $format, $datetime, new DateTimeZone( $timezone ) );
    } else{
        $site_timezone = ep_get_site_timezone();
        if( ! empty( $site_timezone ) ) {
            $date = DateTime::createFromFormat( $format, $datetime, new DateTimeZone( $site_timezone ) );
        } else{
            $date = DateTime::createFromFormat( $format, $datetime );
        }
    }
    if( empty( $date ) ) return false;

    if( ! empty( $full_date ) ) {
        return $date;
    }
    
    return $date->getTimestamp();
}

/**
 * Convert timestamp to date
 */
function ep_timestamp_to_datetime( $timestamp, $format = 'Y-m-d h:i a', $strict = 0 ){
    $datetime = '';
    if( ! empty( $timestamp ) ) {
        if( empty( $strict ) ) {
            $format = ep_get_datepicker_format();
            $format = $format . ' h:i a';
        }
        $datetime = date_i18n( $format, $timestamp );
    }
    return $datetime;
}

/** Show taxonomy dropdown for the post Metabox
 * 
 * @param object $post Post.
 * 
 * @param array $box Metabox.
 */
function ep_taxonomy_select_meta_box( $post, $box ) {
    $defaults = array( 'taxonomy' => $box['args']['taxonomy'] );
  
    if ( ! isset( $box['args'] ) || !is_array( $box['args'] ) )
        $args = array();
    else
        $args = $box['args'];
  
    extract( wp_parse_args( $args, $defaults ), EXTR_SKIP );
    $tax = get_taxonomy( $taxonomy );
    $selected = wp_get_object_terms( $post->ID, $taxonomy, array( 'fields' => 'ids' ) );
    $hierarchical = $tax->hierarchical;
    $custom_venue_class = ( $taxonomy == 'em_venue' ) ? 'ep_event_venue_meta_box' : '';?>
    <div id="taxonomy-<?php echo $taxonomy; ?>" class="selectdiv"><?php 
        if ( current_user_can( $tax->cap->edit_terms ) ) {
            if( $taxonomy == 'em_venue' ) {?>
                <select name="<?php echo "tax_input[$taxonomy][]"; ?>" class="widefat <?php echo esc_attr( $custom_venue_class );?>">
                    <option value="0"><?php echo esc_html__( 'Select', 'eventprime-event-calendar-management' ). " " . $box['title'];?></option>
                    <?php foreach ( get_terms( $taxonomy, array( 'hide_empty' => false ) ) as $term ){
                        $venue_data = EventM_Factory_Service::ep_get_venue_by_id( $term->term_id ); ?>
                        <option value="<?php echo esc_attr( $term->term_id ); ?>" <?php echo selected( $term->term_id, count( $selected ) >= 1 ? $selected[0] : '' ); ?> data-term_id="<?php echo esc_attr( $term->term_id );?>" data-type="<?php echo esc_attr( $venue_data->em_type );?>" data-event_id="<?php echo esc_attr( $post->ID );?>"><?php echo esc_html( $term->name ); ?></option>
                    <?php } ?>
                </select>
                <span class="spinner" id="ep_event_venue_spinner" style="float: none; display: none;"></span><?php
            } elseif ( $hierarchical ) {
                wp_dropdown_categories( array(
                    'taxonomy'        => $taxonomy,
                    'class'           => 'widefat',
                    'hide_empty'      => 0, 
                    'name'            => "tax_input[$taxonomy][]",
                    'selected'        => count($selected) >= 1 ? $selected[0] : '',
                    'orderby'         => 'name', 
                    'hierarchical'    => 1, 
                    'show_option_all' => esc_html__( 'Select', 'eventprime-event-calendar-management' ). " " . $box['title']
                ));
            } else {?>
                <select name="<?php echo "tax_input[$taxonomy][]"; ?>" class="widefat">
                    <option value="0"><?php echo esc_html__( 'Select', 'eventprime-event-calendar-management' ). " " . $box['title'];?></option>
                    <?php foreach ( get_terms( $taxonomy, array( 'hide_empty' => false ) ) as $term): ?>
                        <option value="<?php echo esc_attr( $term->slug ); ?>" <?php echo selected($term->term_id, count($selected) >= 1 ? $selected[0] : ''); ?>><?php echo esc_html($term->name); ?></option>
                    <?php endforeach; ?>
                </select><?php 
            }
        }?>
    </div><?php
}

/**
 * Get event calendar views
 * 
 * @return array Calendar views.
 */
function ep_get_event_calendar_views() {
    $calendar_views = array(
        'month'    => 'Month',
        'week'     => 'Week',
        'day'      => 'Day',
        'listweek' => 'Listweek',
    );
    return $calendar_views;
}

/*
 * Get site Domain
 */
function ep_get_site_domain() {
    $url = get_site_url();

    $url = str_replace('http://', '', $url);
    $url = str_replace('https://', '', $url);
    $url = str_replace('ftp://', '', $url);
    $url = str_replace('svn://', '', $url);
    $url = str_replace('www.', '', $url);

    $ex = explode('/', $url);
    $ex2 = explode('?', $ex[0]);

    return $ex2[0];
}


function ep_timestamp( $str_date, $format='m/d/Y H:i' ){
    $format = ep_get_datepicker_format();
    $format = $format . ' H:i';
    $date = DateTime::createFromFormat( $format,$str_date );
    if( empty( $date ) )
        return false;
    return $date->getTimestamp();
}

/**
 * get difference between event starts and ends.
 * 
 * @param object $event Event.
 * 
 * @return string Date time difference.
 */
function ep_get_event_date_time_diff( $event ) {
    $date_diff = '';
    if( ! empty( $event->em_start_date ) && ! empty( $event->em_end_date ) ) {
        $start_date = ep_timestamp_to_date( $event->em_start_date, 'Y-m-d', 1 );
        $start_time = $end_time = '';
        if( ! empty( $event->em_start_time ) ) {
            $start_time = $event->em_start_time;
        }
        $end_date = ep_timestamp_to_date( $event->em_end_date, 'Y-m-d', 1 );
        if( ! empty( $event->em_end_time ) ) {
            $end_time = $event->em_end_time;
        }
        if( ! empty( $start_time ) ) {
            $start_date .= ' ' . $start_time;
        }
        if( ! empty( $end_time ) ) {
            $end_date .= ' ' . $end_time;
        }

        // create date
        //$start_date = DateTime::createFromFormat( 'Y-m-d H:i', $start_date );
        $start_datetime = new DateTime( $start_date );
        // get difference
        //$end_date = DateTime::createFromFormat( 'Y-m-d H:i', $end_date );
        $diff = $start_datetime->diff( new DateTime( $end_date ) );
        $date_diff = '';
        if( ! empty( $diff->y ) ) { // year
            $date_diff .= $diff->y . 'y ';
        }
        if( ! empty( $diff->m ) ) { // month
            $date_diff .= $diff->m . 'm ';
        }
        if( ! empty( $diff->d ) ) { // days
            $date_diff .= $diff->d . 'd ';
        }
        if( ! empty( $diff->h ) ) { // hour
            $date_diff .= $diff->h . 'h ';
        }
        if( ! empty( $diff->i ) ) { // minute
            $date_diff .= $diff->i . 'm';
        }
    }
    return $date_diff;
}

/**
 * get currency symbol of selected currency from global settings
 * 
 * @return string Currency Symbol.
 */
function ep_currency_symbol() {
    $all_currency_symbols = EventM_Constants::get_currency_symbol();
    $selected_currency = ep_get_global_settings( 'currency' );
    if( empty( $selected_currency ) ) {
        $selected_currency = 'USD';
    }
    $currency_symbol = $all_currency_symbols[$selected_currency];
    return $currency_symbol;
}

/**
 * Format price with currency and currency position
 * 
 * @param int|float $price Price.
 * 
 * @param string $currency_symbol Currency Symbol.
 * 
 * @param bool $format_price Number Format of Price.
 * 
 * @return string Formated Price.
 */
function ep_price_with_position( $price, $currency_symbol = '', $format_price = true ) {
    if( $format_price ) {
        $price = number_format_i18n( $price, 2 );
    }
    if( empty( $currency_symbol ) ) {
        $currency_symbol = ep_currency_symbol();
    }
    $currency_position = ep_get_global_settings( 'currency_position' );
    $price_with_curr_pos = $currency_symbol . $price;
    if( $currency_position == 'before_space' ) {
        $price_with_curr_pos = $currency_symbol . ' '. $price;
    }
    if( $currency_position == 'after' ) {
        $price_with_curr_pos = $price . $currency_symbol;
    }
    if( $currency_position == 'after_space' ) {
        $price_with_curr_pos = $price . ' ' . $currency_symbol;
    }
    return $price_with_curr_pos;
}

/**
 * Get button titles from global settings
 * 
 * @param string $button_title Button Title.
 * 
 * @param string $text_domain EventPrime plugin and extension text domain.
 * 
 * @return string Updated button titles.
 */
function ep_global_settings_button_title( $button_title, $text_domain = 'eventprime-event-calendar-management' ) {
    $button_titles = ep_get_global_settings( 'button_titles' );
    if( is_object( $button_titles ) ) {
        $button_titles = (array)$button_titles;
    }
    if( ! empty( $button_titles ) && isset( $button_titles[$button_title] ) && !empty( $button_titles[$button_title] ) ) {
        return esc_html__( $button_titles[$button_title], $text_domain );  
    } else{
        return esc_html__( $button_title, $text_domain );     
    }
}

/**
 * Check if any payment gateway enabled
 */
function em_is_payment_gateway_enabled() {
    $is_payment_enabled = false;
    $payment_processor = array( 'paypal' => ep_get_global_settings( 'paypal_processor' ) );
    $payment_processor = apply_filters( 'ep_is_payment_gayway_enabled', $payment_processor );
    if( ! empty( $payment_processor ) ) {
        foreach( $payment_processor as $payment ) {
            if( ! empty( $payment ) ) {
                $is_payment_enabled = true;
                break;
            }
        }
    }
    return $is_payment_enabled;
}

/**
 * Get booking ticket prices
 * 
 * @param object $booking_tickets Booking Tickets.
 * 
 * @return float Tickets total price.
 */
function ep_get_booking_tickets_total_price( $booking_tickets ) {
    $price = 0;
    if( ! empty( $booking_tickets ) && count( $booking_tickets ) > 0 ) {
        foreach( $booking_tickets as $ticket ) {
            $tic_price = $ticket->price;
            $tic_qty = $ticket->qty;
            $price += $tic_price * $tic_qty;
            if( isset( $ticket->offer ) && ! empty( $ticket->offer ) ) {
                $price += $ticket->offer;
            }
        }
    }
    return ep_price_with_position( $price );
}

/**
 * Get attendee field labels.
 * 
 * @param array $attendees Attendees data in booking.
 * 
 * @return array
 */
function ep_get_booking_attendee_field_labels( $attendees ) {
    $labels = array();
    if( ! empty( $attendees ) ) {
        foreach( $attendees as $key => $attendee ) {
            if( $key == 'seat' ) continue;

            if( $key == 'name' ) {
                if( isset( $attendee['first_name'] ) ) {
                    $labels[] = esc_html__( 'First Name', 'eventprime-event-calendar-management' );
                }
                if( isset( $attendee['middle_name'] ) ) {
                    $labels[] = esc_html__( 'Middle Name', 'eventprime-event-calendar-management' );
                }
                if( isset( $attendee['last_name'] ) ) {
                    $labels[] = esc_html__( 'Last Name', 'eventprime-event-calendar-management' );
                }
                unset( $attendees['name'] );
            }
            foreach( $attendees as $at_key => $at ) {
                // seat column should be in end
                if( $at_key == 'seat' ) continue;
                if( ! empty( $at['label'] ) ) {
                    $labels[] = $at['label'];
                }
            }
            $labels = apply_filters( 'ep_filter_booking_attendee_field_labels', $labels, $attendees );
            break;
        }
    }
    return $labels;
}

/**
 * Get ticket name by ticket id and event.
 * 
 * @param int $ticket_id Ticket Id.
 * 
 * @param object $event_data Booked event data.
 * 
 * @return string Ticket Name.
 */
function get_event_ticket_name_by_id_event( $ticket_id, $event_data ) {
    $ticket_name = '';
    if( ! empty( $ticket_id ) && ! empty( $event_data ) ) {
        if( isset( $event_data->all_tickets_data ) && count( $event_data->all_tickets_data ) > 0 ) {
            $all_tickets = $event_data->all_tickets_data;
            foreach( $all_tickets as $ticket ) {
                if( $ticket->id == $ticket_id ) {
                    $ticket_name = $ticket->name;
                    break;
                }
            }
        }
    }
    return $ticket_name;
}

/**
 * Get all tickets additional fees total
 * 
 * @param array $tickets Tickets.
 * 
 * @return float
 */
function ep_calculate_order_total_additional_fees( $tickets ){
    $additional_fees = 0;
    if( ! empty( $tickets ) && count( $tickets ) > 0 ) {
        foreach( $tickets as $ticket ) {
            $tic_qty = $ticket->qty;
            if( ! empty( $ticket->additional_fee ) ) {
                foreach( $ticket->additional_fee as $af ) {
                    $price = $af->price;
                    if( $price ) {
                        $additional_fees += $price * $tic_qty;
                    }
                }
            }
        }
    }
    return ep_price_with_position( $additional_fees );
}

/**
 * Get greeting text
 */
function ep_get_greeting_text() {
    $hour = date('H');
    $greet = 'Good ';
    $greet .= ( $hour >= 17 ) ? 'Evening' : ( ( $hour >= 12 ) ? 'Afternoon' : 'Morning' );
    return $greet;
}

/**
 * Return the date & time timespamp
 * 
 * @param object $event Event.
 * 
 * @param string $start Start or End text.
 * 
 * @return int Timestamp.
 */
function ep_convert_event_date_time_to_timestamp( $event, $start = 'start' ) {
    $timestamp = '';
    if( $start == 'start' ) {
        if( ! empty( $event->em_start_date ) ) {
            $timestamp = $event->em_start_date;
            if( ! empty( $event->em_start_time ) ) {
                $start = ep_timestamp_to_date( $event->em_start_date );
                $start .= ' '.$event->em_start_time;
                $timestamp = ep_datetime_to_timestamp( $start );
            }
        }
    } else{
        if( ! empty( $event->em_end_date ) ) {
            $timestamp = $event->em_end_date;
            if( ! empty( $event->em_end_time ) ) {
                $end = ep_timestamp_to_date( $event->em_end_date );
                $end .= ' '.$event->em_end_time;
                $timestamp = ep_datetime_to_timestamp( $end );
            }
        }
    }
    return $timestamp;
}

// get web url content
function ep_get_file_content( $url, $timeout = 20 ) {
    $result = false;
    // get from wordpress remote
    if( function_exists( 'wp_remote_get' ) ) {
        $result = wp_remote_retrieve_body(
            wp_remote_get( $url, 
                array(
                    'body' => null,
                    'timeout' => $timeout,
                    'redirection' => 5,
                )
            )
        );
    }
    // get from file get content
    if( $result === false ) {
        $http = array();
        $result = @file_get_contents( $url, false, stream_context_create( array( 'http' => $http ) ) );
    }

    return $result;
}

/**
 * Get current user name
 */
function ep_get_current_user_profile_name() {
    $current_user = wp_get_current_user();
    $name = '';
    if( ! empty( $current_user->user_firstname ) ) {
        $name .= $current_user->user_firstname;
    }
    if( ! empty( $current_user->user_lastname ) ) {
        $name .= ' ' . $current_user->user_lastname;
    }
    if( empty( $name ) && ! empty( $current_user->display_name ) ) {
        $name = $current_user->display_name;
    }
    return $name;
}

/**
 * Check if event is in user wishlist
 * 
 * @param int $event_id Event ID.
 * 
 * @return bool
 */
function check_event_in_user_wishlist( $event_id ) {
    if( ! empty( $event_id ) ) {
        $user_id = get_current_user_id();
        if( ! empty( $user_id ) ) {
            $wishlist_meta = get_user_meta( $user_id, 'ep_wishlist_event', true );
            if( ! empty( $wishlist_meta ) && isset( $wishlist_meta[$event_id] ) ) {
                return true;
            }
        }
    }
    return false;
}
/*
 * Email Config
 */
function em_set_mail_content_type_html($content_type) {
    $content_type = 'text/html';
    return $content_type;
        
}
function em_set_mail_from($original_email_address) {
    return get_option('admin_email');
}

function em_set_mail_from_name($original_from_address) {
    return get_option('blogname');
}

/**
 * Check if date value show or hide on front
 * 
 * @param string $value Date Value.
 * 
 * @param object $event Event Data.
 * 
 * @return bool
 */
function ep_show_event_date_time( $value, $event ) {
    if( empty( $event->{$value} ) ) {
        return false;
    }

    if( $value == 'em_start_date' ) {
        if( ! empty( $event->em_hide_event_start_date ) ) {
            return false;
        }
    }

    if( $value == 'em_start_time' || $value == 'em_end_date' || $value == 'em_end_time' ) {
        if( $event->em_all_day == 1 ) {
            return false;
        } else{
            if( $value == 'em_start_time' && ! empty( $event->em_hide_event_start_time ) ) {
                return false;
            }
            if( $value == 'em_end_date' && ! empty( $event->em_hide_end_date ) ) {
                return false;
            }
            if( $value == 'em_end_time' && ! empty( $event->em_hide_event_end_time ) ) {
                return false;
            }
        }
    }

    return true;
}

/**
 * Get all tickets offers price total
 * 
 * @param array $tickets Tickets.
 * 
 * @return float
 */
function ep_calculate_order_total_offer_price( $tickets ){
    $offer_fees = 0;
    if( ! empty( $tickets ) && count( $tickets ) > 0 ) {
        foreach( $tickets as $ticket ) {
            if( ! empty( $ticket->offer ) ) {
                $offer_fees += $ticket->offer;
            }
        }
    }
    return ep_price_with_position( $offer_fees );
}

/**
 * Check if event has expired
 * 
 * @param object $event Event Data.
 * 
 * @return bool
 */
function check_event_has_expired( $event ) {
    $expired = false;
    if( $event ) {
        if( ! empty( $event->em_end_date ) ) {
            $end_date = $event->em_end_date;
            if( ! empty( $event->em_end_time ) ) {
                $end_date = ep_timestamp_to_date( $end_date );
                $end_date .= ' ' . $event->em_end_time;
                $end_date = ep_datetime_to_timestamp( $end_date );
            }
            if( $end_date < ep_get_current_timestamp() ) {
                $expired = true;
            }
        }
    }
    return $expired;
}


function ep_get_converted_price_in_cent($price,$currency){
    if(ep_is_price_conversion_req_for_stripe($currency))
        return $price*100;
    return $price;
}
    
function convert_fr($currency,$price){
    if(ep_is_price_conversion_req_for_stripe($currency))
        return $price/100;
    return $price;
}
    
function ep_is_price_conversion_req_for_stripe($currency){
    $currency = strtoupper( $currency );
    switch( $currency )
    {
        case 'BIF':
        case 'DJF':
        case 'JPY':
        case 'KRW':
        case 'PYG':
        case 'VND':
        case 'XAF':
        case 'XPF':
        case 'CLP':
        case 'GNF':
        case 'KMF':
        case 'MGA':
        case 'RWF':
        case 'VUV':
        case 'XOF':
            return false;
        default:
            return true;
    }
    return false;
}

/**
 * Checkout page essential fields data
 * 
 * @return array Fields
 */
function ep_get_checkout_page_esential_fields() {
    $fields = array(
        'name' => array(
            'label' => esc_html__( 'Name', 'eventprime-event-calendar-management' ),
            'sub_fields' => array(
                'first_name' => array(
                    'label' => esc_html__( 'First Name', 'eventprime-event-calendar-management' ),
                    'type'  => 'text'
                ),
                'middle_name' => array(
                    'label' => esc_html__( 'Middle Name', 'eventprime-event-calendar-management' ),
                    'type'  => 'text'
                ),
                'last_name' => array(
                    'label' => esc_html__( 'Last Name', 'eventprime-event-calendar-management' ),
                    'type'  => 'text'
                )
            )
        ),
        'email' => array(
            'label' => esc_html__( 'Email', 'eventprime-event-calendar-management' ),
            'type'  => 'email'
        ),
        'phone' => array(
            'label' => esc_html__( 'Phone', 'eventprime-event-calendar-management' ),
            'type'  => 'tel'
        )
    );
    return $fields;
}

/**
 * Check if guest booking enabled
 */
function ep_enabled_guest_booking() {
    $enabled_guest_booking = 0;
    if( in_array( 'guest_booking', EP()->extensions ) ) {
        $allow_guest_bookings = ep_get_global_settings( 'allow_guest_bookings' );
        if( ! empty( $allow_guest_bookings ) ) {
            $enabled_guest_booking = 1;
        }
    }
    return $enabled_guest_booking;
}

/**
 * Get user ip
 */
function ep_get_user_ip() {
    if( isset( $_SERVER['HTTP_CLIENT_IP'] ) ) {
        $ip_address = sanitize_text_field( $_SERVER['HTTP_CLIENT_IP'] );
    } elseif( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
        $ip_address = sanitize_text_field( $_SERVER['HTTP_X_FORWARDED_FOR'] );
    } elseif( isset( $_SERVER['HTTP_X_FORWARDED'] ) ) {
        $ip_address = sanitize_text_field( $_SERVER['HTTP_X_FORWARDED'] );
    } elseif( isset( $_SERVER['HTTP_FORWARDED_FOR'] ) ) {
        $ip_address = sanitize_text_field( $_SERVER['HTTP_FORWARDED_FOR'] );
    } elseif( isset( $_SERVER['HTTP_FORWARDED'] ) ) {
        $ip_address = sanitize_text_field( $_SERVER['HTTP_FORWARDED'] );
    } elseif( isset( $_SERVER['REMOTE_ADDR'] ) ) {
        $ip_address = sanitize_text_field( $_SERVER['REMOTE_ADDR'] );
    } else {
        $ip_address = 'UNKNOWN';
    }

    $ips = explode( ',', $ip_address );
    if( count( $ips ) > 1 ) {
        $ip_address = $ips[0];
    }
    return $ip_address;
}

// list all extension
function ep_list_all_exts(){
    $exts = array( 'Live Seating', 'Events Import Export', 'Stripe Payments', 'Offline Payments', 'WooCommerce Integration', 'Event Sponsors', 'Attendees List', 'EventPrime Invoices', 'Coupon Codes', 'Guest Bookings', 'EventPrime Zoom Integration', 'Event List Widgets', 'Admin Attendee Bookings', 'EventPrime MailPoet', 'Twilio Text Notifications', 'Event Tickets', 'Zapier Integration' );
    return $exts;
}

// load extensions data
function em_get_more_extension_data($plugin_name){
    $data['is_activate'] = $data['is_installed'] = $data['url'] = '';
    $data['button'] = 'Download';
    $data['class_name'] = 'ep-install-now-btn';
    $em = EP();
    $installed_plugins = get_plugins();
    $installed_plugin_file = array();
    $installed_plugin_url = array();
    if( ! empty( $installed_plugins ) ) {
        foreach ( $installed_plugins as $key => $value ) {
            $exp = explode( '/', $key );
            $installed_plugin_file[] = end( $exp );
            $installed_plugin_url[] = $key;
        }
    }
    switch ( $plugin_name ) {
        case 'Live Seating':
            $data['url'] = 'https://theeventprime.com/all-extensions/live-seating/';
            if( in_array( 'eventprime-live-seating.php', $installed_plugin_file ) ) {
                $data['button'] = 'Activate';
                $data['class_name'] = 'ep-activate-now-btn';
                $file_key = array_search( 'eventprime-live-seating.php', $installed_plugin_file );
                $data['url'] = em_get_extension_activation_url( $installed_plugin_url[$file_key] );
            }
            $data['is_activate'] = class_exists( 'EP_Live_Seating' );
            if( $data['is_activate'] ) {
                $data['button'] = 'Setting';
                $data['class_name'] = 'ep-option-now-btn';
                $data['url'] = admin_url( 'edit.php?post_type=em_event&page=ep-settings&tab=live-seating-settings' );
            }
            $data['is_free'] = 0;
            $data['image'] = 'live_seating_icon.png';
            $data['desc'] = "Add live seat selection on your events and provide seat based tickets to your event attendees. Set a seating arrangement for all your Event Sites with specific rows, columns, and walking aisles using EventPrime's very own Event Site Seating Builder.";
            break;
        /* case 'Advanced Reporting':
            $data['url'] = '';
            if(in_array('event-analytics.php', $installed_plugin_file)){
                $data['button'] = 'Activate';
                $data['class_name'] = 'ep-activate-now-btn';
                $file_key = array_search('event-analytics.php', $installed_plugin_file);
                $data['url'] = em_get_extension_activation_url($installed_plugin_url[$file_key]);
            }
            $data['is_activate'] = class_exists("EM_Analytics");
            if($data['is_activate']){
                $data['button'] = 'Setting';
                $data['class_name'] = 'ep-option-now-btn';
                $data['url'] = admin_url('admin.php?page=em_analytics');
            }
            $data['is_free'] = 1;
            $data['image'] = 'ep-analytics-icon.png';
            $data['desc'] = "Stay updated on all the Revenue and Bookings coming your way through EventPrime. The Event Analytics extension empowers you with data and graphs that you need to know how much your events are connecting with their audience.";
            break; */
        case 'Event Sponsors':
            $data['url'] = 'https://theeventprime.com/all-extensions/event-sponsors/';
            if(in_array('event-sponsor.php', $installed_plugin_file)){
                $data['button'] = 'Activate';
                $data['class_name'] = 'ep-activate-now-btn';
                $file_key = array_search('event-sponsor.php', $installed_plugin_file);
                $data['url'] = em_get_extension_activation_url($installed_plugin_url[$file_key]);
            }
            $data['is_activate'] = class_exists("EM_Sponsor");
            if($data['is_activate']){
                $data['button'] = 'Setting';
                $data['class_name'] = 'ep-option-now-btn';
                $data['url'] = admin_url( 'edit.php?post_type=em_event&page=ep-settings&tab=frontviews&sub_tab=sponsors' );
            }
            $data['is_free'] = 0;
            $data['image'] = 'event_sponsors_icon.png';
            $data['desc'] = "Add Sponsor(s) to your events. Upload Sponsor logos and they will appear on the event page alongside all other details of the event.";
            break;
        case 'Stripe Payments':
            $data['url'] = 'https://theeventprime.com/all-extensions/stripe-payments/';
            if(in_array('eventprime-stripe.php', $installed_plugin_file)){
                $data['button'] = 'Activate';
                $data['class_name'] = 'ep-activate-now-btn';
                $file_key = array_search('eventprime-stripe.php', $installed_plugin_file);
                $data['url'] = em_get_extension_activation_url($installed_plugin_url[$file_key]);
            }
            $data['is_activate'] = class_exists("EP_Stripe");
            if($data['is_activate']){
                $data['button'] = 'Setting';
                $data['class_name'] = 'ep-option-now-btn';
                $data['url'] = admin_url( 'edit.php?post_type=em_event&page=ep-settings&tab=payments&section=stripe' );
            }
            $data['is_free'] = 0;
            $data['image'] = 'stripe_payments_icon.png';
            $data['desc'] = "Start accepting Event Booking payments using the Stripe Payment Gateway. By integrating Stripe with EventPrime, event attendees can now pay with their credit cards while you receive the payment in your Stripe account.";
            break;
        case 'Offline Payments':
            $data['url'] = 'https://theeventprime.com/all-extensions/offline-payments/';
            if(in_array('eventprime-offline.php', $installed_plugin_file)){
                $data['button'] = 'Activate';
                $data['class_name'] = 'ep-activate-now-btn';
                $file_key = array_search('eventprime-offline.php', $installed_plugin_file);
                $data['url'] = em_get_extension_activation_url($installed_plugin_url[$file_key]);
            }
            $data['is_activate'] = class_exists("EP_Offline");
            if($data['is_activate']){
                $data['button'] = 'Setting';
                $data['class_name'] = 'ep-option-now-btn';
                $data['url'] = admin_url( 'edit.php?post_type=em_event&page=ep-settings&tab=payments&section=offline' );
            }
            $data['is_free'] = 0;
            $data['image'] = 'offline_payments_icon.png';
            $data['desc'] = "Don't want to use any online payment gateway to collect your event booking payments? Don't worry. With the Offline Payments extension, you can accept event bookings online while you collect booking payments from attendees offline.";
            break;
        case 'Attendees List':
            $data['url'] = 'https://theeventprime.com/all-extensions/attendees-list/';
            if(in_array('eventprime-attendees-list.php', $installed_plugin_file)){
                $data['button'] = 'Activate';
                $data['class_name'] = 'ep-activate-now-btn';
                $file_key = array_search('eventprime-attendees-list.php', $installed_plugin_file);
                $data['url'] = em_get_extension_activation_url($installed_plugin_url[$file_key]);
            }
            $data['is_activate'] = class_exists("EP_Attendees_List");
            if($data['is_activate']){
                $data['button'] = 'Setting';
                $data['class_name'] = 'ep-option-now-btn';
                $data['url'] = admin_url( 'edit.php?post_type=em_event&page=ep-settings&tab=attendees-list-settings' );
            }
            $data['is_free'] = 0;
            $data['image'] = 'attendee_list_icon.png';
            $data['desc'] = "Display names of your Event Attendees on the Event page. Or within the new Attendees List widget.";
            break;
        case 'Coupon Codes':
            $data['url'] = 'https://theeventprime.com/all-extensions/coupon-codes/';
            if(in_array('event-coupons.php', $installed_plugin_file)){
                $data['button'] = 'Activate';
                $data['class_name'] = 'ep-activate-now-btn';
                $file_key = array_search('event-coupons.php', $installed_plugin_file);
                $data['url'] = em_get_extension_activation_url($installed_plugin_url[$file_key]);
            }
            $data['is_activate'] = class_exists("EP_Coupons");
            if($data['is_activate']){
                $data['button'] = 'Setting';
                $data['class_name'] = 'ep-option-now-btn';
                $data['url'] = admin_url( 'edit.php?edit.php?post_type=em_coupon' );
            }
            $data['is_free'] = 0;
            $data['image'] = 'coupon_code_icon.png';
            $data['desc'] = "Create and activate coupon codes for allowing Attendees for book for events at a discount. Set discount type and limits on coupon code usage, or deactivate at will.";
            break;
        case 'Guest Bookings':
            $data['url'] = 'https://theeventprime.com/all-extensions/guest-bookings/';
            if(in_array('eventprime-guest-booking.php', $installed_plugin_file)){
                $data['button'] = 'Activate';
                $data['class_name'] = 'ep-activate-now-btn';
                $file_key = array_search('eventprime-guest-booking.php', $installed_plugin_file);
                $data['url'] = em_get_extension_activation_url($installed_plugin_url[$file_key]);
            }
            $data['is_activate'] = class_exists("EP_Guest_Booking");
            if($data['is_activate']){
                $data['button'] = 'Setting';
                $data['class_name'] = 'ep-option-now-btn';
                $data['url'] = admin_url( 'edit.php?post_type=em_event&page=ep-settings&tab=forms&section=guest_booking' );
            }
            $data['is_free'] = 0;
            $data['image'] = 'guest_bookings_icon.png';
            $data['desc'] = "Allow attendees to complete their event bookings without registering or logging in.";
            break;
        case 'Event List Widgets':
            $data['url'] = 'https://theeventprime.com/all-extensions/event-list-widgets/';
            if(in_array('eventprime-list-widgets.php', $installed_plugin_file)){
                $data['button'] = 'Activate';
                $data['class_name'] = 'ep-activate-now-btn';
                $file_key = array_search('eventprime-list-widgets.php', $installed_plugin_file);
                $data['url'] = em_get_extension_activation_url($installed_plugin_url[$file_key]);
            }
            $data['is_activate'] = class_exists("EP_List_Widget");
            if($data['is_activate']){
                $data['button'] = '';
                $data['class_name'] = 'ep-option-now-btn';
                $data['url'] = admin_url('admin.php?page=em_global_settings');
            }
            $data['is_free'] = 0;
            $data['image'] = 'guest_bookings_icon.png';
            $data['desc'] = "Add 3 new Event Listing widgets to your website. These are the Popular Events list, Featured Events list, and Related Events list widgets.";
            break;
        case 'Admin Attendee Bookings':
            $data['url'] = 'https://theeventprime.com/all-extensions/admin-attendee-bookings/';
            if(in_array('eventprime-admin-attendee-booking.php', $installed_plugin_file)){
                $data['button'] = 'Activate';
                $data['class_name'] = 'ep-activate-now-btn';
                $file_key = array_search('eventprime-admin-attendee-booking.php', $installed_plugin_file);
                $data['url'] = em_get_extension_activation_url($installed_plugin_url[$file_key]);
            }
            $data['is_activate'] = class_exists("EP_Admin_Attendee_Booking");
            if($data['is_activate']){
                $data['button'] = '';
                $data['class_name'] = 'ep-option-now-btn';
                $data['url'] = admin_url('admin.php?page=em_bookings');
            }
            $data['is_free'] = 0;
            $data['image'] = 'admin_attendee_booking_icon.png';
            $data['desc'] = "Admins can now create custom attendee bookings from the backend EventPrime dashboard.";
            break;
        case 'Events Import Export':
            $data['url'] = 'https://theeventprime.com/all-extensions/events-import-export/';
            if(in_array('events-import-export.php', $installed_plugin_file)){
                $data['button'] = 'Activate';
                $data['class_name'] = 'ep-activate-now-btn';
                $file_key = array_search('events-import-export.php', $installed_plugin_file);
                $data['url'] = em_get_extension_activation_url($installed_plugin_url[$file_key]);
            }
            $data['is_activate'] = class_exists("EP_Events_Import_Export");
            if($data['is_activate']){
                $data['button'] = 'Setting';
                $data['class_name'] = 'ep-option-now-btn';
                $data['url'] = admin_url('edit.php?post_type=em_event&page=ep-import-export');
            }
            $data['is_free'] = 1;
            $data['image'] = 'event_import_export_icon.png';
            $data['desc'] = "Import or export events in popular file formats like CSV, ICS, XML and JSON.";
            break;
        case 'EventPrime MailPoet':
            $data['url'] = 'https://theeventprime.com/all-extensions/mailpoet-integration/';
            if(in_array('eventprime-mailpoet.php', $installed_plugin_file)){
                $data['button'] = 'Activate';
                $data['class_name'] = 'ep-activate-now-btn';
                $file_key = array_search('eventprime-mailpoet.php', $installed_plugin_file);
                $data['url'] = em_get_extension_activation_url($installed_plugin_url[$file_key]);
            }
            $data['is_activate'] = class_exists("EP_MailPoet");
            if($data['is_activate']){
                $data['button'] = 'Setting';
                $data['class_name'] = 'ep-option-now-btn';
                $data['url'] = admin_url('admin.php?page=em_mailpoet');
            }
            $data['is_free'] = 1;
            $data['image'] = 'mailpoet_icon.png';
            $data['desc'] = "Connect and engage with your users by subscribing event attendees to MailPoet lists. Users can opt-in multiple newsletters during checkout and can also manage subscriptions in user account area.";
            break;
        case 'WooCommerce Integration':
            $data['url'] = 'https://theeventprime.com/all-extensions/woocommerce-integration/';
            if(in_array('woocommerce-integration.php', $installed_plugin_file)){
                $data['button'] = 'Activate';
                $data['class_name'] = 'ep-activate-now-btn';
                $file_key = array_search('woocommerce-integration.php', $installed_plugin_file);
                $data['url'] = em_get_extension_activation_url($installed_plugin_url[$file_key]);
            }
            $data['is_activate'] = class_exists("EP_Woocommerce_Integration");
            if($data['is_activate']){
                $data['button'] = '';
                $data['class_name'] = 'ep-option-now-btn';
                $data['url'] = admin_url('admin.php?page=em_global_settings');
            }
            $data['is_free'] = 1;
            $data['image'] = 'woocommerce_integration_icon.png';
            $data['desc'] = "This extension allows you to add optional and/ or mandatory products to your events. You can define quantity or let users chose it themselves. Fully integrates with EventPrime checkout experience and WooCommerce order management.";
            break;
        case 'EventPrime Zoom Integration':
            $data['url'] = 'https://theeventprime.com/all-extensions/zoom-integration/';
            if(in_array('eventprime-zoom-meetings.php', $installed_plugin_file)){
                $data['button'] = 'Activate';
                $data['class_name'] = 'ep-activate-now-btn';
                $file_key = array_search('eventprime-zoom-meetings.php', $installed_plugin_file);
                $data['url'] = em_get_extension_activation_url($installed_plugin_url[$file_key]);
            }
            $data['is_activate'] = class_exists("EP_Zoom_Meetings");
            if($data['is_activate']){
                $data['button'] = 'Setting';
                $data['class_name'] = 'ep-option-now-btn';
                $data['url'] = admin_url('edit.php?post_type=em_event&page=ep-settings&tab=zoom-meetings');
            }
            $data['is_free'] = 1;
            $data['image'] = 'zoom_integration_icon.png';
            $data['desc'] = "This extension seamlessly creates virtual events to be conducted on Zoom through the EventPrime plugin. The extension provides easy linking of your website to that of Zoom. Commence and let the attendees join the event with a single click.";
            break;
        case 'Zapier Integration':
            $data['url'] = 'https://theeventprime.com/all-extensions/zapier-integration/';
            if(in_array('event-zapier.php', $installed_plugin_file)){
                $data['button'] = 'Activate';
                $data['class_name'] = 'ep-activate-now-btn';
                $file_key = array_search('event-zapier.php', $installed_plugin_file);
                $data['url'] = em_get_extension_activation_url($installed_plugin_url[$file_key]);
            }
            $data['is_activate'] = class_exists("EP_Zapier_Integration");
            if($data['is_activate']){
                $data['button'] = 'Setting';
                $data['class_name'] = 'ep-option-now-btn';
                $data['url'] = admin_url('edit.php?post_type=em_event&page=ep-settings&tab=zapier-settings');
            }
            $data['is_free'] = 1;
            $data['image'] = 'zapier_integration_icon.png';
            $data['desc'] = "Extend the power of EventPrime using Zapier's powerful automation tools! Connect with over 3000 apps by building custom templates using EventPrime triggers.";
            break;
        case 'EventPrime Invoices':
            $data['url'] = 'https://theeventprime.com/all-extensions/invoices/';
            if(in_array('event-invoices.php', $installed_plugin_file)){
                $data['button'] = 'Activate';
                $data['class_name'] = 'ep-activate-now-btn';
                $file_key = array_search('event-invoices.php', $installed_plugin_file);
                $data['url'] = em_get_extension_activation_url($installed_plugin_url[$file_key]);
            }
            $data['is_activate'] = class_exists("EM_Event_Invoices");
            if($data['is_activate']){
                $data['button'] = 'Setting';
                $data['class_name'] = 'ep-option-now-btn';
                $data['url'] = admin_url('edit.php?post_type=em_event&page=ep-settings&tab=invoice');
            }
            $data['is_free'] = 1;
            $data['image'] = 'event_invoices_icon.png';
            $data['desc'] = "Allows fully customizable PDF invoices, complete with your company branding, to be generated and emailed with booking details to your users.";
            break;
        case 'Twilio Text Notifications':
            $data['url'] = 'https://theeventprime.com/all-extensions/twilio-text-notifications/';
            if(in_array('sms-integration.php', $installed_plugin_file)){
                $data['button'] = 'Activate';
                $data['class_name'] = 'ep-activate-now-btn';
                $file_key = array_search('sms-integration.php', $installed_plugin_file);
                $data['url'] = em_get_extension_activation_url($installed_plugin_url[$file_key]);
            }
            $data['is_activate'] = class_exists("EP_SMS_Integration");
            if($data['is_activate']){
                $data['button'] = 'Setting';
                $data['class_name'] = 'ep-option-now-btn';
                $data['url'] = admin_url('edit.php?post_type=em_event&page=ep-settings&tab=sms-settings');
            }
            $data['is_free'] = 0;
            $data['image'] = 'twilio_icon.png';
            $data['desc'] = "Keep your users engaged with text/ SMS notification system. Creating Twilio account is quick and easy. With this extension installed, you will be able to configure admin and user notifications separately, with personalized content.";
            break;
        case 'Event Tickets':
            $data['url'] = 'https://theeventprime.com/all-extensions/event-tickets/';
            if(in_array('event-tickets.php', $installed_plugin_file)){
                $data['button'] = 'Activate';
                $data['class_name'] = 'ep-activate-now-btn';
                $file_key = array_search('event-tickets.php', $installed_plugin_file);
                $data['url'] = em_get_extension_activation_url($installed_plugin_url[$file_key]);
            }
            $data['is_activate'] = class_exists("EM_Event_Tickets");
            if($data['is_activate']){
                $data['button'] = 'Setting';
                $data['class_name'] = 'ep-option-now-btn';
                $data['url'] = admin_url('edit.php?post_type=em_ticket');
            }
            $data['is_free'] = 0;
            $data['image'] = 'event_tickets_icon.png';
            $data['desc'] = "An EventPrime extension that generate events tickets.";
            break;    
    }
    return $data;
}

// get extension activation url
function em_get_extension_activation_url( $path ) {
    $plugin = $path;
    if ( strpos( $path, '/' ) ) {
        $path = str_replace( '/', '%2F', $path );
    }
    $activateUrl = sprintf( admin_url( 'plugins.php?action=activate&plugin=%s' ), $path );    
    $activateUrl = wp_nonce_url( $activateUrl, 'activate-plugin_' . $plugin );
    return $activateUrl;
}

/**
 * Check if woocommerce integration enabled
 */
function ep_enabled_woocommerce_integration() {
    $enabled_woocommerce_integration = 0;
    if( in_array( 'woocommerce_integration', EP()->extensions ) ) {
        $allow_woocommerce_integration = ep_get_global_settings( 'allow_woocommerce_integration' );
        if( ! empty( $allow_woocommerce_integration ) ) {
            $enabled_woocommerce_integration = 1;
        }
    }
    return $enabled_woocommerce_integration;
}

/**
 * Get current theme name
 */
function ep_get_current_theme_name() {
    $theme_obj = wp_get_theme();
    return $theme_obj->__get('title');
}

// get user timezone
function ep_get_user_timezone() {
    $timezone_string = get_option('timezone_string');
    $gmt_offset = get_option('gmt_offset');

    if( empty( $timezone_string ) ) {
        if( empty( $gmt_offset ) ) {
            $timezone_string = 'UTC';
        } else{
            $timezone_string = get_site_timezone_from_offset( $gmt_offset );
        }
    }
    return $timezone_string;
}

// get timezone offset
function ep_gmt_offset_seconds( $date = NULL ) {
    if( $date ) {
        $timezone = new DateTimeZone( ep_get_user_timezone() );
        // Convert to Date
        if( is_numeric( $date ) ) $date = date( 'Y-m-d', $date );

        $target = new DateTime( $date, $timezone );
        return $timezone->getOffset( $target );
    } else{
        $gmt_offset = get_option('gmt_offset');
        $seconds = $gmt_offset * HOUR_IN_SECONDS;

        return ( substr( $gmt_offset, 0, 1 ) == '-' ? '' : '+' ) . $seconds;
    }
}

/**
 * Get user timezone by ip address
 */
function get_timezone_by_ip() {
    $user_ip = ep_get_user_ip();
    // First Provider
    $JSON = ep_get_file_content( 'http://ip-api.com/json/'.$user_ip, 3 );
    $data = json_decode($JSON, true);

    // Second Provider
    if( ! trim( $JSON ) or ( is_array( $data ) and ! isset( $data['timezone'] ) ) ) {
        $JSON = ep_get_file_content( 'https://ipapi.co/'.$user_ip.'/json/', 3 );
        $data = json_decode( $JSON, true );
    }

    // Second provider returns X instead of false in case of error!
    $timezone = ( isset( $data['timezone'] ) and strtolower( $data['timezone'] ) != 'x' ) ? $data['timezone'] : false;

    return $timezone;
}

// get current user timezone
function ep_get_current_user_timezone() {
    $user_timezone_meta = '';
    $user_id = get_current_user_id();
    // if user is loggedin
    if( ! empty( $user_id ) ) {
        // check from user meta
        $user_timezone_meta = get_user_meta( $user_id, 'ep_user_timezone_meta', true );
        if( empty( $user_timezone_meta ) ) {
            // check if set in cookie
            if( isset( $_COOKIE['ep_user_timezone_meta'] ) ) {
                $user_timezone_meta = $_COOKIE['ep_user_timezone_meta'];
                add_user_meta( $user_id, 'ep_user_timezone_meta', $user_timezone_meta );
                setcookie( 'ep_user_timezone_meta', '', time() - 3600 );
            }
        }
    } else{
        // for non loggedin user check if set in cookie
        if( isset( $_COOKIE['ep_user_timezone_meta'] ) ) {
            $user_timezone_meta = $_COOKIE['ep_user_timezone_meta'];
        }
    }
    // if user did not save timezone then return site timezone
    if( empty( $user_timezone_meta ) ) {
        $user_timezone_meta = ep_get_user_timezone();
    }
    return $user_timezone_meta;
}

/**
 * Convert date time in the timezone
 */
function ep_convert_event_date_time_from_timezone( $event, $format = '', $end = 0 ) {
    if( $event ) {
        $format = ep_get_datepicker_format();
        $date = ep_timestamp_to_date( $event->em_start_date, $format );
        $time_format = ep_get_global_settings( 'time_format' );
        $start_time = $event->em_start_time;
        if( empty( $start_time ) ) {
            $start_time = '12:00 am';
            if( ! empty( $time_format ) && $time_format == 'HH:mm' ) {
                $start_time = '00:00 am';
            }
        }
        if( ! empty( $end ) ) {
            $date = ep_timestamp_to_date( $event->em_end_date, $format );
            $start_time = $event->em_end_time;
            if( empty( $start_time ) ) {
                $start_time = '11:59 pm';
            }
        }
        $user_timezone = ep_get_current_user_timezone();
        if( ! empty( $user_timezone ) ) {
            if( strpos( $user_timezone, '+' ) !== false ) {
                $exp_timezone = explode( '+', $user_timezone )[1];
                $user_timezone = get_site_timezone_from_offset( $exp_timezone );
            }
            if( strpos( $user_timezone, '-' ) !== false ) {
                $exp_timezone = explode( '-', $user_timezone )[1];
                $user_timezone = get_site_timezone_from_offset( $exp_timezone );
            }
        }
        $site_timezone = ep_get_site_timezone();
        if( ! empty( $user_timezone ) && $user_timezone != $site_timezone ) {
            $datetime = $date . ' ' . $start_time;
            $times = ep_datetime_to_timestamp( $datetime, 'Y-m-d h:i a', $site_timezone, 1 );
            $times->setTimeZone( new DateTimeZone( $user_timezone ) );
            if( ! empty( $time_format ) && $time_format == 'HH:mm' ) {
                $date = $times->format('D, d M');
                $date .= ' ' . $times->format('H:i');
            } else{
                $date = $times->format('D, d M, h:i A');
            }
            return $date;
        } else{
            $datetime = $date . ' ' . $start_time;
            $times = ep_datetime_to_timestamp( $datetime, 'Y-m-d h:i a', $site_timezone, 1 );
            $times->setTimeZone( new DateTimeZone( $site_timezone ) );
            if( ! empty( $time_format ) && $time_format == 'HH:mm' ) {
                $date = $times->format('D, d M');
                $date .= ' ' . $times->format('H:i');
            } else{
                $date = $times->format('D, d M, h:i A');
            }
            return $date;
        }
    }
}

// convert only time from timezone
function ep_convert_event_time_from_timezone( $event, $end = 0 ) {
    if( $event ) {
        if( ! empty( $end ) ) {
            $date = ep_timestamp_to_date( $event->em_end_date );
            $time = $event->em_end_time;
        } else{
            $date = ep_timestamp_to_date( $event->em_start_date );
            $time = $event->em_start_time;
        }
        if( empty( $time ) ) {
            $time = '12:00 am';
        }
        $user_timezone = ep_get_current_user_timezone();
        if( ! empty( $user_timezone ) ) {
            $site_timezone = ep_get_site_timezone();
            if( $user_timezone != $site_timezone ) {
                $datetime = $date . ' ' . $time;
                $times = ep_datetime_to_timestamp( $datetime, 'Y-m-d h:i A', $site_timezone, 1 );
                $times->setTimeZone( new DateTimeZone( $user_timezone ) );
                $date = $times->format('h:i A');
                return $date;
            }
        }
    }
}

// get event booking total
function ep_get_event_booking_total( $booking ) {
    if( empty( $booking ) ) return;
    $order_info = ( ! empty( $booking->em_order_info ) ? $booking->em_order_info : array() );
    $payment_log = ( ! empty( $booking->em_payment_log ) ? $booking->em_payment_log : array() );
    if( empty( $booking->em_old_ep_booking ) ) {
        return esc_html( ep_price_with_position( $order_info['booking_total'] ) );
    } else{
        if( ! empty( $order_info['booking_total'] ) ) {
            return esc_html( ep_price_with_position( $order_info['booking_total'] ) );
        } else{
            if(isset($order_info['item_price']) && !empty($order_info['item_price'])){
                $after_discount_price = ($order_info['item_price'] * $order_info['quantity']) - $order_info['discount'];
                if(isset($order_info['fixed_event_price']) && !empty($order_info['fixed_event_price'])){
                    $after_discount_price += $order_info['fixed_event_price'];
                }
                $after_discount_price = apply_filters('event_magic_booking_get_final_price', $after_discount_price, $order_info);
                // coupon code section
                if(isset($order_info['coupon_discount']) && !empty($order_info['coupon_discount'])){
                    $after_discount_price = $after_discount_price - $order_info['coupon_discount'];
                }
            }
            $total_amount = (!empty($payment_log) && isset($payment_log['total_amount']) ? $payment_log['total_amount'] : (isset($order_info['subtotal']) ? $order_info['subtotal'] : '') );
            if( !empty( $payment_log ) && isset( $payment_log['payment_gateway'] ) && $payment_log['payment_gateway'] == 'none' && !isset( $payment_log['total_amount'] ) ) {
                $total_amount = 0;
            }
            return ( !empty( $total_amount ) ? ep_price_with_position( $total_amount ) : ep_price_with_position( $after_discount_price ) );
        }
    }
}

function ep_get_local_timestamp( $timestamp = 0 ) {
    $stamp_diff = floatval( get_option('gmt_offset') ) * 3600;
    if ( $timestamp == 0 )
        return time() + $stamp_diff;
    else
        return $timestamp + $stamp_diff;
}

// check if phone number is valid
function is_valid_phone( $phone_number ) {
    $phone_pattern = '^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$^';
    return preg_match( $phone_pattern, $phone_number );
}

// check if site url is valid
function is_valid_site_url( $website ) {
    $url_pattern = '^https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()!@:%_\+.~#?&\/\/=]*)^';
    return preg_match( $url_pattern, $website );
}

/**
 * Get datepicket format
 * 
 * @param int $language 1 for PHP and 2 for JS
 * 
 * @return string
 */
function ep_get_datepicker_format( $language = 1 ) {
    $format = 'Y-m-d';
    if( $language == 2 ) {
        $format = 'yy-mm-dd';
    }
    if( ! empty( ep_get_global_settings( 'datepicker_format' ) ) ) {
        $datepicker_format = explode( '&', ep_get_global_settings( 'datepicker_format' ) );
        if( ! empty( $datepicker_format ) ) {
            $format = $datepicker_format[1];
            if( $language == 2 ) {
                $format = $datepicker_format[0];
            }
        }
    }
    return $format;
}

// get ajax url with scheme
function ep_get_ajax_url( $url = null, $scheme = null ) {
    // If $scheme is passed use it, otherwise test if the current request is HTTPS
    $scheme = $scheme ? $scheme : ( is_ssl() ? 'https' : 'http' );
    return admin_url( "admin-ajax.php", $scheme );
}

// get day with position
function ep_get_day_with_position( $day ) {
    $suffix = $day;
    if( ! empty( $day ) ) {
        if( $day < 11 || $day > 20 ) {
            if( strpos( (string)$day, '1' ) !== false ) {
                $suffix .= 'st';
            } else if( strpos( (string)$day, '2' ) !== false ) {
                $suffix .= 'nd';
            } else if( strpos( (string)$day, '3' ) !== false ) {
                $suffix .= 'rd';
            } else{
                $suffix .= 'th';
            }
        } else{
            $suffix .= 'th';
        }
    }
    return $suffix;
}

function ep_enabled_reg_captcha(){
    $enabled = 0;
    if( ep_enabled_guest_booking() && ep_get_global_settings('checkout_reg_google_recaptcha') == 1 && !empty(ep_get_global_settings('google_recaptcha_site_key'))){
        $enabled = 1;
    }
    return $enabled;
}
// set old event prime function call
function event_magic_instance() {
    return EP();
}

// old extension data
function ep_old_ext_data() {
    $old_exts_lists = array( 
        'event-seating.php'               => 'Live Seating', 
        'event-analytics.php'             => 'Event Analytics',
        'event-sponser.php'               => 'Event Sponsors', 
        'event-stripe.php'                => 'Stripe Payments',
        'eventprime-offline.php'          => 'Offline Payments', 
        'eventprime-recurring-events.php' => 'Recurring Events',
        'eventprime-attendees-list.php'   => 'Attendees List',
        'event-coupons.php'               => 'Coupon Codes',
        'event-guest-booking.php'         => 'Guest Bookings',
        'eventprime-more-widgets.php'     => 'Event List Widgets', 
        'event-attendees-booking.php'     => 'Admin Attendee Bookings',
        'event-wishlist.php'              => 'Event Wishlist',
        'eventprime-event-comments.php'   => 'Event Comments',
        'automatic-discounts.php'         => 'Event Automatic Discounts',
        'google-import-export.php'        => 'Google Events Import Export',
        'events-import-export.php'        => 'Events Import Export', 
        'eventprime-mailpoet.php'         => 'EventPrime MailPoet', 
        'woocommerce-integration.php'     => 'WooCommerce Integration',
        'eventprime-zoom-meetings.php'    => 'EventPrime Zoom Integration',
        'event-zapier.php'                => 'Zapier Integration',
        'event-invoices.php'              => 'EventPrime Invoices',
        'sms-integration.php'             => 'Twilio Text Notifications'
    );

    return $old_exts_lists;
}

/**
 * get current time
 */
function ep_get_current_timestamp() {
    $current_timestamp = current_time( 'timestamp' );
    $user_timezone = ep_get_current_user_timezone();
    if( ! empty( $user_timezone ) ) {
        date_default_timezone_set( $user_timezone );
        $current_timestamp = time();
    }
    return $current_timestamp;
}

/**
 * Get seo option page url
 */
function ep_get_seo_page_url( $type ) {
    $enable_seo_urls = ep_get_global_settings( 'enable_seo_urls' );
    if( ! empty( $enable_seo_urls ) ) {
        $seo_urls = (object)ep_get_global_settings( 'seo_urls' );
        $url = '';
        if( ! empty( $seo_urls ) ) {
            if( $type == 'event' ) {
                $url = ( ! empty( $seo_urls->event_page_type_url ) ) ? $seo_urls->event_page_type_url : 'event' ;
            }
            if( $type == 'performer' ) {
                $url = ( ! empty( $seo_urls->performer_page_type_url ) ) ? $seo_urls->performer_page_type_url : 'performer' ;
            }
            if( $type == 'organizer' ) {
                $url = ( ! empty( $seo_urls->organizer_page_type_url ) ) ? $seo_urls->organizer_page_type_url : 'organizer' ;
            }
            if( $type == 'venue' ) {
                $url = ( ! empty( $seo_urls->venues_page_type_url ) ) ? $seo_urls->venues_page_type_url : 'venue' ;
            }
            if( $type == 'event-type' ) {
                $url = ( ! empty( $seo_urls->types_page_type_url ) ) ? $seo_urls->types_page_type_url : 'event-type' ;
            }
            if( $type == 'sponsor' ) {
                $url = ( ! empty( $seo_urls->sponsor_page_type_url ) ) ? $seo_urls->sponsor_page_type_url : 'sponsor' ;
            }
            return $url; 
        }
    }
    return $type;
}

/**
 * Get venue type label
 */
function ep_get_venue_type_label( $type ) {
    if( $type == 'seats' ) {
        return 'Seating';
    } else{
        return 'Standing';
    }
}

// generate slug from the string
function ep_get_slug_from_string( $string ) {
    // Strip html tags
    $text = strip_tags($string);
    // Replace non letter or digits by -
    $text = preg_replace('~[^\pL\d]+~u', '_', $text);
    // Transliterate
    setlocale(LC_ALL, 'en_US.utf8');
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    // Remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);
    // Trim
    $text = trim($text, '-');
    // Remove duplicate -
    $text = preg_replace('~-+~', '_', $text);
    // Lowercase
    $text = strtolower($text);
    // Check if it is empty
    if (empty($text)) { return '---'; }
    // Return result
    return $text;
}
// get calendar local
function ep_get_calendar_locale() {
    $locale = get_locale();
    $locale = ( empty( $locale ) || is_null( $locale ) ) ? 'en' : $locale;
    if( strlen( $locale ) > 5 ) {
        $locale = substr( $locale, 0, 5 );
    }
    $locale = strtolower( $locale );
    $locale = str_replace( '_', '-', $locale );
    if( in_array( $locale, EventM_Constants::get_calendar_locales() ) ) {
        return $locale;
    } else {
        return substr( $locale, 0, 2 );
    }
}

// show free label on 0 price
function ep_show_free_event_price( $price ) {
    if( ! empty( ep_get_global_settings( 'hide_0_price_from_frontend' ) ) ) {
        esc_html_e( 'Free', 'eventprime-event-calendar-management' );
    } else{
        echo esc_html( ep_price_with_position( $price ) );
    }
}

// convert front end time to 24 hours format
function ep_convert_time_with_format( $ep_time ) {
    $timeIn24HourFormat = $ep_time;
    $time_format = ep_get_global_settings( 'time_format' );
    if( ! empty( $time_format ) && $time_format == 'HH:mm' ) {
        $dateTime = new DateTime( $ep_time );
        $timeIn24HourFormat = $dateTime->format('H:i');
    }
    return $timeIn24HourFormat;
}