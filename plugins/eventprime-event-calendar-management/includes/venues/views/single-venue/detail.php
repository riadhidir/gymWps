<?php
/**
 * View: Single Venue - Detail
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/venues/single-venue/detail.php
 *
 */
defined( 'ABSPATH' ) || exit;
?>
<div class="ep-box-col-10">
    <div class="ep-single-box-info">
        <div class="ep-single-box-content">
            <div class="ep-single-box-title-info">
                <h3 class="ep-single-box-title ep-venue-name" title="<?php echo esc_attr( $args->venue->name ); ?>">
                    <?php echo esc_html( $args->venue->name ); ?>
                </h3>
                <ul class="ep-single-box-details-meta ep-mx-0 ep-my-2 ep-p-0">
                    <?php if ( $args->venue->em_established ) { ?>
                        <li class="ep-d-inline-flex ep-box-w-100"> 
                            <div class="em_color ep-fw-bold">
                                <?php esc_html_e('Established', 'eventprime-event-calendar-management'); ?> :  
                            </div>
                            <div class="kf-event-attr-value">
                                  <?php echo date_i18n( get_option('date_format'), $args->venue->em_established ); ?>
                            </div>
                        </li><?php 
                    }?>

                    <li class="ep-d-inline-flex ep-box-w-100 ">
                        <div class="ep-event-type ep-fw-bold">
                            <?php echo esc_html__( 'Type', 'eventprime-event-calendar-management' ). ' : '. esc_html__( ep_get_venue_type_label( $args->venue->em_type ), 'eventprime-event-calendar-management' ); ?>
                        </div>
                    </li>

                    <?php if ( ! empty( $args->venue->em_seating_organizer ) ) { ?>
                        <li class="ep-d-inline-flex ep-box-w-100">
                            <div class="em_color ep-fw-bold">
                                <?php esc_html_e( 'Coordinator', 'eventprime-event-calendar-management' ); ?> :  
                            </div>
                            <div class="kf-event-attr-value dbfl ep-ml-1">
                                <?php echo esc_html( $args->venue->em_seating_organizer ); ?>
                            </div>
                        </li><?php 
                    }?>

                    <?php if ( ! empty( $args->venue->em_address ) && isset( $args->venue->em_display_address_on_frontend ) && $args->venue->em_display_address_on_frontend == 1 ) { ?>
                        <li class="ep-d-inline-flex ep-box-w-100">
                            <div class="em_color ep-fw-bold ep-d-inline-flex" style="min-width: 60px;">
                                <?php esc_html_e( 'Address', 'eventprime-event-calendar-management' ); ?> :  
                            </div>
                            <div class="kf-venue-address ep-ml-1">
                                <?php echo esc_html( $args->venue->em_address ); ?>
                                <span class="ep-vanue-directions ep-py-2">
                                    <a target="blank" href='https://www.google.com/maps?saddr=My+Location&daddr=<?php echo urlencode($args->venue->em_address); ?>&dirflg=w' class="ep-d-inline-flex ep-align-items-center">
                                        <?php esc_html_e('Directions', 'eventprime-event-calendar-management'); ?>
                                        <span class="material-icons-outlined ep-fs-6 ep-text-primary ep-align-text-bottom">open_in_new</span>
                                    </a>
                                </span>
                            </div>
                        </li><?php 
                    }?>
                </ul>
            </div> 

            <?php if ( ! empty( $args->venue->em_facebook_page ) || ! empty( $args->venue->em_instagram_page ) ) { ?> 
                <div class="ep-single-box-social">
                    <?php if ( ! empty( $args->venue->em_facebook_page ) ) {?>
                        <a href="<?php echo esc_url( $args->venue->em_facebook_page ); ?>" target="_blank" title="<?php esc_html_e( 'Facebook Page' ); ?>" class="ep-facebook-f"> 
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"> 
                                <path d="M504 256C504 119 393 8 256 8S8 119 8 256c0 123.78 90.69 226.38 209.25 245V327.69h-63V256h63v-54.64c0-62.15 37-96.48 93.67-96.48 27.14 0 55.52 4.84 55.52 4.84v61h-31.28c-30.8 0-40.41 19.12-40.41 38.73V256h68.78l-11 71.69h-57.78V501C413.31 482.38 504 379.78 504 256z"/>
                            </svg>
                        </a><?php
                    }
                    if ( ! empty( $args->venue->em_instagram_page ) ) {?>
                        <a href="<?php echo esc_url( $args->venue->em_instagram_page ); ?>" target="_blank" title="<?php esc_html_e( 'Instagram Page' ); ?>" class="ep-instagram-f"> 
                           <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                            <path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"></path>
                          </svg>
                            
                        </a><?php
                    }?>
                </div><?php 
            }?>

            <div class="ep-single-box-summery ep-single-box-desc">
                <?php
                if ( isset( $args->venue->description ) && $args->venue->description !== '' ) {
                    echo wpautop( wp_kses_post( $args->venue->description ) );
                } else {
                    esc_html_e( 'No description available', 'eventprime-event-calendar-management' );
                }?>
            </div>

            <!-- single venue gallery images -->
            <?php if ( is_array( $args->venue->em_gallery_images ) && count( $args->venue->em_gallery_images ) > 1 ) { ?>
                <div class="em_photo_gallery em-single-venue-photo-gallery" >
                    <div class="ep-row-heading">
                        <span class="ep-row-title ep-fw-bold ep-mb-3 ep-fs-6">
                            <?php esc_html_e( 'Gallery', 'eventprime-event-calendar-management' ); ?>
                        </span>
                    </div>
                    <div id="ep_venue_gal_thumbs" class="ep-d-inline-flex ep-flex-wrap ep-mb-4">
                        <?php foreach ( $args->venue->em_gallery_images as $id ) { ?>
                            <a href="javascript:void(0);" rel="gal" class="ep_open_gal_modal ep-rounded-1 ep-mr-2" ep-modal-open="ep-venue-gal-modal">
                                <?php echo wp_get_attachment_image( $id, array(50, 50),["class" => "ep-rounded-1","alt"=>"Gallery Image"] ); ?>
                            </a><?php 
                        } ?>
                    </div><?php
                    if( ! empty( $args->venue->em_gallery_images ) && count( $args->venue->em_gallery_images ) > 0 ) {?>
                        <div class="ep_venue_gallery_modal_container ep-modal ep-modal-view" id="ep-venue-gallery-modal"  ep-modal="ep-venue-gal-modal" style="display: none;" >
                            <div class="ep-modal-overlay" ep-modal-close="ep-venue-gal-modal"></div>
                            <div class="ep-modal-wrap ep-modal-lg">
                                <div class="ep-modal-content">
                                    <div class="ep-modal-titlebar ep-d-flex ep-items-center ep-py-2">
                                        <div class="ep-modal-title ep-px-3 ep-fs-5 ep-my-2">
                                            <?php esc_html_e( 'Gallery', 'eventprime-event-calendar-management' ); ?> 
                                        </div>
                                        <span class="ep-modal-close" ep-modal-close="ep-venue-gal-modal"><span class="material-icons-outlined">close</span></span>
                                    </div>
                                    <div class="ep-modal-body">
                                        <ul class="ep-rslides" id="ep_venue_gal_modal">
                                            <?php foreach ( $args->venue->em_gallery_images as $id ) {
                                                $url = wp_get_attachment_url( $id, 'large' )?>
                                                <li>
                                                    <img src="<?php echo esc_url( $url ); ?>" >
                                                </li><?php 
                                            }?>
                                        </ul>
                                        <div class="ep-single-event-nav"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php }?>
                </div><?php 
            }
            
            if ( ! empty( ep_get_global_settings( 'gmap_api_key' ) ) && ! empty( $args->venue->em_address ) ) { ?>
                <div class="ep-single-venue-map">
                    <div class="em-venue-direction" id="ep_venue_load_map_data" data-venue="<?php echo esc_attr( json_encode( $args->venue ) ); ?>">
                        <div id="em_single_venue_map_canvas" style="height:400px;"></div>
                    </div> 
                </div><?php 
            }?>
        </div>
    </div>
</div>