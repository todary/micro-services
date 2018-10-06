<?php

namespace Skopenow\Result\Save;

use App\Models\ResultData;

interface PendingResultsInterface
{
    public function save(ResultData $result);

    public function get(array $criteria): \Iterator;
    
}