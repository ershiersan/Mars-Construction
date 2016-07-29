<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AdminAsset extends AssetBundle
{
    // public $basePath = '@webroot';
    public $sourcePath = '@sq/zui/adminAsset';
    // public $baseUrl = '@web';
    public $css = [
        'font-awesome/css/font-awesome.css',
        'css/animate.css',
        'css/style.css',
    ];
    public $js = [
        // 'js/inspinia.js',
        // 'js/plugins/pace/pace.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'sq\zui\ZuiAsset',
        // 'yii\jui\JuiAsset'
    ];
}
