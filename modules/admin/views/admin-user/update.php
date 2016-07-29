<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\AdminUser */

$this->title = Yii::t('yii', 'Update {modelClass}: ', [
    'modelClass' => 'Admin User',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', 'Admin Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('yii', 'Update');
?>
<div class="admin-user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="admin-user-form">
        <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'username') ?>

            <?= $form->field($model, 'password') ?>

            <?= $form->field($model, 'email') ?>

            <?= $form->field($model, 'profile') ?>

            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('yii', 'Create') : Yii::t('yii', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>
