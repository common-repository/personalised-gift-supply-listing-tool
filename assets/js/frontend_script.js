jQuery(document).ready(function () {
  if (
    jQuery("body").hasClass("cnc_b2b_product") &&
    !jQuery("body").hasClass("cnc_b2b_product_with_no_configurator")
  ) {
    jQuery(".single_add_to_cart_button[name='add-to-cart']").attr(
      "disabled",
      "disabled"
    );
  }
});

// jQuery(document).on("click",".cnc_b2b_personalise_button .Personalise-btn button",function(){
//     jQuery('.customizationpopup').bPopup();
// });

// function closepopup(isSubmit){
//     jQuery('.customizationpopup').bPopup().close();
//     if(isSubmit){
//         jQuery('.engrave_fonts').val(jQuery('#engrave_fonts').val());
//     }
// }

// *****************************************************************************front end js*******************************************************************************//


(function () {
  jQuery(document).on(
    "change",
    ".bc-product-variant__radio--hidden",
    function () {
      if (jQuery(".bcaddoncustomize").length) {
        setTimeout(function () {
          if (jQuery(".bcaddoncustomize").html() == "Customization Confirmed") {
            jQuery(".bc-product-single__top button.bc-btn--add_to_cart").attr(
              "disabled",
              false
            );
          } else {
            jQuery(".bc-product-single__top button.bc-btn--add_to_cart").attr(
              "disabled",
              true
            );
          }
        }, 500);
      }
    }
  );
  jQuery(document).ready(function () {
    jQuery('.bc-product-form__control input[type="radio"]:checked').trigger(
      "click"
    );

    jQuery(".bc-product-form__option-label").each(function () {
      if (jQuery(this).html() == "Design") {
        if (
          jQuery(this)
            .parents(".bc-product-form__control")
            .find('input[type="radio"]:checked').length
        ) {
          $this = jQuery(this)
            .parents(".bc-product-form__control")
            .find('input[type="radio"]:checked');
          setTimeout(function () {
            $this.trigger("change").trigger("click");
          }, 3000);
        }
      }
    });

    setTimeout(function () {
      if (jQuery(".bcaddoncustomize").length) {
        jQuery(".bc-product-single__top button.bc-btn--add_to_cart").attr(
          "disabled",
          true
        );
      }
    }, 300);

    if (
      jQuery(".variation_data_hidden").val() !== undefined &&
      jQuery.parseJSON(jQuery(".variation_data_hidden").val())
    ) {
      for (m = 1; m <= 10; m++) {
        if (m <= jQuery(".engrave_visible_font_customization").val()) {
          jQuery(".show_hide" + m).show();
        } else {
          jQuery(".show_hide" + m).hide();
        }
      }
    }

    jQuery(document).on(
      "change",
      ".bc-product-form__option-variants input",
      function () {
        var value = jQuery(this).val();

        if (jQuery('.image_variation_hidden[data-id="' + value + '"]').length) {
          if (
            jQuery('.image_variation_hidden[data-id="' + value + '"]').val() !=
              "" &&
            jQuery('.image_variation_hidden[data-id="' + value + '"]').val() !=
              undefined
          ) {
            console.log(
              jQuery('.image_variation_hidden[data-id="' + value + '"]').val()
            );
            jQuery(".print_wrap.engrave")
              .find(".engrave_main_image")
              .attr(
                "src",
                jQuery('.image_variation_hidden[data-id="' + value + '"]').val()
              );
          }
          var fonts_hidden = jQuery("#fonts_hidden").val();
          var variation_data = jQuery(".variation_data_hidden").val();

          variation_data = jQuery.parseJSON(variation_data);
          console.log(variation_data);
          if (fonts_hidden) {
            fonts_hidden = jQuery.parseJSON(fonts_hidden);
            if (variation_data.fonts) {
              allowed_fonts = variation_data.fonts[value];
              if (allowed_fonts) {
                jQuery("#engrave_fonts").html("");
                jQuery.each(fonts_hidden, function (index, font_name) {
                  if (Array.isArray(allowed_fonts)) {
                    if (jQuery.inArray(index, allowed_fonts) > -1) {
                      jQuery("#engrave_fonts").append(
                        '<option value="' +
                          index +
                          '">' +
                          font_name +
                          "</option>"
                      );
                    }
                  } else {
                    if (index == allowed_fonts) {
                      jQuery("#engrave_fonts").append(
                        '<option value="' +
                          index +
                          '">' +
                          font_name +
                          "</option>"
                      );
                    }
                  }
                });
              }
            }
          }
          // jQuery('#engrave_fonts').trigger('change');

          if (
            jQuery(".variation_data_hidden").val() !== undefined &&
            jQuery.parseJSON(jQuery(".variation_data_hidden").val())
          ) {
            for (m = 1; m <= 10; m++) {
              if (m <= variation_data.no_of_customisation[value]) {
                jQuery(".show_hide" + m).show();
              } else {
                jQuery(".show_hide" + m).hide();
              }
            }
          }

          jQuery(".font_value").val("").trigger("change");
          jQuery(".font_frontend").each(function () {
            var $this = jQuery(this);

            var id = jQuery(this).attr("data-id");
            if (variation_data.font_color[value][id].indexOf("#") !== -1) {
              color = variation_data.font_color[value][id];
            } else {
              color = "#" + variation_data.font_color[value][id];
            }
            $this.css("color", color);
            $this.css("font-size", variation_data.font_size[value][id] + "px");
            $this.css("top", variation_data.position_top[value][id] + "px");
            $this.css("left", variation_data.position_left[value][id] + "px");
            $this.css(
              "height",
              variation_data.engrave_height[value][id] + "px"
            );
            $this.css("width", variation_data.engrave_width[value][id] + "px");
            jQuery("#cnc_font_size_" + id).val(
              variation_data.font_size[value][id]
            );
            $this.find(".cnc_font_actual").html("");
            jQuery(".font_value").val("");
            jQuery("#cnc_current_font_size_" + id).val(
              variation_data.font_size[value][id]
            );
            jQuery('.cnc_total_character[data-id="' + id + '"]').val(
              variation_data.max_character[value][id]
            );
            jQuery('.font_value_remaining[data-id="' + id + '"]').val(
              variation_data.max_character[value][id]
            );
            jQuery('.total_font[data-id="' + id + '"]').html(
              variation_data.max_character[value][id]
            );
            jQuery('.font_value[data-id="' + id + '"]').attr(
              "maxlength",
              variation_data.max_character[value][id]
            );
          });
        }
      }
    );

    if (typeof resizable === "function") {
      jQuery("#imageWrapper")
        .resizable({
          aspectRatio: true,
        })
        .rotatable({
          stop: function (event, ui) {
            jQuery("#imageRotation").val(ui.angle.current);
          },
        })
        .draggable({
          //	containment: "parent",
          stop: function (event, ui) {
            //console.log(ui);
            jQuery("#imageLeft").val(ui.position.left);
            jQuery("#imageTop").val(ui.position.top);
          },
        });
    }

    jQuery("#imageWrapper").attr("data-value", 5);

    jQuery(document).on("click", ".rotate-left", function () {
      var ang = getCurrentRotationFixed("imageWrapper");

      ang = ang - 10;
      document.getElementById("imageWrapper").style.transform =
        "rotate(" + ang + "deg)";

      jQuery("#imageRotation").val(ang);
    });
    jQuery(document).on("click", ".rotate-right", function () {
      var ang = getCurrentRotationFixed("imageWrapper");

      ang = ang + 10;
      document.getElementById("imageWrapper").style.transform =
        "rotate(" + ang + "deg)";

      jQuery("#imageRotation").val(ang);
    });

    jQuery(document).on("click", ".mv-down", function () {
      var top = parseInt(document.getElementById("imageWrapper").style.top);
      if (isNaN(top)) {
        top = 0;
      }
      jQuery("#imageWrapper").css("top", top + 5 + "px");
    });
    jQuery(document).on("click", ".mv-up", function () {
      var top = parseInt(document.getElementById("imageWrapper").style.top);
      if (isNaN(top)) {
        top = 0;
      }
      jQuery("#imageWrapper").css("top", top - 5 + "px");
    });
    jQuery(document).on("click", ".mv-left", function () {
      var top = parseInt(document.getElementById("imageWrapper").style.left);
      if (isNaN(top)) {
        top = 0;
      }
      jQuery("#imageWrapper").css("left", top - 5 + "px");
    });
    jQuery(document).on("click", ".mv-right", function () {
      var top = parseInt(document.getElementById("imageWrapper").style.left);
      if (isNaN(top)) {
        top = 0;
      }
      jQuery("#imageWrapper").css("left", top + 5 + "px");
    });

    jQuery(document).on("click", ".zoomin", function () {
      var width = jQuery("#imageWrapper").width() - 10;

      var height = jQuery("#imageWrapper").height() - 10;

      jQuery("#imageWrapper").width(width + "px");
      jQuery("#imageWrapper").height(height + "px");
    });
    jQuery(document).on("click", ".zoomout", function () {
      var width = jQuery("#imageWrapper").width() + 10;
      var height = jQuery("#imageWrapper").height() + 10;
      jQuery("#imageWrapper").width(width + "px");
      jQuery("#imageWrapper").height(height + "px");
    });
  });
  jQuery(document).on("click", ".bcaddoncustomize", function () {
    if (jQuery(".image_variation_hidden").length) {
      jQuery(".bc-product-form__option-label").each(function () {
        if (jQuery(this).html() == "Design") {
          if (
            !jQuery(this)
              .parents(".bc-product-form__control")
              .find('input[type="radio"]:checked').length
          ) {
            alert("Please Select Design");
            return;
          } else {
            jQuery(".customizationpopup").bPopup({
              position: ["auto", "auto"],
              positionStyle: "fixed",
              onOpen: function () {
                jQuery('input[data-id="option-font-family"]').val(
                  jQuery("#engrave_fonts").val()
                );
                var engrave_wrap = jQuery(".print_wrap.engrave");
                engrave_wrap.attr("class", "");
                engrave_wrap.addClass("print_wrap");
                engrave_wrap.addClass("engrave");
                jQuery(".print_wrap.engrave").addClass(
                  jQuery("#engrave_fonts").val()
                );
              },
            });
          }
        }
      });
    } else {
      jQuery(".customizationpopup").bPopup({
        onOpen: function () {
          jQuery('input[data-id="option-font-family"]').val(
            jQuery("#engrave_fonts").val()
          );
          var engrave_wrap = jQuery(".print_wrap.engrave");
          engrave_wrap.attr("class", "");
          engrave_wrap.addClass("print_wrap");
          engrave_wrap.addClass("engrave");
          jQuery(".print_wrap").addClass(jQuery("#engrave_fonts").val());
        },
      });
    }
  });
  function getCurrentRotationFixed(elid) {
    var el = document.getElementById(elid);
    var st = window.getComputedStyle(el, null);
    var tr =
      st.getPropertyValue("-webkit-transform") ||
      st.getPropertyValue("-moz-transform") ||
      st.getPropertyValue("-ms-transform") ||
      st.getPropertyValue("-o-transform") ||
      st.getPropertyValue("transform") ||
      "fail...";

    if (tr !== "none") {
      //    console.log('Matrix: ' + tr);

      var values = tr.split("(")[1];
      values = values.split(")")[0];
      values = values.split(",");
      var a = values[0];
      var b = values[1];
      var c = values[2];
      var d = values[3];

      var scale = Math.sqrt(a * a + b * b);

      // First option, don't check for negative result
      // Second, check for the negative result
      /** /
        var radians = Math.atan2(b, a);
        var angle = Math.round( radians * (180/Math.PI));
        /*/
      var radians = Math.atan2(b, a);
      if (radians < 0) {
        radians += 2 * Math.PI;
      }
      var angle = Math.round(radians * (180 / Math.PI));
      /**/
    } else {
      var angle = 0;
    }

    // works!
    return angle;
  }

  jQuery(document).on("change", ".cnc_font_size", function () {
    var id = jQuery(this).attr("data-id");
    var value = jQuery(this).val();
    jQuery('input[data-id="fontsize' + id + '"]').val(value);
    jQuery('.font_frontend[data-id="' + id + '"]').css(
      "font-size",
      value + "px"
    );
  });

  jQuery(document).on("change", "#engrave_fonts", function () {
    value = jQuery(this).val();
    jQuery(".print_wrap").removeClass("timesnewroman");
    jQuery(".print_wrap").removeClass("zapfchancery");
    jQuery(".print_wrap").removeClass("vivaldi");
    jQuery(".print_wrap").removeClass("palacescript");
    jQuery(".print_wrap").removeClass("lobsterregular");
    jQuery(".print_wrap").removeClass("oldenglish");
    jQuery(".print_wrap").removeClass("harrington");
    jQuery(".print_wrap").removeClass("greatVibesregular");
    jQuery(".print_wrap").removeClass("frenchscript");
    jQuery(".print_wrap").removeClass("edwardianscript");
    jQuery(".print_wrap").removeClass("curlz");

    jQuery('input[data-id="option-font-family"]').val(value);

    jQuery("#engrave_fonts option").each(function () {
      //console.log(jQuery(this).attr('value'))
      jQuery(".print_wrap").removeClass(jQuery(this).attr("value"));
    });

    jQuery(".print_wrap").addClass(value);
  });
  jQuery(document).on("keyup change", ".font_value", function () {
    var value = jQuery(this).val();

    value = value.replace(
      /(\u00a9|\u00ae|[\u2000-\u3300]|\ud83c[\ud000-\udfff]|\ud83d[\ud000-\udfff]|\ud83e[\ud000-\udfff])/g,
      ""
    );
    jQuery(this).val(value);
  });
  jQuery(document).on("keyup change", ".font_value", function () {
    var id = jQuery(this).attr("data-id");
    var value = jQuery(this).val();

    value = value.replace(
      /(\u00a9|\u00ae|[\u2000-\u3300]|\ud83c[\ud000-\udfff]|\ud83d[\ud000-\udfff]|\ud83e[\ud000-\udfff])/g,
      ""
    );
    if (value == "") {
      jQuery('input[data-id="fontsize' + id + '"]').val("");
      jQuery('input[data-id="fontcolor' + id + '"]').val("");
    }

    jQuery('.font_frontend[data-id="' + id + '"] .cnc_font_actual').html(value);
    jQuery('.total_font[data-id="' + id + '"]').html(
      parseInt(jQuery('.font_value_remaining[data-id="' + id + '"]').val()) -
        parseInt(value.length)
    );

    jQuery('input[data-id="fonttext' + id + '"]').val(value);
    if (
      jQuery('input[name="enagrave_color_' + id + '"]:checked').val() !==
      undefined
    ) {
      jQuery('input[data-id="fontcolor' + id + '"]').val(
        jQuery('input[name="enagrave_color_' + id + '"]:checked').val()
      );
    }

    var product_type = jQuery("#cnc_product_type").val();

    if (product_type == "default") {
      if (!isNaN(jQuery("#cnc_font_size_" + id).val())) {
        jQuery('input[data-id="fontsize' + id + '"]').val(
          jQuery("#cnc_font_size_" + id).val()
        );
      }
    }
    if (product_type == "fixed") {
      if (!isNaN(jQuery("#cnc_font_size_" + id).val())) {
        jQuery('input[data-id="fontsize' + id + '"]').val(
          jQuery('.cnc_font_size[data-id="' + id + '"]').val()
        );
      }
    }

    if (product_type == "flexible") {
      var current_width = jQuery(
        '.font_frontend[data-id="' + id + '"] .cnc_font_actual'
      ).width();
      var actualt_width = jQuery(
        '.font_frontend[data-id="' + id + '"]'
      ).width();
      var font_size = jQuery("#cnc_current_font_size_" + id).val();
      var max_font_size = jQuery("#cnc_font_size_" + id).val();
      if (value != "") {
        jQuery('input[data-id="fontsize' + id + '"]').val(font_size);
      }

      if (current_width >= actualt_width - 10) {
        if (font_size <= 10) {
          return;
        }
        console.log(font_size);
        console.log(parseInt(font_size) - 5);
        if (font_size > 25) {
          var new_font_size = parseInt(font_size) - 5;
        } else {
          var new_font_size = parseInt(font_size) - 1;
        }

        jQuery('input[data-id="fontsize' + id + '"]').val(new_font_size);
        jQuery('.font_frontend[data-id="' + id + '"]').css(
          "font-size",
          new_font_size + "px"
        );
        if (!isNaN(new_font_size)) {
          jQuery("#cnc_current_font_size_" + id).val(new_font_size);
        }
      } else {
        if (font_size >= max_font_size) {
          return;
        }
        console.log(font_size);
        console.log(parseInt(font_size) + 5);
        if (font_size > 25) {
          var new_font_size = parseInt(font_size) + 5;
        } else {
          var new_font_size = parseInt(font_size) + 1;
        }

        jQuery('input[data-id="fontsize' + id + '"]').val(new_font_size);
        jQuery('.font_frontend[data-id="' + id + '"]').css(
          "font-size",
          new_font_size + "px"
        );

        if (!isNaN(new_font_size)) {
          jQuery("#cnc_current_font_size_" + id).val(new_font_size);
        }
      }
    }
  });

  jQuery(document).on("click", ".search-icon", function () {
    jQuery(".search-wrap").toggle();
  });
})(jQuery);
function makeScreenshot() {
  //   return new Promise((resolve, reject) => {
  //     let node = document.getElementById("screenshort-wrapper");

  //     html2canvas(node,
  //     {
  //          onrendered: (canvas) => {
  //          let pngUrl = canvas.toDataURL();
  //          resolve(pngUrl);

  //      }
  //     })
  //     //.then(function (canvas) {  console.log(canvas.toDataURL())   });
  //   });

  var promise = new Promise(function (resolve) {
    setTimeout(function () {
      resolve("result");
    }, 1000);
  });

  promise.then(
    function (result) {
      let node = document.getElementById("screenshort-wrapper");

      html2canvas(node, {
        onrendered: function (canvas) {
          let pngUrl = canvas.toDataURL();
          resolve(pngUrl);
        },
      });
    },
    function (error) {}
  );
}

function report() {
  // let screenshot = await makeScreenshot(); // png dataUrl
  var formData = new FormData();
  //  formData.append('formDatabase', screenshot);
  formData.append("originalUrl", jQuery("#frameurl").val());
  formData.append("originalWidth", jQuery("#originalWidth").val());
  formData.append("originalHeight", jQuery("#originalHeight").val());
  formData.append("userFile", jQuery("#uploadNewUrl").val());
  formData.append("userFileWidth", jQuery("#imgLogo").width());
  formData.append("userFileHeight", jQuery("#imgLogo").height());
  formData.append("_wp_nonce", jQuery("#pgs_apt_fileupload").val());

  //formData.append('imageLeft', parseInt(jQuery('#imageLeftBase').val()) + parseInt(jQuery('#imageLeft').val()));
  //formData.append('imageTop', parseInt( jQuery('#imageTopBase').val()) + parseInt(jQuery('#imageTop').val()));
  formData.append(
    "imageLeft",
    parseInt(jQuery("#imageLeftBase").val()) +
      parseInt(jQuery("#imageWrapper").position().left)
  );
  formData.append(
    "imageTop",
    parseInt(jQuery("#imageTopBase").val()) +
      parseInt(jQuery("#imageWrapper").position().top)
  );

  formData.append("angle", jQuery("#imageRotation").val());
  formData.append("action", 'cnc_b2b_fileuploaded');

  jQuery.ajax({
    url: cnc_b2b_fileuploaded.ajaxurl, 
    method: "POST",
    data: formData,
    contentType: false,
    processData: false,
    //Ajax events
    success: function (html) {
      jQuery("input#print_url").val(html);
      //   jQuery('.bc-product-form').submit();

      jQuery(".bcaddoncustomize")
        .addClass("greenbg")
        .html("Customization Confirmed");
      jQuery(".bc-product-single__top button.bc-btn--add_to_cart").attr(
        "disabled",
        false
      );
    },
  });
  closepopup();
}
function closepopup(isSubmit) {
  jQuery(".customizationpopup").bPopup().close();
  jQuery(".single_add_to_cart_button[name='add-to-cart']").removeAttr(
    "disabled"
  );
  jQuery(".bcaddoncustomize")
    .addClass("greenbg")
    .html("Customization Confirmed");
  console.log("490");
  jQuery(".bc-product-single__top button.bc-btn--add_to_cart").attr(
    "disabled",
    false
  );
  if (isSubmit) {
    //    jQuery('.bc-product-form').submit();
  }
  jQuery(".font-customisation-result").hide();
  jQuery('.font-customisation-result[data-id="font"]').show();
  jQuery('.font-customisation-result[data-id="font"] .width_70').html(
    jQuery("#engrave_fonts").val()
  );
  jQuery('.font-customisation-result[data-id="font"] .engrave_fonts').val(
    jQuery("#engrave_fonts").val()
  );
  jQuery(".notice-centeralised").show();
  for (m = 1; m <= 10; m++) {
    if (
      jQuery('.font_value[data-id="' + m + '"]').val() != "" &&
      jQuery('.font_value[data-id="' + m + '"]').val() !== undefined
    ) {
      jQuery('.font-customisation-result[data-id="' + m + '"]').show();
      jQuery('.font-customisation-result[data-id="' + m + '"] .width_70').html(
        jQuery('.font_value[data-id="' + m + '"]').val()
      );
      jQuery('.font-customisation-result[data-id="' + m + '"] .width_20').html(
        jQuery('.font_value[data-id="' + m + '"]').val().length +
          "/" +
          jQuery('.cnc_total_character[data-id="' + m + '"]').val()
      );
      jQuery(
        '.font-customisation-result[data-id="' + m + '"] .engrave_text_box'
      ).val(jQuery('.font_value[data-id="' + m + '"]').val());
    }
  }
  jQuery(".engrave_clipart").val(jQuery("#clipart").val());
  jQuery(".engrave_font_color").val(
    jQuery(".engrave_color_class_select").val()
  );
}
function downloadimage() {
  report();
  var container = document.getElementById("screenshort-wrapper"); // full page
}
function hasExtension(inputID, exts) {
  return new RegExp("(" + exts.join("|").replace(/\./g, "\\.") + ")$").test(
    inputID
  );
}
function readURL(input, where) {
  if (!hasExtension(input.files[0].name, [".jpg", ".gif", ".png", ".jpeg"])) {
    jQuery('input[name="userfileprint"]').val("");
    alert("Please select valid file. Allowed file formats are jpg, gif, png.");
    return false;
  }
  var formData = new FormData();
  formData.append("_wp_nonce", jQuery("#pgs_apt_fileupload").val());
  formData.append("formData", input.files[0]);
   formData.append("action", 'cnc_b2b_fileuploaded');
  jQuery.ajax({
    url: cnc_b2b_fileuploaded.ajaxurl, 
    method: "POST",
    data: formData,
    contentType: false,
    processData: false,
    //Ajax events
    success: function (html) {
      jQuery("#imgLogo").attr("src", "");
      jQuery("input#image_url").val(html);
      jQuery("#uploadNewUrl").val(html);

      jQuery("#imgLogo").attr("src", html);
      setTimeout(function () {
        //jQuery('#imageWrapper img').css('height',jQuery('#imageWrapper').height()+'px')
        //jQuery('#imageWrapper img').css('width',jQuery('#imageWrapper').width()+'px')
      }, 2000);
      jQuery(".remove_image").show();
      var reader = new FileReader();
      reader.onload = function (e) {
        jQuery("#imgLogo").attr("src", e.target.result);
      };
      reader.readAsDataURL(input.files[0]);

      jQuery("#imageWrapper").removeAttr("hidden");

      jQuery("#imageWrapper")
        .resizable({
          aspectRatio: true,
        })
        .rotatable({
          stop: function (event, ui) {
            jQuery("#imageRotation").val(ui.angle.current);
          },
        })
        .draggable({
          //	containment: "parent",
          stop: function (event, ui) {
            //console.log(ui);
            jQuery("#imageLeft").val(ui.position.left);
            jQuery("#imageTop").val(ui.position.top);
          },
        });
    },
  });
}

function remove_image() {
  jQuery("#imageWrapper img").attr("src", "");
  jQuery(".remove_image").hide();
  jQuery('input[name="userfileprint"]').val("");
}

jQuery(document).on("change", ".enagrave_color_class", function () {
  jQuery(".font_frontend").css("color", jQuery(this).val());
  jQuery('input[data-id="fontcolor' + jQuery(this).attr("data-id") + '"]').val(
    jQuery(this).val()
  );
});

jQuery(document).on("change", ".engrave_color_class_select", function () {
  console.log(jQuery(this).find("option:selected"));
  if (jQuery(this).hasClass("color_for_variation")) {
    jQuery(".engrave_main_image").attr(
      "src",
      jQuery(this).find("option:selected").attr("data-image")
    );
    jQuery(
      'input[data-id="fontcolor' +
        jQuery(this).find("option:selected").attr("data-id") +
        '"]'
    ).val(jQuery(this).val());

    if (jQuery(this).hasClass("override_font_color")) {
      jQuery(".font_frontend").css(
        "color",
        jQuery(this).find("option:selected").attr("data-color")
      );
    }
  } else {
    //if variable product
    if (jQuery(".bc-product-variant__label").length) {
      jQuery(".font_frontend").css(
        "color",
        jQuery(this).find("option:selected").attr("data-color")
      );
    } else {
      jQuery(".font_frontend.override_this").css(
        "color",
        jQuery(this).find("option:selected").attr("data-color")
      );
    }

    jQuery(
      'input[data-id="fontcolor' +
        jQuery(this).find("option:selected").attr("data-id") +
        '"]'
    ).val(jQuery(this).val());
  }
});

jQuery(document).on("change", ".bc-product-form__control input", function () {
  var value = jQuery(this).val();

  if (jQuery('.image_variation_hidden[data-id="' + value + '"]').length) {
    if (
      jQuery('.image_variation_hidden[data-id="' + value + '"]').val() != "" &&
      jQuery('.image_variation_hidden[data-id="' + value + '"]').val() !=
        undefined
    ) {
      console.log(
        jQuery('.image_variation_hidden[data-id="' + value + '"]').val()
      );
      jQuery(".print_wrap.engrave")
        .find(".engrave_main_image")
        .attr(
          "src",
          jQuery('.image_variation_hidden[data-id="' + value + '"]').val()
        );
    }
    var fonts_hidden = jQuery("#fonts_hidden").val();
    var variation_data = jQuery(".variation_data_hidden").val();
    variation_data = jQuery.parseJSON(variation_data);

    if (fonts_hidden) {
      fonts_hidden = jQuery.parseJSON(fonts_hidden);
      if (variation_data.fonts) {
        allowed_fonts = variation_data.fonts[value];
        if (allowed_fonts) {
          jQuery("#engrave_fonts").html("");
          jQuery.each(fonts_hidden, function (index, font_name) {
            if (Array.isArray(allowed_fonts)) {
              if (jQuery.inArray(index, allowed_fonts) > -1) {
                jQuery("#engrave_fonts").append(
                  '<option value="' + index + '">' + font_name + "</option>"
                );
              }
            } else {
              if (index == allowed_fonts) {
                jQuery("#engrave_fonts").append(
                  '<option value="' + index + '">' + font_name + "</option>"
                );
              }
            }
          });
        }
      }
    }
    jQuery("#engrave_fonts").trigger("change");

    if (
      jQuery(".variation_data_hidden").val() !== undefined &&
      jQuery.parseJSON(jQuery(".variation_data_hidden").val())
    ) {
      for (m = 1; m <= 10; m++) {
        if (m <= variation_data.no_of_customisation[value]) {
          jQuery(".show_hide" + m).show();
        } else {
          jQuery(".show_hide" + m).hide();
        }
      }
    }

    jQuery(".font_value").val("").trigger("change");
    jQuery(".font_frontend").each(function () {
      var $this = jQuery(this);

      var id = jQuery(this).attr("data-id");
      if (variation_data.font_color[value][id].indexOf("#") !== -1) {
        color = variation_data.font_color[value][id];
      } else {
        color = "#" + variation_data.font_color[value][id];
      }
      $this.css("color", color);
      $this.css("font-size", variation_data.font_size[value][id] + "px");
      $this.css("top", variation_data.position_top[value][id] + "px");
      $this.css("left", variation_data.position_left[value][id] + "px");
      $this.css("height", variation_data.engrave_height[value][id] + "px");
      $this.css("width", variation_data.engrave_width[value][id] + "px");
      jQuery("#cnc_font_size_" + id).val(variation_data.font_size[value][id]);
      $this.find(".cnc_font_actual").html("");
      jQuery(".font_value").val("");
      jQuery("#cnc_current_font_size_" + id).val(
        variation_data.font_size[value][id]
      );
      jQuery('.cnc_total_character[data-id="' + id + '"]').val(
        variation_data.max_character[value][id]
      );
      jQuery('.font_value_remaining[data-id="' + id + '"]').val(
        variation_data.max_character[value][id]
      );
      jQuery('.total_font[data-id="' + id + '"]').html(
        variation_data.max_character[value][id]
      );
      jQuery('.font_value[data-id="' + id + '"]').attr(
        "maxlength",
        variation_data.max_character[value][id]
      );
    });
  }
});

jQuery(document).on("change", "#clipart", function () {
  var clipart = jQuery(this).val();
  var clipart_selected = jQuery(this).find("option:selected");
  var height = jQuery(clipart_selected).attr("data-height");
  var width = jQuery(clipart_selected).attr("data-width");
  jQuery(".artwork").remove();
  jQuery('.font_frontend[data-id="1"] .cnc_font_actual').html("");
  jQuery('.font_frontend[data-id="2"] .cnc_font_actual').html("");
  if (clipart == "") {
    jQuery('.font_value[data-id="1"]')
      .val("")
      .trigger("change")
      .trigger("keyup")
      .attr("disabled", false);
    jQuery('.font_value[data-id="2"]')
      .val("")
      .trigger("change")
      .trigger("keyup")
      .attr("disabled", false);
    jQuery('[data-id="fonttext1"]').val("");
    jQuery('[data-id="fonttext2"]').val("");
  } else {
    jQuery('.font_frontend[data-id="1"]').append(
      "<img class='artwork' src='" +
        clipart_selected.attr("data-image") +
        "' />"
    );
    jQuery('.font_value[data-id="1"]')
      .val("")
      .trigger("change")
      .trigger("keyup")
      .attr("disabled", true);
    jQuery('.font_value[data-id="2"]')
      .val("")
      .trigger("change")
      .trigger("keyup")
      .attr("disabled", true);
    jQuery('[data-id="fonttext1"]').val(clipart);
    jQuery('[data-id="fonttext2"]').val("");
  }

  if (height && width) {
    jQuery(".artwork").attr(
      "style",
      "height:" +
        height +
        "px !important;width:" +
        width +
        "px !important;max-height:" +
        height +
        "px !important;"
    );
  }
});


