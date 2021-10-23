<?php

use XoopsModules\Leebuyer_signup\Leebuyer_signup_actions;
use XoopsModules\Tadtools\Utility;

//可報名活動一覽的編輯區塊函數
function action_signup($options)
{
    $block = Leebuyer_signup_actions::get($options[0], true);
    return $block;
}

//可報名活動一覽的編輯區塊函數
function action_signup_edit($options)
{
    $actions = Leebuyer_signup_actions::get_all(true); //抓出所有可報名(抓出來是陣列)
    $opt = '';
    foreach ($actions as $action) {
        $selected = Utility::chk($options[0], $action['id'], '', "selected");
        $opt .= "<option value='{$action['id']}' $selected>{$action['action_date']} {$action['title']}</option>";
    }

    $form = "
        <ol class='my-form'>
            <li class='my-row'>
                <lable class='my-label'>請選擇一個活動</lable>
                <div class='my-content'>
                    <select name='options[0]' class='my-input'>
                        $opt
                    </select>
                </div>
            </li>
        </ol>
    ";
    return $form;
}
