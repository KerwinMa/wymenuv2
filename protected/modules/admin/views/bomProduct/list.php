<link href="../../../../css/jxcgl.css" rel="stylesheet" type="text/css">
<div class="page-content">
	<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->               
	<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title">关系图</h4>
				</div>
				<div class="modal-body">
					<img alt="" src="">
				</div>
				<div class="modal-footer">
					<!--  
					<button type="button" class="btn blue">Save changes</button>
					-->
					<button type="button" class="btn default" data-dismiss="modal">确定</button>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<!-- /.modal -->
	<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
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
		.ku-item.dpgl{
			background-image:url(../../../../img/waiter/icon-dpjcsz.png);
			background-position: 15px 15px;
    		background-repeat: no-repeat;
		}
		.ku-item.czygl{
			background-image:url(../../../../img/waiter/icon-dpjcsz.png);
			background-position: -135px 15px;
    		background-repeat: no-repeat;
		}
		.ku-item.qxsz{
			background-image:url(../../../../img/waiter/icon-dpjcsz.png);
			background-position: -285px 15px;
    		background-repeat: no-repeat;
		}
		.ku-item.fdgl{
			background-image:url(../../../../img/waiter/icon-dpjcsz.png);
			background-position: -425px 15px;
    		background-repeat: no-repeat;
		}
		.ku-item.wxdp{
			background-image:url(../../../../img/waiter/icon-dpjcsz.png);
			background-position: -575px 15px;
    		background-repeat: no-repeat;
		}
		.ku-item.tbsj{
			background-image:url(../../../../img/waiter/icon-dpjcsz.png);
			background-position: -725px 15px;
    		background-repeat: no-repeat;
		}
	</style>
	<!-- BEGIN PAGE CONTENT-->
	<?php if($type==0):?>
		<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','菜品设置'),'subhead'=>yii::t('app','菜品设置'),'breadcrumbs'=>array(array('word'=>yii::t('app','菜品设置'),'url'=>''))));?>
	<?php elseif($type==1):?>
		<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','餐桌设置'),'subhead'=>yii::t('app','餐桌设置'),'breadcrumbs'=>array(array('word'=>yii::t('app','餐桌设置'),'url'=>''))));?>
	<?php elseif ($type==2):?>
		<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','打印设置'),'subhead'=>yii::t('app','打印设置'),'breadcrumbs'=>array(array('word'=>yii::t('app','打印设置'),'url'=>''))));?>
	<?php else:?>
		<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','收银设置'),'subhead'=>yii::t('app','收银设置'),'breadcrumbs'=>array(array('word'=>yii::t('app','收银设置'),'url'=>''))));?>
	<?php endif;?>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet purple box">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-cogs"></i><span class="tab"><?php echo yii::t('app','菜品设置');?></span><span class="tab"><?php echo yii::t('app','餐桌设置');?></span><span class="tab"><?php echo yii::t('app','打印设置');?></span><span class="tab"><?php echo yii::t('app','菜品设置');?></span></div>
				</div>
				<div class="portlet-body" style="min-height: 750px">
					<a href="<?php echo $this->createUrl('company/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left">
							<div class="ku-item ku-purple dpgl"></div>
							<div class="ku-item-info">店铺管理</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('user/index',array('companyId'=>$this->companyId,'type'=>0));?>">
						<div class="pull-left">
							<div class="ku-item ku-purple czygl"></div>
							<div class="ku-item-info">操作员管理</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('weixin/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left">
							<div class="ku-item ku-purple wxdp"></div>
							<div class="ku-item-info">微信设置</div>
						</div>
					</a>
					<!--
					<a href="#">
						<div class="pull-left">
							<div class="ku-item ku-purple qxsz"></div>
							<div class="ku-item-info">权限设置</div>
						</div>
					</a>
					-->
					<a href="<?php echo $this->createUrl('synchronous/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left">
							<div class="ku-item ku-purple tbsj"></div>
							<div class="ku-item-info">同步数据</div>
						</div>
					</a>
					
				</div>
			</div>
		</div>
	</div>
	<!-- END PAGE CONTENT-->
	<script>
        $(document).ready(function() {
        	 $('.relation').click(function(){
                 $('.modal').modal();
            });
        });
	</script>