<?php
namespace Skopenow\PeopleData\Workers;

use Skopenow\PeopleData\SearchInvoker;

interface JobInterface
{
    public function __construct(string $key, string $api, array $input, SearchInvoker $invoker);

    public function ping();
    
    public function start();
    public function pause();
    public function terminate();
}
