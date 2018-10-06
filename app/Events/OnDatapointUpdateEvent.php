<?php

namespace App\Events;

class OnDatapointUpdateEvent extends Event
{
    public $input;
    public $type;
    public $datapointKey;
    public $entityId;
    public $state;
    public $oldData;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($input, string $type, string $datapointKey, int $entityId, $oldData)
    {
        $this->input = $input;
        $this->type = $type;
        $this->datapointKey = $datapointKey;
        $this->entityId = $entityId;
        $this->state = config('state');
        $this->oldData = $oldData;
    }
}
