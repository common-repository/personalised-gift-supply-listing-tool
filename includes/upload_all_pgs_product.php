<?php
if (!defined('ABSPATH')) exit;

function cnc_b2b_add_action_scheduler_for_add_all_pgs_product()
{
    if (false === as_next_scheduled_action('cnc_b2b_add_all_pgs_product') && get_option("cnc_b2b_import_all") == "1") {
        as_schedule_recurring_action(strtotime('tomorrow'), DAY_IN_SECONDS, 'cnc_b2b_add_all_pgs_product');
    }
}
add_action("init", "cnc_b2b_add_action_scheduler_for_add_all_pgs_product");

add_action('cnc_b2b_add_all_pgs_product', 'cnc_b2b_get_all_pgs_product_count');

function cnc_b2b_get_all_pgs_product_count()
{
    $url = "https://personalisedgiftsupply.com/api/reseller-api/v1/product/count";
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
    $count = (int)$body['data']['count'];

    $total_page = ((int)($count / 10)) + 1;

    for ($i = 1; $i <= $total_page; $i++) {
        as_schedule_single_action(strtotime('now'), 'cnc_b2b_fetch_singal_page', array("page" => $i));
    }
}


add_action('cnc_b2b_fetch_singal_page', 'cnc_b2b_get_singal_page_pgs_product');

function cnc_b2b_get_singal_page_pgs_product($page)
{
    $url = "https://personalisedgiftsupply.com/api/reseller-api/v3/product/all_products/?page=" . $page . "&size=10&max_rrp=" . get_option("cnc_b2b_maximum_rrp") . "&range_type=" . implode(",", get_option("cnc_b2b_product_ranges"));
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

    if ($body['statusCode'] == 200) {
        foreach ($body['data']['product'] as $product) {
            $post_id = cnc_b2b_create_post_to_pgs_product($product);
         
            if ($post_id) {
               
                cnc_b2b_create_product_for_woocommerce($post_id, true);
               
            }
        }
    }
}






// function cnc_b2b_add_action_scheduler_for_add_all_pgs_sku()
// {
//     if (false === as_next_scheduled_action('cnc_b2b_add_all_pgs_product_sku') && get_option("cnc_b2b_import_all") == "1") {
//         as_schedule_recurring_action(strtotime("+1 day"), DAY_IN_SECONDS, 'cnc_b2b_add_all_pgs_product_sku');
//     }
// }
// add_action("init", "cnc_b2b_add_action_scheduler_for_add_all_pgs_sku");

// add_action('cnc_b2b_add_all_pgs_product_sku', 'cnc_b2b_add_all_pgs_product_sku_fun');

// function cnc_b2b_add_all_pgs_product_sku_fun(){
//     $the_query = new WP_Query( array(
//         'posts_per_page' => -1,
//         'fields' => 'ids',
//         'post_type' => 'product'
//     ) );
    
//     foreach($the_query->posts  as $post_id){
//         as_schedule_single_action(strtotime('now'), 'cnc_b2b_run_product_single_sku', array("sku" => get_post_meta($post_id,'cnc_b2b_bigcommerce_sku',true),'product_id'=>$post_id));
//         // exit;
//     }
// }

add_action('cnc_b2b_run_product_single_sku','cnc_b2b_sync_product_with_sku',10,2);

function cnc_b2b_sync_product_with_sku($sku,$product_id){
    $logger = wc_get_logger();
	  $logger->debug("sku ".$sku." product id".$product_id, array('source' => 'pgs-all-update-sku'));
	$size = isset($_GET['size'])?$_GET['size']:10;
    $url = "https://personalisedgiftsupply.com/api/reseller-api/v3/product/all_products/?sku=".$sku."&page=1&size=".$size."&max_rrp=" . get_option("cnc_b2b_maximum_rrp") . "&range_type=" . implode(",", get_option("cnc_b2b_product_ranges"));
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
    // print_r($body);
    if ($body['statusCode'] == 200) {
        foreach ($body['data']['product'] as $product) {
                // print_R($product);
             $post_id = cnc_b2b_create_post_to_pgs_product($product);
         
            if ($post_id) {
              //  print_r($product);
                 $product_imported_id = cnc_b2b_create_product_for_woocommerce($post_id, true);
                //echo get_permalink($product_imported_id);
                //$logger->debug("<pre>".print_r($product,1)."</pre>", array('source' => 'pgs-all-update'));
	            $logger->debug(get_permalink($product_imported_id), array('source' => 'pgs-all-update-sku'));
	            if(empty($product['customiser_data'])):
	                $logger->debug($product['meta']['bigcommerce_sku'][0].'<--->'.$product['pgs_link'], array('source' => 'pgs-customiser-missing'));
	            endif;
	            if(!$post_id){
	                 $logger->debug($sku."<==>".$product_id, array('source' => 'pgs-all-update-sku-error-in-product'));
	            }
	           // echo get_permalink($product_imported_id);
                //echo '<br/>';
            } else {
                $logger->debug($sku."<==>".$product_id, array('source' => 'pgs-all-update-sku-error-in-creation'));
            }
        }
        if(empty($body['data']['product'])){
            
            $logger->debug($product_id, array('source' => 'pgs-all-update-sku-deleted'));
            // wp_delete_post($product_id,1);
        }
    }
    
} 