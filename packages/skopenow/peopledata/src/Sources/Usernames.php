<?php
namespace Skopenow\PeopleData\Sources;

use Skopenow\PeopleData\Criteria;
use Skopenow\PeopleData\Clients\ClientInterface;
use Skopenow\PeopleData\OutputModel;
use App\Models\ApiAccount;

class Usernames extends AbstractSearchFetcher
{
    const SOURCE_NAME = "usernames";
    const FETCHER_SOURCE = "usernames";
}
