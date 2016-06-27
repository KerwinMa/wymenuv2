
<style>
		span.tab{
			color: black;
			border-right:1px dashed white;
			margin-right:10px;
			padding-right:10px;
			display:inline-block;
		}
		span.tab-active{
			color:white;
		}
		.ku-item{
			width:100px;
			height:100px;
			margin-right:20px;
			margin-top:20px;
			margin-left:20px;
			border-radius:5px !important;
			border:2px solid black;
			box-shadow: 5px 5px 5px #888888;
			vertical-align:middle;
		}
		.ku-item-info{
			width:144px;
			font-size:2em;
			color:black;
			text-align:center;
		}
		.ku-purple{
			background-color:#852b99;
		}
		.ku-grey{
			background-color:rgb(68,111,120);
		}
	</style>
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
	<?php if($type==0):?>
		<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','口味设置'),'subhead'=>yii::t('app','单品口味设置'),'breadcrumbs'=>array(array('word'=>yii::t('app','单品口味设置'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('product/list' , array('companyId' => $this->companyId,'type'=>0)))));?>
	<?php elseif($type==1):?>
		<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','口味设置'),'subhead'=>yii::t('app','整单口味设置'),'breadcrumbs'=>array(array('word'=>yii::t('app','整单口味设置'),'url'=>''))));?>
	<?php else:?>
		<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','基础设置'),'subhead'=>yii::t('app','单品口味对应'),'breadcrumbs'=>array(array('word'=>yii::t('app','菜品设置'),'url'=>$this->createUrl('product/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','单品口味对应'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('product/list' , array('companyId' => $this->companyId,'type'=>0)))));?>
	<?php endif;?>
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
		<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-cogs"></i><a href="<?php echo $this->createUrl('taste/index',array('companyId'=>$this->companyId,'type'=>1));?>"><span class="tab <?php if($type==1){ echo 'tab-active';}?>"><?php echo yii::t('app','整单口味设置');?></span></a><a href="<?php echo $this->createUrl('taste/index',array('companyId'=>$this->companyId,'type'=>0));?>"><span class="tab <?php if($type==0){ echo 'tab-active';}?>" ><?php echo yii::t('app','单品口味设置');?></span></a><a href="<?php echo $this->createUrl('taste/productTaste',array('companyId'=>$this->companyId,'type'=>2));?>"><span class="tab <?php if($type==2){ echo 'tab-active';}?>" ><?php echo yii::t('app','单品口味对应');?></span></a></div>
					                     <div class="actions">						
                                                <div class="btn-group">
							<?php echo CHtml::dropDownList('selectCategory', $categoryId, $categories , array('class'=>'form-control'));?>
						</div>						
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
                                                            <th class="col-md-3"><?php echo yii::t('app','产品名称');?></th>
                                                            <th><?php echo yii::t('app','产品口味');?></th>
                                                            <th  class="col-md-1">&nbsp;</th>
							</tr>
						</thead>
						<tbody>
						<?php if($models):?>
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td ><?php echo $model->product_name;?></td>
								<td>
									<?php foreach($model->productTaste as $val){
											echo TasteClass::getTasteName($val->taste_group_id,$this->companyId).' ';
									}?>
								</td>
								<td class="center">
								<a href="<?php echo $this->createUrl('taste/updateProductTaste',array('lid' => $model->lid , 'companyId' => $model->dpid));?>"><?php echo yii::t('app','编辑');?></a>
								</td>
							</tr>
						<?php endforeach;?>
						<?php endif;?>
						</tbody>
					</table>
						<?php if($pages->getItemCount()):?>
						<div class="row">
							<div class="col-md-5 col-sm-12">
								<div class="dataTables_info">
									<?php echo yii::t('app','共');?> <?php echo $pages->getPageCount();?> <?php echo yii::t('app','页');?>  , <?php echo $pages->getItemCount();?> <?php echo yii::t('app','条数据');?> , <?php echo yii::t('app','当前是第');?> <?php echo $pages->getCurrentPage()+1;?> <?php echo yii::t('app','页');?>
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
	</div>
	<!-- END PAGE CONTENT-->
        <script type="text/javascript">
	$(document).ready(function(){
		$('#selectCategory').change(function(){
			var cid = $(this).val();
			location.href="<?php echo $this->createUrl('taste/productTaste' , array('companyId'=>$this->companyId));?>/cid/"+cid;
		});
	});
	</script>