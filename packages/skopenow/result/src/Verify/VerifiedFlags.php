<?php

namespace Skopenow\Result\Verify;

/**
 * Returns the verified flags  .
 *
 * @author Ahmed Samir
 */

class VerifiedFlags {
    
    protected $scoringFlags = array() ;


    public function __construct() 
    {
        $this->scoringFlags = loadData("scoringFlags") ;
    }
    
    public function getNeverDeletedFlags()
    {
        $nameFlags = $this->scoringFlags['fn']['value'] | $this->scoringFlags['ln']['value'] ;
        $flags = array(
            "email"     =>  $this->scoringFlags['em']['value'] ,
            "phone"     =>  $this->scoringFlags['ph']['value'] ,
            "onlyOne"   =>  $this->scoringFlags['onlyOne']['value'] ,
            "usernameVerified+name" =>  $this->scoringFlags['verified_un']['value'] | $nameFlags ,
            "input_un"  =>  $this->scoringFlags['input_un']['value'] ,
            "unique+small"  =>  $this->scoringFlags['unq_name']['value'] | $this->scoringFlags['exct-sm']['value'] ,
            "people_un+name" => $this->scoringFlags['people_un']['value'] | $nameFlags,
        );

        return $flags;
    }

    public function getNeverDeletedIdentities()
    {
        $flags = [
            ['em'],
            ['ph'],
            ['onlyOne'],
            ['verified_un', 'fn', 'ln'],
            ['input_un', 'fn', 'ln'],
            ['unq_name', 'exct-sm'],
            ['people_un', 'fn', 'ln']
        ];

        return $flags;
    }

    public function getVerifiedFlags()
    {
        $nameFlags = $this->scoringFlags['fn']['value'] | $this->scoringFlags['ln']['value'] ;
        $flags = array(
            "email+name"    =>  $this->scoringFlags['em']['value'] | $nameFlags ,
            "phone+name"    =>  $this->scoringFlags['ph']['value'] | $nameFlags ,
            "f+m+l"         =>  $this->scoringFlags['mn']['value'] | $nameFlags ,
            "name+sm+rltv"  =>  $this->scoringFlags['rltvWithMain']['value'] | $this->scoringFlags['exct-sm']['value'] | $nameFlags ,
            "name+bg+rltv"  =>  $this->scoringFlags['rltvWithMain']['value'] | $this->scoringFlags['exct-bg']['value'] | $nameFlags ,
            "name+pct+rltv" =>  $this->scoringFlags['rltvWithMain']['value'] | $this->scoringFlags['pct']['value'] | $nameFlags ,
            "name+st+rltv"  =>  $this->scoringFlags['rltvWithMain']['value'] | $this->scoringFlags['st']['value'] | $nameFlags ,
            "name+company"  =>  $this->scoringFlags['input_cm']['value'] | $nameFlags ,
            "name+school"   =>  $this->scoringFlags['input_sc']['value'] | $nameFlags ,
            "name+address"  =>  $this->scoringFlags['input_addr']['value'] | $nameFlags ,
            "name+username" =>  $this->scoringFlags['input_un']['value'] | $nameFlags ,
            "name+sm+age"   =>  $this->scoringFlags['age']['value'] | $this->scoringFlags['exct-sm']['value'] | $nameFlags ,
            "name+bg+age"   =>  $this->scoringFlags['age']['value'] | $this->scoringFlags['exct-bg']['value'] | $nameFlags ,
            "name+pct+age"  =>  $this->scoringFlags['age']['value'] | $this->scoringFlags['pct']['value'] | $nameFlags ,
            "name+st+age"   =>  $this->scoringFlags['age']['value'] | $this->scoringFlags['st']['value'] | $nameFlags,
            "onlyOne+name" => $this->scoringFlags['onlyOne']['value'] | $nameFlags,
            "people_un+name+sm" => $this->scoringFlags['people_un']['value'] | $nameFlags | $this->scoringFlags['exct-sm']['value'],
            "people_un+name+bg" => $this->scoringFlags['people_un']['value'] | $nameFlags | $this->scoringFlags['exct-bg']['value'],
            "people_un+name+pct"=> $this->scoringFlags['people_un']['value'] | $nameFlags |$this->scoringFlags['pct']['value'],
            "people_un+name+st" => $this->scoringFlags['people_un']['value'] | $nameFlags | $this->scoringFlags['st']['value'],
        );
        return $flags ;
    }

    public function getVerifiedIdentities(): array
    {
        $flags = [
            ['em', 'fn', 'ln'],
            ['ph', 'fn', 'ln'],
            ['fn', 'mn', 'ln'],
            ['fn', 'ln', 'exct-sm', 'rltvWithMain'],
            ['fn', 'ln', 'exct-bg', 'rltvWithMain'],
            ['fn', 'ln', 'pct', 'rltvWithMain'],
            ['fn', 'ln', 'st', 'rltvWithMain'],
            ['fn', 'ln', 'input_cm'],
            ['fn', 'ln', 'input_sc'],
            ['fn', 'ln', 'input_addr'],
            ['fn', 'ln', 'addr'],
            ['fn', 'ln', 'exct-sm', 'age'],
            ['fn', 'ln', 'exct-bg', 'age'],
            ['fn', 'ln', 'pct', 'age'],
            ['fn', 'ln', 'st', 'age'],
            ['fn', 'ln', 'onlyOne'],
            ['fn', 'ln', 'exct-sm', 'people_un'],
            ['fn', 'ln', 'exct-bg', 'people_un'],
            ['fn', 'ln', 'pct', 'people_un'],
            ['fn', 'ln', 'st', 'people_un'],
        ];

        return $flags;
    }

    public function getOnlyOneRelativeFlag()
    {
        $flags = array(
            "onlyOneRelative"   =>  $this->scoringFlags['onlyOneRelative']['value'] ,
        );
        return $flags ;
    }

    public function getOnlyOneRelativeIdentities()
    {
        $flags = [
            ['onlyOne', 'rltv']
        ];

        return $flags;
    }

    public function getFirstLevelFlags()
    {
        $nameFlag = $this->scoringFlags['fn']['value'] | $this->scoringFlags['ln']['value'] ;
        $flags = array(
            "input_email+name"      =>  $this->scoringFlags['input_em']['value'] | $nameFlag  ,
            "input_phone+name"      =>  $this->scoringFlags['input_ph']['value'] | $nameFlag  , 
            "f+m+l"                 =>  $this->scoringFlags['mn']['value'] | $nameFlag , 
            "input_company+name"    =>  $this->scoringFlags['input_cm']['value'] | $nameFlag  , 
            "input_school+name"     =>  $this->scoringFlags['input_sc']['value'] | $nameFlag  , 
            "input_username+name"   =>  $this->scoringFlags['input_un']['value'] | $nameFlag  , 
            "name_exctsm_relative"  =>  $this->scoringFlags['rltvWithMain']['value'] | $this->scoringFlags['exct-sm']['value'] | $nameFlag  , 
            "name_exctbg_relative"  =>  $this->scoringFlags['rltvWithMain']['value'] | $this->scoringFlags['exct-bg']['value'] | $nameFlag  , 
            "name_pct_relative" =>  $this->scoringFlags['rltvWithMain']['value'] | $this->scoringFlags['pct']['value'] | $nameFlag  , 
            "name_st_relative"  =>  $this->scoringFlags['rltvWithMain']['value'] | $this->scoringFlags['st']['value'] | $nameFlag  ,
            "onlyOne+name" => $this->scoringFlags['onlyOne']['value'] | $nameFlag,
            "people_un+name+sm" => $this->scoringFlags['people_un']['value'] | $nameFlag | $this->scoringFlags['exct-sm']['value'],
            "people_un+name+bg" => $this->scoringFlags['people_un']['value'] | $nameFlag | $this->scoringFlags['exct-bg']['value'],
            "people_un+name+pct"=> $this->scoringFlags['people_un']['value'] | $nameFlag |$this->scoringFlags['pct']['value'],
            "people_un+name+st" => $this->scoringFlags['people_un']['value'] | $nameFlag | $this->scoringFlags['st']['value'],
        );
        return $flags ;
    }

    public function getFirstLevelIdentities()
    {
        $flags = [
            ['input_em', 'fn', 'ln'],
            ['input_ph', 'fn', 'ln'],
            ['fn', 'mn', 'ln'],
            ['input_cm', 'fn', 'ln'],
            ['input_sc', 'fn', 'ln'],
            ['input_un', 'fn', 'ln'],
            ['fn', 'ln', 'exct-sm', 'rltvWithMain'],
            ['fn', 'ln', 'exct-bg', 'rltvWithMain'],
            ['fn', 'ln', 'pct', 'rltvWithMain'],
            ['fn', 'ln', 'sc', 'rltvWithMain'],
            ['fn', 'ln', 'onlyOne'],
            ['fn', 'ln', 'exct-sm', 'people_un'],
            ['fn', 'ln', 'exct-bg', 'people_un'],
            ['fn', 'ln', 'pct', 'people_un'],
            ['fn', 'ln', 'sc', 'people_un'],
        ];

        return $flags;
    }

    public function getSecondFlags()
    {
        $nameFlag = $this->scoringFlags['fn']['value'] | $this->scoringFlags['ln']['value'] ;
        $flags = array(
            "input_email"       =>  $this->scoringFlags['input_em']['value']   , 
            "input_phone"       =>  $this->scoringFlags['input_ph']['value']   , 
            "input_company"     =>  $this->scoringFlags['input_cm']['value']   , 
            "input_school"      =>  $this->scoringFlags['input_sc']['value']   , 
            "input_username"    =>  $this->scoringFlags['input_un']['value']   ,
            "onlyOne"           => $this->scoringFlags['onlyOne']['value']     , 
            "people_un+name"    => $this->scoringFlags['onlyOne']['value'] | $nameFlag    , 
        );
        return $flags ;
    }

    public function getSecondLevelIdentities()
    {
        $flags = [
            'input_em',
            'input_ph',
            'input_cm',
            'input_sc',
            'input_un',
            'onlyOne',
            ['fn', 'ln', 'people_un']
        ];

        return $flags;
    }
}
