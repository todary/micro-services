<?php
namespace App\Libraries;

/**
*
*/
class SearchAccount
{

    public function getUserAccount($userId)
    {
        return \CAccount::of($userId);
    }

    public function canSearch($userId)
    {
        //user account
        $account = $this->getUserAccount($userId);

        if (!$account->canSearch()) {
            return false;
        }
        return true;
    }

    public function getUserCorporate($userId)
    {
        $account = $this->getUserAccount($userId);
        if ($account->user->corporate) {
            return $account->user->corporate;
        }
        return null;
    }

    public function isHighSearchUsage($corporateId)
    {
        if (!$corporate->high_search_usage && $corporate->accounts->pay_as_you_go_searches >= 3000) {
            return true;
        }
    }

    public function markCorporateWithHighSearchUsage($corporateId)
    {
        $corporate->high_search_usage = 1;
        $corporate->save();
    }

    public function newSearchStarted($userId)
    {
        $corporate = $this->getUserCorporate($userId);
        //notify admins and mark corporate for high search usage
        if ($corporate && $this->isHighSearchUsage($corporate->id)) {
            // $notificationsService->notifyAdminsHighSearchUsage($corporate->id);
            $this->markCorporateWithHighSearchUsage($corporate->id);
        }
    }

    public function isPremiumSearchEnabled($userId)
    {
        $account = $this->getUserAccount($userId);
        $user = $account->user;

        if ($user->corporate) {
            $corporate = $user->corporate;

            if ($corporate->enable_premium_search == 1) {
                return true;
            }
        } else {
            if ($user->enable_premium_search == 1) {
                return true;
            }
        }
    }

    public function refundSearch(int $reportId)
    {
        return \CAccount::refundSearch($reportId);
    }

    public function payForSearch(int $userId, int $reportId)
    {
        $account = $this->getUserAccount($userId);
        return $account->payForSearch($reportId);
    }
}
