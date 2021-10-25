<?php

use XoopsModules\Leebuyer_signup\Update;
if (!class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}

use XoopsModules\Tadtools\Utility;
if (!class_exists('XoopsModules\Leebuyer_signup\Update')) {
    require dirname(__DIR__) . '/preloads/autoloader.php';
}

// 安裝前
function xoops_module_pre_install_leebuyer_signup(XoopsModule $module) //$module是目前這個模組

{

}

// 安裝後
function xoops_module_install_leebuyer_signup(XoopsModule $module)
{
    // 有上傳功能才需要
    Utility::mk_dir(XOOPS_ROOT_PATH . "/uploads/leebuyer_signup");
    // 若有用到CKEditor編輯器才需要
    Utility::mk_dir(XOOPS_ROOT_PATH . "/uploads/leebuyer_signup/file");
    Utility::mk_dir(XOOPS_ROOT_PATH . "/uploads/leebuyer_signup/image");
    Utility::mk_dir(XOOPS_ROOT_PATH . "/uploads/leebuyer_signup/image/.thumbs");

    $groupid = update::mk_group("活動報名管理");

    $perm_handler = xoops_getHandler('groupperm');
    $perm = $perm_handler->create();
    $perm->setVar('gperm_groupid', $groupid); //群組編號
    $perm->setVar('gperm_itemid', 1); //權限編號
    $perm->setVar('gperm_name', $xoopsModule->dirname()); //權限名稱。一般為模組目錄名稱
    $perm->setVar('gperm_modid', $xoopsModule->mid()); //模組編號
    $perm_handler->insert($perm);

    return true;
}
