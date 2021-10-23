<h3 class="my">
    <{if $block.enable && $block.number > $block.signup|@count && $block.end_date|strtotime >= $smarty.now}>  <!--顯示啟用或關閉圖示-->
        <i class="fa fa-check text-success" aria-hidden="true"></i>
    <{else}>
        <i class="fa fa-times text-danger" aria-hidden="true"></i>
    <{/if}>
    <{$block.title}>
</h3>

<div class="alert alert-info">
    <{$block.detail}>
</div>

<h4 class="my">
    <small>
        <div><i class="fa fa-calendar" aria-hidden="true"></i>活動日期:<{$block.action_date}></div>
        <div><i class="fa fa-calendar-check-o" aria-hidden="true"></i>報名截止:<{$block.end_date}></div>
        <div><i class="fa fa-users" aria-hidden="true"></i>報名情形:<{$block.signup|@count}>/<{$block.number}></div>
    </small>
</h4>
