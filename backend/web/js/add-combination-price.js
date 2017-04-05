/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 */

$(document).ready(function () {

    var attributeListTableBody = $('table#attributes-list tbody');
    var lastPriceInputs = attributeListTableBody.find('tr.prices-inputs').last();

    var userGroupSelect = $('#price-user_group_id option:selected');

    /**
     * Adding fields
     */
    var addMoreButton = $('.add-fields');
    addMoreButton.on('click', function () {
        var clone = $(lastPriceInputs).clone();
        clone.find('input').each(function () {
            $(this).val('');
        });
        clone.appendTo(attributeListTableBody);
    });

    /**
     * Reset fields
     */
    attributeListTableBody.on('click', '.clear-prices-tr', function () {
        var clearPricesTr = $(this).closest('tr');
        clearPricesTr.find('input').each(function () {
            $(this).val('');
        });
        clearPricesTr.find('select').each(function () {
            $(this).val('');
        });
    });


    /**
     * Removing fields
     */
    attributeListTableBody.on('click', '.remove-prices-tr', function () {
        $(this).closest('tr').remove();
    });
});

