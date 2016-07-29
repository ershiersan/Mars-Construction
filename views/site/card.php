<script>
    var CARD_LIST = <?= json_encode($cardList);?>
</script>
<div class="m-box">
    <div class="z-logo"><img src="<?php echo $this->params['baseUrl'];?>/wap-1/images/D-logo001.png" alt="" /></div>
    <div class="m-red">
        <p class="z-red-text">成功获得35元优惠券<br />优惠券码：<span><?php echo $code;?></span></p>
        <p class="z-red-text z-red-c">快去找老板激活优惠券吧！</p>
        <p class="z-add">门店：<span><?php echo $storeInfo['store_name'];?></span></p>
        <p class="z-add">地址：<span><?php echo $storeInfo['store_addr'];?></span></p>
        <p class="z-add">电话：<span><?php echo $storeInfo['store_tel'];?></span></p>
        <div class="z-red-btn"></div>
    </div>
    <div class="m-exp">
        <h2>【如何使用优惠券】</h2>
        <p><em>1.</em><span>前往发放优惠券门店</span></p>
        <p><em>2.</em><span>购买家乐鸡粉1kg促销装</span></p>
        <p><em>3.</em><span>在门店店员处激活优惠码</span></p>
        <p><em>4.</em><span>进入"联合利华饮食策划"官方微信，点击[兑<br />奖中心]-[抽奖入口]</span></p>
        <p><em>5.</em><span>输入家乐鸡粉1kg促销装内包装上的兑奖码，立刻获得优惠奖励</span></p>
    </div>
</div>
