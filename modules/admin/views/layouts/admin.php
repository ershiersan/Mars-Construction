<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AdminAsset;
use mdm\admin\models\searchs\Menu;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */

AdminAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= Html::csrfMetaTags() ?>
    <title><?=$this->title?></title>
    <?php $this->head() ?>
    <style>
        td {
            word-wrap: break-word;
            word-break: break-all;
        }
        .zhyf-fix input[type="date"],
        .zhyf-fix input[type="time"],
        .zhyf-fix input[type="datetime-local"],
        .zhyf-fix input[type="month"]
        {
            line-height: normal;
        }
        #prize_list_table td{
            padding:3px 0 3px 0;
        }
    </style>
</head>

<body>
<?php $this->beginBody() ?>
<div id="header">
    <div class="color-line">
    </div>
    <div id="logo" class="light-version">
        <span>
            ON PACK
        </span>
    </div>
    <nav role="navigation">
        <div class="header-link hide-menu"><i class="fa fa-bars"></i></div>
        <div class="small-logo">
            <span class="text-primary">HOMER APP</span>
        </div>
        <div class="navbar-right" style="margin-right: 15px;">
            <div class="row">
                <nav class="navbar navbar-static-top" role="navigation" style="margin-right: 15px;">
                    <ul class="nav navbar-top-links navbar-right">
                        <li style="display: inline-block;">
                            <span class="m-r-sm text-muted welcome-message">Welcome <?=Yii::$app->user->identity->realname?>. &nbsp;&nbsp;&nbsp;&nbsp;用户id：<?=Yii::$app->user->identity->userid?></span>
                        </li>
                        <li style="display: inline-block;">
                            <a href="<?= Yii::$app->urlManager->createUrl('admin/site/logout');?>" style="padding:18px;">
                                <i class="fa fa-sign-out"></i>退出
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </nav>
</div>
<aside id="menu">
    <div id="navigation">
        <div class="profile-picture">

        </div>

        <ul class="nav" id="side-menu">

        <?php
            $thisRoute = '/'.$this->context->getRoute();
            foreach (\mdm\admin\components\MenuHelper::getAssignedMenu(Yii::$app->user->id, null) as $menus) :?>
            <?php
                $classUlAttr = '';
                $classUlAttr1 = '';
                $strLiHtml = '';
                foreach ($menus['items'] as $item) :?>
                <?php
                $classLiAttr = '';
                if(!empty($item['url'][0]) && strpos(strtolower($thisRoute), preg_replace('/index$/', '', strtolower($item['url'][0]))) !== false) {
                    $classLiAttr = 'class="active"';
                    $classUlAttr = 'class="active"';
                    $classUlAttr1 = 'in';
                }
                $strLiHtml .= "<li {$classLiAttr}><a href='".Url::toRoute($item['url'])."'>{$item['label']}</a></li>";
            ?>
            <?php endforeach ?>
            <li <?=$classUlAttr?>>
                <a href="javascript:;"><span class="nav-label"><i class="fa fa-th-large"></i>&nbsp;&nbsp;<?=$menus['label']?></span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse <?=$classUlAttr1?>" aria-expanded="false">
                    <?=$strLiHtml?>
                </ul>
            </li>
        <?php endforeach ?>
        </ul>
    </div>
</aside>
<div id="wrapper">
    <!--
    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav" id="side-menu">
                <li class="nav-header">
                    <div class="logo-element">
                        IN+
                    </div>
                </li>
                    <?php foreach (\mdm\admin\components\MenuHelper::getAssignedMenu(Yii::$app->user->id, null) as $menus) :?>
                    <li class="">
                        <a href="#"><i class="fa fa-th-large"></i><span class="nav-label"><?=$menus['label']?></span> <span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <?php foreach ($menus['items'] as $item) :?>
                            <li ><a href="<?= Url::toRoute($item['url'])?>"><?=$item['label']?></a></li>
                            <?php endforeach ?>
                        </ul>
                    </li>
                    <?php endforeach ?>
            </ul>
        </div>
    </nav>
    -->

    <div id="page-wrapper" class="gray-bg dashbard-1">
        <!--
        <div class="row border-bottom">
            <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
                </div>
                <ul class="nav navbar-top-links navbar-right">
                    <li>
                        <span class="m-r-sm text-muted welcome-message">Welcome <?=Yii::$app->user->identity->realname?>. &nbsp;&nbsp;&nbsp;&nbsp;用户id：<?=Yii::$app->user->identity->userid?></span>
                    </li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl('admin/site/logout');?>">
                            <i class="fa fa-sign-out"></i>退出
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        -->

        <div class="row">
            <div class="col-lg-12">
                <div class="wrapper wrapper-content">
                    <div class="hpanel">
                        <div class="panel-body">
                    <?= Breadcrumbs::widget([
                        'homeLink' => ['label' => '首页', 'url' => ['/admin']],
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    ]) ?>
                        </div>
                    </div>
                    <style type="text/css">
                        .hpanel .breadcrumb {
                            margin-bottom: 0;
                            background-color: transparent;
                        }
                    </style>
                    <?= $content ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->endBody() ?>
<script type="text/javascript">

    $(document).ready(function () {
        $('.hide-menu').on('click', function(event){
            event.preventDefault();
            if ($(window).width() < 769) {
                $("body").toggleClass("show-sidebar");
            } else {
                $("body").toggleClass("hide-sidebar");
            }
        });
        //目录高亮
        /*
        $('#side-menu a').each(function(){
            var href = $(this).attr('href');
            if(href == "#"){return}

            href = href.replace(/(\B[A-Z])/, '-$1').toLowerCase();

            location_href = decodeURIComponent(location.href);
            location_href = location_href.replace(/(\B[A-Z])/, '-$1').toLowerCase();
            if(href.match(/index$/)) {
                href = href.substr(0,href.lastIndexOf('index'));
            }

            if(location_href.indexOf(href) > 0) {
                $(this).parent('li').addClass('active');
                $(this).parent('li').parents('li').addClass('active');
            }
        })
        */

        // Add body-small class if window less than 768px
        if ($(this).width() < 769) {
            $('body').addClass('body-small')
        } else {
            $('body').removeClass('body-small')
        }

        // MetsiMenu
        $('#side-menu').metisMenu();

        // Collapse ibox function
        $('.collapse-link').click( function() {
            var ibox = $(this).closest('div.ibox');
            var button = $(this).find('i');
            var content = ibox.find('div.ibox-content');
            content.slideToggle(200);
            button.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
            ibox.toggleClass('').toggleClass('border-bottom');
            setTimeout(function () {
                ibox.resize();
                ibox.find('[id^=map-]').resize();
            }, 50);
        });

        // Close ibox function
        $('.close-link').click( function() {
            var content = $(this).closest('div.ibox');
            content.remove();
        });

        // Close menu in canvas mode
        $('.close-canvas-menu').click( function() {
            $("body").toggleClass("mini-navbar");
            SmoothlyMenu();
        });

        // Open close right sidebar
        $('.right-sidebar-toggle').click(function(){
            $('#right-sidebar').toggleClass('sidebar-open');
        });

        // Initialize slimscroll for right sidebar
        $('.sidebar-container').slimScroll({
            height: '100%',
            railOpacity: 0.4,
            wheelStep: 10
        });

        // Open close small chat
        $('.open-small-chat').click(function(){
            $(this).children().toggleClass('fa-comments').toggleClass('fa-remove');
            $('.small-chat-box').toggleClass('active');
        });

        // Initialize slimscroll for small chat
        $('.small-chat-box .content').slimScroll({
            height: '234px',
            railOpacity: 0.4
        });

        // Small todo handler
        $('.check-link').click( function(){
            var button = $(this).find('i');
            var label = $(this).next('span');
            button.toggleClass('fa-check-square').toggleClass('fa-square-o');
            label.toggleClass('todo-completed');
            return false;
        });


        // Minimalize menu
        $('.navbar-minimalize').click(function () {
            $("body").toggleClass("mini-navbar");
            SmoothlyMenu();

        });

        // Tooltips demo
        $('.tooltip-demo').tooltip({
            selector: "[data-toggle=tooltip]",
            container: "body"
        });

        // Move modal to body
        // Fix Bootstrap backdrop issu with animation.css
        $('.modal').appendTo("body");

        // Full height of sidebar
        function fix_height() {
            var heightWithoutNavbar = $("body > #wrapper").height() - 61;
            $(".sidebard-panel").css("min-height", heightWithoutNavbar + "px");

            var navbarHeigh = $('nav.navbar-default').height();
            var wrapperHeigh = $('#page-wrapper').height();

            if(navbarHeigh > wrapperHeigh){
                $('#page-wrapper').css("min-height", navbarHeigh + "px");
            }

            if(navbarHeigh < wrapperHeigh){
                $('#page-wrapper').css("min-height", $(window).height()  + "px");
            }

        }
        fix_height();

        // Fixed Sidebar
        $(window).bind("load", function () {
            if ($("body").hasClass('fixed-sidebar')) {
                $('.sidebar-collapse').slimScroll({
                    height: '100%',
                    railOpacity: 0.9
                });
            }
        })

        // Move right sidebar top after scroll
        $(window).scroll(function(){
            if ($(window).scrollTop() > 0 && !$('body').hasClass('fixed-nav') ) {
                $('#right-sidebar').addClass('sidebar-top');
            } else {
                $('#right-sidebar').removeClass('sidebar-top');
            }
        });

        $(document).bind("load resize scroll", function() {
            if(!$("body").hasClass('body-small')) {
                fix_height();
            }
        });

        $("[data-toggle=popover]")
            .popover();

        // Add slimscroll to element
        $('.full-height-scroll').slimscroll({
            height: '100%'
        })

        // 加载奖项设置
        if($hiddenPriziMsg.length > 0) {
            var jsonPrizeMsg = $hiddenPriziMsg.val();
            var arrPrizeMsg = $.parseJSON(jsonPrizeMsg);
            var i;
            var c = 0;
            for(i in arrPrizeMsg) {
                c++;
                $tablePrizeList.append(getPrizeListTr(arrPrizeMsg[i]));
            }
            for(;c<1;c++) {
                $tablePrizeList.append(getPrizeListTr(new Array()));
            }

        }

        $('form').submit(function(){
            var returnBool = true;
            var total_probablity = 0;
            $tablePrizeList.find('select, input').each(function(){
                if(!checkHasValue($(this))) {
                    returnBool = false;
                }
                if($(this).hasClass('probablity')) {
                    total_probablity += parseFloat($(this).val());
                }
            });
            if(total_probablity > 100) {
                if($tablePrizeList.find('.help-tr').length <= 0) {
                    $('<tr class="help-tr"><td></td><td></td><td><div class="help-block" style="color:#a94442;">概率和不能超过100%。</div></td></tr>').appendTo($tablePrizeList);
                }
                returnBool = false;
            } else {
                $tablePrizeList.find('.help-tr').remove();
            }
            return returnBool;
        });
    });

    function checkHasValue($objDom) {
        if($($objDom).val() == 0 || $($objDom).val() == '') {
            $($objDom).parents('td').addClass('has-error');
            // $($objDom).css('border-color', '#ed5565');
            return false;
        } else if($($objDom).hasClass('probablity') && !/^\d+(\.\d*)?$/.test($($objDom).val())) { // 概率栏位是浮点数
            // $($objDom).css('border-color', '#ed5565');
            $($objDom).parents('td').addClass('has-error');
            return false;
        } else {
            // $($objDom).css('border-color', '#1ab394');
            // $($objDom).css('border-color', '#e5e6e7');
            $($objDom).parents('td').removeClass('has-error');
            return true;
        }
    }

    function addPrize() {
        $tablePrizeList.append(getPrizeListTr(new Array()));
        prizeListChange();
    }

    var $hiddenPriziMsg = $("#productinfo-prize_msg");
    var $tablePrizeList = $('#prize_list_table');
    if(typeof strPrizeOrder != 'undefined')
        var arrPrizeOrder = $.parseJSON(strPrizeOrder);

    // 奖品列表数据变动
    function prizeListChange() {
        var jsonPrizeList = {};
        var i = 0;
        $tablePrizeList.find('tr').each(function(){
            var onePrize = {};
            onePrize.id = $(this).attr('prize_id');
            onePrize.prize_id = $(this).find('.prize_id').val();
            onePrize.names = $(this).find('.names').val();
            onePrize.probablity = $(this).find('.probablity').val();
            jsonPrizeList[i++] = onePrize;
        });
        $hiddenPriziMsg.val(JSON.stringify(jsonPrizeList));
        // console.log($hiddenPriziMsg.val());
    }

    function getPrizeListTr(onePrizeMsg) {
        var prize_id = onePrizeMsg['id']?onePrizeMsg['id']:'';
        var $tr = $('<tr prize_id="'+prize_id+'"></tr>');
        var $td1 = $('<td style="width:23%;"></td>');
        var $select = getPrizeListSelect();
        if(onePrizeMsg['prize_id'])
            $select.val(onePrizeMsg['prize_id']);
        $td1.append(
            $('<label class="label-rank">'+arrPrizeOrder[$tablePrizeList.find('tr').length]+'&nbsp;&nbsp;</label>')
        ).append(
            $select
        ).appendTo($tr);

        var $td2 = $('<td style="width:23%;"></td>');
        var $nameInput = $('<input type="text" class="form-control names" style="width:63%;display:inline-block;"/>');
        if(onePrizeMsg['names'])
            $nameInput.val(onePrizeMsg['names']);
        $td2.append(
            $('<label>奖品名称&nbsp;&nbsp;</label>')
        ).append(
            $nameInput
        ).appendTo($tr); 

        var $td3 = $('<td style="width:23%;"></td>');
        var $rateInput = $('<input type="text" min="0" max="100" class="form-control probablity" style="width:40%;display:inline-block;"/>&nbsp;<lable>%</lable>');
        if(onePrizeMsg['probablity'])
            $rateInput.val(onePrizeMsg['probablity']);
        $td3.append(
            $('<label>概率&nbsp;&nbsp;</label>')
        ).append(
            $rateInput
        ).appendTo($tr); 

        var $td4 = $('<td></td>');
        var $btnDel = $('<button type="button" class="btn btn-link" onclick="prizeDel(this)">删除</button>');
        $td4.append($btnDel).appendTo($tr);

        // 添加更改事件
        $tr.find('select, input').change(function(){
            prizeListChange();
            checkHasValue($(this));
        }).blur(function(){
            checkHasValue($(this));
        });
        return $tr;
    }

    function getPrizeListSelect() {
        var arrPrizeList = $.parseJSON(strPrizeList);
        var $prizeListSelect = $("<select class='form-control prize_id' style='width:70%;display:inline-block;'><option value='0'>请选择</option></select>");
        for(var j in arrPrizeList) {
            $prizeListSelect.append(
                $("<option value='"+arrPrizeList[j]['id']+"'>"+arrPrizeList[j]['prize_name']+"</option>")
            );
        }
        return $prizeListSelect;
    }
    
    function prizeDel(btnDel) {
        $(btnDel).parents('tr').remove();
        revalueRank();
        prizeListChange();
    }

    // 更新排名
    function revalueRank() {
        var i = 0;
        $tablePrizeList.find('.label-rank').each(function(){
            $(this).html(arrPrizeOrder[i++]+'&nbsp;&nbsp;');
        });
            // $('<label class="label-rank">'+arrPrizeOrder[$tablePrizeList.find('tr').length]+'&nbsp;&nbsp;</label>')
    }

    // Minimalize menu when screen is less than 768px
    $(window).bind("resize", function () {
        if ($(this).width() < 769) {
            $('body').addClass('body-small')
        } else {
            $('body').removeClass('body-small')
        }
    });

    /**
     * 删选，全部清空按钮
     */
    function clearAll(objBtn) {
        $(objBtn).parents('form').find('input:not([type=hidden])').val('');
        $(objBtn).parents('form').find('select').val('');
        $(objBtn).parents('form').find('input[type=date]').prev('input[type=hidden]').val('');
    }

</script>
</body>
</html>
<?php $this->endPage() ?>
