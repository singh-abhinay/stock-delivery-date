require([
    'jquery',
    'underscore'
], function (jQuery, _) {

    jQuery(".product-options-wrapper div").click(function () {
        checkProductOption();
    });

    function checkProductOption() {
        var selected_options = {};
        jQuery('div.swatch-attribute').each(function (k, v) {
            var attribute_id = jQuery(v).data('attribute-id');
            var option_selected = jQuery(v).attr('data-option-selected');
            if (!attribute_id || !option_selected) {
                return;
            }
            selected_options[attribute_id] = option_selected;
        });
        var product_id_index = jQuery('[data-role=swatch-options]').data('mage-SwatchRenderer').options.jsonConfig.index;
        var found_ids = [];
        jQuery.each(product_id_index, function (product_id, attributes) {
            var productIsSelected = function (attributes, selected_options) {
                return _.isEqual(attributes, selected_options);
            }
            if (productIsSelected(attributes, selected_options)) {
                found_ids.push(product_id);
            }
        });
        if (found_ids.length) {
            var selected_product_id = found_ids[0];

            var baseUrl = jQuery('#stock-delivery-date').attr("data-url");
            var customUrl = "stockdelivery/stock/index";
            var url = baseUrl + customUrl;
            jQuery.ajax({
                type: "POST",
                url: url,
                data: {id: selected_product_id},
                success: function (data) {
                    if (data.response != 'false') {
                        jQuery('.delivery-date').empty();
                        jQuery('.delivery-date').text(data.date);
                        jQuery('.estimated-delivery-msg').css('display', 'block');
                    } else {
                        jQuery('.estimated-delivery-msg').css('display', 'none');
                    }
                },
                error: function (request, status, error) {
                    console.log('Something went wrong on while fetching stock delivery date');
                }
            });
        }
    }
});