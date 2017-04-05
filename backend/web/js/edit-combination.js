/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * Gets attribute values by attribute id
 */

$(document).ready(function () {
    getAttributeValues();

    var addButton = $('#add-attribute-value');
    addButton.click(function (e) {
        e.preventDefault();
        addToInputs();
    });
});

/*GETS ATTRIBUTE VALUES BY AJAX WHEN OPTION HAS SELECTED*/
function getAttributeValues() {

    $('#productcombinationattribute').change(function () {
        var selectedAttributeId = $("#productcombinationattribute option:selected").attr('value');

        $.ajax({
            type: "GET",
            url: "/admin/shop/attribute/get-attribute-values",
            data: {
                'attributeId': selectedAttributeId
            },
            success: function (result) {
                var attributeValues = JSON.parse(result);

                $('#productcombinationvalue').children().remove();

                $.each(attributeValues, function (i, value) {
                    var label = $('<label>');
                    var radioBtn = $('<label class="btn btn-default">' +
                        '<input type="radio" name="value" value="' + value['id'] + '">' +
                        '<div>' + value['translation']['value'] + '</div>' +
                        '</label>');
                    radioBtn.appendTo('#productcombinationvalue');

                });
            },
            error: function (data) {
                console.log(data);
                alert('You have not permissions to get attribute values.')
            }
        });

    });
}

/*ADDS ATTRIBUTE ID AND VALUE TO HIDDEN INPUTS AND CREATES NEW HIDDEN INPUTS*/
function addToInputs() {
    var attributeInput = $('.hidden-attribute').last();
    var valueInput = $('.hidden-value').last();

    var selectedAttribute = $("#productcombinationattribute option:selected");
    var selectedValue = $("#productcombinationvalue input:checked");

    var selectedAttributeId = $(selectedAttribute).attr('value');
    var selectedValueId = $(selectedValue).attr('value');

    if (selectedAttributeId && selectedValueId) {

        $(attributeInput).clone().appendTo("#attribute-inputs");
        $(valueInput).clone().appendTo("#value-inputs");

        var key = $('.hidden-attribute').length - 1;
        $(attributeInput[attributeInput.length - 1]).val(selectedAttributeId).attr('data-key', key);
        $(valueInput[valueInput.length - 1]).val(selectedValueId).attr('data-key', key);

        addToTable(
            $(selectedAttribute).text(),
            $(selectedValue).next()[0],
            key
        );
    }
    else {
        alert('You must fill in all fields');
    }

    if (selectedAttributeId && selectedValueId) {
        attributeInput.val(selectedAttributeId);
        valueInput.val(selectedValueId);
    }
}

function addToTable(attributeTdText, valueTdText, removeTdText) {
    var lastTr = $('#attributes-list tr').last();
    var attributeTd = $(lastTr).children()[0];
    var valueTd = $(lastTr).children()[1];

    var removeButton = $($(lastTr).children()[2]).children();
    $(removeButton).attr('data-key', removeTdText);
    $(removeButton).click(function () {
        var key = $(this).attr('data-key');

        $('input[data-key="' + (key) + '"]').remove();

        $(this).parent().parent().remove();
    });

    $(lastTr).show();
    lastTr.clone().appendTo('#attributes-list tbody').hide();

    $(attributeTd).text(attributeTdText);
    $(valueTdText).clone().appendTo(valueTd);

}

