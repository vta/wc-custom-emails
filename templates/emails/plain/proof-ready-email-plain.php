<?php
/**
 * An email notifying that the order status is now "Proof Ready"
 */
$order = new WC_order( $item_data->order_id );

echo "= " . $email_heading . " =\n\n";

$opening_paragraph = __( 'An order, made by %s, has now been marked Proof Ready. The details of the item are as follows:',
    'proof-ready-email' );

$customer = new WC_Customer( $order->get_customer_id() );
$billing_first_name = $customer->get_first_name();
$billing_last_name = $customer->get_last_name();

if ( $order && $billing_first_name && $billing_last_name ) {
    echo sprintf( $opening_paragraph, $billing_first_name . ' ' . $billing_last_name ) . "\n\n";
}

echo "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

echo sprintf( __( 'Ordered Product: %s', 'proof-ready-email' ), $item_data->product_title ) . "\n";

echo sprintf( __( 'Quantity: %s', 'proof-ready-email' ), $item_data->qty ) . "\n";

echo sprintf( __( 'Total: %s', 'proof-ready-email' ), $item_data->total ) . "\n";

echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
    echo esc_html( wp_strip_all_tags( wptexturize( $additional_content ) ) );
}

echo apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) );
