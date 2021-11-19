<?php
use Xmf\Request; //是用來接收並過濾各種外來變數用的物件
use XoopsModules\Leebuyer_signup\Leebuyer_signup_actions;
use XoopsModules\Tadtools\Utility;

require_once __DIR__ . '/header.php';

$id = Request::getInt('id');

$action = Leebuyer_signup_actions::get($id);

header("Content-type: text/html");
//header("Content-Disposition: attachment; filename={$action['title']}.html"); //attachment是要讓人下載

$content = "
<h2 class='my'>
    {$action['title']}
</h2>
<div class='alert alert-info'>
    {$action['detail']}
</div>
{$action['files']}

<h5 class='my'>
    <small>
        <div><i class='fa fa-calendar' aria-hidden='true'></i>" . _MD_LEEBUYER_SIGNUP_ACTION_DATE . _TAD_FOR . "{$action['action_date']}</div>
        <div><i class='fa fa-calendar-check-o' aria-hidden='true'></i>" . _MD_LEEBUYER_SIGNUP_END_DATE . _TAD_FOR . "{$action['end_date']}</div>
        <div>
            <i class='fa fa-users' aria-hidden='true'></i>" . _MD_LEEBUYER_SIGNUP_STATUS . _TAD_FOR . "" . $action['signup_count'] . "/{$action['number']}
            <span data-toggle='tooltip' title='" . _MD_LEEBUYER_SIGNUP_CANDIDATES_QUOTA . "'>({$action['candidate']})</span>
        </div>
    </small>
</h5>

<div class='text-center my-3'>
<a href='" . XOOPS_URL . "/modules/leebuyer_signup/index.php?op=leebuyer_signup_data_create&action_id={$action['id']}' class='btn btn-lg btn-info'><i class='fa fa-plus' aria-hidden='true'></i>" . _MD_LEEBUYER_SIGNUP_APPLY_NOW . "</a>
</div>
";

$content = Utility::html5($content, false, true, 4, true, 'container', $action['title'], '<link rel="stylesheet" href="' . XOOPS_URL . '/modules/leebuyer_signup/css/module.css" type="text/css">');

// echo $content;

if (file_put_contents(XOOPS_ROOT_PATH . "/uploads/leebuyer_signup/{$action['id']}.html", $content)) { //file_put_contents(file,data,mode,context)函數把一個字符串寫入文件中，file    必需。規定要寫入數據的文件。如果文件不存在，則創建一個新文件。
    header("location: " . XOOPS_URL . "/uploads/leebuyer_signup/{$action['id']}.html");
}
exit;
