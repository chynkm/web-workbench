<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchemaTableController extends Controller
{
    public function index($schema)
    {
        $pageTitle = $schema->name.' '.__('form.tables');
        return view('schemaTables.index', compact('pageTitle', 'schema'));
    }

    public function store($schema, Request $request)
    {
        $request->validate([
            'name' => 'required|min:2|max:100',
            'description' => 'max:255',
        ]);

        $schema->schemaTables()
            ->create([
                'user_id' => Auth::id(),
                'name' => $request->name,
                'description' => $request->description,
            ]);

        return response()->json(['status' => true]);
    }
}
