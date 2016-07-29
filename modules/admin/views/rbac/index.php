<?php
// use Yii;
use yii\bootstrap\Tabs;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

?>
<?php

echo Tabs::widget([
    'items' => [
        [
            'label' => '权限分配',
            'headerOptions' => [
                'href' => 'assignment'
            ],
            'active' => true,
        ],
        [
            'label' => '角色管理',
            'headerOptions' => [
                'href' => 'role'
            ],
        ],
        [
            'label' => '权限管理',
            'headerOptions' => [
                'href' => 'permission'
            ],
        ],
        [
            'label' => '路由管理',
            'headerOptions' => [
                'href' => 'route'
            ],
        ],
        [
            'label' => '菜单管理',
            'headerOptions' => [
                'href' => 'menu'
            ],
        ],
        [
            'label' => '规则管理',
            'headerOptions' => [
                'href' => 'rule'
            ],
        ],
    ],
    'id' => 'rbacTabs'
]);
?>
<iframe src="<?=$assignmentUrl?>" id="iframepage" name="iframepage" frameBorder=0 scrolling=no width="100%" onLoad="iFrameHeight()" ></iframe>
<script type='text/javascript'>
    var persentTag = 'assignment';
    var isinit = true;
    function iFrameHeight() {
        // iframe加载的时候，调整iframe高度
    	var $iframe = $('#iframepage');
    	$iframe.height($iframe.contents().height());
    	if (isinit) {
        	// 为tabs按钮加载监听事件
        	$('#rbacTabs').find('a').click(function(){
            	/* if (persentTag == $(this).parent().attr('href'))
                	return; */
            	var assignmentUrl = "<?=$assignmentUrl?>";
            	persentTag = $(this).parent().attr('href');
            	$iframe.attr('src', assignmentUrl.replace('assignment', $(this).parent().attr('href')));
        	});
    		isinit = false ;
    	}
	}
</script>