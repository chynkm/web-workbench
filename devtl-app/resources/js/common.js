$(function() {
    APP.common.init();
});

var APP = APP || {};

APP.common = {
    init: function() {
        this.signOut();
        this.showToast();
        this.createSchema();
    },

    signOut: function() {
        $('#sign_out_btn').click(function() {
            $('#sign_out_form').submit();
        });
    },

    showToast: function() {
        if($('#alert_toast').length) {
            $('#alert_toast').toast('show');
        }
    },

    createSchema: function() {
        var self = this;
        $('#create_schema_form').on('hide.bs.modal', function (e) {
            self.clearCreateSchemaFormErrors();
        });

        $('#create_schema_form').submit(function(e) {
            e.preventDefault();
            self.clearCreateSchemaFormErrors();
            $('#create_schema_button').data('original-text', $('#create_schema_button').html());
            $('#create_schema_button').html($('#create_schema_button').data('loading-text')).prop('disabled', true);

            $.post($(this).attr('action'), $(this).serialize())
                .done(function(data) {
                    console.log(data);
                })
                .fail(function(xhr) {
                    $('#create_schema_button').html($('#create_schema_button').data('original-text')).prop('disabled', false);
                    var data = xhr.responseJSON;
                    if (data.errors) {
                        for (var i in data.errors) {
                            $('#'+i).addClass('is-invalid')
                                .parent()
                                .find('.invalid-feedback')
                                .html(data.errors[i])
                                .removeClass('d-none');
                        };
                    };
                });
        });
    },

    clearCreateSchemaFormErrors: function() {
        $('#name').val('');
        $('.invalid-feedback').html('').addClass('d-none');
    },
};
