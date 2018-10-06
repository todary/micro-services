<?php

if (class_exists('Yii', false)) return;

if (empty($_SERVER['SERVER_ADDR'])) {
	$_SERVER['SERVER_ADDR'] = '127.0.0.1';
}

defined('ROOT_DIR') or define('ROOT_DIR', dirname(__FILE__) . '/../../..');
// change the following paths if necessary
$yii=dirname(__FILE__).'/../../../framework/yii.php';
$config=dirname(__FILE__).'/../../../protected/config/main.php';

if (!defined('YII_DEBUG')) {
	// remove the following lines when in production mode
	defined('YII_DEBUG') or define('YII_DEBUG',true);
	// specify how many levels of call stack should be shown in each log message
	defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',10);

	defined('YII_ENABLE_EXCEPTION_HANDLER') or define('YII_ENABLE_EXCEPTION_HANDLER',false);
	defined('YII_ENABLE_ERROR_HANDLER') or define('YII_ENABLE_ERROR_HANDLER',false);

	define('RegistrationCredits', 'reg_credit');
	define('PurchaseSearches', 'pur_search');
	define('PurchasePremiumSearches', 'pur_prem_search');
	define('ConvertSearchesToPremiumSearches', 'conv_search_prem');
	define('PurchaseRescan', 'add_rescan');
	define('UseSearch', 'use_search');
	define('UsePremiumSearch', 'use_prem_search');
	define('UseTrialSearch', 'use_trial');
	define('UsePayAsYouGoSearch', 'use_payg');
	define('UseRecan', 'use_rescan');
	define('RefundSearch', 'refund_search');
	define('RefundPremiumSearch', 'refund_prem_search');
	define('RefundPAYGSearch', 'refund_payg_search');
	define('AdminCredit', 'admin_credit');
	define('PurchaseTracks', 'pur_tracks');
	define('ConvertSearchesToTracks', 'conv_search_tracks');
	define('UseSubscriptionNormalSearch', 'use_sub_normal');
	define('UseSubscriptionPremiumSearch', 'use_sub_premium');

	global $is_local;
	$is_local = env('APP_ENV') == "local";

	global $_start_time;
	$_start_time = microtime(true);
	require_once dirname(__FILE__) . '/../../../protected/helpers/general.php';

	require_once($yii);

	Yii::createWebApplication($config);

	YiiBase::setPathOfAlias("webroot", dirname(__FILE__) . '/../../../');
	require_once YiiBase::getPathOfAlias("webroot") . '/automation/SearchApis.php';

	define('YII_INITIALIZED', true);
}

