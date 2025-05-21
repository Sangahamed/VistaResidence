<?php

namespace App\Services;

use App\Models\SavedSearch;
use App\Models\Property;

class SearchService
{
    public function checkForNewMatches(SavedSearch $search)
    {
        $properties = Property::query()
            ->applySearchCriteria($search->criteria)
            ->where('created_at', '>', $search->updated_at)
            ->get();

        if ($properties->isNotEmpty()) {
            app(NotificationService::class)->notifySearchMatches($search, $properties);
        }
    }
}