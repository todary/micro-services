<?php

namespace Skopenow\Reports\PeopleData;

use Skopenow\Reports\Models\Report;
use App\DataTypes\Address;
use App\DataTypes\Age;
use App\DataTypes\Email;
use App\DataTypes\Name;
use App\DataTypes\Phone;
use App\DataTypes\School;
use App\DataTypes\Username;
use App\DataTypes\Work;
use App\DataTypes\Website;
use App\DataTypes\Relative;

class DatapointHandler
{
    public function addDataPoint(array $data, $reportID)
    {
        $report = Report::find($reportID);
        \Log::info('BRAIN: addDataPoint');
        \Log::debug('BRAIN: addDataPoint', $data);
        $dataPoint = [];
        if ($data['source'] == 'input') {
            $flags = ['is_input' => true];
        } else {
            $flags = ['is_peopleData' => true];
            $data['source'] = 'peopleData';
        }

        $dataPoint['address'] = [];

        if (!empty($data['address'])) {
            \Log::info('BRAIN: Create Address DataType');
            $addressData = ['full_address' => $data['address']];
            $addressData['street'] = $data['street']??'';
            $addressData['city'] = $data['city']??'';
            $addressData['state'] = $data['state']??'';
            $addressData['zip'] = $data['zip']??'';
            $dataPoint['address'][] = Address::create($addressData, $data['source'], $flags);
        }
        if (!empty($data['addresses'])) {
            foreach ($data['addresses'] as $address) {
                \Log::info('BRAIN: Create Address DataType multi data');
                if (empty($address['address'])) {
                    continue;
                }
                $addressData = ['full_address' => $address['address']];
                $addressData['street'] = $address['street']??'';
                $addressData['city'] = $address['city']??'';
                $addressData['state'] = $address['state']??'';
                $addressData['zip'] = $address['zip']??'';
                $dataPoint['address'][] = Address::create($addressData, $data['source'], $flags);
            }
        }
        if (!empty($data['location'])) {
            foreach ($data['location'] as $location) {
                \Log::info('BRAIN: Create Location DataType');
                $addressData = ['full_address' => $location];
                $dataPoint['address'][] = Address::create($addressData, $data['source'], $flags);
            }
        }

        if (!empty($data['age'])) {
            \Log::info('BRAIN: Create Age DataType');
            $age = ['age' => $data['age']];
            $dataPoint['age'] = [Age::create($age, $data['source'], $flags)];
        }

        if (!empty($data['phones'])) {
            foreach ($data['phones'] as $phone) {
                \Log::info('BRAIN: Create Phone DataType');
                $dataPoint['phone'][] = Phone::create(['phone' => $phone], $data['source'], $flags);
            }
        }

        if (!empty($data['school'])) {
            foreach ($data['school'] as $school) {
                \Log::info('BRAIN: Create School DataType');
                $dataPoint['school'][] = School::create(['name' => $school], $data['source'], $flags);
            }
        }
        $dataPoint['name'] = [];
        if (!empty($data['first_name'])) {
            \Log::info('BRAIN: Create Name DataType');
            $name = ['first_name' => $data['first_name']];
            $name['middle_name'] = $data['middle_name'];
            $name['last_name'] = $data['last_name'];
            $otherNameFlag = $flags;
            if ($data['source'] != "input" && !empty($report->full_name[0]) && (strtolower($data['first_name']) != strtolower($report->full_name[0]))) {
                $otherNameFlag['other_name'] = 1;
            }
            $dataPoint['name'][] = Name::create($name, $data['source'], $otherNameFlag);
        }
        if (!empty($data['other_names'])) {
            $filter = new PeopleDataFilter($reportID);
            $otherNames = $filter->filterOtherNames($data['other_names']);
            foreach ($otherNames as $otherName) {
                if ($otherName['full_name'] == $data['full_name'] &&
                    $otherName['full_name'] == $report->full_name[0]
                ) {
                    \Log::info('BRAIN: Other name is the same as report name');
                    continue;
                }
                \Log::info('BRAIN: Create Other Names DataType');
                $name = ['first_name' => $otherName['first_name']];
                $name['middle_name'] = $otherName['middle_name'];
                $name['last_name'] = $otherName['last_name'];
                $name['full_name'] = $otherName['full_name'];
                $otherNameFlag = $flags;
                $otherNameFlag['other_name'] = 1;
                $dataPoint['name'][] = Name::create($name, $data['source'], $otherNameFlag);
            }
        }
        if (!empty($data['emails'])) {
            foreach ($data['emails'] as $email) {
                \Log::info('BRAIN: Create Email DataType');
                $emails = ['email' => $email];
                $dataPoint['emails'][] = Email::create($emails, $data['source'], $flags);
                /*
                preg_match("/(.*)@(.*)/", $email, $match);
                if (empty($match[1])) {
                    continue;
                }
                $username = $match[1];
                \Log::info('BRAIN: Create Username DataType from Email');
                $extra = $flags;
                $extra['from_email'] = true;
                $dataPoint['username'][] = Username::create(['username' => $username], 'email', $extra);
                */
            }
        }

        if (!empty($data['usernames'])) {
            foreach ($data['usernames'] as $username) {
                \Log::info('BRAIN: Create Username DataType');
                \Log::debug('BRAIN: Create Username DataType', ['username' => $username]);
                $dataPoint['username'][] = Username::create(['username' => $username], $data['source'], $flags);
            }
        }

        if (!empty($data['work'])) {
            foreach ($data['work'] as $work) {
                \Log::info('BRAIN: Create Work DataType');
                $dataPoint['work'][] = Work::create(['company' => $work], $data['source'], $flags);
            }
        }

        if (!empty($data['relatives'])) {
            foreach ($data['relatives'] as $relatives) {
                \Log::info('BRAIN: Create Relative DataType');
                $dataPoint['relatives'][] = Relative::create($relatives, $data['source'], $flags);
            }
        }
        if (!empty($data['profiles'])) {
            \Log::info('BRAIN: add Websites to Result Data');
            foreach ($data['profiles'] as $profile) {
                if (isDomain($profile['url']) && !isBannedDomain($profile['domain'])) {
                    \Log::info('BRAIN: Create Website DataType');
                    \Log::debug('BRAIN: add Website to Result Data', [$profile['url']]);
                    $dataPoint['website'][] = Website::create(['url' => $profile['domain']], $data['source'], $flags);
                }
            }
        }
        $input = [];
        foreach ($dataPoint as $type => $subDataPoint) {
            $input[$type] = new \ArrayIterator($subDataPoint);
        }
        $datapointService = loadService('datapoint');
        \Log::debug('BRAIN: make DataPoints', $input);
        $datapointService->make(new \ArrayIterator($input));
        \Log::info('BRAIN: made DataPoints');
    }

    public function addEmailUsernamesDatapoint(array $emails, string $source)
    {
        $dataPoint = [];
        if ($source == 'input') {
            $flags = ['is_input' => true];
        } else {
            $flags = ['is_peopleData' => true];
            $source = 'peopleData';
        }
        $flags['from_email'] = true;
        foreach ($emails as $email) {
            preg_match("/(.*)@(.*)/", $email, $match);
            if (empty($match[1])) {
                continue;
            }
            $username = $match[1];
            \Log::info('BRAIN: Create Username DataType from Email');
            $dataPoint['username'][] = Username::create(['username' => $username], 'email', $flags);
        }

        $input = [];
        foreach ($dataPoint as $type => $subDataPoint) {
            $input['username'] = new \ArrayIterator($subDataPoint);
        }

        $datapointService = loadService('datapoint');
        \Log::debug('BRAIN: make generated usernames DataPoints', $input);
        $datapointService->make(new \ArrayIterator($input));
        \Log::info('BRAIN: made generated usernames DataPoints');
    }
}
