$(document).ready(
    function() {

        var widget = $('#widget-menu');
        widget.on('click', '.show-category', (function (e) {
            e.preventDefault();
            var switcher = $(this);
            var url = $(this).attr("href");

            $.ajax({
                type: "GET",
                url: url,
                success: function (data) {
                    var i = $(switcher).children('i');
                    if (data) {
                        $(i).removeClass('glyphicon-minus text-danger');
                        $(i).addClass('glyphicon-ok text-primary');
                    }
                    else {
                        $(i).addClass('glyphicon-minus text-danger');
                        $(i).removeClass('glyphicon-ok text-primary');
                    }


                }
            });
        }));

        widget.on('click', '.change-category-position', (function (e) {
            e.preventDefault();
            var switcher = $(this);
            var url = $(this).attr("href");

            $.ajax({
                type: "GET",
                url: url,
                success: function (data) {
                    switch (data) {
                        case 'up':
                            var prevElement = $(switcher).closest('li').prev();
                            prevElement.find('.position-index').text(
                                Number(prevElement.find('.position-index').text()) + 1
                            );
                            $(switcher).next().text(
                                Number($(switcher).next().text()) - 1
                            );
                            $(switcher).closest('li').insertBefore($(switcher).closest('li').prev());
                            break;
                        case 'down':
                            var nextElement = $(switcher).closest('li').next();
                            nextElement.find('.position-index').text(
                                Number(nextElement.find('.position-index').text()) - 1
                            );
                            $(switcher).prev().text(
                                Number($(switcher).prev().text()) + 1
                            );
                            $(switcher).closest('li').insertAfter(nextElement);
                            break;
                    }

                }
            });
        }));

        widget.on('click', '.glyphicon-remove', (function (e) {
            e.preventDefault();

            var isDelete = confirm('Are you sure you want to delete this category');

            if (isDelete) {

                var switcher = $(this);
                var url = $(this).parent().attr("href");

                $.ajax({
                    type: "GET",
                    url: url,
                    success: function (data) {
                        if (data) {
                            $(switcher).closest('li').remove();
                        }
                    }
                });
            }
        }));
    }
);