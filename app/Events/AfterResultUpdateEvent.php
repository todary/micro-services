<?php

namespace App\Events;

use App\Models\ResultData as Result ;

class AfterResultUpdateEvent extends Event
{

    /**
     * [$result the result's object]
     * @var [model object]
     */
    protected $result ;

    /**
     * [$report_id the report identifier]
     * @var [type]
     */
    protected $report_id;
    
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Result $result)
    {
        $this->result = $result ;
        $this->report_id = config('state.report_id');
    }

    public function getResult()
    {
        return $this->result ;
    }

    public function getReportId()
    {
        return $this->report_id;
    }
}
