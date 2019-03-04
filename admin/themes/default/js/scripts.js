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
    $('#navigation li.active').parents('li.parent').addClass('opened');
    $('button[data-dismiss="alert"]').on('click', function (e) {
        $.ajax({
            
        });
    });
});