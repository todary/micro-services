<?php
namespace Skopenow\PeopleData\Sources;

use Skopenow\PeopleData\Criteria;
use Skopenow\PeopleData\Clients\ClientInterface;
use Skopenow\PeopleData\OutputModel;
use App\Models\ApiAccount;

class Googleplus extends AbstractSearchFetcher
{
    const SOURCE_NAME = "googleplus";
    const FETCHER_SOURCE = "googleplus";
}
