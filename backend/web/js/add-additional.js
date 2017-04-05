$(document).ready(
    function() {
        var additionalProductSelector = $('#additional-product-selector');
        var productId = additionalProductSelector.data('product-id');

        $(additionalProductSelector).change(function() {
            if (additionalProductSelector.val()) {
                $.ajax({
                    type: "GET",
                    url: "/admin/shop/additional-product/add-to-additional-products",
                    data: {
                        'productId': productId,
                        'additionalProductId': additionalProductSelector.val()
                    },
                    success: function (data) {

                        var additionalProductsTable = $('#additional-products-table');

                        var tr = '<tr><td>' +
                            $('#additional-product-selector option:selected').text() +
                            '</td><td><a href="/admin/shop/additional-product/remove-additional-product?id=' +
                            data + '"' +
                            'class="btn btn-danger btn-xs">' +
                            '<span class="glyphicon glyphicon-remove remove-additional-product"></span></a>' +
                            '</td></tr>';

                        $(tr).appendTo($(additionalProductsTable));
                    },
                    error: function() {
                        alert('Such additional product has already added.');
                    }
                });
            }
        });

        var removeAdditionalProductButton = $('.remove-additional-product');
        $(removeAdditionalProductButton).click(function (e) {
            e.preventDefault();
            var button = $(this);
            var url = $(this).attr("href");

            $.ajax({
                type: "GET",
                url: url,
                success: function (data) {
                    $(button).closest('tr').remove();
                },
                error: function() {
                    alert('Error.');
                }
            });
        });
    }
);