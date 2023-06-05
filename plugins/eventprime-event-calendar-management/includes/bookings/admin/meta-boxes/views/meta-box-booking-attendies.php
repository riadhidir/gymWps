<?php
/**
 * Booking meta box html
 */
defined( 'ABSPATH' ) || exit;
$booking_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Booking_Controller_List' );
$booking_id = $post->ID;
$post_meta = get_post_meta( $booking_id );
$booking = $booking_controller->load_booking_detail( $booking_id );?>

<div class="panel-wrap ep_event_metabox">
    <?php if( ! empty( $booking->em_attendee_names ) && count( $booking->em_attendee_names ) > 0 ) {?>
        <div class="ep-border-bottom">
            <div class="ep-py-3 ep-ps-3 ep-fw-bold ep-text-uppercase ep-text-small">
                <?php esc_html_e( 'Attendees', 'eventprime-event-calendar-management' );?>
            </div>
        </div>
        <?php $booking_attendees_field_labels = array();
        if( isset( $booking->em_old_ep_booking ) && ! empty( $booking->em_old_ep_booking ) ) {
            $table_head = array_keys( $booking->em_attendee_names[0] );?>
            <div class="ep-p-4">
                <table class="ep-table ep-table-hover ep-text-small ep-table-borderless">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <?php foreach( $table_head as $labels ){?>
                                <th scope="col">
                                    <?php echo esc_html( $labels );?>
                                </th><?php
                            }?>
                            <!-- <th scope="col"></th> -->
                        </tr>
                    </thead>
                    <tbody class=""><?php $att_count = 1;
                        foreach( $booking->em_attendee_names as $key => $attendee_data ) {
                            $table_data = array_values( $attendee_data );?>
                            <tr>
                                <th scope="row" class="py-3"><?php echo esc_html( $att_count );?></th><?php
                                foreach( $table_data as $data ) {?>
                                    <td class="py-3"><?php echo esc_html( $data );?></td><?php
                                }
                                $att_count++;?>
                            </tr><?php
                        }?>
                    </tbody>
                </table>
            </div><?php
        } else{
            $is_new_format = $att_count = 1;
            foreach( $booking->em_attendee_names as $ticket_id => $attendee_data ) {
                if( isset( $attendee_data[1] ) ) {
	                $booking_attendees_field_labels = ep_get_booking_attendee_field_labels( $attendee_data[1] );
				} else{
					$att_key_array = array();
					foreach( $attendee_data as $att_key => $att_value ) {
						$att_key_array[] = $att_key;
					}
					$booking_attendees_field_labels = array_unique( $att_key_array );
					$is_new_format = 0;
				}?>
                <div class="ep-p-4">
                    <div class="ep-mb-3 ep-fw-bold ep-text-small">
                        <?php echo esc_html( get_event_ticket_name_by_id_event( $ticket_id, $booking->event_data ) );?>
                    </div>
                    <table class="ep-table ep-table-hover ep-text-small ep-table-borderless">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <?php foreach( $booking_attendees_field_labels as $labels ){?>
                                    <th scope="col">
                                        <?php echo esc_html( $labels );?>
                                    </th><?php
                                }?>
                                <!-- <th scope="col"></th> -->
                            </tr>
                        </thead>
                        <tbody class=""><?php $att_count = 1;
                            foreach( $attendee_data as $booking_attendees ) {?>
                                <tr>
                                    <th scope="row" class="py-3"><?php echo esc_html( $att_count );?></th><?php 
                                    $booking_attendees_val = (is_array( $booking_attendees ) ? array_values( $booking_attendees ) : $booking_attendees );
                                    foreach( $booking_attendees_field_labels as $labels ){?>
                                        <td class="py-3"><?php
                                            if( $is_new_format == 0 ) {
                                                $at_val = $booking_attendees_val;
                                            } else{
                                                $formated_val = ep_get_slug_from_string( $labels );
                                                $at_val = '---';
                                                foreach( $booking_attendees_val as $key => $baval ) {
                                                    if( isset( $baval[$formated_val] ) && ! empty( $baval[$formated_val] ) ) {
                                                        $at_val = $baval[$formated_val];
                                                        break;
                                                    }
                                                }
                                                if( empty( $at_val ) ) {
                                                    $formated_val = strtolower( $labels );
                                                    foreach( $booking_attendees_val as $key => $baval ) {
                                                        if( isset( $baval[$formated_val] ) && ! empty( $baval[$formated_val] ) ) {
                                                            $at_val = $baval[$formated_val];
                                                            break;
                                                        }
                                                    }
                                                }
                                            }
                                            echo esc_html( $at_val );?>
                                        </td><?php
                                    }?>
                                </tr><?php
                                $att_count++;
                            }?>
                        </tbody>
                    </table>
                </div><?php
            }
        }
    }?>
</div>