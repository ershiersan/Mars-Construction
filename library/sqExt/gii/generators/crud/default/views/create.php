<?php
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

echo "<?php\n";
?>

use yii\helpers\Html;
use app\components\ActiveForm;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

$this->title = <?= $generator->generateString('Create ' . Inflector::camel2words(StringHelper::basename($generator->modelClass))) ?>;
$this->params['breadcrumbs'][] = ['label' => <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-create hpanel">

    <div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form panel-body">
        <h1><?= "<?= " ?>Html::encode($this->title) ?></h1>
<?php
    /* @var $model \yii\db\ActiveRecord */
    $model = new $generator->modelClass();
    $safeAttributes = $model->safeAttributes();
    if (empty($safeAttributes)) {
        $safeAttributes = $model->attributes();
    }
?>
        <?= "<?php " ?>$form = ActiveForm::begin(['options' => ['class'=>'form-horizontal']]); ?>

<?php foreach ($generator->getColumnNames() as $attribute) {
    if (in_array($attribute, $safeAttributes)) {
        echo "            <?= " . $generator->generateActiveField($attribute) . " ?>\n\n";
    }
} ?>
            <div class="form-group">
                <div class="col-sm-8 col-sm-offset-2">
                <?= "<?= " ?>Html::submitButton($model->isNewRecord ? <?= $generator->generateString('新建') ?> : <?= $generator->generateString('Update') ?>, ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>
            </div>

        <?= "<?php " ?>ActiveForm::end(); ?>
    </div>

</div>