/// <reference path="../../../../node_modules/@types/jquery/index.d.ts"/>

jQuery(document).ready(function () {
    $('input[data-input-validation]').on('keyup blur', inputValidation);

    function inputValidation(event) {
        var input = $(event.currentTarget);
        if (input) {
            var input_value = input.val();
            var input_type = input.data('input-validation').charAt(0).toUpperCase() + input.data('input-validation').slice(1);
            var function_name = 'validate' + input_type;
            validationPostAction(validation[function_name](input_value, input_type), input);
        }
    }

    function validationPostAction(result, input) {
        if (result.status) {
            input.removeClass('validation_failed')
            input.addClass('validation_success');
            if (result.alert.length == 0 && input.siblings('.validation-alert')) {
                alert = input.siblings('.validation-alert');
                alert.remove();
            }
        } else {
            input.removeClass('validation_success');
            input.addClass('validation_failed');
            if (result.alert.length > 0 && !validation.hasValidationAlert(input, result)) {
                if (result.type !== 'Date') {
                    input.after(result.alert);
                } else {
                    input.parent().after(result.alert);
                }
            }
        }
    }

    var validation = {
        validateVarchar: function (value, type) {
            var pattern = /[A-za-z0-9]{1,32}/;
            return {
                status: pattern.test(value) ? true : false,
                alert: pattern.test(value) ? '' : this.buildValidationAlert(this.varchar_alert),
                type: type
            };
        },

        validateInt: function (value, type) {
            var pattern = /^\d{1,}$/;
            return {
                status: pattern.test(value) ? true : false,
                alert: pattern.test(value) ? '' : this.buildValidationAlert(this.int_alert),
                type: type
            };
        },

        validateDate: function (value, type) {
            console.log(value);
            var pattern = /^\d{4}-^\d{2}-^\d{2}$/;
            return {
                status: pattern.test(value) ? true : false,
                alert: pattern.test(value) ? '' : this.buildValidationAlert(this.date_alert),
                type: type
            };
        },

        buildValidationAlert: function (alert_message) {
            if (alert_message.length > 0) {
                return '<div class="alert alert-danger validation-alert" role="alert"><span class="glyphicon glyphicon-info-sign"></span>' + alert_message + '</div>';
            }
        },

        hasValidationAlert: function (input, result) {
            if (result.type !== 'Date') {
                parent = input.parent();
            } else {
                parent = input.parent().parent();
            }
            find_alert = parent.find('.validation-alert');
            return find_alert.length > 0 ? true : false;
        },

        varchar_alert: 'String must be between 1 - 32 characters long.',
        int_alert: 'Field accepts only integer values at least 1 character long.',
        date_alert: 'Date format should looks like YYYY-MM-DD.',
    }
});