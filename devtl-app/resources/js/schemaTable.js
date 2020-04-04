$(function() {
    APP.schemaTable.init();
});

var APP = APP || {};

APP.schemaTable = {
    tableDetailBody: $('#table_detail_tbody'),
    exampleRow: $('#column_example_row tbody').html(),
    tableButton: $('.table_button'),
    tableName: $('#table_name'),
    tableEngine: $('#table_engine'),
    tableCollation: $('#table_collation'),
    autoIncrementTypes: ['tinyint', 'smallint', 'mediumint', 'int', 'bigint'],
    overlay: $('#overlay'),
    init: function() {
        this.addNewRow();
        this.createTable();
        this.changeTableAndColumns();
        this.autoIncrementEvent();
        this.zeroFillEvent();
    },

    addNewRow: function() {
        var self = this;

        // on keyUp; since dynamic row
        $('#table_detail_tbody').on('keypress', 'tr:last-child .column_name', function() {
            if ($(this).val().length) {
                self.tableDetailBody.append(self.exampleRow);
            }
        });
    },

    createTable: function() {
        $('#create_table_btn').click(function() {
            $('#table_listing').addClass('d-none');
            $('#table_column_listing').removeClass('d-none');
        });
    },

    changeTableAndColumns: function() {
        var self = this;

        this.tableButton.click(function() {
            self.overlay.removeClass('d-none');
            var clickedTable = $(this);

            $.getJSON(clickedTable.data('route'))
                .done(function(data) {
                    if (data.status) {
                        self.tableName.val(clickedTable.data('name'));
                        self.tableEngine.val(clickedTable.data('engine'));
                        self.tableCollation.val(clickedTable.data('collation'));
                        self.tableDetailBody
                            .empty()
                            .append(data.html)
                            .append(self.exampleRow);

                        self.autoIncrementOnLoad();
                        self.zeroFillOnLoad();
                    } else {
                        // @todo some error occurred, try again
                        // add it to common.js
                    }
                    self.overlay.addClass('d-none');
                });
        });
    },

    saveTableAndColumns: function() {
        var self = this;

    },

    autoIncrementEvent: function() {
        // COL - AI no default value
        // COL - AI means NULL is disabled, PK is enabled
        // COL - AI only available for one row throughout the table
        // COL - AI only for certain types
        var self = this;

        $('#table_detail_tbody').on('click', '.auto_increment_column', function() {
            var row = $(this).closest('.table_column_row');
            var selectedType = row.find('.select_type option:selected').val();

            // remove all checks
            $('.auto_increment_column').not(this).prop('checked', false);
            $('.null_column').prop('disabled', false);
            $('.default_value').prop('disabled', false);

            if (
                $(this).is(':checked')
                && $.inArray(selectedType, self.autoIncrementTypes) != -1
            ) {
                row.find('.default_value')
                    .val('')
                    .prop('disabled', true);
                row.find('.null_column')
                    .prop('checked', false)
                    .prop('disabled', true);
                row.find('.primary_key_column').prop('checked', true);
            } else {
                row.find('.default_value, .primary_key_column, .null_column')
                    .prop('disabled', false);
            }
        });

        $('#table_detail_tbody').on('change', '.select_type', function() {
            var row = $(this).closest('.table_column_row');
            var selectedType = row.find('.select_type option:selected').val();

            if($.inArray(selectedType, self.autoIncrementTypes) == -1) {
                row.find('.auto_increment_column')
                    .prop('checked', false)
                    .prop('disabled', true);
                row.find('.null_column, .default_value')
                    .prop('disabled', false);
            } else {
                if (row.find('.auto_increment_column').is(':checked')) {
                    row.find('.auto_increment_column')
                        .prop('checked', true);
                    row.find('.null_column')
                        .prop('checked', false)
                        .prop('disabled', true);
                    row.find('.default_value')
                        .val('')
                        .prop('disabled', true);
                }
                row.find('.auto_increment_column')
                    .prop('disabled', false);
            }
        });
    },

    autoIncrementOnLoad: function() {
        var self = this;

        $('#table_detail_tbody').find('.table_column_row').each(function() {
            var row = $(this);
            var selectedType = row.find('.select_type option:selected').val();

            if($.inArray(selectedType, self.autoIncrementTypes) == -1) {
                row.find('.auto_increment_column')
                    .prop('checked', false)
                    .prop('disabled', true);
                row.find('.null_column, .default_value')
                    .prop('disabled', false);
            } else {
                if (row.find('.auto_increment_column').is(':checked')) {
                    row.find('.auto_increment_column')
                        .prop('checked', true);
                    row.find('.null_column')
                        .prop('checked', false)
                        .prop('disabled', true);
                    row.find('.default_value')
                        .val('')
                        .prop('disabled', true);
                }
                row.find('.auto_increment_column')
                    .prop('disabled', false);
            }
        });
    },

    zeroFillEvent: function() {
        // COL - ZF only available for INT types
        // COL - ZF implied UN; UN won't be ticked; will be disabled
        var self = this;

        $('#table_detail_tbody').on('click', '.zero_fill_column', function() {
            var row = $(this).closest('.table_column_row');
            var selectedType = row.find('.select_type option:selected').val();

            if (
                $(this).is(':checked')
                && $.inArray(selectedType, self.autoIncrementTypes) != -1
            ) {
                row.find('.unsigned_column')
                    .prop('checked', false)
                    .prop('disabled', true);
            } else {
                row.find('.unsigned_column').prop('disabled', false);
            }
        });

        $('#table_detail_tbody').on('change', '.select_type', function() {
            var row = $(this).closest('.table_column_row');
            var selectedType = row.find('.select_type option:selected').val();

            if($.inArray(selectedType, self.autoIncrementTypes) == -1) {
                row.find('.unsigned_column, .zero_fill_column')
                    .prop('checked', false)
                    .prop('disabled', true);
            } else {
                row.find('.unsigned_column, .zero_fill_column')
                    .prop('disabled', false);
            }
        });
    },

    zeroFillOnLoad: function() {
        var self = this;

        $('#table_detail_tbody').find('.table_column_row').each(function() {
            var row = $(this);
            var selectedType = row.find('.select_type option:selected').val();

            if($.inArray(selectedType, self.autoIncrementTypes) == -1) {
                row.find('.unsigned_column, .zero_fill_column')
                    .prop('checked', false)
                    .prop('disabled', true);
            } else {
                row.find('.unsigned_column, .zero_fill_column')
                    .prop('disabled', false);
            }
        });
    },
};
