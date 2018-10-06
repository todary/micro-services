<?php

use Skopenow\Acceptance\Classes\FlagsCheck;

class FlagsCheckTest extends TestCase
{
    /**
     * [$flagsCheck description]
     * 
     * @var FlagsCheck
     */
    protected $flagsCheck;

    /**
     * [setup description]
     * 
     * @return void
     */
    public function setup()
    {
        $this->flagsCheck = new FlagsCheck;
    }

    public function testIsInputAgeMatch()
    {
        $this->assertTrue($this->flagsCheck->isMatchAge(4096));
    }

    public function testIsInputAgeNotMatch()
    {
        $this->assertFalse($this->flagsCheck->isMatchAge(0));
    }

    public function testIsInputEmailMatch()
    {
        $this->assertTrue($this->flagsCheck->isInputEmail(262144));
    }

    public function testIsInputEmailNotMatch()
    {
        $this->assertFalse($this->flagsCheck->isInputEmail(0));
    }

    public function testIsInputPhoneMatch()
    {
        $this->assertTrue($this->flagsCheck->isInputPhone(524288));
    }

    public function testIsInputPhoneNotMatch()
    {
        $this->assertFalse($this->flagsCheck->isInputPhone(0));
    }

    public function testIsDistanceInRangMatch()
    {
        $this->assertTrue($this->flagsCheck->isDistanceInRang(0,3));
    }

    public function testIsDistanceInRangNotMatch()
    {
        $this->assertFalse($this->flagsCheck->isDistanceInRang(0,13));
    }

    public function testIsDistanceInRangNulll()
    {
        $this->assertFalse($this->flagsCheck->isDistanceInRang(0,null));
    }
    
    public function testIsNotMatchDataPointMatch()
    {
        $flags = 1024;
        $this->assertFalse($this->flagsCheck->isNotMatchDataPoint(true, $flags));
    }

    /*public function testIsNotMatchDataPointNotMatch()
    {
        $flags = 0;
        $this->assertTrue($this->flagsCheck->isNotMatchDataPoint(false, $flags));
    }*/

    public function testIsNotMatchDataPointNoDataPoint()
    {
        $flags = 2048;
        $this->assertFalse($this->flagsCheck->isNotMatchDataPoint(null, $flags));
    }

    public function testIsMatchNameMatch()
    {
        $this->assertTrue($this->flagsCheck->isMatchName(5));   
    }

    public function testIsMatchNameNotMatch()
    {
        $this->assertFalse($this->flagsCheck->isMatchName(1));  
    }

    public function testIsUniqueNameMatch()
    {
        $this->assertTrue($this->flagsCheck->isUniqueName(8));  
    }

    public function testIsUniqueNameNotMatch()
    {
        $this->assertFalse($this->flagsCheck->isUniqueName(7)); 
    }

    public function testIsMatchLocationSmallCity()
    {
        $this->assertTrue($this->flagsCheck->isMatchLocation(16));  
    }

    public function testIsMatchLocationBigCity()
    {
        $this->assertTrue($this->flagsCheck->isMatchLocation(32));  
    }

    public function testIsMatchLocationPartialCity()
    {
        $this->assertTrue($this->flagsCheck->isMatchLocation(64));  
    }

    public function testIsMatchLocationState()
    {
        $this->assertTrue($this->flagsCheck->isMatchLocation(128)); 
    }

    public function testIsMatchLocationNotMatch()
    {
        $this->assertFalse($this->flagsCheck->isMatchLocation(7));  
    }

    public function testIsSmallCityNotMatch()
    {
        $this->assertFalse($this->flagsCheck->isSmallCity(5));  
    }

    public function testIsBigCityNotMatch()
    {
        $this->assertFalse($this->flagsCheck->isBigCity(5));    
    }

    public function testIsMatchPhoneMatch()
    {
        $this->assertTrue($this->flagsCheck->IsMatchPhone(512));    
    }

    public function testIsMatchPhoneNotMatch()
    {
        $this->assertFalse($this->flagsCheck->IsMatchPhone(5)); 
    }

    public function testIsMatchEmailMatch()
    {
        $this->assertTrue($this->flagsCheck->isMatchEmail(256));    
    }

    public function testIsMatchEmailNotMatch()
    {
        $this->assertFalse($this->flagsCheck->isMatchEmail(5)); 
    }

    public function testIsMatchDataPointMatch()
    {
        $flags = 1024;
        $this->assertTrue($this->flagsCheck->isMatchDataPoint(true, $flags));
    }

    public function testIsMatchDataPointNotMatch()
    {
        $flags=0;
        $this->assertFalse($this->flagsCheck->isMatchDataPoint(false, $flags));
    }

    public function testIsMatchDataPointNull()
    {
        $flags = 0;
        $this->assertFalse($this->flagsCheck->isMatchDataPoint(null, $flags));
    }

    public function testIsMatchMiddleNameMatch()
    {
        $this->assertTrue($this->flagsCheck->isMatchMiddleName(2)); 
    }

    public function testIsMatchMiddleNameNotMatch()
    {
        $this->assertFalse($this->flagsCheck->isMatchMiddleName(1));    
    }
 
    // public function testIsMatchRelativeMatch()
    // {
    //     $this->assertTrue($this->flagsCheck->isRelative(16777216)); 
    // }

    public function testIsMatchRelativeNotMatch()
    {
        $this->assertFalse($this->flagsCheck->isRelative(1));   
    }

    public function testIsMatchOnlyOneMatch()
    {
        $this->assertTrue($this->flagsCheck->isOnlyOne(8192));  
    }

    public function testIsMatchOnlyOneNotMatch()
    {
        $this->assertFalse($this->flagsCheck->isOnlyOne(1));    
    }

    public function testIsMatchVerifiedUsernameMatch()
    {
        $this->assertTrue($this->flagsCheck->isVerifiedUsername(8388608));  
    }

    public function testIsMatchVerifiedUsernameNotMatch()
    {
        $this->assertFalse($this->flagsCheck->isVerifiedUsername(0));   
    }

    public function testRelativeMatch()
    {
        $this->assertTrue($this->flagsCheck->relative(16384));  
    }

    public function testRelativeNotMatch()
    {
        $this->assertFalse($this->flagsCheck->relative(0)); 
    }




}