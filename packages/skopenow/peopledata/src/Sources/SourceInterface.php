<?php
namespace Skopenow\PeopleData\Sources;

use App\Models\ApiAccount;
use Skopenow\PeopleData\Criteria;
use Skopenow\PeopleData\Clients\ClientInterface;

interface SourceInterface
{
    public function __construct(Criteria $criteria, ApiAccount $account, ClientInterface $client);
    public function search();
}
