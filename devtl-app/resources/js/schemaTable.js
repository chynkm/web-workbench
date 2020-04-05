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
        this.clickTableInHorizontalListing();
        this.changeTableAndColumns();
        this.autoIncrementEvent();
        this.zeroFillEvent();
        this.primaryKeyEvent();
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
        var self = this;

        $('#create_table_btn').click(function() {
            self.overlay.removeClass('d-none');
            $('#table_listing').addClass('d-none');
            $('#table_column_listing').removeClass('d-none');
            self.tableName.val('');
            self.tableEngine.val($(this).data('engine'));
            self.tableCollation.val($(this).data('collation'));
            self.tableDetailBody
                .empty()
                .append(self.exampleRow);
            self.tableButton
                .removeClass('btn-primary')
                .addClass('btn-outline-primary');
            self.overlay.addClass('d-none');
        });
    },

    clickTableInHorizontalListing: function() {
        $('#table_listing').on('click', '.table_button', function() {
            $('#table_listing').addClass('d-none');
            $('#table_column_listing').removeClass('d-none');
            $('.tables_listing_div').find('[data-name="'+$(this).data('name')+'"]')
                .addClass('btn-primary')
                .removeClass('btn-outline-primary');
        });
    },

    changeTableAndColumns: function() {
        var self = this;

        this.tableButton.click(function() {
            self.overlay.removeClass('d-none');
            var clickedTable = $(this);
            self.tableButton
                .removeClass('btn-primary')
                .addClass('btn-outline-primary');
            clickedTable.addClass('btn-primary')
                .removeClass('btn-outline-primary');

            $.getJSON(clickedTable.data('route_get_columns'))
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
                        self.primaryKeyOnLoad();
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
        // COL - AI only for INT types
        var self = this;

        $('#table_detail_tbody').on('click', '.auto_increment_column', function() {
            var row = $(this).closest('.table_column_row');
            var selectedType = row.find('.select_type option:selected').val();

            // remove all existing checks
            $('.auto_increment_column').not(this).prop('checked', false);
            $('.null_column, .default_value, .primary_key_column').prop('disabled', false);
            $('.primary_key_column').prop('checked', false);

            if (
                $(this).is(':checked')
                && $.inArray(selectedType, self.autoIncrementTypes) != -1
            ) {
                row.find('.null_column')
                    .prop('checked', false)
                    .prop('disabled', true);
                row.find('.default_value')
                    .val('')
                    .prop('disabled', true);
                row.find('.primary_key_column').prop('checked', true);
            }
        });

        $('#table_detail_tbody').on('change', '.select_type', function() {
            var row = $(this).closest('.table_column_row');
            var selectedType = row.find('.select_type option:selected').val();

            if($.inArray(selectedType, self.autoIncrementTypes) == -1) {
                // if not an INT type
                row.find('.auto_increment_column')
                    .prop('checked', false)
                    .prop('disabled', true);
                row.find('.null_column, .default_value')
                    .prop('disabled', false);
            } else {
                // if not an AI column
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
                // if not an INT type
                row.find('.auto_increment_column')
                    .prop('checked', false)
                    .prop('disabled', true);
            } else if(row.find('.auto_increment_column').is(':checked')) {
                // if AI column
                row.find('.null_column')
                    .prop('checked', false)
                    .prop('disabled', true);
                row.find('.default_value')
                    .val('')
                    .prop('disabled', true);
            }
        });
    },

    zeroFillEvent: function() {
        // COL - ZF only available for INT types
        // COL - ZF implied UN; UN won't be ticked; will be disabled
        // COL - UN available for only INT types
        var self = this;

        $('#table_detail_tbody').on('click', '.zero_fill_column', function() {
            var row = $(this).closest('.table_column_row');
            var selectedType = row.find('.select_type option:selected').val();

            if (
                $(this).is(':checked')
                && $.inArray(selectedType, self.autoIncrementTypes) != -1
            ) {
                // ZF ticked, then UN is disabled
                row.find('.unsigned_column')
                    .prop('checked', false)
                    .prop('disabled', true);
            } else {
                // ZF unticked, then UN is enabled
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
                // ZF, UN available for INT types
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
            } else if(row.find('.zero_fill_column').is(':checked')) {
                // ZF, UN available for INT types
                row.find('.unsigned_column')
                    .prop('disabled', true);
            }
        });
    },

    primaryKeyEvent: function() {
        // COL - PK; UQ disabled
        // COL - PK; NULL disabled
        // COL - PK a table can have only one primary key
        // COL - PK should be INT types only
        // COL - check UN click and NULL/UQ enabled
        var self = this;

        $('#table_detail_tbody').on('click', '.primary_key_column', function() {
            var row = $(this).closest('.table_column_row');
            var selectedType = row.find('.select_type option:selected').val();

            // remove all existing PK checks
            $('.primary_key_column').not(this).prop('checked', false);
            $('.unique_column, .null_column').prop('disabled', false);

            if (
                $(this).is(':checked')
                && $.inArray(selectedType, self.autoIncrementTypes) != -1
            ) {
                row.find('.unique_column, .null_column')
                    .prop('checked', false)
                    .prop('disabled', true);
            }

            // find row which is auto-increment and execute null column check
            $('.auto_increment_column:checked').closest('.table_column_row')
                .find('.null_column')
                .prop('disabled', true);
        });

        $('#table_detail_tbody').on('change', '.select_type', function() {
            var row = $(this).closest('.table_column_row');
            var selectedType = row.find('.select_type option:selected').val();

            if($.inArray(selectedType, self.autoIncrementTypes) == -1) {
                row.find('.unique_column, .null_column, .primary_key_column')
                    .prop('checked', false)
                    .prop('disabled', true);
            } else {
                row.find('.unique_column, .null_column, .primary_key_column')
                    .prop('disabled', false);
            }
        });
    },

    primaryKeyOnLoad: function() {
        var self = this;

        $('#table_detail_tbody').find('.table_column_row').each(function() {
            var row = $(this);
            var selectedType = row.find('.select_type option:selected').val();

            if($.inArray(selectedType, self.autoIncrementTypes) == -1) {
                row.find('.unique_column, .null_column, .primary_key_column')
                    .prop('checked', false)
                    .prop('disabled', true);
            }
        });
    },

};
