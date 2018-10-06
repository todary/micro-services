<?php

namespace Skopenow\Acceptance\tests\Save;

use Skopenow\Acceptance\Classes\FlagsCheck;
use Skopenow\Acceptance\Classes\Acceptance;
use Skopenow\Acceptance\Classes\Banned;
use Skopenow\Reports\EnteryPoint as Report;
use App\Models\ResultData;
use App\DataTypes\Name;
use App\DataTypes\Address;
use App\DataTypes\Email;

class AcceptanceTest extends \TestCase
{
    protected $reportObj;
    protected $flagsCheck;
    protected $bannedSource;
    protected $acceptanceObj;
    protected $scoringFlags;
    protected $resultData;

    public function setup()
    {
        $this->flagsCheck = new FlagsCheck;
        $banned = array((object)["domain" => "tests","url"=>"https://ar.tests.com.org/"]);
        $userBanned = array((object)["domain" => "fakeskopenow.com","url"=>"https://fakeskopenow.com"]);
        $this->bannedSource = \Mockery::mock(Banned::class);
        $this->bannedSource->shouldReceive("getBannedDomains")->andReturn($banned);
        $this->bannedSource->shouldReceive("getUserBanned")->andReturn($userBanned);
        $reportObj = \Mockery::mock(Report::class);
        $reportObj->shouldReceive("isVerifiedDataPoints")->andReturn(false);

        $this->acceptanceObj = new Acceptance($this->flagsCheck, $this->bannedSource, $reportObj);
        $this->scoringFlags = loadData("scoringFlags");
        $this->resultData = new ResultData("https://plus.google.com/RobDouglas3");
    }

    public function testMatchDataPoint()
    {
        config(["state.report_id"=>61637]);

        $flags = 0;
        $this->resultData->setIsProfile(true);
        $education = new \ArrayIterator(["Programming"]);
        $this->resultData->setEducation($education);

        $reportObj = \Mockery::mock(Report::class);
        $reportObj->shouldReceive("isVerifiedDataPoints")->andReturn(true);
        $acceptanceObj = new Acceptance($this->flagsCheck, $this->bannedSource, $reportObj);

        $output = $acceptanceObj->checkAccptance($this->resultData, $flags);
        
        $expected = [
            "acceptance" => false,
            "reason" => 8
        ];
        $this->assertEquals($expected, $output);
    }

    public function testOnlyOneDefault()
    {
        $flags = 8192;
        $this->resultData->setIsProfile(true);
        $output = $this->acceptanceObj->checkAccptance($this->resultData, $flags);
        
        $expected = [
            "acceptance" => false,
            "reason" => 404799488
        ];
        $this->assertEquals($expected, $output);
    }

    public function testOnlyOneInputUserName()
    {
        $flags = $this->scoringFlags["onlyOne"]["value"]+$this->scoringFlags["input_un"]["value"];
        $this->resultData->setIsProfile(true);
        $output = $this->acceptanceObj->checkAccptance($this->resultData, $flags);
        
        $expected = [
            "acceptance" => true,
            "reason" => 0
        ];
        $this->assertEquals($expected, $output);
    }

    public function testOnlyOneInputPhone()
    {
        $flags = $this->scoringFlags["onlyOne"]["value"]+$this->scoringFlags["input_ph"]["value"];
        $this->resultData->setIsProfile(true);
        $output = $this->acceptanceObj->checkAccptance($this->resultData, $flags);
        
        $expected = [
            "acceptance" => true,
            "reason" => 0
        ];
        $this->assertEquals($expected, $output);
    }

    public function testOnlyOneInputEmail()
    {
        $flags = $this->scoringFlags["onlyOne"]["value"]+$this->scoringFlags["input_em"]["value"];
        $this->resultData->setIsProfile(true);
        $output = $this->acceptanceObj->checkAccptance($this->resultData, $flags);
        
        $expected = [
            "acceptance" => true,
            "reason" => 0
        ];
        $this->assertEquals($expected, $output);
    }

    public function testOnlyOneInputName()
    {
        $flags = $this->scoringFlags["onlyOne"]["value"]+$this->scoringFlags["input_name"]["value"];
        $this->resultData->setIsProfile(true);
        $output = $this->acceptanceObj->checkAccptance($this->resultData, $flags);
        
        $expected = [
            "acceptance" => true,
            "reason" => 0
        ];
        $this->assertEquals($expected, $output);
    }

    public function testUrlArWikipedia()
    {
        $flags = 0;
        $this->resultData->setIsProfile(false);
        $this->resultData->setUrl("(add_url)https://ar.wikipedia.org/");

        $output = $this->acceptanceObj->checkAccptance(
            $this->resultData,
            $flags
        );

        $expected = [
            "acceptance" => false,
            "reason" => 131072
        ];

        $this->assertEquals($expected, $output);
    }

    public function testBannedURL()
    {
        $flags = 0;
        $this->resultData->setIsProfile(true);
        $this->resultData->setUrl("https://ar.tests.com.org/");

        $output = $this->acceptanceObj->checkAccptance(
            $this->resultData,
            $flags
        );

        $expected = [
            "acceptance" => false,
            "reason" => 65536
        ];

        $this->assertEquals($expected, $output);
    }

    public function testUserBannedURL()
    {
        $flags = 0;
        $this->resultData->url = "https://fakeskopenow.com/rob";
        $this->resultData->setIsProfile(true);
        
        $output = $this->acceptanceObj->checkAccptance(
            $this->resultData,
            $flags
        );
        
        $expected = [
            "acceptance" => false,
            "reason" => 65536
        ];

        $this->assertEquals($expected, $output);
    }

    public function testEmptyUrl()
    {
        $flags = 0;
        $this->resultData->url = "";
        $output = $this->acceptanceObj->checkAccptance(
            $this->resultData,
            $flags
        );

        $expected = [
            "acceptance" => false,
            "reason" => 67108864
        ];

        $this->assertEquals($expected, $output);
    }

    public function testIsManual()
    {
        $flags = 0;
        $this->resultData->setIsManual(true);
        $output = $this->acceptanceObj->checkAccptance(
            $this->resultData,
            $flags
        );

        $expected = [
            "acceptance" => true,
            "reason" => 0
        ];

        $this->assertEquals($expected, $output);
    }

    public function testIsMatchNameIsMatchMiddleName()
    {
        $flags = 7;
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);

        $output = $this->acceptanceObj->checkAccptance(
            $this->resultData,
            $flags
        );
        $expected = [
            "acceptance" => true,
            "reason" => 0
        ];
        $this->assertEquals($expected, $output);
    }

    public function testIsMatchNameIsMatchEmail()
    {
        $flags = 5+256+64;
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);
        $education = new \ArrayIterator(["Programming"]);
        $this->resultData->setEducation($education);

        $output = $this->acceptanceObj->checkAccptance(
            $this->resultData,
            $flags
        );

        $expected = [
            "acceptance" => true,
            "reason" => 0
        ];
        $this->assertEquals($expected, $output);
    }

    public function testIsMatchNameIsMatchPhone()
    {
        $flags = 5+512+64;
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);
        $education = new \ArrayIterator(["Programming"]);
        $this->resultData->setEducation($education);

        $output = $this->acceptanceObj->checkAccptance(
            $this->resultData,
            $flags
        );

        $expected = [
            "acceptance" => true,
            "reason" => 0
        ];
        $this->assertEquals($expected, $output);
    }

    public function testIsMatchUniqueName()
    {
        $flags = 5+8+64;
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);

        $output = $this->acceptanceObj->checkAccptance(
            $this->resultData,
            $flags
        );

        $expected = [
            "acceptance" => true,
            "reason" => 0
        ];
        $this->assertEquals($expected, $output);
    }

    public function testIsNotUniqueNameAndBigCity()
    {
        $flags = 5+32+64;
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);

        $output = $this->acceptanceObj->checkAccptance(
            $this->resultData,
            $flags
        );

        $this->assertTrue($output["acceptance"]);
    }

    public function testIsNotUniqueNameAndSmallCity()
    {
        $flags = 5+16+64;
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);

        $output = $this->acceptanceObj->checkAccptance(
            $this->resultData,
            $flags
        );

        $this->assertTrue($output["acceptance"]);
    }

    public function testIsNotUniqueNameAndPartialCity()
    {
        $flags = 5+64;
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);

        $output = $this->acceptanceObj->checkAccptance(
            $this->resultData,
            $flags
        );

        $this->assertTrue($output["acceptance"]);
    }

    public function testIsNotUniqueNameAndState()
    {
        $flags = 5+128;
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);

        $output = $this->acceptanceObj->checkAccptance(
            $this->resultData,
            $flags
        );

        $this->assertTrue($output["acceptance"]);
    }

    public function testIsNotUniqueName()
    {
        $flags = 5+64;
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);

        $output = $this->acceptanceObj->checkAccptance(
            $this->resultData,
            $flags
        );

        $expected = [
            "acceptance" => true,
            "reason" => 0
        ];
        $this->assertEquals($expected, $output);
    }

    public function testMatchNameMatchLocationMatchUserName()
    {
        $flags = 5+16+32768;
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);

        $output = $this->acceptanceObj->checkAccptance(
            $this->resultData,
            $flags
        );

        $this->assertTrue($output["acceptance"]);
    }

    public function testMatchNameRelative()
    {
        $flags = 5+16384+64;
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);

        $output = $this->acceptanceObj->checkAccptance(
            $this->resultData,
            $flags
        );

        $this->assertTrue($output["acceptance"]);
    }

    public function testIsMatchNameIsMatchVerfidUserName()
    {
        $flags = 5+8388608+64;
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);
        
        $output = $this->acceptanceObj->checkAccptance(
            $this->resultData,
            $flags
        );

        $expected = [
            "acceptance" => true,
            "reason" => 0
        ];
        $this->assertEquals($expected, $output);
    }

    public function testIsMatchNameIsMatchInputUserName()
    {
        $flags = 5+1048576+64;
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);
        
        $output = $this->acceptanceObj->checkAccptance(
            $this->resultData,
            $flags
        );

        $expected = [
            "acceptance" => true,
            "reason" => 0
        ];
        $this->assertEquals($expected, $output);
    }

    public function testIsMatchNameDataPoint()
    {
        $flags = 5+1024+64;
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);
        $this->acceptanceObj->isdataPoint = true;
        $output = $this->acceptanceObj->checkAccptance(
            $this->resultData,
            $flags
        );

        $expected = [
            "acceptance" => true,
            "reason" => 0
        ];
        $this->assertEquals($expected, $output);
    }

    public function testNotMatchNameInputPhone()
    {
        $flags = 524288+64;
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);
        
        $output = $this->acceptanceObj->checkAccptance(
            $this->resultData,
            $flags
        );

        $expected = [
            "acceptance" => true,
            "reason" => 0
        ];
        $this->assertEquals($expected, $output);
    }

    public function testNotMatchNameInputEmail()
    {
        $flags = 262144+64;
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);
        
        $output = $this->acceptanceObj->checkAccptance(
            $this->resultData,
            $flags
        );

        $expected = [
            "acceptance" => true,
            "reason" => 0
        ];
        $this->assertEquals($expected, $output);
    }

    public function testNotMatchName()
    {
        $flags = 0+64;
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);
        
        $output = $this->acceptanceObj->checkAccptance(
            $this->resultData,
            $flags
        );

        $expected = [
            "acceptance" => false,
            "reason" => 49153
        ];
        $this->assertEquals($expected, $output);
    }

    public function testNotMatchLocationNotMatchName()
    {
        $flags = 0;
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);
        
        $output = $this->acceptanceObj->checkAccptance(
            $this->resultData,
            $flags
        );

        $expected = [
            "acceptance" => false,
            "reason" => 33554689
        ];
        $this->assertEquals($expected, $output);
    }

    public function testNotFoundNameNotMatchMidelNameNotMatchLocation()
    {
        $flags = $this->scoringFlags["name_not_found"]["value"];
        
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);
        
        $output = $this->acceptanceObj->checkAccptance(
            $this->resultData,
            $flags
        );

        $expected = [
            "acceptance" => false,
            "reason" => 35655808
        ];

        $this->assertEquals($expected, $output);
    }

    public function testNotFoundNameMatchInputUserName()
    {
        $flags = 1048576+64+$this->scoringFlags["name_not_found"]["value"];
        
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);
        
        $output = $this->acceptanceObj->checkAccptance(
            $this->resultData,
            $flags
        );

        $this->assertTrue($output["acceptance"]);
    }

    public function testNotFoundNameMatchUserName()
    {
        $flags = 32768+$this->scoringFlags["name_not_found"]["value"]+64;
        
        $this->resultData->setIsProfile(true);
        
        $output = $this->acceptanceObj->checkAccptance(
            $this->resultData,
            $flags
        );

        $this->assertTrue($output["acceptance"]);
    }

    public function testNotFoundNameUserNameVerfid()
    {
        $flags = 8454144+$this->scoringFlags["name_not_found"]["value"]+64;
        
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);
        
        $output = $this->acceptanceObj->checkAccptance(
            $this->resultData,
            $flags
        );

        $this->assertTrue($output["acceptance"]);
    }

    public function testNotFoundNameNotMatchUserName()
    {
        $flags = $this->scoringFlags["name_not_found"]["value"]+64;
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);

        $output = $this->acceptanceObj->checkAccptance(
            $this->resultData,
            $flags
        );

        $expected = [
            "acceptance" => false,
            "reason" => 2101376
        ];
        $this->assertEquals($expected, $output);
    }

    public function testNotFoundNameNotMatchUserNameLocationNotFound()
    {
        $flags = $this->scoringFlags["name_not_found"]["value"]+$this->scoringFlags["loc_not_found"]["value"];
        
        $this->resultData->setIsProfile(true);
        
        $output = $this->acceptanceObj->checkAccptance(
            $this->resultData,
            $flags
        );

        $expected = [
            "acceptance" => false,
            "reason" => 2101378
        ];
        $this->assertEquals($expected, $output);
    }

    public function testNotFoundNameNotFoundUserNameUniqueName()
    {
        $flags = 8+$this->scoringFlags["name_not_found"]["value"];
        $resultData = new ResultData("facebook.com");
        $resultData->setIsProfile(true);
        
        $output = $this->acceptanceObj->checkAccptance(
            $resultData,
            $flags
        );

        $expected = [
            "acceptance" => true,
            "reason" => 0
        ];
        
        $this->assertEquals($expected, $output);
    }

    public function testNotFoundNameNotFoundUserNameNotUniqueName()
    {
        $flags = $this->scoringFlags["name_not_found"]["value"];
        $resultData = new ResultData("facebook.com");
        $resultData->setIsProfile(true);
        
        $output = $this->acceptanceObj->checkAccptance(
            $resultData,
            $flags
        );

        $expected = [
            "acceptance" => false,
            "reason" => 2111488
        ];
        
        $this->assertEquals($expected, $output);
    }

    public function testNotFoundLocationMatchNameRelative()
    {
        $flags = 5+16384+$this->scoringFlags["loc_not_found"]["value"];
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->setIsProfile(true);

        $output = $this->acceptanceObj->checkAccptance(
            $this->resultData,
            $flags
        );

        $this->assertTrue($output["acceptance"]);
    }

    public function testNotFoundLocationMatchNameMatchUserName()
    {
        $flags = 5+32768+$this->scoringFlags["loc_not_found"]["value"];
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->setIsProfile(true);

        $output = $this->acceptanceObj->checkAccptance(
            $this->resultData,
            $flags
        );

        $this->assertTrue($output["acceptance"]);
    }

    public function testNotFoundLocationMatchNameNotMatchUserName()
    {
        $flags = 5+$this->scoringFlags["loc_not_found"]["value"];
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->setIsProfile(true);

        $output = $this->acceptanceObj->checkAccptance(
            $this->resultData,
            $flags
        );
        $ecpected = [
            "acceptance" => true,
            "reason" => 0
        ];
        $this->assertEquals($ecpected, $output);
    }

    public function testNotFoundLocationMatchNameNotFoundUserName()
    {
        $flags = 5+$this->scoringFlags["loc_not_found"]["value"];
        $resultData = new ResultData("facebook");
        $resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $resultData->setIsProfile(true);

        $output = $this->acceptanceObj->checkAccptance(
            $resultData,
            $flags
        );
        $ecpected = [
            "acceptance" => true,
            "reason" => 0
        ];
        $this->assertEquals($ecpected, $output);
    }

    public function testNotFoundLocationMatchNameMatchInputUserName()
    {
        $flags = 5+1048576+$this->scoringFlags["loc_not_found"]["value"];
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->setIsProfile(true);

        $output = $this->acceptanceObj->checkAccptance(
            $this->resultData,
            $flags
        );
        $ecpected = [
            "acceptance" => true,
            "reason" => 0
        ];
        $this->assertEquals($ecpected, $output);
    }

    public function testNotFoundLocationMatchNameMatchVerifiedUserName()
    {
        $flags = 5+8388608+$this->scoringFlags["loc_not_found"]["value"];
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->setIsProfile(true);

        $output = $this->acceptanceObj->checkAccptance(
            $this->resultData,
            $flags
        );
        $ecpected = [
            "acceptance" => true,
            "reason" => 0
        ];
        $this->assertEquals($ecpected, $output);
    }

    public function testNotFoundLocationMatchNameMatchAge()
    {
        $flags = 5+0b000000000000000001000000000000+$this->scoringFlags["loc_not_found"]["value"];
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->setIsProfile(true);

        $output = $this->acceptanceObj->checkAccptance(
            $this->resultData,
            $flags
        );
        $ecpected = [
            "acceptance" => true,
            "reason" => 0
        ];
        $this->assertEquals($ecpected, $output);
    }

    public function testNotFoundLocationNotMatchName()
    {
        $flags = $this->scoringFlags["loc_not_found"]["value"];
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->setIsProfile(true);

        $output = $this->acceptanceObj->checkAccptance(
            $this->resultData,
            $flags
        );
        $ecpected = [
            "acceptance" => false,
            "reason" => 3
        ];
        $this->assertEquals($ecpected, $output);
    }
}
