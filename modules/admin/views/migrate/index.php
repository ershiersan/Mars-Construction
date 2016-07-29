<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var $model c006\utility\migration\models\MigrationUtility */
/** @var $output String */
/** @var $output_drop String */
/** @var $tables Array */

$array = ['CASCADE' => 'CASCADE', 'NO ACTION' => 'NO ACTION', 'RESTRICT' => 'RESTRICT', 'SET NULL' => 'SET NULL'];
?>

<style>
    .title {
        display    : block;
        font-size  : 2em;
        margin-top : 20px;
        color      : #e7ad24;
    }

    label[for=migrationutility-databasetables] {
        display : block;
    }

    .inline-elements {
        display : table;
        width   : 100%;
    }

    .inline-elements > div {
        display        : table-cell;
        vertical-align : top;
    }

    .inline-elements > div:first-of-type {
        width : 30%;
    }

    .inline-elements > div:last-of-type {
        width : 70%;
    }

    .inline-elements select {
        min-width : 100%;
    }

    .inline-elements input {
        min-width : 100%;
    }

    .button-style {
        display               : inline-block;
        padding               : 2px 10px;
        margin                : 0;
        margin-left           : 20px;

        font-size             : 0.9em;
        font-weight           : normal;

        background-color      : rgba(155, 202, 242, 0.56);
        -webkit-border-radius : 5px;
        -moz-border-radius    : 5px;
        border-radius         : 5px;

        cursor                : pointer;
    }
    
    h2{
    	color:black;
    	font-weight:bold;
    }
    
    #migration-pre .newest{
    	color:#00aa00;
    }
    #migration-pre div{
    	margin-bottom:8px;
    }    

</style>
<br/>
<h2>系统信息</h2>
    <table>
      <tr>
        <th style='width:20%;'>服务器系统信息:</th>
        <td><?=$sysInfo['serverInfo']?></td>
      </tr>
      <tr>
        <th>php版本号:</th>
        <td><?=$sysInfo['phpdevInfo']?></td>
      </tr>
      <tr>
        <th>当前域名:</th>
        <td><?=$sysInfo['hostInfo']?></td>
      </tr>
      <tr>
        <th>web服务器:</th>
        <td><?=$sysInfo['webInfo']?></td>
      </tr>
      <tr>
        <th>当前项目路径:</th>
        <td><?=$sysInfo['pathInfo']?></td>
      </tr>
      <tr>
        <th>当前框架更新时间:</th>
        <td><?=$sysInfo['libVersion']?></td>
      </tr>
      <tr>
        <th>数据库信息:</th>
        <td><?=$sysInfo['dsn']?></td>
      </tr>
    </table>


<br/>
<h2>软链接</h2>
    <div class="inline-elements">
        <div>
            <?= Html::button('创建软连接', ['class' => 'btn btn-success', 'id' => 'button-create-slink']) ?>
        </div>
    </div><br/>
<br/>
<h2>数据库操作</h2>
    <div class="inline-elements">
        <div style="width: 80%">
            <label class="control-label" for="new-migration-id">Migration 名称</label>
            <input type='text' class="form-control"  value='' id='new-migration-name' />
        </div>
        <div style='width: 20%; vertical-align: bottom; text-align: right'>
            <?= Html::button('创建migration【create】', ['class' => 'btn btn-success', 'id' => 'button-migrate-create']) ?>
        </div>
    </div><br/>
    <div class="inline-elements">
        <div>
            <?= Html::button('执行migrations【up】', ['class' => 'btn btn-success', 'id' => 'button-migrate-check']) ?>
        </div>
    </div><br/>
    <div class="inline-elements">
        <div>
            <?= Html::button('查看migrations历史【history】', ['class' => 'btn btn-success', 'id' => 'button-migrate-history']) ?>
        </div>
    </div><br/>
    <div class="inline-elements">
        <div>
            <?= Html::button('备份数据库', ['class' => 'btn btn-success', 'id' => 'button-database-backup']) ?>
        </div>
    </div><br/>
    <div class="inline-elements">
        <div>
            <?= Html::button('查看数据库备份', ['class' => 'btn btn-success', 'id' => 'button-database-checkbackup']) ?>
        </div>
    </div><br/>
<pre id='migration-pre' style='height:200px;'></pre>

<br/>
<h2>生成migration代码</h2>
<div class="form">
    <?php $form = ActiveForm::begin(['id' => 'form-submit',]); ?>

    <div class="inline-elements">
        <input type='hidden' value='1' name='MigrationUtility[mysql]' />
        <div>
            <?= $form->field($model, 'mysql_options')->label('mysql选项') ?>
        </div>
    </div>

    <div class="inline-elements">
        <div style="width: 80%">
            <?= $form->field($model, 'databaseTables')->dropDownList(['00' => ' '] + $tables)->label('选择数据表') ?>
        </div>
        <div style="width: 20%; vertical-align: middle; text-align: right">
            <?= Html::button('添加全部数据表', ['class' => 'btn btn-success', 'id' => 'button-add-all']) ?>
        </div>
    </div>

    <div class="inline-elements">
        <div style="width: 80%">
            <?= $form->field($model, 'tables')
                ->label('执行的数据表')
                // ->hint('Change to textarea and back to easily view tables') ?>
        </div>
        <div style="width: 20%; vertical-align: middle; text-align: right">
            <?= Html::button('切换显示方式', ['class' => 'btn btn-success', 'id' => 'button-tables-convert']) ?>
            <?= Html::button('全部移除', ['class' => 'btn btn-success', 'id' => 'button-tables-remove']) ?>
        </div>
    </div>


    <div class="inline-elements">
        <div style="width: 50%">
            <?= $form->field($model, 'ForeignKeyOnUpdate')->dropDownList($array)->hint('') ?>
        </div>
        <div style="width: 50%; vertical-align: middle; text-align: right">
            <?= $form->field($model, 'ForeignKeyOnDelete')->dropDownList($array)->hint('') ?>
        </div>
    </div>

    <?= $form->field($model, 'addTableInserts')->dropDownList(['0' => 'No', '1' => 'Yes'])->label('保留数据表记录') ?>
    


    <div class="form-group">
        <?= Html::submitButton('执行', ['class' => 'btn btn-primary', 'name' => 'button-submit', 'id' => 'button-submit']) ?>
    </div>

    <?php ActiveForm::end() ?>
</div>


<?php /* This is optional if SubmitSpinner is installed */ ?>
<?php if (class_exists('c006\\spinner\\SubmitSpinner')) : ?>
    <?= c006\spinner\SubmitSpinner::widget(['form_id' => $form->id,]);
    ?>
<?php endif ?>



<?php if ($output) : ?>
    <div class="title" style="margin-top:10px; padding-top: 10px; border-top: 1px dotted #CCCCCC"">Up()
    <?= Html::button('全选文本', ['class' => 'btn btn-success', 'id' => 'button-select-all']) ?>
    </div>
    <div style="display: block; position: relative;">
        <pre id="code-output" style="margin-top: 20px;"><?= $output ?></pre>
    </div>
<?php endif ?>


<script type="text/javascript">
    document.body.onload = (function () {

        jQuery.fn.selectText = function () {
            this.find('input').each(function () {
                if ($(this).prev().length == 0 || !$(this).prev().hasClass('p_copy')) {
                    $('<p class="p_copy" style="position: absolute; z-index: -1;"></p>').insertBefore($(this));
                }
                $(this).prev().html($(this).val());
            });
            var doc = document;
            var element = this[0];
            console.log(this, element);
            if (doc.body.createTextRange) {
                var range = document.body.createTextRange();
                range.moveToElementText(element);
                range.select();
            } else if (window.getSelection) {
                var selection = window.getSelection();
                var range = document.createRange();
                range.selectNodeContents(element);
                selection.removeAllRanges();
                selection.addRange(range);
            }
        };

        jQuery('#button-add-all')
            .click(function () {
                var $tables = jQuery('#migrationutility-tables');
                $tables.val("");
                jQuery("#migrationutility-databasetables > option")
                    .each(function () {
                        if (this.text != 'migration' && this.text != '') {
                            $tables.val($tables.val() + ',' + this.text);
                        }
                    });
                $tables.val($tables.val().replace(/^,+/, ''));
            });
        jQuery('#button-select-all')
            .click(function () {
                jQuery('#code-output').selectText();
            });
        jQuery('#button-select-all-drop')
            .click(function () {
                jQuery('#code-output-drop').selectText();
            });
        jQuery('#button-tables-convert')
            .click(function () {
                var $this = jQuery('#migrationutility-tables');
                var $parent = $this.parent();
                if ($this.attr('type') == "text") {
                    var $textarea = jQuery(document.createElement('textarea'));
                    $textarea.attr('id', $this.attr('id'));
                    $textarea.attr('type', 'textarea');
                    $textarea.attr('class', $this.attr('class'));
                    $textarea.attr('name', $this.attr('name'));
                    $textarea.html($this.val().replace(/\s+/g, '').replace(/,/g, "\n"));
                    $this.remove();
                    jQuery($textarea).insertAfter($parent.find('> label'));
                } else {
                    var $input = jQuery(document.createElement('input'));
                    $input.attr('id', $this.attr('id'));
                    $input.attr('type', 'text');
                    $input.attr('class', $this.attr('class'));
                    $input.attr('name', $this.attr('name'));
                    $input.val($this.html().replace(/[\r\n]/g, ", "));
                    $this.remove();
                    jQuery($input).insertAfter($parent.find('> label'));
                }
                jQuery('#migrationutility-tables').blur();
            });
        jQuery('#button-tables-remove').click(function(){
        	jQuery('#migrationutility-tables').val('');
        });
        
        $('#button-migrate-create').click(function(){
        	$.ajax({
                "async":false,
                "type": "POST",
                "url":'<?=Url::toRoute('/admin/migrate-interface/create')?>?name='+$('#new-migration-name').val(),
                "data":{},
                "success":function(text){
                    var arrReturn = $.parseJSON(text);
                    migrationLog(arrReturn['msg']);
                    if (arrReturn['code'] == '1') {
                        if (confirm(arrReturn['msg'])) {
                        	$.ajax({
                                "async":false,
                                "type": "POST",
                                "url":'<?=Url::toRoute('/admin/migrate-interface/confirm-create')?>?file=' + encodeURIComponent(arrReturn['data']['file']) + '&name=' + arrReturn['data']['name'],
                                "data":{},
                                "success":function(text){
                                    var arrReturn = $.parseJSON(text);
                                    migrationLog(arrReturn['msg']);
                                }
                            });
                        } else {
                        	migrationLog('Operation Canceled!\n');
                        }
                    }
                }
            });
        });
        $('#button-migrate-check').click(function(){
        	$.ajax({
                "async":false,
                "type": "POST",
                "url":'<?=Url::toRoute('/admin/migrate-interface/check')?>',
                "data":{},
                "success":function(text){
                    var arrReturn = $.parseJSON(text);
                    migrationLog(arrReturn['msg']);
                    if (arrReturn['code'] == '1') {
                        if (confirm(arrReturn['msg'])) {
                        	$.ajax({
                                "async":false,
                                "type": "POST",
                                "url":'<?=Url::toRoute('/admin/migrate-interface/up')?>',
                                "data":{},
                                "success":function(text){
                                    var arrReturn = $.parseJSON(text);
                                    migrationLog(arrReturn['msg']);
                                }
                            });
                        } else {
                        	migrationLog('Operation Canceled!\n');
                        }
                    }
                }
            });
        });
        $('#button-migrate-history').click(function(){
        	$.ajax({
                "async":false,
                "type": "POST",
                "url":'<?=Url::toRoute('/admin/migrate-interface/history')?>',
                "data":{},
                "success":function(text){
                    var arrReturn = $.parseJSON(text);
                    migrationLog(arrReturn['msg']);
                }
            });
        });
        $('#button-database-backup').click(function(){
        	$.ajax({
                "async":false,
                "type": "POST",
                "url":'<?=Url::toRoute('/admin/migrate-interface/backup')?>',
                "data":{},
                "success":function(text){
                    var arrReturn = $.parseJSON(text);
                    migrationLog(arrReturn['msg'], arrReturn['data']['sql_list']);
                }
            });
        });
        $('#button-database-checkbackup').click(function(){
        	$.ajax({
                "async":false,
                "type": "POST",
                "url":'<?=Url::toRoute('/admin/migrate-interface/check-backup')?>',
                "data":{},
                "success":function(text){
                    var arrReturn = $.parseJSON(text);
                    migrationLog(arrReturn['msg'], arrReturn['data']['sql_list']);
                }
            });
        });
        $('#button-create-slink').click(function(){
        	$.ajax({
                "async":false,
                "type": "POST",
                "url":'<?=Url::toRoute('/admin/migrate-interface/lns')?>',
                "data":{},
                "success":function(text){
                    var arrReturn = $.parseJSON(text);
                    alert(arrReturn['msg']);
                }
            });
        });
    });
    function migrationLog(appendText, urlList) {
        for (filename in urlList) {
        	appendText += "\n    "+"<a href='"+urlList[filename]+"'>" + filename + "</a>";
        }
        $('#migration-pre')
        .children('div').removeClass('newest').end()
        .append(
        	$('<div class="newest">'+appendText+'<div>')
        ).scrollTop(100000);
    }
</script>