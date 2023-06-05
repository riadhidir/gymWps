<div class="ep-box-row">
    <div class="ep-box-col-8 ep-py-5">
        <div class="ep-event-category ep-box-row">
            <?php if( ! empty( $args->event->em_event_type ) ) {
                $styles = '';
                $styles .= ( ! empty( $args->event->event_type_details->em_color ) ? 'background-color:' . $args->event->event_type_details->em_color . ';' : '');
                $styles .= ( ! empty( $args->event->event_type_details->em_type_text_color ) ? 'color:' . $args->event->event_type_details->em_type_text_color . ';' : '');?>
                <div class="ep-box-col-12">
                    <span class="ep-bg-warning ep-py-2 ep-px-3 ep-mr-3" id="ep_single_event_event_type" style="<?php echo esc_attr( $styles );?>">
                        <?php
                        if( ! empty( $args->event->event_type_details ) ) {
                            echo esc_html( $args->event->event_type_details->name );
                        }?>
                    </span>
                </div><?php
            }?>
        </div>
    </div>
    <div class="ep-box-col-4 ep-py-5 ep-align-right ep-di-flex ep-justify-content-end">
        <!-- <span class="material-icons-outlined ep-mr-3">notifications_active</span> -->
        <?php //wishlist
        do_action( 'ep_event_view_wishlist_icon', $args->event, 'event_detail' );?>
        <div class="ep-event-action ep-event-ical-action">
         <span class="material-icons-outlined ep-mr-3 ep-cursor" id="ep_event_ical_export" title="<?php esc_html_e( '+ iCal Export','eventprime-event-calendar-management' ); ?>" data-event_id="<?php echo esc_attr( $args->event->id );?>">file_download</span>
        </div>
        <?php //social sharing
        do_action( 'ep_event_view_social_sharing_icon', $args->event, 'event_detail' );?>
        
        <?php do_action( 'ep_single_event_load_icons', $args );?>
    </div>   
</div>