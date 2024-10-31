<?php
if ( ! defined( 'ABSPATH' ) ) exit; 

function cnc_b2b_register_product_api_endpoints() {
    register_rest_route( 
    'v1/cnc_b2b', 
    '/product/create',
    array(
        'methods'  => 'POST',
        'callback' => 'cnc_b2b_handle_product_create_request',
    ) );

    register_rest_route(
    'v1/cnc_b2b',
    '/product/update',
    array(
        'methods'  => 'POST',
        'callback' => 'cnc_b2b_handle_product_update_request',
    ) );

    register_rest_route(
    'v1/cnc_b2b',
    '/product/delete',
    array(
        'methods'  => 'DELETE',
        'callback' => 'cnc_b2b_handle_product_delete_request',
    ) );
}

function cnc_b2b_handle_product_create_request( $request ) {
    
    $params = $request->get_params();
    $post_id = cnc_b2b_create_post_to_pgs_product($params);
            if ($post_id) {
                cnc_b2b_create_product_for_wooconnerce($post_id, true);
            }
    return new WP_REST_Response( array( 'message' => 'Product created successfully.' ), 200 );
}

function cnc_b2b_handle_product_update_request( $request ) {
    // Handle update product request
    $params = $request->get_param();
    $post_id = cnc_b2b_create_post_to_pgs_product($params);
            if ($post_id) {
                cnc_b2b_create_product_for_wooconnerce($post_id, true);
            }
    return new WP_REST_Response( array( 'message' => 'Product updated successfully.' ), 200 );
}

function cnc_b2b_handle_product_delete_request( $request ) {
    $sku = $request->get_param( 'sku' );
    $product_args = array(
            'post_type'  => 'product',
            'meta_query' => array(
                array(
                    'key'     => 'cnc_b2b_bigcommerce_sku',
                    'value'   =>  $sku,
                    'compare' => '=',
                ),
            )
        );
    $query = new WP_Query($product_args);
    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $product_id = get_the_ID();
            
            wp_delete_post( $product_id, true );
            
        }
    }

}


add_action( 'rest_api_init', 'cnc_b2b_register_product_api_endpoints' );
?>
