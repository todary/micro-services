<?php
$flags = [
	"fn"		=> ["name"		=> "FirstName"			,"identity" => "fn"				, "value"	=> 0b000000000000000000000000000000001] ,
	"mn"		=> ["name"		=> "MiddleName"			,"identity" => "mn"				, "value"	=> 0b000000000000000000000000000000010] ,
	"ln"		=> ["name"		=> "LastName"			,"identity" => "ln"				, "value"	=> 0b000000000000000000000000000000100] ,
	"unq_name"	=> ["name"		=> "UniqueName"			,"identity" => "unq_name"		, "value"	=> 0b000000000000000000000000000001000] ,
	"exct-sm"	=> ["name"		=> "ExactSmall"			,"identity" => "exct-sm"		, "value"	=> 0b000000000000000000000000000010000] ,
	"exct-bg"	=> ["name"		=> "ExactBig"			,"identity" => "exct-bg"		, "value"	=> 0b000000000000000000000000000100000] ,
	"pct"		=> ["name"		=> "PartialCity"		,"identity" => "pct"			, "value"	=> 0b000000000000000000000000001000000] ,
	"st"		=> ["name"		=> "State"				,"identity" => "st"				, "value"	=> 0b000000000000000000000000010000000] ,
	"em"		=> ["name"		=> "Email"				,"identity" => "em"				, "value"	=> 0b000000000000000000000000100000000] ,
	"ph"		=> ["name"		=> "Phone"				,"identity" => "ph"				, "value"	=> 0b000000000000000000000001000000000] ,
	"cm"		=> ["name"		=> "Company"			,"identity" => "cm"				, "value"	=> 0b000000000000000000000010000000000] ,
	"sc"		=> ["name"		=> "School"				,"identity" => "sc"				, "value"	=> 0b000000000000000000000100000000000] ,
	"age"		=> ["name"		=> "Age"				,"identity" => "age"			, "value"	=> 0b000000000000000000001000000000000] ,
	"onlyOne"	=> ["name"		=> "OnlyOne"			,"identity" => "onlyOne"		, "value"	=> 0b000000000000000000010000000000000] ,
	"rltv"		=> ["name"		=> "relative"			,"identity" => "rltv"			, "value"	=> 0b000000000000000000100000000000000] ,
	"un"		=> ["name"		=> "Username"			,"identity" => "un"				, "value"	=> 0b000000000000000001000000000000000] ,
	"input_name"=> ["name"		=> "InputName"			,"identity" => "input_name"		, "value"	=> 0b000000000000000010000000000000000] ,
	"input_loc"	=> ["name"		=> "InputLocation"		,"identity" => "input_loc"		, "value"	=> 0b000000000000000100000000000000000] ,
	"input_em"	=> ["name"		=> "InputEmail"			,"identity" => "input_em"		, "value"	=> 0b000000000000001000000000000000000] ,
	"input_ph"	=> ["name"		=> "InputPhone"			,"identity" => "input_ph"		, "value"	=> 0b000000000000010000000000000000000] ,
	"input_un"	=> ["name"		=> "InputUsername"		,"identity" => "input_un"		, "value"	=> 0b000000000000100000000000000000000] ,
	"input_cm"	=> ["name"		=> "InputCompany"		,"identity" => "input_cm"		, "value"	=> 0b000000000001000000000000000000000] ,
	"input_sc"	=> ["name"		=> "InputSchool"		,"identity" => "input_sc"		, "value"	=> 0b000000000010000000000000000000000] ,
	"verified_un" => ["name"	=> "VerifiedUsername"	,"identity" => "verified_un"	, "value"	=> 0b000000000100000000000000000000000] ,
	"addr"		=> ["name"		=>	"Address"			,"identity"	=> "addr"			, "value"	=> 0b000000001000000000000000000000000] ,
	"input_addr"=> ["name"		=>	"InputAddress"		,"identity"	=> "input_addr"		, "value"	=> 0b000000010000000000000000000000000] ,
	"rltvWithMain"=> ["name" =>	"relativeWithMain"	,"identity"	=>	"rltvWithMain"		,"value"	=> 0b000000100000000000000000000000000] ,
	"rltvWithRltv"=> ["name" =>	"rltvWithRelative"	,"identity"	=>	"rltvWithRltv"		,"value"	=> 0b000001000000000000000000000000000] ,
	"people_un"=> ["name" =>	"people_username"	,"identity"	=>	"people_un"			,"value"	=> 0b000010000000000000000000000000000] ,
	"name_not_found"=> ["name" =>	"name_not_found"	,"identity"	=>	"name_not_found","value"	=> 0b000100000000000000000000000000000] ,
	"loc_not_found"=> ["name" =>	"location_not_found"	,"identity"	=>	"loc_not_found","value"	=> 0b001000000000000000000000000000000] ,
																								 	 //0b000000000000000111000000110010101

];

$flags["is_relative"] 	  =  ["name" =>	"is_relative"	,"identity"	=>	"is_relative","value"	=> $flags['rltv']];
$flags["onlyOneRelative"] =  ["name" =>	"onlyOneRelative"	,"identity"	=>	"onlyOneRelative","value"	=> $flags['onlyOne']['value'] | $flags['rltv']];

return $flags;	

