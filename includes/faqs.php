<?php
/**
 * FAQs Output
 *
 * @package CNC B2B
 */
 
if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="cnc_b2b_faq">
    <h2>
        <?php
        esc_html_e('FAQs', 'personalised-gift-supply-listing-tool');
        ?>
    </h2>
    <div class="cnc_b2b_accoding_wapper">
        <div class="accoding_item">
            <div class="accoding_header">
                <div class="accoding_title">
                    <h4>
                        <?php
                        esc_html_e('Will my order status be updated?', 'personalised-gift-supply-listing-tool');
                        ?>
                    </h4>
                </div>
                <div class="accoding_img"><img src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'assets/images/Plus_symbol.png'); ?>" class="accoding_item_img"></div>
            </div>
            <div class="accoding_body">
                <p><?php esc_html_e('Yes, once we process your order and it has been dispatched our Plugin will update WooCommerce and mark the order as completed, unless you ticked the option to mark orders as completed manually,', 'personalised-gift-supply-listing-tool'); ?></p>
            </div>
        </div>
        <div class="accoding_item">
            <div class="accoding_header">
                <div class="accoding_title">
                    <h4><?php esc_html_e('What happens if I sell one of my products and one of yours?', 'personalised-gift-supply-listing-tool'); ?></h4>
                </div>
                <div class="accoding_img"><img src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'assets/images/Plus_symbol.png'); ?>" class="accoding_item_img"></div>
            </div>
            <div class="accoding_body">
                <p><?php esc_html_e('There is an option in order settings where you can toggle if you would like us to mark the order as completed when our side is completed or wait for you to complete the order on your side.', 'personalised-gift-supply-listing-tool'); ?></p>
            </div>
        </div>
        <div class="accoding_item">
            <div class="accoding_header">
                <div class="accoding_title">
                    <h4><?php esc_html_e('Will I have to send you the personalisation details through?', 'personalised-gift-supply-listing-tool'); ?></h4>
                </div>
                <div class="accoding_img"><img src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'assets/images/Plus_symbol.png'); ?>" class="accoding_item_img"></div>
            </div>
            <div class="accoding_body">
                <p><?php esc_html_e('Not at all, this is automatically collected by our plugin and sent through to us, when your item sells everything will happen automatically, if any issues do arise we will contact you.', 'personalised-gift-supply-listing-tool'); ?></p>
            </div>
        </div>
        <div class="accoding_item">
            <div class="accoding_header">
                <div class="accoding_title">
                    <h4><?php esc_html_e('I have a customer with a broken, defective or incorrect item, what do I do?', 'personalised-gift-supply-listing-tool'); ?></h4>
                </div>
                <div class="accoding_img"><img src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'assets/images/Plus_symbol.png'); ?>" class="accoding_item_img"></div>
            </div>
            <div class="accoding_body">
                <p><?php esc_html_e('Contact us on', 'personalised-gift-supply-listing-tool'); ?> <a href="mailto:sales@personalisedgiftsupply.com"><?php mailto:
                                                                                                                        esc_html_e('sales@personalisedgiftsupply.com ', 'personalised-gift-supply-listing-tool'); ?></a><?php esc_html_e('and one of our friendly representatives will happily help, if you need an urgent answer you can call on 0800 069 9414.', 'personalised-gift-supply-listing-tool'); ?> </p>
            </div>
        </div>
        <div class="accoding_item">
            <div class="accoding_header">
                <div class="accoding_title">
                    <h4><?php esc_html_e('I have a customer asking for a bulk order, what`s the best way to proceed?', 'personalised-gift-supply-listing-tool'); ?></h4>
                </div>
                <div class="accoding_img"><img src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'assets/images/Plus_symbol.png'); ?>" class="accoding_item_img"></div>
            </div>
            <div class="accoding_body">
                <p><?php esc_html_e('The best way is to contact us, providing the customer is asking for 50+ items we can offer even more competitive rates for you.', 'personalised-gift-supply-listing-tool'); ?></p>
            </div>
        </div>
        <div class="accoding_item">
            <div class="accoding_header">
                <div class="accoding_title">
                    <h4><?php esc_html_e('What happens if you run out of stock?', 'personalised-gift-supply-listing-tool'); ?></h4>
                </div>
                <div class="accoding_img"><img src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'assets/images/Plus_symbol.png'); ?>" class="accoding_item_img"></div>
            </div>
            <div class="accoding_body">
                <p><?php esc_html_e('Although every effort is taken to ensure we are apply to supply a constant supply of products, we do however, sometimes have supply chain issues. However, if we ever run out of stock on an item, the product will automatically be taken
                    out of stock on your website to ensure you don`t sell any items we don`t have in stock.', 'personalised-gift-supply-listing-tool'); ?></p>
            </div>
        </div>
        <div class="accoding_item">
            <div class="accoding_header">
                <div class="accoding_title">
                    <h4><?php esc_html_e('Can I create my own designs and sell them on your products?', 'personalised-gift-supply-listing-tool'); ?></h4>
                </div>
                <div class="accoding_img"><img src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'assets/images/Plus_symbol.png'); ?>" class="accoding_item_img"></div>
            </div>
            <div class="accoding_body">
                <p><?php esc_html_e('Absolutely, if you email us on', 'personalised-gift-supply-listing-tool'); ?> <a href="mailto:sales@personalisedgiftsupply.com"><?php esc_html_e('mailto:sales@personalisedgiftsupply.com ', 'personalised-gift-supply-listing-tool'); ?></a><?php esc_html_e('we can assist on the best way to get your designs live!', 'personalised-gift-supply-listing-tool'); ?> </p>
            </div>
        </div>
    </div>
</div>