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
        $schemas = Auth::user()->schemas;

        return view('schemas.index', compact('pageTitle', 'schemas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:2|max:100',
        ]);

        $schema = Schema::create(['name' => $request->name]);
        Auth::user()->schemas()
            ->syncWithoutDetaching([$schema->id => ['owner' => true]]);

        return redirect(route('schemas.show', ['schema' => $schema->id]))
            ->with('alert', [
                'class' => 'success',
                'message' => __('form.schema_created_successfully')
            ]);
    }

    public function show($schema)
    {

    }
}
