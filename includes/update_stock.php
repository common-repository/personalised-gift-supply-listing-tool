<?php
if (!defined('ABSPATH')) exit;
// add_action("wp",function(){
//     if($_GET['sss']=='sss'){
//     	echo "<pre>";
//   //  	$allposts = new WP_Query( 
//   //  		array(
//   //  			'post_type'=>'product',
//   //  			'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash'),
// 	 //		 		'posts_per_page' => 299
//   //  		) 
//   //  	);
// 		// print_R($allposts);
// 		// foreach ($allposts->posts as $eachpost) {
// 		// 	wp_delete_post( $eachpost->ID, true );
// 		// }
//         cnc_b2b_product_stock_update();
//         exit;
//     }

// });
function cnc_b2b_add_action_scheduler_for_stock()
{
    if (false === as_next_scheduled_action('cnc_b2b_get_product_stock')) {
        as_schedule_recurring_action(strtotime('+1 hour'), HOUR_IN_SECONDS, 'cnc_b2b_get_product_stock');
    }
}

add_action("init", "cnc_b2b_add_action_scheduler_for_stock");

add_action('cnc_b2b_get_product_stock', 'cnc_b2b_product_stock_update');

function cnc_b2b_product_stock_update()
{
    global $wpdb;
    $results = $wpdb->get_results("SELECT meta_value,post_id FROM {$wpdb->prefix}postmeta WHERE meta_key='cnc_b2b_bigcommerce_sku'", ARRAY_A);
    $skus = array();

    foreach ($results as $row) {
        $skus[] = esc_attr($row['meta_value']);
    }
    if (count($skus) > 10) {
        foreach (array_chunk($skus, 10) as $skus) {
            cnc_b2b_update_product_stock_by_skus($skus);
        }
    } else {
        cnc_b2b_update_product_stock_by_skus($skus);
    }
    $upload_dir = wp_upload_dir();
    $files = glob($upload_dir['basedir'] . '/*.tmp');
    foreach ($files as $file) {
        if (is_file($file))
            unlink($file);
    }
}

function cnc_b2b_update_product_stock_by_skus($skus)
{

    $url = "https://personalisedgiftsupply.com/api/reseller-api/v1/product/stock/?skus=" . implode(",", $skus);

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
        $args = array(
            'post_type'       => 'product',
            'meta_query'      => array(
                array(
                    'key'         => 'cnc_b2b_bigcommerce_sku',
                    'value'       => $key,
                ),
            )
        );
        $query = new WP_Query($args);
        $product_id = $query->posts[0]->ID;
        if ((int)$value['Stock Level'] == 0) {
            $outofstock = sanitize_text_field('outofstock');
            update_post_meta($product_id, '_stock_status', esc_attr($outofstock));
            wp_set_post_terms($product_id, 'outofstock', 'product_visibility', true);
        } else {
            wp_remove_object_terms($product_id, 'outofstock', 'product_visibility');
            $instock = sanitize_text_field('instock');
            update_post_meta($product_id, '_stock_status', esc_attr($instock));
        }
        $yes = sanitize_text_field('yes');
        $stock_level = sanitize_text_field((int)$value['Stock Level']);
        update_post_meta($product_id, '_manage_stock', esc_attr($yes));
        update_post_meta($product_id, '_stock', esc_attr($stock_level));

        $regular_price = cnc_b2b_get_regular_price($value);

        //   echo "<pre>";
        // print_r($regular_price);
        // exit;
        $regular_price = sanitize_text_field($regular_price);
        update_post_meta($product_id, "_price", $regular_price);
        update_post_meta($product_id, "_regular_price", $regular_price);
        update_post_meta($product_id, 'reseller_pricing', esc_attr($value));


        $product_url = "https://personalisedgiftsupply.com/api/reseller-api/v1/product/singal/?sku=" . $key;
        $product_args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'token' => esc_attr(get_option("pgs_products_api_key")),
                'username' => esc_attr(get_option("pgs_username")),
            )
        );
        $product_responsedata = wp_remote_get($product_url, $product_args);
        $product_data = wp_remote_retrieve_body($product_responsedata);
        $product_body = json_decode($product_data, true);

        if ($product_body['statusCode'] == 200) {
            $customiser_data = $product_body['data']['customiser_data'];
            $customiser_data = sanitize_text_field($customiser_data);
            update_post_meta($product_id, "customiser_data", esc_attr($customiser_data));
        }
    }
}

function cnc_b2b_get_regular_price($value)
{   
    if($value){
        if (get_option("cnc_b2b_price_for_product") == "custom_margin") {
            $margin_per = (float)get_option("cnc_b2b_margin_for_ragular_price");
            $margin = ($margin_per / 100) + 1;
            $round_up_price = (float)get_option("cnc_b2b_round_up_the_nearest");
            $dropship_for_1 = (float)$value['Dropship For 1'];
            $regular_price = round(($dropship_for_1 * 1.2) * $margin) - $round_up_price;
            return $regular_price;
        } else if (get_option("cnc_b2b_price_for_product") == "suggested_rrp") {
            return $value['RRP'];
        } else {
            return $value['RRP'] ? $value['RRP'] : 0;
        }
    }else{
        return 0;
    }
}
