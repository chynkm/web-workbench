<?php

namespace App\Http\Controllers;

use App\Models\Schema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchemaController extends Controller
{
    public function index()
    {
        $pageTitle = __('form.schemas');
        $schemas = Auth::user()->schemas
            ->sortBy('name');

        return view('schemas.index', compact('pageTitle', 'schemas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|alpha_dash|max:100',
        ]);

        $schema = Schema::create(['name' => $request->name]);
        Auth::user()->schemas()
            ->syncWithoutDetaching([$schema->id => ['owner' => true]]);

        $request->session()
            ->flash('alert', [
                'class' => 'success',
                'message' => __('form.schema_created_successfully')
            ]);

        return response()->json(['url' => route('schemaTables.index', ['schema' => $schema->id])]);
    }

    public function show(Schema $schema)
    {
        $pageTitle = $schema->name;
        return view('schemas.show', compact('pageTitle', 'schema'));
    }
}
