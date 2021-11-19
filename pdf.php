<?php
use Xmf\Request; //是用來接收並過濾各種外來變數用的物件
use XoopsModules\Leebuyer_signup\Leebuyer_signup_actions;
use XoopsModules\Leebuyer_signup\Leebuyer_signup_data;
use XoopsModules\Tadtools\Utility;
/*-----------引入檔案區--------------*/
require_once __DIR__ . '/header.php';

//防止網址輸入觀看表單之轉向，配合$uid = $xoopsUser ? $xoopsUser->uid() : 0;才不致報錯
if (!$_SESSION['can_add']) {
    redirect_header($_SERVER['PHP_SELF'], 3, _TAD_PERMISSION_DENIED);
}
//過濾id
$id = Request::getInt('id');

//取得活動詳細資料
$action = Leebuyer_signup_actions::get($id);

if ($action['uid'] != $xoopsUser->uid()) {
    redirect_header($_SERVER['PHP_SELF'], 3, _TAD_PERMISSION_DENIED);
}

$title = $action['title'];
$html[] = "<h1>{$title}" . _MD_LEEBUYER_SIGNUP_APPLY_LIST . "</h1>";
$html[] = '<table border="1" cellpadding="3">';
//標題列
//$head_row = explode("\n", $action['setup']); //explode() 函數把字符串分割為數組。用換行符號把$action['setup']拆開，會成一陣列

$head = Leebuyer_signup_data::get_head($action);

//標題列
$html[] = "<tr><th>" . implode("</th><th>", $head) . "</th></tr>";

//產生內容
$signup = Leebuyer_signup_data::get_all($action['id']);
foreach ($signup as $signup_data) { //$signup_data(是陣列)每一個人一筆玩整資料
    $iteam = []; //其中每一個項目
    foreach ($signup_data['tdc'] as $user_data) {
        $iteam[] = implode('|', $user_data);
    }
    if ($signup_data['accept'] === '1') {
        $iteam[] = _MD_LEEBUYER_SIGNUP_ACCEPT;
    } elseif ($signup_data['accept'] === '0') {
        $iteam[] = _MD_LEEBUYER_SIGNUP_NOT_ACCEPT;
    } else {
        $iteam[] = _MD_LEEBUYER_SIGNUP_ACCEPT_NOT_YET;
    }
    $iteam[] = $signup_data['signup_date'];
    $iteam[] = $signup_data['tag'];

    $html[] = "<tr><td>" . implode("</td><td>", $iteam) . "</td></tr>";
}

$html[] = "</table>";

$html_content = implode('', $html);

require_once XOOPS_ROOT_PATH . '/modules/tadtools/tcpdf/tcpdf.php';
$pdf = new TCPDF("P", "mm", "A4", true, 'UTF-8', false);
$pdf->setPrintHeader(false); //不要頁首
$pdf->setPrintFooter(false); //不要頁尾
$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM); //設定自動分頁
$pdf->setFontSubsetting(true); //產生字型子集（有用到的字才放到文件中）
$pdf->SetFont('droidsansfallback', '', 12, '', true); //設定字型
$pdf->SetMargins(15, 15); //設定頁面邊界，
$pdf->AddPage(); //新增頁面，一定要有，否則內容出不來

$pdf->writeHTML($html_content);
//PDF內容設定
$pdf->Output("{$title}.pdf", 'D');
