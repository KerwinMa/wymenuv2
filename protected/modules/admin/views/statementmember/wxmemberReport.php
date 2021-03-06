    <script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js');?>"></script>
    <script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js');?>"></script>
    <script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/echarts.min.js');?>"></script>
    <!-- BEGIN PAGE -->
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
	<div id="main2" style="width: 600px;height: 400px;display: none;" onMouseOver="this.style.background='#fff'" onmouseout="this.style.background=''"></div>
	
	<!-- /.modal -->
	<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
	<!-- BEGIN PAGE HEADER-->
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','会员数据'),'url'=>$this->createUrl('statementmember/list' , array('companyId'=>$this->companyId,'type'=>2,))),array('word'=>yii::t('app','微信会员增加报表'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('statementmember/list' , array('companyId' => $this->companyId,'type'=>2,)))));?>

	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','微信会员增加报表');?></div>
				<div class="actions">
					<select id="text" class="btn yellow" >
					<option value="1" <?php if ($text==1){?> selected="selected" <?php }?> ><?php echo yii::t('app','年');?></option>
					<option value="2" <?php if ($text==2){?> selected="selected" <?php }?> ><?php echo yii::t('app','月');?></option>
					<option value="3" <?php if ($text==3){?> selected="selected" <?php }?> ><?php echo yii::t('app','日');?></option>
					</select>
					
					<select id="sex" class="btn green" >
					<option value="-1" <?php if ($sex==-1){?> selected="selected" <?php }?> ><?php echo yii::t('app','所有');?></option>
					<option value="0" <?php if ($sex==0){?> selected="selected" <?php }?> ><?php echo yii::t('app','未知');?></option>
					<option value="1" <?php if ($sex==1){?> selected="selected" <?php }?> ><?php echo yii::t('app','男');?></option>
					<option value="2" <?php if ($sex==2){?> selected="selected" <?php }?> ><?php echo yii::t('app','女');?></option>
					</select>
					<select id="sub" class="btn " >
					<option value="-1" <?php if ($sub==-1){?> selected="selected" <?php }?> ><?php echo yii::t('app','所有');?></option>
					<option value="0" <?php if ($sub==0){?> selected="selected" <?php }?> ><?php echo yii::t('app','关注');?></option>
					<option value="1" <?php if ($sub==1){?> selected="selected" <?php }?> ><?php echo yii::t('app','取消关注');?></option>
					</select>
				<div class="btn-group">
				
				
						   <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
								<input type="text" class="form-control" name="begtime" id="begin_time" placeholder="<?php echo yii::t('app','起始时间');?>" value="<?php echo $begin_time; ?>">  
								<span class="input-group-addon">~</span>
							    <input type="text" class="form-control" name="endtime" id="end_time" placeholder="<?php echo yii::t('app','终止时间');?>"  value="<?php echo $end_time;?>">           
						  </div>  
					</div>	
					
					<div class="btn-group">
							<button type="submit" id="btn_time_query" class="btn red" ><i class="fa fa-pencial"></i><?php echo yii::t('app','查 询');?></button>
							<button type="submit" id="excel"  class="btn green" ><i class="fa fa-pencial"></i><?php echo yii::t('app','导出Excel');?></button>				
					</div>			
				</div>
			 </div> 
			
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th><?php echo yii::t('app','时间');?></th>
								<th><?php echo yii::t('app','数量');?></th>                                                              
                                <th><?php echo yii::t('app','备注');?></th>
								
							</tr>
						</thead>
						<tbody>
							<?php if( $models) :?>
							<!--foreach-->
							<?php $a=1;?>
							<?php foreach ($models as $model):?>
								<tr class="odd gradeX">
								<td><?php if($text==1){echo $model['y_all'];}elseif($text==2){ echo $model['y_all'].-$model['m_all'];}else{echo $model['y_all'].-$model['m_all'].-$model['d_all'];}?></td>
								<td><?php echo $model->all_num;?></td>
								<td></td>
							<?php $a++;?>
							<?php endforeach;?>	
							<!-- end foreach-->
							<?php endif;?>
						</tbody>
					</table>
					
				</div>
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
	
	</div>
	<!-- END PAGE CONTENT-->
</div>
<!-- END PAGE -->

<script>
var xAxisname = <?php echo $xAxisname;?>;
var seriesnum = <?php echo $seriesnum;?>;

option = {
	    title: {
	        text: '微信会员增加曲线图'
	    },
	    tooltip : {
	        trigger: 'axis'
	    },
	    legend: {
	        data:['会员增长量']
	    },
	    toolbox: {
	        feature: {
	            saveAsImage: {}
	        }
	    },
	    grid: {
	        left: '3%',
	        right: '7%',
	        bottom: '3%',
	        containLabel: true
	    },
	    xAxis : [
	        {
	            type : 'category',
	            boundaryGap : false,
	            data : xAxisname
	            //data : ['周一','周二','周三','周四','周五','周六','周日']
	        }
	    ],
	    yAxis : [
	        {
	            type : 'value'
	        }
	    ],
	    series : [
	        {
	            name:'会员增长量',
	            type:'line',
	            stack: '总量',
	            areaStyle: {normal: {}},
	            data:seriesnum
	            //data:[120, 132, 101, 134, 90, 230, 210]
	        },
	    ]
	};
	if(xAxisname){
		var myChart = echarts.init(document.getElementById('main2'));
		myChart.setOption(option);
		layer_zhexiantu=layer.open({
		    type: 1,
		    //shift:5,
		    shade: [0.1,'#fff'],
		    move:'#main2',
		    moveOut:true,
		    offset:['50px','50px'],
		    shade: false,
		    title: false, //不显示标题
		    area: ['auto', 'auto'],
		    content: $('#main2'),//$('#productInfo'), //捕获的元素
		    cancel: function(index){
		        layer.close(index);
		        layer_zhexiantu=0;
		    }
		});
	}

	
		//var str=new array();						
		jQuery(document).ready(function(){
		    if (jQuery().datepicker) {
	            $('.date-picker').datepicker({
	            	format: 'yyyy-mm-dd',
	            	language: 'zh-CN',
	                rtl: App.isRTL(),
	                autoclose: true
	            });
	            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
	            
           }
		});
  
		   $('#btn_time_query').click(function time() {
			   var begin_time = $('#begin_time').val();
			   var end_time = $('#end_time').val();
			   var text = $('#text').val();
			   var sex = $('#sex').val();
			   var sub = $('#sub').val();
			   location.href="<?php echo $this->createUrl('statementmember/wxmemberReport' , array('companyId'=>$this->companyId ));?>/begin_time/"+begin_time+"/end_time/"+end_time+"/text/"+text+"/sex/"+sex+"/sub/"+sub;    
	        });
		   $('#cx').click(function cx(){  
			    var obj=$('.checkedCN');
			    var str=new Array();
					obj.each(function(){
						if($(this).attr("checked")=="checked")
						{
							
							str += $(this).val()+","
							
						}								
					});
				 str = str.substr(0,str.length-1);//除去最后一个“，”
				//alert(str);
					  var begin_time = $('#begin_time').val();
					   var end_time = $('#end_time').val();
					   var text = $('#text').val();
					   var setid = $('#setid').val();
					   //var cid = $(this).val();
					  
					 location.href="<?php echo $this->createUrl('statementmember/wxmemberReport' , array('companyId'=>$this->companyId ));?>/str/"+str+"/begin_time/"+begin_time+"/end_time/"+end_time +"/text/"+text+"/sex/"+sex+"/sub/"+sub;	  
					 //return a; 
			 });

			  $('#excel').click(function excel(){
				   var begin_time = $('#begin_time').val();
				   var end_time = $('#end_time').val();
				   var text = $('#text').val();
				   var sex = $('#sex').val();
				   var sub = $('#sub').val();
			       if(confirm('确认导出并且下载Excel文件吗？')){
				       //layer.msg('暂未开放！');
			    	   location.href="<?php echo $this->createUrl('statementmember/wxmemberExport' , array('companyId'=>$this->companyId ));?>/begin_time/"+begin_time+"/end_time/"+end_time +"/text/"+text+"/sex/"+sex+"/sub/"+sub;
			       }
			       else{
			    	   location.href="<?php echo $this->createUrl('statementmember/wxmemberReport' , array('companyId'=>$this->companyId ));?>/str/"+str+"/begin_time/"+begin_time+"/end_time/"+end_time +"/text/"+text+"/sex/"+sex+"/sub/"+sub;
			       }
			      
			   });
</script> 
