<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?=$this->context->pcPageTitle?></title>
    <link rel="stylesheet" href="/assets/pc/css/public.css" type="text/css" />
    <link rel="stylesheet" href="/assets/pc/css/css.css" type="text/css" />
    <script>
        var $STCONFIG = {
            VERSION     : '1.0'
        };
    </script>
</head>
<body>
    <div class="l-body">
        <div class="m-pay-title"><span class="J-text<?=($this->context->isTitleOn?' z-on':'')?>"><?=$this->context->hTitle?></span></div>
        <div class="m-pay-con">
            <ul class="c-pay-nav J-pay-nav">
                <li<?=($this->context->pageIndex === 0?' class="z-on"':'')?>>1.填写订单信息  </li>
                <li<?=($this->context->pageIndex === 1?' class="z-on"':'')?>>2.在线支付</li>
                <li<?=($this->context->pageIndex === 2?' class="z-on"':'')?>>3.完成订单</li>
            </ul>
            <ul class="c-pay-list J-pay-list">
                <?php echo $content; ?>
            </ul>
        </div>
        <div class="m-pay-footer">
            <ul>
                <li class="list1">
                    <p>关于我们<br />http://www.eciawards.org  </p>
                    <p>联系我们<br />http://www.eciawards.org/contact.asp </p>
                </li>
                <li class="list2">
                    <p>购书咨询：</p>
                    <p>
                         电话：021-63553917<br />邮箱：xyd@eciawards.org
                    </p>
                </li>
                <li class="list3"><img src="/assets/pc/images/temp-016.png" /></li>
                <li class="list4">
                    <img src="/assets/pc/images/temp-017.jpg" />
                    <p>扫一扫关注微信账号</p>
                </li>
            </ul>
        </div>
    </div>
    <div class="m-layer J-layer"></div>
    
    <div class="m-pop c-pop-code J-pop-code">
        <div class="c-title">提示</div>
        <div class="t_c" style="padding-top:54px;"><img src="/assets/pc/images/temp-029.jpg" /></div>
        <p>扫码支付</p>
        <span class="c-closed J-closed"></span>
    </div>
    <script src="/assets/pc/js/sea.js"></script>
    <script src="/assets/pc/js/run.js"></script>
</body>
</html>