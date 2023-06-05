<div class="ep-tab-content ep-item-hide" id="ep-list-all-bookings" role="tabpanel" aria-labelledby="#list-allbookings-list">
    <?php if( ! empty( $args->all_bookings ) && count( $args->all_bookings ) > 0 ) {?>
        <div class="ep-box-row">
            <div class="ep-box-col-12 ep-border-left ep-border-3 ep-ps-3 ep-border-warning">
                <span class="text-uppercase fw-bold small">
                    <?php esc_html_e( 'My Bookings', 'eventprime-event-calendar-management');?>
                </span>
            </div>
        </div>

        <!-- <div class="ep-box-row ep-mb-3 ep-border ep-ep-rounded ep-p-2">
            <div class="ep-box-col-auto ep-p-0">
                <select class="ep-form-select ep-form-select-sm" aria-label="Default select example">
                    <option selected="">All Bookings</option>
                    <option value="1">Completed</option>
                    <option value="2">Cancelled</option>
                </select>
            </div>
            <div class="ep-box-col-auto">
                <input type="date" class="ep-form-control ep-form-control-sm">
            </div>
            <div class="ep-box-col-auto">
                <input type="date" class="ep-form-control ep-form-control-sm">
            </div>
        </div> -->
        
        <div class="ep-box-row">
            <div class="ep-box-col-12 ep-text-small ep-mb-3 ep-text-end">
                <span class="ep-fw-bold">
                    <?php echo count( $args->all_bookings );?>
                </span>
                <span class="">
                    <?php esc_html_e( 'events found', 'eventprime-event-calendar-management');?>
                </span>
            </div>
        </div>
        
        <?php foreach( $args->all_bookings as $booking ) {
            if( ! empty( $booking->event_data ) && ! empty( $booking->event_data->id ) ) {
                $div_cancel_class = '';$btn_cancel_class = 'ep-btn-warning';
                if( $booking->em_status == 'cancelled' ) {
                    $div_cancel_class = 'ep-bg-danger ep-bg-opacity-10';
                    $btn_cancel_class = 'ep-btn-danger';
                }?>
                <div class="ep-my-booking-row ep-box-row ep-border ep-rounded ep-overflow-hidden ep-text-small ep-mb-4 <?php echo esc_attr( $div_cancel_class );?>">
                    <div class="ep-box-col-2 ep-m-0 ep-p-0">
                        <img class="ep-event-card-img" src="<?php echo esc_url( $booking->event_data->image_url );?>">
                    </div>
                    <div class="ep-box-col-6 ps-4 ep-d-flex ep-items-center ep-justify-content-between">
                        <div>
                            <div class="">
                                <?php echo esc_html( $booking->event_data->name );?>
                            </div>
                            <?php if( ! empty( $booking->event_data->venue_details ) && $booking->event_data->venue_details->em_address ) {?>
                                <div class="ep-text-muted ep-text-small">
                                    <?php echo esc_html( $booking->event_data->venue_details->em_address );?>
                                </div><?php
                            }?>
                        </div>
                    </div>
                    <div class="ep-box-col-4 ep-d-flex ep-justify-content-between ep-items-center">
                        <div class="ep-fw-bold ep-event-date">
                            <?php echo esc_html( $booking->event_data->fstart_date );
                            if( ! empty( $booking->event_data->em_start_time ) ) {
                                echo ', ' . esc_html( $booking->event_data->em_start_time );
                            }?>
                        </div>
                        <div class="ep-text-end ep-event-action-bt">
                            <a href="<?php echo esc_url( $booking->booking_detail_url );?>" target="_blank">
                                <button type="button" class="ep-btn <?php echo esc_attr( $btn_cancel_class );?> ep-btn-sm">
                                    <?php esc_html_e( 'Details', 'eventprime-event-calendar-management' ); ?>
                                </button>
                            </a>
                        </div>
                    </div>
                </div><?php
            }
        }?>

        <!-- <div class="ep-box-row">
            <div class="ep-box-col-12 ep-mb-3 ep-text-center">
                <button type="button" class="ep-btn ep-btn-outline-dark ep-btn-sm">Load More</button>
            </div>
        </div> -->
        <?php
    } else{?>
        <div class="ep-box-row">
            <div class="ep-box-col-12 ep-border-left ep-border-3 ep-ps-3 ep-border-warning">
                <span class="text-uppercase fw-bold small">
                    <?php esc_html_e( 'No bookings found', 'eventprime-event-calendar-management');?>
                </span>
            </div>
        </div><?php
    }?>
</div>