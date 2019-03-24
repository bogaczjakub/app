/// <reference path="../../../../node_modules/@types/jquery/index.d.ts"/>

function alertDissmis(event) {
    $.ajax({
        method: 'GET',
        url: window.location.origin,
        data: {
            ajax: true,
            class: 'Alerts',
            action: 'alertDissmis',
            id: $(event.currentTarget).data('alert-id')
        }
    }).done(function (message) {
        console.log(message);
    })
}