<?php

namespace App\Events;

use App\Models\Result ;

class OnVerifiedResultSaveEvent extends Event
{

    /**
     * [$result the result's object]
     * @var [model object]
     */
    protected $result ;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Result $result)
    {
        $this->result = $result ;
    }

    public function getResult()
    {
        return $this->result ;
    }
}
