<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace sq\zui;

use yii\helpers\Html;

class Image extends Widget
{
    public $value = '';
    public $width = null;
    public $height = null;
    /**
     * Renders the widget.
     */
    public function run()
    {
        if(stripos($this->value, 'http') === false) { //如果值里包含 http， 则判断不是七牛id， 是真实链接
            $url = \Yii::$app->get('fileManager')->getFileUrl($this->value);
        } else {
            $url = $this->value;
        }

        if($this->width !== null) {
            $this->options['width'] = $this->width;
        }
        if($this->height !== null) {
            $this->options['height'] = $this->height;
        }
        return Html::img($url, $this->options);
    }
}
