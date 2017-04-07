<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/PCASClass.js');?>
<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-1.7.1.min.js');?>
<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/mobiscroll_002.css');?>
<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/mobiscroll.css');?>
<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/mobiscroll_003.css');?>
<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/mobiscroll_002.js');?>
<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/mobiscroll_004.js');?>
<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/mobiscroll.js');?>
<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/mobiscroll_003.js');?>
<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/mobiscroll_005.js');?> 
<style>
.selectedclass{
	font-size: 14px;
	color: #333333;
	height: 34px;
	line-height: 34px;
	padding: 6px 12px;
}
.timeset{
	width: 88%;
	margin-left: 6%;
	padding-top: 10px;
}
.timeset{
	width: 88%;
	margin-left: 6%;
	padding-top: 10px;
}
.android-ics .dw {
	top: 50px;
    left: 650px;
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
	<div id="main2" name="main2" style="min-width: 300px;min-height:200px;display:none;" onMouseOver="this.style.backgroundColor='rgba(255,222,212,1)'" onmouseout="this.style.backgroundColor=''">
		<div id="content"></div>
	</div>
	
	<!-- /.modal -->
	<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
	<!-- BEGIN PAGE HEADER-->
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','店铺管理'),'url'=>$this->createUrl('company/list' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','微店列表'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('company/list' , array('companyId' => $this->companyId,)))));?>
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
            <?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'company-form',
				'action' => $this->createUrl('companyWx/delete', array('companyId' => $this->companyId)),
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
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','微店列表');?></div>
					<div class="actions">
					<div class="btn-group">
						<select id="province" name="province" class="selectedclass"></select>
						<select id="city" name="city" class="selectedclass"></select>
						<select id="area" name="area" class="selectedclass"></select>
                    </div>
                    <div class="btn-group">
	                	<button type="button" id="cityselect" class="btn green" ><i class="fa fa-repeat"></i> <?php echo yii::t('app','查询');?></button>
	                </div>
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
								<?php if(Yii::app()->user->role < '5'): ?><th>ID</th><?php endif; ?>
                                <th><?php echo yii::t('app','店铺名称');?></th>
								<th>logo</th>
								<th><?php echo yii::t('app','是否开通微店');?></th>
								<th><?php echo yii::t('app','营业状态');?></th>
								<th><?php echo yii::t('app','营业时间');?></th>
								<th><?php echo yii::t('app','打烊时间');?></th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody>
						<?php if($models) :?>
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td><?php if(Yii::app()->user->role >= User::POWER_ADMIN_VICE && Yii::app()->user->role <= User::ADMIN_AREA&&$model->type=="0"):?><?php else:?><input type="checkbox" class="checkboxes" value="<?php echo $model->dpid;?>" name="companyIds[]" /><?php endif;?></td>
								<?php if(Yii::app()->user->role < '5'): ?><td><?php echo $model->dpid;?></td><?php endif; ?>
                                <td><a ><?php echo $model->company_name;?></a></td>
								<td ><img width="100" src="<?php echo $model->logo;?>" /></td>
								<td >
								<?php if($model->property){
									switch ($model->property->is_rest){
										case 0: $info = '未开通微店'; echo yii::t('app','未开通');break;
										case 1: $info = '总部强制关闭'; echo yii::t('app','开通');break;
										case 2: $info = '店铺打烊了'; echo yii::t('app','开通');break;
										case 3: $info = '营业中...'; echo yii::t('app','开通');break;
										default: $info = '未开通微店'; echo yii::t('app','未知');break;
									}
								}else{$info=''; echo yii::t('app','未开通');}?></td>
								<td ><?php if($model->property){
									echo $model->property->rest_message?$model->property->rest_message:$info;
								};?></td>
								<td ><?php if($model->property){
									echo $model->property->shop_time;
								};?></td>
								<td ><?php if($model->property){
									echo $model->property->closing_time;
								};?></td>
								<td class="center">
									<div class="actions">
                                        <?php if(Yii::app()->user->role < User::ADMIN_AREA) : ?>
                                        	<?php if($model->property):
                                        		if($model->property->is_rest == '0'):?>
                                        			<a class='btn green open-wxdpid' style="margin-top: 5px;" rest='2' dpid='<?php echo $model->dpid;?>'><?php echo yii::t('app','开通');?></a>
                                        		<?php elseif($model->property->is_rest == '1'):?>
                                        			<a class='btn green open-wxdpid' style="margin-top: 5px;" rest='3' dpid='<?php echo $model->dpid;?>'><?php echo yii::t('app','开店');?></a>
                                        		<?php elseif($model->property->is_rest == '2'):?>
                                        			<a class='btn green open-wxdpid' style="margin-top: 5px;" rest='3' dpid='<?php echo $model->dpid;?>'><?php echo yii::t('app','开店');?></a>
                                        		<?php elseif($model->property->is_rest == '3'):?>
                                        			<a class='btn green open-wxdpid' style="margin-top: 5px;" rest='2' dpid='<?php echo $model->dpid;?>'><?php echo yii::t('app','关店');?></a>
                                        			<a class='btn green open-wxdpid' style="margin-top: 5px;" rest='1' dpid='<?php echo $model->dpid;?>'><?php echo yii::t('app','强制关店');?></a>
                                        		<?php endif;?>
                                        	<?php else:?>
                                        		<a class='btn green open-wxdpid' style="margin-top: 5px;" rest='2' dpid='<?php echo $model->dpid;?>'><?php echo yii::t('app','开通');?></a>
                                        	<?php endif;?>
                                            
                                        <?php else: ?>
                                        	<?php if($model->property):
                                        		if($model->property->is_rest == '2'):?>
                                        			<a class='btn green open-wxdpid' style="margin-top: 5px;" rest='3' dpid='<?php echo $model->dpid;?>'><?php echo yii::t('app','开店');?></a>
                                        		<?php elseif($model->property->is_rest == '3'):?>
                                        			<a class='btn green open-wxdpid' style="margin-top: 5px;" rest='2' dpid='<?php echo $model->dpid;?>'><?php echo yii::t('app','关店');?></a>
                                        		<?php endif;?>
                                        	<?php else:?>
                                        		
                                        	<?php endif;?>
                                        <?php endif;?>
                                         <a  class='btn green setAppid' style="margin-top: 5px;" id="setAppid<?php echo $model->dpid;?>" dpid="<?php echo $model->dpid;?>" dpidname="<?php echo $model->company_name;?>"><?php echo yii::t('app','编辑');?></a>
                                    </div>	
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
            <?php $this->endWidget(); ?>
	</div>
	<!-- END PAGE CONTENT-->
<script>
jQuery(document).ready(function() {
								


								
	new PCAS("province","city","area","<?php echo $province;?>","<?php echo $city;?>","<?php echo $area;?>");
	function genQrcode(that){
		var id = $(that).attr('lid');
		var $parent = $(that).parent();
		$.get('<?php echo $this->createUrl('/admin/company/genWxQrcode');?>/dpid/'+id,function(data){
			if(data.status){
				$parent.find('img').remove();
				$parent.prepend('<img style="width:100px;" src="/wymenuv2/./'+data.qrcode+'">');
			}
			alert(data.msg);
		},'json');
	}
	$('#cityselect').on('click',function(){
		 var province = $('#province').children('option:selected').val();
         var city = $('#city').children('option:selected').val();
         var area = $('#area').children('option:selected').val();
         location.href="<?php echo $this->createUrl('companyWx/index' , array('companyId'=>$this->companyId));?>/province/"+province+"/city/"+city+"/area/"+area;
	});
	$('#province').change(function(){
		changeselect();
	});
	$('#city').change(function(){
		changeselect();
	});
	$('#area').change(function(){
		changeselect();
	});
	 function changeselect(){
		 var province = $('#province').children('option:selected').val();
			var city = $('#city').children('option:selected').val();
	        var area = $('#area').children('option:selected').val();
			location.href="<?php echo $this->createUrl('companyWx/index' , array('companyId'=>$this->companyId));?>/province/"+province+"/city/"+city+"/area/"+area;
		 }

	$('.open-wxdpid').on('click',function(){
		var rest = $(this).attr('rest');
		var dpid = $(this).attr('dpid');
		
		var url = "<?php echo $this->createUrl('companyWx/store');?>/companyId/"+dpid+"/rest/"+rest;
		//alert(url);
		//return false;
        $.ajax({
            url:url,
            type:'GET',
            //data:orderid,//CF
            async:false,
            dataType: "json",
            success:function(msg){
                var data=msg;
                if(data.status){
                	layer.msg('成功！！！');
                	location.reload();
                }else{
                	layer.msg('失败！！！');
                }
            },
            error: function(msg){
                layer.msg('网络错误！！！');
            }
        });
		});
	$('.setAppid').on('click',function(){

		
		$('#content').html('');
		var dpid = $(this).attr('dpid');
		var dpidname = $(this).attr('dpidname');
		var content = '<div class="timeset"><span style="font-size: 18px;">'+dpidname+'</span><span>  营业时间设置</span></div>'
					+ '<div class="timeset"><input id="shop_time" placeholder="营业时间"/></div>'
					+ '<div class="timeset"><input id="closing_time" placeholder="打烊时间"/></div>'
					+ '<div class="timeset"><button id="appid_store" dpid="'+dpid+'" class="btn green">确认</button></div>'
					;
		$('#content').html(content);
		//alert(dpid);
		layer_zhexiantu=layer.open({
		     type: 1,
		     //shift:5,
		     shade: [0.5,'#fff'],
		     //move:'#main2',
		     moveOut:true,
		     offset:['100px','200px'],
		     shade: false,
		     title: false, //不显示标题
		     area: ['auto', 'auto'],
		     content: $('#main2'),//$('#productInfo'), //捕获的元素
		     cancel: function(index){
		         layer.close(index);
		         layer_zhexiantu=0;
		     }
		 });

		   layer.style(layer_zhexiantu, {
			   backgroundColor: 'rgba(255,255,255,0.2)',
			 });


			var currYear = (new Date()).getFullYear();
			var opt = {};
			opt.time = {
			    preset : 'time'
			};
			opt.default = {
				theme : 'android-ics light', //皮肤样式
				display : 'modal', //显示方式
				mode : 'scroller', //日期选择模式
				dateFormat : 'hh:mm:ss',
				//width : cHeight / 1.2,
				//height : cHeight / 1.6,
				width:100,
				height:40,
				circular:true,
				showScrollArrows:true,
				lang : 'zh',
				showNow : true,
				nowText : "现在",
				startYear : currYear , //开始年份
				endYear : currYear  //结束年份
			};
			
			var optDateTime = $.extend(opt['time'], opt['default']);
			// var optTime = $.extend(opt['time'], opt['default']);
			$("#shop_time").mobiscroll(optDateTime).time(optDateTime);
			$("#closing_time").mobiscroll(optDateTime).time(optDateTime);

			 
		$('#appid_store').on('click',function(){
			var shop_time = $('#shop_time').val();
			var closing_time = $('#closing_time').val();
			var dpid = $(this).attr('dpid');
			//alert(appid);
			if(shop_time&&closing_time){
				
				//return false;
				var url = "<?php echo $this->createUrl('companyWx/storetime');?>/companyId/"+dpid+"/shop_time/"+shop_time+"/closing_time/"+closing_time;
		        $.ajax({
		            url:url,
		            type:'GET',
		            //data:orderid,//CF
		            async:false,
		            dataType: "json",
		            success:function(msg){
		                var data=msg;
		                if(data.status){
		                	layer.msg('成功！！！');
		                	layer.close(layer_zhexiantu);
		   		        	layer_zhexiantu=0;
		   		        	location.reload();
		                }else{
		                	layer.msg('失败！！！');
		                }
		            },
		            error: function(msg){
		                layer.msg('网络错误！！！');
		            }
		        });
			}else{
				layer.msg('请完善信息！！！');}
		});
	});
});	
</script>