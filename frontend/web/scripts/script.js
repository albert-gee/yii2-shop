$(document).ready(function() {



    var count = $('#count');
    var total_price = null;
    var tara = null;
    var table = $('.price-table tr');
    function findChecked() {
        table.each(function () {
            if ($(this).find('td:first-child input').is(':checked')) {
                total_price = $(this).find('td:last-child span').html();
                tara = $.trim($(this).find('td:nth-child(2)').html());
            }
        });
    }
    findChecked();
    $('input[name="AddToCartModel[price_id]"]').change(function() {
        findChecked();
        $('span.price-elem').html(total_price * count.val());
    });
    count.keyup(function() {
        var total_price2 = total_price * count.val();
        $('span.price-elem').html(total_price2);
    });

    $('#cart_btn').click(function(e) {
        var id = $(this).attr('data-id');
        var price = parseInt($('span.price-elem').html());
        var count = parseInt($('#count').val());

        $.ajax({
            type: 'POST',
            url: '/shop/cart/add-to-cart',
            data: {
                product_id: id,
                price: price,
                count: count
            },
            success: function(data) {
                alert(data);
            }
        });
        // window.location.href = '/shop/cart/add-to-cart?product_id='+id+'&price='+price+'&count='+count+'&tara='+tara;
        e.preventDefault();
    })

    $('#order-btn').click(function(e) {
        $(this).addClass('hide');
        $('#order-form').fadeIn();
        e.preventDefault();
    })


    $('.header .menu-icon').click(function() {
        $('.header nav').fadeToggle("slow");
    })


    function circleResize() {
        var circleWidth = $('.shop-services .circle').width();
        $('.shop-services .circle').height(circleWidth);
    }
    function circleNormal() {
        $('.shop-services .circle').removeAttr('style');
    }

    $(window).resize(function() {
        if ($(window).width() <= (390 - 17)) {
            circleResize();
        }
        else {
            circleNormal();
        }
    });
    if ($(window).width() <= (390 - 17)) {
        circleResize();
    }
    else {
        circleNormal();
    }



    $('.social .siteheart-button').click(function(e) {
        e.preventDefault();
        $('#sh_button').trigger('click');
    });


//    ===== Inside Pages ==========================

    $('.help .column table a.article-title').mouseover(function() {
        $('.help .column table .circle').removeClass('bg');
        $(this).parent().parent().find('.circle').addClass('bg');
        $(this).parent().next().find('a.article-title').addClass('hover');
    })
    $('.help .column table a.article-title').mouseout(function() {
        $('.help .column table .circle').removeClass('bg');
        $(this).parent().next().find('a.article-title').removeClass('hover');
    })

});