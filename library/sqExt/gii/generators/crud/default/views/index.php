<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator sq\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

echo "<?php\n";
?>

use yii\helpers\Html;
use <?= $generator->indexWidgetType === 'grid' ? "yii\\grid\\GridView" : "yii\\widgets\\ListView" ?>;

/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>;
$this->params['breadcrumbs'][] = $this->title;
?>

<?php if(!empty($generator->searchModelClass)): ?>
    <div class="hpanel">
        <?= "    <?php " . ($generator->indexWidgetType === 'grid' ? " " : "") ?>echo $this->render('_search', ['model' => $searchModel]); ?>
    </div>
<?php endif; ?>

<div class="hpanel">
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-index panel-body">

    <h1><?= "<?= " ?>Html::encode($this->title) ?></h1>

    <p>
        <?= "<?= " ?>Html::a(<?= $generator->generateString('新建') ?>, ['create'], ['class' => 'btn btn-success']) ?>
        <?= "<?= " ?>Html::a(<?= $generator->generateString('导出') ?>, ['excel', $searchModel->formName() => Yii::$app->request->get($searchModel->formName())], ['class' => 'btn btn-success']) ?>
    </p>

<?php if ($generator->indexWidgetType === 'grid'): ?>
    <?= "<?= " ?>GridView::widget([
        'dataProvider' => $dataProvider,
        <?= !empty($generator->searchModelClass) ? "// 'filterModel' => \$searchModel,\n        'columns' => [\n" : "'columns' => [\n"; ?>
            ['class' => 'yii\grid\SerialColumn'],

<?php
foreach ($generator->getColumnNames() as $name) {
    if(!isset($generator->columnsOption[$name])) {
        echo "            '" . $name . "',\n";
        continue;
    }
    switch ($generator->columnsOption[$name]) {
        case 'input':
        case 'textarea':
            echo "            '" . $name . "',\n";
            break;
        case 'date':
            echo <<<EOF
            [
                'attribute' => '$name',
                'content' => function (\$model){
                    return date('Y-m-d H:i:s', \$model->$name);
                },
            ],\n
EOF;
            break;
        case 'image':
            echo <<<EOF
            [
                'attribute' => '$name',
                'content' => function (\$model){
                    return \sq\zui\Image::widget([
                        'width' => 100,
                        'height' => 100,
                    ]);
                },
            ],\n
EOF;
            break;
        case 'downlist':
            echo <<<EOF
            [
                'attribute' => '$name',
                'content' => function (\$model){
                    return \$model->getAttributeByType('$name', \$model->$name);
                },
            ],\n
EOF;
            break;
        case 'date':
            echo <<<EOF
            [
                'attribute' => '$name',
                'content' => function (\$model){
                    return date('Y-m-d H:i:s',\$model->$name);
                },
            ],\n
EOF;
            break;
        default:
            echo "            '" . $name . "',\n";
            break;
    }
}
?>

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php else: ?>
    <?= "<?= " ?>ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => function ($model, $key, $index, $widget) {
            return Html::a(Html::encode($model-><?= $nameAttribute ?>), ['view', <?= $urlParams ?>]);
        },
    ]) ?>
<?php endif; ?>

</div>
</div>
