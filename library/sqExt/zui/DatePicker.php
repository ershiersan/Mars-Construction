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
class DatePicker extends InputWidget
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
            $id = $name.'-id';
        }


        $datepickerId = $id.'-input';

        $contents = [];
        $contents[] = Html::hiddenInput($name, $value, ['id' => $id]);
        $this->options['id'] = $datepickerId;
        if(isset($this->options['value'])) {
            $value = $this->options['value'];
        }
        $value = date('Y-m-d', $value);
        $contents[] = Html::input('date', '', $value, $this->options);
        echo implode("\n", $contents) . "\n";

        $hiddenInputId = $id;
//        $objectName = str_replace('-' , '_', $buttonId);
        
        $js = <<<EOF
            var js_strto_time = function(str_time){
                var arr = str_time.split('T');
                var date = '';
                var time = '';
                if(arr.length == 1) {
                    date = arr[0];
                } else {
                    date = arr[0];
                    time = arr[1];
                }
                return strtotime = Date.parse(date + ' ' + time) / 1000;
            }
            $('#{$datepickerId}').on('change', function(){
                $('#{$hiddenInputId}').val(js_strto_time($(this).val()));
            });
EOF;
        $view = $this->getView();
        $view->registerJs($js);

        ZuiAsset::register($view);
    }
}