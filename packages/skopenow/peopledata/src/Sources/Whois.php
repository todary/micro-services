<?php
namespace Skopenow\PeopleData\Sources;

use Skopenow\PeopleData\Criteria;
use Skopenow\PeopleData\Clients\ClientInterface;
use Skopenow\PeopleData\OutputModel;
use App\Models\ApiAccount;

class Whois extends AbstractSearchFetcher
{
    const SOURCE_NAME = "whois";
    const FETCHER_SOURCE = "whois";
}
