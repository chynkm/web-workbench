<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveRelationshipRequest;
use App\Http\Requests\SaveSchemaTableColumnRequest;
use App\Http\Requests\SaveSchemaTableRequest;
use App\Models\SchemaTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SchemaTableController extends Controller
{
    public function index($schema)
    {
        $pageTitle = $schema->name.' '.__('form.tables');
        $schemaTables = $schema->schemaTables
            ->sortBy('name');

        return view('schemaTables.index', compact(
            'pageTitle',
            'schema',
            'schemaTables',
        ));
    }

    public function create($schema)
    {
        $pageTitle = $schema->name;
        $schemaTables = $schema->schemaTables
            ->sortBy('name');

        $relationships = $schemaTableColumns = [];

        return view('schemaTables.createEdit', compact(
            'pageTitle',
            'schema',
            'schemaTableColumns',
            'schemaTables',
            'relationships',
        ));
    }

    public function edit($schemaTable)
    {
        $pageTitle = $schemaTable->name;
        $schemaTableColumns = $schemaTable->schemaTableColumns
            ->sortBy('order');

        $schemaTables = $schemaTable->schema
            ->schemaTables
            ->sortBy('name');
        $relationships = $schemaTable->foreignRelationships;

        return view('schemaTables.createEdit', compact(
            'pageTitle',
            'schemaTable',
            'schemaTableColumns',
            'schemaTables',
            'relationships',
        ));
    }

    public function store($schema, SaveSchemaTableRequest $request)
    {
        $schemaTable = $schema->schemaTables()
            ->create([
                'user_id' => Auth::id(),
                'name' => $request->name,
                'engine' => $request->engine,
                'collation' => $request->collation,
                'description' => $request->description,
            ]);

        return response()->json([
            'status' => true,
            'browser_url' => route('schemaTables.edit', ['schemaTable' => $schemaTable->id]),
            'table_url' => route('schemaTables.update', ['schemaTable' => $schemaTable->id]),
            'column_url' => route('schemaTables.updateColumns', ['schemaTable' => $schemaTable->id]),
            'relationship_url' => route('schemaTables.updateRelationships', ['schemaTable' => $schemaTable->id]),
        ]);
    }

    public function update($schemaTable, SaveSchemaTableRequest $request)
    {
        $schemaTable->update([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'engine' => $request->engine,
            'collation' => $request->collation,
            'description' => $request->description,
        ]);

        return response()->json(['status' => true]);
    }

    public function delete($schemaTable)
    {
        $schemaTable->delete();
        return response()->json(['status' => true]);
    }

    public function updateColumns($schemaTable, SaveSchemaTableColumnRequest $request)
    {
        // remove last empty row from validation
        $schemaTableColumns = removeLastTableColumnRow($request->schema_table_columns);

        for ($i = 0; $i < count($schemaTableColumns['id']); $i++) {
            $data = [
                'user_id' => Auth::id(),
                'name' => $schemaTableColumns['name'][$i],
                'datatype' => $schemaTableColumns['datatype'][$i],
                'length' => $schemaTableColumns['length'][$i],
                'primary_key' => $schemaTableColumns['primary_key'][$i],
                'unique' => $schemaTableColumns['unique'][$i],
                'zero_fill' => $schemaTableColumns['zero_fill'][$i],
                'auto_increment' => $schemaTableColumns['auto_increment'][$i],
                'unsigned' => $schemaTableColumns['unsigned'][$i],
                'nullable' => $schemaTableColumns['nullable'][$i],
                'default_value' => $schemaTableColumns['default_value'][$i],
                'comment' => $schemaTableColumns['comment'][$i],
                'order' => $schemaTableColumns['order'][$i],
            ];

            $existingSchemaTableColumn = $schemaTable->schemaTableColumns()
                ->find($schemaTableColumns['id'][$i]);

            if ($existingSchemaTableColumn) {
                $existingSchemaTableColumn->update($data);
            } else {
                $data['order'] = isset($data['order']) ? $data['order'] : $schemaTable->schemaTableColumns()->count() + 1;
                $schemaTable->schemaTableColumns()
                    ->create($data);
            }
        }

        $schemaTableColumns = $schemaTable->schemaTableColumns
            ->sortBy('order');

        $alert = [
            'class' => 'success',
            'message' => __('form.table_changes_saved_successfully')
        ];

        return response()->json([
            'status' => true,
            'toast' => view('layouts.toast', compact('alert'))->render(),
            'html' => view('schemaTables.columns', compact('schemaTableColumns'))->render(),
        ]);
    }

    public function updateRelationships($schemaTable, SaveRelationshipRequest $request)
    {
        // remove last empty row from validation
        $relationships = removeLastRelationshipRow($request->relationships);

        for ($i = 0; $i < count($relationships['id']); $i++) {
            $data = [
                'user_id' => Auth::id(),
                'foreign_table_id' => $schemaTable->id,
                'foreign_table_column_id' => $relationships['foreign_table_column_id'][$i],
                'primary_table_id' => $relationships['primary_table_id'][$i],
                'primary_table_column_id' => $relationships['primary_table_column_id'][$i],
            ];

            $existingRelationship = $schemaTable->foreignRelationships()
                ->find($relationships['id'][$i]);

            if ($existingRelationship) {
                $existingRelationship->update($data);
            } else {
                $schemaTable->foreignRelationships()
                    ->create($data);
            }
        }

        $schemaTableColumns = $schemaTable->schemaTableColumns
            ->sortBy('order');
        $schemaTables = $schemaTable->schema
            ->schemaTables
            ->sortBy('name');
        $relationships = $schemaTable->foreignRelationships;

        $alert = [
            'class' => 'success',
            'message' => __('form.fk_saved_successfully')
        ];

        return response()->json([
            'status' => true,
            'toast' => view('layouts.toast', compact('alert'))->render(),
            'html' => view('schemaTables.relationships', compact('schemaTableColumns', 'schemaTables', 'relationships'))->render(),
        ]);
    }

    public function referenceColumns(Request $request)
    {
        $schemaTable = SchemaTable::select('schema_tables.*')
            ->join('schema_user', 'schema_tables.schema_id', 'schema_user.schema_id')
            ->where('schema_user.user_id', Auth::id())
            ->find($request->schema_table_id);

        if ($schemaTable) {
            $referenceColumns = $schemaTable->schemaTableColumns
                ->where('datatype', $request->datatype)
                ->pluck('name', 'id');
        } else {
            $referenceColumns = [];
        }

        return response()->json([
            'status' => true,
            'html' => view('schemaTableColumns.referenceColumns', compact('referenceColumns'))->render(),
        ]);
    }
}
