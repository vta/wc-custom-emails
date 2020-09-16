<?php

/**
 * Ready For Pick Up Email
 *
 * An email class that handles "Ready For Pick Up" order status reminders
 * @NOTE - This class is not used for status changes. It is only created for reminders by using hooks for its call.
 *
 * @class       Ready_Reminder_Email
 * @extends     WC_Email
 *
 */
class Ready_Reminder_Email extends WC_Email
{

    private $order_id;

    function __construct()
    {

        // Add email ID, title, description, heading, subject
        $this->id = 'ready_reminder_email';
        $this->customer_email = true;
        $this->title = __( 'Ready For Pick Up Reminder Email', 'ready-reminder-email' );
        $this->description = __( 'This email is received as a reminder for orders that are "Ready for Pick Up".', 'ready-reminder-email' );

        $this->heading = __( 'Ready For Pick Up Reminder', 'ready-reminder-email' );
        $this->subject = __( '[{blogname}] Order for {product_title} (Order {order_number}) - {order_date}', 'ready-reminder-email' );

        // email template path
        $this->template_html = 'emails/ready-reminder-email-html.php';
        $this->template_plain = 'emails/plain/ready-reminder-email-plain.php';

        // Triggers for this email
        add_action( 'custom_ready_reminder_email_notification', array( $this, 'queue_notification' ) );
        add_action( 'custom_ready_reminder_email_trigger_notification', array( $this, 'trigger' ) );

        // Call parent constructor
        parent::__construct();

        // Other settings
        $this->template_base = CUSTOM_TEMPLATE_PATH;
        // default recipient to null. This field will be set when trigger pulls order information
        // and sets it to customer email
        $this->recipient = null;
        // placeholders for form fields
        $this->placeholders = array(
            '{order_date}' => '',
            '{order_number}' => '',
            '{order_billing_full_name}' => '',
        );

    }

    public function queue_notification( $order_id )
    {

        $order = new WC_order( $order_id );
        wp_schedule_single_event( time(), 'custom_ready_reminder_email_trigger', array( $order_id ) );
    }

    // This function collects the data and sends the email
    function trigger( $order_id )
    {
        $order = wc_get_order( $order_id );
        // save order_id here to pass to get_html_content
        $this->order_id = $order_id;

        $this->find[] = '{order_date}';
        $this->replace[] = date_i18n( wc_date_format(), strtotime( $order->get_date_created() ) );

        $this->find[] = '{order_number}';
        $this->replace[] = function_exists( 'wc_sequential_order_numbers' )  // check for plugin
            ? $order->get_order_number()
            : $this->object->order_id;

        error_log(json_encode($order->get_billing_email(), JSON_PRETTY_PRINT));

        $this->recipient = $order->get_billing_email();

        // if no recipient is set, do not send the email
        if ( !$this->get_recipient() ) {
            return;
        }

        // send the email
        $this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(),
            array() );
        // avoid stacking this when repeating for reminders
        unset($this->replace);
    }

    // Create an object with the data to be passed to the templates
    public function create_object( $item_id )
    {

        global $wpdb;

        $item_object = new stdClass();

        // order ID
        $query_order_id = "SELECT order_id FROM `" . $wpdb->prefix . "woocommerce_order_items`
                            WHERE order_item_id = %d";
        $get_order_id = $wpdb->get_results( $wpdb->prepare( $query_order_id, $item_id ) );

        $order_id = 0;
        if ( isset( $get_order_id ) && is_array( $get_order_id ) && count( $get_order_id ) > 0 ) {
            $order_id = $get_order_id[0]->order_id;
        }
        $item_object->order_id = $order_id;

        $order = new WC_order( $order_id );
        $this->recipient = $order->get_billing_email();

        // order date
        $post_data = get_post( $order_id );
        $item_object->order_date = $post_data->post_date;

        // product ID
        $item_object->product_id = wc_get_order_item_meta( $item_id, '_product_id' );

        // product name
        $_product = wc_get_product( $item_object->product_id );
        $item_object->product_title = $_product->get_title();

        // qty
        $item_object->qty = wc_get_order_item_meta( $item_id, '_qty' );

        // total
        $item_object->total = wc_price( wc_get_order_item_meta( $item_id, '_line_total' ) );

        // email address
        $item_object->billing_email = (version_compare( WOOCOMMERCE_VERSION, "3.0.0" ) < 0) ? $order->billing_email : $order->get_billing_email();

        // customer ID
        $item_object->customer_id = (version_compare( WOOCOMMERCE_VERSION, "3.0.0" ) < 0) ? $order->user_id : $order->get_user_id();

        return $item_object;

    }

    // return the html content
    function get_content_html()
    {
        ob_start();
        wc_get_template( $this->template_html, array(
            'order_id' => $this->order_id,
            'email_heading' => $this->get_heading(),
            'additional_content' => $this->get_additional_content()
        ), 'custom-templates', $this->template_base );
        return ob_get_clean();
    }

    // return the plain content
    function get_content_plain()
    {
        ob_start();
        wc_get_template( $this->template_plain, array(
            'item_data' => $this->object,
            'email_heading' => $this->get_heading(),
            'additional_content' => $this->get_additional_content()
        ), 'custom-templates', $this->template_base );
        return ob_get_clean();
    }

    // return the subject
    function get_subject()
    {
        // check if user defined subject exists, else use default subject
        $subject = ! empty( $this->settings['subject'] )
            ? $this->settings['subject']
            : $this->subject;
        return apply_filters( 'woocommerce_email_subject_' . $this->id, $this->format_string( $subject ), $this->object );
    }

    // return the email heading
    public function get_heading()
    {
        // check if user defined heading exists, else use default heading
        $heading = ! empty( $this->settings['heading'] )
            ? $this->settings['heading']
            : $this->heading;
        return apply_filters( 'woocommerce_email_heading_' . $this->id, $this->format_string( $heading ), $this->object );
    }

    // form fields that are displayed in WooCommerce->Settings->Emails
    function init_form_fields()
    {
        $placeholder_text = sprintf( __( 'Available placeholders: %s', 'woocommerce' ), '<code>' . esc_html( implode( '</code>, <code>', array_keys( $this->placeholders ) ) ) . '</code>' );
        $this->form_fields = array(
            'enabled' => array(
                'title' => __( 'Enable/Disable', 'ready-reminder-email' ),
                'type' => 'checkbox',
                'label' => __( 'Enable this email notification', 'ready-reminder-email' ),
                'default' => 'yes'
            ),
            'subject' => array(
                'title' => __( 'Subject', 'ready-reminder-email' ),
                'type' => 'text',
                'description' => sprintf( __( 'This controls the email subject line. Leave blank to use the default subject: <code>%s</code>.', 'ready-reminder-email' ), $this->subject ),
                'placeholder' => '',
                'default' => ''
            ),
            'heading' => array(
                'title' => __( 'Email Heading', 'ready-reminder-email' ),
                'type' => 'text',
                'description' => sprintf( __( 'This controls the main heading contained within the email notification. Leave blank to use the default heading: <code>%s</code>.', 'ready-reminder-email' ), $this->heading ),
                'placeholder' => '',
                'default' => ''
            ),
            'additional_content' => array(
                'title' => __( 'Additional content', 'ready-reminder-email' ),
                'description' => __( 'Text to appear below the main email content.', 'ready-reminder-email' ) . ' ' .
                    $placeholder_text,
                'css' => 'width:400px; height: 75px;',
                'placeholder' => __( 'N/A', 'ready-reminder-email' ),
                'type' => 'textarea',
                'default' => $this->get_default_additional_content(),
                'desc_tip' => true,
            ),
            'email_type' => array(
                'title' => __( 'Email type', 'ready-reminder-email' ),
                'type' => 'select',
                'description' => __( 'Choose which format of email to send.', 'ready-reminder-email' ),
                'default' => 'html',
                'class' => 'email_type',
                'options' => array(
                    'plain' => __( 'Plain text', 'ready-reminder-email' ),
                    'html' => __( 'HTML', 'ready-reminder-email' ),
                    'multipart' => __( 'Multipart', 'ready-reminder-email' ),
                )
            )
        );
    }

}

return new Ready_Reminder_Email();

