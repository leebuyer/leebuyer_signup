<?php
// 如「模組目錄」= signup，則「首字大寫模組目錄」= Signup
// 如「資料表名」= actions，則「模組物件」= Actions
use Xmf\Request; //是用來接收並過濾各種外來變數用的物件
use XoopsModules\Leebuyer_signup\Leebuyer_signup_actions;
use XoopsModules\Leebuyer_signup\Leebuyer_signup_data;
use XoopsModules\Tadtools\Utility;

/*-----------引入檔案區--------------*/
require_once __DIR__ . '/header.php';
$GLOBALS['xoopsOption']['template_main'] = 'leebuyer_signup_index.tpl'; //寫這段時必須到xoops_version.php設定檔註冊
require_once XOOPS_ROOT_PATH . '/header.php';

/*-----------變數過濾----------*/
$op = Request::getString('op');
$id = Request::getInt('id');
$action_id = Request::getInt('action_id');

/*-----------執行動作判斷區----------*/
switch ($op) {

    //新增活動表單
    case 'leebuyer_signup_actions_create':
        Leebuyer_signup_actions::create();
        break;

    //新增活動資料
    case 'leebuyer_signup_actions_store':
        $id = Leebuyer_signup_actions::store();
        //header("location: {$_SERVER['PHP_SELF']}?id=$id");
        redirect_header($_SERVER['PHP_SELF'] . "?id=$id", 3, "成功建立活動！");
        exit;

    //修改用表單
    case 'leebuyer_signup_actions_edit':
        Leebuyer_signup_actions::create($id);
        $op = 'leebuyer_signup_actions_create';
        break;

    //更新資料
    case 'leebuyer_signup_actions_update':
        Leebuyer_signup_actions::update($id);
        //header("location: {$_SERVER['PHP_SELF']}?id=$id");
        redirect_header($_SERVER['PHP_SELF'] . "?id=$id", 3, "已成功修改活動！");
        exit;

    //刪除資料
    case 'leebuyer_signup_actions_destroy':
        Leebuyer_signup_actions::destroy($id);
        //header("location: {$_SERVER['PHP_SELF']}");
        redirect_header($_SERVER['PHP_SELF'], 3, "已成功刪除活動！");
        exit;

    //新增報名表單
    case 'leebuyer_signup_data_create':
        Leebuyer_signup_data::create($action_id);
        break;

    //新增報名資料
    case 'leebuyer_signup_data_store':
        $id = Leebuyer_signup_data::store();
        //header("location: {$_SERVER['PHP_SELF']}?op=leebuyer_signup_data_show&id=$id");
        redirect_header("{$_SERVER['PHP_SELF']}?op=leebuyer_signup_data_show&id=$id", 3, "已成功新增報名！");
        exit;

    //顯示報名表
    case 'leebuyer_signup_data_show':
        Leebuyer_signup_data::show($id);
        break;

    //修改報名表單
    case 'leebuyer_signup_data_edit':
        Leebuyer_signup_data::create($action_id, $id);
        $op = 'leebuyer_signup_data_create';
        break;

    //更新報名資料
    case 'leebuyer_signup_data_update':
        Leebuyer_signup_data::update($id);
        //header("location: {$_SERVER['PHP_SELF']}?op=leebuyer_signup_data_show&id=$id");
        redirect_header($_SERVER['PHP_SELF'] . "?op=leebuyer_signup_data_show&id=$id", 3, "已成功修改報名資料！");
        exit;

    //刪除報名資料
    case 'leebuyer_signup_data_destroy':
        Leebuyer_signup_data::destroy($id);
        //header("location: {$_SERVER['PHP_SELF']?id=$action_id}");
        redirect_header($_SERVER['PHP_SELF'] . "?id=$action_id", 3, "已成功刪除報名資料！");
        exit;

    default:
        if (empty($id)) {
            Leebuyer_signup_actions::index(); //Leebuyer_signup_actions 是類別（class），:: 是呼叫類別的靜態方法（無須用new去進行實例化），index()就是類別的靜態方法（函式）
            $op = 'leebuyer_signup_actions_index';
        } else {
            Leebuyer_signup_actions::show($id);
            $op = 'leebuyer_signup_actions_show';
        }
        break;
}

/*-----------function區--------------*/

/*-----------秀出結果區--------------*/
unset($_SESSION['api_mode']);
$xoopsTpl->assign('toolbar', Utility::toolbar_bootstrap($interface_menu));
$xoopsTpl->assign('now_op', $op);
$xoTheme->addStylesheet(XOOPS_URL . '/modules/leebuyer_signup/css/module.css');
require_once XOOPS_ROOT_PATH . '/footer.php';
