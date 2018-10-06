<?php
namespace Skopenow\Matching\Match;

use Skopenow\Matching\Interfaces\MatchInterface;

class SchoolMatch implements MatchInterface
{
    private $school1;
    private $school2;

    public function setSchool1($school)
    {
        $this->school1 = $school;
    }

    public function setSchool2($school)
    {
        $this->school2 = $school;
    }

    public function match()
    {
        $filteredSchool1 = $this->filterSchoolString($this->school1);
        if (!empty($filteredSchool1)) {
            $this->school1 = $filteredSchool1;
        }
        $filteredSchool2 = $this->filterSchoolString($this->school2);
        if (!empty($filteredSchool2)) {
            $this->school2 = $filteredSchool2;
        }
        $percent = 0;
        similar_text($this->school1, $this->school2, $percent);
        if ($percent > 95) {
            return true ;
        }
        if (strlen($this->school1) > 2 && strlen($this->school2) > 2) {
            if (preg_match('/(' . preg_quote($this->school1, '/') . ')/i', $this->school2)) {
                return true;
            } elseif (preg_match('/(' . preg_quote($this->school2, '/') . ')/i', $this->school1)) {
                return true;
            }
        }
        return false;
    }

    private function filterSchoolString($string)
    {
        $ignoredCommonWords = ['university','company','school','college','of','and','&','&amp;','in','at','the','city','institute','technology'];
        $ignoredCommonWords = array_merge($ignoredCommonWords, array_values(loadData('states_abv')));
        $string = $this->trimWords($string, $ignoredCommonWords);
        return $string ;
    }

    private function trimWords($content, array $words){
        $words = implode("|", $words);
        $re = '/(\s|\b)(' . $words . ')($| |\W)/i';
        $content = preg_replace($re, " ", $content);
        $content = preg_replace('/(\s+)/', " ", $content);
        $content = trim(mb_strtolower($content));
        return $content;
    }
}
