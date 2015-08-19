			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
					<div class="portlet box blue">
						<div class="portlet-title">
							<div class="caption"><i class="fa fa-reorder"></i><?php echo yii::t('app','会员充值');?></div>
						</div>
						<div class="portlet-body form">
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-1">
									</div>
									<div class="col-md-10" style="padding:0;margin-top:20px;">
										<div class="input-group">
											<input type="text" class="form-control" placeholder="请输入卡号、手机、会员姓名" value="" />
											<span class="input-group-btn">
											<button class="btn blue getMember" type="button"> 搜 索 </button>
											</span>
									     </div>
							        </div>
							        <div class="col-md-1">
									</div>
								</div>
								<div class="row">
									<div class="col-md-7" style="padding:0;margin-top:10px;">
										<div class="table-responsive" style="font-size:20px;">
											<table class="table table-hover">
												<tbody>
													<tr>
														<td width="10%">卡号:</td>
														<td width="50%" id="selfcode"></td>
													</tr>
													<tr>
														<td>余额:</td>
														<td id="all-money"></td>
													</tr>
													<tr>
														<td>姓名:</td>
														<td id="name"></td>
													</tr>
													<tr>
														<td>手机:</td>
														<td id="mobile"></td>
													</tr>
													<tr>
														<td>邮箱:</td>
														<td id="email"></td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
									<div class="col-md-5" style="padding:0;margin-top:10px;">
									<!-- BEGIN FORM-->
									<?php $form=$this->beginWidget('CActiveForm', array(
											'id' => 'taste-form',
											'action'=>$this->createUrl('member/charge',array('companyId'=>$this->companyId)),
											'errorMessageCssClass' => 'help-block',
											'htmlOptions' => array(
												'class' => 'form-horizontal',
												'enctype' => 'multipart/form-data'
											),
									)); ?>
										<div class="form-body">
											<div class="form-group">
												<?php echo $form->label($model, 'reality_money',array('class' => 'col-md-6 control-label'));?>
												<div class="col-md-6">
													<?php echo $form->textField($model, 'reality_money',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('reality_money')));?>
													<?php echo $form->error($model, 'reality_money' )?>
												</div>
											</div>
											<div class="form-group">
												<?php echo $form->label($model, 'give_money',array('class' => 'col-md-6 control-label'));?>
												<div class="col-md-6">
													<?php echo $form->textField($model, 'give_money',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('give_money')));?>
													<?php echo $form->error($model, 'give_money' )?>
												</div>
											</div>
											<div class="col-md-offset-3 col-md-9">
													<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
													<a href="<?php echo $this->createUrl('member/index' , array('companyId' => $model->dpid));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
												</div>
											</div>
											<input type="hidden" name="rfid" value="" />
											<input type="hidden" name="MemberRecharge[member_card_id]" value="" />
									<?php $this->endWidget(); ?>
									<!-- END FORM--> 
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- END PAGE CONTENT-->    
		<script type="text/javascript">
		jQuery(document).ready(function(){
			$('.getMember').click(function(){
				var card = $(this).parents('.input-group').find('input').val();
				$.get('<?php echo $this->createUrl('/admin/member/getMember', array('companyId' => $model->dpid));?>/card/'+card,function(data){
					if(data.status){
						$('input[name="rfid"]').val(data.msg.rfid);
						$('input[name="MemberRecharge[member_card_id]"]').val(data.msg.selfcode);
						$('#selfcode').html(data.msg.selfcode)
						$('#all-money').html(data.msg.all_money)
						$('#name').html(data.msg.name)
						$('#mobile').html(data.msg.mobile)
						$('#email').html(data.msg.email)
					}else{
						alert(data.msg);
					}
				},'json') ;
			});
		});
		</script> 