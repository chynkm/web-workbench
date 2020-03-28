$(function() {
    APP.common.init();
});

var APP = APP || {};

APP.common = {
    init: function() {
        this.signOut();
        this.showToast();
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
};
