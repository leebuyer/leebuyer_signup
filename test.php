<?php
/*-----------引入檔頭區--------------*/

require_once __DIR__ . '/header.php';
require_once XOOPS_ROOT_PATH . '/header.php';

$url = "http://odata.tn.edu.tw/ebookApi/api/getOpenCourse/?year=110";
$json = file_get_contents($url);
$arr = json_decode($json, true);
//var_dump($arr);
//var_export($arr);
$content = "<ol>";
foreach ($arr as $action) {
    $content .= "<li>{$action['courseDay']}{$action['courseName']}</li>";
}
$content .= "</ol>";
echo $content;

/*-----------引入檔尾區--------------*/

require_once XOOPS_ROOT_PATH . '/footer.php';
