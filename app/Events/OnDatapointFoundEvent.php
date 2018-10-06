<?php

namespace App\Events;

use Skopenow\Datapoint\Classes\Datapoint;
use App\DataTypes\DataType;

class OnDatapointFoundEvent extends Event
{
    public $type;
    public $data;
    public $state;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Datapoint $type, DataType $data)
    {
        $this->type = $type;
        $this->data = $data;
        $this->state = config('state');
    }
}
