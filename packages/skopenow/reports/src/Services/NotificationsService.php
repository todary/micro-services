<?php
namespace Skopenow\Reports\Services;

/**
*
*/
class NotificationsService
{
    public function notifyAdminsHighSearchUsage($corporate)
    {
        try {
            $emailParams = array(
                    '{name}'=>ucwords(strtolower($corporate->name)),
                    '{search_count}'=>$corporate->accounts->pay_as_you_go_searches,
            );

            $skopenowAdmins = User::model()->cache(600)->findAllByAttributes(array('role_id'=>1,'status'=>1));
            foreach ($skopenowAdmins as $skopenowAdmin) {
                Yii::app()->EmailHelper->SendEmail("high_search_usage", $skopenowAdmin->email, $emailParams, false);
            }

        } catch (Exception $ex) {
        }
    }
}
