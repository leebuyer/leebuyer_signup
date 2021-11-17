<?php
use Xmf\Request;
use XoopsModules\Leebuyer_signup\Leebuyer_signup_api;

require_once dirname(dirname(__DIR__)) . '/mainfile.php';

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$token = Request::getString('token'); //使用者登入的那一個token
$action_id = Request::getString('action_id');

$api = new Leebuyer_signup_api($token);

switch ($op) {
    //取得所有活動
    case 'leebuyer_signup_actions_index':
        echo $api->leebuyer_signup_actions_index($xoopsModuleConfig['only_enable']); //此值由偏好設定處而來
        break;
    //取得活動所有報名資料
    case 'leebuyer_signup_data_index':
        echo $api->leebuyer_signup_data_index($action_id); //此值由偏好設定處而來
        break;
    default:
        echo $api->user();
        break;
}
