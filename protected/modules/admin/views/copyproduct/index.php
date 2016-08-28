<script type="text/javascript">
function fun()
		{
			
			if($(this).checked){
				document.$("FirstItem").checked;
			}

		};
		
		
		
</script>
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
	
	
	<div id="printRsultListdetail" style="margin:0;padding:0;display:none;width:96%;height:96%;">		                
         <div class="modal-header">
         	<h4 class="modal-title">选择需要下发菜品的店铺</h4>
         </div>
         <div class="modal-body">
	         <div class="portlet-body" id="table-manage">  
		         <div id="reportlistdiv" style="display:inline-block;width:100%;font-size:1.5em;">
			         <ul style="margin:0;padding:0;list-style:none;"><?php $a=1;?>
			         <?php if($dpids):?>
			         <?php foreach($dpids as $dpid):?>
				         <li>
					         <div style="width:10%;float:left;"><?php echo $a++;?></div>
					         <div style="width:25%;float:left;"><?php echo $dpid['company_name'];?></div>
					         <div style="width:14%;float:left;">
					         	<input style="height:20px;" type="checkbox" class="checkdpids" value="<?php echo $dpid['dpid'];?>" name="reportlist[]" />
					         </div>
				         </li>
				     <?php endforeach;?>
				     <?php endif;?>
				         <li>
					         <div style="width:10%;float:left;"></div>
					         <div style="width:25%;float:left;"></div>
					         <div style="width:14%;float:right;">
					         	<input style="height:20px;" type="checkbox" class="group-checkable" data-set="#reportlistdiv .checkdpids" />
					         	全选
					         </div>
					         
				         </li>                                                                       
			         </ul>
		         </div>
	         </div>
	         <div class="modal-footer">
		         <button id="printall" type="button" class="btn blue">确认下发</button>
		         <!-- button id="selectall" type="button" class="btn blue">全选</button> -->
		         <button id="closeall" type="button" class="btn default" data-dismiss="modal">关闭</button>
		         
	         </div>
		 </div>
				                	
		</div>
	<!-- /.modal -->
	<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
	<!-- BEGIN PAGE HEADER-->
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','基础设置'),'subhead'=>yii::t('app','产品列表'),'breadcrumbs'=>array(array('word'=>yii::t('app','菜品设置'),'url'=>$this->createUrl('product/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','产品下发'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('product/list' , array('companyId' => $this->companyId,'type'=>0)))));?>
	
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'copyproduct-form',
				'action' => $this->createUrl('copyproduct/storProduct' , array('companyId' => $this->companyId)),
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
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','产品列表');?></div>
					<div class="actions">
						<div class="btn-group">
							<?php echo CHtml::dropDownList('selectCategory', $categoryId, $categories , array('class'=>'form-control'));?>
						</div>
<!-- 						<a href="<?php echo $this->createUrl('product/create' , array('companyId' => $this->companyId));?>" class="btn blue"><i class="fa fa-pencil"></i> <?php echo yii::t('app','添加');?></a>-->
							<div class="btn-group"> 
							<button type="button" id="su"  class="btn red form-control" ><i class="fa fa-share-square-o "></i> <?php echo yii::t('app','菜单下发');?></button>
 						</div> 
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th style="width:10%" class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /><?php echo yii::t('app','全选');?></th>
								<th style="width:25%"><?php echo yii::t('app','名称');?></th>
								<th><?php echo yii::t('app','类别');?></th>
								<th><?php echo yii::t('app','现价');?></th>
							</tr>
						</thead>
						<tbody>
						<?php if($models) :?>
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td><input id="<?php echo $model->lid;?>" type="checkbox" class="checkboxes" value="<?php echo $model->lid;?>" phs_code="<?php echo $model->phs_code;?>" chs_code="<?php echo $model->chs_code;?>" name="ids[]" />
								</td>
								<td style="width:25%"><?php echo $model->product_name;?></td>
								<td><?php if(!empty($model->category->category_name)) echo $model->category->category_name;?></td>
								<td ><?php echo $model->original_price;?></td>
								
							</tr>
						<?php endforeach;?>
						<?php endif;?>
						</tbody>
						<div style="display: none;">
						<input type="hidden" id="phscode" name="phscode" value="" />
						<input type="hidden" id="chscode" name="chscode" value="" />
						<input type="hidden" id="dpids" name="dpids" value="" />
						</div>
					</table>
					
<!-- 						<php if($pages->getItemCount()):?>
<!-- 						<div class="row"> --
<!-- 							<div class="col-md-5 col-sm-12"> 
<!-- 								<div class="dataTables_info">
									<php echo yii::t('app','共');?> <php echo $pages->getPageCount();?> <php echo yii::t('app','页');?> , <php echo $pages->getItemCount();?> <php echo yii::t('app','条数据');?> , <php echo yii::t('app','当前是第');?> <php echo $pages->getCurrentPage()+1;?> <php echo yii::t('app','页');?>
<!-- 								</div> --
<!-- 							</div> --
<!-- 							<div class="col-md-7 col-sm-12"> --
<!-- 								<div class="dataTables_paginate paging_bootstrap"> --
								<php $this->widget('CLinkPager', array(
// 									'pages' => $pages,
// 									'header'=>'',
// 									'firstPageLabel' => '<<',
// 									'lastPageLabel' => '>>',
// 									'firstPageCssClass' => '',
// 									'lastPageCssClass' => '',
// 									'maxButtonCount' => 8,
// 									'nextPageCssClass' => '',
// 									'previousPageCssClass' => '',
// 									'prevPageLabel' => '<',
// 									'nextPageLabel' => '>',
// 									'selectedPageCssClass' => 'active',
// 									'internalPageCssClass' => '',
// 									'hiddenPageCssClass' => 'disabled',
// 									'htmlOptions'=>array('class'=>'pagination pull-right')
// 								));
// 								?>
<!-- 								</div> -->
<!-- 							</div> -->
<!-- 						</div> 
						<php endif;?>					
-->					
				</div>
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
		</div>
		<?php $this->endWidget(); ?>
	</div>
	<!-- END PAGE CONTENT-->
	<script type="text/javascript">
	$(document).ready(function(){
		
		$('#product-form').submit(function(){
			if(!$('.checkboxes:checked').length){
				alert("<?php echo yii::t('app','请选择要删除的项');?>");
				return false;
			}
			return true;
		});
		$('.s-btn').on('switch-change', function () {
			var id = $(this).find('input').attr('pid');
		    $.get('<?php echo $this->createUrl('copyproduct/status',array('companyId'=>$this->companyId));?>/id/'+id);
		});
		$('.r-btn').on('switch-change', function () {
			var id = $(this).find('input').attr('pid');
		    $.get('<?php echo $this->createUrl('copyproduct/recommend',array('companyId'=>$this->companyId));?>/id/'+id);
		});
		$('#selectCategory').change(function(){
			var cid = $(this).val();
			location.href="<?php echo $this->createUrl('copyproduct/index' , array('companyId'=>$this->companyId));?>/cid/"+cid;
		});
	});


	$("#su").on('click',function() {
		
        //alert(11);
		var aa = document.getElementsByName("ids[]");
		//var aa = document.getElementsByName("ids[]");
        var codep=new Array();
        var codec=new Array();
        for (var i = 0; i < aa.length; i++) {
            if (aa[i].checked) {
                //var str = aa[i].getAttribute("chs_code");
                codep += aa[i].getAttribute("phs_code") +',';
            }
        }
        if(codep!=''){
        	codep = codep.substr(0,codep.length-1);//除去最后一个“，”
        }else{
       	 	alert("<?php echo yii::t('app','请选择要下发的菜品！！！');?>");
       		return false;
       	}
        
        for (var i = 0; i < aa.length; i++) {
            if (aa[i].checked) {
                //var str = aa[i].getAttribute("chs_code");
                codec += aa[i].getAttribute("chs_code") +',';
            }
        }
        if(codec!=''){
        	codec = codec.substr(0,codec.length-1);//除去最后一个“，”
        }else{
       	 	alert("<?php echo yii::t('app','请选择要下发的菜品！！！');?>");
       		return false;
       	}
     	//alert(str);
        
		if(window.confirm("确认进行此项操作?")){
			layer_index_printreportlist=layer.open({
	            type: 1,
	            shade: false,
	            title: false, //不显示标题
	            area: ['60%', '60%'],
	            content: $('#printRsultListdetail'),//$('#productInfo'), //捕获的元素
	            cancel: function(index){
	                layer.close(index);
	                layer_index_printreportlist=0;                                                                                                     
	            }
	        });
			$("#printall").on("click",function(){
	            //alert("暂无权限！！！");
	            var dpids =new Array();
	            var dpids="";
	            $('.checkdpids:checked').each(function(){
	                dpids += $(this).val()+',';
	                //alert(dpids);
	            });
	            if(dpids!=''){
	            	dpids = dpids.substr(0,dpids.length-1);//除去最后一个“，”
	            	//alert(dpids);
	            	$("#dpids").val(dpids);
	            	$("#chscode").val(codec);
	            	$("#phscode").val(codep);
	    	        $("#copyproduct-form").submit();
		            }else{
						alert("请选择店铺。。。");return;
			            }
			});
	        $("#closeall").on('click',function(){
		        //alert("123");
		        layer.closeAll();
		        layer_index_printerportlist = 0;
		        });
	    }else{
			return false;
			}
	});
	
	</script>	