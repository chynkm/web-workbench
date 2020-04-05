<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveSchemaTableRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function updateColumns($schemaTable, Request $request)
    {

    }
}
