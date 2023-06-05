<?php if( ! empty( $args->event->em_start_date ) && ep_show_event_date_time( 'em_start_date', $args->event ) ) {?>
    <div class="ep-box-col-12 ep-sl-event-date" id="ep-sl-event-start">
        <span class="ep-fs-4 ep-fw-bold" id="ep_single_event_start_date">
            <?php echo esc_html( $args->event->fstart_date );?>
        </span>
        <?php if( ! empty( $args->event->em_start_time ) && ep_show_event_date_time( 'em_start_time', $args->event ) ) {?>
            <span class="ep-text-dark ep-fs-6" id="ep_single_event_start_time">
                <?php echo esc_html( ep_convert_time_with_format( $args->event->em_start_time ) );?>
            </span><?php
        }?>
        <?php if( count( $args->event->child_events ) > 0 ) { //if event has recurring events ?>
            <span class="material-icons-outlined ep-bg-dark ep-text-white ep-rounded-5 ep-p-2 ep-cursor-pointer ep-text-small ep-ml-1 ep-cursor" ep-modal-open="ep-get-other-date" id="ep_event_more_child_dates">event_repeat</span><?php
        }?>
    </div>

    <div class="ep-box-col-12 ep-text-muted ep-text-small" id="ep-sl-event-end">
        <?php if( $args->event->em_all_day == 1 ) {?>
            <span class="ep-text-small ep-text-uppercase">
                <?php esc_html_e( 'All Day', 'eventprime-event-calendar-management' );?> 
            </span><?php
        } else{?>
            <span class="ep-text-small ep-text-uppercase">
                <?php esc_html_e( 'Until', 'eventprime-event-calendar-management' );?> 
                <span id="ep_single_event_end_date_time">
                    <?php echo esc_html( $args->event->fend_date );
                    if( ! empty( $args->event->em_end_time ) && ep_show_event_date_time( 'em_end_time', $args->event )) {
                        echo ', ' . esc_html( ep_convert_time_with_format( $args->event->em_end_time ) );
                    }?>
                </span>
            </span>     
            <span class="ep-text-small ep-ml-4" id="ep_single_event_start_end_diff">
                <?php echo esc_html( $args->event->start_end_diff ); ?>
            </span><?php
        }?>
    </div>

    <?php 
    //Additional dates modal for recurring events
    if( count( $args->event->child_events ) > 0 ) { ?>
        <div class="ep-modal ep-modal-view" id="ep-event-checkout-modal" ep-modal="ep-get-other-date" style="display: none;">
            <div class="ep-modal-overlay" ep-modal-close="ep-get-other-date"></div>
            <div class="ep-modal-wrap ep-modal-lg">
                <div class="ep-modal-content">
                    <div class="ep-modal-titlebar ep-d-flex ep-items-center ep-py-2">
                        <div class="ep-modal-title ep-px-3 ep-fs-5 ep-my-2">
                            <?php esc_html_e( 'Additional Dates', 'eventprime-event-calendar-management' );?> 
                        </div>
                        <span class="ep-modal-close" id="ep_close_other_date_modal" ep-modal-close="ep-get-other-date"><span class="material-icons-outlined">close</span></span>
                    </div>
                    <div class="ep-modal-body">
                        <div class="ep-box-row ep-mb-3">
                            <div class="ep-box-col-12">
                                <ul class="ep-nav-tabs ep-mb-3 ep-m-0" role="tablist">
                                    <li class="ep-tab-item" role="presentation"><a href="javascript:void(0);" data-tag="ep-sl-nav-calendar-tab" class="ep-tab-link ep-tab-active"><?php esc_html_e( 'Event Calendar', 'eventprime-event-calendar-management' );?></a></li>
                                    <li class="ep-tab-item" role="presentation"><a href="javascript:void(0);" data-tag="ep-sl-nav-upcoming" class="ep-tab-link"><?php esc_html_e( 'Upcoming Event Dates', 'eventprime-event-calendar-management' );?></a></li>
                                    <?php //<li class="ep-tab-item" role="presentation"><a href="javascript:void();" data-tag="ep-sl-nav-search" class="ep-tab-link">Search Dates</a></li>?>
                                </ul>    
                                <div id="ep-tab-container" class="ep-box-w-100">
                                    <div class="ep-tab-content" id="ep-sl-nav-calendar-tab" role="tabpanel" >                                        
                                        <div class="ep-box-row">
                                            <div class="ep-box-col-12">
                                                <span><?php esc_html_e( 'Click on an event occurance to go to its page', 'eventprime-event-calendar-management' );?> </span>
                                                <div id="ep_single_event_recurring_events" class="ep-mt-3"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ep-tab-content  ep-item-hide" id="ep-sl-nav-upcoming" role="tabpanel">
                                        <div class="ep-d-flex ep-flex-wrap ep-py-5">
                                            <?php foreach( $args->event->child_events as $events ) {?>
                                                <span class="ep-text-primary ep-text-small ep-p-2 ep-rounded ep-border ep-border-primary ep-mr-2 ep-mt-2">
                                                    <a href="<?php echo esc_url( $events->event_url );?>" target="_blank">
                                                        <?php echo esc_html( ep_timestamp_to_date( $events->em_start_date, 'd M', 1 ) );?>
                                                    </a>
                                                </span><?php
                                            }?>
                                        </div>
                                    </div>
                                    <div class="ep-tab-content ep-item-hide" id="ep-sl-nav-search" role="tabpanel">Weather</div>
                                </div>   
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><?php
    }
} elseif( ! empty( $args->event->em_event_date_placeholder ) ) {
    if( $args->event->em_event_date_placeholder == 'tbd' ) {
        $tbd_icon_file = EP_BASE_URL .'/includes/assets/images/tbd-icon.png';?>
        <span class="ep-card-event-date-start ep-text-primary">
            <img src="<?php echo esc_url( $tbd_icon_file );?>" width="35" />
        </span><?php
    } else {
        if( ! empty( $args->event->em_event_date_placeholder_custom_note ) ) {?>
            <div class="ep-box-col-12 ep-sl-event-date" id="ep-sl-event-start">
                <span class="ep-fs-4 ep-fw-bold" id="ep_single_event_start_date">
                    <?php echo esc_html( $args->event->em_event_date_placeholder_custom_note );?>
                </span>
            </div><?php
        }
    }
}?>
