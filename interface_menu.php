<?php
use XoopsModules\Tadtools\Utility;

//判斷是否對該模組有管理權限 $_SESSION['leebuyer_signup_admin']，以下幾行的目的就是要產生這個值leebuyer_signup_admin
$is_admin = basename(__DIR__) . '_adm';
if (!isset($_SESSION[$is_admin])) {
    $_SESSION[$is_admin] = ($xoopsUser) ? $xoopsUser->isAdmin() : false;
}

//判斷有無開設活動的權限
if (!isset($_SESSION['can_add'])) {
    $_SESSION['can_add'] = utility::power_chk('leebuyer_signup', '1');
}

//回模組首頁
$interface_menu[_TAD_TO_MOD] = "index.php";
$interface_icon[_TAD_TO_MOD] = "fa-chevron-right";

//新增我的報名記錄選單
$interface_menu['我的報名記錄'] = "my_signup.php";
$interface_icon['我的報名記錄'] = "fa-chevron-right";

//模組後台
if ($_SESSION[$is_admin]) {
    $interface_menu[_TAD_TO_ADMIN] = "admin/main.php";
    $interface_icon[_TAD_TO_ADMIN] = "fa-chevron-right";
}
