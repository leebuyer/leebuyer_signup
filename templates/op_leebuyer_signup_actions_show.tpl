<h2 class="my">
    <{if $enable}>  <!--顯示啟用或關閉圖示-->
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
            <{foreach from=$signup.0.tdc key=col_name item=user name=tdc}>
        <th data-sortable="true"><{$col_name}></th>
            <{/foreach}>
            <{if $smarty.session.leebuyer_signup_adm}>
                <th data-sortable="true">錄取</th>
            <{/if}>
            <th data-sortable="true">報名日期</th>
        </tr>
    </thead>
    <tbody>
        <{foreach from=$signup item=signup_data}>
            <tr>
                <{foreach from=$signup_data.tdc key=col_name item=user_data}>
                    <td>
                        <{foreach from=$user_data item=data}>
                            <{if $smarty.session.leebuyer_signup_adm || $signup_data.uid == $uid}>  <!---是管理員看到完整資料或此筆資料uid跟登入資料uid相同，意指這筆資料是我自己的--->
                                <div><a href="index.php?op=leebuyer_signup_data_show&id=<{$signup_data.id}>"><{$data}></a>
                                </div>
                            <{else}>
                                <!---有登入但這筆資料不是我的--->
                                <{if strpos($col_name, '姓名')!==false}>    <!---strpos()函數返回字符串在另一個字符串中第一次出現的位置。如果沒有找到該字符串，則返回 false。此例假如姓名有的話--->
                                <div><{$data|substr_replace:'o':3:3}></div> <!--substr_replace() 函數把字符串的一部分替換為另一個字符串。3是第三位元(第2個字是345)，3是取3個位元-->
                                <{else}>
                                    <div>****</div>
                                <{/if}>
                            <{/if}>

                        <{/foreach}>
                    </td>
                <{/foreach}>
                <{if $smarty.session.leebuyer_signup_adm}>
                    <td>
                        <{if $signup_data.accept==='1'}>
                            <div class="text-primary">錄取</div>
                            <a href="index.php?op=leebuyer_signup_data_accept&id=<{$signup_data.id}>&action_id=<{$id}>&accept=0" class="btn btn-sm btn-warning">改成未錄取</a>
                        <{elseif $signup_data.accept==='0'}>
                            <div class="text-danger">未錄取</div>
                            <a href="index.php?op=leebuyer_signup_data_accept&id=<{$signup_data.id}>&action_id=<{$id}>&accept=1" class="btn btn-sm btn-success">改成錄取</a>
                        <{else}>
                            <div class="text-muted">尚未審定</div>
                            <a href="index.php?op=leebuyer_signup_data_accept&id=<{$signup_data.id}>&action_id=<{$id}>&accept=0" class="btn btn-sm btn-warning">未錄取</a>
                            <a href="index.php?op=leebuyer_signup_data_accept&id=<{$signup_data.id}>&action_id=<{$id}>&accept=1" class="btn btn-sm btn-success">錄取</a>
                        <{/if}>
                    </td>
                <{/if}>
                <td><{$signup_data.signup_date}></td>
            </tr>
        <{/foreach}>
    </tbody>
</table>

<{if $smarty.session.leebuyer_signup_adm}>
    <div class="bar">
        <a href="javascript:del_action('<{$id}>')" class="btn btn-danger"><i class="fa fa-times" aria-hidden="true"></i>刪除活動</a>
        <a href="index.php?op=leebuyer_signup_actions_edit&id=<{$id}>" class="btn btn-warning"><i class="fa fa-pencil" aria-hidden="true"></i>編輯活動</a>
    </div>
<{/if}>