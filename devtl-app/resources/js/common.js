$(function() {
    APP.common.init();
});

var APP = APP || {};

APP.common = {
    init: function() {
        this.signOut();
        this.showToast();
        this.showToolTip();
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

    showToolTip: function() {
        $('[data-toggle="tooltip"]').tooltip();
    },

    createSchema: function() {
        var self = this;
        $('#create_schema_modal').on('hide.bs.modal', function (e) {
            $('#name').val('');
            self.clearCreateSchemaFormErrors();
        });

        $('#create_schema_form').submit(function(e) {
            e.preventDefault();
            self.clearCreateSchemaFormErrors();
            $('#create_schema_button').data('original-text', $('#create_schema_button').html());
            $('#create_schema_button').html($('#create_schema_button').data('loading-text')).prop('disabled', true);

            $.post($(this).attr('action'), $(this).serialize())
                .done(function(data) {
                    window.location.href = data.url;
                })
                .fail(function(xhr) {
                    $('#create_schema_button').html($('#create_schema_button').data('original-text')).prop('disabled', false);
                    var data = xhr.responseJSON;
                    for (var i in data.errors) {
                        $('#'+i).addClass('is-invalid')
                            .parent()
                            .find('.invalid-feedback')
                            .html(data.errors[i])
                            .removeClass('d-none');
                    };
                });
        });
    },

    clearCreateSchemaFormErrors: function() {
        $('#name').removeClass('is-invalid');
        $('.invalid-feedback').html('').addClass('d-none');
    },
};
