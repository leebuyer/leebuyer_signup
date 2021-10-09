<?php
use Xmf\Request; //是用來接收並過濾各種外來變數用的物件
use XoopsModules\Leebuyer_signup\Leebuyer_signup_data;
use XoopsModules\Tadtools\Utility;

/*-----------引入檔案區--------------*/
require_once __DIR__ . '/header.php';
$GLOBALS['xoopsOption']['template_main'] = 'leebuyer_signup_index.tpl'; //寫這段時必須到xoops_version.php設定檔註冊
require_once XOOPS_ROOT_PATH . '/header.php';

/*-----------變數過濾----------*/
$op = Request::getString('op');
$uid = Request::getInt('uid');

/*-----------執行動作判斷區----------*/
switch ($op) {

    default:

        Leebuyer_signup_data::my($uid);
        $op = 'leebuyer_signup_data_my';

        break;
}

/*-----------function區--------------*/

/*-----------秀出結果區--------------*/
unset($_SESSION['api_mode']);
$xoopsTpl->assign('toolbar', Utility::toolbar_bootstrap($interface_menu));
$xoopsTpl->assign('now_op', $op);
$xoTheme->addStylesheet(XOOPS_URL . '/modules/leebuyer_signup/css/module.css');
require_once XOOPS_ROOT_PATH . '/footer.php';
