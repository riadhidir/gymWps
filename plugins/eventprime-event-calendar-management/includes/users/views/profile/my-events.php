<div class="ep-tab-content ep-item-hide" id="ep-list-my-events" role="tabpanel" aria-labelledby="#list-allbookings-list">
    <?php if( ! empty( $args->submitted_events ) && count( $args->submitted_events ) ) {?>
        <div class="ep-box-row ep-mb-4">
            <div class="ep-box-col-12 ep-border-left ep-border-3 ep-ps-3 ep-border-warning ep-mb-4">
                <span class="ep-text-uppercase ep-fw-bold ep-text-small">
                    <?php esc_html_e( 'My Events', 'eventprime-event-calendar-management');?>
                </span>
            </div>
        </div>
        <!-- <div class="ep-box-row mb-4">
            <div class="ep-box-col-12 ep-text-center">
                <div class="ep-btn-group btn-group-sm">
                    <a href="#" class="ep-btn ep-btn-outline-dark active" aria-current="page">Submitted</a>
                    <a href="#" class="ep-btn ep-btn-outline-dark">Organized</a>
                    <a href="#" class="ep-btn ep-btn-outline-dark">Sponsored</a>
                </div>
            </div>
        </div> -->
        <div class="ep-box-row ep-mb-2">
            <div class="ep-box-col-12 ep-text-small ep-text-end">
                <span class="ep-fw-bold">
                    <?php echo absint( count( $args->submitted_events ) );?>
                </span>
                <span class="">
                    <?php esc_html_e( 'events found', 'eventprime-event-calendar-management');?>
                </span>
            </div>
        </div>
        <?php foreach( $args->submitted_events as $event ) {?>
            <div class="ep-my-booking-row ep-box-row ep-border ep-rounded ep-overflow-hidden ep-text-small ep-mb-4">
                <div class="ep-box-col-2 ep-m-0 ep-p-0">
                    <img class="ep-event-card-img" src="<?php echo esc_url( $event->image_url );?>" style="width:100%;" alt="<?php echo esc_html( $event->name );?>">
                </div>
                <div class="ep-box-col-6 ps-4 ep-d-flex ep-items-center ep-justify-content-between">
                    <div>
                        <div class=""><?php echo esc_html( $event->name );?></div>
                        <?php if( ! empty( $event->venue_details ) && $event->venue_details->em_address ) {?>
                            <div class="ep-text-muted ep-text-small">
                                <?php echo esc_html( $event->venue_details->em_address );?>
                            </div><?php
                            if( ! empty( $event->em_start_date ) ) {
                                //echo esc_html( ' / ' );
                            }
                        }
                        if( ! empty( $event->em_start_date ) ) {
                            echo esc_html( ep_timestamp_to_date( $event->em_start_date, 'dS M Y', 1 ) );
                        }
                        if( ! empty( $event->em_start_date ) ) {
                            echo ', ' . esc_html( $event->em_start_time );
                        }?>
                    </div>
                </div>
                
                <div class="ep-box-col-4 ep-d-flex ep-justify-content-between ep-align-items-center">
                    <div class="">
                        <div class="ep-mb-1">
                            <span class="ep-bg-success ep-p-1 ep-text-white ep-rounded-1 ep-text-small">
                                <?php esc_html_e( EventM_Constants::$status[$event->post_status], 'eventprime-event-calendar-management');?>
                            </span>
                        </div>
                    </div>
                    <div class="ep-text-end">
                        <div class="ep-btn-group ep-btn-group-sm">
                            <?php 
                            $submit_page_id = ep_get_global_settings( 'event_submit_form' );
                            if($submit_page_id){
                                $submit_event_url =  get_permalink($submit_page_id);
                            }
                            $submit_event_url = add_query_arg(array('event_id' => $event->id),$submit_event_url);
                            ?>
                            <a href="<?php echo esc_url($submit_event_url);?>" target="__blank" class="ep-btn ep-btn-warning">
                                <span class="material-icons-round ep-fs-6">edit</span>
                            </a>
                            <a href="javascript:void(0);" onclick="ep_event_download_attendees('<?php echo esc_html( $event->id );?>')" class="ep-btn ep-btn-warning" title="<?php echo __("Download Attendees", 'eventprime-event-calendar-management'); ?>">
                                <span class="material-icons-round ep-fs-6">list</span>
                            </a>
                            <a href="#" class="ep-btn ep-btn-danger">
                                <span class="material-icons-round ep-fs-6">delete_forever</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div><?php
        }?>

        <!-- <div class="ep-box-row">
            <div class="ep-box-col-12 ep-mb-3 ep-text-center">
                <button type="button" class="ep-btn ep-btn-outline-dark ep-btn-sm">Load More</button>
            </div>
        </div> --><?php
    } else{?>
        <div class="ep-box-row">
            <div class="ep-box-col-12 ep-border-left ep-border-3 ep-ps-3 ep-border-warning ep-mb-4">
                <span class="text-uppercase fw-bold small">
                    <?php esc_html_e( 'No events found', 'eventprime-event-calendar-management');?>
                </span>
            </div>
        </div><?php
    }?>
</div>