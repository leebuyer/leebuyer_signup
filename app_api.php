<?php
use Xmf\Request;
use XoopsModules\Leebuyer_signup\Leebuyer_signup_api;

require_once dirname(dirname(__DIR__)) . '/mainfile.php';

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$token = Request::getString('token'); //使用者登入的那一個token

$api = new Leebuyer_signup_api($token);

switch ($op) {
    //取得所有活動
    case 'leebuyer_signup_action_index':

        break;
    default:
        echo $api->user();
        break;
}
