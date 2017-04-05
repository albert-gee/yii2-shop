$(document).ready(function () {

    $('.quantity').change(function () {
        var widget = this.closest('.product-prices-widget');
        var newPriceTag = $(widget).find('#newPrice');
        var countInput = $(widget).find('#cartform-count');
        var currencyCode = $(newPriceTag).data('currency-code');

        countInput.change(function () {
            if (countInput.length) {
                var oneItemPrice = $(newPriceTag).attr('data-sum');
                var number = countInput.val();
                var newPrice = (oneItemPrice * number).toLocaleString();
                newPriceTag.text(newPrice + ' ' + currencyCode);
            }
        });
    });

    $('.combinations-values').change(function () {
        var productId = $(this).data('product-id');
        var thisWidget = $(this).closest('.product-prices-widget');
        var countInput = $(thisWidget).find('#cartform-count');
        var notAvailableText = $(thisWidget).data('not-available-text');
        var addToCartButton = $(thisWidget).find('#add-to-cart-button');
        var oldPriceTag = $(this).find('#oldPrice');
        var productImage = $('#main-image');
        var checkedValues = $(this).find('input:checked');
        var selectedValues = $(this).find('option:selected');
        var priceWrapHeight = $(thisWidget).find('.prices-wrapp').innerHeight();
        var newPriceTag = $(this).find('#newPrice');
        var currencyCode = $(newPriceTag).data('currency-code');
        var values = [];
        for (var i = 0; i < checkedValues.length; i++) {
            values[i] = checkedValues[i].value;
        }
        for (var j = 0; j < selectedValues.length; j++) {
            values[checkedValues.length + j] = $(selectedValues[j]).val();
        }

        /**
         * Adds "active" class for selected element.
         */
        $(this).find('.active').removeClass('active');
        $(this).addClass('active');

        var combinationBlockInputsNumber = $(this).find('div.form-group').length;
        if (values.length == combinationBlockInputsNumber) {
            values = JSON.stringify(values);
            $.ajax({
                type: "GET",
                url: '/shop/product/get-product-combination',
                data: {
                    values: values,
                    productId: productId
                },
                ajaxSend: showLoader(newPriceTag, oldPriceTag, priceWrapHeight),
                complete: function () {
                    setTimeout(hideLoader, 2000);
                },

                success: function (data) {
                    setTimeout(
                        function () {
                            data = JSON.parse(data);
                            if (data) $(addToCartButton).removeAttr('disabled');
                            else $(addToCartButton).attr('disabled', 'disabled');

                            var oldPrice = (data.oldPrice) ? data.oldPrice.toLocaleString() + ' ' + currencyCode : '';
                            if (data.newPrice) {
                                var newPrice = (data.newPrice * countInput.val()).toLocaleString() + ' ' + currencyCode;
                                var dataSum = data.newPrice;
                            }
                            else {
                                newPrice = dataSum = '';
                            }

                            if (!data.oldPrice && !data.newPrice) {
                                newPrice = notAvailableText;
                                $(thisWidget).find('button[type="submit"]').prop('disabled', true);
                            }
                            else {
                                $(thisWidget).find('button[type="submit"]').prop('disabled', false);
                            }

                            oldPriceTag.text(oldPrice);
                            newPriceTag.fadeOut(125).text(newPrice).fadeIn(125);
                            newPriceTag.attr('data-sum', dataSum);

                            if (data.image) productImage.fadeOut(125).attr('src', data.image).fadeIn(125);
                            $('img.zoomImg').attr('src', data.image);

                            var skuText = (data.sku) ? data.sku : notAvailableText;
                            $('#sku').text(skuText);
                        }, 1500
                    );
                },
                error: function (data) {
                    $(addToCartButton).attr('disabled', 'disabled');
                    oldPriceTag.text();
                    newPriceTag.text(notAvailableText);
                }
            });
        }
    });
});

function showLoader(newPriceTag, oldPriceTag, priceWrapHeight) {
    var loader = '<div id="floatBarsG">';

    for (var i = 1; i < 9; i++) {
        loader += "<div id='floatBarsG_" + i + "' class='floatBarsG'></div>";
    }
    loader += '</div>';
    $(newPriceTag).html(loader);
    $(oldPriceTag).text('');

    $('#floatBarsG').css('height', priceWrapHeight);
}
function hideLoader() {
    $('floatBarsG').remove();
}