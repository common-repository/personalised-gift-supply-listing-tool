jQuery(document).on(
  "change",
  ".cnc_b2b_order_setting_content .order_type_wrap input[type='radio']",
  function () {
    let sync_type = jQuery(
      ".cnc_b2b_order_setting_content .order_type_wrap input[type='radio']:checked"
    ).val();
    if (sync_type == "manually_sync") {
      jQuery(".cnc_b2b_sync_order_status_wrap").hide(1000);
    } else {
      jQuery(".cnc_b2b_sync_order_status_wrap").show(1000);
    }
  }
);
jQuery(document).on("click", ".cnc_b2b_sync_with_woocommerce", function () {
  jQuery(this).children(".loadding").css("display", "block");
  var product_id = jQuery(this).attr("data_id");
  var request_data = {
    action: "cnc_b2b_sync_product_with_woocommerce",
    product_id: product_id,
    _wp_nonce: jQuery("#pgs_apt_sync_with_woocommerce_nonce_field").val(),
  };
  jQuery.post(cnc_b2b_ajax.ajaxurl, request_data, function (data) {
    var responce = JSON.parse(data);
    if (responce.status == 200 && responce.url) {
      window.location.replace(responce.url.replace(/&amp;/g, "&"));
    } else {
      alert("There are sothing wants to wrong. Product was not created");
    }
    jQuery(this).children(".loadding").css("display", "none");
  });
});
jQuery(document).on(
  "click",
  ".order_sync_manully .order_sync_manully_button",
  function () {
    var order_id = jQuery(this).attr("data-id");
    var request_data = {
      action: "cnc_b2b_sync_order_with_pgs",
      order_id: order_id,
      _wp_nonce: jQuery("#pgs_apt_manually_sync_order_nonce_field").val(),
    };
    jQuery.post(cnc_b2b_ajax.ajaxurl, request_data, function (data) {
      location.reload();
    });
  }
);

jQuery(document).on(
  "click",
  ".cnc_b2b_accoding_wapper .accoding_item .accoding_header",
  function () {
    if (jQuery(this).hasClass("active")) {
      jQuery(
        ".cnc_b2b_accoding_wapper .accoding_item .accoding_body"
      ).slideUp();
      jQuery(
        ".cnc_b2b_accoding_wapper .accoding_item .accoding_header"
      ).removeClass("active");
    } else {
      jQuery(
        ".cnc_b2b_accoding_wapper .accoding_item .accoding_header"
      ).removeClass("active");
      jQuery(
        ".cnc_b2b_accoding_wapper .accoding_item .accoding_body"
      ).slideUp();
      jQuery(this).siblings(".accoding_body").slideDown();
      jQuery(this).addClass("active");
    }
  }
);

jQuery(document).on(
  "change",
  ".cnc_b2b_order_setting_content .pricing_option",
  function () {
    let opt_val = jQuery(
      ".cnc_b2b_order_setting_content .pricing_option option:selected"
    ).val();
    if (opt_val == "custom_margin") {
      jQuery(".cnc_b2b_margin_pricing").slideDown();
    } else {
      jQuery(".cnc_b2b_margin_pricing").slideUp();
    }
  }
);

jQuery(document).on(
  "change keyup",
  '.cnc_b2b_order_setting_content .margin_input input[name="cnc_b2b_margin_for_ragular_price"]',
  function () {
    var input_val = parseInt(jQuery(this).val());
    if (!isNaN(input_val)) {
      jQuery(this).val(input_val);
    }
    if (input_val > 99 || input_val < 1 || isNaN(input_val)) {
      jQuery(".cnc_b2b_margin_error").css("display", "block");
      jQuery(".pgs_button input[name='cnc_b2b_save_sync_order_setting']")
        .prop("disabled", true)
        .addClass("not_allowed");
    } else {
      jQuery(".cnc_b2b_margin_error").css("display", "none");
      jQuery(".pgs_button input[name='cnc_b2b_save_sync_order_setting']")
        .prop("disabled", false)
        .removeClass("not_allowed");
    }
  }
);
