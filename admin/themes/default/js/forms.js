/// <reference path="../../../../node_modules/@types/jquery/index.d.ts"/>

jQuery(document).ready(function () {
    $('form[method=get]').submit(function (e) {
        var form_method = $(this).attr('method');
        var form_action = $(this).attr('action');
        var form_serialize = $(this).serialize();
        if (form_method.toLocaleLowerCase() == 'get' && form_action.length > 0) {
            var location = form_action + '&' + form_serialize;
            window.location.href = location;
        }
        return false;
    });
    $('.btn-switch button').on('click', switchButtonAction);
    function switchButtonAction(event) {
        var parent = $(this).parent('.btn-switch');
        if (parent.hasClass('switch-off')) {
            $(this).siblings('.btn-switch-input').val(1);
            parent.removeClass('switch-off').addClass('switch-on');
        } else if (parent.hasClass('switch-on')) {
            $(this).siblings('.btn-switch-input').val(0);
            parent.removeClass('switch-on').addClass('switch-off');
        }
    }
});