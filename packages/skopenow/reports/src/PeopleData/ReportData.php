<?php

namespace Skopenow\Reports\PeopleData;

use Skopenow\Reports\Models\Report;

class ReportData
{
    public function updateReportData($reportId, $data)
    {
        if (empty($data)) {
            return;
        }
        \Log::info('BRAIN: updating Report Data');
        \Log::debug('BRAIN: updating Report Data', $data);
        $report = Report::find($reportId);

        $report->full_name = $this->getFullName($report);
        $report->first_name = !empty($data['first_name']) ? [ucwords($data['first_name'])] : $report->first_name;
        $report->middle_name = !empty($data['middle_name']) ? [ucwords($data['middle_name'])] : $report->middle_name;
        $report->last_name = !empty($data['last_name']) ? [ucwords($data['last_name'])] : $report->last_name;

        $name = '';
        if (!empty($report->first_name[0]) && !empty($report->last_name[0])) {
            $name = $report->first_name[0] . ' ';
            $name .= $report->middle_name[0]??'';
            $name = trim($name);
            $name .= ' ' . $report->last_name[0];
        }

        if ($name) {
            $report->searched_names = [$name];
        } else {
            $report->searched_names = [];
        }

        if (!empty($data['address'])) {
            if (empty($report->address && empty($report->city))) {
                $report->street = !empty($data['street']) ? [$data['street']] : $report->street;
                if (!empty($data['city']) && !empty($data['state'])) {
                    $report->city = ["{$data['city']}, {$data['state']}"];
                }
                $report->state = !empty($data['state']) ? $data['state'] : $report->state;
                // $report->location = $report->city[0] . ', ' . $report->state[0];
                $report->zip = !empty($data['zip']) ? [$data['zip']] : $report->zip;
                $report->address = !empty($data['address']) ? [$data['address']] : $report->address;
            }
        } elseif (!empty($data['city']) && !empty($data['state'])) {
            $report->city = ["{$data['city']}, {$data['state']}"];
        }
        $report->save();
    }

    public function mergeReportPeopleData(array $personData, array $data) : array
    {
        if (!empty($personData['full_name'])) {
            $data['other_names'][] = [
                'first_name' => $data['first_name'],
                'middle_name' => $data['middle_name'],
                'last_name' => $data['last_name'],
                'full_name' => $data['full_name']??''
            ];
            $data['full_name'] = $personData['full_name'][0];
            foreach ($personData['full_name'] as $index => $full_name) {
                if ($index == 0) {
                    continue;
                }
                $data['other_names'][] = ['full_name' => $full_name];
            }
        }
        if (!empty($personData['address'])) {
            $data['addresses'][] = [
                "street" => $data["street"]??'',
                "city" => $data["city"]??'',
                "state" => $data["state"]??'',
                "location" => $data["location"]??'',
                "zip" => $data["zip"]??'',
                "address" => $data["address"]??''
            ];
            $data['street'] = '';
            $data['city'] = '';
            $data['state'] = '';
            $data['location'] = $personData['location']??[];
            $data['zip'] = '';
            $data['address'] = $personData['address'];
            $data['addresses'] = array_merge($data['addresses'], $personData['addresses']);
        }
        $data['location'] = $personData['location']??[];

        if (!empty($personData['phones'])) {
            foreach ($personData['phones'] as $phone) {
                array_unshift($data['phones'], $phone);
            }
        }

        if (!empty($personData['emails'])) {
            foreach ($personData['emails'] as $email) {
                array_unshift($data['emails'], $email);
            }
        }

        if (!empty($personData['work'])) {
            foreach ($personData['work'] as $work) {
                array_unshift($data['work'], $work);
            }
        }

        if (!empty($personData['school'])) {
            foreach ($personData['school'] as $school) {
                array_unshift($data['school'], $school);
            }
        }
        if (!empty($personData['usernames'])) {
            foreach ($personData['usernames'] as $username) {
                array_unshift($data['usernames'], $username);
            }
        }
        return $data;
    }

    protected function getFullName(Report $report) : array
    {
        $full_name = $report->full_name??[];
        if (!empty($report->full_name)) {
            $full_name = array_merge($full_name, $report->full_name);
        }

        if (!empty($report->email)) {
            $full_name = array_merge($full_name, $report->email);
        }

        if (!empty($report->phone)) {
            $full_name = array_merge($full_name, $report->phone);
        }

        if (!empty($report->username)) {
            $full_name = array_merge($full_name, $report->username);
        }

        if (!empty($report->company)) {
            $full_name = array_merge($full_name, $report->company);
        }

        if (!empty($report->school)) {
            $full_name = array_merge($full_name, $report->school);
        }

        return $full_name;
    }
}
