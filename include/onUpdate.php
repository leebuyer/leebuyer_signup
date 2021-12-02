<?php

use XoopsModules\Leebuyer_signup\Update;
if (!class_exists('XoopsModules\Leebuyer_signup\Update')) {
    require dirname(__DIR__) . '/preloads/autoloader.php';
}

use XoopsModules\Tadtools\Utility;
if (!class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}

// 更新前
function xoops_module_pre_update_leebuyer_signup(XoopsModule $module, $old_version) //$old_version是版本判別

{
    // 有上傳功能才需要
    Utility::mk_dir(XOOPS_ROOT_PATH . "/uploads/leebuyer_signup");
    // 若有用到CKEditor編輯器才需要
    Utility::mk_dir(XOOPS_ROOT_PATH . "/uploads/leebuyer_signup/file");
    Utility::mk_dir(XOOPS_ROOT_PATH . "/uploads/leebuyer_signup/image");
    Utility::mk_dir(XOOPS_ROOT_PATH . "/uploads/leebuyer_signup/image/.thumbs");

    $gperm_handler = xoops_getHandler('groupperm');
    $groupid = Update::mk_group(_MI_LEEBUYER_SIGNUP_ADMIN);

    if (!$gperm_handler->checkRight($module->dirname(), 1, $groupid, $module->mid())) { //在tadtools/class/power_chk()函數內會用到內鍵的工具checkRight()。判斷此模組1號權限,對此群組是否有執行?是否有權限?如果沒有做下面動作。如此在xx_group_permission資料表每更新一次就會多一筆資料
        $perm_handler = xoops_getHandler('groupperm');
        $perm = $perm_handler->create();
        $perm->setVar('gperm_groupid', $groupid);
        $perm->setVar('gperm_itemid', 1);
        $perm->setVar('gperm_name', $module->dirname()); //一般為模組目錄名稱
        $perm->setVar('gperm_modid', $module->mid());
        $perm_handler->insert($perm);
    }
    return true;
}

// 更新後
function xoops_module_update_leebuyer_signup(XoopsModule $module, $old_version)
{
    global $xoopsDB;

    //新增候補欄位
    if (Update::chk_candidate()) {
        Update::go_candidate();
    }

    return true;
}
