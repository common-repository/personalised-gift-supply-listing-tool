<?php

if (!defined('ABSPATH')) exit;
add_action("wp_ajax_cnc_b2b_sync_product_with_woocommerce", "cnc_b2b_sync_product_with_woocommerce");
add_action("wp_ajax_nopriv_cnc_b2b_sync_product_with_woocommerce", "cnc_b2b_sync_product_with_woocommerce");

function cnc_b2b_sync_product_with_woocommerce()
{
    if (
        !isset($_POST['_wp_nonce'])
        || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wp_nonce'])), 'pgs_apt_sync_with_woocommerce_action')
    ) {
        die();
    }
    $product_id = esc_attr(sanitize_text_field($_POST['product_id']));

    $post_id = cnc_b2b_create_product_for_woocommerce($product_id, false);
        
    if($post_id){
        $results = array(
            "status" => 200,
            "url" => get_edit_post_link($post_id)
        );
    }else{
        $results = array(
            "status" => 400,
            "url" => "Bad Request"
        );
    }

    echo wp_json_encode($results);
    die();
}


add_action("wp_ajax_cnc_b2b_sync_order_with_pgs", "cnc_b2b_sync_order_with_pgs");
add_action("wp_ajax_nopriv_cnc_b2b_sync_order_with_pgs", "cnc_b2b_sync_order_with_pgs");

function cnc_b2b_sync_order_with_pgs()
{
    if (
        !isset($_POST['_wp_nonce'])
        || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wp_nonce'])), 'pgs_apt_manually_sync_order_action')
    ) {
        die();
    }
    if (isset($_POST['order_id'])) {
        cnc_b2b_order_sync_by_id(sanitize_text_field($_POST['order_id']));
    }
    die();
}

function cnc_b2b_order_sync_by_id($order_id)
{
    $order = wc_get_order($order_id);
    if ($order) {
        foreach ($order->get_items() as $item_id => $item) {
            $product_id = $item->get_product_id();
            if (get_post_meta($product_id, "cnc_b2b_bigcommerce_product", true) == "1") {
                cnc_b2b_order_item_sync_to_pgs($order, $item_id, $item);
            }
        }
    }
}

function cnc_b2b_order_item_sync_to_pgs($order, $item_id, $item)
{
    $cnc_b2b_next_day_shipping = get_option("cnc_b2b_next_day_shipping");
    $sipping_total = $order->get_shipping_total();
    $next_day = false;
    if ($cnc_b2b_next_day_shipping && $cnc_b2b_next_day_shipping == "enable_next_day_shipping") {
        if ($sipping_total > 0) {
            $next_day = true;
        }
    } else if ($cnc_b2b_next_day_shipping && $cnc_b2b_next_day_shipping == "all_orders_to_next_day") {
        $next_day = true;
    } else if ($cnc_b2b_next_day_shipping && $cnc_b2b_next_day_shipping == "disable_next_day_shipping") {
        $next_day = false;
    }
    $custom_field = get_post_meta($product_id, '_tmcartepo_data', true);
    $data = array(
        "order_id" => $order->get_id(),
        "item_id" => $item_id,
        "custom_field" => wc_get_order_item_meta($item_id, 'custom_field', true),
        "product_sku" => get_post_meta($item->get_product_id(), 'cnc_b2b_bigcommerce_sku', true),
        "product_name" => $item->get_name(),
        "item_number" =>  get_post_meta($item->get_product_id(), 'cnc_b2b_bigcommerce_sku', true),
        "quantity" => $item->get_quantity(),
        "shipping_type" => wc_get_order_item_meta($item_id, 'shipping_type', true),
        "next_day" => ($next_day ? 1 : 0),
        "customer_name" => (!empty($order->get_shipping_first_name()) && !empty($order->get_shipping_last_name())) ? $order->get_shipping_first_name() . " " . $order->get_shipping_last_name() : $order->get_billing_first_name() . " " . $order->get_billing_last_name(),
        "address_line_1" => $order->get_shipping_address_1() ? $order->get_shipping_address_1() : $order->get_billing_address_1(),
        "address_line_2" => $order->get_shipping_address_2() ? $order->get_shipping_address_2() : $order->get_billing_address_2(),
        "town" => $order->get_shipping_city() ? $order->get_shipping_city() : $order->get_billing_city(),
        "county" => $order->get_shipping_state() ? $order->get_shipping_state() : $order->get_billing_state(),
        "postcode" => $order->get_shipping_postcode() ? $order->get_shipping_postcode() : $order->get_billing_postcode(),
        "country" => $order->get_shipping_country() ? $order->get_shipping_country() : $order->get_billing_country(),
        "reference" => wc_get_order_item_meta($item_id, 'reference', true),
        "order_notes" => $order->get_customer_note(),
        "engrave_fonts" => wc_get_order_item_meta($item_id, 'Engrave Fonts', true),
        "font_color" => wc_get_order_item_meta($item_id, 'Engrave Font Color', true),
        "clipart" => wc_get_order_item_meta($item_id, 'Engrave Clipart', true),
        "font_value_1" => wc_get_order_item_meta($item_id, 'Engrave Font 1', true),
        "font_value_2" => wc_get_order_item_meta($item_id, 'Engrave Font 2', true),
        "font_value_3" => wc_get_order_item_meta($item_id, 'Engrave Font 3', true),
        "font_value_4" => wc_get_order_item_meta($item_id, 'Engrave Font 4', true),
        "font_value_5" => wc_get_order_item_meta($item_id, 'Engrave Font 5', true),
        "font_value_6" => wc_get_order_item_meta($item_id, 'Engrave Font 6', true),
        "font_value_7" => wc_get_order_item_meta($item_id, 'Engrave Font 7', true),
        "font_value_8" => wc_get_order_item_meta($item_id, 'Engrave Font 8', true),
        "font_value_9" => wc_get_order_item_meta($item_id, 'Engrave Font 9', true),
        "font_value_10" => wc_get_order_item_meta($item_id, 'Engrave Font 10', true),
        "uploadNewUrl" => wc_get_order_item_meta($item_id, 'Uploaded File', true),
        "print_url" => wc_get_order_item_meta($item_id, 'Print Preview', true),
        "sale_price" => $item->get_total()
    );
    $url = "https://personalisedgiftsupply.com/api/reseller-api/v1/order/create";
    $args = array(
        'headers' => array(
            'Content-Type' => 'application/json',
            'token' => esc_attr(get_option("pgs_products_api_key")),
            'username' => esc_attr(get_option("pgs_username")),
        ),
        'body' => wp_json_encode($data)
    );
    $responsedata = wp_remote_post($url, $args);
    $data = wp_remote_retrieve_body($responsedata);
    $body = json_decode($data, true);
    // print_r($data);
    $cnc_order_id = sanitize_text_field($body['data']['cnc_order_id']);
    update_post_meta($order->get_id(), "cnc_order_id", $cnc_order_id);
    if ($body['statusCode'] == 200) {
        update_post_meta($order->get_id(), "cnc_b2b_order_created", true);
    }
}

function cnc_b2b_is_image_exist($image)
{
    $args = array(
        'post_type' => 'attachment',
        'posts_per_page' => -1,
        'post_status' => 'inherit',
        'meta_query' => array(
            array(
                'key' => 'cnc_b2b_reference_url',
                'value' => $image,
                'compare' => '='
            )
        )
    );
    $query =  new WP_Query($args);
    if ($query->post_count > 0) {
        return $query->post->ID;
    } else {
        return false;
    }
}

function cnc_b2b_is_print_uploaded_file(){
    
    if ( ! isset( $_POST['_wp_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['_wp_nonce'] ) ) , 'pgs_apt_fileupload' ) ){
        die();
    }
        
    if (isset($_POST['originalUrl'])) {

    	$upload_dir  = wp_upload_dir();
        $upload_url  = $upload_dir['url'];
        $upload_path = str_replace('/', DIRECTORY_SEPARATOR, $upload_dir['path']) . DIRECTORY_SEPARATOR;
        $filename        = 'print.png';
        $hashed_filename = md5('fy' . microtime()) . '_fy' . $filename;
        require 'SimpleImage.php';
        
        try {
        	// Create a new  object
            $image = new \cnc_b2b_claviska\SimpleImage();
            $image2   = new \cnc_b2b_claviska\SimpleImage();
            $layer2 = $image2->fromFile(sanitize_text_field($_POST['userFile']))->resize(sanitize_text_field($_POST['userFileWidth']), sanitize_text_field($_POST['userFileHeight']))->rotate(sanitize_text_field($_POST['angle']))->autoOrient();    //src image


            $image->fromFile(sanitize_text_field($_POST['originalUrl']))->resize(sanitize_text_field($_POST['originalWidth']), sanitize_text_field($_POST['originalHeight']))->autoOrient()
            ->overlay($layer2, 'top left', 1, sanitize_text_field($_POST['imageLeft']), sanitize_text_field($_POST['imageTop']))
            ->toFile($upload_path . $hashed_filename, 'image/png');
        } catch (Exception $err) {
            echo esc_attr($err->getMessage());
        }
        echo esc_url($upload_url . '/' . $hashed_filename);
        die();
		            
    }
        
    $uploadedfile = [
        'name' => sanitize_file_name($_FILES['formData']['name']),
        'type' => sanitize_text_field($_FILES['formData']['type']),
        'tmp_name' => sanitize_url($_FILES['formData']['tmp_name']),
        'error' => sanitize_text_field($_FILES['formData']['error']),
        'size' => intval($_FILES['formData']['size'])
    ];

    $upload_overrides = array('test_form' => false);

    $movefile = wp_handle_upload($uploadedfile, $upload_overrides);
	

    if ($movefile) {
            echo esc_url($movefile['url']);
    } else {
            echo esc_html("Possible file upload attack!\n");
    }
    die();
   
 
}
add_action("wp_ajax_cnc_b2b_fileuploaded", "cnc_b2b_is_print_uploaded_file");
add_action("wp_ajax_nopriv_cnc_b2b_fileuploaded", "cnc_b2b_is_print_uploaded_file");
