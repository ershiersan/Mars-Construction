<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace sq\zui;

use Yii;
use yii\web\View ;
use yii\widgets\Block ;

/**
 * DatePicker renders a `datepicker` jQuery UI widget.
 *
 * For example to use the datepicker with a [[yii\base\Model|model]]:
 *
 * ```php
 * echo DatePicker::widget([
 *     'model' => $model,
 *     'attribute' => 'from_date',
 *     //'language' => 'ru',
 *     //'dateFormat' => 'yyyy-MM-dd',
 * ]);
 * ```
 *
 * The following example will use the name property instead:
 *
 * ```php
 * echo DatePicker::widget([
 *     'name'  => 'from_date',
 *     'value'  => $value,
 *     //'language' => 'ru',
 *     //'dateFormat' => 'yyyy-MM-dd',
 * ]);
 * ```
 *
 * You can also use this widget in an [[yii\widgets\ActiveForm|ActiveForm]] using the [[yii\widgets\ActiveField::widget()|widget()]]
 * method, for example like this:
 *
 * ```php
 * <?= $form->field($model, 'from_date')->widget(\yii\jui\DatePicker::classname(), [
 *     //'language' => 'ru',
 *     //'dateFormat' => 'yyyy-MM-dd',
 * ]) ?>
 * ```
 *
 * Note that and empty string (`''`) and `null` will result in an empty text field while `0` will be
 * interpreted as a UNIX timestamp and result in a date displayed as `1970-01-01`.
 * It is recommended to add a
 * validation filter in your model that sets the value to `null` in case when no date has been entered:
 *
 * ```php
 * [['from_date'], 'default', 'value' => null],
 * ```
 *
 * @see http://api.jqueryui.com/datepicker/
 * @author Alexander Kochetov <creocoder@gmail.com>
 * @author Carsten Brandt <mail@cebe.cc>
 * @since 2.0
 */
class JsBlock extends Block{
    /**
     * @var null
     */
    public $key = null;
    /**
     * @var int
     */
    public $pos = View::POS_END ;
    /**
     * Ends recording a block.
     * This method stops output buffering and saves the rendering result as a named block in the view.
     */
    public function run()
    {
        $block = ob_get_clean();
        if ($this->renderInPlace) {
            throw new \Exception("not implemented yet ! ");
            // echo $block;
        }
        $block = trim($block) ;
        /*
         $jsBlockPattern  = '|^<script[^>]*>(.+?)</script>$|is';
         if(preg_match($jsBlockPattern,$block)){
         $block =  preg_replace ( $jsBlockPattern , '${1}'  , $block );
         }
        */
        $jsBlockPattern  = '|^<script[^>]*>(?P<block_content>.+?)</script>$|is';
        if(preg_match($jsBlockPattern,$block,$matches)){
            $block =  $matches['block_content'];
        }

        $this->view->registerJs($block, $this->pos,$this->key) ;
    }
}
