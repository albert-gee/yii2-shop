$(document).ready(function () {

    /*FIND WIDGET*/
    var widget = $('#input-tree');
    var currentCategoryId = widget.attr('data-current-category');

    autoOpen(currentCategoryId);

    // /*FIND TREE ITEMS WHICH MUST BE OPENED USING DATA ATTRIBUTE AND OPEN ITS*/
    // var openedTreeItem = $('[data-opened=true]');
    // autoOpen(openedTreeItem, currentCategoryId);


    /*OPEN ON CLICK*/
    widget.on('click', '.category-toggle.fa-plus', (function () {

        $(this).removeClass('fa-plus');
        $(this).addClass('fa-minus');
        var thisField= $(this).siblings('div');

        var categoryId = $(thisField).children('input').val();

        if ($(thisField).siblings('ul')) {
            $(thisField).siblings('ul').show(300);
        }


        // var id = element.id;
        // var aHeight = $($(element).parent().children('a')).height(); // This is 'a' tag height
        // var ul = $(element).parent().parent();
        // var level = $(ul).attr("data-level");

        // $.ajax({
        //     type: "GET",
        //     url: '/shop/category/get-categories',
        //     data: 'parentId=' + id + '&level=' + level + '&currentCategoryId=' + currentCategoryId,
        //
        //     success: function (data) {
        //         $(element).removeClass('fa-plus');
        //         $(element).addClass('fa-minus');
        //
        //         $(data).height($(data).children().length * aHeight * .7).slideDown(300).insertAfter($('#' + id));
        //     }
        // });

        // ul.attr('style', '');
    }));

    /*CLOSE ON CLICK*/
    widget.on('click', '.category-toggle.fa-minus', (function () {
        $(this).removeClass('fa-minus');
        $(this).addClass('fa-plus');

        var thisField= $(this).siblings('div');

        var categoryId = $(thisField).children('input').val();

        if ($(thisField).siblings('ul')) {
            $(thisField).siblings('ul').hide(300);
        }

        // var ul = $(element).nextAll();
        // $(ul).slideUp(300);
        //
        // setTimeout(function() {
        //     $(element).nextAll().remove()
        // }, 300);
    }));
});


function autoOpen(currentCategoryId) {

    var currentCategoryInput = $('input[value=' + currentCategoryId + ']');
    var currentCategoryUl = $(currentCategoryInput).parents(".input-tree-ul");
    currentCategoryUl.each(function(i) {
        $(currentCategoryUl[i]).show();

        var button = $(currentCategoryUl[i]).siblings('p');
        $(button).removeClass('fa-plus');
        $(button).addClass('fa-minus');
    });


    // if (currentCategoryId == categoryId) {
    //
    //     if ($(thisField).siblings('ul')) {
    //         $(thisField).siblings('ul').show();
    //     }
    // }

    // if (openedTreeItem) {
    //     openedTreeItem.each(function() {
    //         var id = this.id;
    //         var ul = $(this).parent().parent();
    //         var level = $(ul).attr("data-level");
    //
    //         $(this).removeClass('fa-toggle-down');
    //         $(this).addClass('fa-toggle-up');
    //
    //         $.ajax({
    //             type: "GET",
    //             url: '/shop/category/get-categories',
    //             data: 'parentId=' + id + '&level=' + level + '&currentCategoryId=' + currentCategoryId,
    //
    //             success: function (data) {
    //
    //                 $(data).slideDown(300).insertAfter($('#' + id));
    //                 level++;
    //                 autoOpen($('[data-level=' + level + '] ' + '[data-opened=true]'), currentCategoryId);
    //             }
    //         });
    //     });
    // }
}