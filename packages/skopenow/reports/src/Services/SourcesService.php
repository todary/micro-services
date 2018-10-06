<?php
namespace Skopenow\Reports\Services;

use DB;

/**
*
*/
class SourcesService
{
    public function isFreeSource($sourceKey)
    {
        return false;
    }

    public function getSources($mainSource)
    {
        $sources = DB::table('source')
            ->join('main_source', 'main_source.id', '=', 'source.main_source_id')
            ->where('main_source.name', $mainSource)
            ->pluck('source.name')->all();
        return $sources;
    }

    public function getAllSources()
    {
        $sources = DB::table('source')->pluck('source.name')->all();
        return $sources;
    }
}
