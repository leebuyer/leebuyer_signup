<?php
use XoopsModules\Leebuyer_signup\Update;
if (!class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}

use XoopsModules\Tadtools\Utility;
if (!class_exists('XoopsModules\Leebuyer_signup\Update')) {
    require dirname(__DIR__) . '/preloads/autoloader.php';
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
    $groupid = Update::mk_group("活動報名管理");

    if (!$gperm_handler->checkRight($module->dirname(), 1, $groupid, $module->mid())) {
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

    // if (Update::chk_1()) {
    //     Update::go_1();
    // }

    return true;
}
