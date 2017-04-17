/*This script gets delivery method info used CartController by Blcms-Shop module*/

$(document).ready(function () {

    /*AFTER PAGE LOAD ELEMENT SETTINGS*/
    var radioButtons = $('#payment-selector input[type="radio"]');
    $(radioButtons[0]).attr("checked", "checked");

    getPaymentMethodInfo($(radioButtons[0]).val());

    /*SELECTED ELEMENT SETTINGS*/
    var inputs = $('#payment-selector input[type="radio"]');
    inputs.change(function () {
        if (this.checked) {
            var elementValue = this.value;
            getPaymentMethodInfo(elementValue);
        }
    });
});

/*GETTING METHOD INFO BY IT VALUE*/
function getPaymentMethodInfo(elementValue) {
    var languagePrefix = $('#payment-selector').data('language-prefix');

    $.ajax({
        type: "GET",
        url: languagePrefix + '/shop/payment/get-payment-method-info',
        data: {
            'id': elementValue
        },
        success: function (data) {

            var paymentMethod = $.parseJSON(data).paymentMethod;

            /*Title settings*/
            var title = $('#payment-title');
            $(title).text(paymentMethod['translation']['title']);

            /*Description setting*/
            var description = $('#payment-description');
            $(description).text(paymentMethod['translation']['description']);

            /*Image settings*/
            var image = $('#payment-image');
            $(image).attr('src', paymentMethod['image']);
        }
    });
}