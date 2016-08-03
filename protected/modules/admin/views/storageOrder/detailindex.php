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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','进销存管理'),'subhead'=>yii::t('app','入库单详情列表'),'breadcrumbs'=>array(array('word'=>yii::t('app','库存管理'),'url'=>$this->createUrl('bom/bom' , array('companyId'=>$this->companyId,'type'=>2,))),array('word'=>yii::t('app','入库单管理'),'url'=>$this->createUrl('storageOrder/index' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','入库单详情'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('storageOrder/index' , array('companyId' => $this->companyId,)))));?>
	
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'material-form',
				'action' => $this->createUrl('storageOrder/detailDelete' , array('companyId' => $this->companyId,'slid'=>$slid,'status'=>$status,)),
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
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','入库单详情列表');?></div>
					<div class="actions">
						<?php if($status == 0 || $status == 2):?>
							<a href="<?php echo $this->createUrl('storageOrder/detailcreate' , array('companyId' => $this->companyId, 'lid'=>$slid));?>" class="btn blue"><i class="fa fa-pencil"></i> <?php echo yii::t('app','添加');?></a>
						<div class="btn-group">
							<button type="submit"  class="btn red" ><i class="fa fa-ban"></i> <?php echo yii::t('app','删除');?></button>
						</div>
						<?php endif;?>
						<a href="<?php echo $this->createUrl('storageOrder/index' , array('companyId' => $this->companyId));?>" class="btn blue"> <?php echo yii::t('app','返回');?></a>
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
								<th style="width:16%"><?php echo yii::t('app','品项名称');?></th>
								<th><?php echo yii::t('app','入库进价');?></th>
								<th><?php echo yii::t('app','入库库存');?></th>
								<th><?php echo yii::t('app','赠品数量');?></th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody>
						<?php if($models) :?>
						<div style="display: none;" id="storagedetail" val="1"></div>
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td><input type="checkbox" class="checkboxes" value="<?php echo $model->lid;?>" name="ids[]" /></td>
								<td style="width:16%"><?php echo Common::getmaterialName($model->material_id);?></td>
								<td><?php echo $model->price;?></td>
								<td ><?php echo $model->stock;?></td>
								<td><?php echo $model->free_stock;?></td>
								<td class="center">
								<?php if($status == 0 || $status == 2):?>
									<a href="<?php echo $this->createUrl('storageOrder/detailupdate',array('lid' => $model->lid , 'slid'=>$model->storage_id,  'companyId' => $model->dpid));?>"><?php echo yii::t('app','编辑');?></a>
								<?php endif;?>
								</td>
							</tr>
						<?php endforeach;?>
						<?php else:?>
						<div style="display: none;" id="storagedetail" val="0"></div>
						<?php endif;?>
							<tr>
								<td colspan="6" style="text-align: right;">
								<?php if($storage->status==1):?><?php if(Yii::app()->user->role<3):?><input id="storage-in" type="button" class="btn blue" value="确认入库" storage-id="<?php echo $storage->lid;?>" /><?php else:?><span style="color:red">等待确认入库</span><?php endif;?>
								<?php elseif($storage->status==3):?><span style="color:red">已入库</span>
								<?php elseif($storage->status==2):?><?php if(Yii::app()->user->role<3):?><input id="status-2" type="button" class="btn blue" value="重新送审" storage-id="<?php echo $storage->lid;?>" /><?php else:?><span style="color:red">等待重新送审</span><?php endif;?>
								<?php elseif($storage->status==0):?><?php if(Yii::app()->user->role<3):?><input id="status-0" type="button" class="btn blue" value="确认送审" storage-id="<?php echo $storage->lid;?>" /><?php else:?><span style="color:red">正在编辑</span><?php endif;?>
								<?php elseif($storage->status==4):?><?php if(Yii::app()->user->role<3):?><input id="status-4" type="button" class="btn blue" value="审核通过" storage-id="<?php echo $storage->lid;?>" />&nbsp;<input id="status-1" type="button" class="btn blue" value="驳回" storage-id="<?php echo $storage->lid;?>" /><?php else:?><span style="color:red">等待审核</span><?php endif;?>
								<?php endif;?>
								</td>
							</tr>
						</tbody>
					</table>
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
		$('#storage-in').click(function(){
			var id = $(this).attr('storage-id');
			var storagedetail = $('#storagedetail').attr('val');
			
			if(storagedetail == 1){
			$.ajax({
					url:'<?php echo $this->createUrl('storageOrder/storageIn' , array('companyId'=>$this->companyId));?>',
					data:{sid:id},
					success:function(msg){
						if(msg=='true'){
						   alert('入库成功!');		
						}else{
							alert('入库失败!');
						}
						//history.go(0);
						location.href="<?php echo $this->createUrl('storageOrder/index' , array('companyId'=>$this->companyId,));?>";
					},
				});
			}else{
					alert('请添加需要入库的品项');
				}
		});
		$('#status-0').click(function(){
			var pid = $(this).attr('storage-id');
			var storagedetail = $('#storagedetail').attr('val');
			
			if(storagedetail == 1){
			if(confirm('确认送审该入库单')){
				$.ajax({
					url:'<?php echo $this->createUrl('storageOrder/storageVerify',array('companyId'=>$this->companyId,'status'=>4));?>',
					data:{type:4,pid:pid},
					success:function(msg){
						if(msg=='true'){
							alert('送审成功');
						}else{
							alert('送审失败');
						}
						//history.go(0);
						location.href="<?php echo $this->createUrl('storageOrder/index' , array('companyId'=>$this->companyId,));?>";
					}
				});
			}
			}else{
				alert('请添加需要入库的详细品项');
				}
		});
		$('#status-1').click(function(){
			var pid = $(this).attr('storage-id');
			var storagedetail = $('#storagedetail').attr('val');
			
			if(storagedetail == 1){
			if(confirm('确认驳回该入库单')){
				$.ajax({
					url:'<?php echo $this->createUrl('storageOrder/storageVerify',array('companyId'=>$this->companyId,'status'=>4));?>',
					data:{type:2,pid:pid},
					success:function(msg){
						if(msg=='true'){
							alert('驳回成功');
						}else{
							alert('驳回失败');
						}
						//history.go(0);
						location.href="<?php echo $this->createUrl('storageOrder/index' , array('companyId'=>$this->companyId,));?>";
					}
				});
			}
			}else{
				alert('请添加需要入库的详细品项');
				}
		});
		$('#status-2').click(function(){
			var pid = $(this).attr('storage-id');
			var storagedetail = $('#storagedetail').attr('val');
			
			if(storagedetail == 1){
			if(confirm('确认重新送审该入库单')){
				$.ajax({
					url:'<?php echo $this->createUrl('storageOrder/storageVerify',array('companyId'=>$this->companyId,'status'=>4));?>',
					data:{type:4,pid:pid},
					success:function(msg){
						if(msg=='true'){
							alert('重新审核成功');
						}else{
							alert('重新审核失败');
						}
						//history.go(0);
						location.href="<?php echo $this->createUrl('storageOrder/index' , array('companyId'=>$this->companyId,));?>";
					}
				});
			}
			}else{
				alert('请添加需要入库的详细品项');
				}
		});
		$('#status-4').click(function(){
			var pid = $(this).attr('storage-id');
			
			if(confirm('确认审核入库单')){
				$.ajax({
					url:'<?php echo $this->createUrl('storageOrder/storageVerify',array('companyId'=>$this->companyId,'status'=>1));?>',
					data:{type:1,pid:pid},
					success:function(msg){
						if(msg=='true'){
							alert('审核成功');
						}else{
							alert('审核失败');
						}
						//history.go(0);
						location.href="<?php echo $this->createUrl('storageOrder/index' , array('companyId'=>$this->companyId,));?>";
					}
				});
			}
			
		});

		
	});
	</script>	