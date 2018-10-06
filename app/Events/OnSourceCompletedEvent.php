<?php

namespace App\Events;

class OnSourceCompletedEvent extends Event
{
    protected $reportId;
    protected $managerClassName = "";

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $managerClassName)
    {
        $this->reportId =  config('state.report_id');
        $this->managerClassName = $managerClassName;
    }

    public function getReportId()
    {
        return $this->reportId ;
    }

    public function getManagerClassName()
    {
        return $this->managerClassName ;
    }
}
