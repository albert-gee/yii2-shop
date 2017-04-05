/*This script removes empty get params*/

$("div.products").on("click", "button.filter-button", function(e) {
    e.preventDefault();
    $('input').each(function(){
        if ($(this).val() == '')
            $(this).attr('disabled', true);
    });
    $('option').each(function(){
        if ($(this).val() == '')
            $(this).attr('disabled', true);
    });
    $('form').submit();
});