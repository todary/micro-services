<?php

namespace Skopenow\Result\AfterSave\Helpers;

use App\Models\ResultData as Result;
use App\DataTypes\DataType;
use App\DataTypes\Work;
use App\DataTypes\School;
use App\Libraries\DBCriteria;

trait DataPoints
{

	public $isVerified = null;

	public function saveDataPoints(Result $result)
	{
		$this->isVerified = $this->isVerified($result)['status'];
		config(['state.result_id' => $result->id]);
		$data = new \ArrayIterator($this->prepareDataPoints($result));

		return $this->dataPointService->make($data);
	}

	public function prepareDataPoints(Result $result)
	{
		if (!$result->getIsRelative()) {
			return [
				"work"		=>	$this->prepareExperiences($result, "storing"),
				"school"	=>	$this->prepareEducations($result, "storing"),
				"username"	=>	new \ArrayIterator($this->prepareUsername($result, "storing")),
				"phones"	=>	$this->preparePhones($result, "storing"),
				"emails"	=>	$this->prepareEmails($result, "storing"),
				"age"		=>	new \ArrayIterator($this->prepareAge($result, "storing")),
				"name"		=>	$this->prepareNames($result, "storing"),
				"website"		=>	$this->prepareWebsites($result, "storing"),
				// "address"	=>	$this->prepareLocations($result),
			];
		}else {
			return [
				"name"		=>	$this->prepareNames($result),
				// "address"	=>	$this->prepareLocations($result),
			];
		}
	}

	public function prepareDataPointsForMatching(Result $result)
	{
		return [
			"work"		=>	$this->prepareExperiences($result, "matching"),
			"school"	=>	$this->prepareEducations($result, "matching"),
			"username"	=>	new \ArrayIterator($this->prepareUsername($result, "matching")),
			"phones"	=>	$this->preparePhones($result, "matching"),
			"emails"	=>	$this->prepareEmails($result, "matching"),
			"age"		=>	new \ArrayIterator($this->prepareAge($result, "matching")),
			"names"		=>	$this->prepareNames($result, "matching"),
			"locations"	=>	$this->prepareLocations($result, "matching"),
		];

		// return [
		// 	"workExperiences"	=>	$result->getExperiences(),
		// 	"educations"		=>	$result->getEducations(),
		// 	"phones"			=>	$result->getPhones(),
		// 	"emails"			=>	$result->getEmails(),
		// 	"username"			=>	new \ArrayIterator([$result->getUsername()]),
		// 	"age"				=>	$result->getAge(),
		// 	"names"				=>	$result->getNames(),
		// 	"locations"			=>	$result->getLocations(),
		// ];
	}

	public function prepareNames(Result $result)
	{
		$names = $result->getNames();
		foreach ($names as $name) {
			$this->appendFlags($name , $result->getFlags());
			$this->appendIsVerified($name);
		}
		return $names;
	}

	public function prepareLocations(Result $result)
	{
		$locations = $result->getLocations();
		foreach ($locations as $location) {
			$this->appendIsVerified($location);
			$this->appendFlags($location,$result->getFlags());
		}

		return $locations;
	}

	public function prepareWebsites(Result $result)
	{
		$websites = $result->getWebsites();
		foreach ($websites as $website) {
			$this->appendIsVerified($website);
			$this->appendFlags($website,$result->getFlags());
		}

		return $websites;
	}

	public function prepareUsername(Result $result, string $for)
	{
		if ($for == "storing" && !in_array($result->mainSource, ['facebook', 'twitter', 'linkedin'])) {
			return [];
		}

		$username = $result->getUsername();
		if ($for == "storing" && $result->mainSource == "linkedin") {
			if (preg_match('/.*\-.*\-/', $username['username'])) {
				return [];
			}
		}

		if (!$username) {
			return [];
		}
		$this->appendFlags($username,$result->getFlags());
		$this->appendIsVerified($username);
		return [$username];
	}

	public function prepareExperiences(Result $result)
	{
		$experiences = $result->getExperiences();
		while ($experiences->valid()) {
			$work = $experiences->current();
			$this->appendFlags($work,$result->getFlags());
			$this->appendIsVerified($work);
			$experiences->next();
		}

		return $experiences;
	}

	public function prepareEducations(Result $result)
	{
		$educations = $result->getEducations();
		foreach ($educations as $key => $education) {
			$this->appendFlags($education,$result->getFlags());
			$this->appendIsVerified($education);
		}

		return $educations;
	}

	public function preparePhones(Result $result)
	{
		$phones = $result->getPhones();
		foreach ($phones as $key => $phone) {
			$this->appendFlags($phone,$result->getFlags());
			$this->appendIsVerified($phone);
		}

		return $phones;
	}

	public function prepareEmails(Result $result)
	{
		$emails = $result->getEmails();
		foreach ($emails as $key => $email) {
			$this->appendFlags($email,$result->getFlags());
			$this->appendIsVerified($email);
		}

		return $emails;
	}

	public function prepareAge(Result $result)
	{
		$age = $result->getAge();
		if (!$age) {
			return [];
		}

		$this->appendIsVerified($age);
		$this->appendFlags($age,$result->getFlags());

		return [$age];
	}

	public function appendIsVerified(DataType $dataType)
	{
		$dataType->appendToExtras('is_verified' , $this->isVerified);
	}

	public function appendFlags(DataType $dataType,$flags)
	{
		$dataType->appendToExtras('flags',$flags);
	}

	public function loadData($type = null)
	{
		$dataPointService = loadService('datapoint')->datasource();
        $DBCriteria = new DBCriteria();
        $DBCriteria->compare('report_id' , config('state.report_id'));
        if(!empty($type)){
        	$DBCriteria->compare('type', $type);
        }
        $dataPoints = $dataPointService->loadData($DBCriteria);

        return $dataPoints;
	}

}
