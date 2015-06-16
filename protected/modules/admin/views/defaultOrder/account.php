			
						<?php $form=$this->beginWidget('CActiveForm', array(
                                                        'id'=>'account-form',
                                                        'action' => $this->createUrl('defaultOrder/account',array('companyId'=>$this->companyId,'typeId'=>$typeId,'orderId'=>$order->lid)),
                                                        'enableAjaxValidation'=>true,
                                                        //'method'=>'POST',
                                                        'enableClientValidation'=>true,
                                                        'clientOptions'=>array(
                                                                'validateOnSubmit'=>false,
                                                        ),
                                                        'htmlOptions'=>array(
                                                                'class'=>'form-horizontal'
                                                        ),
                                                )); ?>
                                                <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                        <h4 class="modal-title"><?php if($payback=='1') echo yii::t('app','退款'); else echo yii::t('app','收银 && 结单'); ?><?php switch($order->order_status) {case 2:{echo yii::t('app','未支付');break;} case 3:{echo yii::t('app','已支付');break;} }?></h4>
                                                        <?php if($callid!='0'): ?>
                                                        <span style="color:red;" id="timecount">20</span><?php echo yii::t('app','...秒后自动完成结单，点击');?><input type="button" class="btn green" id="autopay_pause" value="<?php echo yii::t('app','此按钮');?>"><?php echo yii::t('app','停止结单！');?>
                                                        
                                                        <?php endif;?>
                                                </div>
                                                <div class="modal-body">
                                                        <div class="form-actions fluid">
                                                            <?php if($payback=='1'): ?>
                                                                <?php echo yii::t('app','整单应付金额：');?><?php echo number_format($order->should_total,2); ?><?php echo yii::t('app','，整单已付金额：');?><?php echo number_format($order->reality_total,2); ?>
                                                            <?php else: ?>
                                                                <?php echo yii::t('app','整单应付金额：');?><?php echo number_format($order->should_total,2); ?><?php echo yii::t('app','，整单已付金额：');?><?php echo number_format($order->reality_total,2); ?><?php echo yii::t('app','，本次应付：');?>
                                                            <?php endif;?>
                                                                <div class="form-group">
                                                                        <?php echo $form->label($orderpay, 'pay_amount',array('class' => 'col-md-4 control-label'));?>
                                                                        <div class="col-md-6">
                                                                                <?php echo $form->textField($orderpay, 'pay_amount' ,array('value'=>number_format($order->should_total-$order->reality_total,2),'class' => 'form-control','placeholder'=>$orderpay->getAttributeLabel('pay_amount')));?>
                                                                                <?php echo $form->error($orderpay, 'pay_amount' )?>
                                                                        </div>
                                                                </div>
                                                                <div class="form-group">
                                                                        <?php echo $form->label($orderpay, 'payment_method_id',array('class' => 'col-md-4 control-label'));?>
                                                                        <div class="col-md-6">
                                                                                <?php echo $form->dropDownList($orderpay, 'payment_method_id' ,$paymentMethods ,array('class' => 'form-control','placeholder'=>$orderpay->getAttributeLabel('payment_method_id')));?>
                                                                                <?php echo $form->error($orderpay, 'payment_method_id' )?>
                                                                        </div>
                                                                </div>
                                                                <div class="form-group">
                                                                        <?php echo $form->label($orderpay, 'remark',array('class' => 'col-md-4 control-label'));?>
                                                                        <div class="col-md-6">
                                                                                <?php echo $form->textArea($orderpay, 'remark' ,array('class' => 'form-control','placeholder'=>$orderpay->getAttributeLabel('remark')));?>
                                                                                <?php echo $form->error($orderpay, 'remark' )?>
                                                                        </div>
                                                                </div>
                                                                <?php echo $form->hiddenField($order , 'order_status' , array('id'=>'account_orderstatus'));?>
                                                                <?php echo $form->hiddenField($order , 'should_total' , array('id'=>'order_should_total'));?>

                                                                </div><<?php echo yii::t('app','!--订单明细中 退菜、勾挑、优惠、重新厨打///厨打、结单、整单优惠--');?>>
                                                </div>
                                                <div class="modal-footer">
                                                    <?php if($payback=='1'): ?>
                                                        <input type="button" class="btn green" id="payback-btn" value="<?php echo yii::t('app','退 款');?>">
                                                    <?php else: ?>
                                                        <input type="button" class="btn green" id="pay-btn" value="<?php echo yii::t('app','收 银');?>">
                                                        <input type="button" class="btn green" id="account-btn" value="<?php echo yii::t('app','结 单');?>">
                                                        <input type="button" class="btn green" id="cashin-btn" value="<?php echo yii::t('app','现金支付');?>">
                                                    <?php endif; ?>
                                                        <button type="button" data-dismiss="modal" class="btn default" id="btn-account-cancle"><?php echo yii::t('app','取 消');?></button>
                                                </div>

                                                <?php $this->endWidget(); ?>
					
			
			<script type="text/javascript">
                            var interval;
                            $('#payback-btn').on(event_clicktouchstart,function(){
                                 bootbox.confirm("<?php echo yii::t('app','你确定退款吗？');?>", function(result) {
                                        if(result){
                                                //$('#account-form').attr('action','<?php $this->createUrl('defaultOrder/account',array('companyId'=>$this->companyId,'typeId'=>$typeId,'op'=>'pay','orderId'=>$order->lid)) ?>');
                                                var tempamount=$('#OrderPay_pay_amount').val();
                                                $('#OrderPay_pay_amount').val(-1*tempamount);
                                                $('#account-form').submit();
                                        }
                                 });
                            });
                            $('#pay-btn').on(event_clicktouchstart,function(){
                                 bootbox.confirm("<?php echo yii::t('app','你确定只收银不结单吗？');?>", function(result) {
                                        if(result){
                                                //$('#account-form').attr('action','<?php $this->createUrl('defaultOrder/account',array('companyId'=>$this->companyId,'typeId'=>$typeId,'op'=>'pay','orderId'=>$order->lid)) ?>');
                                                $('#account_orderstatus').val('3');
                                                $('#account-form').submit();
                                        }
                                 });
                            });
                            $('#account-btn').on(event_clicktouchstart,function(){
                                 bootbox.confirm("<?php echo yii::t('app','确定结单吗？');?>", function(result) {
                                        if(result){
                                                //$('#account-form').attr('action','<?php $this->createUrl('defaultOrder/account',array('companyId'=>$this->companyId,'typeId'=>$typeId,'op'=>'account','orderId'=>$order->lid)) ?>');
                                                $('#account_orderstatus').val('4');
                                                $('#account-form').submit();
                                        }
                                 });
                            });
                            $('#cashin-btn').on(event_clicktouchstart,function(){
                                 bootbox.confirm("<?php echo yii::t('app','你确定切换到其他现金支付吗？');?>", function(result) {
                                        if(result){
                                                accountmanul();
                                        }
                                 });
                            });
                            $(document).ready(function() {
                                clearTimeout(interval);
                                var isauto="<?php if($callid=='0'){ echo '0';} else{ echo '1';} ?>";
                                //var isauto='1';
                                if(isauto=='1')
                                {
                                    interval = setInterval(autopaytimer,"2000");
                                }
                            });
                            $('#autopay_pause').on(event_clicktouchstart,function(){
                                //alert(11);
                                clearTimeout(interval);
                            });
                            function autopaytimer(){
                                //alert($("#timecount").html());
                                var curtime=parseInt($("#timecount").html());
                                curtime-=2;
                                if(curtime==0)
                                {
                                    clearTimeout(interval);
                                    //auto pay
                                    $('#account_orderstatus').val('4');
                                    $('#account-form').submit();
                                }else{
                                    $("#timecount").html(curtime);
                                }
                            }
                            $('#btn-account-cancle').on(event_clicktouchstart,function(){
                                 clearTimeout(interval);
                                 scanon=false;
                                 location.href="<?php echo $this->createUrl('defaultOrder/order',array('companyId'=>$this->companyId,'typeId'=>$typeId,'orderId'=>$order->lid,'syscallId'=>$callid));?>";
                            });
                        </script>