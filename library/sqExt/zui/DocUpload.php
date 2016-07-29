<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace sq\zui;

use Yii;
use yii\base\InvalidParamException;
use yii\helpers\FormatConverter;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 *
 * For example to use the FileUpload with a [[yii\base\Model|model]]:
 *
 * ```php //这个还没有尝试好不好用
 * echo FileUpload::widget([
 *     'model' => $model,
 *     'attribute' => 'from_date',
 *     //'language' => 'ru',
 *     //'dateFormat' => 'yyyy-MM-dd',
 * ]);
 * ```
 *  <?= $form->field($model, 'headimgurl')->widget('\sq\zui\FileUpload', ['options' => ['class' => 'form-control', 'is-pic' =>'0']]) ?>

 */
class DocUpload extends InputWidget
{
    /**
     * @var string the locale ID (e.g. 'fr', 'de', 'en-GB') for the language to be used by the date picker.
     * If this property is empty, then the current application language will be used.
     *
     * Since version 2.0.2 a fallback is used if the application language includes a locale part (e.g. `de-DE`) and the language
     * file does not exist, it will fall back to using `de`.
     */
    public $language;
    /**
     * @var boolean If true, shows the widget as an inline calendar and the input as a hidden field.
     */
    public $inline = false;
    /**
     * @var array the HTML attributes for the container tag. This is only used when [[inline]] is true.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $containerOptions = [];
    /**
     * @var string the format string to be used for formatting the date value. This option will be used
     * to populate the [[clientOptions|clientOption]] `dateFormat`.
     * The value can be one of "short", "medium", "long", or "full", which represents a preset format of different lengths.
     *
     * It can also be a custom format as specified in the [ICU manual](http://userguide.icu-project.org/formatparse/datetime#TOC-Date-Time-Format-Syntax).
     * Alternatively this can be a string prefixed with `php:` representing a format that can be recognized by the
     * PHP [date()](http://php.net/manual/de/function.date.php)-function.
     *
     * For example:
     *
     * ```php
     * 'MM/dd/yyyy' // date in ICU format
     * 'php:m/d/Y' // the same date in PHP format
     * ```
     *
     * If not set the default value will be taken from `Yii::$app->formatter->dateFormat`.
     */
    public $dateFormat;
    /**
     * @var string the model attribute that this widget is associated with.
     * The value of the attribute will be converted using [[\yii\i18n\Formatter::asDate()|`Yii::$app->formatter->asDate()`]]
     * with the [[dateFormat]] if it is not null.
     */
    public $attribute;
    /**
     * @var string the input value.
     * This value will be converted using [[\yii\i18n\Formatter::asDate()|`Yii::$app->formatter->asDate()`]]
     * with the [[dateFormat]] if it is not null.
     */
    public $value;


    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->inline && !isset($this->containerOptions['id'])) {
            $this->containerOptions['id'] = $this->options['id'] . '-container';
        }
        if ($this->dateFormat === null) {
            $this->dateFormat = Yii::$app->formatter->dateFormat;
        }
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        if ($this->hasModel()) {
            $name = Html::getInputName($this->model, $this->attribute);
            $id = Html::getInputId($this->model, $this->attribute);
            $value = Html::getAttributeValue($this->model, $this->attribute);
        } else {
            $value = $this->value;
            $name = $this->name;
            $id = str_replace(['[', ']'], '',$name).'-id';
        }



        if(stripos($value, 'http') === false) { //如果值里包含 http， 则判断不是七牛id， 是真实链接
            $url = \Yii::$app->get('fileManager')->getFileUrl($value);
        } else {
            $url = $value;
        }

        $buttonId = $id.'-auto_upload';
        $delButId = $id.'-del';
        $imgId = $id.'-img';

        $imgWidth = isset($this->options['width']) ? $this->options['width'] : 200;
        $imgHeight = isset($this->options['height']) ? $this->options['height'] : 35;

        $contents = [];
        $contents[] = Html::hiddenInput($name, $value, ['id' => $id]);
        $contents[] = Html::beginTag('div', ['class' => 'form-control', 'style' => 'height:auto']);
        $contents[] = Html::tag('div', '上传文件', ['id' => $buttonId, 'class' => 'btn btn-success', 'style' => '']);
        $contents[] = Html::tag('div', '删除', ['id' => $delButId, 'class' => 'btn btn-info', 'style' => '']);

        if(!isset($this->options['is-pic']) || $this->options['is-pic'] == 1) {
            $contents[] = Html::tag('div', $value, ['id' => $imgId, 'style' => '']);
        }
// //         $contents[] = Html::tag('div', $value);
// //         $contents[] = Html::endTag('div');
        echo implode("\n", $contents) . "\n";

        $hiddenInputId = $id;
        $objectName = str_replace('-' , '_', $buttonId);
        $uploadUrl = Url::to(['/admin/site/fileupload']);
        $js = <<<EOF
            var {$objectName} = new Dropzone("#{$buttonId}",{ 
                url: "{$uploadUrl}",
                previewTemplate:'<div id="preview-template" style="display: none;"></div>',
                limit:1
            });

            {$objectName}.on('success',function(data){ 
                var res = JSON.parse(data.xhr.responseText);
                if(res && res.code == 1) {
                    $('#{$hiddenInputId}').val(res.data.fileId);
                    $('#{$imgId}').html(res.data.fileId);
                } else {
                    alert(res.msg);
                }
            });

            $('#{$delButId}').on('click', function(){
                if($('#{$hiddenInputId}').val() != '' && confirm("是否删除？")) {
                    $('#{$hiddenInputId}').val('');
//                     $('#{$imgId}').attr('src', '');
                }
            });
EOF;
        $view = $this->getView();
        $view->registerJs($js);

        ZuiAsset::register($view);
    }

}
