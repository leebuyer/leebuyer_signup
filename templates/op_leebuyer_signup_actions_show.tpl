<h2 class="my">
    <{if $enable && $number > $signup|@count && $end_date|strtotime >= $smarty.now}>  <!--顯示啟用或關閉圖示-->
        <i class="fa fa-check text-success" aria-hidden="true"></i>
    <{else}>
        <i class="fa fa-times text-danger" aria-hidden="true"></i>
    <{/if}>
    <{$title}>
    <small>fa <i class="fa fa-calendar" aria-hidden="true"></i>活動日期:<{$action_date}></small>
</h2>

<div class="alert alert-info">
    <{$detail}>
</div>

<h3 class="my">
    已報名資料
    <small>
        <i class="fa fa-calendar-check-o" aria-hidden="true"></i>報名截止日期:<{$end_date}>
        <i class="fa fa-users" aria-hidden="true"></i>報名人數上限:<{$number}>
    </small>
</h3>

<table class="table" data-toggle="table" data-pagination="true" data-search="true" data-mobile-responsive="true">
    <thead>
        <tr>
            <{foreach from=$signup.0.tdc key=col_name item=user name=tdc}>  <!--from=$signup.0.tdc來源資料已寫到第3層-->
        <th data-sortable="true"><{$col_name}></th>
            <{/foreach}>

            <th data-sortable="true">錄取</th>
            <th data-sortable="true">報名日期</th>
        </tr>
    </thead>
    <tbody>
        <{foreach from=$signup item=signup_data}>
            <tr>
                <{foreach from=$signup_data.tdc key=col_name item=user_data}>
                    <td>
                        <{if $smarty.session.can_add && $uid == $now_uid || $signup_data.uid == $now_uid}>  <!---是管理員看到完整資料或此筆資料uid跟登入資料uid相同，意指這筆資料是我自己的--->
                            <{foreach from=$user_data item=data}>
                                <div>
                                    <a href="<{$xoops_url}>/modules/leebuyer_signup/index.php?op=leebuyer_signup_data_show&id=<{$signup_data.id}>"><{$data}></a>
                                </div>
                            <{/foreach}>
                        <{else}>
                            <div>
                                <!---有登入但這筆資料不是我的--->
                                <{if strpos($col_name, '姓名')!==false}>    <!---strpos()函數返回字符串在另一個字符串中第一次出現的位置。如果沒有找到該字符串，則返回 false。此例是在$col_name內找姓名，假如姓名有的話就取代，不是姓名就****--->
                                    <{if preg_match("/[a-z]/i", $user_data.0)}>
                                        <{$user_data.0|regex_replace:"/[a-z]/":"*"}>
                                    <{else}>
                                        <{$user_data.0|substr_replace:'O':3:3}>
                                    <{/if}> <!--substr_replace() 函數把字符串的一部分替換為另一個字符串。3是第三位元(第2個字是345)，3是取3個位元-->
                                <{else}>
                                    ****
                                <{/if}>
                            </div>
                        <{/if}>
                    </td>
                <{/foreach}>


                    <td>
                        <{if $signup_data.accept==='1'}>
                            <div class="text-primary">錄取</div>
                            <{if $smarty.session.can_add && $uid == $now_uid}>
                                <a href="<{$xoops_url}>/modules/leebuyer_signup/index.php?op=leebuyer_signup_data_accept&id=<{$signup_data.id}>&action_id=<{$id}>&accept=0" class="btn btn-sm btn-warning">改成未錄取</a>
                            <{/if}>
                        <{elseif $signup_data.accept==='0'}>
                            <div class="text-danger">未錄取</div>
                            <{if $smarty.session.can_add && $uid == $now_uid}>
                                <a href="<{$xoops_url}>/modules/leebuyer_signup/index.php?op=leebuyer_signup_data_accept&id=<{$signup_data.id}>&action_id=<{$id}>&accept=1" class="btn btn-sm btn-success">改成錄取</a>
                            <{/if}>
                        <{else}>
                            <div class="text-muted">尚未審定</div>
                            <{if $smarty.session.can_add && $uid == $now_uid}>
                                <a href="<{$xoops_url}>/modules/leebuyer_signup/index.php?op=leebuyer_signup_data_accept&id=<{$signup_data.id}>&action_id=<{$id}>&accept=0" class="btn btn-sm btn-warning">未錄取</a>
                                <a href="<{$xoops_url}>/modules/leebuyer_signup/index.php?op=leebuyer_signup_data_accept&id=<{$signup_data.id}>&action_id=<{$id}>&accept=1" class="btn btn-sm btn-success">錄取</a>
                            <{/if}>
                        <{/if}>
                    </td>

                <td><{$signup_data.signup_date}></td>
            </tr>
        <{/foreach}>
    </tbody>
</table>

<table class="table table-sm">
    <tr>
        <{foreach from=$statistics key=title item=options}>
            <td>
                <b><{$title}></b>
                <hr class="my-1">
                <ul>
                    <{foreach from=$options key=option item=count}>
                        <li><{$option}> : <{$count}></li>
                    <{/foreach}>
                </ul>
            </td>
        <{/foreach}>
    </tr>
</table>

<{if $smarty.session.can_add && $uid == $now_uid}>
    <div class="bar">
        <a href="javascript:del_action('<{$id}>')" class="btn btn-danger"><i class="fa fa-times" aria-hidden="true"></i>刪除活動</a>
        <a href="<{$xoops_url}>/modules/leebuyer_signup/index.php?op=leebuyer_signup_actions_edit&id=<{$id}>" class="btn btn-warning"><i class="fa fa-pencil" aria-hidden="true"></i>編輯活動</a>
    </div>
<{/if}>