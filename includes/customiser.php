<?php
if (!defined('ABSPATH')) exit;
add_action("woocommerce_after_add_to_cart_quantity", "cnc_b2b_add_personalise_button_product_page", 111);
function cnc_b2b_add_personalise_button_product_page()
{
    global $engrave_fonts;
    if (get_post_meta(get_the_ID(), "cnc_b2b_bigcommerce_product", true) == "1" && !has_term(array("kings-coronation"), 'product_cat') && !has_term(array("decorated-glassware"), 'product_cat')) :
?>
<?php  esc_html(wp_nonce_field('pgs_apt_order_fields_action', 'pgs_apt_order_fields_nonce_field')); ?>
<div class="cnc_b2b_personalise_button">
    <div class="Personalise-btn">
        <button type="button" class="Pro-btn bcaddoncustomize">
            <svg enable-background="new 0 0 24 24" height="24px" id="Layer_1" version="1.1" viewBox="0 0 24 24"
                width="24px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink">
                <path
                    d="M21.635,6.366c-0.467-0.772-1.043-1.528-1.748-2.229c-0.713-0.708-1.482-1.288-2.269-1.754L19,1C19,1,21,1,22,2S23,5,23,5  L21.635,6.366z M10,18H6v-4l0.48-0.48c0.813,0.385,1.621,0.926,2.348,1.652c0.728,0.729,1.268,1.535,1.652,2.348L10,18z M20.48,7.52  l-8.846,8.845c-0.467-0.771-1.043-1.529-1.748-2.229c-0.712-0.709-1.482-1.288-2.269-1.754L16.48,3.52  c0.813,0.383,1.621,0.924,2.348,1.651C19.557,5.899,20.097,6.707,20.48,7.52z M4,4v16h16v-7l3-3.038V21c0,1.105-0.896,2-2,2H3  c-1.104,0-2-0.895-2-2V3c0-1.104,0.896-2,2-2h11.01l-3.001,3H4z">
                </path>
            </svg>
            <span><?php esc_html_e('Personalise', 'personalised-gift-supply-listing-tool') ?></span>
        </button>
        <div class="quantity-notice-second">
            <div>
                <?php esc_html_e('If you require', 'personalised-gift-supply-listing-tool') ?><span><?php esc_html_e('more than 1', 'personalised-gift-supply-listing-tool') ?></span><?php esc_html_e(' of the same item,', 'personalised-gift-supply-listing-tool') ?>
                <span><?php esc_html_e('but with different customised text.', 'personalised-gift-supply-listing-tool') ?>
                </span> <br>
                <?php esc_html_e('Please make sure to press the “ADD TO CART” button above, after entering the first custom text. Then simply repeat the process for subsequent items.', 'personalised-gift-supply-listing-tool') ?>
            </div>
        </div>
    </div>
    <div class="right_side_div">
        <div class="customisation_details">
            <div class="fullwidth font-customisation-result" data-id="font">
                <div class="width_20"><?php esc_html_e('Font', 'personalised-gift-supply-listing-tool') ?></div>
                <div class="width_70 ">&nbsp;</div>
                <input type="hidden" name="engrave_fonts" class="engrave_fonts" value="" />
            </div>
            <?php
                    for ($x = 1; $x <= 10; $x++) {
                    ?>
            <div class="fullwidth font-customisation-result" data-id="<?php echo esc_attr($x); ?>">
                <div class="width_70"></div>
                <div class="width_20 ">&nbsp;</div>
                <input type="hidden" name="font_value_<?php echo esc_attr($x); ?>" class="engrave_text_box" value="" />
            </div>
            <?php
                    }
                    ?>
            <input type="hidden" name="engrave_product_sku" class="engrave_product_sku"
                value="<?php echo esc_attr(get_post_meta(get_the_ID(), "cnc_b2b_bigcommerce_sku", true)); ?>" />
            <input type="hidden" name="clipart" class="engrave_clipart" value="" />
            <input type="hidden" name="font_color" class="engrave_font_color" value="" />
            <div class="fullwidth notice-centeralised">
                <?php esc_html_e('All text will be centralised', 'personalised-gift-supply-listing-tool') ?></div>
        </div>
    </div>
    <?php $postdata = (array)get_post_meta(get_the_ID(), "customiser_data", true);
             if(empty($postdata) || (isset($postdata[0]) && empty($postdata[0])) ){
                
                 try{
                    //as_schedule_single_action(strtotime('now'), 'cnc_b2b_run_product_single_sku', array("sku" => get_post_meta(get_the_ID(),'cnc_b2b_bigcommerce_sku',true),'product_id'=>get_the_ID()));
                    cnc_b2b_sync_product_with_sku(get_post_meta(get_the_ID(),'cnc_b2b_bigcommerce_sku',true),get_the_ID());
                    $postdata = (array)get_post_meta(get_the_ID(), "customiser_data", true);
                } catch(Exception $e){
                  //  print_r($e);
                }
            } ?>

    <?php if ($postdata['product_type_cnc'] == 'print') :
                $path = $postdata['previewimage'];
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                // print_r($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                global $cnc_b2b_url;
            ?>
    <?php $cnc_b2b_url = sanitize_url($cnc_b2b_url . 'includes/fileupload.php');
                $str_replace = sanitize_text_field(str_replace('secureservercdn.net/160.153.137.170/', '', $postdata['previewimage']));
                $print_image_width = sanitize_text_field($postdata['print_image_width']);
                $print_image_height = sanitize_text_field($postdata['print_image_height']);
                $print_position_left = sanitize_text_field($postdata['print_position_left']);
                $print_position_top = sanitize_text_field($postdata['print_position_top']);
                ?>
    <input type="hidden" id="fileupload" value="<?php echo esc_attr($cnc_b2b_url) ?>" />
    <input type="hidden" id="frameurl" value="<?php echo esc_attr($str_replace) ?>" />
    <input type="hidden" id="uploadNewUrl" value="" />
    <input type="hidden" id="originalWidth" value="<?php echo esc_attr($print_image_width) ?>" />
    <input type="hidden" id="originalHeight" value="<?php echo esc_attr($print_image_height) ?>" />
    <input type="hidden" id="imageLeftBase" value="<?php echo esc_attr($print_position_left) ?>" />
    <input type="hidden" id="imageTopBase" value="<?php echo esc_attr($print_position_top) ?>" />
    <input type="hidden" id="imageLeft" value="" />
    <input type="hidden" id="imageTop" value="" />
    <input type="hidden" id="imageRotation" value="" />
    <input type="hidden" id="print_url" name="print_url" value="" />
    <input type="hidden" id="image_url" name="image_url" value="" />
    <input type="hidden" id="pgs_apt_fileupload" name="pgs_apt_fileupload"
        value="<?php echo esc_attr(wp_create_nonce('pgs_apt_fileupload')); ?>" />
    <?php wp_nonce_field('pgs_apt_form_data_action', 'pgs_apt_form_data_nonce_field'); ?>


    <div class="customizationpopup" style="display:none;">
        <div class="width_100 headSection">
            <h1><?php esc_html_e('Customise Your Product', 'personalised-gift-supply-listing-tool') ?></h1>
            <span><button type="button" onclick="closepopup()" class="closeIcon"></button></span>
        </div>
        <div class="width_100 contentWrapper print">
            <div class="width_50 left">
                <div class="print_wrap" id="screenshort-wrapper" style="
                        width : <?php echo esc_attr($postdata['print_image_width']); ?>px;
                        height : <?php echo esc_attr($postdata['print_image_height']); ?>px;
                        ">
                    <img src="<?php echo esc_url($path) ?>" style="
                        width : <?php echo esc_attr($postdata['print_image_width']); ?>px;
                        height : <?php echo esc_attr($postdata['print_image_height']); ?>px;
                        " />
                    <div class="printarea" style="
                            width: <?php echo esc_attr($postdata['print_width']); ?>px;
                            height: <?php echo esc_attr($postdata['print_height']); ?>px;
                            margin-top: <?php echo esc_attr($postdata['print_position_top']); ?>px;
                            margin-left: <?php echo esc_attr($postdata['print_position_left']); ?>px;
                            ">
                        <div id="imageWrapper" class="defaultState" style="
                            width: <?php echo esc_attr($postdata['print_width'] - 10); ?>px;
                            height: <?php echo esc_attr($postdata['print_height'] - 10); ?>px;" hidden>
                            <img id="imgLogo" src="" alt="" />
                        </div>
                    </div>
                </div>
                <div class="notice width_100">
                    <?php esc_html_e('Use your mouse to scale, move & rotate your image', 'personalised-gift-supply-listing-tool') ?>
                </div>
            </div>
            <div class="width_50 right">
                <div class="width_100 mobile_bc_action_button">
                    <div class="width_30">
                        <div class="inline-block rotate-left"><i class="fa fa-undo"></i></div>
                        <div class="inline-block rotate-right"><i class="fa fa-repeat"></i></div>
                    </div>
                    <div class="width_30">
                        <div class="inline-block mv-left"><i class="fa fa-arrow-left"></i></div>
                        <div class="inline-block mv-right"><i class="fa fa-arrow-right"></i></div>
                        <div class="inline-block mv-up"><i class="fa fa-arrow-up"></i></div>
                        <div class="inline-block mv-down"><i class="fa fa-arrow-down"></i></div>
                    </div>
                    <div class="width_30">
                        <div class="inline-block zoomin"><i class="fa fa-search-minus"></i></div>
                        <div class="inline-block zoomout"><i class="fa fa-search-plus"></i></div>
                    </div>
                </div>
                <h2><?php esc_html_e('Image Upload', 'personalised-gift-supply-listing-tool') ?></h2>
                <div class="width_100" />
                <input type="file" accept="jpg,jpeg,png,gif" name="userfileprint" onchange="readURL(this,'print')" />
            </div>
            <div class="width_100 remove_image">
                <button onclick="remove_image()"
                    class="remove_image"><?php esc_html_e('Remove Image', 'personalised-gift-supply-listing-tool') ?></button>
            </div>
            <div class="width_100 remove_image">

            </div>
            <div class="width_100">
                <button onclick="downloadimage()"
                    class="confirm_print"><?php esc_html_e('Confirm Customisation', 'personalised-gift-supply-listing-tool') ?></button>
            </div>
        </div>
    </div>
</div>
<?php
            endif; ?>
<?php if ($postdata['product_type_cnc'] == 'engrave')  : ?>
<div class="customizationpopup" style="display:none;">
    <?php
            $engrave_user_fonts = array();
            ?>
    <style id="personalised-style">
    <?php $user_specific_fonts=get_option('cnc_b2b_user_specific_fonts');
    $rows=get_option('cnc_b2b_fonts');

    if (gettype($postdata['engrave_fonts'])=='string') {
        $postdata['engrave_fonts']=array($postdata['engrave_fonts']);
    }

    if ($rows) {
        foreach ($rows as $row) {

            $engrave_fonts[str_replace(' ', '', $row->name_of_font)]=$row->name_of_font;

            ?>@font-face {
                font-family: "<?php echo esc_attr($row->name_of_font); ?>";
                src: url('<?php echo esc_attr($row->font_file); ?>');
            }

            .<?php echo esc_attr(str_replace(' ', '', $row->name_of_font));

            ?> {
                font-family: '<?php echo esc_attr($row->name_of_font); ?>';
            }

            <?php
        }
    }

    if ($user_specific_fonts) {
        foreach ($user_specific_fonts as $user_font) {

            $engrave_user_fonts[str_replace(' ', '', $user_font->name_of_font)]=$user_font->name_of_font;

            ?>@font-face {
                font-family: '<?php echo esc_url($user_font->name_of_font); ?>';
                src: url('<?php echo esc_url($user_font->name_of_font); ?>');
            }

            .<?php echo esc_attr(str_replace(' ', '', $user_font->name_of_font));

            ?> {
                font-family: '<?php echo esc_url($user_font->name_of_font); ?>';
            }

            <?php
        }
    }

    ksort($engrave_fonts);


    ?>
    </style>
    <?php


            ?>
    <div class="width_100 headSection">


        <h1><?php esc_html_e('Customise Your Gift', 'personalised-gift-supply-listing-tool') ?></h1>
        <span><button type="button" onclick="closepopup()" class="closeIcon"></button></span>
    </div>
    <div class="width_100 contentWrapper">
        <?php
                if (!empty($postdata['engrave_option_images'])) {
                    foreach ($postdata['engrave_option_images'] as $key => $value) {
                ?>
        <input type="hidden" data-id="<?php echo esc_attr($key) ?>" class="image_variation_hidden"
            value="<?php echo esc_attr($value); ?>" />
        <?php
                    }
                }
                ?>
        <input type="hidden" class="variation_data_hidden"
            value='<?php echo wp_json_encode($postdata['varialble_option'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>' />
        <input type="hidden" class="engrave_visible_font_customization"
            value='<?php $engrave_visible_font_customization = sanitize_text_field($postdata['engrave_visible_font_customization']);
                                                                                        echo esc_attr($engrave_visible_font_customization); ?>' />
        <div class="width_50 left">
            <div class="print_wrap engrave" id="screenshort-wrapper" style="
                            height : <?php echo esc_attr($postdata['engrave_image_height'] + 10); ?>px;
                            width : <?php echo esc_attr($postdata['engrave_image_width'] + 10); ?>px;
                            ">
                <img class="engrave_main_image"
                    src="<?php echo esc_url(str_replace(array("es.", "aus.", "us."), '', $postdata['engravepreviewimage'])); ?>"
                    style="
                                width : <?php echo esc_attr($postdata['engrave_image_width']); ?>px;
                                height : <?php echo esc_attr($postdata['engrave_image_height']); ?>px;
                                " />
                <?php
                        for ($i = 1; $i <= $postdata['engrave_no_of_customization']; $i++) {
                        ?>
                <div class="font_frontend <?php if ($postdata['multiple_fonts'] == 1) {
                                                            echo esc_attr($postdata['engrave_fonts_' . $i]);
                                                        } ?>
                                    <?php if ($postdata['engrave_fonts_override_' . $i] == 1) {
                                        echo esc_html('override_this');
                                    } ?>
                                    " style="
                                    color:<?php echo esc_attr((substr($postdata['font_color_' . $i], 0, 1) == '#') ? $postdata['font_color_' . $i] : '#' . $postdata['font_color_' . $i]); ?>;
                                    font-size:<?php echo  esc_attr($postdata['font_size_' . $i]) ?>px;
                                    top : <?php echo  esc_attr($postdata['position_top_' . $i]) ?>px;
                                    left : <?php echo  esc_attr($postdata['position_left_' . $i]) ?>px;
                                    height : <?php echo  esc_attr($postdata['engrave_height_' . $i]) ?>px;
                                    width : <?php echo  esc_attr($postdata['engrave_width_' . $i]) ?>px;
                                    " data-id="<?php echo esc_attr($i); ?>">
                    <?php esc_attr($postdata['sample_text_' . $i]); ?>
                    <div class="cnc_font_actual"></div>
                </div>
                <?php $font_size_ = sanitize_text_field($postdata['font_size_' . $i]) ?>
                <input type="hidden" name="cnc_font_size_<?php echo esc_attr($i); ?>"
                    value="<?php echo  esc_attr($font_size_); ?>" id="cnc_font_size_<?php echo esc_attr($i); ?>" />
                <input type="hidden" name="cnc_current_font_size_<?php echo esc_attr($i); ?>"
                    value="<?php echo  esc_attr($font_size_) ?>"
                    id="cnc_current_font_size_<?php echo esc_attr($i); ?>" />
                <?php
                        }

                        ?>

            </div>
            <div class="notice width_100 engravenotice">
                <?php esc_html_e('Note: Display is an approximate preview. final product may vary slightly, although we will notify of any major changes. We don`t allow emojis.', 'personalised-gift-supply-listing-tool') ?>
            </div>
        </div>
        <div class="width_50 right">
            <?php
                    if (count($postdata['engrave_fonts']) == 1) :
                    ?>
            <input type="hidden" id="engrave_fonts" name="engrave_fonts"
                value="<?php $engrave_fonts = sanitize_text_field($postdata['engrave_fonts'][0]);
                                                                                            echo esc_attr($engrave_fonts); ?>">
            <?php

                    else :
                    ?>

            <div class="width_100" <?php if (isset($postdata['multiple_fonts']) && $postdata['multiple_fonts'] == 1) {
                                                    echo esc_html('style="display:none"');
                                                } ?>>
                <b><?php esc_html_e('Font Choice', 'personalised-gift-supply-listing-tool') ?></b>
            </div>
            <div class="width_100" <?php if (isset($postdata['multiple_fonts']) && $postdata['multiple_fonts'] == 1) {
                                                    echo esc_html('style="display:none"');
                                                } ?>>
                <select name="engrave_fonts" id="engrave_fonts">
                    <?php
                                if (gettype($postdata['engrave_fonts']) == 'string') {
                                    $postdata['engrave_fonts'] = array($postdata['engrave_fonts']);
                                }
                                $i = 0;
                                foreach ($engrave_fonts as $key => $font) :
                                    if (!in_array($key, $postdata['engrave_fonts'])) :
                                        continue;
                                    endif;
                                    if ($postdata['engrave_fonts'][0] == 'curlz' && $i == 0 && in_array('zapfchancery', $postdata['engrave_fonts'])) :
                                        unset($engrave_fonts['$engrave_fonts']);
                                ?><option class="zapfchancery" value="zapfchancery">
                        <?php esc_html_e('Zapf Chancery', 'personalised-gift-supply-listing-tool') ?></option>
                    <?php
                                                                                                                                endif;
                                                                                                                                $i++;
                                                                                                                                    ?>
                    <option class="<?php echo esc_attr($key); ?>" value="<?php echo esc_attr($key); ?>">
                        <?php echo esc_attr($font); ?></option>
                    <?php
                                endforeach;

                                foreach ($engrave_user_fonts as $key => $font) :
                                ?>
                    <option class="<?php echo esc_attr($key); ?>" value="<?php echo esc_attr($key); ?>">
                        <?php echo esc_attr($font); ?></option>
                    <?php
                                endforeach;
                                ?>
                </select>
            </div>
            <?php
                    endif;
                    ?>

            <?php if (isset($postdata['engrave_enable_clip_art'])) : ?>
            <div class="width_100">
                <b><?php esc_html_e('Clip Art', 'personalised-gift-supply-listing-tool') ?></b>
            </div>
            <div class="width_100">
                <select id="clipart" name="clipart">
                    <option value=""><?php esc_html_e('(not selected)', 'personalised-gift-supply-listing-tool') ?>
                    </option>
                    <?php
                                foreach (get_option('cnc_b2b_cliparts') as $cliparts) {
                                ?>
                    <option data-height="<?php echo esc_attr($cliparts->height); ?>"
                        data-width="<?php echo esc_attr($cliparts->width); ?>"
                        value="<?php echo esc_attr($cliparts->internal_name); ?>"
                        data-image="<?php echo esc_attr($cliparts->image); ?>"><?php echo esc_attr($cliparts->name); ?>
                    </option>
                    <?php
                                }
                                ?>
                </select>
                <div class="notice width_100 engravenotice" style="margin-top: 0;color: #fe0000;margin-bottom: 15px;">
                    <?php esc_html_e('Note: While using a clipart you will not be able use the first two lines of text.', 'personalised-gift-supply-listing-tool') ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($postdata['available_color'])) : ?>
            <div class="width_100">
                <b><?php esc_html_e('Select Color', 'personalised-gift-supply-listing-tool') ?></b>
            </div>
            <?php endif; ?>
            <div class="width_100 color_selection">

                <div class="engrave_color_selection width_100">
                    <?php


                            $color = (substr($postdata['font_color_1'], 0, 1) == '#') ? $postdata['font_color_1'] : '#' . $postdata['font_color_1'];

                            if (!empty($postdata['available_color_label'][0])) :
                            ?>
                    <select class="engrave_color_class_select <?php if ($postdata['color_for_variation'] == '1') {
                                                                                echo esc_html('color_for_variation');
                                                                            } ?> <?php if ($postdata['override_font_color']) {
                                                                                        echo esc_html('override_font_color');
                                                                                    } ?>">
                        <option value=""><?php esc_html_e('Select Color', 'personalised-gift-supply-listing-tool') ?>
                        </option>
                        <?php
                                    foreach ($postdata['available_color_label'] as $key => $value) {
                                        $color = (substr($postdata['available_color'][$key], 0, 1) == '#') ? $postdata['available_color'][$key] : '#' . $postdata['available_color'][$key];
                                    ?>
                        <option data-id="1"
                            data-image="<?php echo esc_attr($postdata['available_color_image'][$key]) ?>"
                            data-label="<?php echo esc_attr($value) ?>" value="<?php echo esc_attr($value)  ?>"
                            data-color="<?php echo esc_attr($color) ?>"><?php echo esc_attr($value); ?></option>

                        <?php
                                    }

                                    ?>
                    </select>

                    <?php
                            else :
                                if (!empty($postdata['available_color'])) :
                                    foreach ($postdata['available_color'] as $key => $value) {
                                ?>

                    <input type="radio" class="enagrave_color_class" data-id="1" value="<?php echo esc_attr($value); ?>"
                        id="enagrave_color_1_<?php echo esc_attr($key); ?>" name="enagrave_color_1"
                        <?php if ($color == $value) {
                                                                                                                                                                                                                                echo esc_html('checked="checked"');
                                                                                                                                                                                                                            } ?> />
                    <label for="enagrave_color_1_<?php echo esc_attr($key); ?>"
                        style="background-color: <?php echo esc_attr($value); ?>" />
                    </label>
                    <?php
                                    }
                                endif;
                            endif;
                            ?>
                </div>

            </div>
            <?php
                    for ($i = 1; $i <= $postdata['engrave_no_of_customization']; $i++) {
                    ?>
            <div class="width_100">
                <div class="width_50">
                    <div class="width_100 show_hide<?php echo esc_attr($i); ?>"> &nbsp;
                        <!--<b>Line <?php echo esc_attr($i); ?></b>-->
                    </div>
                </div>
                <div class="width_50">
                    <div class="width_100 show_hide<?php echo esc_attr($i); ?>">
                        <strong>
                            <div class="total_font" data-id="<?php echo esc_attr($i); ?>">
                                <?php echo esc_attr($postdata['max_character_' . $i]); ?></div>
                        </strong>
                        <?php $max_char = sanitize_text_field($postdata['max_character_' . $i]) ?>
                        <input type="hidden" class="font_value_remaining" data-id="<?php echo esc_attr($i); ?>"
                            value="<?php echo esc_attr($max_char); ?>" />
                        <input type="hidden" class="cnc_total_character" data-id="<?php echo esc_attr($i); ?>"
                            value="<?php echo esc_attr($max_char); ?>" />
                    </div>
                </div>
            </div>



            <div class="width_100 show_hide<?php echo esc_attr($i); ?>">
                <?php $placeholder = empty($postdata['sample_text_'.$i]) ? "Line ".$i : $postdata['sample_text_'.$i]; ?>
                <input placeholder="<?php echo esc_attr($placeholder)  ?>" class="font_value"
                    data-id="<?php echo esc_attr($i); ?>" value=""
                    maxlength="<?php echo esc_attr($postdata['max_character_' . $i]); ?>" />
            </div>

            <?php

                        if ($postdata['engrave_font_size_type'] == 'fixed') {
                        ?>
            <select name="cnc_font_size" id="cnc_font_size" class="cnc_font_size show_hide<?php echo esc_attr($i); ?>"
                data-id="<?php echo esc_attr($i); ?>">
                <?php
                                for ($j = 30; $j >= 10; $j--) {
                                ?>
                <option value=<?php echo esc_attr($j); ?> <?php if ($j == $postdata['font_size_' . $i]) {
                                                                                    echo 'selected="selected"';
                                                                                } ?>><?php echo esc_attr($j); ?>
                </option>
                <?php
                                }
                                ?>
            </select>
            <?php
                        }

                        ?>
            <?php
                    }
                    ?>
            <div class="customisation-double-check">
                <?php esc_html_e('Have you double-checked your personalised details? ', 'personalised-gift-supply-listing-tool') ?><br>
                <?php esc_html_e('Personalisation details will be used as submitted', 'personalised-gift-supply-listing-tool') ?>
            </div>
            <!-- Hidden field -->
            <div class="hidden-field">
                <input type="hidden" name="cnc_nonce_action" value="cnc_nonce_add_to_cart_action">
            </div>

            <div class="width_100">
                <button onclick="closepopup(true)"
                    class="confirm_print"><?php esc_html_e('Confirm Customisation', 'personalised-gift-supply-listing-tool') ?></button>

            </div>
            <input type="hidden" name="cnc_product_type" id="cnc_product_type"
                value="<?php $engrave_font_size_type = sanitize_text_field($postdata['engrave_font_size_type']);
                                                                                                echo esc_attr($engrave_font_size_type); ?>" />
        </div>
    </div>
</div>
<?php endif; ?>
</div>
<?php
    endif;
}

// ------------------------------------------------------WooCommerce Coustom Data to Cart,Checkout,order------------------------------------------------//

add_filter('woocommerce_add_cart_item_data', 'cnc_b2b_add_cart_item_data', 25, 2);
function cnc_b2b_add_cart_item_data($cart_item_data, $product_id)
{
    if (isset($_POST['pgs_apt_order_fields_nonce_field']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['pgs_apt_order_fields_nonce_field'])), 'pgs_apt_order_fields_action')) {
        if (isset($_POST['engrave_product_sku'])) {
            if (!empty($_POST['engrave_product_sku'])) {
                $engrave_product_sku = sanitize_text_field($_POST['engrave_product_sku']);
                $cart_item_data['engrave_product_sku'] =  esc_attr($engrave_product_sku);
            }
        }
    
        if (isset($_POST['engrave_fonts'])) {
            if (!empty($_POST['engrave_fonts'])) {
                $engrave_fonts = sanitize_text_field($_POST['engrave_fonts']);
                $cart_item_data['engrave_fonts'] = esc_attr($engrave_fonts);
            }
        }
    
        if (isset($_POST['font_color'])) {
            if (!empty($_POST['font_color'])) {
                $font_color = sanitize_text_field($_POST['font_color']);
                $cart_item_data['font_color'] = esc_attr($font_color);
            }
        }
    
        for ($x = 1; $x <= 10; $x++) {
            if (isset($_POST['font_value_' . $x])) {
                if (!empty($_POST['font_value_' . $x])) {
                    $font_value_ = sanitize_text_field($_POST['font_value_' . $x]);
                    $cart_item_data['font_value_' . $x] = esc_attr(stripslashes($font_value_));
                }
            }
        }
    
        if (isset($_POST['clipart'])) {
            if (!empty($_POST['clipart'])) {
                $clipart = sanitize_text_field($_POST['clipart']);
                $cart_item_data['clipart'] = esc_attr($clipart);
            }
        }
        if (isset($_POST['print_url'])) {
            if (!empty($_POST['print_url'])) {
                $print_url = sanitize_text_field($_POST['print_url']);
                $cart_item_data['print_url'] = esc_attr($print_url);
            }
        }
        if (isset($_POST['image_url'])) {
            if (!empty($_POST['image_url'])) {
                $image_url = sanitize_text_field($_POST['image_url']);
                $cart_item_data['image_url'] = esc_attr($image_url);
            }
        }
    }
    return $cart_item_data;
}

// Display custom data on cart and checkout page.
add_filter('woocommerce_get_item_data', 'cnc_b2b_get_item_data', 25, 2);
function cnc_b2b_get_item_data($cart_data, $cart_item)
{
    if (isset($cart_item['engrave_product_sku'])) {
        $engrave_product_sku = sanitize_text_field($cart_item['engrave_product_sku']);
        $engrave_fonts = sanitize_text_field($cart_item['engrave_fonts']);
        $font_color = sanitize_text_field($cart_item['font_color']);
        $clipart = sanitize_text_field($cart_item['clipart']);
        $print_url = sanitize_text_field($cart_item['print_url']);
        $image_url = sanitize_text_field($cart_item['image_url']);


        $cart_data[] = array(
            'name'    => __("Engrave SKU"),
            'display' =>  esc_attr($engrave_product_sku)
        );
    }
    if (isset($cart_item['engrave_fonts'])) {
        $cart_data[] = array(
            'name'    => __("Engrave Fonts"),
            'display' =>   esc_attr($engrave_fonts)
        );
    }
    if (isset($cart_item['font_color'])) {
        $cart_data[] = array(
            'name'    => __("Engrave Font Color"),
            'display' =>  esc_attr($font_color)
        );
    }
    for ($x = 1; $x <= 10; $x++) {
        if (isset($cart_item['font_value_' . $x])) {
            $font_value_ = sanitize_text_field($cart_item['font_value_' . $x]);
            $cart_data[] = array(

                'name'    => __('Engrave Font ') . $x,
                'display' => esc_attr($font_value_)
            );
        }
    }
    if (isset($cart_item['clipart'])) {
        $cart_data[] = array(
            'name'    => __("Engrave Clipart"),
            'display' =>  esc_attr($clipart)
        );
    }
    if (isset($cart_item['print_url'])) {
        $cart_data[] = array(
            'name'    => __("Print Preview"),
            'display' => esc_attr($print_url)
        );
    }
    if (isset($cart_item['image_url'])) {
        $cart_data[] = array(
            'name'    => __("Uploaded File"),
            'display' =>  esc_attr($image_url)
        );
    }

    return $cart_data;
}

// Add order item meta.
add_action('woocommerce_add_order_item_meta', 'cnc_b2b_add_order_item_meta', 10, 3);
function cnc_b2b_add_order_item_meta($item_id, $cart_item, $cart_item_key)
{
    $engrave_product_sku = sanitize_text_field($cart_item['engrave_product_sku']);
    $engrave_fonts = sanitize_text_field($cart_item['engrave_fonts']);
    $font_color = sanitize_text_field($cart_item['font_color']);
    $clipart = sanitize_text_field($cart_item['clipart']);
    $print_url = sanitize_text_field($cart_item['print_url']);
    $image_url = sanitize_text_field($cart_item['image_url']);

    if (isset($cart_item['engrave_product_sku'])) {
        wc_add_order_item_meta($item_id, "Engrave SKU", esc_attr($engrave_product_sku));
    }
    if (isset($cart_item['engrave_fonts'])) {
        wc_add_order_item_meta($item_id, "Engrave Fonts",  esc_attr($engrave_fonts));
    }
    if (isset($cart_item['font_color'])) {
        wc_add_order_item_meta($item_id, "Engrave Font Color",  esc_attr($font_color));
    }
    for ($x = 1; $x <= 10; $x++) {
        $font_value_ = sanitize_text_field($cart_item['font_value_' . $x]);
        if (isset($cart_item['font_value_' . $x])) {
            wc_add_order_item_meta($item_id, 'Engrave Font ' . $x, esc_attr($font_value_));
        }
    }
    if (isset($cart_item['clipart'])) {
        wc_add_order_item_meta($item_id, "Engrave Clipart",  esc_attr($clipart));
    }
    if (isset($cart_item['print_url'])) {
        wc_add_order_item_meta($item_id, "Print Preview", esc_attr($print_url));
    }
    if (isset($cart_item['image_url'])) {
        wc_add_order_item_meta($item_id, "Uploaded File", esc_attr($image_url));
    }
}


add_action('woocommerce_thankyou', 'cnc_b2b_after_order_create', 10, 1);
function cnc_b2b_after_order_create($order_id)
{
    $order = wc_get_order($order_id);
    if (!$order) {
        return;
    }

    $is_cnc_b2b_product = false;

    foreach ($order->get_items() as $item_id => $item) {
        if ($item->get_meta('Engrave SKU', true)) {
            $is_cnc_b2b_product = true;
        }
    }

    if ($is_cnc_b2b_product) {
        update_post_meta($order_id, "is_cnc_b2b_order", true);
    }
}   

// ------------------------------------------------------WooCommerce Coustom Data to Cart,Checkout,order------------------------------------------------//