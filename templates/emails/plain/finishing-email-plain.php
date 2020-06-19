<?php
/**
 * Customer "Finishing" order email
 */
$order = new WC_order( $item_data->order_id );

echo "= " . $email_heading . " =\n\n";

$opening_paragraph = __( 'The following by %s is finishing! The details of the item are as follows:', 'woocommerce' );

$billing_first_name = ( version_compare( WOOCOMMERCE_VERSION, "3.0.0" ) < 0 ) ? $order->billing_first_name : $order->get_billing_first_name();
$billing_last_name = ( version_compare( WOOCOMMERCE_VERSION, "3.0.0" ) < 0 ) ? $order->billing_last_name : $order->get_billing_last_name();
if ( $order && $billing_first_name && $billing_last_name ) {
    echo sprintf( $opening_paragraph, $billing_first_name . ' ' . $billing_last_name ) . "\n\n";
}

echo "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

echo sprintf( __( 'Ordered Product: %s', 'woocommerce' ), $item_data->product_title ) . "\n";

echo sprintf( __( 'Quantity: %s', 'woocommerce' ), $item_data->qty ) . "\n";

echo sprintf( __( 'Total: %s', 'woocommerce' ), $item_data->total ) . "\n";

echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

echo __( 'This is an email sent as the order status has been changed to "Finishing".', 'woocommerce' ) . "\n\n";

echo apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) );
