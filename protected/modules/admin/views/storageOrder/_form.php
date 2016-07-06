<?php $form=$this->beginWidget('CActiveForm', array(
		'id' => 'material-form',
		'errorMessageCssClass' => 'help-block',
		'htmlOptions' => array(
			'class' => 'form-horizontal',
			'enctype' => 'multipart/form-data'
		),
)); ?>
<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/jquery-ui-1.8.17.custom.css');?>
<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/jquery-ui-timepicker-addon.css');?>
<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-1.7.1.min.js');?>
<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-ui-1.8.17.custom.min.js');?>
<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-ui-timepicker-addon.js');?>
<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-ui-timepicker-zh-CN.js');?>
	<style>
	#category_container select {display:block;float:left;margin-right:3px;max-width:200px;overflow:hidden;}
	</style>
	<div class="form-body">
		<div class="form-group <?php if($model->hasErrors('manufacturer_id')) echo 'has-error';?>">
			<?php echo $form->label($model, 'manufacturer_id',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->dropDownList($model, 'manufacturer_id', Helper::genMfrInfoname() ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('manufacturer_id')));?>
				<?php echo $form->error($model, 'manufacturer_id' )?>
			</div>
		</div>
		<div class="form-group <?php if($model->hasErrors('organization_id')) echo 'has-error';?>">
			<?php echo $form->label($model, 'organization_id',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->dropDownList($model, 'organization_id', CHtml::listData(Helper::genOrgCompany($this->companyId), 'dpid', 'company_name'),array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('organization_id')));?>
				<?php echo $form->error($model, 'organization_id' )?>
			</div>
		</div>
        <div class="form-group" <?php if($model->hasErrors('admin_id')) echo 'has-error';?>>
			<?php echo $form->label($model, 'admin_id',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->dropDownList($model, 'admin_id', Helper::genUsername($this->companyId) ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('admin_id')));?>
				<?php echo $form->error($model, 'admin_id' )?>
			</div>
		</div>
		<div class="form-group <?php if($model->hasErrors('storage_date')) echo 'has-error';?>">
			<?php echo $form->label($model, 'storage_date',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'storage_date',array('class' => 'form-control ui_timepicker','placeholder'=>$model->getAttributeLabel('storage_date')));?>
				<?php echo $form->error($model, 'storage_date' )?>
			</div>
		</div>
		
		<div class="form-group" <?php if($model->hasErrors('remark')) echo 'has-error';?>>
			<?php echo $form->label($model, 'remark',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-5">
				<?php echo $form->textArea($model, 'remark', array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('remark')));?>
				<?php echo $form->error($model, 'remark' )?>
			</div>
		</div>
		<!--<div class="form-group <?php if($model->hasErrors('status')) echo 'has-error';?>">
			<?php echo $form->label($model, 'status',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->dropDownList($model, 'status', array('0' => yii::t('app','已审核') , '1' => yii::t('app','未审核')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('status')));?>
				<?php echo $form->error($model, 'status' )?>
			</div>
		</div>-->
		<div class="form-actions fluid">
			<div class="col-md-offset-3 col-md-9">
				<button type="submit" class="btn blue"><?php echo yii::t('app','确定并下一步');?></button>
				<a href="<?php echo $this->createUrl('storageOrder/index' , array('companyId' => $model->dpid));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
			</div>
		</div>
	</div>
<?php $this->endWidget(); ?>
<?php $this->widget('ext.kindeditor.KindEditorWidget',array(
	'id'=>'',	//Textarea id
	'language'=>'zh_CN',
	// Additional Parameters (Check http://www.kindsoft.net/docs/option.html)
	'items' => array(
		'height'=>'200px',
		'width'=>'100%',
		'themeType'=>'simple',
		'resizeType'=>1,
		'allowImageUpload'=>true,
		'allowFileManager'=>true,
	),
)); ?>
						
<script>
	   $('#category_container').on('change','.category_selecter',function(){
	   		var id = $(this).val();
	   		var $parent = $(this).parent();
                        var sid ='0000000000';
                        var len=$('.category_selecter').eq(1).length;
                        if(len > 0)
                        {
                            sid=$('.category_selecter').eq(1).val();
                            //alert(sid);
                        }
	   });
	   $(function () {
		   $(".ui_timepicker").datetimepicker({
			   //showOn: "button",
			   //buttonImage: "./css/images/icon_calendar.gif",
			   //buttonImageOnly: true,
			   showSecond: true,
			   timeFormat: 'hh:mm:ss',
			   stepHour: 1,
			   stepMinute: 1,
			   stepSecond: 1
		   })
	   });
</script>