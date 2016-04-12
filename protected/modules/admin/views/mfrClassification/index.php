<div class="page-content">
	<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->               
	<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title">Modal title</h4>
				</div>
				<div class="modal-body">
					Widget settings form goes here
				</div>
				<div class="modal-footer">
					<button type="button" class="btn blue">Save changes</button>
					<button type="button" class="btn default" data-dismiss="modal">Close</button>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<!-- /.modal -->
	<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
	<!-- BEGIN PAGE HEADER-->
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','厂商分类'),'subhead'=>yii::t('app','厂商分类'),'breadcrumbs'=>array(array('word'=>yii::t('app','厂商分类'),'url'=>''))));?>
	
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'material-form',
				'action' => $this->createUrl('mfrClassification/delete' , array('companyId' => $this->companyId)),
				'errorMessageCssClass' => 'help-block',
				'htmlOptions' => array(
					'class' => 'form-horizontal',
					'enctype' => 'multipart/form-data'
				),
		)); ?>
	<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','厂商分类表');?></div>
					<div class="actions">
						<a href="<?php echo $this->createUrl('mfrClassification/create' , array('companyId' => $this->companyId));?>" class="btn blue"><i class="fa fa-pencil"></i> <?php echo yii::t('app','添加');?></a>
						<div class="btn-group">
							<button type="submit"  class="btn red" ><i class="fa fa-ban"></i> <?php echo yii::t('app','删除');?></button>
						</div>
						<a href="<?php echo $this->createUrl('bom/stock' , array('companyId' => $this->companyId));?>" class="btn blue"> <?php echo yii::t('app','返回');?></a>
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
								<th style="width:25%"><?php echo yii::t('app','分类名称');?></th>
								<th><?php echo yii::t('app','说明');?></th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody>
						<?php if($models) :?>
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td><input type="checkbox" class="checkboxes" value="<?php echo $model->lid;?>" name="ids[]" /></td>
								<td style="width:35%"><?php echo $model->classification_name;?></td>
								<td><?php echo $model->remark;?></td>
								<td class="center">
								<a href="<?php echo $this->createUrl('mfrClassification/update',array('id' => $model->lid , 'companyId' => $model->dpid));?>"><?php echo yii::t('app','编辑');?></a>
								</td>
							</tr>
						<?php endforeach;?>
						<?php endif;?>
						<!--test start-->
							<tr class="odd gradeX">
								<td><input type="checkbox" class="checkboxes" value="" name="ids[]" /></td>
								<td style="width:35%">糖</td>
								<td></td>
								<td class="center">
								<a href="#">编辑</a>
								</td>
							</tr>
							<tr class="odd gradeX">
								<td><input type="checkbox" class="checkboxes" value="" name="ids[]" /></td>
								<td style="width:35%">醋</td>
								<td></td>
								<td class="center">
								<a href="#">编辑</a>
								</td>
							</tr>
							<tr class="odd gradeX">
								<td><input type="checkbox" class="checkboxes" value="" name="ids[]" /></td>
								<td style="width:35%">盐</td>
								<td></td>
								<td class="center">
								<a href="#">编辑</a>
								</td>
							</tr>
							<tr class="odd gradeX">
								<td><input type="checkbox" class="checkboxes" value="" name="ids[]" /></td>
								<td style="width:35%">醋</td>
								<td></td>
								<td class="center">
								<a href="#">编辑</a>
								</td>
							</tr>
							<tr class="odd gradeX">
								<td><input type="checkbox" class="checkboxes" value="" name="ids[]" /></td>
								<td style="width:35%">酱</td>
								<td></td>
								<td class="center">
								<a href="#">编辑</a>
								</td>
							</tr>
							<tr class="odd gradeX">
								<td><input type="checkbox" class="checkboxes" value="" name="ids[]" /></td>
								<td style="width:35%">味精</td>
								<td></td>
								<td class="center">
								<a href="#">编辑</a>
								</td>
							</tr>
							<tr class="odd gradeX">
								<td><input type="checkbox" class="checkboxes" value="" name="ids[]" /></td>
								<td style="width:35%">辣椒</td>
								<td></td>
								<td class="center">
								<a href="#">编辑</a>
								</td>
							</tr>
							<tr class="odd gradeX">
								<td><input type="checkbox" class="checkboxes" value="" name="ids[]" /></td>
								<td style="width:35%">姜</td>
								<td></td>
								<td class="center">
								<a href="#">编辑</a>
								</td>
							</tr>
							<tr class="odd gradeX">
								<td><input type="checkbox" class="checkboxes" value="" name="ids[]" /></td>
								<td style="width:35%">蒜</td>
								<td></td>
								<td class="center">
								<a href="#">编辑</a>
								</td>
							</tr>
							<tr class="odd gradeX">
								<td><input type="checkbox" class="checkboxes" value="" name="ids[]" /></td>
								<td style="width:35%">葱</td>
								<td></td>
								<td class="center">
								<a href="#">编辑</a>
								</td>
							</tr>

						<!--test end-->
						</tbody>
					</table>

					<!-- 分页（测式） -->
					<div class="row">
						<div class="col-md-5 col-sm-12">
							<div class="dataTables_info">共 2 页 , 11 条数据 , 当前是第 1 页</div>
						</div>
						<div class="col-md-7 col-sm-12">
							<div class="dataTables_paginate paging_bootstrap">
								<ul class="pagination pull-right" id="yw0">
									<li class=" disabled"><a href="#">&lt;&lt;</a></li>
									<li class=" disabled"><a href="#">&lt;</a></li>
									<li class=" active"><a href="#">1</a></li>
									<li class=""><a href="#">2</a></li>
									<li class=""><a href="#">&gt;</a></li>
									<li class=""><a href="#">&gt;&gt;</a></li>
								</ul>	
							</div>
						</div>
					</div>
					<!-- 分页（测试） 结束 -->

					<!-- 分页（正式） -->
						<?php if($pages->getItemCount()):?>
						<div class="row">
							<div class="col-md-5 col-sm-12">
								<div class="dataTables_info">
									<?php echo yii::t('app','共');?> <?php echo $pages->getPageCount();?> <?php echo yii::t('app','页');?> , <?php echo $pages->getItemCount();?> <?php echo yii::t('app','条数据');?> , <?php echo yii::t('app','当前是第');?> <?php echo $pages->getCurrentPage()+1;?> <?php echo yii::t('app','页');?>
								</div>
							</div>
							<div class="col-md-7 col-sm-12">
								<div class="dataTables_paginate paging_bootstrap">
								<?php $this->widget('CLinkPager', array(
									'pages' => $pages,
									'header'=>'',
									'firstPageLabel' => '<<',
									'lastPageLabel' => '>>',
									'firstPageCssClass' => '',
									'lastPageCssClass' => '',
									'maxButtonCount' => 8,
									'nextPageCssClass' => '',
									'previousPageCssClass' => '',
									'prevPageLabel' => '<',
									'nextPageLabel' => '>',
									'selectedPageCssClass' => 'active',
									'internalPageCssClass' => '',
									'hiddenPageCssClass' => 'disabled',
									'htmlOptions'=>array('class'=>'pagination pull-right')
								));
								?>
								</div>
							</div>
						</div>
						<?php endif;?>					
					
				</div>
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
		</div>
		<?php $this->endWidget(); ?>
	</div>
	<!-- END PAGE CONTENT-->
	<script type="text/javascript">
	$(document).ready(function(){
		$('#material-form').submit(function(){
			if(!$('.checkboxes:checked').length){
				alert("<?php echo yii::t('app','请选择要删除的项');?>");
				return false;
			}
			return true;
		});

	});
	</script>	