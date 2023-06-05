<?php
/**
 * View: Event Calendar
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/events/list/views/calendar.php
 *
 */
?>

<div id="ep_event_calendar" class="ep-mb-5"></div>
 <!-- Event type swatches -->
 <div class="ep-event-types ep-d-flex ep-flex-wrap">
    <?php
    if( isset( $args->event_types ) && ! empty( $args->event_types ) ) {
        foreach( $args->event_types as $type ) {?>
            <div class="ep-event-type ep-event-type ep-mr-2 ep-border ep-p-2 ep-rounded-1 ep-lh-0 ep-di-flex ep-align-items-center ep-mb-2">
                <?php
                $type_url = ep_get_custom_page_url( 'event_types', $type['id'], 'event_type', 'term' );
                $enable_seo_urls = ep_get_global_settings( 'enable_seo_urls' );
                if( isset( $enable_seo_urls ) && ! empty( $enable_seo_urls ) ){
                    $type_url = get_term_link( $type['id'] );
                }?>
                <a href="<?php echo esc_url( $type_url ); ?>"><?php echo esc_html( $type['name'] ); ?></a><?php
                if( ! empty( $type['em_color'] ) && $type['em_color'] != '#' ) {?>
                    <span style="background-color:<?php echo esc_attr( $type['em_color'] ); ?>" class="ep-ml-1"></span><?php
                }?>
            </div><?php 
        } 
    }?>  
</div>      
<!-- Swatches ends here -->