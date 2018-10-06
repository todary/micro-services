<?php

if (!function_exists('name_parts')) {

    function name_parts(string $name)
    {
        $firstName = '';
        $middleName = '';
        $lastName = '';
        $nameInfoService = loadService('nameInfo');

        // load name splitter service here
        $splittedNames = $nameInfoService->nameSplit(new \ArrayIterator([$name]));
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

        return [
            'full_name' => $name,
            'first_name' => $firstName,
            'middle_name' => $middleName,
            'last_name' => $lastName,
        ];
    }
}

if (!function_exists('names_parts')) {
    function names_parts(array $names)
    {
        $returnedNames = [];
        foreach ($names as $name) {
            $returnedNames[] = name_parts($name);
        }

        return $returnedNames;
    }
}


if (!function_exists('isUniqueName')) {

    function isUniqueName(array $name)
    {
        $nameInfoService = loadService('nameInfo');

        // load name splitter service here
        $unique = $nameInfoService->uniqueName(new \ArrayIterator([$name]));
        return (bool) $unique[0]['unique'];
    }
}

if (!function_exists('isUniqueFullName')) {

    function isUniqueFullName(array $fullNames)
    {
        $unique = [];
        $nameInfoService = loadService('nameInfo');
        foreach ($fullNames as $name) {
            $firstName = '';
            $middleName = '';
            $lastName = '';

            // load name splitter service here
            $splittedNames = $nameInfoService->nameSplit(new \ArrayIterator([$name]));
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

            $names = [
                'fullName' => $name,
                'firstName' => $firstName,
                'middleName' => $middleName,
                'lastName' => $lastName,
            ];
            $nameInfo = $nameInfoService->uniqueName(new \ArrayIterator([$names]));
            if (!empty($nameInfo[0]['unique'])) {
                $unique[] = true;
            } else {
                $unique[] = false;
            }
        }
        return $unique;
    }
}
