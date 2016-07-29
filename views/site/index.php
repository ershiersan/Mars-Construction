<script>
    var STORE_NUM = '<?=$storeInfo['id']?>';
</script>
<div class="m-box">
    <div class="z-logo"><img src="<?php echo $this->params['baseUrl'];?>/wap-1/images/D-logo001.png" alt="" /></div>
    <div class="z-img-text"><img src="<?php echo $this->params['baseUrl'];?>/wap-1/images/D-text001.png" alt="" /></div>
    <div class="z-money"><img src="<?php echo $this->params['baseUrl'];?>/wap-1/images/D-text002.png" alt="" /></div>
    <div class="z-info">
        <p>使用门店信息：</p>
        <p>门店：<span><?php echo $storeInfo['store_name'];?></span></p>
        <p>地址：<span><?php echo $storeInfo['store_addr'];?></span></p>
        <p>电话：<span><?php echo $storeInfo['store_tel'];?></span></p>
    </div>
    <!-- 填写信息 -->
    <div class="z-info-btn J-info-btn"></div>
    <div class="z-rule J-rule">活动细则</div>
    <p class="z-point">恭喜你获得<span>35</span>元优惠券</p>
</div>
<!-- 遮罩层 -->
<div class="z-pop J-pop" style="display:none;"></div>
<!-- 规则弹窗 -->
<div class="z-pop-rule J-pop-rule" style="display:none;">
    <h2>活动规则</h2>
    <p><em>1.</em><span>请于10月15日至12月13日期间，前往<br />发放优惠券门店</span></p>
    <p><em>2.</em><span>购买家乐鸡粉1kg促销装</span></p>
    <p><em>3.</em><span>在门店批发商处激活优惠券</span></p>
    <p><em>4.</em><span>进入“联合利华饮食策划”官方微信，<br />点击[兑奖中心]-[抽奖入口]</span></p>
    <p><em>5.</em><span>输入家乐鸡粉1kg促销装内包装上的兑<br />奖码，立刻获得优惠奖励</span></p>
    <div class="z-rule-btn J-rule-btn"></div>
</div>
<!-- 填写信息弹窗 -->
<div class="z-pop-info J-pop-info" style="display:none;">
    <h2>完善以下信息，就能领取奖品啦！</h2>
    <div class="z-row">
        <span>职业类型：</span>
        <div class="z-sele">
            <select class="J-input J-select-a">
                <option>请选择您的职业类型</option>
                <option>行政总厨/后厨主管/厨师长</option>
                <option>普通厨师</option>
                <option>餐厅老板</option>
                <option>餐厅采购</option>
                <option>调味品经销商/批发商</option>
                <option>其他(餐饮从业者，美食爱好者)</option>
            </select>
        </div>
    </div>
    <div class="z-row">
        <span>餐厅类型：</span>
        <div class="z-sele">
            <select class="J-input J-select-b">
                <option>请选择您的餐厅类型</option>
                <option>中餐厅</option>
                <option>火锅</option>
                <option>星级酒店</option>
                <option>中式快餐(连锁)</option>
                <option>中式快餐(非连锁)</option>
                <option>其他</option>
            </select>
        </div>
    </div>
    <div class="z-row">
        <span>联系电话：</span>
        <input class="J-input J-call" type="text" vaule="" placeholder="请填写您的手机号码"/>
    </div>
    <!-- 提交按钮 -->
    <div class="z-pop-btn-a J-btn-info"></div>
    <p class="z-pop-text J-text-pop" style="display:none;">信息填写有误</p>
</div>