$(function() {
    APP.common.init();
});

var APP = APP || {};

APP.common = {
    init: function() {
        this.signOut();
    },

    signOut: function() {
        $('#sign_out_btn').click(function() {
            $('#sign_out_form').submit();
        });
    },
};
