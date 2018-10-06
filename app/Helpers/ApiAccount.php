<?php

// require_once(dirname(__FILE__).'/../../../framework/yii.php');
// require_once dirname(__FILE__) . '/../../../automation/SearchHelpers.php';


function getApiAccount($source,$type = 1)
    {
    	$current_datetime = date("Y-m-d H:i:s");
    	$error_leave_minutes = 15;
    	$micro_time = microtime(true);
    	$allowedFailuresCount = 3;
    	$accounts = \App\Models\ApiAccount::where("source",$source)
    		->where("is_active", 1)
    		->where("type", $type)
    		->whereRaw("(is_available=1 or fail_trials<=$allowedFailuresCount or last_check is null or (((now() - last_check) / 60) >= $error_leave_minutes * fail_trials))")
    		->orderBy("is_available" , "desc")
            ->orderByRaw("rand()")
    		->first();
	    	// print_r($accounts);die;
   //  	if(!$accounts){
   //  		$checkArray=array(
			// 	'type'=>$source,
			// 	'status'=>0,
			// 	'additional_data'=>'There is no api account for that source'
			// );
			// // \Yii::app()->NotificationsComponent->CheckApiAccount($checkArray);
			// unset($checkArray);
   //  	}
    	return $accounts;
    }
function setApiAccountStatus(
	\App\Models\ApiAccount $account,
	$is_succeeded,
	$reason = "",
	$ip = null,
	$status = null,
	$data = null
	) {
        $account->reason = $reason;
        if ($is_succeeded){
        	$account->last_check = DB::raw('now()');
    		$account->last_usage = DB::raw('now()');
    		$account->fail_trials = 0;
    		$account->is_available = 1;
    		$account->data = $data;
    		if ($ip) $account->associated_proxy_ip = $ip->id;
    		if ($status) $account->associated_proxy_status = $status->id;
    		$account->save();

    		$checkArray=array(
				'type'=>$account->source,
				'status'=>1,
				'additional_data'=>''
			);
			Yii::app()->NotificationsComponent->CheckApiAccount($checkArray);
			unset($checkArray);
        }else{
        	if($account->password){
	    		$account->fail_trials = $account->fail_trials+1;
	    		$account->is_available = 0;
    		}
			$account->last_check = DB::raw('now()');
			if ($ip) $account->associated_proxy_ip = $ip->id;
    		if ($status) $account->associated_proxy_status = $status->id;
    		unset($account->updated_at);
    		$account->save();
        }
}
