<?php

namespace Skopenow\PeopleDataTest\Sources;

use Skopenow\PeopleData\Sources\Tloxp;
use Skopenow\PeopleData\Criteria;
use Skopenow\PeopleData\OutputModel;
use Skopenow\PeopleData\Clients\SoapClient;

/**
 *
 */
class TloxpTest extends \TestCase
{
    public function testSearch()
    {
        \Cache::delete('Tloxp-257888ec5e9eeab8d70e55a7caac8485');
        \Cache::delete('Tloxp-767ae21ddebe8a3ba676266368e2dcca');

        // inputs
        $one_tloxp_tinput = $this->oneTloxpTestInput();

        // accounts
        $tloxp_account = $this->tloxpAccount();

        // responses
        $tloxp_response = $this->tloxpResponse();

        /*
        $curlSoap = new SoapClient(__DIR__."/../../src/Resources/tloxp.wsdl", [
            "proxy_host" => $tloxp_account->associated_proxy_ip,
            "proxy_port" => $tloxp_account->associated_proxy_port,
            "proxy_login" => $tloxp_account->associated_proxy_username,
            "proxy_password" => $tloxp_account->associated_proxy_password,
            "features" => SOAP_SINGLE_ELEMENT_ARRAYS,
            "trace" => true,
            "connection_timeout" => 6,
        ]);
        */

        $curlSoap = $this->getMockBuilder(SoapClient::class)
            ->setConstructorArgs([__DIR__."/../../src/Resources/tloxp.wsdl"], [
            "proxy_host" => $tloxp_account->associated_proxy_ip,
            "proxy_port" => $tloxp_account->associated_proxy_port,
            "proxy_login" => $tloxp_account->associated_proxy_username,
            "proxy_password" => $tloxp_account->associated_proxy_password,
            "features" => SOAP_SINGLE_ELEMENT_ARRAYS,
            "trace" => true,
            "connection_timeout" => 6,
            ])
            ->setMethods(['call'])
            ->getMock();
        $curlSoap->method('call')->willReturn(file_get_contents(__DIR__.'/../Data/TLOXP-RobDouglas.xml'));

        $search = new Tloxp($one_tloxp_tinput, $tloxp_account, $curlSoap);
        $search->search();
        $actual = $search->getResults();

        $this->assertEquals($tloxp_response, $actual);
    }

    public function oneTloxpTestInput()
    {
        $criteria = [
            "apis"=>["tloxp"],
            "name" => "Rob Douglas",
            "city" => "Oyster Bay",
            "state" => "NY"
        ];
        return new Criteria($criteria);
    }

    public function tloxpResponse()
    {
        $expected = new OutputModel;
        $expected ->report_id = "HPRG-MY3D";
        $expected->link = "Tloxp.api";
        $expected->source = "-";
        $expected->result_rank = 3;
        $expected->first_name = "Robert";
        $expected->last_name = "Douglas";
        $expected->full_name = "Robert Douglas";
        $expected->location = "Oyster Bay, NY";
        $expected->address = "92 Sunken Orchard Ln, Oyster Bay, NY";
        $expected->street = "92 Sunken Orchard Ln";
        $expected->city = "Oyster Bay";
        $expected->state = "NY";
        $expected->zip = "11771";
        $expected->age = "";
        $expected->other_names = [
            [
                "first_name" => "Rob",
                "middle_name" => "",
                "last_name" => "Douglas",
                "full_name" => "Rob Douglas"
            ]
        ];
        $expected->phones = ["5162205847"];
        $expected->emails = [
            "romado12187@aol.com",
            "rdouglas2@yahoo.com",
            "rob.douglas@hotmail.com",
           "roamdo12187@aol.com"
        ] ;
        $expected->relatives = [
            [
                "first_name" => "Barry",
                "middle_name" => "K",
                "last_name" => "Douglas",
                "full_name" => "Barry K Douglas",
            ],
            [
                "first_name" => "Kim",
                "middle_name" => "K",
                "last_name" => "Douglas",
                "full_name" => "Kim K Douglas",
            ],
            [
                "first_name" => "Lauren",
                "middle_name" => "B",
                "last_name" => "Douglas",
                "full_name" => "Lauren B Douglas",
            ],
            [
                "first_name" => "Marc",
                "middle_name" => "Franklin",
                "last_name" => "Douglas",
                "full_name" => "Marc Franklin Douglas",
            ]
        ];

        $expected->addresses = [
            [
                "street" => "333 E 49th St Apt 2r",
                "city" => "New York",
                "state" => "NY",
                "location" => "New York, NY",
                "zip" => "10017",
                "address" => "333 E 49th St Apt 2r, New York, NY"
            ]
        ];
        $expected = [$expected];
        return $expected;
    }

    public function tloxpAccount()
    {
        $account = new \App\Models\ApiAccount();

        // $account = getApiAccount('tloxp');

        return $account;
    }
}
