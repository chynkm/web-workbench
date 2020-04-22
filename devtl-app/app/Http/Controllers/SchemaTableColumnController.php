<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SchemaTableColumnController extends Controller
{
    public function delete($schemaTableColumn)
    {
        if (
            $schemaTableColumn->foreignRelationships->count()
            || $schemaTableColumn->primaryRelationships->count()
        ) {
            $alert = [
                'class' => 'danger',
                'message' => __('form.fk_relationship_exists_for_delete_column'),
            ];

            return response()->json([
                    'status' => false,
                    'html' => view('layouts.alert', compact('alert'))->render(),
                ], 422);
        }

        $schemaTableColumn->delete();
        return response()->json(['status' => true]);
    }
}
