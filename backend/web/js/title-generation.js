/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * This script generates products seo-url from title.
 */

$(document).ready(function () {

    var $seoUrlBtn = $("button#generate-seo-url");

    $seoUrlBtn.click(function () {
        var title = $("#producttranslation-title").val();
        $.ajax({
            type: "GET",
            url: $seoUrlBtn.attr('url'),
            data: {'title':title},
            success: function (result) {
                $("#producttranslation-seourl").val(result);
            }
        });
    });

});
