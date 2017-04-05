/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * This script adds animation for Pjax loading.
 */

jQuery(document).ready(function () {

    var loader = '<div id="pjaxLoader"><div class="spinner"></div></div>';

    $('body').append(loader);
    $('#pjaxLoader').css({
        display:"none",
        position: "fixed",
        width: "100%",
        height: "100%",
        background: "rgba(0, 0, 0, .2)",
        top: "0"
    });

    jQuery(document).on("pjax:start", function () {
        $('#pjaxLoader').show();
    }).on("pjax:end", function () {
        $('#pjaxLoader').hide();
    });

});

