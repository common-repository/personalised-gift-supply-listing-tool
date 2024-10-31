<?php
// add_action("wp",function(){
//     if($_GET['sss']=='sss'){
//         echo "<pre>";

//         cnc_b2b_update_order_status();
//         exit;
//         }
//     });
if (!defined('ABSPATH'))
    exit;

function cnc_b2b_add_action_scheduler_for_status()
{
    if (false === as_next_scheduled_action('cnc_b2b_get_product_status') && get_option("cnc_b2b_sync_order_status_automatically") == "1") {
        as_schedule_recurring_action(strtotime('+1 hour'), HOUR_IN_SECONDS, 'cnc_b2b_get_product_status');
    }
}
add_action("init", "cnc_b2b_add_action_scheduler_for_status");

add_action('cnc_b2b_get_product_status', 'cnc_b2b_update_order_status');

function cnc_b2b_update_order_status()
{
    $status = wc_get_order_statuses();
    unset($status['wc-completed']);
    $args = array(
        'post_type' => 'shop_order',
        'status' => array_keys($status),
        'posts_per_page' => -1,
        'meta_key' => 'is_cnc_b2b_order', // Postmeta key field
        'meta_value' => "1", // Postmeta value field
        'meta_compare' => '=',
        'return' => 'ids'
    );
    $orders = wc_get_orders($args);

    $order_ids = implode(",", $orders);
    $url = "https://personalisedgiftsupply.com/api/reseller-api/v1/order/status/?ids=" . esc_attr($order_ids);
    $args = array(
        'headers' => array(
            'Content-Type' => 'application/json',
            'token' => esc_attr(get_option("pgs_products_api_key")),
            'username' => esc_attr(get_option("pgs_username")),
        )
    );
    $responsedata = wp_remote_get($url, $args);

    $data = wp_remote_retrieve_body($responsedata);
    $body = json_decode($data, true);
    $data = $body['data'];
    foreach ($data as $key => $value) {
        $order = new WC_Order($key);
        $is_other_product = false;
        foreach ($order->get_items() as $item_id => $item) {
            if (!$item->get_meta('Engrave SKU', true)) {
                $is_other_product = true;
            }
        }
        if ($is_other_product && get_option("cnc_b2b_sync_order_status_if_other_product_also") != "1") {
            continue;
        }
        $complete_status = array();
        $tracking_id = "";
        if (!empty($order)) {
            foreach ($value as $item_id => $data) {
                if ($data['status'] == 'completed' && isset($data['tracking_id'])) {
                    $complete_status[] = $item_id;
                    $tracking_id = esc_attr($data['tracking_id']);
                }
                if (isset($data['processed_time'])) {
                    $processed_time = sanitize_text_field($data['processed_time']);
                    update_post_meta($key, "cnc_b2b_order_processed_time", esc_attr($processed_time));
                }
                if (isset($data['shipping_service'])) {
                    $shipping_service = sanitize_text_field($data['shipping_service']);
                    update_post_meta($key, "cnc_b2b_order_shipping_service", esc_attr($shipping_service));
                }
            }
        }
        if (count($complete_status) == count($value)) {
            $order->update_status('completed');
            $id = sanitize_text_field($tracking_id);
            update_post_meta($key, "cnc_b2b_order_tracking_id", esc_attr($id));
        }
    }
}