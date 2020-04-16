<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RelationshipController extends Controller
{
    public function delete($relationship)
    {
        $relationship->delete();
        return response()->json(['status' => true]);
    }
}
