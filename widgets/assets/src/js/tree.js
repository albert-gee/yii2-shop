$(document).ready(function () {
    if (typeof downIconClass !== 'undefined' && typeof upIconClass !== 'undefined') {
        downIconClass = downIconClass.replace(/ /g,".");
        upIconClass = upIconClass.replace(/ /g,".");

        /*FIND WIDGET*/
        var widget = $('#widget-menu');
        var languageId = widget.data('language');
        var currentCategoryId = widget.attr('data-current-category-id');
        var isGrid = widget.attr('data-is-grid');

        /*FIND TREE ITEMS WHICH MUST BE OPENED USING DATA ATTRIBUTE AND OPEN ITS*/
        var openedTreeItem = $('[data-opened=true]');
        autoOpen(openedTreeItem, currentCategoryId);


        /*OPEN ON CLICK*/
        widget.on('click', '.category-toggle.' + downIconClass, (function () {

            var element = this;
            var id = $(element).attr('id');
            var ul = $(element).closest('ul');
            var li = $(element).closest('li');
            var level = $(ul).attr("data-level");

            var url = appName ? appName + '/shop/category/get-categories' :
                '/shop/category/get-categories';
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    'parentId': id,
                    'level': level,
                    'languageId': languageId,
                    'currentCategoryId': currentCategoryId,
                    'isGrid': isGrid,
                    'downIconClass': downIconClass.replace('.', ' '),
                    'upIconClass': upIconClass.replace('.', ' ')
                },

                success: function (data) {
                    var downClasses = downIconClass.split('.');
                    var upClasses = upIconClass.split('.');
                    for (var i = 0; i < downClasses.length; i++) {
                        $(element).removeClass(downClasses[i]);
                        $(element).addClass(upClasses[i]);
                    }
                    var color = 255 - level * (10 - level * 2);
                    $(data).height('100%')
                        .css({
                            'background-color': 'rgb(' + color + ',  ' + color + ', ' + color + ')',
                            'border-left-width': 10,
                            'border-left-color': 'rgb(235, 235, 235)',
                            'border-left-style': 'solid'
                        })
                        .slideDown(300).appendTo($(li));
                }
            });
        }));

        /*CLOSE ON CLICK*/
        widget.on('click', '.category-toggle.' + upIconClass, (function () {
            var element = this;

            var downClasses = downIconClass.split('.');
            var upClasses = upIconClass.split('.');
            for (var i = 0; i < downClasses.length; i++) {
                $(element).removeClass(upClasses[i]);
                $(element).addClass(downClasses[i]);
            }

            var li = $(element).closest('li');

            var ul = (isGrid) ? li.children('ul') : $(element).nextAll();
            $(ul).slideUp(300);

            setTimeout(function() {
                $(element).nextAll().remove()
            }, 300);
        }));
    }
});


function autoOpen(openedTreeItem, currentCategoryId) {
    if (openedTreeItem) {
        openedTreeItem.each(function() {
            var id = this.id;
            var ul = $(this).closest('ul');
            var li = $(this).closest('li');
            var level = $(ul).attr("data-level");
            var widget = $('#widget-menu');
            var isGrid = widget.attr('data-is-grid');
            var languagePrefix = widget.data('language');

            $(this).removeClass(downIconClass);
            $(this).addClass(upIconClass);

            var url = appName ? appName + '/shop/category/get-categories' :
                '/shop/category/get-categories';

            $.ajax({
                type: "GET",
                url: url,

                data: {
                    'parentId': id,
                    'level': level,
                    'currentCategoryId': currentCategoryId,
                    'isGrid': isGrid,
                    'downIconClass': downIconClass.replace('.', ' '),
                    'upIconClass': upIconClass.replace('.', ' ')
                },

                success: function (data) {
                    var color = 255 - level * 7;

                    $(data).height('100%')
                        .css({
                            'background-color': 'rgb(' + color + ',  ' + color + ', ' + color + ')',
                            'border-left-width': 10,
                            'border-left-color': 'rgb(235, 235, 235)',
                            'border-left-style': 'solid'
                        })
                        .slideDown(300).appendTo($(li));

                    level++;
                    autoOpen($('[data-level=' + level + '] ' + '[data-opened=true]'), currentCategoryId);
                }
            });
        });
    }
}