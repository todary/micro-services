<?php
namespace Skopenow\PeopleData\Sources;

use Skopenow\PeopleData\Criteria;
use Skopenow\PeopleData\Clients\ClientInterface;
use Skopenow\PeopleData\OutputModel;
use App\Models\ApiAccount;

class Twitter extends AbstractSearchFetcher
{
    const SOURCE_NAME = "twitter";
    const FETCHER_SOURCE = "twitter";
}
