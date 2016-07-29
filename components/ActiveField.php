<?php

namespace app\components;

use Yii;

class ActiveField extends \yii\widgets\ActiveField
{
    public $template = "{label}\n<div class='col-sm-10'>{input}\n{hint}\n{error}</div>";
    public $labelOptions = ['class' => 'col-sm-2 control-label'];

}
