<?php
// 如「模組目錄」= signup，則「首字大寫模組目錄」= Signup
// 如「資料表名」= actions，則「模組物件」= Actions

namespace XoopsModules\Leebuyer_signup;

use XoopsModules\Leebuyer_signup\Leebuyer_signup_actions;
use XoopsModules\Tadtools\BootstrapTable;
use XoopsModules\Tadtools\FormValidator;
use XoopsModules\Tadtools\SweetAlert;
use XoopsModules\Tadtools\TadDataCenter;
use XoopsModules\Tadtools\Utility;

class Leebuyer_signup_data
{
    //列出所有資料
    public static function index($action_id) //($action_id)是必填，沒有預設值($action_id="")

    {
        global $xoopsTpl;

        $all_data = self::get_all($action_id); //$action_id必須經由index($action_id)給預設值進來
        $xoopsTpl->assign('all_data', $all_data);
    }

    //編輯表單
    public static function create($action_id, $id = '')
    {
        global $xoopsTpl, $xoopsUser;

        /************報名內容************/

        //抓取預設值
        $db_values = empty($id) ? [] : self::get($id);

        foreach ($db_values as $col_name => $col_val) {
            $$col_name = $col_val;
            $xoopsTpl->assign($col_name, $col_val);
        }

        $op = empty($id) ? "leebuyer_signup_data_store" : "leebuyer_signup_data_update";
        $xoopsTpl->assign('next_op', $op);

        //套用formValidator驗證機制
        $formValidator = new FormValidator("#myForm", true);
        $formValidator->render();

        //加入Token安全機制
        include_once $GLOBALS['xoops']->path('class/xoopsformloader.php');
        $token = new \XoopsFormHiddenToken();
        $token_form = $token->render();
        $xoopsTpl->assign("token_form", $token_form);

        /************活動內容************/

        //用類別的方式抓出
        $action = Leebuyer_signup_actions::get($action_id); //抓筆資料要給它編號$action_id，要在function參數的地方給個入口$action_id，在index.php流程處create也要給$action_id(要看來源是否存在)。

        if (time() > strtotime($action['end_date'])) { //time()指現在時間，strtotime轉換成時間戳記
            redirect_header($_SERVER['PHP_SELF'], 3, "已報名截止，無法再進行報名或修改報名！");
        } elseif (count($action['signup']) >= $action['number']) {
            redirect_header($_SERVER['PHP_SELF'], 3, "人數已滿，無法再進行報名！");
        }

        $myts = \MyTextSanitizer::getInstance(); //建立資料過濾工具
        foreach ($action as $col_name => $col_val) {

            //過濾"讀出"的變數值
            if ($col_name == 'detail') {
                $col_val = $myts->displayTarea($col_val, 0, 1, 0, 1, 1);
            } else {
                $col_val = $myts->htmlSpecialChars($col_val);
            }

            $action[$col_name] = $col_val; //把原始資料過濾完塞回原陣列
        }
        $xoopsTpl->assign("action", $action); //把活動的陣列送去表單，如此不會後面蓋前面

        /************使用者本身資料************/
        $uid = $xoopsUser ? $xoopsUser->uid() : 0;
        $xoopsTpl->assign("uid", $uid);

        //儲存資料
        $TadDataCenter = new TadDataCenter('leebuyer_signup'); //new之後要填入模組名稱，會自動去抓編號，亦是資料庫內mid欄位模組編號
        $TadDataCenter->set_col('id', $id); //修改時必須告知綁定方法，當初是用id綁定並抓出編號，綁定上了就會把預設值還原回來
        $signup_form = $TadDataCenter->strToForm($action['setup']); //strToForm把eguide標籤語法轉換成真正的表單，setup就是在做這事
        $xoopsTpl->assign('signup_form', $signup_form);
    }
    //新增資料
    public static function store()
    {
        global $xoopsDB;

        //XOOPS表單安全檢查
        Utility::xoops_security_check();

        $myts = \MyTextSanitizer::getInstance();

        foreach ($_POST as $var_name => $var_val) { //把post傳來的東西過濾，完後塞回原來變數值
            $$var_name = $myts->addSlashes($var_val);
        }

        $action_id = (int) $action_id;
        $uid = (int) $uid;

        $sql = "insert into `" . $xoopsDB->prefix("leebuyer_signup_data") . "` (
            `action_id`,
            `uid`,
            `signup_date`
            ) values(
            '{$action_id}',
            '{$uid}',
            now()
        )";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        //取得最後新增資料的流水編號
        $id = $xoopsDB->getInsertId();

        //儲存資料
        $TadDataCenter = new TadDataCenter('leebuyer_signup');
        $TadDataCenter->set_col('id', $id); //綁定這個值
        $TadDataCenter->saveData();

        return $id;
    }

    //以流水號秀出某筆資料內容
    public static function show($id = '')
    {
        global $xoopsDB, $xoopsTpl, $xoopsUser;

        if (empty($id)) {
            return;
        }

        $id = (int) $id;
        $data = self::get($id); //資料庫把資料抓出來

        $myts = \MyTextSanitizer::getInstance();
        //報名
        foreach ($data as $col_name => $col_val) {
            $col_val = $myts->htmlSpecialChars($col_val); //此可過濾數字跟文字
            $xoopsTpl->assign($col_name, $col_val);
            $$col_name = $col_val; //把過濾完的值指派給當初這個變數(跑$$col_name這一行即會跑5次迴圈，產生leebuyer_signup_dara資料表內的5個值(變數)到樣板檔，重組回來成單一個變數之意)
        }

        //取得資料陣列
        $TadDataCenter = new TadDataCenter('leebuyer_signup'); //leebuyer_signup目錄名稱
        $TadDataCenter->set_col('id', $id); //此id為重新迴圈跑完後之id
        $tdc = $TadDataCenter->getData(); //getData()取得綁定的相關資料，現在是綁定id的相關資料
        //Utility::dd($tdc);
        $xoopsTpl->assign('tdc', $tdc);

        //活動
        $action = Leebuyer_signup_actions::get($action_id); //跑5次迴圈其中一個就是$action_id
        foreach ($action as $col_name => $col_val) {
            //過濾讀出的變數值
            if ($col_name == 'detail') {
                $col_val = $myts->displayTarea($col_val, 0, 1, 0, 1, 1);
            } else {
                $col_val = $myts->htmlSpecialChars($col_val);
            }
            $action[$col_name] = $col_val; //再塞回陣列
        }
        $xoopsTpl->assign('action', $action);

        $now_uid = $xoopsUser ? $xoopsUser->uid() : 0;
        $xoopsTpl->assign("now_uid", $now_uid);

        $SweetAlert = new SweetAlert();
        $SweetAlert->render("del_data", "index.php?op=leebuyer_signup_data_destroy&action_id={$action_id}&id=", 'id'); //要刪的是報名編號，非活動編號，故將會變動的id變數放在後面且把值移除以javascript靈活保持彈性，以接當下的值傳進去
    }

    //更新某一筆資料
    public static function update($id = '')
    {
        global $xoopsDB, $xoopsUser;

        //XOOPS表單安全檢查
        Utility::xoops_security_check();

        $myts = \MyTextSanitizer::getInstance();

        foreach ($_POST as $var_name => $var_val) {
            $$var_name = $myts->addSlashes($var_val);
        }

        $action_id = (int) $action_id;
        $uid = (int) $uid;

        $now_uid = $xoopsUser ? $xoopsUser->uid() : 0;

        $sql = "update `" . $xoopsDB->prefix("leebuyer_signup_data") . "` set
        `signup_date` = now()

        where `id` = '$id' and `uid` =' $now_uid'";
        if ($xoopsDB->queryF($sql)) {
            //取得資料陣列
            $TadDataCenter = new TadDataCenter('leebuyer_signup');
            $TadDataCenter->set_col('id', $id); //此id為重新迴圈跑完後之id
            $TadDataCenter->saveData();
        } else {
            Utility::web_error($sql, __FILE__, __LINE__);
        }

        return $id;
    }

    //刪除某筆資料資料
    public static function destroy($id = '')
    {
        global $xoopsDB, $xoopsUser;

        if (empty($id)) {
            return;
        }

        $now_uid = $xoopsUser ? $xoopsUser->uid() : 0;

        $sql = "delete from `" . $xoopsDB->prefix("leebuyer_signup_data") . "`
        where `id` = '{$id}' and `uid` = '$now_uid'";
        if ($xoopsDB->queryF($sql)) {
            $TadDataCenter = new TadDataCenter('leebuyer_signup');
            $TadDataCenter->set_col('id', $id);
            $TadDataCenter->delData();
        } else {
            Utility::web_error($sql, __FILE__, __LINE__);
        }
    }

    //以流水號取得某筆資料
    public static function get($id = '')
    {
        global $xoopsDB;

        if (empty($id)) {
            return;
        }

        $sql = "select * from `" . $xoopsDB->prefix("leebuyer_signup_data") . "`
        where `id` = '{$id}'";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $data = $xoopsDB->fetchArray($result);
        return $data;
    }

    //取得所有資料陣列
    public static function get_all($action_id = '', $uid = '', $auto_key = false) //在本檔案之public static function index()處有用到get_all()，故要付予$action值

    {
        global $xoopsDB, $xoopsUser;
        $myts = \MyTextSanitizer::getInstance();

        if ($action_id) {
            $sql = "select * from `" . $xoopsDB->prefix("leebuyer_signup_data") . "` where `action_id`='$action_id' order by `signup_date`";
        } else {
            if (!$_SESSION['leebuyer_signup_adm'] or !$uid) {
                $uid = $xoopsUser ? $xoopsUser->uid() : 0;
            }
            $sql = "select * from `" . $xoopsDB->prefix("leebuyer_signup_data") . "` where `uid`='$uid' order by `signup_date`";
        }

        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $data_arr = [];

        $TadDataCenter = new TadDataCenter('leebuyer_signup'); //實體化。leebuyer_signup是目錄名稱
        while ($data = $xoopsDB->fetchArray($result)) {

            // $data['文字欄'] = $myts->htmlSpecialChars($data['文字欄']);
            // $data['大量文字欄'] = $myts->displayTarea($data['大量文字欄'], 0, 1, 0, 1, 1);
            // $data['HTML文字欄'] = $myts->displayTarea($data['HTML文字欄'], 1, 0, 0, 0, 0);
            // $data['數字欄'] = (int) $data['數字欄'];

            //取得資料陣列
            $TadDataCenter->set_col('id', $data['id']); //此id為重新迴圈跑完後之id
            $data['tdc'] = $TadDataCenter->getData(); //getData()取得綁定的相關資料，現在是綁定id的相關資料
            $data['action'] = Leebuyer_signup_actions::get($data['action_id']);

            if ($_SESSION['api_mode'] or $auto_key) {
                $data_arr[] = $data;
            } else {
                $data_arr[$data['id']] = $data;
            }
        }
        return $data_arr;
    }

    //查詢某人的報名記錄
    public static function my($uid)
    {
        global $xoopsTpl, $xoopsUser;

        $my_signup = self::get_all(null, $uid);
        $xoopsTpl->assign('my_signup', $my_signup);
        BootstrapTable::render();
    }

    public static function accept($id, $accept)
    {
        global $xoopsDB, $xoopsUser;

        //防止網址輸入觀看表單之轉向，配合$uid = $xoopsUser ? $xoopsUser->uid() : 0;才不致報錯
        if (!$_SESSION['leebuyer_signup_adm']) {
            redirect_header($_SERVER['PHP_SELF'], 3, "非管理員，您沒有權限使用此功能");
        }

        $id = (int) $id;
        $accept = (int) $accept;

        $sql = "update `" . $xoopsDB->prefix("leebuyer_signup_data") . "` set
        `accept` = '$accept'
        where `id` = '$id'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    }

}
