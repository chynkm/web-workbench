<?php

namespace App\Observers;

use App\Models\Relationship;

class RelationshipObserver
{
    public function updated(Relationship $relationship)
    {
        $relationship->createHistory();
    }
}
