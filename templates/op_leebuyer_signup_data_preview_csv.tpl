<h2 class="my"><{$smarty.const._MD_LEEBUYER_SIGNUP_IMPORT}>【<{$action.title}>】<{$smarty.const._MD_LEEBUYER_SIGNUP_DATA_PREVIEW}></h2>

<form action="index.php" method="post" id="myForm">

    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <{foreach from=$head item=title}>
                    <th><{$title}></th>
                <{/foreach}>
            </tr>
        </thead>
        <tbody>
            <{foreach from=$preview_data key=i item=data name=preview_data}>
                <{if $smarty.foreach.preview_data.iteration > 1}>
                    <tr>
                        <{foreach from=$data key=j item=val}>
                            <{assign var=title value=$head.$j}><!---每跑一格設定一個變數var=title，他的值$head(陣列)，抓標題第一個。跑第二格，$j就等於二，去抓取$head第二個--->
                            <{assign var=input_type value=$type.$j}>
                            <{if $title!=''}>
                                <td>
                                    <{if $input_type=="checkbox"}>
                                    <{assign var=val_arr value='|'|explode:$val}><!---explode第1個參數一定放左邊，第2個放右邊茂號之後--->
                                        <{foreach from=$val_arr item=val}>
                                            <div class="form-check-inline checkbox-inline">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="checkbox" name="tdc[<{$i}>][<{$title}>][]" value="<{$val}>" checked>
                                                    <{$val}>
                                                </label>
                                            </div>
                                        <{/foreach}>
                                    <{else}>
                                        <input type="text" name="tdc[<{$i}>][<{$title}>]" value="<{$val}>" class="form-control form-control-sm">
                                    <{/if}>
                                </td>
                            <{/if}>
                        <{/foreach}>
                    </tr>
                <{/if}>
            <{/foreach}>
        </tbody>
    </table>
    <{$token_form}>
    <input type="hidden" name="id" value="<{$action.id}>">
    <input type="hidden" name="op" value="leebuyer_signup_data_import_csv"><!---此要存入TadDataCenter，tdc[<{$i}>][]是二維陣列，$i是第幾筆資料(preview_data)，--->
    <div class="bar">
        <button type="submit" class="btn btn-primary">
            <i class="fa fa-save" aria-hidden="true"></i> <{$smarty.const._MD_LEEBUYER_SIGNUP_IMPORT}>CSV
        </button>
    </div>
</form>