<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/default.css'); ?>
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
	<div class="row">
		<div class="col-md-12">
			<!-- BEGIN PAGE TITLE & BREADCRUMB-->			
			<h3 class="page-title">
				<small></small>
			</h3>
			<!-- END PAGE TITLE & BREADCRUMB-->
		</div>
	</div>
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	
	<div class="row">
		<div class="col-md-10">
			<?php if($siteTypes):?>
					<div class="tabbable tabbable-custom">
						<ul class="nav nav-tabs">
						<?php foreach ($siteTypes as $key=>$siteType):?>
							<li typeId="<?php echo $key ;?>" class="tabtitle <?php if($key == $typeId) echo 'active';?>"><a href="#tab_1_<?php echo $key;?>" data-toggle="tab"><?php echo $siteType ;?></a></li>
						<?php endforeach;?>
                                                        <li typeId="tempsite" class="tabtitle <?php if($typeId == 'tempsite') echo 'active';?>"><a href="#tab_1_tempsite" data-toggle="tab">临时座/排队</a></li>
                                                        <li typeId="reserve" class="tabtitle <?php if($typeId == 'reserve') echo 'active';?>"><a href="#tab_1_reserve" data-toggle="tab">预定/外卖</a></li>
						</ul>
						<div class="tab-content" id="tabsiteindex">
							
							<!-- END EXAMPLE TABLE PORTLET-->												
						</div>
					</div>
				<?php endif;?>
			
		</div>	
		<div class="col-md-2 messagepart">
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-volume-up"></i>2条未读消息</div>					
				</div>
				<div class="portlet-body message_list">
					<ul>
                                                <li class="bg-red">[K001-12:01]<br>开发票：上海物易网络科技有限公司</li>
                                                <li class="bg-blue">[牡丹江厅-12:00]<br>催菜：都等了半个小时了</li>
						<li>[牡丹江厅-12:00]<br>催菜：都等了半个小时了</li>
						<li>[牡丹江厅-12:00]<br>催菜：都等了半个小时了</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
        <script type="text/javascript">
            $(document).ready(function() {
                $('body').addClass('page-sidebar-closed');
                $('<audio id="chatAudio"><source src="/wymenuv2/admin/audio/notify.ogg" type="audio/ogg"><source src="/wymenuv2/admin/audio/notify.mp3" type="audio/mpeg"><source src="/wymenuv2/admin/audio/notify.wav" type="audio/wav"></audio>').appendTo('body');
                $('#tabsiteindex').load('<?php echo $this->createUrl('defaultSite/showSite',array('typeId'=>$typeId,'companyId'=>$this->companyId));?>'); 
        });            
        $('.tabtitle').on('click', function(){
            var typeId=$(this).attr('typeid');
            //alert(typeId);
            $('#tabsiteindex').load('<?php echo $this->createUrl('defaultSite/showSite',array('companyId'=>$this->companyId));?>'+'/typeId/'+typeId); 
        });     
	</script>
	<!-- END PAGE CONTENT-->
        