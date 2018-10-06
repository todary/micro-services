<?php

namespace Skopenow\Search\Models;

class DataPoint implements DataPointInterface
{
    public function save(): bool
    {
        echo "DataPoint saved!\n";
        return true;
    }
}
