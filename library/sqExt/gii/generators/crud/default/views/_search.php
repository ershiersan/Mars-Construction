<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->searchModelClass, '\\') ?> */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="panel-search panel-body <?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-search zhyf-fix">

    <?= "<?php " ?>$form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

<?php
$count = 0;
foreach ($generator->getColumnNames() as $attribute) {
    if (++$count < 16) {
        echo "    <?= " . $generator->generateActiveSearchField($attribute) . " ?>\n\n";
    } else {
        echo "    <?php // echo " . $generator->generateActiveSearchField($attribute) . " ?>\n\n";
    }
}
?>
    <div class="form-group" style="float: right;">
        <?= "<?= " ?>Html::submitButton(<?= $generator->generateString('搜索') ?>, ['class' => 'btn btn-primary']) ?>
        &nbsp;&nbsp;
        <?= "<?= " ?>Html::button('清空', ['class' => 'btn btn-warn search-btn', 'onclick' => "clearAll(this)"]) ?>
        <?= "<?php // echo " ?>Html::button('清空', ['class' => 'btn btn-warn', 'onclick' => "$(this).parents('form').find('input:not([type=\"hidden\"]),select:not([type=\"hidden\"])').val('')"]); ?>
    </div>

    <?= "<?php " ?>ActiveForm::end(); ?>

</div>
