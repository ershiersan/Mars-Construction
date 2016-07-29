<div class="settings-index">

	<h1>设置</h1>
	<br />
	<div class="settings-form">
        <?php $form = \yii\widgets\ActiveForm::begin(); ?>
			<div class="form-group field-server-content required">
				<label class="control-label" for="money-content">充值上限开启：</label>
				<div class="checkbox">
				 <?=\yii\helpers\Html::checkbox('Settings[money_limit_on]', $settings['money_limit_on'] == 1, ['uncheck'=>0,'label'=>'启用'])?>
					<p class="help-block help-block-error"></p>
				</div>
			</div>
	</div>
	<div class="form-group">
		<button type="submit" class="btn btn-success">保存</button>
	</div>
        <?php \yii\widgets\ActiveForm::end(); ?>
    </div>
</div>