<h2 class="my">
    <{if $enable && ($number + $candidate) > $signup|@count && $end_date|strtotime >= $smarty.now}>  <!--顯示啟用或關閉圖示-->
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
<div class="container img-thumbnail">
    <{$files}>
</div>


<h4 class="my">
    已報名資料
    <small>
        <i class="fa fa-calendar-check-o" aria-hidden="true"></i>報名截止日期:<{$end_date}>
        <i class="fa fa-users" aria-hidden="true"></i>報名人數上限:<{$number}>
        <{if $candidate}><span data-toggle="tooltip" title="可候補名額">(<{$candidate}>)</span><{/if}>
    </small>
</h4>

<table class="table" data-toggle="table" data-pagination="true" data-search="true" data-mobile-responsive="true">
    <thead>
        <tr>
            <{foreach from=$titles item=title}>  <!--from=$signup.0.tdc來源資料已寫到第3層-->
        <th data-sortable="true"><{$title}></th>
            <{/foreach}>

            <th data-sortable="true">錄取</th>
            <th data-sortable="true">報名日期</th>
        </tr>
    </thead>
    <tbody>
        <{foreach from=$signup item=signup_data}>
            <tr>
                <{foreach from=$titles item=title}>
                    <{assign var=user_data value=$signup_data.tdc.$title}>
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
                                <{if strpos($title, '姓名')!==false}>    <!---strpos()函數返回字符串在另一個字符串中第一次出現的位置。如果沒有找到該字符串，則返回 false。此例是在$col_name內找姓名，假如姓名有的話就取代，不是姓名就****--->
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

                <td>
                    <{$signup_data.signup_date}>
                    <{if $signup_data.tag}>
                        <div><span class="badge badge-primary"><{$signup_data.tag}></span></div>
                    <{/if}>
                </td>
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
        <a href="<{$xoops_url}>/modules/leebuyer_signup/csv.php?id=<{$id}>&type=signup" class="btn btn-primary"><i class="fa fa-file-o" aria-hidden="true"></i>匯出報名名單CSV</a>
        <a href="<{$xoops_url}>/modules/leebuyer_signup/excel.php?id=<{$id}>&type=signup" class="btn btn-success"><i class="fa fa-file-excel-o" aria-hidden="true"></i>匯出報名名單EXCEL</a>
    </div>

    <form action="index.php" method="post" id="myForm" enctype="multipart/form-data">
        <div class="input-group">
            <div class="input-group-prepend input-group-addon">
                <span class="input-group-text">匯入報名名單【CSV】</span>
            </div>
            <input type="file" name="csv" class="form-control" accept="text/csv">
            <div class="input-group-append input-group-btn">
                <input type="hidden" name="id" value=<{$id}>>
                <input type="hidden" name="op" value="leebuyer_signup_data_preview_csv"><!---要到index.php加入流程leebuyer_signup_data_preview_csv--->
                <button type="submit" class="btn btn-primary">匯入CSV</button>
                <a href="<{$xoops_url}>/modules/leebuyer_signup/csv.php?id=<{$id}>" class="btn btn-secondary border-left"><i class="fa fa-file-o" aria-hidden="true"></i>下載CSV匯入格式檔</a>
            </div>
        </div>
    </form>

    <form action="index.php" method="post" id="myForm" enctype="multipart/form-data" class="my-1">
        <div class="input-group">
            <div class="input-group-prepend input-group-addon">
                <span class="input-group-text">匯入報名名單【EXCEL】</span>
            </div>
            <input type="file" name="excel" class="form-control" accept=".xlsx">
            <div class="input-group-append input-group-btn">
                <input type="hidden" name="id" value=<{$id}>>
                <input type="hidden" name="op" value="leebuyer_signup_data_preview_excel"><!---要到index.php加入流程leebuyer_signup_data_preview_csv--->
                <button type="submit" class="btn btn-primary">匯入EXCEL</button>
                <a href="<{$xoops_url}>/modules/leebuyer_signup/excel.php?id=<{$id}>" class="btn btn-secondary border-left"><i class="fa fa-file-excel-o" aria-hidden="true"></i>下載EXCEL匯入格式檔</a>
            </div>
        </div>
    </form>
<{/if}>