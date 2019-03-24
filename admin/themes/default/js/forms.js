/// <reference path="../../../../node_modules/@types/jquery/index.d.ts"/>

jQuery(document).ready(function () {
    $('form#search-form .search-selector-list li a').on('click', pickSearchCategory);

    function pickSearchCategory(event) {
        event.preventDefault();
        var search_category = $(this).attr('href');
        var hidden_selector = $('form#search-form').find('.search-selector-input');
        var search_button = $('form#search-form ').find('.search-selector-text');
        search_button.text(search_category);
        hidden_selector.val(search_category);
    }
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
    $('.admin-form .btn-switch button').on('click', switchButtonAction);

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
    $('.admin-form table tbody tr').on('click', tableRowClick);

    function tableRowClick(event) {
        var $this = $(event.currentTarget);
        var checkbox = $this.find('input[type="checkbox"]');
        if (checkbox) {
            $(checkbox).click();
            if ($(checkbox).is(':checked')) {
                $this.addClass('selected-row');
            } else {
                $this.removeClass('selected-row');
            }
        }
    }

    $('.admin-form table thead tr th .sort').on('click', sortChange);

    function sortChange(event) {
        $this = $(this);
        if ($this.hasClass('asc')) {
            $this.removeClass('asc').addClass('desc');
        } else if ($this.hasClass('desc')) {
            $this.removeClass('desc').addClass('asc')
        } else {
            $this.addClass('asc');
        }
    }

    $('.admin-form .change-password-button').on('click', toggleChangePassword);

    function toggleChangePassword(event) {
        var $this = event.currentTarget;
        var box = $($this).siblings('.change-password-container');
        var hidden_input = $($this).siblings('.change-password');
        if (box.is(':hidden')) {
            box.show();
            hidden_input.val(1);
        } else {
            box.hide();
            hidden_input.val(0);
        }
    }

    $('.admin-form .change-password-container .input-group-addon').on('click', showPassword)

    function showPassword(event) {
        var $this = $(event.currentTarget);
        var password_input = $this.siblings('input');
        if (password_input[0].getAttribute('type') == 'password') {
            password_input[0].setAttribute('type', 'text');
        } else {
            password_input[0].setAttribute('type', 'password');
        }
    }
});