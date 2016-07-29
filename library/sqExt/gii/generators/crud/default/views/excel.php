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
use sq\base\ExcelView;

/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */
?>

    <?= "<?php " ?>$excel = new ExcelView([
        'fileName' => 'data',
        'dataProvider' => $dataProvider,
        <?= !empty($generator->searchModelClass) ? "'filterModel' => \$searchModel,\n        'columns' => [\n" : "'columns' => [\n"; ?>

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
        ],
    ]);

$excel->run();

