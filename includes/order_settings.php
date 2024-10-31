<?php
if (!defined('ABSPATH')) exit;

if (
    isset($_POST['cnc_b2b_save_sync_order_setting']) && isset($_POST['pgs_apt_order_setting_nonce_field'])
    && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['pgs_apt_order_setting_nonce_field'])), 'pgs_apt_order_setting_action')
) {

    $cnc_b2b_sync_order_type = sanitize_text_field($_POST['cnc_b2b_sync_order_type']);
    $cnc_b2b_sync_order_status = sanitize_text_field($_POST['cnc_b2b_sync_order_status']);
    $cnc_b2b_sync_order_status_automatically = sanitize_text_field($_POST["cnc_b2b_sync_order_status_automatically"]);
    $cnc_b2b_sync_order_status_if_other_product_also = sanitize_text_field($_POST["cnc_b2b_sync_order_status_if_other_product_also"]);
    $cnc_b2b_import_category = sanitize_text_field($_POST["cnc_b2b_import_category"]);
    $cnc_b2b_import_all = sanitize_text_field($_POST["cnc_b2b_import_all"]);
    $cnc_b2b_margin_for_ragular_price = sanitize_text_field($_POST["cnc_b2b_margin_for_ragular_price"]);
    $cnc_b2b_round_up_the_nearest = sanitize_text_field($_POST["cnc_b2b_round_up_the_nearest"]);
    $cnc_b2b_price_for_product = sanitize_text_field($_POST["cnc_b2b_price_for_product"]);
    $cnc_b2b_maximum_rrp = sanitize_text_field($_POST["cnc_b2b_maximum_rrp"]);
    $cnc_b2b_product_ranges =  array_map('sanitize_text_field', $_POST["cnc_b2b_product_ranges"]);
    $cnc_b2b_next_day_shipping = sanitize_text_field($_POST["cnc_b2b_next_day_shipping"]);
    $cnc_b2b_photography_images_as_main_image = sanitize_text_field($_POST["cnc_b2b_photography_images_as_main_image"]);

    update_option("cnc_b2b_sync_order_type",  esc_attr($cnc_b2b_sync_order_type));
    update_option("cnc_b2b_sync_order_status", esc_attr($cnc_b2b_sync_order_status));
    update_option("cnc_b2b_sync_order_status_automatically", esc_attr((isset($cnc_b2b_sync_order_status_automatically) &&  $cnc_b2b_sync_order_status_automatically == "on") ? "1" : "0"));
    update_option("cnc_b2b_sync_order_status_if_other_product_also", esc_attr((isset($cnc_b2b_sync_order_status_if_other_product_also) && $cnc_b2b_sync_order_status_if_other_product_also == "on") ? "1" : "0"));
    update_option("cnc_b2b_import_category", esc_attr((isset($cnc_b2b_import_category) && $cnc_b2b_import_category  == "on") ? "1" : "0"));
    update_option("cnc_b2b_import_all", esc_attr((isset($cnc_b2b_import_all) &&  $cnc_b2b_import_all == "on") ? "1" : "0"));
    update_option("cnc_b2b_margin_for_ragular_price", esc_attr($cnc_b2b_margin_for_ragular_price));
    update_option("cnc_b2b_round_up_the_nearest", esc_attr($cnc_b2b_round_up_the_nearest));
    update_option("cnc_b2b_price_for_product",esc_attr( $cnc_b2b_price_for_product));
    update_option("cnc_b2b_maximum_rrp",   esc_attr($cnc_b2b_maximum_rrp));
    update_option("cnc_b2b_product_ranges", $cnc_b2b_product_ranges);    
    update_option("cnc_b2b_next_day_shipping", $cnc_b2b_next_day_shipping);
    update_option('cnc_b2b_photography_images_as_main_image', $cnc_b2b_photography_images_as_main_image);
}   

$cnc_b2b_import_all = get_option("cnc_b2b_import_all") ? get_option("cnc_b2b_import_all") : "0";
$cnc_b2b_import_category = get_option("cnc_b2b_import_category") ? get_option("cnc_b2b_import_category") : "0";
$cnc_b2b_sync_order_type = get_option("cnc_b2b_sync_order_type");
$cnc_b2b_sync_order_status = get_option("cnc_b2b_sync_order_status");
$cnc_b2b_sync_order_status_automatically = get_option("cnc_b2b_sync_order_status_automatically") ? get_option("cnc_b2b_sync_order_status_automatically") : "0";
$cnc_b2b_sync_order_status_if_other_product_also = get_option("cnc_b2b_sync_order_status_if_other_product_also") ? get_option("cnc_b2b_sync_order_status_if_other_product_also") : "0";
$cnc_b2b_margin_for_ragular_price = get_option("cnc_b2b_margin_for_ragular_price");
$cnc_b2b_round_up_the_nearest = get_option("cnc_b2b_round_up_the_nearest");
$cnc_b2b_price_for_product = get_option("cnc_b2b_price_for_product");
$cnc_b2b_maximum_rrp = get_option("cnc_b2b_maximum_rrp");
$cnc_b2b_product_ranges = get_option("cnc_b2b_product_ranges") ? get_option("cnc_b2b_product_ranges") : array() ;
$cnc_b2b_next_day_shipping = get_option("cnc_b2b_next_day_shipping");
$cnc_b2b_photography_images_as_main_image = get_option("cnc_b2b_photography_images_as_main_image");
?>
<div class="cnc_b2b_order_settings_page">
    <div class="page_title cnc_special_title">
        <h1><?php esc_html_e('Order Settings', 'personalised-gift-supply-listing-tool') ?></h1>
    </div>
    <div class="cnc_b2b_order_setting_content">
        <form name="order_settins" method="POST">

            <?php wp_nonce_field('pgs_apt_order_setting_action', 'pgs_apt_order_setting_nonce_field'); ?>
            <div class="order_type_wrap">
                <h3><?php esc_html_e('Order Synchronize Type', 'personalised-gift-supply-listing-tool') ?></h3>
                <p><?php esc_html_e('Use "manually synchronised" to sync orders manually with Personalised Gift Supply, this is used if you want to send all orders at the end of each day.', 'personalised-gift-supply-listing-tool') ?></p>
                <p><?php esc_html_e('Synchronise when order status change will update orders to processed when they`re dispatched from Personalised Gift Supply', 'personalised-gift-supply-listing-tool') ?></p>
                <div class="radio_wrap">
                    <input type="radio" name="cnc_b2b_sync_order_type" value="manually_sync" id="manually_sync" <?php if ($cnc_b2b_sync_order_type && $cnc_b2b_sync_order_type == "manually_sync") {
                                                                                                                    echo esc_html("checked='checked'");
                                                                                                                } ?> />
                    <label for="manually_sync"><?php esc_html_e('Manually Synchronize', 'personalised-gift-supply-listing-tool') ?></label>
                </div>
                <div class="radio_wrap">
                    <input type="radio" name="cnc_b2b_sync_order_type" value="sync_on_status_change" id="sync_on_status_change" <?php if ($cnc_b2b_sync_order_type && $cnc_b2b_sync_order_type == "sync_on_status_change") {
                                                                                                                                    echo esc_html("checked='checked'");
                                                                                                                                } ?> />
                    <label for="sync_on_status_change"><?php esc_html_e('Synchronize When Order Status Change', 'personalised-gift-supply-listing-tool') ?></label>
                </div>
            </div>

            <div class="order_type_wrap cnc_b2b_sync_order_status_wrap" <?php if ($cnc_b2b_sync_order_type && $cnc_b2b_sync_order_type == "manually_sync") {
                                                                            echo esc_html("style='display: none;'");
                                                                        } ?>>
                <h3><?php esc_html_e('Select Order Status', 'personalised-gift-supply-listing-tool') ?></h3>
                <p><?php esc_html_e('Default setting is "Processing" which will only send an order through to us when the order is paid', 'personalised-gift-supply-listing-tool') ?></p>
                <div class="select_status_wrap">
                    <select name="cnc_b2b_sync_order_status">
                        <?php
                        foreach (wc_get_order_statuses() as $key => $value) {
                        ?>
                            <option value="<?php echo esc_attr($key); ?>" <?php if ($cnc_b2b_sync_order_status && $cnc_b2b_sync_order_status == $key) {
                                                                    echo esc_html("selected='selected'");
                                                                } ?>><?php echo esc_attr($value)  ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="order_type_wrap">
                <h3><?php esc_html_e('Next Day Shipping', 'personalised-gift-supply-listing-tool') ?></h3>
                <p><?php esc_html_e('When this is enabled if you charge shipping cost the order will be marked as paid shipping and upgraded to next day delivery, this will incur a charge on your account.', 'personalised-gift-supply-listing-tool') ?></p>
                <div class="radio_wrap">
                    <input type="radio" name="cnc_b2b_next_day_shipping" value="enable_next_day_shipping" id="enable_next_day_shipping" <?php if ($cnc_b2b_next_day_shipping && $cnc_b2b_next_day_shipping == "enable_next_day_shipping") {
                                                                                                                                            echo esc_html("checked='checked'");
                                                                                                                                        } ?> />
                    <label for="enable_next_day_shipping"><?php esc_html_e('Enable next day shipping', 'personalised-gift-supply-listing-tool') ?></label>
                </div>
                <div class="radio_wrap">
                    <input type="radio" name="cnc_b2b_next_day_shipping" value="all_orders_to_next_day" id="all_orders_to_next_day" <?php if ($cnc_b2b_next_day_shipping && $cnc_b2b_next_day_shipping == "all_orders_to_next_day") {
                                                                                                                                        echo esc_html("checked='checked'");
                                                                                                                                    } ?> />
                    <label for="all_orders_to_next_day"><?php esc_html_e('Upgrade all orders to next day regardless of shipping cost', 'personalised-gift-supply-listing-tool') ?></label>
                </div>
                <div class="radio_wrap">
                    <input type="radio" name="cnc_b2b_next_day_shipping" value="disable_next_day_shipping" id="disable_next_day_shipping" <?php if ($cnc_b2b_next_day_shipping && $cnc_b2b_next_day_shipping == "disable_next_day_shipping") {
                                                                                                                                                echo esc_html("checked='checked'");
                                                                                                                                            } ?> />
                    <label for="disable_next_day_shipping"><?php esc_html_e('Disable next day shipping', 'personalised-gift-supply-listing-tool') ?></label>
                </div>
            </div>
            <div class="order_type_wrap">
                <h3><?php esc_html_e('Automatically Process Orders', 'personalised-gift-supply-listing-tool') ?></h3>
                <p><?php esc_html_e('If this is ticked your orders will automatically be updated to completed once the order has been dispatched from Personalised Gift Supply.', 'personalised-gift-supply-listing-tool') ?></p>
                <div class="radio_wrap">
                    <input type="checkbox" name="cnc_b2b_sync_order_status_automatically" id="cnc_b2b_sync_order_status_automatically" <?php if ($cnc_b2b_sync_order_status_automatically && $cnc_b2b_sync_order_status_automatically == "1") {
                                                                                                                                            echo esc_html("checked='checked'");
                                                                                                                                        } ?> />
                    <label for="cnc_b2b_sync_order_status_automatically"><?php esc_html_e('Automictically Process Orders', 'personalised-gift-supply-listing-tool') ?></label>
                </div>
            </div>

            <div class="order_type_wrap">
                <h3><?php esc_html_e('Process Shared Orders', 'personalised-gift-supply-listing-tool') ?></h3>
                <p><?php esc_html_e('By default this is unticked, if you sell a mix of our products and your own, when ticked this will mark the order as complete once we have processed the order from our side', 'personalised-gift-supply-listing-tool') ?></p>
                <div class="radio_wrap">
                    <input type="checkbox" name="cnc_b2b_sync_order_status_if_other_product_also" id="cnc_b2b_sync_order_status_if_other_product_also" <?php if ($cnc_b2b_sync_order_status_if_other_product_also && $cnc_b2b_sync_order_status_if_other_product_also == "1") {
                                                                                                                                                            echo esc_html("checked='checked'");
                                                                                                                                                        } ?> />
                    <label for="cnc_b2b_sync_order_status_if_other_product_also"><?php esc_html_e('Automatically process mixed orders', 'personalised-gift-supply-listing-tool') ?></label>
                </div>
            </div>

            <div class="order_type_wrap">
                <h3><?php esc_html_e('Import Category ?', 'personalised-gift-supply-listing-tool') ?></h3>
                <p><?php esc_html_e('When enabled this will import our recommended categories into your site and list products into those categories.', 'personalised-gift-supply-listing-tool') ?></p>
                <div class="radio_wrap">
                    <input type="checkbox" name="cnc_b2b_import_category" id="cnc_b2b_import_category" <?php if ($cnc_b2b_import_category && $cnc_b2b_import_category == "1") {
                                                                                                            echo esc_html("checked='checked'");
                                                                                                        } ?> />
                    <label for="cnc_b2b_import_category"><?php esc_html_e('Enabled', 'personalised-gift-supply-listing-tool') ?></label>
                </div>
            </div>

            <div class="order_type_wrap">
                <h3><?php esc_html_e('Product Ranges', 'personalised-gift-supply-listing-tool') ?></h3>
                <p><?php esc_html_e('Please check the product ranges that you would like to be displayed on your website.', 'personalised-gift-supply-listing-tool') ?></p>
                <div class="radio_wrap">
                    <input type="checkbox" name="cnc_b2b_product_ranges[]" value="personalised_products" id="personalised_products" <?php if ($cnc_b2b_product_ranges && in_array("personalised_products", $cnc_b2b_product_ranges)) {
                                                                                                                                        echo esc_html("checked='checked'");
                                                                                                                                    }
                                                                                                                                    ?> />
                    <label for="personalised_products"><?php esc_html_e('Personalised Products', 'personalised-gift-supply-listing-tool') ?></label>
                </div>
                <div class="radio_wrap">
                    <input type="checkbox" name="cnc_b2b_product_ranges[]" value="decorated_glassware" id="decorated_glassware" <?php if ($cnc_b2b_product_ranges && in_array("decorated_glassware", $cnc_b2b_product_ranges)) {
                                                                                                                                    echo esc_html("checked='checked'");
                                                                                                                                }
                                                                                                                                ?> />
                    <label for="decorated_glassware"><?php esc_html_e('Decorated Glassware', 'personalised-gift-supply-listing-tool') ?></label>
                </div>
                <div class="radio_wrap">
                    <input type="checkbox" name="cnc_b2b_product_ranges[]" value="engraved_trophies" id="engraved_trophies" <?php if ($cnc_b2b_product_ranges && in_array("engraved_trophies", $cnc_b2b_product_ranges)) {
                                                                                                                                echo esc_html("checked='checked'");
                                                                                                                            }
                                                                                                                            ?> />
                    <label for="engraved_trophies"><?php esc_html_e('Engraved Trophies', 'personalised-gift-supply-listing-tool') ?></label>
                </div>
                <div class="radio_wrap">
                    <input type="checkbox" name="cnc_b2b_product_ranges[]" value="say_it_with_glass" id="say_it_with_glass" <?php if ($cnc_b2b_product_ranges && in_array("say_it_with_glass", $cnc_b2b_product_ranges)) {
                                                                                                                                echo esc_html("checked='checked'");
                                                                                                                            }
                                                                                                                            ?> />
                    <label for="say_it_with_glass"><?php esc_html_e('Say it with Glass', 'personalised-gift-supply-listing-tool') ?></label>
                </div>
            </div>
            <!--......................................................................................................................................................................-->
            <div class="order_type_wrap">
                <h3><?php esc_html_e('Enable lifestyle image as main product image', 'personalised-gift-supply-listing-tool') ?></h3>
                <p><?php esc_html_e('When selected, if available a lifestyle image will be used as the main product image rather than a white background image.', 'personalised-gift-supply-listing-tool') ?></p>
                <div class="radio_wrap">
                    <input type="checkbox" name="cnc_b2b_photography_images_as_main_image" id="photography_images_as_main_image" <?php if ($cnc_b2b_photography_images_as_main_image == "on") {
                                                                                                                                        echo esc_html("checked='checked'");
                                                                                                                                    } ?> />
                    <label for="photography_images_as_main_image"><?php esc_html_e('Enable', 'personalised-gift-supply-listing-tool') ?></label>
                </div>
            </div>
            <!--......................................................................................................................................................................-->

            <div class="order_type_wrap">
                <h3><?php esc_html_e('Import All ?', 'personalised-gift-supply-listing-tool') ?></h3>
                <div class="radio_wrap">
                    <input type="checkbox" name="cnc_b2b_import_all" id="cnc_b2b_import_all" <?php if ($cnc_b2b_import_all && $cnc_b2b_import_all == "1") {
                                                                                                    echo esc_html("checked='checked'");
                                                                                                } ?> />
                    <label for="cnc_b2b_import_all"><?php esc_html_e('Import All ?', 'personalised-gift-supply-listing-tool') ?></label>
                </div>
                <?php if ($cnc_b2b_import_all && $cnc_b2b_import_all == "1") {
                    global $wpdb;
                    $count = $wpdb->get_var("
                        SELECT count(*) FROM " . $wpdb->prefix . "actionscheduler_actions 
                            WHERE 
                            hook = 'cnc_b2b_fatch_singal_page' AND
                            status = 'pending'
                    ");
                ?>
                    <div>
                        <p><b><?php esc_html_e('Note:', 'personalised-gift-supply-listing-tool') ?> </b><?php echo esc_attr($count); ?> <?php esc_html_e('Actions Left. ', 'personalised-gift-supply-listing-tool') ?><a href="<?php echo esc_url(site_url('/wp-admin/admin.php?page=wc-status&tab=action-scheduler&status=pending&s=cnc_b2b_fatch_singal_page')); ?>"><?php esc_html_e('View Pending Action', 'personalised-gift-supply-listing-tool') ?></a></p>
                    </div>
                <?php
                } ?>
            </div>

            <div class="order_type_wrap">
                <h3><?php esc_html_e('Pricing ?', 'personalised-gift-supply-listing-tool') ?></h3>
                <p><?php esc_html_e('Here you`re able to state how you would like your products priced.', 'personalised-gift-supply-listing-tool') ?></p>
                <p><?php esc_html_e('Set my own pricing - Will initially pull our RRP but you`re then able to manually set each product to the price you would like', 'personalised-gift-supply-listing-tool') ?></p>
                <p><?php esc_html_e('Suggested RRP - This will take use our RRP and continually update the RRP as products become cheaper or more expensive your RRP will fluctuate up and down making you roughly a 35% margin on each item.', 'personalised-gift-supply-listing-tool') ?></p>
                <p><?php esc_html_e('Custom Margin - This will allow you to set your own margin, after this has been selected you will need to click save which will enable two extra fields "Margin" and "Deduct from pricing" once your margin has been set your products will be rounded up to the nearest pound you can then set the value you wish to deduct from the price, for example you wish for your products to round to the nearest 99p you would select 0.01 in this field.', 'personalised-gift-supply-listing-tool') ?></p>
                <div class="radio_wrap">
                    <select class="pricing_option" name="cnc_b2b_price_for_product">
                        <option value="set_own_price" <?php if ($cnc_b2b_price_for_product && $cnc_b2b_price_for_product == "set_own_price") {
                                                            echo esc_html("selected");
                                                        } ?>><?php esc_html_e('Set my own pricing', 'personalised-gift-supply-listing-tool') ?></option>
                        <option value="suggested_rrp" <?php if ($cnc_b2b_price_for_product && $cnc_b2b_price_for_product == "suggested_rrp") {
                                                            echo esc_html("selected");
                                                        } ?>><?php esc_html_e('Suggested RRP', 'personalised-gift-supply-listing-tool') ?></option>
                        <option value="custom_margin" <?php if ($cnc_b2b_price_for_product && $cnc_b2b_price_for_product == "custom_margin") {
                                                            echo esc_html("selected");
                                                        } ?>><?php esc_html_e('Custom Margin', 'personalised-gift-supply-listing-tool') ?></option>
                    </select>
                </div>
            </div>

            <div class="cnc_b2b_margin_pricing" <?php if ($cnc_b2b_price_for_product && $cnc_b2b_price_for_product == "custom_margin") {
                                                    echo "style='display: block'";
                                                } else {
                                                    echo "style='display: none'";
                                                } ?>>
                <div class="order_type_wrap">
                    <h3><?php esc_html_e('Margin: ', 'personalised-gift-supply-listing-tool') ?></h3>
                    <div class="radio_wrap margin_input">
                        <input type="text" name="cnc_b2b_margin_for_ragular_price" id="cnc_b2b_margin_for_ragular_price" value="<?php if ($cnc_b2b_margin_for_ragular_price) {
                                                                                                                                     echo esc_attr($cnc_b2b_margin_for_ragular_price);
                                                                                                                                } ?>" />
                        <span><?php esc_html_e('Automatically Process Orders', 'personalised-gift-supply-listing-tool') ?> %</span>
                    </div>
                    <p class="cnc_b2b_margin_error" <?php if ((int)$cnc_b2b_margin_for_ragular_price < 1 || (int)$cnc_b2b_margin_for_ragular_price > 99) {
                                                        echo esc_html("style='display: block;'");
                                                    } else {
                                                        echo "style='display: none;'";
                                                    } ?>><?php esc_html_e('Margin should be less then 99 and greter then 1 or valid Integer', 'personalised-gift-supply-listing-tool') ?></p>
                </div>

                <div class="order_type_wrap">
                    <h3><?php esc_html_e('Deduct from pricing : ', 'personalised-gift-supply-listing-tool') ?></h3>
                    <div class="radio_wrap">
                        <input type="text" name="cnc_b2b_round_up_the_nearest" id="cnc_b2b_round_up_the_nearest" value="<?php if ($cnc_b2b_round_up_the_nearest) {
                                                                                                                            echo esc_attr($cnc_b2b_round_up_the_nearest);
                                                                                                                        } ?>" />
                    </div>
                </div>
            </div>

            <div class="order_type_wrap" style="display:none">
                <h3><?php esc_html_e('Maximum RRP :', 'personalised-gift-supply-listing-tool') ?></h3>
                <div class="radio_wrap">
                    <input type="text" name="cnc_b2b_maximum_rrp" id="cnc_b2b_maximum_rrp" value="<?php if ($cnc_b2b_maximum_rrp) {
                                                                                                        echo esc_attr($cnc_b2b_maximum_rrp);
                                                                                                    } ?>" />
                </div>
            </div>
            <div class="order_type_wrap">
                <div class="pgs_button">
                    <input type="submit" name="cnc_b2b_save_sync_order_setting" value="<?php esc_html_e("Save Settings", 'personalised-gift-supply-listing-tool') ?>" />
                </div>
            </div>
        </form>
    </div>
</div>