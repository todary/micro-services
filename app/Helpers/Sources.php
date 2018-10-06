<?php

use App\Model\SourceAdmin;
use Illuminate\Support\Facades\Cache;

if (!function_exists('trackSources')) {
    function trackSources()
    {
        $sources = Cache::remember('sourcesTrack', 1440, function () {
            SourceAdmin::where([
                ['is_visible', 1],
                ['filter_source', '<>', ''],
                ['display_name', '<>', ''],
            ])->get();
        });
        $filterSources = array();
        $sourcescounter = 0;

        foreach ($sources as $sources) {
            if (strpos($sources->filter_source, 'facebook') !== false) {
                $sources->filter_source = 'facebook';
            }

            if (array_key_exists($sources->filter_source, $filterSources)) {
                continue;
            }

            $filterSources[$sources->filter_source] = $sources->display_name;
            $sourcescounter++;
        }

        return $filterSources;
    }
}
