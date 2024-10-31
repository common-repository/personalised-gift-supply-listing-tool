<?php
$product_sync = false;
if (!defined('ABSPATH')) exit;
if ((isset($_POST["verify"]) || isset($_POST['sync_data'])) && isset($_POST['pgs_apt_setting_nonce_field'])
    && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['pgs_apt_setting_nonce_field'])), 'pgs_apt_setting_action')
) {
    $url = "https://personalisedgiftsupply.com/api/reseller-api/v1/user/token";
    $args = array(
        'headers' => array(
            'Content-Type' => 'application/json',
            'token' => esc_attr(sanitize_text_field($_POST["tokan_name"])),
            'username' => esc_attr(sanitize_text_field($_POST["pgs_username"])),
        )
    );
    $responsedata = wp_remote_get($url, $args);
    $data = wp_remote_retrieve_body($responsedata);
    $body = json_decode($data);
    $token_name = sanitize_text_field($_POST["tokan_name"]);
    $pgs_username = sanitize_text_field($_POST["pgs_username"]);
    update_option("cnc_b2b_products_api_key", $token_name);
    update_option("cnc_b2b_username", $pgs_username);
    if ($body->statusCode == 200) {
        update_option("cnc_b2b_products_api_key_varify", true);
    } else {
        update_option("cnc_b2b_products_api_key_varify", false);
    }
}
if (
    isset($_POST['sync_data']) && isset($_POST['pgs_apt_setting_nonce_field'])
    && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['pgs_apt_setting_nonce_field'])), 'pgs_apt_setting_action')
) {
    
    if (get_option("cnc_b2b_import_all") != "1") {
        $url = "https://personalisedgiftsupply.com/api/reseller-api/v1/product/list/?max_rrp=" . get_option("cnc_b2b_maximum_rrp");
        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'token' => $token_name,
                'username' =>  $pgs_username,
            )
        );
        $responsedata = wp_remote_get($url, $args);
        $data = wp_remote_retrieve_body($responsedata);
        $body = json_decode($data, true);
        if ($body['statusCode'] == 200) {
            foreach ($body['data'] as $product) {
                if ($product['post']) {
                    $post_id = cnc_b2b_create_post_to_pgs_product($product);
                    $product_sync = true;
                }
            }
        }
    }



    $fonts_url = "https://personalisedgiftsupply.com/api/reseller-api/v1/content/fonts";
    $fonts_args = array(
        'headers' => array(
            'Content-Type' => 'application/json',
            'token' => esc_attr($token_name),
            'username' => esc_attr($pgs_username),
        )
    );
    $fonts_responsedata = wp_remote_get($fonts_url, $fonts_args);
    $fonts_data = wp_remote_retrieve_body($fonts_responsedata);
    $fonts_body = json_decode($fonts_data);
    update_option("cnc_b2b_user_specific_fonts", $fonts_body->data->user_fonts);
    update_option("cnc_b2b_fonts", $fonts_body->data->option_fonts);

    $clipart_url = "https://personalisedgiftsupply.com/api/reseller-api/v1/content/clipart";
    $clipart_args = array(
        'headers' => array(
            'Content-Type' => 'application/json',
            'token' =>  esc_attr($token_name),
            'username' => esc_attr($pgs_username),
        )
    );
    $clipart_responsedata = wp_remote_get($clipart_url, $clipart_args);
    $clipart_data = wp_remote_retrieve_body($clipart_responsedata);
    $clipart_body = json_decode($clipart_data);

    update_option("cnc_b2b_cliparts", $clipart_body->data);

    if (get_option("cnc_b2b_import_all") != "1") {
        cnc_b2b_update_product_list_with_pgs();
    }
}

$apikey = get_option("cnc_b2b_products_api_key");
$varify = get_option("cnc_b2b_products_api_key_varify");
$username = get_option("cnc_b2b_username")

?>
<div class="cnc_b2b_settings_page">
    <div class="page_title">
        <h1><?php esc_html_e('Settings', 'personalised-gift-supply-listing-tool') ?></h1>
    </div>
    <form class="pgs_form" method="POST">
        <?php wp_nonce_field('pgs_apt_setting_action', 'pgs_apt_setting_nonce_field'); ?>
        <div>
            <?php
            if ($product_sync) {
            ?>
                <div class="product_sync"><?php esc_html_e(' Product Synchronize with Personalise Gift Suppy Successfully ... ', 'personalised-gift-supply-listing-tool') ?></div>
            <?php
            }
            ?>
            <div class="info_section">
                <div class="token_lebel">
                    <h3><?php esc_html_e('Username :', 'personalised-gift-supply-listing-tool') ?></h3>
                </div>
                <div class="token_and_varification">
                    <div class="pgs_col token_input">
                        <input name="pgs_username" value="<?php if ($username) {
                                                                echo esc_attr($username);
                                                            } ?>" />
                    </div>
                </div>
            </div>
            <div class="info_section">
                <div class="token_lebel">
                    <h3><?php esc_html_e('Api Key :', 'personalised-gift-supply-listing-tool') ?></h3>
                </div>
                <div class="token_and_varification">
                    <div class="pgs_col token_input">
                        <textarea name="tokan_name" cols="75" rows="10"><?php if ($apikey) {
                                                                            echo esc_attr($apikey);
                                                                        } ?></textarea>
                    </div>
                </div>
            </div>
            <div class="pgs_button">
                <input class="varification_button" type="submit" name="verify" value="Verify">
                <?php
                if ($varify == "1") {
                ?>
                    <div class="varification_wrapper"><span class="varification varification-true"></span><?php esc_html_e('Validation Successful', 'personalised-gift-supply-listing-tool') ?></div>
                <?php
                } else {
                ?>
                    <div class="varification_wrapper"><span class="varification varification-false"></span><?php esc_html_e(' Validation Fail', 'personalised-gift-supply-listing-tool') ?></div>
                <?php
                }

                ?>
            </div>
            <?php
            if ($varify == "1") {
            ?>
                <div class="pgs_button">
                    <input type="submit" name="sync_data" value="Sync Data">
                </div>
            <?php
            }
            ?>
        </div>
    </form>
    <p><?php esc_html_e('Please contact us on', 'personalised-gift-supply-listing-tool') ?> <a href="sales@personalisedgiftsupply.com"><?php esc_html_e('sales@personalisedgiftsupply.com ', 'personalised-gift-supply-listing-tool') ?> </a><?php esc_html_e('for your API key.', 'personalised-gift-supply-listing-tool') ?></p>
</div>