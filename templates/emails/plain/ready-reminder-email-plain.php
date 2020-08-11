<?php
/**
 * Customer email to notify "Ready For Pick Up"
 */
$order = new WC_order( $item_data->order_id );

echo "= " . $email_heading . " =\n\n";

$opening_paragraph = __( '%s, This is a reminder email that your order is ready for pick up. Please come get your order 
as soon as possible. If this was a mistake, click <a>here</a> to notify the team that your order has been picked up.',
    'ready-reminder-email' );

$billing_first_name = ( version_compare( WOOCOMMERCE_VERSION, "3.0.0" ) < 0 ) ? $order->billing_first_name : $order->get_billing_first_name();
$billing_last_name = ( version_compare( WOOCOMMERCE_VERSION, "3.0.0" ) < 0 ) ? $order->billing_last_name : $order->get_billing_last_name();
if ( $order && $billing_first_name && $billing_last_name ) {
    echo sprintf( $opening_paragraph, $billing_first_name . ' ' . $billing_last_name ) . "\n\n";
}

echo "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

echo sprintf( __( 'Ordered Product: %s', 'ready-reminder-email' ), $item_data->product_title ) . "\n";

echo sprintf( __( 'Quantity: %s', 'ready-reminder-email' ), $item_data->qty ) . "\n";

echo sprintf( __( 'Total: %s', 'ready-reminder-email' ), $item_data->total ) . "\n";

echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

echo __( 'This is a custom email sent as the order status has been changed to "Ready for Pick Up".', 'ready-reminder-email'
    ) .
    "\n\n";

echo apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) );
