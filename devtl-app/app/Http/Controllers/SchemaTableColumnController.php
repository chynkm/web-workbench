<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SchemaTableColumnController extends Controller
{
    public function delete($schemaTableColumn)
    {
        $schemaTableColumn->delete();
        return response()->json(['status' => true]);
    }
}
