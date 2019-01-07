/// <reference path="../../../../../../node_modules/@types/jquery/JQuery.d.ts"/>

jQuery(document).ready(function () {

    $('#navigation .nav li a').click(function (e) {
        
        var parent = $(this).parent();
        if (parent.hasClass('parent')) {
            e.preventDefault();
            parent.toggleClass('opened');            
        }
    });

});