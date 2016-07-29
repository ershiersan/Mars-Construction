<?php
namespace app\components;

use Yii;
use yii\grid\DataColumn;

class SYDateColumn extends DataColumn
{

	/**
	 * if filter is false then no show filter
	 * else if filter is 'range' string then show from input to input
	 * else if filter is 'single' string then show input
	 * @var mixed
	 */
	public $filter='range';

	public $language = false;

    public $start ;
    public $end ;

	/**
	 * jquery-ui theme name
	 * @var string
	 */
	public $theme = 'base';

	public $fromText = 'From: ';

	public $toText = 'To: ';

	public $dateFormat = 'yy-mm-dd';

	public $dateInputStyle="width:70%";

	public $dateLabelStyle="width:30%;display:block;float:left;";

	public $dateOptions = array();

	/**
	 * Renders the filter cell content.
	 */
	protected function renderFilterCellContent()
	{
        $this->start = $this->grid->filterModel->{$this->attribute}['start'];
        $this->end = $this->grid->filterModel->{$this->attribute}['end'];
        $formName = $this->grid->filterModel->formName();
	    $return =
            '开始
            <input type="datetime-local" id="' . $this->attribute . '-cost_date-input1" class="form-control" name="' . $formName . '[' . $this->attribute . '][start]" value="'. $this->start.'">
            结束
            <input type="datetime-local" id="' . $this->attribute . '-cost_date-input2" class="form-control" name="' . $formName . '[' . $this->attribute . '][end]" value="'. $this->end.'">
            ';
	    return $return;
//		if($this->filter!==false && $this->grid->filter!==null && $this->name!==null && strpos($this->name,'.')===false)
//		{
//
//			$cs=Yii::app()->getClientScript();
//			$cs->registerCssFile($cs->getCoreScriptUrl().'/jui/css/'. $this->theme .'/jquery-ui.css');
//			if ($this->language!==false) {
//				$cs->registerScriptFile($cs->getCoreScriptUrl().'/jui/js/jquery-ui-i18n.min.js');
//			}
//			$cs->registerScriptFile($cs->getCoreScriptUrl().'/jui/js/jquery-ui.min.js');
//
//			if ($this->filter=='range') {
//				echo CHtml::tag('div', array(), "". $this->fromText ."" . CHtml::activeTextField($this->grid->filter, $this->name.'_range[from]', array('style'=>$this->dateInputStyle, 'class'=>'filter-date')));
//				echo CHtml::tag('div', array(), "". $this->toText ."". CHtml::activeTextField($this->grid->filter, $this->name.'_range[to]', array('style'=>$this->dateInputStyle, 'class'=>'filter-date')));
//			}
//			else {
//				echo CHtml::tag('div', array(), CHtml::activeTextField($this->grid->filter, $this->name.'_range[to]', array('class'=>'filter-date')));
//			}
//			$options=CJavaScript::encode($this->dateOptions);
//
//			if ($this->language!==false) {
//$js=<<<EOD
//dateFormat}'}, jQuery.datepicker.regional['{$this->language}'], {$options}));
//EOD;
//			}
//			else {
//$js=<<<EOD
//dateFormat}'}, {$options}));
//EOD;
//			}
//$js=<<<EOD
//registerScript(__CLASS__, $js);
//EOD;
//	}
//		else
//			parent::renderFilterCellContent();
	}

}