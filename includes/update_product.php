<?php
if (!defined('ABSPATH')) exit;
function cnc_b2b_add_action_scheduler_for_product()
{
	if (false === as_next_scheduled_action('cnc_b2b_get_product_list_update') && get_option("cnc_b2b_import_all") != "1") {
		as_schedule_recurring_action(strtotime('+1 hour'), HOUR_IN_SECONDS, 'cnc_b2b_get_product_list_update');
	}
}
add_action("init", "cnc_b2b_add_action_scheduler_for_product");

add_action('cnc_b2b_get_product_list_update', 'cnc_b2b_update_product_list_with_pgs');


function cnc_b2b_update_product_list_with_pgs()
{
	$logger = wc_get_logger();
	$url = "https://personalisedgiftsupply.com/api/reseller-api/v1/product/list";
	$args = array(
		'headers' => array(
			'Content-Type' => 'application/json',
			'token' => esc_attr(get_option("pgs_products_api_key")),
			'username' => esc_attr(get_option("pgs_username")),
		)
	);
	$responsedata = wp_remote_get($url, $args);
	$data = wp_remote_retrieve_body($responsedata);
	$body = json_decode($data);
	$logger->debug("1 -----> " . serialize($body), array('source' => 'list-update'));
	$post_ids = array();
	$wc_post_ids = array();
	if ($body->statusCode == 200) {
		if (!empty($body->data)) {
			foreach ($body->data as $product) {
				$args = array(
					'post_type'  => 'pgs_products',
					'posts_per_page' => -1,
					'meta_query' => array(
						array(
							'key'     => 'bigcommerce_sku',
							'value'   => esc_attr($product->meta->bigcommerce_sku[0]),
							'compare' => '=',
						),
					),
				);
				$query = new WP_Query($args);

				if ($query->post_count > 0) {
					$post_id = $query->post->ID;
					$post_ids[] = esc_attr($post_id);
					$wc_post_ids[] = esc_attr(get_post_meta($post_id, "cnc_b2b_woocommerce_product_id", true));
				}
			}
		}
	}

	$logger->debug("2 -----> " . serialize($post_ids), array('source' => 'list-update'));
	if (!empty($post_ids)) {
		$args  = array(
			'post_type'      => 'pgs_products',
			'post__not_in'   => $post_ids,
			'posts_per_page' => -1,
		);
		$the_query = new WP_Query($args);

		if ($the_query->have_posts()) {
			while ($the_query->have_posts()) {
				$the_query->the_post();
				wp_delete_post(get_the_ID());
			}
		}


		$args = array(
			'post_type'  => 'product',
			'posts_per_page' => -1,
			'meta_query' => array(
				array(
					'key'     => 'cnc_b2b_bigcommerce_product',
					'value'   => true,
					'compare' => '=',
				),
			),
			'post__not_in'   => $wc_post_ids,
		);
		$wc_query = new WP_Query($args);

		$logger->debug("3 -----> " . json_encode($wc_query->posts), array('source' => 'list-update'));
		if ($wc_query->have_posts()) {
			while ($wc_query->have_posts()) {
				$wc_query->the_post();
				wp_delete_post(get_the_ID());
			}
		}
	}
}
