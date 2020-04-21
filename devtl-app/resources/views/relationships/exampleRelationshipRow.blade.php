<tr class="table_relationship_row">
    <input type="hidden" name="relationships[id][]" value="{{ isset($relationship) ? $relationship->id : null }}">
    <td>
        <select name="relationships[foreign_table_column_id][]" class="form-control foreign_table_column_id" aria-labelledby="label-type">
            @foreach($schemaTableColumns as $schemaTableColumn)
            <option value="{{ $schemaTableColumn->id }}" data-datatype="{{ $schemaTableColumn->datatype }}"{{ isset($relationship) && $relationship->foreign_table_column_id == $schemaTableColumn->id ? ' selected' : null }}>{{ $schemaTableColumn->name }}</option>
            @endforeach
        </select>
    </td>
    <td>
        <select name="relationships[primary_table_id][]" class="form-control primary_table_id" aria-labelledby="label-type">
            <option value=""{{ ! isset($relationship) ? ' selected' : null }}>@lang('form.select_table')</option>
            @foreach($schemaTables as $schemaTable)
            <option value="{{ $schemaTable->id }}"{{ isset($relationship) && $relationship->primary_table_id == $schemaTable->id ? ' selected' : null }}>{{ $schemaTable->name }}</option>
            @endforeach
        </select>
    </td>
    <td>
        <input type="hidden" class="hidden_primary_table_column_id" value="{{ isset($relationship) ? $relationship->primary_table_column_id : null }}">
        <select name="relationships[primary_table_column_id][]" class="form-control primary_table_column_id" aria-labelledby="label-type">
            <option>@lang('form.select_column')</option>
        </select>
    </td>
    <td>
        <button type="button"
            class="btn btn-danger btn-sm delete_relationship_button"
            data-href="{{ isset($relationship) ? route('relationships.delete', ['relationship' => $relationship->id]) : null }}"
            data-item="relationship"
            data-toggle="modal"
            data-target="#delete_confirm_modal">
                <span class="oi oi-x"></span>
        </button>
    </td>
</tr>
