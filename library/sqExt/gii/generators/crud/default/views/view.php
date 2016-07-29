<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

$this->title = $model-><?= $generator->getNameAttribute() ?>;
$this->params['breadcrumbs'][] = ['label' => <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hpanel">
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-view panel-body">

    <h1><?= "<?= " ?>Html::encode($this->title) ?></h1>

    <p>
        <?= "<?= " ?>Html::a(<?= $generator->generateString('Update') ?>, ['update', <?= $urlParams ?>], ['class' => 'btn btn-primary']) ?>
        <?= "<?= " ?>Html::a(<?= $generator->generateString('Delete') ?>, ['delete', <?= $urlParams ?>], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => <?= $generator->generateString('Are you sure you want to delete this item?') ?>,
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= "<?= " ?>DetailView::widget([
        'model' => $model,
        'attributes' => [
<?php
if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {
        echo "            '" . $name . "',\n";
    }
} else {
    foreach ($generator->getTableSchema()->columns as $column) {
        $format = $generator->generateColumnFormat($column);
        $name = $column->name;
        if(!isset($generator->columnsOption[$name])) {
            $generator->columnsOption[$name] = 'input';
        }
        switch ($generator->columnsOption[$name]) {
            case 'input':
            case 'textarea':
                echo "            '" . $name . ($format === 'text' ? "" : ":" . $format) . "',\n";
                break;
            case 'downlist':
                echo <<<EOF
            [
                'attribute' => '$name',
                'value' => \$model->getAttributeByType('$name', \$model->$name)
            ],\n
EOF;
                break;
            case 'date':
                echo <<<EOF
            [
                'attribute' => '$name',
                'value' => date('Y-m-d H:i:s',\$model->$name)
            ],\n
EOF;
                break;
            case 'image':
                echo <<<EOF
            [
                'attribute' => '$name',
                'value' => \sq\zui\Image::widget([
                    'value' => \$model->$name,
                    'width' => 200,
                    'height' => 200,
                ]),
                'format' => 'html'
            ],\n
EOF;
                break;
            default:
                echo "            '" . $name . ($format === 'text' ? "" : ":" . $format) . "',\n";
                break;
        }

    }
}
?>
        ],
    ]) ?>

</div>
</div>
