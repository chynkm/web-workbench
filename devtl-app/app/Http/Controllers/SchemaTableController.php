<?php

namespace App\Http\Controllers;

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

    public function store($schema, Request $request)
    {
        $request->validate([
            'name' => 'required|alpha_dash|max:100',
            'engine' => 'required|max:20',
            'collation' => 'required|max:40',
            'description' => 'max:255',
        ]);

        $schema->schemaTables()
            ->create([
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
