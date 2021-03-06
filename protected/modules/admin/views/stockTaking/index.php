<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-1.7.1.min.js');?>
<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/keyboard1.js');?>

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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','库存管理'),'url'=>$this->createUrl('bom/bom' , array('companyId'=>$this->companyId,'type'=>2,))),array('word'=>yii::t('app','盘点库存'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('bom/bom' , array('companyId' => $this->companyId,'type' =>'2',)))));?>
	
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	
	<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','盘点库存');?></div>
					
					<div class="actions">
						<div class="btn-group">
							<?php echo CHtml::dropDownList('selectCategory', $categoryId, $categories , array('class'=>'form-control'));?>
						</div>
						<div class="btn-group">
							<button type="submit" id="excel"  class="btn green" ><i class="fa fa-pencial"></i><?php echo yii::t('app','导出Excel');?></button>				
						</div>
						<div class="btn-group">
							<button type="button" id="save"  class="btn yellow" ><i class="fa fa-pencial"></i><?php echo yii::t('app','暂时保存');?></button>				
						</div>
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
								<th style="width:16%"><?php echo yii::t('app','品项编号');?></th>
								<th ><?php echo yii::t('app','品项名称');?></th>
								<th ><?php echo yii::t('app','类型');?></th>
								<th><?php echo yii::t('app','库存单位');?></th>
								<!-- <th><?php echo yii::t('app','实时库存');?></th> -->
								<th><?php echo yii::t('app','盘点库存（大单位、小单位、转换比例）');?>
								<!--<th><php echo yii::t('app','库存成本');?></th>-->
								<th>&nbsp;</th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody>
                                                 
						<?php if($models) :?>
                                                 
						<?php foreach ($models as $model):?>

							<tr class="odd gradeX pd-product" vid="<?php echo $model['lid'];?>" orinin-num="<?php  echo $model['stock_all'];?>" sales-name="<?php echo $model['sales_name'];?>">
								<td><input type="checkbox" class="checkboxes" value="<?php echo $model['lid'];?>" name="ids[]" /></td>
								<td><?php echo $model['material_identifier'];?></td>
								<td ><?php echo $model['material_name'];?></td>
								<td><?php if(!empty($model['category_name'])) echo $model['category_name'];?></td>
								<td ><?php echo $model['unit_name'];?></td>
								                               
                                <td><input style="display: none;" type="text" class="checkboxes" id="originalnum<?php echo $model['lid'];?>" value="<?php  echo $model['stock_all'];?>" name="idss[]" />
                                
								<input class="kucundiv-left" type="text"   style="width:100px;" name="leftnum<?php echo $model['lid'];?>" id="idleftnum0<?php echo $model['lid'];?>" value="<?php echo $model['inventory_stock'];?>" stockid="0" onfocus=" if (value =='0.00'){value = '0.00'}" onblur="if (value ==''){value=''}"  onkeyup="if(isNaN(value))execCommand('undo')" onafterpaste="if(isNaN(value))execCommand('undo')" placeholder="库存大单位">
								<span><?php echo $model['unit_name'];?></span>
								<input class="kucundiv-right" type="text"   style="width:100px;" name="rightnum<?php echo $model['lid'];?>" id="idrightnum0<?php echo $model['lid'];?>" value="<?php echo $model['inventory_sales'];?>" stockid="0" onfocus=" if (value =='0.00'){value = '0.00'}" onblur="if (value ==''){value=''}"  onkeyup="if(isNaN(value))execCommand('undo')" onafterpaste="if(isNaN(value))execCommand('undo')" placeholder="零售小单位">
								<?php echo $model['sales_name'];?>
								<input type="button" disabled type="text" class="checkboxes kucundiv-ratio" id="mratio<?php echo $model['lid'];?>" value="<?php  echo $this->getRatio($model['mu_lid'],$model['ms_lid']);?>"/>
								</td>
								<td class="center">
								<?php if(Yii::app()->user->role <5):?>
								<?php echo $model['stock_all'];?>
								<?php endif;?>
								 </td>
								<td class="center">
								
								</td>
							</tr>
						<?php endforeach;?>
						<?php endif;?>
						</tbody>
					</table>
                     </div>
					<div class="form-actions fluid">
						<div class="col-md-offset-8 col-md-4">
							盘点类型:
							<select id="sttype" class="btn yellow" >
								<option value="0" <?php if ($sttype==0){?> selected="selected" <?php }?> ><?php echo yii::t('app','请选择');?></option>
								<option value="1" <?php if ($sttype==1){?> selected="selected" <?php }?> ><?php echo yii::t('app','日盘');?></option>
								<option value="2" <?php if ($sttype==2){?> selected="selected" <?php }?> ><?php echo yii::t('app','周盘');?></option>
								<option value="3" <?php if ($sttype==3){?> selected="selected" <?php }?> ><?php echo yii::t('app','月盘');?></option>
							</select>   
							<button type="button" class="btn green" id="stocktaking" clk='0'>一键盘点</button>                              
						</div>
					</div>			
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
			location.href="<?php echo $this->createUrl('stockTaking/index' , array('companyId'=>$this->companyId));?>/cid/"+cid;
		});   
	})             
	$("#stocktaking").on("click",function(){
		var _this = $(this);
		var sttype = $('#sttype').val();
        var arr=document.getElementsByName("idss[]");
        var optid;
        var optval = '';
        
        var clk = $(this).attr('clk');
        if(clk=='1'){
			layer.msg('请勿多次操作！');
			return false;
        }
        if(sttype=='0'){
        	layer.msg('请选择盘点类型,然后再进行一键盘点！');
			return false; 
        }
		var sttypestr = '';
		if(sttype=='1'){
			sttypestr = '日盘';
		}else if(sttype=='2'){
			sttypestr = '周盘';
		}else{
			sttypestr = '月盘';
		}
        
        if(confirm('该次盘点为"'+sttypestr+'",请确认后,再进行操作...')){
            $(this).attr('clk',1);
            $('.pd-product').each(function(){
            	var vid = $(this).attr('vid');
            	var originalnum = $(this).attr('orinin-num'); 
            	var materialcode = $(this).attr('identifier'); 
            	var salesname = $(this).attr('sales-name');
                var nownumd = $(this).find('.kucundiv-left').val(); 
                var nownumx = $(this).find('.kucundiv-right').val(); 
                var ratio = $(this).find('.kucundiv-ratio').val();

                if(nownumd ==''){
    				nownumd = '0';
    			}
    			if(nownumx ==''){
    				nownumx = '0';
    			}
    			if(nownumd == '0'&&nownumx == '0'){
    				a = 0;
    			}else{
    				a = 1;
    			}
                if(ratio != ''&&a==1){
                    optval = vid +','+ nownumd +','+ nownumx +','+ ratio +','+ originalnum +','+salesname+';'+ optval;
                } 
            });
            if(optval.length >0){
            	optval = optval.substr(0,optval.length-1);//除去最后一个“，”
            }else{
                alert('请至少盘点一项');
                layer.closeAll('loading');
                $(this).attr('clk',0);
                return false;
             }
            layer.load(2);
    		var categoryId = '<?php echo $categoryId;?>';
            $.ajax({
                type:'POST',
    			url:"<?php echo $this->createUrl('stockTaking/allStore',array('companyId'=>$this->companyId));?>",
    			data:{cid:categoryId,sttype:sttype,optval:optval},
                dataType:'json',
    			success:function(msg){
    				layer.closeAll('loading');
    	            if(msg.status=="success")
    	            {            
    		            if(msg.msg !=''){
    		            	alert("下列产品尚未入库，无非进行盘点【"+msg.msg+"】；其他产品盘点正常。点击确认跳转至盘点日志查看");
    		            	
    		            }else{
    			            alert("盘点成功！");
    			        }  
    		            location.href="<?php echo $this->createUrl('stocktakinglog/detailindex' , array('companyId'=>$this->companyId,));?>/id/"+msg.logid
    	            }else{
    		            alert("盘点失败:"+msg.msg);
    		            _this.attr('clk',0);
    	            }
    			},
                error:function(){
                	_this.attr('clk',0);
    				alert("盘点失败,网络异常");  
    				layer.closeAll('loading');                              
    			},
    		});
        }else{
             layer.closeAll('loading');
             return false;
       }
	});

	$("#save").on("click",function(){
		var loading = layer.load();
		//alert("123");
		var sttype = $('#sttype').val();
        var arr=document.getElementsByName("idss[]");
        var optid;
        var optval = '';
        
        for(var i=0;i<arr.length;i++)
        {
            var vid = $(arr[i]).attr("id").substr(11,10);  
            var nownumd = $("#idleftnum0"+vid).val(); 
            var nownumx = $("#idrightnum0"+vid).val();
            var stockid = $("#idleftnum0"+vid).attr('stockid');
            var ratio = $("#mratio"+vid).val();

            if(nownumd ==''){
				nownumd = '0';
			}
			if(nownumx ==''){
				nownumx = '0';
			}
			//var a = 1;
			if(nownumd == '0'&&nownumx == '0'){
				a = 0;
			}else{
				a = 1;
			}
			
            if(ratio != ''&&a==1){
                optval = vid +','+ nownumd +','+ nownumx +','+ ratio +','+ stockid +';'+ optval;
            } 
        }
        if(optval.length >0){
        	optval = optval.substr(0,optval.length-1);//除去最后一个“，”
        	//alert(optval);
        }else{
            alert('请至少盘点一项');
            layer.closeAll('loading');
            return false;
            }
        //alert(optval);return false;
		var categoryId = '<?php echo $categoryId;?>';
        $.ajax({
            type:'GET',
			url:"<?php echo $this->createUrl('stockTaking/savestore',array('companyId'=>$this->companyId,));?>/optval/"+optval+"/cid/"+categoryId+"/sttype/"+sttype,
			async: false,
			//data:"companyId="+company_id+'&padId='+pad_id,
            cache:false,
            dataType:'json',
			success:function(msg){
	            if(msg.status=="success")
	            {            
			        layer.msg("保存成功！");
		            location.reload();
		            layer.closeAll('loading');
	            }else{
		            alert("<?php echo yii::t('app','失败'); ?>"+"1");
		            layer.closeAll('loading');
	            }
			},
            error:function(){
				alert("<?php echo yii::t('app','失败'); ?>"+"2");  
				layer.closeAll('loading');                              
			},
		});
        
		});
	$('#excel').click(function excel(){
		var cid = '<?php echo $categoryId;?>';
       if(confirm('确认导出并且下载Excel文件吗？')){
	       //layer.msg('暂未开放！');
    	   location.href="<?php echo $this->createUrl('stockTaking/stockExport' , array('companyId'=>$this->companyId ));?>/cid/"+cid;
       }   
	});  
 
    
	</script>	