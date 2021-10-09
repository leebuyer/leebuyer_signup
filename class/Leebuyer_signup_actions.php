<?php
// 如「模組目錄」= signup，則「首字大寫模組目錄」= Signup
// 如「資料表名」= actions，則「模組物件」= Actions

namespace XoopsModules\Leebuyer_signup;

use XoopsModules\Leebuyer_signup\Leebuyer_signup_data;
use XoopsModules\Tadtools\BootstrapTable; //tadtool內之小月曆，可做欄位排序、搜尋....等功能
use XoopsModules\Tadtools\FormValidator;
use XoopsModules\Tadtools\My97DatePicker;
use XoopsModules\Tadtools\SweetAlert;
use XoopsModules\Tadtools\Utility;

class Leebuyer_signup_actions//命名與檔名相同

{
    //列出所有資料
    public static function index()
    {
        global $xoopsTpl;

        $all_data = self::get_all();
        $xoopsTpl->assign('all_data', $all_data);
    }

    //編輯表單
    public static function create($id = '')
    {
        global $xoopsTpl, $xoopsUser;
        //防止網址輸入觀看表單之轉向，配合$uid = $xoopsUser ? $xoopsUser->uid() : 0;才不致報錯
        if (!$_SESSION['leebuyer_signup_adm']) {
            redirect_header($_SERVER['PHP_SELF'], 3, "非管理員，無法執行此動作");
        }

        //抓取預設值(抓取該活動預設值以及設定表單的預設值)
        $db_values = empty($id) ? [] : self::get($id);
        $db_values['number'] = empty($id) ? 50 : $db_values['number'];
        $db_values['enable'] = empty($id) ? 1 : $db_values['enable'];

        foreach ($db_values as $col_name => $col_val) {
            $$col_name = $col_val;
            $xoopsTpl->assign($col_name, $col_val);
        }

        $op = empty($id) ? "leebuyer_signup_actions_store" : "leebuyer_signup_actions_update";
        $xoopsTpl->assign('next_op', $op);

        //套用formValidator驗證機制
        $formValidator = new FormValidator("#myForm", true);
        $formValidator->render();

        //加入Token安全機制
        include_once $GLOBALS['xoops']->path('class/xoopsformloader.php');
        $token = new \XoopsFormHiddenToken();
        $token_form = $token->render();
        $xoopsTpl->assign("token_form", $token_form);

        $uid = $xoopsUser ? $xoopsUser->uid() : 0;
        $xoopsTpl->assign("uid", $uid);

        My97DatePicker::render(); //把小月曆所需之javascript與css引入，另在樣板之日期時間input內加入onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss' , startDate:'%y-%M-%d %H:%m:%s}'})"
    }

    //新增資料
    public static function store()
    {
        global $xoopsDB;

        //防止網址輸入觀看表單之轉向，配合$uid = $xoopsUser ? $xoopsUser->uid() : 0;才不致報錯
        if (!$_SESSION['leebuyer_signup_adm']) {
            redirect_header($_SERVER['PHP_SELF'], 3, "您沒有權限使用此功能");
        }

        //XOOPS表單安全檢查
        Utility::xoops_security_check();

        $myts = \MyTextSanitizer::getInstance(); //建立資料過濾工具

        foreach ($_POST as $var_name => $var_val) {
            $$var_name = $myts->addSlashes($var_val); //替特殊符號加入脫逸斜線，以順利存入資料庫中
        }

        //寫入時過濾數字
        $uid = (int) $uid;
        $number = (int) $number;
        $enable = (int) $enable;

        $sql = "insert into `" . $xoopsDB->prefix("leebuyer_signup_actions") . "` (
            `title`,
            `detail`,
            `action_date`,
            `end_date`,
            `number`,
            `setup`,
            `enable`,
            `uid`
        ) values(
            '{$title}',
            '{$detail}',
            '{$action_date}',
            '{$end_date}',
            '{$number}',
            '{$setup}',
            '{$enable}',
            '{$uid}'
        )";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__); //當無法執行時顯示錯誤訊息

        //取得最後新增資料的流水編號
        $id = $xoopsDB->getInsertId(); //只有在寫入時才用到，取得當下寫入資料的流水號
        return $id;
    }

    //以流水號秀出某筆資料內容
    public static function show($id = '')
    {
        global $xoopsDB, $xoopsTpl, $xoopsUser;

        if (empty($id)) {
            return;
        }

        //過濾數字
        $id = (int) $id;
        //取得某筆資料成一維陣列
        $data = self::get($id);

        $myts = \MyTextSanitizer::getInstance(); //建立資料過濾工具
        foreach ($data as $col_name => $col_val) { //把一維陣列一筆一筆抽出來
            //$col_val = $myts->htmlSpecialChars($col_val); //之後沒有要針對每個變數做什麼事，而是直接送到樣板，故不用$$var_name = $myts->htmlSpecialChars($var_val);

            //過濾讀出的變數值 displayTarea($text, $html=0, $smiley=1, $xcode=1, $image=1, $br=1);
            // $data['大量文字欄'] = $myts->displayTarea($data['大量文字欄'], 0, 1, 0, 1, 1);(非所件即所得編輯器用參數0, 1, 0, 1, 1。用所件即所得編輯器用參數1, 0, 0, 0, 0)
            // $data['HTML文字欄'] = $myts->displayTarea($data['HTML文字欄'], 1, 0, 0, 0, 0);

            //過濾"讀出"的變數值
            if ($col_name == 'detail') {
                $col_val = $myts->displayTarea($col_val, 0, 1, 0, 1, 1);
            } else {
                $col_val = $myts->htmlSpecialChars($col_val);
            }

            $xoopsTpl->assign($col_name, $col_val);
        }
        $SweetAlert = new SweetAlert();
        $SweetAlert->render("del_action", "index.php?op=leebuyer_signup_actions_destroy&id=", 'id'); //del_action是javascript函數的名字，index.php?op=leebuyer_signup_actions_destroy&id="是原先刪除的聯結，d="後面因是活的所以不要寫值，id是參數名稱。在樣板刪除連結處要帶入javascript:del_action('<{$id}>')

        $signup = Leebuyer_signup_data::get_all($id, null, true); //$auto_key預設是false，用本身流水號當索引,現true重新編號01234...，優點是一定會有0，沒0表格就不會出現
        $xoopsTpl->assign('signup', $signup);

        BootstrapTable::render(); //自動載入javascript、css等所需工具

        //註冊會員，uid編號送至樣板後判斷是否資料讀出的這個人
        $uid = $xoopsUser ? $xoopsUser->uid() : 0;
        $xoopsTpl->assign("uid", $uid);

    }

    //更新某一筆資料
    public static function update($id = '')
    {
        global $xoopsDB;

        //防止網址輸入觀看表單之轉向，配合$uid = $xoopsUser ? $xoopsUser->uid() : 0;才不致報錯
        if (!$_SESSION['leebuyer_signup_adm']) {
            redirect_header($_SERVER['PHP_SELF'], 3, "非管理員，無法執行此動作");
        }

        //XOOPS表單安全檢查
        Utility::xoops_security_check();

        $myts = \MyTextSanitizer::getInstance(); //建立資料過濾工具

        foreach ($_POST as $var_name => $var_val) {
            $$var_name = $myts->addSlashes($var_val); //替特殊符號加入脫逸斜線，以順利存入資料庫中
        }

        //過濾數字
        $uid = (int) $uid;
        $number = (int) $number;
        $enable = (int) $enable;

        $sql = "update `" . $xoopsDB->prefix("leebuyer_signup_actions") . "` set
        `title` = '{$title}',
        `detail` = '{$detail}',
        `action_date` = '{$action_date}',
        `end_date` = '{$end_date}',
        `number` = '{$number}',
        `setup` = '{$setup}',
        `enable` = '{$enable}',
        `uid` = '{$uid}'
        where `id` = '$id'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        return $id;
    }

    //刪除某筆資料資料
    public static function destroy($id = '')
    {
        global $xoopsDB;

        //防止網址輸入觀看表單之轉向，配合$uid = $xoopsUser ? $xoopsUser->uid() : 0;才不致報錯
        if (!$_SESSION['leebuyer_signup_adm']) {
            redirect_header($_SERVER['PHP_SELF'], 3, "您沒有權限使用此功能");
        }

        if (empty($id)) {
            return;
        }

        $sql = "delete from `" . $xoopsDB->prefix("leebuyer_signup_actions") . "`
        where `id` = '{$id}'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    }

    //以流水號取得某筆資料
    public static function get($id = '')
    {
        global $xoopsDB;

        if (empty($id)) {
            return;
        }

        $sql = "select * from `" . $xoopsDB->prefix("leebuyer_signup_actions") . "`
        where `id` = '{$id}'";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $data = $xoopsDB->fetchArray($result); //fetchArray把欄位名稱當作索引值
        return $data;
    }

    //取得所有資料陣列
    public static function get_all($only_enable = true, $auto_key = false)
    {
        global $xoopsDB;
        $myts = \MyTextSanitizer::getInstance(); //建立資料過濾工具
        //index()則利用類別中的 get_all()來取得該資料表所有值，我們添加一個參數參數 $only_enable 用來指示是僅列出已啟用（包含未過期活動），還是全部都列出。
        $and_enable = $only_enable ? "and `enable` = '1' and `action_date` >= now()" : ''; //1是恆成立，什麼都篩
        $sql = "select * from `" . $xoopsDB->prefix("leebuyer_signup_actions") . "` where 1 $and_enable";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $data_arr = [];
        while ($data = $xoopsDB->fetchArray($result)) {

            // $data['文字欄'] = $myts->htmlSpecialChars($data['文字欄']);
            // $data['大量文字欄'] = $myts->displayTarea($data['大量文字欄'], 0, 1, 0, 1, 1);
            // $data['HTML文字欄'] = $myts->displayTarea($data['HTML文字欄'], 1, 0, 0, 0, 0);
            // $data['數字欄'] = (int) $data['數字欄'];

            $data['title'] = $myts->htmlSpecialChars($data['title']); //過濾
            $data['detail'] = $myts->displayTarea($data['detail'], 0, 1, 0, 1, 1); //過濾
            $data['setup'] = $myts->displayTarea($data['setup'], 0, 1, 0, 1, 1); //過濾

            $data['signup'] = Leebuyer_signup_data::get_all($data['id']); //活動報名完整資料

            if ($_SESSION['api_mode'] or $auto_key) { //api_mode，$auto_key控制索引值要採取哪一種
                $data_arr[] = $data;
            } else {
                $data_arr[$data['id']] = $data; //用活動流水號(id編號)當作索引值
            }
        }
        return $data_arr;
    }

}
