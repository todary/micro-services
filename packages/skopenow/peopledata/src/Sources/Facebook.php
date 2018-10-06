<?php
namespace Skopenow\PeopleData\Sources;

use Skopenow\PeopleData\Criteria;
use Skopenow\PeopleData\Clients\ClientInterface;
use Skopenow\PeopleData\OutputModel;
use App\Models\ApiAccount;

class Facebook extends AbstractSearchFetcher
{
    const SOURCE_NAME = "facebook";
    const FETCHER_SOURCE = "facebook_people_search";
}
