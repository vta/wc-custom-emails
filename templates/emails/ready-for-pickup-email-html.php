<?php
/**
 * Customer email to notify order is Ready for Pick Up
 */
$order = new WC_order( $item_data->order_id );
$opening_paragraph = __( '%s, your order is ready for pick up! The details of the item are as follows:', 'ready-for-pickup-email' );

?>

<?php do_action( 'woocommerce_email_header', $email_heading ); ?>

<?php
$customer = new WC_Customer( $order->get_customer_id() );
$billing_first_name = $customer->get_first_name();
$billing_last_name = $customer->get_last_name();

if ( $order && $billing_first_name && $billing_last_name ) : ?>
    <p><?php printf( $opening_paragraph, $billing_first_name . ' ' . $billing_last_name ); ?></p>
    <p>
      If you already picked up your order, click on the link below to complete your order. You may need to log in to complete
      the following action.
    </p>
    <table>
      <tr>
        <td style="background: #017aca;
                  border: none;
                  cursor: pointer;
                  opacity: 1;
                  padding: 12px 10px;
                  text-align: center;">
          <a href="<?php echo site_url() . '/my-account/view-order/' . $order->get_id() . '?completed=1' ?>"
             style="height: 100%;
                       width: 100%;
                       background: #017aca;
                       text-decoration: none;
                       text-align: center;
                       color: #ffffff;">
              <span style="text-decoration: none;
                        color: #ffffff;
                        letter-spacing: 0.0333em;
                        line-height: 1.25;
                        text-decoration: none;
                        text-transform: uppercase;
                        font-weight: 600;">
                I have picked up my order
              </span>
          </a>
        </td>
      </tr>
      <tr>
        <td style="padding: 10px; background: #ffffff"></td>
      </tr>
    </table>
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
?>

<?php do_action( 'woocommerce_email_footer' ); ?>
