<?php
use XoopsModules\Leebuyer_signup\Leebuyer_signup_actions;
use XoopsModules\Tadtools\Utility;

//可報名活動一覽
function action_list()
{
    $block = Leebuyer_signup_actions::get_all(true, false, $options[0], $options[1]);
    return $block;
}

//可報名活動一覽的編輯區塊函數
function action_list_edit($options)
{
    $form = "
        <ol class='my-form'>
            <li class='my-row'>
                <lable class='my-label'>" . _MB_LEEBUYER_SIGNUP_SHOW_ACTIONS_NUMBER . "</lable>
                <div class='my-content'>
                    <input type='text' class='my-input' name='options[0]' value='{$options[0]}' size=6>
                </div>
            </li>
            <li class='my-row'>
                <lable class='my-label'>" . _MB_LEEBUYER_SIGNUP_ORDER_BY . "</lable>
                <div class='my-content'>
                    <select name='options[編號]' class='my-input'>
                        <option value='`action_date` desc' " . Utility::chk($options[1], '`action_date` desc', '1', "selected") . ">" . _MB_LEEBUYER_SIGNUP_ORDER_BY_ACTION_DATE . "</option>
                        <option value='`action_date`' " . Utility::chk($options[1], '`action_date`', '', "selected") . ">" . _MB_LEEBUYER_SIGNUP_ORDER_BY_ACTION_DATE_DESC . "</option>
                        <option value='`end_date` desc' " . Utility::chk($options[1], '`end_date` desc', '', "selected") . ">" . _MB_LEEBUYER_SIGNUP_ORDER_BY_END_DATE . "</option>
                        <option value='`end_date`' " . Utility::chk($options[1], '`end_date`', '', "selected") . ">" . _MB_LEEBUYER_SIGNUP_ORDER_BY_END_DATE_DESC . "</option>
                    </select>
                </div>
            </li>
        </ol>
    ";
    return $form;
}
