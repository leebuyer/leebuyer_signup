<h2 class="my"><{$smarty.const._MD_LEEBUYER_SIGNUP_ACTION_SETTING}></h2>

<form action="index.php" method="post" id="myForm" enctype="multipart/form-data" class="form-horizontal">

    <div class="form-group row">
        <label class="col-sm-2 control-label col-form-label text-md-right">
            <{$smarty.const._MD_LEEBUYER_SIGNUP_TITLE}>
        </label>
        <div class="col-sm-10">
            <input type="text" name="title" id="title" class="form-control validate[required]" value="<{$title}>" placeholder="<{$smarty.const._MD_LEEBUYER_SIGNUP_KEYIN}><{$smarty.const._MD_LEEBUYER_SIGNUP_TITLE}>">
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 control-label col-form-label text-md-right">
            <{$smarty.const._MD_LEEBUYER_SIGNUP_DETAIL}>
        </label>
        <div class="col-sm-10">
            <{$editor}>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 control-label col-form-label text-md-right">
            <{$smarty.const._MD_LEEBUYER_SIGNUP_END_DATE_COL}>
        </label>
        <div class="col-sm-10">
            <input type="text" name="end_date" id="end_date" class="form-control validate[required]" value="<{$end_date}>" placeholder="<{$smarty.const._MD_LEEBUYER_SIGNUP_KEYIN}><{$smarty.const._MD_LEEBUYER_SIGNUP_END_DATE_COL}>"  onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss' , startDate:'%y-%M-%d %H:%m:%s}'})">
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 control-label col-form-label text-md-right">
            <{$smarty.const._MD_LEEBUYER_SIGNUP_ACTION_DATE}>
        </label>
        <div class="col-sm-10">
            <input type="text" name="action_date" id="action_date" class="form-control validate[required]" value="<{$action_date}>" placeholder="<{$smarty.const._MD_LEEBUYER_SIGNUP_KEYIN}><{$smarty.const._MD_LEEBUYER_SIGNUP_ACTION_DATE}>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss' , startDate:'%y-%M-%d %H:%m:%s}'})">
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 control-label col-form-label text-md-right">
            <{$smarty.const._MD_LEEBUYER_SIGNUP_NUMBER}>
        </label>
        <div class="col-sm-10">
            <input type="number" name="number" id="number" class="form-control validate[required]" value="<{$number}>" placeholder="<{$smarty.const._MD_LEEBUYER_SIGNUP_KEYIN}><{$smarty.const._MD_LEEBUYER_SIGNUP_NUMBER}>">
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 control-label col-form-label text-md-right">
            <{$smarty.const._MD_LEEBUYER_SIGNUP_CANDIDATES_QUOTA}>
        </label>
        <div class="col-sm-10">
            <input type="number" name="candidate" id="candidate" class="form-control validate[required]" value="<{$candidate}>" placeholder="<{$smarty.const._MD_LEEBUYER_SIGNUP_KEYIN}><{$smarty.const._MD_LEEBUYER_SIGNUP_CANDIDATES_QUOTA}>">
        </div>
    </div>


    <div class="form-group row">
        <label class="col-sm-2 control-label col-form-label text-md-right">
            <{$smarty.const._MD_LEEBUYER_SIGNUP_SETUP}>
        </label>
        <div class="col-sm-10">
            <textarea name="setup" id="setup" class="form-control validate[required]" placeholder="<{$smarty.const._MD_LEEBUYER_SIGNUP_KEYIN}><{$smarty.const._MD_LEEBUYER_SIGNUP_SETUP}>"><{$setup}></textarea>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 control-label col-form-label text-md-right">
            <{$smarty.const._MD_LEEBUYER_SIGNUP_ENABLE}>
        </label>
        <div class="col-sm-10" style="padding-top: 8px;">
            <div class="form-check-inline radio-inline">
                <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="enable" value="1" <{if $enable=='1'}>checked<{/if}>>
                    <{$smarty.const._YES}>
                </label>
            </div>
            <div class="form-check-inline radio-inline">
                <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="enable" value="0" <{if $enable=='0'}>checked<{/if}>>
                    <{$smarty.const._NO}>
                </label>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 control-label col-form-label text-md-right">
            <{$smarty.const._MD_LEEBUYER_SIGNUP_UPLOADS}>
        </label>
        <div class="col-sm-10">
            <{$upform}>
        </div>
    </div>

    <{$token_form}>
    <input type="hidden" name="uid" value="<{$uid}>">   <!--由leebuyer_signup_actions.php之create送來，1.global $xoopsUser;2.$uid = $xoopsUser ? $xoopsUser->uid() : 0;3.$xoopsTpl->assign("uid", $uid);-->
    <input type="hidden" name="id" value="<{$id}>">
    <input type="hidden" name="op" value="<{$next_op}>">
    <div class="bar">
        <button type="submit" class="btn btn-primary">
            <i class="fa fa-save" aria-hidden="true"></i> <{$smarty.const._TAD_SAVE}>
        </button>
    </div>
</form>