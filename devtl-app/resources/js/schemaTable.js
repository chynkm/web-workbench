$(function() {
    APP.schemaTable.init();
});

var APP = APP || {};

APP.schemaTable = {
    tableDetailBody: $('#table_detail_tbody'),
    sampleRow: $('#column_example_row tbody').html(),
    init: function() {
        this.addNewRow();
        this.autoIncrement();
    },

    addNewRow: function() {
        var self = this;

        // on keyUp; since dynamic row
        $('#table_detail_tbody').on('keypress', 'tr:last-child .column_name', function() {
            if ($(this).val().length) {
                self.tableDetailBody.append(self.sampleRow);
            }
        });
    },

    autoIncrement: function() {
        // should only be possible for numbers columns
        // if ticked here, remove from other

    },

};
