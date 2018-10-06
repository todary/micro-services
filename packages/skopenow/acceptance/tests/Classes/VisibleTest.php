<?php

namespace Skopenow\Acceptance\tests\Save;

use Skopenow\Acceptance\Classes\FlagsCheck;
use Skopenow\Acceptance\Classes\Visible;
use App\Models\ResultData;
use App\DataTypes\Name;
use App\DataTypes\Address;
use App\DataTypes\Email;
use App\DataTypes\Work;
use App\DataTypes\School;

class VisibleTest extends \TestCase
{
    protected $visibleObj;
    protected $resultData;
    protected $scoringFlags;

    public function setup()
    {
        //config(["state.report_id"=>61637]);
        $flagsCheck = new FlagsCheck;
        $reportObj = \Mockery::mock(Report::class);
        $reportObj->shouldReceive("isVerifiedDataPoints")->andReturn(false);
        $this->visibleObj = new Visible($flagsCheck, $reportObj);
        $this->resultData = new ResultData("");
        $this->scoringFlags = loadData("scoringFlags");
    }

    public function testIsDatapoint()
    {
        $flags = 0b000000000000000000100000000000+0b000000000000000000010000000000;
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->setIsProfile(true);
        $work = [
            "company" => "Inertia Lab",
            "image" => "https://d1qb2nb5cznatu.cloudfront.net/startups/i/520786-d3e4fd5a1581cbc9b4eac46176204005-thumb_jpg.jpg?buster=1414460153",
            "title" => "",
            "start" => 2011,
            "end" => 2014
        ];
        $this->resultData->addExperience(Work::create($work, "facebook"));

        $school = [
            "name" => "Vanderbilt University",
            "degree" => "",
            "start" => "",
            "end" => "",
            "image" => "https://d1qb2nb5cznatu.cloudfront.net/new_tags/i/71805-2a754387447da2867b4a667eb1e71572-medium.png?buster=1440132757",
        ];
        $this->resultData->addEducation(School::create($school, "facebook"));

        $flagsCheck = new FlagsCheck;
        $reportObj = \Mockery::mock(Report::class);
        $reportObj->shouldReceive("isVerifiedDataPoints")->andReturn(true);
        $this->visibleObj = new Visible($flagsCheck, $reportObj);

        $output = $this->visibleObj->checkVisible(
            $this->resultData,
            $flags
        );
        $this->assertFalse($output["visible"]);
    }

    public function testNoFlag()
    {
        $flags = 0;
        $this->resultData->setIsProfile(true);

        $output = $this->visibleObj->checkVisible(
            $this->resultData,
            $flags
        );
        $this->assertFalse($output["visible"]);
    }

    public function testNotProfile()
    {
        $flags = 0;
        $this->resultData->setIsProfile(false);

        $output = $this->visibleObj->checkVisible(
            $this->resultData,
            $flags
        );
        $this->assertTrue($output["visible"]);
    }

    public function testDefault()
    {
        $flags = 5;
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);

        $output = $this->visibleObj->checkVisible(
            $this->resultData,
            $flags
        );

        $this->assertFalse($output["visible"]);
    }

    public function testIsMatchNameRelativeMainRelative()
    {
        $flags = 5+0b000000000000000100000000000000+$this->scoringFlags["rltvWithMain"]["value"];
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);
        
        $output = $this->visibleObj->checkVisible(
            $this->resultData,
            $flags
        );

        $this->assertTrue($output["visible"]);
    }

    public function testIsMatchNameRelativeOnlyOne()
    {
        $flags = 5+0b000000000000000100000000000000+0b000000000000000010000000000000;
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);
        
        $output = $this->visibleObj->checkVisible(
            $this->resultData,
            $flags
        );

        $this->assertTrue($output["visible"]);
    }

    public function testIsMatchNameRelativeNotOnlyOne()
    {
        $flags = 5+0b000000000000000100000000000000;
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);
        
        $output = $this->visibleObj->checkVisible(
            $this->resultData,
            $flags
        );

        $expected = [
          "visible" => false,
          "reason" => 4194304
        ];
        $this->assertEquals($expected, $output);
    }

    public function testIsMatchNamePeopleUserNameMatchLocation()
    {
        $flags = 5+$this->scoringFlags["people_un"]["value"]+$this->scoringFlags["un"]["value"]+$this->scoringFlags["pct"]["value"];
        $this->resultData->username = "rob";
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);
        
        $output = $this->visibleObj->checkVisible(
            $this->resultData,
            $flags
        );

        $expected = [
          "visible" => true,
          "reason" => 0
        ];
        $this->assertEquals($expected, $output);
    }

    public function testIsMatchUniqeNameUserNameMatchLocation()
    {
        $flags = 5+$this->scoringFlags["un"]["value"]+$this->scoringFlags["unq_name"]["value"]+$this->scoringFlags["pct"]["value"];
        $this->resultData->username = "rob";
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        //$this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);
        
        $output = $this->visibleObj->checkVisible(
            $this->resultData,
            $flags
        );

        $expected = [
          "visible" => true,
          "reason" => 0
        ];
        $this->assertEquals($expected, $output);
    }

    public function testIsMatchCommenNameUserNameMatchLocation()
    {
        $flags = 5+$this->scoringFlags["un"]["value"]+$this->scoringFlags["pct"]["value"];
        $this->resultData->username = "rob";
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);
        
        $output = $this->visibleObj->checkVisible(
            $this->resultData,
            $flags
        );

        $expected = [
          "visible" => false,
          "reason" => 536879104
        ];
        $this->assertEquals($expected, $output);
    }

    public function testIsMatchNameIsMatchDataPoint()
    {
        $flags = 5+1024;
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);
        $education = new \ArrayIterator(["Programming"]);
        $this->resultData->setEducation($education);

        $output = $this->visibleObj->checkVisible(
            $this->resultData,
            $flags
        );

        $this->assertTrue($output["visible"]);
    }

    public function testIsMatchNameIsMatchEmail()
    {
        $flags = 5+256;
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);
        $education = new \ArrayIterator(["Programming"]);
        $this->resultData->setEducation($education);

        $output = $this->visibleObj->checkVisible(
            $this->resultData,
            $flags
        );

        $this->assertTrue($output["visible"]);
    }

    public function testIsMatchNameIsMatchPhone()
    {
        $flags = 5+512;
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);
        $education = new \ArrayIterator(["Programming"]);
        $this->resultData->setEducation($education);

        $output = $this->visibleObj->checkVisible(
            $this->resultData,
            $flags
        );

        $this->assertTrue($output["visible"]);
    }

    public function testIsMatchNameIsMatchVerfidUserName()
    {
        $flags = 5+0b000000100000000000000000000000;
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);
        
        $output = $this->visibleObj->checkVisible(
            $this->resultData,
            $flags
        );

        $this->assertTrue($output["visible"]);
    }
    
    public function testIsUniqueNameAndBigCity()
    {
        $flags = 5+32;
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);
        $education = new \ArrayIterator(["Programming"]);
        $this->resultData->setEducation($education);

        $output = $this->visibleObj->checkVisible(
            $this->resultData,
            $flags
        );

        $this->assertFalse($output["visible"]);
    }

    public function testCommenNameAndSmallCity()
    {
        $flags = 5+0b000000000000000000000000010000;
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);
       
        $output = $this->visibleObj->checkVisible(
            $this->resultData,
            $flags
        );
        
        $this->assertTrue($output["visible"]);
    }

    public function testMatchUniqueName()
    {
        $flags = 13;
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);
        $education = new \ArrayIterator(["Programming"]);
        $this->resultData->setEducation($education);

        $output = $this->visibleObj->checkVisible(
            $this->resultData,
            $flags
        );

        $this->assertTrue($output["visible"]);
    }

    public function testNotMatchNameMatchInputEmail()
    {
        $flags = 0b000000000001000000000000000000;
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);
        
        $output = $this->visibleObj->checkVisible(
            $this->resultData,
            $flags
        );

        $this->assertTrue($output["visible"]);
    }

    public function testNotMatchNameMatchInputPhone()
    {
        $flags = 0b000000000010000000000000000000;
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);
        
        $output = $this->visibleObj->checkVisible(
            $this->resultData,
            $flags
        );

        $this->assertTrue($output["visible"]);
    }

    public function testNotFoundNameUserNameVerfid()
    {
        $flags = 8454144+$this->scoringFlags["name_not_found"]["value"];
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);
        $this->resultData->username = "rob";

        $output = $this->visibleObj->checkVisible(
            $this->resultData,
            $flags
        );

        $this->assertTrue($output["visible"]);
    }

    public function testNotFoundNameUserNameNotSentUniqueName()
    {
        $flags = 8+$this->scoringFlags["name_not_found"]["value"];
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);
        

        $output = $this->visibleObj->checkVisible(
            $this->resultData,
            $flags
        );

        $this->assertFalse($output["visible"]);
    }

    public function testNotFoundNameUserNameNotSentNoUniqueName()
    {
        $flags = 1+$this->scoringFlags["name_not_found"]["value"];
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);
        
        $output = $this->visibleObj->checkVisible(
            $this->resultData,
            $flags
        );

        $expected = [
            "reason" => 4224,
            "visible" => false
        ];

        $this->assertEquals($expected, $output);
    }

    public function testNotFoundNameUserName()
    {
        $flags = $this->scoringFlags["un"]["value"]+$this->scoringFlags["pct"]["value"]+$this->scoringFlags["name_not_found"]["value"];
        $this->resultData->username = "rob";
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);
        
        $output = $this->visibleObj->checkVisible(
            $this->resultData,
            $flags
        );

        $expected = [
          "visible" => false,
          "reason" => 4096
        ];
        $this->assertEquals($expected, $output);
    }

    public function testNotFoundNameUserNameMatchLocation()
    {
        $flags = 32768+64+16+$this->scoringFlags["name_not_found"]["value"];
        $this->resultData->username = "rob";
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);
        
        $output = $this->visibleObj->checkVisible(
            $this->resultData,
            $flags
        );

        $expected = [
          "visible" => false,
          "reason" => 4096
        ];
        $this->assertEquals($expected, $output);
    }

    public function testNotFoundLocationMatchNameRelativeMainRelative()
    {
        $flags = 5+$this->scoringFlags["rltv"]["value"]+$this->scoringFlags["rltvWithMain"]["value"]+$this->scoringFlags["loc_not_found"]["value"];
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->setIsProfile(true);
        
        $output = $this->visibleObj->checkVisible(
            $this->resultData,
            $flags
        );

        $this->assertTrue($output["visible"]);
    }

    public function testNotFoundLocationMatchNameRelativeOnlyOne()
    {
        $flags = 5+0b000000000000000100000000000000+0b000000000000000010000000000000+4294967296;
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->setIsProfile(true);
        
        $output = $this->visibleObj->checkVisible(
            $this->resultData,
            $flags
        );

        $this->assertTrue($output["visible"]);
    }

    public function testNotFoundLocationMatchNameRelativeNotOnlyOne()
    {
        $flags = 5+0b000000000000000100000000000000+$this->scoringFlags["loc_not_found"]["value"];
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->setIsProfile(true);
        
        $output = $this->visibleObj->checkVisible(
            $this->resultData,
            $flags
        );

        $expected = [
          "visible" => false,
          "reason" => 4194304
        ];
        $this->assertEquals($expected, $output);
    }

    public function testNotFoundLocationMatchNameMatchUserName()
    {
        $flags = 5+$this->scoringFlags["un"]["value"]+$this->scoringFlags["loc_not_found"]["value"];
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->setIsProfile(true);
        $this->resultData->username="rob";
        
        $output = $this->visibleObj->checkVisible(
            $this->resultData,
            $flags
        );

        $expected = [
          "visible" => false,
          "reason" => 536879104
        ];
        $this->assertEquals($expected, $output);
    }

    public function testNotFoundLocationMatchNameMatchAge()
    {
        $flags = 5+$this->scoringFlags["age"]["value"]+$this->scoringFlags["loc_not_found"]["value"];
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->setIsProfile(true);
        
        $output = $this->visibleObj->checkVisible(
            $this->resultData,
            $flags
        );

        $expected = [
          "visible" => true,
          "reason" => 0
        ];
        $this->assertEquals($expected, $output);
    }

    public function testNotFoundLocationNotMatchName()
    {
        $flags = $this->scoringFlags["loc_not_found"]["value"];
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->setIsProfile(true);
        
        $output = $this->visibleObj->checkVisible(
            $this->resultData,
            $flags
        );

        $expected = [
          "visible" => false,
          "reason" => 0
        ];
        $this->assertEquals($expected, $output);
    }

    public function testNotFoundLocationPeopleUserName()
    {
        $flags = 5+$this->scoringFlags["people_un"]["value"]+$this->scoringFlags["un"]["value"]+$this->scoringFlags["loc_not_found"]["value"];
        $this->resultData->username = "rob";
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);
        
        $output = $this->visibleObj->checkVisible(
            $this->resultData,
            $flags
        );

        $expected = [
          "visible" => true,
          "reason" => 0
        ];
        $this->assertEquals($expected, $output);
    }

    public function testNotFoundLocationUniqueName()
    {
        $flags = 5+$this->scoringFlags["unq_name"]["value"]+$this->scoringFlags["un"]["value"]+$this->scoringFlags["loc_not_found"]["value"];
        $this->resultData->username = "rob";
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);
        
        $output = $this->visibleObj->checkVisible(
            $this->resultData,
            $flags
        );

        $expected = [
          "visible" => true,
          "reason" => 0
        ];
        $this->assertEquals($expected, $output);
    }

    public function testNotFoundLocationCommenName()
    {
        $flags = 5+$this->scoringFlags["un"]["value"]+$this->scoringFlags["loc_not_found"]["value"];
        $this->resultData->username = "rob";
        $this->resultData->addName(Name::create(["full_name"=>"rob M douglas"], "facebook"));
        $this->resultData->addLocation(Address::create(["full_address"=>"Milwaukee, Wisconsin"], "facebook"));
        $this->resultData->setIsProfile(true);
        
        $output = $this->visibleObj->checkVisible(
            $this->resultData,
            $flags
        );

        $expected = [
          "visible" => false,
          "reason" => 536879104
        ];
        $this->assertEquals($expected, $output);
    }
}
