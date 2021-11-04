<?php
use Xmf\Request; //是用來接收並過濾各種外來變數用的物件
use XoopsModules\Leebuyer_signup\Leebuyer_signup_actions;
use XoopsModules\Leebuyer_signup\Leebuyer_signup_data;
use XoopsModules\Tadtools\Utility;

require_once __DIR__ . '/header.php';

//防止網址輸入觀看表單之轉向，配合$uid = $xoopsUser ? $xoopsUser->uid() : 0;才不致報錯
if (!$_SESSION['can_add']) {
    redirect_header($_SERVER['PHP_SELF'], 3, "您沒有權限使用此功能！");
}
//過濾id
$id = Request::getInt('id');

$type = Request::getString('type');

//取得活動詳細資料
$action = Leebuyer_signup_actions::get($id);

if ($action['uid'] != $xoopsUser->uid()) {
    redirect_header($_SERVER['PHP_SELF'], 3, "您沒有權限使用此功能！");
}

$csv = [];

$head_row = explode("\n", $action['setup']); //explode() 函數把字符串分割為數組。用換行符號把$action['setup']拆開，會成一陣列

$head = [];
foreach ($head_row as $head_data) {
    $cols = explode(',', $head_data);
    if (strpos($cols[0], '#') === false) { //strpos()在此字串$cols[0]找是否有#的符號。沒找到跑下面不含#字號把他放入標題，有#字號是註解
        $head[] = str_replace('*', '', trim($cols[0])); //搜尋*符號，取代成空白
    }
}

$head[] = '錄取';
$head[] = '報名日期';
$head[] = '身分';

$csv[] = implode(',', $head); //implode() 函數把數組元素組合為一個字符串。標題第一行過濾完
//產生匯入檔
if ($type == 'signup') {
    $signup = Leebuyer_signup_data::get_all($action['id']);
    foreach ($signup as $signup_data) { //$signup_data(是陣列)每一個人一筆玩整資料
        $iteam = []; //其中每一個項目
        foreach ($signup_data['tdc'] as $user_data) {
            $iteam[] = implode(',', $user_data);
        }
        if ($signup_data['accept'] === '1') {
            $iteam[] = '錄取';
        } elseif ($signup_data['accept'] === '0') {
            $iteam[] = '未錄取';
        } else {
            $iteam[] = '尚未設定';
        }
        $iteam[] = $signup_data['signup_date'];
        $iteam[] = $signup_data['tag'];

        $csv[] = implode(',', $iteam); //取得每一個項目用逗號組合起來，
    }
}

$content = implode("\n", $csv); //用換行符號把陣列組合起來
$content = mb_convert_encoding($content, 'big5');

header("Content-type: text/csv");
header("Content-Disposition: attachment; filename= {$action['title']}報名名單.csv");

echo $content;
exit;
