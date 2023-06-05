<?php
/**
 * View: List View
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/events/list/views/list.php
 *
 */
?>
<?php foreach ( $args->events->posts as $event ) {
    $new_window = ( ! empty( ep_get_global_settings( 'open_detail_page_in_new_tab' ) ) ? 'target="_blank"' : '' );?>
    <div class="ep-box-col-12">
        <div class="ep-event-list-item ep-box-row ep-bg-white ep-border ep-rounded ep-mb-4 ep-overflow-hidden ep-text-small">
            <div class="ep-box-col-3 ep-p-0 ep-bg-light ep-border-right ep-position-relative">
                <?php if ( ! empty( $event->_thumbnail_id ) ) { ?>
                <a href="<?php echo esc_url( $event->event_url ); ?>" <?php echo esc_attr( $new_window );?> class="ep-img-link">
                        <?php echo get_the_post_thumbnail( $event->id, 'post-thumbnail size', array( 'class' => 'ep-img-fluid ep-box-w-100 ep-list-img-fluid' ) ); ?>
                    </a><?php 
                } else {?>
                    <a href="<?php echo esc_url( $event->event_url ); ?>" <?php echo esc_attr( $new_window );?> class="ep-img-link">
                        <img src="<?php echo esc_url( EP_BASE_URL . 'includes/assets/images/dummy_image.png' ); ?>" alt="<?php esc_html_e( 'Dummy Image', 'eventprime-event-calendar-management' ); ?>" class="em-no-image ep-box-w-100 ep-list-img-fluid">
                    </a><?php 
                }?>

                <div class="ep-list-icon-group ep-position-absolute ep-bg-white ep-rounded ep-d-inline-flex">
                    <!--wishlist-->
                    <?php do_action( 'ep_event_view_wishlist_icon', $event, 'event_list' );?>
                    <!--social sharing-->
                    <?php do_action( 'ep_event_view_social_sharing_icon', $event, 'event_list' );?>

                    <?php do_action( 'ep_event_view_event_icons', $event );?>
                </div>
            </div>

            <?php do_action( 'ep_event_view_before_event_title', $event );?>
            
            <div class="ep-box-col-6 ep-p-4 ep-text-small">
                <div class="ep-box-list-item">
                    <div class="ep-box-title ep-box-list-title ep-text-truncate">
                        <!-- Event Type -->
                        <?php if( ! empty( $event->event_type_details ) ) {
                            if( ! empty( $event->event_type_details->name ) ) {?>
                                <div class="ep-text-small ep-text-uppercase ep-text-warning ep-fw-bold"><?php
                                    echo '/ ' . esc_html( $event->event_type_details->name );?>
                                </div><?php
                            }
                        }?>
                        <!-- Event Title -->
                        <a class="ep-fs-5 ep-fw-bold ep-text-dark" data-event-id="<?php echo esc_attr( $event->id ); ?>" href="<?php echo esc_url( $event->event_url ); ?>" <?php echo esc_attr( $new_window );?> rel="noopener">
                            <?php echo esc_html( $event->em_name ); ?>
                        </a>
                    </div>
                    <!-- Venue -->
                    <?php if( ! empty( $event->venue_details ) ) {
                        if( ! empty( $event->venue_details->name ) ) {?>
                            <div class="ep-mb-2 ep-text-small ep-text-muted ep-text-truncate"><?php
                                echo esc_html( $event->venue_details->name );?>
                            </div><?php
                        }
                    }?>
                    <!-- Event Description -->
                    <div class="ep-box-list-desc ep-text-small ep-mt-3 ep-content-truncate ep-content-truncate-line-">
                        <?php if ( ! empty( $event->description ) ) {
                            echo wp_trim_words( wp_kses_post( $event->description ), 35 );
                        }?>
                    </div>

                    <!-- Hook after event description -->
                    <?php do_action( 'ep_event_view_after_event_description', $event );?>
                </div>
            </div>

            <div class="ep-box-col-3 ep-box-list-right-col ep-px-0 ep-pt-4 ep-border-left ep-position-relative">
                <div class="ep-px-3 ep-text-end">
                    <div class="ep-event-list-view-action ep-d-flex ep-flex-wrap ep-content-right">
                        <?php do_action( 'ep_event_view_event_dates', $event, 'list' );?>
                    </div>
                    
                    <!-- Event Price -->
                    <?php do_action( 'ep_event_view_event_price', $event );?>
                    
                    <?php $available_offers = EventM_Factory_Service::get_event_available_offers( $event );
                    if( ! empty( $available_offers ) ) {?>
                        <div class="ep-text-small ep-mb-1">
                            <div class="ep-offer-tag ep-overflow-hidden ep-text-small ep-text-white ep-rounded-1 ep-px-2 ep-py-1 ep-position-relative ep-d-inline-flex">
                                <span class=""><?php echo absint( $available_offers );?> <?php esc_html_e( 'Offers Available', 'eventprime-event-calendar-management' ); ?></span>
                                <div class="ep-offer-spark ep-bg-white ep-position-absolute ep-border ep-border-white ep-border-3">wqdwqd</div>
                            </div>
                        </div><?php
                    }?>

                    <!-- Booking Status -->
                    <?php do_action('ep_events_booking_count_slider', $event);?>
                </div>

                <?php do_action( 'ep_event_view_before_event_button', $event );?>

                <div class="ep-align-self-end ep-position-absolute ep-p-2 ep-bg-white ep-box-w-100" style="bottom:0">
                    <?php //echo EventM_Factory_Service::render_event_booking_btn( $event );?>
                    <?php do_action( 'ep_event_view_event_booking_button', $event );?>
                </div>

            </div>
        </div>
    </div><?php 
}?>


<style>
    .ep-list-icon-group {
        z-index: 99999;
        top: 1rem;
        left: 1rem;
    }

    .ep-list-icon-group a {
        padding: 0.25rem 0.5rem;
        box-shadow: none;
        text-decoration: none;
    }

    .ep-list-icon-group a span {
        vertical-align: middle;
    }

    .ep-offer-tag {
        background-color: rgb(255 127 80);
    }

    .ep-offer-spark {
        animation: offerspark 2s linear forwards normal infinite;
        height: 30px;
        transform: rotate(45deg);
        opacity: 0.5;
        filter: blur(8px);
        bottom: 0;
    }

    @keyframes offerspark {
        from {
            transform: translateX(-200px) rotate(45deg);
        }

        to {
            transform: translateX(200px) rotate(45deg);
        }
    }


    .ep-event-list-item .ep-img-fluid {
        height: 100%;
    }
</style>