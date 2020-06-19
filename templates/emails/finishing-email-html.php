<?php
/**
 * Customer "Finishing" order status email
 */
$order = new WC_order( $item_data->order_id );
$opening_paragraph = __( 'The following by %s is finishing! The details of the item are as follows:', 'woocommerce' );

?>

<?php do_action( 'woocommerce_email_header', $email_heading ); ?>

<?php
$customer = new WC_Customer( $order->get_customer_id() );
$billing_first_name = $customer->get_first_name();
$billing_last_name = $customer->get_last_name();

if ( $order && $billing_first_name && $billing_last_name ) : ?>
    <p><?php printf( $opening_paragraph, $billing_first_name . ' ' . $billing_last_name ); ?></p>
<?php endif; ?>

<?php
/*
* @hooked WC_Emails::order_details() Shows the order details table.
* @hooked WC_Structured_Data::generate_order_data() Generates structured data.
* @hooked WC_Structured_Data::output_structured_data() Outputs structured data.
* @since 2.5.0
*/
do_action( 'woocommerce_email_order_details', $order );

/*
* @hooked WC_Emails::order_meta() Shows order meta data.
*/
do_action( 'woocommerce_email_order_meta', $order );

/*
* @hooked WC_Emails::customer_details() Shows customer details
* @hooked WC_Emails::email_address() Shows email address
*/
do_action( 'woocommerce_email_customer_details', $order );

/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
    echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

do_action( 'woocommerce_email_footer' );
?>
