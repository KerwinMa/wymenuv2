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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','会员中心'),'subhead'=>yii::t('app','消费返现比例模板'),'breadcrumbs'=>array(array('word'=>yii::t('app','微信会员'),'url'=>$this->createUrl('member/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','微信会员设置'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('member/list' , array('companyId' => $this->companyId,'type'=>0)))));?>
	
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
            <div class="col-md-12 col-sm-12">
                    <ul class="nav nav-tabs">
                            <li><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('/admin/wxlevel/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab">会员等级</a></li>
                            <li><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('/admin/wxpoint/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab">消费积分比例模板</a></li>
                            <li><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('/admin/wxpointvalid/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab">积分有效期模板</a></li>
                            <li class="active"><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('/admin/wxcashback/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab">消费返现比例模板</a></li>
                            <li><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('/admin/wxrecharge/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab">充值模板</a></li>
                    </ul>
            </div>
	<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'branduserlevel-form',
				'action' => $this->createUrl('wxcashback/delete' , array('companyId' => $this->companyId)),
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
					<div class="caption"><i class="fa fa-globe"></i>消费返现比例模板</div>
					<div class="actions">
						<a href="<?php echo $this->createUrl('wxcashback/create' , array('companyId' => $this->companyId));?>" class="btn blue"><i class="fa fa-pencil"></i> <?php echo yii::t('app','添加');?></a>
                                                <a href="javascript:void(0)" class="btn red" onclick="document.getElementById('branduserlevel-form').submit();"><i class="fa fa-times"></i> <?php echo yii::t('app','删除');?></a>
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
                                                    <tr>
                                                        <th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
                                                        <th><?php echo yii::t('app','名称');?></th>
                                                        <th><?php echo yii::t('app','类型');?></th>
                                                        <th><?php echo yii::t('app','最低积分');?></th>
                                                        <th><?php echo yii::t('app','最高积分');?></th>
                                                        <th><?php echo yii::t('app','返现比例');?></th>
                                                        <!-- <th><?php echo yii::t('app','是否有效');?></th> -->
                                                        <th>&nbsp;</th>
                                                    </tr>
						</thead>
						<tbody>
						<?php if($models):?>
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td><input type="checkbox" class="checkboxes" value="<?php echo $model->lid;?>" name="lid[]" /></td>
								<td ><?php echo $model->ccp_name;?></td>
                                                                <td ><?php if($model->point_type){ echo '有效积分';}else{echo '历史积分';}?></td>
								<td ><?php echo $model->min_available_point;?></td>
                                                                <td ><?php echo $model->max_available_point;?></td>
                                                                <td ><?php echo $model->proportion_points;?></td>
                                                                <!-- <td>
                                                                        <?php if($model->is_available) {echo '否';} else {echo '是';} ?>
								</td> -->
								<td class="center">
								<a href="<?php echo $this->createUrl('wxcashback/update',array('lid' => $model->lid , 'companyId' => $model->dpid));?>"><?php echo yii::t('app','编辑');?></a>
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
									<?php echo yii::t('app','共');?> <?php echo $pages->getPageCount();?> <?php echo yii::t('app','页');?>  , <?php echo $pages->getItemCount();?> <?php echo yii::t('app','条数据');?> , <?php echo yii::t('app','当前是第');?><?php echo $pages->getCurrentPage()+1;?> <?php echo yii::t('app','页');?>
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
							<div class="alert alert-danger">
								<strong> 注:</strong> 每一等级的最低积分要比前一等级的最高积分大一。<br/>
								<strong> 例:</strong> 等级一的最低积分为0，最高积分为100；等级二的最低积分就必须设置成101。<br/>
							</div>
				</div>
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
		</div>
		<?php $this->endWidget(); ?>
	</div>
	<!-- END PAGE CONTENT-->