<?php
namespace Skopenow\Matching\Match;

use Skopenow\Matching\Interfaces\MatchInterface;

class WorkMatch implements MatchInterface
{
    private $workExp1;
    private $workExp2;
    private $extractCompany = false;
    private $params = [];


    public function setWork1($workExp1)
    {
        $this->workExp1 = $workExp1;
    }

    public function setWork2($workExp2)
    {
        $this->workExp2 = $workExp2;
    }

    public function setExtractCompany(bool $extract)
    {
        $this->extractCompany = $extract;
    }

    public function setParams(array $params)
    {
        $this->params = $params;
    }

    public function match()
    {
        if ($this->extractCompany) {
            $this->workExp1 = $this->extractCompanyFromWork($this->workExp1);
            $this->workExp2 = $this->extractCompanyFromWork($this->workExp2);
        }

        $filteredWorkExp1 = $this->filterWorkString($this->workExp1);
        if (!empty($filteredWorkExp1)) {
            $this->workExp1 = $filteredWorkExp1;
        }

        $filteredWorkExp2 = $this->filterWorkString($this->workExp2);
        if (!empty($filteredWorkExp2)) {
            $this->workExp2 = $filteredWorkExp2;
        }

        // Remove words with less than three chars .
        if (!empty($this->params['remove_three_chars_words'])) {
            $this->workExp1 = $this->removeWordsWithLength($this->workExp1, 3);
            $this->workExp2 = $this->removeWordsWithLength($this->workExp2, 3);
        }

        $percent = 0;
        similar_text($this->workExp1, $this->workExp2, $percent);

        if ($percent > 95) {
            return true;
        }

        if (strlen($this->workExp2) > 2 && strlen($this->workExp1) > 2) {
            if (preg_match('/(\s*)(' . preg_quote($this->workExp1, '/') . ')(\s|$)/i', $this->workExp2, $match)) {
                return true;
            }
            if (preg_match('/(\s*)(' . preg_quote($this->workExp2, '/') . ')(\s|$)/i', $this->workExp1, $match)) {
                return true;
            }
        }
        if (stripos($this->workExp2, ',') || stripos($this->workExp1, ',')) {
            // incase there are commas will remove it and recheck
            $this->workExp1 = str_replace(',', '', $this->workExp1);
            $this->workExp2 = str_replace(',', '', $this->workExp2);
            return $this->match();
        } elseif ($percent > 90 && stripos($this->workExp2, ' ')) {
            // incase there are whiteSpace will remove it and recheck
            $this->workExp2 = str_replace(' ', '', $this->workExp2);
            return $this->match();
        }
        return false;
    }

    private function extractCompanyFromWork($workString)
    {
        if (stripos($workString, "- ")) {
            $workString = substr($workString, stripos($workString, "- ")+2);
        } elseif (stripos($workString, "-")) {
            $workString = substr($workString, stripos($workString, "-")+1);
        }
        return $workString;
    }

    private function filterWorkString($string)
    {
        $ignoredCommonWords = [
            'university',
            'company',
            'school',
            'college',
            'of',
            'and',
            '&',
            '&amp;',
            'in',
            'at',
            'the',
            'city',
            'institute',
            'technology',
            'llc',
            'ltd'
        ];
        $adverbs = "";
        $ignoredCommonWords = array_merge($ignoredCommonWords, array_values(loadData('states_abv')));
        $string = $this->trimWords($string, $ignoredCommonWords);
        return $string;
    }

    private function removeWordsWithLength($word, $length)
    {
        $wordExploded = explode(" ", $word);
        $retWord = "";
        foreach ($wordExploded as $value) {
            if (strlen($value) > $length) {
                $retWord .= $value . " ";
            }
        }
        $retWord = trim($retWord);
        if (empty($retWord)) {
            $retWord = $word;
        }
        return $retWord;
    }

    private function trimWords($content, array $words)
    {
        $words = implode("|", $words);
        $re = '/(\s|\b)(' . $words . ')($| |\W)/i';
        $content = preg_replace($re, " ", $content);
        $content = preg_replace('/(\s+)/', " ", $content);
        $content = trim(mb_strtolower($content));
        return $content;
    }

    public function extractCompany()
    {
        $this->extractCompany = true;
    }

    public function setParameters(array $params)
    {
        $this->params = $params;
    }
}
