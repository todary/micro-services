<?php
namespace Skopenow\Reports\CombinationGenerators\Generators;

use Skopenow\Reports\Models\Report;
use App\Models\EmailBlacklist;
use \Illuminate\Database\Eloquent\ModelNotFoundException;

/**
*
*/
class GoogleCombinationsGenerator extends AbstractCombinationsGenerator
{
    public function make()
    {
        $emails = $this->report->email;
        foreach ($emails as $email) {
            if ($this->checkPrivateEmail($email)) {
                $this->makePrivateEmailCombinations($email, 'google');
            }
        }
        $this->makeNameSchoolCombinations();
        $this->makeNameWorkCombinations();
        $this->makeEmailCombinations();
        $this->makePhoneCombinations();
        $this->makeNameAddressCombinations();
        $this->makeFirstMiddleLastNameCombinations();
        $this->makeInURLCombinations();
        $this->makeUsernameCombinations();
        $this->makeCustomUsernameCombinations();
    }

    public function makeUsernameCombinations()
    {
        $this->combinationsMaker->set('username_source', ['input']);
        $this->combinationsMaker->set('username_status', ['not_verified']);
        $combinations = $this->combinationsMaker
            ->withEach(['username', 'username_status', 'username_source'])
            ->get();
        $levels = [];
        $levelNumber = 1;
        foreach ($combinations as $combination) {
            $criteria = $this->buildSearchCriteria($combination);
            $levels[] = ['source' => 'google', 'data' => $criteria->toCombinationData(), 'level_number' => $levelNumber];
            $levelNumber++;
        }
        $this->combinationsService->store('google', $levels);
    }

    public function makeCustomUsernameCombinations()
    {
        $usernames = $this->generateUsernamesFromNames($this->report->full_name);
        if (count($usernames) == 0) {
            return;
        }
        $this->combinationsMaker->set('username', $usernames, 'username_generated');
        $this->combinationsMaker->set('username_status', ['not_verified']);
        $this->combinationsMaker->set('username_source', ['generated']);
        $combinations = $this->combinationsMaker
            ->withEach(['username_generated', 'username_status', 'username_source'])
            ->get();
        foreach ($combinations as $combination) {
            $criteria = $this->buildSearchCriteria($combination);
            $level = ['source' => 'google', 'data' => $criteria->toCombinationData(), 'level_number' => 1];
            $this->combinationsService->store('google', [$level]);
        }
    }

    public function makePhoneCombinations()
    {
        $this->createSimpleCombination(['phone'], 'google');
    }

    public function makeInURLCombinations()
    {
        // removing Angel
        $sites = ['slideshare.net', /*'angel.co',*/ 'etsy.com', 'flickr.com', 'instagram.com', 'youtube.com', 'pinterest.com'];
        $this->combinationsMaker->set('site', $sites);
        $combinations = $this->combinationsMaker
            ->withEach(['name', 'location', 'site'])
            ->get();

        foreach ($combinations as $combination) {
            // $combinationId = $this->combinationsService->store('google');
            $criteria = $this->buildSearchCriteria($combination);

            $levels = [];
            $levels[]= ['source' => 'google', 'data' => $criteria->toCombinationData(), 'level_number' => 1];

            $criteria->city = '';
            $levels[]= ['source' => 'google', 'data' => $criteria->toCombinationData(), 'level_number' => 2];

            $criteria->state = '';
            $levels[] = ['source' => 'google', 'data' => $criteria->toCombinationData(), 'level_number' => 3];

            $this->combinationsService->store('google', $levels);
        }
    }

    public function makeNameAddressCombinations()
    {
        $this->createSimpleCombination(['name', 'address'], 'google');
    }

    public function makeFirstMiddleLastNameCombinations()
    {
        if (empty($this->report->middle_name)) {
            return;
        }
        $this->createSimpleCombination(['name'], 'google');
    }

    public function makeNameSchoolCombinations()
    {
        $this->createSimpleCombination(['name', 'school'], 'google');
    }

    public function makeNameWorkCombinations()
    {
        $this->createSimpleCombination(['name', 'company'], 'google');
    }

    public function makeEmailCombinations()
    {
        $this->createSimpleCombination(['email'], 'google');
    }
}
