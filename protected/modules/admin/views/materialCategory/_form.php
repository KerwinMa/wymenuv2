<?php $form=$this->beginWidget('CActiveForm', array(
		'id' => 'materialCategory-form',
		'errorMessageCssClass' => 'help-block',
		'htmlOptions' => array(
			'class' => 'form-horizontal',
			'enctype' => 'multipart/form-data'
		),
)); ?>
	<div class="form-body">
		<div class="form-group">
			<?php echo $form->label($model, 'category_name',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'category_name',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('category_name')));?>
				<?php echo $form->error($model, 'category_name' )?>
			</div>
		</div>
											<div class="form-group">
			<?php echo $form->label($model, 'order_num',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'order_num',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('order_num')));?>
				<?php echo $form->error($model, 'order_num' )?>
			</div>
		</div>
	<div class="modal-footer">
		<button type="button" data-dismiss="modal" class="btn default"><?php echo yii::t('app','取 消');?></button>
		<input type="submit" class="btn green" id="create_btn" value="<?php echo yii::t('app','确 定');?>">
	</div>
<?php $this->endWidget(); ?>