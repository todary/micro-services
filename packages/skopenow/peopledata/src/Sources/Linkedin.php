<?php
namespace Skopenow\PeopleData\Sources;

use Skopenow\PeopleData\Criteria;
use Skopenow\PeopleData\Clients\ClientInterface;
use Skopenow\PeopleData\OutputModel;
use App\Models\ApiAccount;

class Linkedin extends AbstractSearchFetcher
{
    const SOURCE_NAME = "linkedin";
    const FETCHER_SOURCE = "linkedin";
}
