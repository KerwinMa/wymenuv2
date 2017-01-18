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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','店铺管理'),'subhead'=>yii::t('app','联系方式列表'),'breadcrumbs'=>array(array('word'=>yii::t('app','店铺管理'),'url'=>$this->createUrl('company/list' , array('companyId'=>$this->companyId,))),array('word'=>yii::t('app','联系方式列表'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('company/list' , array('companyId' => $this->companyId,)))));?>
	<!-- END PAGE HEADER-->	
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'product-form',
				'action' => $this->createUrl('connectUs/delete' , array('companyId' => $this->companyId)),
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
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','联系方式列表');?></div>
					<div class="actions">
						<a href="<?php echo $this->createUrl('connectUs/create' , array('companyId' => $this->companyId));?>" class="btn blue"><i class="fa fa-pencil"></i> <?php echo yii::t('app','添加');?></a>
						<div class="btn-group">
							<button type="submit"  class="btn red" ><i class="fa fa-ban"></i> <?php echo yii::t('app','删除');?></button>
						</div>
						<div class="btn-group">
							<button type="button"  class="btn pink ceshi " style="display: none;"><i class="fa fa-ban"></i> <?php echo yii::t('app','测试');?></button>
						</div>
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th class="table-checkbox">
                                   <input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" />
                                </th>
                                <th><?php echo yii::t('app','序号');?></th>
								<th><?php echo yii::t('app','类型');?></th>
								<th><?php echo yii::t('app','号码');?></th>
								<th><?php echo yii::t('app','');?></th>
							</tr>
						</thead>
						<tbody>
						<?php if($models) :?>
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">                                    
								<td><input type="checkbox" class="checkboxes" value="<?php echo $model->lid;?>" name="ids[]" /></td>
								<td><?php echo $model->lid%1000;?></td>
								<td><?php switch($model->type){case 0: echo 'QQ';break;case 1: echo '手机';break;case 2: echo 'Email';break;case 3: echo '固话';break;case 4: echo '微信';break;default: echo '';}?></td>								<td><?php echo $model->content;?></td>
								<td class="center">
									<a class="btn btn-sm blue" href="<?php echo $this->createUrl('connectUs/update' , array('lid' => $model->lid , 'companyId' => $this->companyId));?>"><?php echo yii::t('app','编辑');?></a>
								</td>
							</tr>
						<?php endforeach;?>
						<?php else:?>
						<tr><td colspan = '7'>没有找到数据</td></tr>
						<?php endif;?>
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
	$('.ceshi').on('click',function(){
		$.ajax({
	        url : 'http://menu.wymenu.com/wymenuv2/admin/dataAppSync/appUpdate',
	        type : 'POST',
	        data : {
	            versioninfo: '00.00.0401',
	            type: 0,
	            appType: 1,
	        },
	        dataType:'json',
	        success:function(msg){
	            if(!msg.status){
	                layer.msg('亲，已是最新版本！',{icon: 6});
	                isclick = 1;
	            }else{
	                $('#new_version').text(msg.verinfo);
	                var apkUrl = msg.url;
	                //var apkVer = msg.verinfo;
	                var apkVer = 'menucharge';
	                apkVer = apkVer.replace(/\./g, "-");
	                apkVer = 'file:///sdcard/Download/ydc'+apkVer+'.apk';
	                //appcan.locStorage.setVal('path',apkVer);
	                $('#downloadPath_1').val(apkUrl);
	                $('#savedPath_1').val(apkVer);
	                //deleteapk(apkVer);
	                layer_appversion = layer.open({
	                  type: 1,
	                  shade: [0.33,'#ppp',true],
	                  shadeClose: false,
	                  title: false,
	                  //shade: false,
	                  closeBtn: 0,
	                  area: ['260px','180px'],
	                  content: $('#version_div'),
	                  cancel: function(index){
	                      layer.close(index);
	                      layer_appversion=0;
	                  }
	                });
	                layer.style(layer_appversion, {
	                   backgroundColor: 'rgba(242,242,242,1)',
	                   borderRadius: '10px',
	                   border: '2px solid #fff',
	                }); 
	            }
	            isclick = 1;
	        },
	        error:function(){
	            layer.msg('臣妾做不到呐：网络错误',{icon: 7});
	            isclick = 1;
	          },
		});
	});
	</script>	
