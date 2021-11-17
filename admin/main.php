<?php
// 如「模組目錄」= signup，則「首字大寫模組目錄」= Signup
// 如「資料表名」= actions，則「模組物件」= Actions
use Xmf\Request;
use XoopsModules\Leebuyer_signup\Leebuyer_signup_actions;

/*-----------引入檔案區--------------*/
$GLOBALS['xoopsOption']['template_main'] = 'leebuyer_signup_admin.tpl';
require_once __DIR__ . '/header.php'; //後台要先引入樣板再引入header.php。跟前台相反
require_once dirname(__DIR__) . '/function.php';
$_SESSION['leebuyer_signup_adm'] = true; //若安裝完直接進入後台的話，$_SESSION['leebuyer_signup_adm']此值沒產生的話就會判斷不過，能進入後台就是管理員，所以賦予true
$_SESSION['can_add'] = true;

/*-----------變數過濾----------*/
$op = Request::getString('op');
$id = Request::getInt('id');

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
        redirect_header($_SERVER['PHP_SELF'] . "?id=$id", 3, _MA_LEEBUYER_SIGNUP_CREATE_SUCCESS);
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
        redirect_header($_SERVER['PHP_SELF'] . "?id=$id", 3, _MA_LEEBUYER_SIGNUP_UPDATE_SUCCESS);
        exit;

    //刪除資料
    case 'leebuyer_signup_actions_destroy':
        Leebuyer_signup_actions::destroy($id);
        //header("location: {$_SERVER['PHP_SELF']}");
        redirect_header($_SERVER['PHP_SELF'], 3, _MA_LEEBUYER_SIGNUP_DESTROY_SUCCESS);
        exit;

    default:
        if (empty($id)) {
            Leebuyer_signup_actions::index(false); //Leebuyer_signup_actions 是類別（class），:: 是呼叫類別的靜態方法（無須用new去進行實例化），index()就是類別的靜態方法（函式）
            $op = 'leebuyer_signup_actions_index';
        } else {
            Leebuyer_signup_actions::show($id);
            $op = 'leebuyer_signup_actions_show';
        }
        break;
}

/*-----------功能函數區----------*/

/*-----------秀出結果區--------------*/
$xoopsTpl->assign('now_op', $op);
$xoTheme->addStylesheet('/modules/tadtools/css/font-awesome/css/font-awesome.css');
$xoTheme->addStylesheet(XOOPS_URL . '/modules/tadtools/css/xoops_adm4.css');
$xoTheme->addStylesheet(XOOPS_URL . '/modules/leebuyer_signup/css/module.css');
require_once __DIR__ . '/footer.php';
