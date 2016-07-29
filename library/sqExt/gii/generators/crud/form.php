<?php
/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $generator yii\gii\generators\crud\Generator */

echo $form->field($generator, 'modelClass');
echo $form->field($generator, 'searchModelClass');
echo $form->field($generator, 'controllerClass');
echo $form->field($generator, 'viewPath');
echo $form->field($generator, 'baseControllerClass');
echo $form->field($generator, 'indexWidgetType')->dropDownList([
    'grid' => 'GridView',
    'list' => 'ListView',
]);
echo $form->field($generator, 'enableI18N')->checkbox();
echo $form->field($generator, 'messageCategory');
if ($generator->validate()) {
	foreach ($generator->getColumnNames() as $value) {
		echo $form->field($generator, "columnsOption[$value]")
		->label($value)
		->dropDownList([
		    'input' 	=> '输入框',
		    'textarea' 	=> '文本框',
		    'editor' 	=> '富文本框',
		    'date' 		=> '日期',
		    'downlist' 	=> '下拉框',
		    'image' 	=> '图片',
		    'file' 		=> '文件',
		]);
	}
}