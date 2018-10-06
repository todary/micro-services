<?php
namespace Skopenow\Reports\Services;

/**
*
*/
class SettingsService
{
    public function getPremiumSearchCost()
    {
        return Yii::app()->settings->PremiumSearchCost;
    }

    public function getEnabledSources()
    {
        return [
            'fullcontact',
            'googleplus',
            'whitepages',
            
            'linkedin',
            'spokeo',
            'instagram',
            'intelius',
            'intelius',
            'twitter',
            'facebook',
            'google',
            '411locate',
            'pipl',
            'lookup',
            'myspace',
            'yellowpages',
            'mylife',
            'beenverified',
            'instantcheckmate',
            'peekyou',
            'pinterest',
            'tendigits',
            'websites',
            'slideshare',
            "websites_work_experience",
            'soundcloud',
            'flickr',
            'tloxp',
            'angel',

            'foursquare',
            'foursquare_facebook',
            'youtube',
        ];
    }

    public function getMaxCominationsCount()
    {
        return 150;
    }

    public function getSearchCreditCount($serviceId, $isPremiumSearch = false, $isCorporate = false)
    {
        if (!$isPremiumSearch
            || (
              ($service_id == 1 || $service_id == 6)
              && $isCorporate
            )
        ) {
            return 1;
        } elseif ($isPremiumSearch) {
            return $this->getPremiumSearchCost();
        }
    }

    public function getChargeScore()
    {
        return \Yii::app()->settings->charge_score;
    }
}
