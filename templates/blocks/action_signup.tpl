<h2 class="my">
    <{if $block.enable}>  <!--顯示啟用或關閉圖示-->
        <i class="fa fa-check text-success" aria-hidden="true"></i>
    <{else}>
        <i class="fa fa-times text-danger" aria-hidden="true"></i>
    <{/if}>
    <{$block.title}>
    <small>fa <i class="fa fa-calendar" aria-hidden="true"></i>活動日期:<{$block.action_date}></small>
</h2>

<div class="alert alert-info">
    <{$block.detail}>
</div>

<h3 class="my">
    已報名資料
    <small>
        <i class="fa fa-calendar-check-o" aria-hidden="true"></i>報名截止日期:<{$block.end_date}>
        <i class="fa fa-users" aria-hidden="true"></i>報名人數上限:<{$block.number}>
    </small>
</h3>
