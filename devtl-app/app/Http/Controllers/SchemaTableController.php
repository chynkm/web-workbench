<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveSchemaTableColumnRequest;
use App\Http\Requests\SaveSchemaTableRequest;
use App\Models\Relationship;
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

        return view('schemaTables.createEdit', compact(
            'pageTitle',
            'schema',
        ));
    }

    public function edit($schemaTable)
    {
        $pageTitle = $schemaTable->name;
        $schemaTableColumns = $schemaTable->schemaTableColumns
            ->sortBy('order');

        return view('schemaTables.createEdit', compact(
            'pageTitle',
            'schemaTable',
            'schemaTableColumns',
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
            'current_url' => route('schemaTables.edit', ['schemaTable' => $schemaTable->id]),
            'table_url' => route('schemaTables.update', ['schemaTable' => $schemaTable->id]),
            'column_url' => route('schemaTables.updateColumns', ['schemaTable' => $schemaTable->id]),
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
                'primary_table_id' => $schemaTable->id,
                'primary_table_column_id' => $relationships['primary_table_column_id'][$i],
                'foreign_table_id' => $relationships['foreign_table_id'][$i],
                'foreign_table_column_id' => $relationships['foreign_table_column_id'][$i],
            ];

            $existingRelationship = Relationship::find($relationships['id'][$i]);

            if (
                $existingRelationship
                && $existingRelationship->primary_table_id == $schemaTable->id
            ) {
                $existingRelationship->update($data);
            } else {
                Relationship::create($data);
            }
        }

        return response()->json([
            'status' => true,
            'html' => null,
            // 'html' => view('schemaTables.columns', compact('schemaTableColumns'))->render(),
        ]);
    }
}
