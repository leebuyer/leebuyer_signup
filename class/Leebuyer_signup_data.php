<?php
// 如「模組目錄」= signup，則「首字大寫模組目錄」= Signup
// 如「資料表名」= actions，則「模組物件」= Actions

namespace XoopsModules\Leebuyer_signup;

use XoopsModules\Leebuyer_signup\Leebuyer_signup_actions;
use XoopsModules\Tadtools\BootstrapTable;
use XoopsModules\Tadtools\FormValidator;
use XoopsModules\Tadtools\SweetAlert;
use XoopsModules\Tadtools\TadDataCenter;
use XoopsModules\Tadtools\Tmt;
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

        //搜尋其他地方有用到 get() 的地方，看是否要加入，修改 create() 時，若不是管理員，就強制只能讀取自己的資料，讀不到資料就轉走。
        $uid = $_SESSION['can_add'] ? null : $xoopsUser->uid(); //是管理員的話就不抓是空的，不能是零，若不是管理員，只能抓取目前登入者

        /************報名內容************/

        //抓取預設值
        $db_values = empty($id) ? [] : self::get($id, $uid); //$uid要麻就是空的，不能為零

        if ($id and empty($db_values)) {
            redirect_header($_SERVER['PHP_SELF'] . "?id={$action_id}", 3, "查無報名無資料，無法修改！");
        }

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
        $action = Leebuyer_signup_actions::get($action_id, true); //抓筆資料要給它編號$action_id，要在function參數的地方給個入口$action_id，在index.php流程處create也要給$action_id(要看來源是否存在)。true是要過濾
        $signup = Leebuyer_signup_data::get_all($action_id);

        if (time() > strtotime($action['end_date'])) {
            redirect_header($_SERVER['PHP_SELF'], 3, "已報名截止，無法再進行報名或修改報名");
        } elseif (!$action['enable']) {
            redirect_header($_SERVER['PHP_SELF'], 3, "此報名已關閉，無法再進行報名或修改報名");
        } elseif ($action['signup_count'] >= ($action['number'] + $action['candidate']) && $op == 'leebuyer_signup_data_create') { //此判斷搭配op_leebuyer_signup_actions_index.tpl樣板立即報名連結
            redirect_header($_SERVER['PHP_SELF'], 3, "人數已滿，無法再進行報名");
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

        // 若是超過名額，註記為「候補」
        $action = Leebuyer_signup_actions::get($action_id);
        $action['signup'] = self::get_all($action_id);
        if (count($action['signup']) > $action['number']) {
            $TadDataCenter->set_col('data_id', $id); //綁定這個值
            //儲存資料
            $TadDataCenter->saveCustomData(['tag' => ['候補']]); //存入那個資料的name是tag此標籤，值是候補。此['tag' => ['候補']]是陣列，亦可加入其他東西
        }

        return $id;
    }

    //以流水號秀出某筆資料內容
    public static function show($id = '')
    {
        global $xoopsTpl, $xoopsUser;

        if (empty($id)) {
            return;
        }

        $uid = $_SESSION['can_add'] ? null : $xoopsUser->uid(); //是管理員的話就不抓是空的，不能是零，若不是管理員，只能抓取目前登入者

        $id = (int) $id;
        $data = self::get($id, $uid); //資料庫把資料抓出來

        if (empty($data)) {
            redirect_header($_SERVER['PHP_SELF'], 3, "查無報名資料，無法觀看！");
        }

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
        $action = Leebuyer_signup_actions::get($action_id, true); //跑5次迴圈其中一個就是$action_id。true是要過濾

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

    //刪除某筆資料
    public static function destroy($id = '')
    {
        global $xoopsDB, $xoopsUser;

        if (empty($id)) {
            return;
        }

        $now_uid = $xoopsUser ? $xoopsUser->uid() : 0;

        $sql = "delete from `" . $xoopsDB->prefix("leebuyer_signup_data") . "`
        where `id` = '{$id}' and `uid` = '{$now_uid}'";
        if ($xoopsDB->queryF($sql)) {
            $TadDataCenter = new TadDataCenter('leebuyer_signup');
            $TadDataCenter->set_col('id', $id);
            $TadDataCenter->delData();
        } else {
            Utility::web_error($sql, __FILE__, __LINE__);
        }
    }

    //以流水號取得某筆資料
    public static function get($id = '', $uid = '') //加入第二個參數 $uid，若該參數有值時，會同時加入篩選條件，確保只會篩出原始報名者資料

    {
        global $xoopsDB;

        if (empty($id)) {
            return;
        }

        $and_uid = $uid ? "and `uid` = '$uid'" : ''; //sql語法，判斷有值的話and `uid`等於我指定的$uid，如沒有傳進來表示不用判斷身分，$and_uid放到sql內

        $sql = "select * from `" . $xoopsDB->prefix("leebuyer_signup_data") . "`
        where `id` = '{$id}' $and_uid";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $data = $xoopsDB->fetchArray($result);
        return $data;
    }

    //取得所有資料陣列
    public static function get_all($action_id = '', $uid = '', $auto_key = false, $only_accept = false) //在本檔案之public static function index()處有用到get_all()，故要付予$action值

    {
        global $xoopsDB, $xoopsUser;
        $myts = \MyTextSanitizer::getInstance();

        $and_accept = $only_accept ? "and `accept`='1'" : '';

        if ($action_id) {
            $sql = "select * from `" . $xoopsDB->prefix("leebuyer_signup_data") . "` where `action_id`='$action_id' $and_accept order by `signup_date` desc";
        } else {
            if (!$_SESSION['can_add'] or !$uid) {
                $uid = $xoopsUser ? $xoopsUser->uid() : 0;
            }
            $sql = "select * from `" . $xoopsDB->prefix("leebuyer_signup_data") . "` where `uid`='$uid' $and_accept order by `signup_date`";
        }

        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $data_arr = [];

        $TadDataCenter = new TadDataCenter('leebuyer_signup'); //實體化。leebuyer_signup是目錄名稱
        while ($data = $xoopsDB->fetchArray($result)) {

            //取得資料陣列
            $TadDataCenter->set_col('id', $data['id']); //此id為重新迴圈跑完後之id
            $data['tdc'] = $TadDataCenter->getData(); //getData()取得xx_leebuyer_signup_data_center資料，綁定的相關資料，現在是綁定id的相關資料
            $data['action'] = Leebuyer_signup_actions::get($data['action_id'], true);
            //列出候補之小標籤(之後去修改 templates\op_leebuyer_signup_actions_show.tpl，在日期欄位加入候補標記 )
            $TadDataCenter->set_col('data_id', $data['id']); //綁定data_id
            $data['tag'] = $TadDataCenter->getData('tag', 0); //取出第0筆資料，只有一筆不會是陣列

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

        $my_signup = self::get_all(null, $uid); //只查指定uid的報名記錄
        $xoopsTpl->assign('my_signup', $my_signup);
        BootstrapTable::render();
    }

    public static function accept($id, $accept)
    {
        global $xoopsDB;

        //防止網址輸入觀看表單之轉向，配合$uid = $xoopsUser ? $xoopsUser->uid() : 0;才不致報錯
        if (!$_SESSION['can_add']) {
            redirect_header($_SERVER['PHP_SELF'], 3, "非管理員，您沒有權限使用此功能");
        }

        $id = (int) $id;
        $accept = (int) $accept;

        $sql = "update `" . $xoopsDB->prefix("leebuyer_signup_data") . "` set
        `accept` = '$accept'
        where `id` = '$id'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    }

    // 統計 radio、checkbox、select
    public static function statistics($setup, $signup = [])
    {
        $result = []; //初始值
        $setup_items = explode("\n", $setup); //explode(separator,string,limit)函數把字符串分割為數組。separator必需。規定在哪裡分割字符串。string    必需。要分割的字符串。limit    可選。規定所返回的數組元素的最大數目。屆時$setup_items會是陣列。statistics($setup, $signup = [])在..._action.php之show會呼叫他

        foreach ($setup_items as $setup_item) { //$setup_items是陣列就用foreach一個個的抽出來
            if (preg_match("/radio|checkbox|select/", $setup_item)) { //preg_match執行一個正則表達式匹配
                $items = explode(",", $setup_item); //用空值取代*號
                $title = str_replace('*', '', $items[0]);
                foreach ($signup as $data) { //不用取索引，$signup是報名資料，若有10個人報名就會跑10圈，$data是一個人的報名完整資料
                    foreach ($data['tdc'][$title] as $option) { //而我們只需要tdc裡面的資料，裡面資料現只取之前抓出來radio|checkbox|select3項
                        $result[$title][$option]++;
                    }
                }
            }
        }
        return $result;
    }

    //立即寄出(只純脆將信寄出)
    public static function send($title = "無標題", $content = "無內容", $email = "")
    {
        global $xoopsUser;
        if (empty($email)) {
            $email = $xoopsUser->email();
        }
        $xoopsMailer = xoops_getMailer();
        $xoopsMailer->multimailer->ContentType = "text/html";
        $xoopsMailer->addHeaders("MIME-Version: 1.0");
        $header = '';
        return $xoopsMailer->sendMail($email, $title, $content, $header);
    }

    //產生通知信
    public static function mail($id, $type, $signup = [])
    {
        global $xoopsUser;

        $id = (int) $id;
        if (empty($id)) {
            redirect_header($_SERVER['PHP_SELF'], 3, "沒有編號，無法寄出通知信！");
        }

        $signup = $signup ? $signup : self::get($id);

        $now = date("Y-m-d H:i:s");
        $name = $xoopsUser->name();
        $name = $name ? $name : $xoopsUser->uname(); //有姓名就抓姓名，若無就抓帳號

        $action = Leebuyer_signup_actions::get($signup['action_id']); //抓取活動編號資訊

        $member_handler = xoops_getHandler('member');
        $admUser = $member_handler->getUser($action['uid']); //給uid就會生出那一個使用者物件
        $adm_email = $admUser->email(); //得到email

        if ($type == 'destroy') {
            $title = "【{$action['title']}】取消報名通知";
            $head = "<p>您於{$signup['signup_date']}報名了【{$action['title']}】活動已於{$now}由{$name}取消報名！</p>";
            $foot = "欲重新報名，請連至" . XOOPS_URL . "/modules/leebuyer_signup/index.php?op=leebuyer_signup_data_create&action_id={$action['id']}";
        } elseif ($type == 'store') {
            $title = "【{$action['title']}】報名完成通知";
            $head = "<p>您於{$signup['signup_date']}報名了【{$action['title']}】活動已於{$now}由{$name}完成報名！</p>";
            $foot = "完整詳情，請連至" . XOOPS_URL . "/modules/leebuyer_signup/index.php?id={$signup['action_id']}";
        } elseif ($type == 'update') {
            $title = "【{$action['title']}】修改報名通知";
            $head = "<p>您於{$signup['signup_date']}報名了【{$action['title']}】活動已於{$now}由{$name}修改報名通知如下！</p>";
            $foot = "完整詳情，請連至" . XOOPS_URL . "/modules/leebuyer_signup/index.php?id={$signup['action_id']}";
        } elseif ($type == 'accept') {
            $title = "【{$action['title']}】報名錄取狀況通知";
            if ($signup['accept'] == 1) {
                $head = "<p>您於{$signup['signup_date']}報名了【{$action['title']}】活動已通過審核，<h2 style='color:#e699f2'>恭喜錄取！</h2></p>";
            } else {
                $head = "<p>您於{$signup['signup_date']}報名了【{$action['title']}】活動已審核，很遺憾的通知您，因名額有限，</p><span style='color:red;'>您並未錄取！</span>";
            }
            $foot = "完整詳情，請連至" . XOOPS_URL . "/modules/leebuyer_signup/index.php?id={$signup['action_id']}";

            //取得報名者的編號
            $signupUser = $member_handler->getUser($signup['uid']);
            $email = $signupUser->email();
        }

        $content = self::mk_content($id, $head, $foot, $action);

        //寄信失敗秀出訊息
        if (!self::send($title, $content, $email)) {
            redirect_header($_SERVER['PHP_SELF'], 3, "通知信寄發失敗！");
        }
        //寄信給管理員
        self::send($title, $content, $adm_email);

    }

    // 產生通知信內容
    public static function mk_content($id, $head = '', $foot = '', $action = [])
    {
        if ($id) {
            $TadDataCenter = new TadDataCenter('leebuyer_signup');
            $TadDataCenter->set_col('id', $id);
            $tdc = $TadDataCenter->getData(); //假如有id編號的話就去抓出此人的完整報名資料

            $table = '<table class="table">';
            foreach ($tdc as $title => $signup) {
                $table .= "
            <tr>
                <th>{$title}</th>
                <td>";
                foreach ($signup as $i => $val) {
                    $table .= "<div>{$val}</div>";
                }

                $table .= "</td>
            </tr>";
            }
            $table .= '</table>';
        }

        $content = "
    <html>
        <head>
            <style>
                .table{
                    border:2px solid #cfcfcf;
                    border-collapse: collapse;
                    margin:10px 0px;
                }

                .table th, .table td{
                    border:1px solid #632222;
                    padding: 4px 10px;
                }

                .table th{
                    background:#cfcfcf;
                }

                .well{
                    border-radius: 10px;
                    background: #fcfcfc;
                    border: 2px solid #cfcfcf;
                    padding:14px 16px;
                    margin:10px 0px;
                }
            </style>
        </head>
        <body>
        $head
        <h2>{$action['title']}</h2>
        <div>活動日期：{$action['action_date']}</div>
        <div class='well'>{$action['detail']}</div>
        $table
        $foot
        </body>
    </html>
    ";
        return $content;
    }

//預覽csv
    public static function preview_csv($action_id)
    {
        global $xoopsTpl;

        //防止網址輸入觀看表單之轉向，配合$uid = $xoopsUser ? $xoopsUser->uid() : 0;才不致報錯
        if (!$_SESSION['can_add']) {
            redirect_header($_SERVER['PHP_SELF'], 3, "您沒有權限使用此功能");
        }

        $action = Leebuyer_signup_actions::get($action_id);
        $xoopsTpl->assign("action", $action);

        //製作標題(tadtools/class/TadDataCenter.php 必須是 2021/10/29 日以後的版本，可以改用下列方式更簡單)
        list($head, $type) = self::get_head($action, true, true);

        $xoopsTpl->assign("head", $head);
        $xoopsTpl->assign("type", $type);

        //抓取內容
        $preview_data = [];
        $handle = fopen($_FILES['csv']['tmp_name'], "r") or die("無法開啟"); //在樣板檔的input表單輸入框<input type="file" name="csv" class="form-control" accept="text/csv">此地$_FILES['csv']也要寫csv
        while (($val = fgetcsv($handle, 1000)) !== false) {
            $preview_data[] = mb_convert_encoding($val, 'UTF-8', 'big5'); //轉碼
        }
        fclose($handle);
        $xoopsTpl->assign("preview_data", $preview_data);

        //加入Token安全機制
        include_once $GLOBALS['xoops']->path('class/xoopsformloader.php');
        $token = new \XoopsFormHiddenToken();
        $token_form = $token->render();
        $xoopsTpl->assign("token_form", $token_form);

    }

    //批次匯入csv
    public static function import_csv($action_id)
    {
        global $xoopsDB, $xoopsUser;

        //XOOPS表單安全檢查
        Utility::xoops_security_check();

        //防止網址輸入觀看表單之轉向，配合$uid = $xoopsUser ? $xoopsUser->uid() : 0;才不致報錯
        if (!$_SESSION['can_add']) {
            redirect_header($_SERVER['PHP_SELF'], 3, "您沒有權限使用此功能");
        }

        $action_id = (int) $action_id;
        $uid = $xoopsUser->uid();

        $action = Leebuyer_signup_actions::get($action_id);

        $TadDataCenter = new TadDataCenter('leebuyer_signup');
        //Utility::dd($_POST['tdc']);
        foreach ($_POST['tdc'] as $tdc) {
            $sql = "insert into `" . $xoopsDB->prefix("leebuyer_signup_data") . "` (
                `action_id`,
                `uid`,
                `signup_date`,
                `accept`
                ) values(
                '{$action_id}',
                '{$uid}',
                now(),
                '1'
            )";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

            //取得最後新增資料的流水編號(報名之後的id)
            $id = $xoopsDB->getInsertId();

            //儲存資料

            $TadDataCenter->set_col('id', $id); //綁定這個值
            $TadDataCenter->saveCustomData($tdc);

            $action['signup'] = self::get_all($action_id);
            // 若是超過名額，註記為「候補」
            if (count($action['signup']) > $action['number']) {
                $TadDataCenter->set_col('data_id', $id); //綁定這個值
                //儲存資料
                $TadDataCenter->saveCustomData(['tag' => ['候補']]); //存入那個資料的name是tag此標籤，值是候補。此['tag' => ['候補']]是陣列，亦可加入其他東西
            }

        }
    }

    //預覽excel
    public static function preview_excel($action_id)
    {
        global $xoopsTpl;

        //防止網址輸入觀看表單之轉向，配合$uid = $xoopsUser ? $xoopsUser->uid() : 0;才不致報錯
        if (!$_SESSION['can_add']) {
            redirect_header($_SERVER['PHP_SELF'], 3, "您沒有權限使用此功能");
        }

        $action = Leebuyer_signup_actions::get($action_id);
        $xoopsTpl->assign("action", $action);

        //製作標題(tadtools/class/TadDataCenter.php 必須是 2021/10/29 日以後的版本，可以改用下列方式更簡單)
        list($head, $type) = self::get_head($action, true, true);

        //取得標題及類型
        // $TadDataCenter = new TadDataCenter('leebuyer_signup');
        // $head = $TadDataCenter->getAllColItems($action['setup']);
        // $type = $TadDataCenter->getAllColItems($action['setup'], 'type');

        $xoopsTpl->assign("head", $head);
        $xoopsTpl->assign("type", $type);

        //抓取內容
        $preview_data = [];

        require_once XOOPS_ROOT_PATH . '/modules/tadtools/vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php';
        $reader = \PHPExcel_IOFactory::createReader('Excel2007');
        $PHPExcel = $reader->load($_FILES['excel']['tmp_name']); // 檔案名稱
        $sheet = $PHPExcel->getSheet(0); // 讀取第一個工作表(編號從 0 開始)
        $maxCell = $PHPExcel->getActiveSheet()->getHighestRowAndColumn();
        $maxColumn = self::getIndex($maxCell['column']);
        // 一次讀一列
        for ($row = 1; $row <= $maxCell['row']; $row++) {
            // 讀出每一格
            for ($column = 0; $column <= $maxColumn; $column++) {
                $preview_data[$row][$column] = $sheet->getCellByColumnAndRow($column, $row)->getCalculatedValue();
            }
        }

        $xoopsTpl->assign("preview_data", $preview_data);

        //加入Token安全機制
        include_once $GLOBALS['xoops']->path('class/xoopsformloader.php');
        $token = new \XoopsFormHiddenToken();
        $token_form = $token->render();
        $xoopsTpl->assign("token_form", $token_form);

    }

    // 將文字轉為數字
    private static function getIndex($let)
    {
        // Iterate through each letter, starting at the back to increment the value
        for ($num = 0, $i = 0; $let != ''; $let = substr($let, 0, -1), $i++) {
            $num += (ord(substr($let, -1)) - 65) * pow(26, $i);
        }

        return $num;
    }

    //批次匯入excel
    public static function import_excel($action_id)
    {
        self::import_csv($action_id);
    }

    //取得報名的標題欄
    public static function get_head($action, $return_type = false, $only_tdc = false)
    {
        //標題列
        $head_row = explode("\n", $action['setup']); //explode() 函數把字符串分割為數組。用換行符號把$action['setup']拆開，會成一陣列

        $head = $type = [];
        foreach ($head_row as $head_data) {
            $cols = explode(',', $head_data);
            if (strpos($cols[0], '#') === false) { //strpos()在此字串$cols[0]找是否有#的符號。沒找到跑下面不含#字號把他放入標題，有#字號是註解
                $head[] = str_replace('*', '', trim($cols[0])); //搜尋*符號，取代成空白
                $type[] = trim($cols[1]);
            }
        }

        if (!$only_tdc) {
            $head[] = '錄取';
            $head[] = '報名日期';
            $head[] = '身份';
        }

        if ($return_type) {
            return [$head, $type];
        } else {
            return $head;
        }

    }

    //進行pdf的匯出設定
    public static function pdf_setup($action_id)
    {
        global $xoopsTpl;

        $action = Leebuyer_signup_actions::get($action_id);
        $xoopsTpl->assign("action", $action);

        //儲存資料
        $TadDataCenter = new TadDataCenter('leebuyer_signup');
        $TadDataCenter->set_col('pdf_setup_id', $action_id); //綁定這個值
        $pdf_setup_col = $TadDataCenter->getData('pdf_setup_col', 0); //當0時，抓出來是完整的值
        $to_arr = explode(',', $pdf_setup_col);

        //製作標題
        $head_arr = self::get_head($action);
        $from_arr = array_diff($head_arr, $to_arr);
        $hidden_arr = [];
        //(tadtools/class/Tmt.php)public function render($id, $from_arr = [], $to_arr = [], $hidden_arr = ['op' => 'save_tmt'], $only_value = false, $submit = true, $size = '15rem', $from_name = 'repository', $to_name = 'destination')
        $tmt_box = Tmt::render('pdf_setup_col', $from_arr, $to_arr, $hidden_arr, true, false);
        $xoopsTpl->assign("tmt_box", $tmt_box);
    }

    //儲存pdf的匯出設定
    public static function pdf_setup_save($action_id, $pdf_setup_col = '')
    {
        //儲存資料
        $TadDataCenter = new TadDataCenter('leebuyer_signup');
        $TadDataCenter->set_col('pdf_setup_id', $action_id); //綁定這個值
        //儲存資料
        $TadDataCenter->saveCustomData(['pdf_setup_col' => [$pdf_setup_col]]); //存入那個資料的name是tag此標籤，值是候補。此['tag' => ['候補']]是陣列，亦可加入其他東西
    }

}
