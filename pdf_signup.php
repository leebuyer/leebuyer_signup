<?php
use Xmf\Request; //是用來接收並過濾各種外來變數用的物件
use XoopsModules\Leebuyer_signup\Leebuyer_signup_actions;
use XoopsModules\Leebuyer_signup\Leebuyer_signup_data;
use XoopsModules\Tadtools\TadDataCenter;
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

require_once XOOPS_ROOT_PATH . '/modules/tadtools/tcpdf/tcpdf.php';
$pdf = new TCPDF("P", "mm", "A4", true, 'UTF-8', false);
$pdf->setPrintHeader(false); //不要頁首
$pdf->setPrintFooter(false); //不要頁尾
$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM); //設定自動分頁
$pdf->setFontSubsetting(true); //產生字型子集（有用到的字才放到文件中）
$pdf->SetFont('twkai98_1', '', 12, '', true); //設定字型
$pdf->SetMargins(15, 15); //設定頁面邊界，
$pdf->AddPage(); //新增頁面，一定要有，否則內容出不來

//$pdf->Cell($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = 0, $link = nil, $stretch = 0, $ignore_min_height = false, $calign = 'T', $valign = 'M')
//$pdf->MultiCell( $w, $h, $txt, $border = 0, $align = 'J', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 0, $valign = 'T', $fitcell = false );

$title = $action['title'] . _MD_LEEBUYER_SIGNUP_SIGNIN_TABLE;
$pdf->SetFont('twkai98_1', 'B', 24, '', true); //設定字型
$pdf->MultiCell(180, 0, $title, 0, "C");
$pdf->SetFont('twkai98_1', '', 16, '', true); //設定字型
$pdf->Cell(30, 20, _MD_LEEBUYER_SIGNUP_ACTION_DATE . _TAD_FOR, 0, 0);
$pdf->Cell(140, 20, $action['action_date'], 0, 1);

//儲存資料
$TadDataCenter = new TadDataCenter('leebuyer_signup');
$TadDataCenter->set_col('pdf_setup_id', $id); //綁定這個值
$pdf_setup_col = $TadDataCenter->getData('pdf_setup_col', 0); //當0時，抓出來是完整的值
$col_arr = explode(',', $pdf_setup_col);

//標題部分
$col_count = count($col_arr);
if (empty($col_count)) {
    $col_count = 1;
}
$h = 15;
$w = 110 / $col_count;
$maxh = 15;
$pdf->Cell(15, $h, _MD_LEEBUYER_SIGNUP_ID, 1, 0, "C");
foreach ($col_arr as $key => $col_name) {
    $pdf->Cell($w, $h, $col_name, 1, 0, "C");
}
$pdf->Cell(55, $h, _MD_LEEBUYER_SIGNUP_SIGNIN, 1, 1, "C");

//欄位部分
$signup = Leebuyer_signup_data::get_all($action['id'], null, true, true);
$i = 1;
foreach ($signup as $signup_data) {
    $pdf->MultiCell(15, $h, $i, 1, "C", false, 0, '', '', true, 0, false, true, $maxh, 'M');
    foreach ($col_arr as $key => $col_name) {
        $pdf->MultiCell($w, $h, implode('、', $signup_data['tdc'][$col_name]), 1, "C", false, 0, '', '', true, 0, false, true, $maxh, 'M');
    }
    $pdf->MultiCell(55, $h, '', 1, "C", false, 1, '', '', true, 0, false, true, $maxh, 'M');
    $i++;
}

$pdf->Output("{$title}.pdf", 'D');
