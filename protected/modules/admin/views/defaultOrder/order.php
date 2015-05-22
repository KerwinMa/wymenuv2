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
			<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->               
                        <div class="modal fade" id="portlet-config2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                        <div class="modal-content">
                                                <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                        <h4 class="modal-title">Modal title2</h4>
                                                </div>
                                                <div class="modal-body">
                                                        Widget settings form goes here2
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
                        <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->               
                        <div class="modal fade" id="portlet-print-loading" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                                <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                        <h4 class="modal-title">Modal title2</h4>
                                                </div>
                                                <div class="modal-body">
                                                        Widget settings form goes here2
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
                        <!-- BEGIN PAGE CONTENT-->
			<div class="row">
                                <div class="col-md-4">
                                    <h3 class="page-title"><?php switch($model->order_status) {case 1:{echo '未下单';break;} case 2:{echo '下单未支付';break;} case 3:{echo '已支付'.$model->reality_total;break;} }?></h3>                                    
                                </div>
                                <div class="col-md-8">
                                    <h4>
                                       下单时间：<?php echo $model->create_at;?> 
                                       &nbsp;&nbsp;&nbsp;&nbsp; 应付金额（元）：<?php echo number_format($total['total'], 2);?>
                                       &nbsp;&nbsp;&nbsp;&nbsp; 实付金额（元）：<?php echo $model->reality_total;?>
                                    </h4>    
                                </div>
				<div class="col-md-12">
					<div class="portlet box blue">
						<div class="portlet-title">
							<div class="caption"><i class="fa fa-reorder"></i>
                                                            <?php echo $total['remark'] ;?>
                                                        </div>
                                                        <div class="col-md-3 ">
                                                                <input id="callbarscanid" type="text" class="form-control" placeholder='<?php if($syscallId!='0') echo "扫描呼叫器条码快速收银、结算"; else echo "扫描呼叫器条码快速下单、厨打"; ?>'>
                                                        </div>
                                                        <div class="actions">
                                                            <?php if($model->order_status=='3' || $model->order_status=='4'): ?>
                                                                <a class="btn purple" id="btn_payback"><i class="fa fa-adjust"></i> 退款</a>
                                                            <?php endif;?>
                                                            <?php if($model->order_status!='4'): ?>
                                                            <a class="btn purple" id="btn_account"><i class="fa fa-pencil"></i> 结单&收银</a>
                                                            <a id="kitchen-btn" class="btn purple"><i class="fa fa-cogs"></i> 下单&厨打</a>
                                                            <a id="print-btn" class="btn purple"><i class="fa fa-print"></i> 打印清单</a>
                                                            <a id="alltaste-btn" class="btn purple"><i class="fa fa-pencil"></i> 全单口味</a>
                                                            <?php endif; ?>
                                                            <a href="<?php echo $this->createUrl('default/index' , array('companyId' => $model->dpid,'typeId'=>$typeId));?>" class="btn red"><i class="fa fa-times"></i> 返回</a>
                                                        </div>
						</div>
						<div class="portlet-body form">
							<!-- BEGIN FORM-->
							<?php echo $this->renderPartial('_form', array('model'=>$model,'orderProducts' => $orderProducts,'productTotal' => $productTotal,'total' => $total,'typeId'=>$typeId,'allOrderTastes'=>$allOrderTastes,'allOrderProductTastes'=>$allOrderProductTastes)); ?>
							<!-- END FORM--> 
						</div>
					</div>
				</div>
			</div>
			<!-- END PAGE CONTENT-->    
		</div>
		<!-- END PAGE -->  
                
                    <script type="text/javascript">
                        var syscallid='<?php echo $syscallId; ?>';
                        var scanon=false;
                        $(document).ready(function(){
                                $('body').addClass('page-sidebar-closed');                                
                        });
                        
                        function openaccount(payback){
                            var loadurl='<?php echo $this->createUrl('defaultOrder/account',array('companyId'=>$this->companyId,'typeId'=>$typeId,'orderId'=>$model->lid,'total'=>$total['total']));?>';
                            if(payback==1)
                            {
                                loadurl=loadurl+'/payback/1'
                            }
                            var callid= $('#callbarscanid').val();
                            if(callid>"Ca000" && callid<"Ca999")
                            {
                                loadurl=loadurl+'/callId/'+callid;
                            }
                            //alert(loadurl);
                            var $modalconfig = $('#portlet-config');
                                $modalconfig.find('.modal-content')
                                        .load(loadurl
                                            , ''
                                            , function(){
                                                $modalconfig.modal();
                                });
                        }
                        
                        $('#btn_account').click(function(){
                                 openaccount('0');
                        });
                        $('#btn_payback').click(function(){
                                 openaccount('1');
                        });
                        /*
                        $('#btn_pay').click(function(){
                                var $modalconfig = $('#portlet-config');
                                $modalconfig.find('.modal-content').load('<?php echo $this->createUrl('defaultOrder/pay',array('companyId'=>$this->companyId,'typeId'=>$typeId,'orderId'=>$model->lid,'total'=>$total['total']));?>', '', function(){
                                            $modalconfig.modal();
                                          }); 
                        });
                        */
                        $('#print-btn').click(function(){
                            if (typeof Androidwymenuprinter == "undefined") {
                                alert("无法获取PAD设备信息，请在PAD中运行该程序！");
                                return false;
                            }
                            var padinfo=Androidwymenuprinter.getPadInfo();
                            var pad_id=padinfo.substr(10,10);
                            //var pad_id="0000000001";
                            var $modal=$('#portlet-config');
                            $modal.find('.modal-content').load('<?php echo $this->createUrl('defaultOrder/printList',array('companyId'=>$this->companyId));?>/orderId/'+"<?php echo $model->lid; ?>"+'/typeId/'+"<?php echo $typeId; ?>"+'/padId/'+pad_id
                                    ,'', function(){
                                                $modal.modal();
                                        });
                            /*
                            $.get('<?php echo $this->createUrl('defaultOrder/printList',array('companyId'=>$this->companyId,'orderId'=>$model->lid));?>/padId/'+pad_id,function(data){
                                    if(data.status) {
                                        if(data.type='local')
                                        {
                                            if(Androidwymenuprinter.printJob(company_id,data.jobid))
                                            {
                                                alert("打印成功");
                                            }
                                            else
                                            {
                                                alert("PAD打印失败！，请确认打印机连接好后再试！");                                                                        
                                            }
                                        }else{
                                            var $modal=$('#portlet-config');
                                            $modal.find('.modal-content').load('<?php echo $this->createUrl('defaultOrder/printListNet',array('companyId'=>$this->companyId));?>/orderId/'+"<?php echo $model->lid; ?>"+'/typeId/'+"<?php echo $typeId; ?>"
                                                    ,'', function(){
                                                                $modal.modal();
                                                        });
                                        }
                                    } else {
                                            alert(data.msg);
                                    }
                            },'json');*/
                        });
                        
                        function printKiten(callid){
                            var $modalloading = $('#portlet-print-loading');                                
                           $modalloading.find('.modal-content').load('<?php echo $this->createUrl('defaultOrder/printKitchen',array('companyId'=>$this->companyId,'typeId'=>$typeId,'orderId'=>$model->lid));?>/callId/'+callid, '', function(){
                                $modalloading.modal();
                            });
                        }
                        
                        $('#kitchen-btn').click(function(){
                            var statu = confirm("下单，并厨打，确定吗？");
                                if(!statu){
                                    return false;
                                }
                            printKiten('0');                          
                        });
                        
                        $('#alltaste-btn').click(function(){
                                var $modalconfig = $('#portlet-config');
                                $modalconfig.find('.modal-content').load('<?php echo $this->createUrl('defaultOrder/productTaste',array('companyId'=>$this->companyId,'typeId'=>$typeId,'lid'=>$model->lid,'isall'=>'1'));?>', '', function(){
                                            $modalconfig.modal();
                                          }); 
                        });
                        
                        $('#callbarscanid').keyup(function(){
                            if($(this).val().length==5 && scanon==false)
                            {
                                scanon=true;
                                var callid=$(this).val();
                                //alert(callid);
                                if(callid>"Ca000" && callid<"Ca999")
                                {
                                    
                                    if(syscallid!='0')
                                    {
                                        if(syscallid==callid)
                                        {
                                            openaccount('0');
                                        }else{
                                            alert("请再次扫描呼叫器："+syscallid+"，系统自动结单！");
                                            $('#callbarscanid').val("");
                                            scanon=false;
                                            return false;
                                        }
                                    }else{                                        
                                        printKiten(callid);
                                    }
                                }else{
                                    alert("呼叫器编码不正确！");
                                    $('#callbarscanid').val("");
                                    scanon=false;
                                    return false;
                                }
                            }
                        });
                        
                        $(document).ready(function () {
                            //$('#barscanid').val("222");
                            $('#callbarscanid').focus();
                        });
                    </script>