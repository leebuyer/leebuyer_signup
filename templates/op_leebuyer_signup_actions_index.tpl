<h2 class="my"><{$smarty.const._MD_LEEBUYER_SIGNUP_ACTION_LIST}></h2>

<table class="table table-bordered">
    <thead>
        <tr>
            <th nowrap class="c"><{$smarty.const._MD_LEEBUYER_SIGNUP_TITLE}></th>
            <th nowrap class="c"><{$smarty.const._MD_LEEBUYER_SIGNUP_ACTION_DATE}></th>
            <th nowrap class="c"><{$smarty.const._MD_LEEBUYER_SIGNUP_END_DATE_COL}></th>
            <th nowrap class="c"><{$smarty.const._MD_LEEBUYER_SIGNUP_NUMBER_OF_APPLIED}></th>
            <th nowrap class="c"><{$smarty.const._TAD_FUNCTION}></th>
        </tr>
    </thead>
    <tbody>
        <{foreach from=$all_data key=id item=action name=all_data}>
            <tr>
                <td>
                    <{if $action.enable && ($action.number + $action.candidate)> $action.signup_count && $action.end_date|strtotime >= $smarty.now}>
                        <i class="fa fa-check text-success" data-toggle="tooltip" title="<{$smarty.const._MD_LEEBUYER_SIGNUP_IN_PROGRESS}>" aria-hidden="true"></i>
                    <{else}>
                        <i class="fa fa-times text-danger" data-toggle="tooltip" title="<{$smarty.const._MD_LEEBUYER_SIGNUP_CANT_APPLY}>" aria-hidden="true"></i>
                    <{/if}>
                    <a href="<{$xoops_url}>/modules/leebuyer_signup/index.php?id=<{$action.id}>"><{$action.title}></a>
                </td>
                <td><{$action.action_date}></td>
                <td><{$action.end_date}></td>
                <td>
                    <{$action.signup_count}>/<{$action.number}><!----|是套用php函數count把前面套入計算筆數，前面加@是因為算此陣列-->
                    <{if $action.candidate}><span data-toggle="tooltip" title="可候補名額">(<{$action.candidate}>)</span><{/if}>
                </td>

                <td nowrap>
                    <{if $smarty.session.can_add && ($action.uid==$now_uid || $smarty.session.leebuyer_signup_adm)}>
                        <a href="<{$xoops_url}>/modules/leebuyer_signup/index.php?op=leebuyer_signup_actions_edit&id=<{$action.id}>" class="btn btn-sm btn-warning"><i class="fa fa-pencil" aria-hidden="true"></i><{$smarty.const._EDIT}></a>
                        <a href="<{$xoops_url}>/modules/leebuyer_signup/index.php?op=leebuyer_signup_actions_copy&id=<{$action.id}>" class="btn btn-sm btn-secondary"><i class="fa fa-copy" aria-hidden="true"></i><{$smarty.const._CLONE}></a>
                    <{/if}>
                    <{if $action.enable && ($action.number + $action.candidate)> $action.signup_count && $xoops_isuser && $action.end_date|strtotime >= $smarty.now}>   <!--end_date時間不能做比較，用strtotime把日期轉換成時間戳記-->
                        <a href="<{$xoops_url}>/modules/leebuyer_signup/index.php?op=leebuyer_signup_data_create&action_id=<{$action.id}>" class="btn btn-sm btn-info"><i class="fa fa-plus" aria-hidden="true"></i><{$smarty.const._MD_LEEBUYER_SIGNUP_APPLY_NOW}></a>
                    <{else}>
                        <a href="<{$xoops_url}>/modules/leebuyer_signup/index.php?id=<{$action.id}>" class="btn btn-sm btn-success"><i class="fa fa-file" aria-hidden="true"></i><{$smarty.const._MORE}></a>
                    <{/if}>
                </if>
                </td>
            </tr>
        <{/foreach}>
    </tbody>
</table>

<{$bar}>

<{if $smarty.session.can_add}>
    <div class="bar">
        <a href="<{$xoops_url}>/modules/leebuyer_signup/index.php?op=leebuyer_signup_actions_create" class="btn btn-xs btn-primary"><i class="fa fa-plus" aria-hidden="true"></i><{$smarty.const._MD_LEEBUYER_SIGNUP_ADD_ACTION}></a>
    </div>
<{/if}>