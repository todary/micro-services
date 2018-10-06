<?php
namespace Skopenow\Matching\Check;

use Skopenow\Matching\Interfaces\CheckInterface;

class EmailCheck
{
    public static function checkEmail($personId, $email, $firstName, $lastName)
    {
        $emailInArray = explode("@", $email);
        $username = $this->convertToAlphaNum(trim(strtolower($emailInArray[0])));
        $firstName = trim(strtolower($firstName));
        $lastName = trim(strtolower($lastName));
        $haveNumber = preg_match('/[0-9]/', $username);

        if ($haveNumber) {
            return true;
        }

        if ($username == $firstName) {
            return false;
        }

        $usernameStrLen = strlen($username);
        if ($usernameStrLen && $usernameStrLen < 4) {
            return true;
        }

        if ($lastName) {
            $indexLastName = strpos($username, $lastName);
            if ($indexLastName !== false) {
                $username = substr($username, 0, $indexLastName) . substr($username, $indexLastName + strlen($lastName), $usernameStrLen);
            }
        }
        if (!$username) {
            return false;
        }

        $nameAnalyzer = new NameAnalyzer;
        $nickNames = $nameAnalyzer->getNickNamesFromDB($firstName);

        foreach ($nickNames as $nickName) {
            if ($username == $nickName) {
                return false;
            }
        }
        return isWord($username, $personId);
    }

    private function convertToAlphaNum($name)
    {
        return preg_replace('/[^a-zA-Z0-9_]/', '', $name);
    }
}
