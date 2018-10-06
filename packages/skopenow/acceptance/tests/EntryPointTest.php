<?php

namespace Skopenow\Acceptance\tests;

use Skopenow\Acceptance\EntryPoint;
use Skopenow\Acceptance\Classes\FlagsCheck;
use Skopenow\Acceptance\Classes\Acceptance;
use App\Models\ResultData;
use App\DataTypes\Name;
use App\DataTypes\Address;
use App\DataTypes\Email;

class EntryPointTest extends \TestCase
{
    public function setup()
    {
        $this->entryPoint = new EntryPoint;
        $this->resultData = new ResultData("https://www.facebook.com/kim.k.douglas2");
        config([
            'state.report_id' => 20144,
            'state.combination_id' => 123,
            'state.combination_level_id' => 1
        ]);
    }
 
    public function testCheckResultAcceptance()
    {
        $flags = 7+0b10000;
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);

        $output = $this->entryPoint->checkAcceptance(
            $this->resultData,
            $flags
        );

        $expected = [
            "acceptance"=>["acceptance"=>true,"reason"=>0],
            "visible"=>["visible"=>true,"reason"=>0]
        ];
        
        $this->assertEquals($expected, $output);
    }

    public function testCheckResultAcceptanceInvisible()
    {
        $flags = 32+5+1024;
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);

        $output = $this->entryPoint->checkAcceptance(
            $this->resultData,
            $flags
        );

        $expected = [
            "acceptance"=>["acceptance"=>true,"reason"=>0],
            "visible"=>["visible"=>false,"reason"=>9216]
        ];
        
        $this->assertEquals($expected, $output);
    }

    public function testCheckResultAcceptanceRejected()
    {
        $flags = 5;
        $this->resultData->username = "rob";
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);

        $output = $this->entryPoint->checkAcceptance(
            $this->resultData,
            $flags
        );

        $expected = [
            "acceptance"=>["acceptance"=>false, "reason"=>33554689],
            "visible"=>["visible"=>false,"reason"=>0]
        ];
        
        $this->assertEquals($expected, $output);
    }
}
