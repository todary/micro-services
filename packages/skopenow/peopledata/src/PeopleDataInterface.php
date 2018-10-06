<?php
namespace Skopenow\PeopleData;

interface PeopleDataInterface
{
    public function __construct(Criteria $criteria);
    public function search() : SearchResults;
}
