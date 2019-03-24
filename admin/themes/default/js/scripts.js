/// <reference path="../../../../node_modules/@types/jquery/index.d.ts"/>

jQuery(document).ready(function () {
    $('#navigation li.active').parents('li.parent').addClass('opened');
    $('button[data-dismiss="alert"]').on('click', alertDissmis);
    $('.datepicker-date').datepicker({
        format: 'yyyy-mm-dd'
    });
    $('.panel-hide-toggle').on('click', hidePanel)

    function hidePanel(event) {
        var target = event.currentTarget;
        var parent = $(target).parents(':eq(2)');
        parent.toggleClass('collapsed');
    }
});