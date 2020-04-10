<tr class="table_column_row">
    <input type="hidden" name="schema_table_columns[id][]" value="{{ isset($schemaTableColumn) ? $schemaTableColumn->id : null }}">
    <input type="hidden" name="schema_table_columns[order][]" value="{{ isset($schemaTableColumn) ? $schemaTableColumn->order : null }}">
    <td><input type="text" class="name" name="schema_table_columns[name][]" class="length" placeholder="@lang('form.name')" value="{{ isset($schemaTableColumn) ? $schemaTableColumn->name : null }}"></td>
    <td>@include('schemaTableColumns.datatype', compact('schemaTableColumn'))</td>
    <td><input type="text" class="length" name="schema_table_columns[length][]" placeholder="@lang('form.length')" value="{{ isset($schemaTableColumn) ? $schemaTableColumn->length : null }}"></td>
    <td class="text-center">
        <div class="form-check">
            <input class="form-check-input position-static null_column" type="checkbox" name="schema_table_columns[nullable][]" value="1" {{ isset($schemaTableColumn) && $schemaTableColumn->nullable ? ' checked' : null }}>
        </div>
    </td>
    <td class="text-center">
        <div class="form-check">
            <input class="form-check-input position-static unsigned_column" type="checkbox" name="schema_table_columns[unsigned][]" value="1" {{ isset($schemaTableColumn) && $schemaTableColumn->unsigned ? ' checked' : null }}>
        </div>
    </td>
    <td class="text-center">
        <div class="form-check">
            <input class="form-check-input position-static unique_column" type="checkbox" name="schema_table_columns[unique][]" value="1" {{ isset($schemaTableColumn) && $schemaTableColumn->unique ? ' checked' : null }}>
        </div>
    </td>
    <td class="text-center">
        <div class="form-check">
            <input class="form-check-input position-static auto_increment_column" type="checkbox" name="schema_table_columns[auto_increment][]" value="1" {{ isset($schemaTableColumn) && $schemaTableColumn->auto_increment ? ' checked' : null }}>
        </div>
    </td>
    <td class="text-center">
        <div class="form-check">
            <input class="form-check-input position-static primary_key_column" type="checkbox" name="schema_table_columns[primary_key][]" value="1" {{ isset($schemaTableColumn) && $schemaTableColumn->primary_key ? ' checked' : null }}>
        </div>
    </td>
    <td class="text-center">
        <div class="form-check">
            <input class="form-check-input position-static zero_fill_column" type="checkbox" name="schema_table_columns[zero_fill][]" value="1" {{ isset($schemaTableColumn) && $schemaTableColumn->zero_fill ? ' checked' : null }}>
        </div>
    </td>
    <td><input type="text" class="default_value" name="schema_table_columns[default_value][]" placeholder="@lang('form.default_value')" value="{{ isset($schemaTableColumn) ? $schemaTableColumn->default_value : null }}"></td>
    <td><input type="text" class="comment" name="schema_table_columns[comment][]" placeholder="@lang('form.comment')" value="{{ isset($schemaTableColumn) ? $schemaTableColumn->comment : null }}"></td>
</tr>
