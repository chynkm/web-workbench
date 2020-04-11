<div class="row">
    @forelse ($schemaTables as $schemaTable)
    <div class="col-md-12 mt-2">
        <button
            type="button"
            class="btn btn-outline-primary btn-block table_button"
            data-route_get_columns="{{ route('schemaTables.columns', ['schemaTable' => $schemaTable->id]) }}"
            data-route_save_table="{{ route('schemaTables.update', ['schemaTable' => $schemaTable->id]) }}"
            data-route_save_columns="{{ route('schemaTables.updateColumns', ['schemaTable' => $schemaTable->id]) }}"
            data-name="{{ $schemaTable->name }}"
            data-engine="{{ $schemaTable->engine }}"
            data-collation="{{ $schemaTable->collation }}">
            {{ substr($schemaTable->name, 0, 40) }}
        </button>
    </div>
    @endforeach
</div>
