<?php
/**
 * Booking meta box html
 */
defined( 'ABSPATH' ) || exit;
$booking_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Booking_Controller_List' );

$booking_id = $post->ID;
$post_meta = get_post_meta($booking_id);
$booking = $booking_controller->load_booking_detail($booking_id);  
$event_name = $post->post_title;
$payment_method = '';
if( ! empty( $booking->em_payment_method ) ) {
    $payment_method = esc_html( ucfirst( $booking->em_payment_method ) );
} else if( ! empty( $booking->em_order_info['payment_gateway'] ) ) {
    $payment_method = esc_html( ucfirst( $booking->em_order_info['payment_gateway'] ) );
}
$event_date = isset($booking->em_date) ? $booking->em_date : '';
$payment_log = isset($booking->em_payment_log) ? $booking->em_payment_log : array();
$status = EventM_Constants::$status;
?>
<div class="emagic">
    <div class="ep-box-wrap">
        <div class="ep-box-row ep-my-3 ep-p-2">
            <div class="ep-box-col-12">
                <div class="ep-booking-title ep-m-0 ep-fs-4">
                    <?php echo sprintf(__('Booking #%d :- %s', 'eventprime-event-calendar-management'), $booking_id, $event_name);?>
                </div>
                <div class="ep-payment-method ep-text-muted ep-fs-5">
                    <?php 
                    
                    echo sprintf(__('Payment via %s', 'eventprime-event-calendar-management'), $payment_method);?>
                </div>
            </div>
        </div>
        
        <div class="ep-box-row">
            <div class="ep-box-col-12 ep-box-pr-0">
                <h3 class="ep-fw-bold ep-my-3 ep-fs-6"><?php esc_html_e('General','eventprime-event-calendar-management');?></h3>
            </div>
            <div class="ep-box-row">
                <div class="ep-box-col-3 ep-boo-date">
                    
                    <div class="ep-gen-date  ep-fw-bold"><?php esc_html_e('Date Created:','eventprime-event-calendar-management');?></div>
                    <div><?php echo date('Y-m-d h:i A', $event_date);?></div>
                </div>
                
                <div class="ep-box-col-3 ep-boo-status">
                    
                    <div class="ep-gen-date ep-fw-bold"><?php esc_html_e('Booking Status:','eventprime-event-calendar-management');?></div>
                    <div class="ep-booking-status-<?php echo $booking->em_status; ?> ep-booking-status-completed ep-py-2 ep-align-top ">
                        <?php
                        $booking_status = '';
                        if( ! empty( $booking->em_status ) ) {
                            $booking_status = $booking->em_status;
                            echo esc_html( ucfirst( $booking->em_status ) );
                        } else{
                            $booking_status = $booking->post_data->post_status;
                            esc_html_e( EventM_Constants::$status[$booking_status], 'eventprime-event-calendar-management' );
                        }?>
                        <?php if(! empty( $booking_status ) && ( $booking_status == 'completed' || $booking_status == 'pending' || $booking_status == 'draft' || $booking_status == 'publish' ) ):?>
                            <span class="icon-asset material-icons-outlined edit-booking-status ep-cursor ep-text-primary" id="ep-booking-status-edit">Edit</span>
                        <?php endif;?>
                    </div>

                    <?php if( ! empty( $booking_status ) && ( $booking_status == 'completed' || $booking_status == 'pending' || $booking_status == 'draft' || $booking_status == 'publish' ) ):?>
                        <div class="ep-booking-status-edit" id="ep-booking-status-child" style="display:none;">
                            <div class="ep-form-row">
                                <select class="ep-booking-status" id='ep-booking-status'>
                                    <?php foreach($status as $key => $label){?>
                                        <option value="<?php echo $key;?>" <?php echo $booking_status == $key ? 'selected': '';?>><?php echo esc_attr($label)?></option>
                                    <?php } ?>
                                </select>
                                <button type="button" id="update_booking_status" class="button"><?php esc_html_e('Update','eventprime-event-calendar-management');?></button>
                                <span class="spinner ep-booking-status-spinner"></span>
                            </div>
                        </div>
                    <?php endif;?>

                    <?php if( ! empty( $booking_status ) && $booking_status == 'cancelled' ):?>
                        <button class="ep-btn" type="button" id="ep_refunded_btn" onclick="ep_booking_refund_status(<?php echo $booking_id;?>,'<?php echo $payment_method;?>','<?php echo 'refunded';?>');"><?php esc_html_e('Mark as Refunded','eventprime-event-calendar-management');?></button>
                    <?php endif;?>
                </div>
                
                <div class="ep-box-col-3 ep-payment-status">
                    <div class="ep-gen-date ep-fw-bold"><?php esc_html_e('Payment Status:','eventprime-event-calendar-management');?></div>
                    <div class="ep-payment-status ep-booking-status ep-py-2 ep-align-top ">
                        <?php 
                        if(strtolower($payment_method) == 'offline'){
                            echo isset($payment_log['offline_status']) ? $payment_log['offline_status'] : '';
                        }else{
                            echo isset($payment_log['payment_status']) ? $payment_log['payment_status'] : '';
                        }?>
                        
                         <?php do_action( 'ep_booking_payment_status', $booking_id );?>
                    </div>    
                 
                </div>
                
                <div class="ep-box-col-3 ep-boo-user">
                    <div class="ep-gen-user ep-fw-bold"><?php esc_html_e('User:','eventprime-event-calendar-management');?></div>
                    <div>    
                        <?php $user_id = isset($booking->em_user) ? (int) $booking->em_user : 0;
                        if($user_id){
                            $user = get_userdata($user_id);
                            echo $user->display_name.'('.$user->user_email.')';
                        }else{
                            $order_info = $booking->em_order_info;
                            if( ! empty( $order_info ) && ! empty( $order_info['user_email'] ) ) {
                                echo esc_html( $order_info['user_email'] );
                            } else{
                                echo esc_html__( 'Guest', 'eventprime-event-calendar-management' );
                            }
                        }?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>