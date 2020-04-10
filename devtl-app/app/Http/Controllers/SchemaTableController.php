<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveSchemaTableColumnRequest;
use App\Http\Requests\SaveSchemaTableRequest;
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

    public function columns($schemaTable)
    {
        $schemaTableColumns = $schemaTable->schemaTableColumns
            ->sortBy('order');

        return response()->json([
            'status' => true,
            'html' => view('schemaTables.columns', compact('schemaTableColumns'))->render(),
        ]);
    }

    public function updateColumns($schemaTable, SaveSchemaTableColumnRequest $request)
    {
        // remove last empty row from validation
        $schemaTableColumns = removeLastRow($request->schema_table_columns);

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
                $data['order'] = $schemaTable->schemaTableColumns()->count() + 1;
                $schemaTable->schemaTableColumns()
                    ->create($data);
            }
        }

        $schemaTableColumns = $schemaTable->schemaTableColumns
            ->sortBy('order');

        return response()->json([
            'status' => true,
            'html' => view('schemaTables.columns', compact('schemaTableColumns'))->render(),
        ]);
    }
}
