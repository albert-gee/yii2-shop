/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * This script adds animation for Ajax loading.
 */

jQuery(document).ready(function () {

    var loader = '<div id="ajaxLoader"><div class="spinner"></div></div>';

    $('body').append(loader);
    $('#ajaxLoader').css({
        display: "none",
        position: "fixed",
        width: "100%",
        height: "100%",
        background: "rgba(0, 0, 0, .2)",
        top: "0"
    });

    jQuery(document)
        .ajaxStart(function () {
            $('#ajaxLoader').show();
        })
        .ajaxStop(function () {
            setTimeout(function () {
                $('#ajaxLoader').hide()
            }, 700)})
});

