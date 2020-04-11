(function ($) {
    $.fn.serialize = function (options) {
        return $.param(this.serializeArray(options));
    };

    $.fn.serializeArray = function (options) {
        var o = $.extend({
            checkboxesAsBools: false
        }, options || {});

        var rselectTextarea = /select|textarea/i;
        var rinput = /text|hidden|password|search|number/i;

        return this.map(function () {
            return this.elements ? $.makeArray(this.elements) : this;
        })
        .filter(function () {
            return this.name && !this.disabled &&
                (this.checked
                || (o.checkboxesAsBools && this.type === 'checkbox')
                || rselectTextarea.test(this.nodeName)
                || rinput.test(this.type));
            })
            .map(function (i, elem) {
                var val = $(this).val();
                return val == null ?
                null :
                $.isArray(val) ?
                $.map(val, function (val, i) {
                    return { name: elem.name, value: val };
                }) :
                {
                    name: elem.name,
                    value: (o.checkboxesAsBools && this.type === 'checkbox') ?
                        (this.checked ? 1 : 0) :
                        val
                };
            }).get();
    };
})(jQuery);

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
    inputFields: ['name', 'length', 'comment', 'default_value',],
    overlay: $('#overlay'),
    tableForm: $('#create_schema_table_form'),
    columnForm: $('#create_schema_table_column_form'),
    tableErrorDiv: $('#table_error_display_div'),
    sidebarTables: $('.sidebar_tables_listing'),
    init: function() {
        this.addRemoveRow();
        this.disableLastDeleteButton();
        this.createTable();
        this.saveTableAndColumns();
        this.deleteTableColumns();
        this.clickTableInHorizontalListing();
        this.changeTableAndColumns();
        this.autoIncrementEvent();
        this.zeroFillEvent();
        this.primaryKeyEvent();
    },

    addRemoveRow: function() {
        var self = this;

        // on keyUp; since dynamic row
        $('#table_detail_tbody').on('keyup', 'tr:last-child .name', function() {
            if ($(this).val().length) {
                self.tableDetailBody.append(self.exampleRow);
            }
            self.disableLastDeleteButton();
        });

        // delete last row, if second last_row is empty
        $('#table_detail_tbody').on('keyup', 'tr:eq(-2) .name', function() {
            if ($(this).val().length == 0) {
                self.tableDetailBody.find('tr:last-child').remove();
            }
            self.disableLastDeleteButton();
        });
    },

    disableLastDeleteButton: function() {
        this.tableDetailBody
            .find('.delete_column_button')
            .prop('disabled', false);
        this.tableDetailBody
            .find('.delete_column_button')
            .last()
            .prop('disabled', true);
    },

    createTable: function() {
        var self = this;

        $('#create_table_btn').click(function() {
            self.clearErrors();
            self.overlay.removeClass('d-none');
            $('#table_listing').addClass('d-none');
            $('#table_column_listing').removeClass('d-none');
            self.tableName.val('');
            self.tableEngine.val($(this).data('engine'));
            self.tableCollation.val($(this).data('collation'));
            self.tableForm.attr('action', $(this).data('route_save_table'))
            self.tableDetailBody
                .empty()
                .append(self.exampleRow);
            self.disableLastDeleteButton();
            self.tableButton
                .removeClass('btn-primary')
                .addClass('btn-outline-primary');
            self.overlay.addClass('d-none');
        });
    },

    clickTableInHorizontalListing: function() {
        var self = this;

        $('#table_listing').on('click', '.table_button', function() {
            $('#table_listing').addClass('d-none');
            $('#table_column_listing').removeClass('d-none');
            self.sidebarTables
                .find('[data-name="'+$(this).data('name')+'"]')
                .click()
                .addClass('btn-primary')
                .removeClass('btn-outline-primary');
        });
    },

    changeTableAndColumns: function() {
        var self = this;

        $('.sidebar_tables_listing').on('click', '.table_button', function() {
            self.clearErrors();
            self.overlay.removeClass('d-none');
            self.tableErrorDiv.empty();
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
                        self.tableForm.attr('action', clickedTable.data('route_save_table'))
                        self.columnForm.attr('action', clickedTable.data('route_save_columns'))
                        self.tableDetailBody
                            .empty()
                            .append(data.html)
                            .append(self.exampleRow);
                        self.sortTableColumns();
                        self.disableLastDeleteButton();

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

    clearErrors: function() {
        this.tableForm
            .find('.is-invalid')
            .removeClass('is-invalid');
        this.columnForm
            .find('.error_highlight')
            .removeClass('error_highlight');
    },

    saveTableAndColumns: function() {
        var self = this;

        $('#create_schema_table_button').click(function() {
            self.overlay.removeClass('d-none');
            self.tableErrorDiv.empty();
            self.clearErrors();

            var tableData = self.tableForm.serialize();

            $.post(self.tableForm.attr('action'), self.tableForm.serialize())
                .done(function(data) {
                    if (data.status) {
                        self.sidebarTables
                            .empty()
                            .html(data.sidebarHtml);

                        self.sidebarTables
                            .find('[data-name="'+self.tableName.val()+'"]')
                            .addClass('btn-primary')
                            .removeClass('btn-outline-primary');

                        if (data.table_url) {
                            self.tableForm.attr('action', data.table_url)
                            self.columnForm.attr('action', data.column_url)
                        }

                        self.saveColumns(self);
                    }
                })
                .fail(function(xhr) {
                    var data = xhr.responseJSON;
                    for (var i in data.errors) {
                        $('#table_'+i).addClass('is-invalid')
                    };
                    self.tableErrorDiv.html(data.html);
                })
                .always(function() {
                    self.overlay.addClass('d-none');
                });
        });
    },

    saveColumns: function(self) {
        var disabled = self.columnForm.find(':input:disabled').removeAttr('disabled');
        var columnData = self.columnForm.serialize({ checkboxesAsBools: true });
        disabled.attr('disabled','disabled');

        $.post(self.columnForm.attr('action'), columnData)
            .done(function(data) {
                if (data.status) {
                    $('#toast_div').html(data.toast);
                    $('#alert_toast').toast('show');

                    self.tableDetailBody
                        .empty()
                        .append(data.html)
                        .append(self.exampleRow);
                    self.sortTableColumns();
                    self.disableLastDeleteButton();

                    self.autoIncrementOnLoad();
                    self.zeroFillOnLoad();
                    self.primaryKeyOnLoad();
                }
            })
            .fail(function(xhr) {
                var data = xhr.responseJSON;
                for (var field in data.errors) {
                    field = field.split('.');
                    if ($.inArray(field[0], self.inputFields) == -1 ) {
                        $('.'+field[0]+':eq('+field[1]+')').closest('td')
                            .addClass('error_highlight');
                    } else {
                        $('.'+field[0]+':eq('+field[1]+')').addClass('error_highlight');
                    }
                }
                self.tableErrorDiv.html(data.html);
            });
    },

    sortTableColumns: function() {
        this.tableDetailBody
            .sortable({
                handle: '.sort_column_button',
                cancel: '',
                items: 'tr:not(tr:last-child)',
                cursor: 'pointer',
                axis: 'y',
                update: function(event, ui) {
                    $(this).find('tr').each(function (index) {
                        $(this).find('.order').val(index+1);
                    });
                }
            });
    },

    deleteTableColumns: function() {
        $('#delete_confirm_modal').on('show.bs.modal', function(e) {
            $('#delete_ok').click(function() {
                if ($(e.relatedTarget).data('href').length) {
                    $.getJSON($(e.relatedTarget).data('href'), function(data) {
                        if (data.status) {
                            $(e.relatedTarget).closest('tr.table_column_row')
                                .addClass('table-danger')
                                .fadeOut('slow', function() {
                                    $(this).remove();
                                });
                        }
                    });
                } else {
                    $(e.relatedTarget).closest('tr.table_column_row')
                        .addClass('table-danger')
                        .fadeOut('slow', function() {
                            $(this).remove();
                        });
                }
                $('#delete_confirm_modal').modal('hide');
            });
        });
    },

    autoIncrementEvent: function() {
        // COL - AI no default value
        // COL - AI means NULL is disabled, PK is enabled
        // COL - AI only available for one row throughout the table
        // COL - AI only for INT types
        var self = this;

        $('#table_detail_tbody').on('click', '.auto_increment_column', function() {
            var row = $(this).closest('.table_column_row');
            var selectedType = row.find('.datatype option:selected').val();

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

        $('#table_detail_tbody').on('change', '.datatype', function() {
            var row = $(this).closest('.table_column_row');
            var selectedType = row.find('.datatype option:selected').val();

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
            var selectedType = row.find('.datatype option:selected').val();

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
            var selectedType = row.find('.datatype option:selected').val();

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

        $('#table_detail_tbody').on('change', '.datatype', function() {
            var row = $(this).closest('.table_column_row');
            var selectedType = row.find('.datatype option:selected').val();

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
            var selectedType = row.find('.datatype option:selected').val();

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
            var selectedType = row.find('.datatype option:selected').val();

            // remove all existing PK checks
            $('.primary_key_column').not(this).prop('checked', false);
            $('.unique_column, .null_column').prop('disabled', false);

            if (
                $(this).is(':checked')
                && $.inArray(selectedType, self.autoIncrementTypes) == -1
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

        $('#table_detail_tbody').on('change', '.datatype', function() {
            var row = $(this).closest('.table_column_row');
            var selectedType = row.find('.datatype option:selected').val();

            if(
                row.find('.primary_key_column').is(':checked')
                && $.inArray(selectedType, self.autoIncrementTypes) == -1
            ) {
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
            var selectedType = row.find('.datatype option:selected').val();

            if($.inArray(selectedType, self.autoIncrementTypes) == -1) {
                row.find('.unique_column, .null_column, .primary_key_column')
                    .prop('checked', false)
                    .prop('disabled', true);
            }
        });
    },

};
