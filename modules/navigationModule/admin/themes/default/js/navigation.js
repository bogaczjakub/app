/// <reference path="../../../../../../node_modules/@types/jquery/JQuery.d.ts"/>

jQuery(document).ready(function () {
    $('#navigation .nav li a > .caret-container').on('click', categoryLinkClick);

    function categoryLinkClick(event) {
        event.preventDefault();
        var parent = $(event.currentTarget).parent().parent();
        if (parent.hasClass('parent')) {
            event.preventDefault();
            parent.toggleClass('opened');
        }
    }
});