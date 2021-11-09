<?php
use Xmf\Request;
use XoopsModules\Leebuyer_signup\Leebuyer_signup_actions;
use XoopsModules\Leebuyer_signup\Leebuyer_signup_data;
use \PhpOffice\PhpWord\TemplateProcessor;
/*-----------引入檔案區--------------*/
require_once __DIR__ . '/header.php';
require_once XOOPS_ROOT_PATH . '/modules/tadtools/vendor/autoload.php';

if (!$_SESSION['can_add']) {
    redirect_header($_SERVER['PHP_SELF'], 3, "您沒有權限使用此功能！");
}

$id = Request::getInt('id');
$action = Leebuyer_signup_actions::get($id);

$templateProcessor = new TemplateProcessor("signup.docx");
$templateProcessor->setValue('title', $action['title']);
$templateProcessor->setValue('detail', str_replace("\n", "</w:t><w:br/><w:t>", strip_tags($action['detail']))); //若標籤值有HTML語法，需用 strip_tags() 去除，若有換行，可用str_replace此方式處理
$templateProcessor->setValue('action_date', $action['action_date']);
$templateProcessor->setValue('end_date', $action['end_date']);
$templateProcessor->setValue('number', $action['number']);
$templateProcessor->setValue('candidate', $action['candidate']);
$templateProcessor->setValue('signup', count($action['signup']));
$templateProcessor->setValue('url', XOOPS_URL . "/modules/leebuyer_signup/index.php?op=leebuyer_signup_data_create&amp;action_id={$action['id']}");

//產生內容
$signup = Leebuyer_signup_data::get_all($action['id']);
$templateProcessor->cloneRow('id', count($signup)); //要複製幾筆資料。現有3欄，通常找第1欄id，用count算出有幾筆資料，就知道要複製幾筆資料

$i = 1;
foreach ($signup as $id => $signup_data) { //$signup_data(是陣列)每一個人一筆玩整資料
    $iteam = []; //其中每一個項目
    foreach ($signup_data['tdc'] as $head => $user_data) {
        $iteam[] = $head . '：' . implode('、', $user_data);
    }
    $data = implode('<w:br/>', $item); //<w:br/>是word檔的換行符號
    if ($signup_data['accept'] === '1') {
        $sccept = '錄取';
    } elseif ($signup_data['accept'] === '0') {
        $sccept = '未錄取';
    } else {
        $sccept = '尚未設定';
    }

    $templateProcessor->setValue("id#{$i}", $id);
    $templateProcessor->setValue("accept#{$i}", $accept);
    $templateProcessor->setValue("data#{$i}", $data);
    $i++;
}

header('Content-Type: application/vnd.ms-word');
header("Content-Disposition: attachment;filename={$action['title']}報名名單.docx");
header('Cache-Control: max-age=0');
$templateProcessor->saveAs('php://output');
