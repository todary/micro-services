<?php
namespace Skopenow\Reports\Transformers;

/**
 *
 */
class ReportTransformer
{
    public function transform($report)
    {
        $data = [
            'id' => $report->id,
            'names' => $report->searched_names ?? [],
            'ages' => $report->age ?? [],
            'cities' => $report->city ?? [],
            'addresses' => $report->address ?? [],
            'phones' => $report->phone ?? [],
            'companies' => $report->company ?? [],
            'schools' => $report->school ?? [],
            'emails' => $report->email ?? [],
            'usernames' => $report->usernames ?? [],
            'birthDates' => $report->date_of_birth ?? [],
            'zipCodes' => $report->zip ?? [],
            'is_paid' => $report->is_paid ?? '',
            'is_charge' => $report->is_charge ?? '',
            'filters' => $report->filters ?? '',
            'reference' => $report->reference,
            'completed' => $report->completed,
            'api_options' => json_decode($report->api_options, true)?? [],
            'score'=>$report->score,
            'user_id'=>$report->user_id,
            'model' => $report,
        ];
        return $data;
    }

    public function transformToPeopleData($report)
    {
        $nameInfoService = loadService('nameInfo');
        $splittedNames = $nameInfoService->nameSplit(new \ArrayIterator($report->full_name));
        foreach ($splittedNames as $splittedName) {
            if ($splittedNameDetails = $splittedName['splitted'][0]) {
                if (!empty($splittedNameDetails["firstName"])) {
                    $firstName = $splittedNameDetails["firstName"];
                }

                if (!empty($splittedNameDetails["middleName"])) {
                    $middleName = $splittedNameDetails["middleName"];
                }

                if (!empty($splittedNameDetails["lastName"])) {
                    $lastName = $splittedNameDetails["lastName"];
                }
            }
        }
        $addresses = [];
        foreach ($report->address as $index => $address) {
            if ($index == 0) {
                continue;
            }
            $addresses[] = ['address' => $address];
        }
        $data = [
            'source' => 'input',
            'first_name' => $firstName ?? '',
            'middle_name' => $middleName ?? '',
            'last_name' => $lastName ?? '',
            'address' => $report->address[0] ?? '',
            'addresses' => $addresses,
            'location' => $report->city ?? [],
            'phones' => $report->phone ?? [],
            'work' => $report->company ?? [],
            'school' => $report->school ?? [],
            'emails' => $report->email ?? [],
            'usernames' => $report->usernames??[],
            'type' => 'input',
        ];
        return $data;
    }
}
