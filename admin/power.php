<?php
require_once __DIR__ . '/header.php'; //後台要先引入樣板再引入header.php。跟前台相反
include_once $GLOBALS['xoops']->path('class/xoopsform/grouppermform.php');
//權限項目陣列（編號超級重要！設定後，以後切勿隨便亂改。）
$item_list = array(
    '1' => "建立報名活動", //建立後不要改來改去，尤其是意思相反的
    //'2' => "權限二",
);
$mid = $xoopsModule->mid();
$perm_name = $xoopsModule->dirname();
$formi = new XoopsGroupPermForm('細部權限設定', $mid, $perm_name, '請勾選欲開放給群組使用的權限：<br>');
foreach ($item_list as $item_id => $item_name) {
    $formi->addItem($item_id, $item_name);
}
echo $formi->render();
require_once __DIR__ . '/footer.php';
