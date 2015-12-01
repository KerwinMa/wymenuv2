							<?php $form=$this->beginWidget('CActiveForm', array(
									'id' => 'privatepromotion-form',
									'errorMessageCssClass' => 'help-block',
									'htmlOptions' => array(
										'class' => 'form-horizontal',
										'enctype' => 'multipart/form-data'
									),
							)); ?>
								<style>
								#category_container select {display:block;float:left;margin-right:3px;max-width:200px;overflow:hidden;}
								</style>
								<div class="form-body">
														
									<div class="form-group ">
									
										<?php echo $form->label($model, yii::t('app','标题'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'promotion_title',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('promotion_title')));?>
											<?php echo $form->error($model, 'promotion_title' )?>
										</div>
									</div><!-- 活动标题 -->
									<div class="form-group">
										<?php if($model->hasErrors('main_picture')) echo 'has-error';?>
										<?php echo $form->label($model,'main_picture',array('class'=>'control-label col-md-3')); ?>
										<div class="col-md-9">
										<?php
										$this->widget('application.extensions.swfupload.SWFUpload',array(
											'callbackJS'=>'swfupload_callback',
											'fileTypes'=> '*.jpg',
											'buttonText'=> yii::t('app','上传产品图片'),
											'companyId' => $model->dpid,
											'imgUrlList' => array($model->main_picture),
										));
										?>
										<?php echo $form->hiddenField($model,'main_picture'); ?>
										<?php echo $form->error($model,'main_picture'); ?>
										</div>
									</div><!-- 主图片 -->
						
									<div class="form-group" >
									<?php if($model->hasErrors('promotion_abstract')) echo 'has-error';?>
										<?php echo $form->label($model, yii::t('app','摘要'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'promotion_abstract',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('promotion_abstract')));?>
											<?php echo $form->error($model, 'promotion_abstract' )?>
										</div>
									</div><!-- 活动摘要 -->
									<div class="form-group">
										<?php echo $form->label($model, yii::t('app','类型'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'promotion_type', array('0' => yii::t('app','独享') , '1' => yii::t('app','共享')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('promotion_type')));?>
											<?php echo $form->error($model, 'promotion_type' )?>
										</div>
									</div><!-- 活动类型 -->
									<div class="form-group" >
									<?php if($model->hasErrors('change_point')) echo 'has-error';?>
										<?php echo $form->label($model, yii::t('app','兑换该特价活动所需的积分'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'change_point',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('change_point')));?>
											<?php echo $form->error($model, 'change_point' )?>
										</div>
									</div><!-- 需要的积分 -->
                                    <div class="form-group">
										<?php echo $form->label($model, yii::t('app','是否可用代金券'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'can_cupon', array('0' => yii::t('app','可以使用代金券') , '1' => yii::t('app','不能使用代金券')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('can_cupon')));?>
											<?php echo $form->error($model, 'can_cupon' )?>
										</div>
									</div><!-- 是否可用代金券 -->
									<div class="form-group">
										<?php echo $form->label($model, yii::t('app','活动针对对象'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'to_group', array( '1' => yii::t('app','关注微信人群'), '2' => yii::t('app','会员等级') , '3' => yii::t('app','会员个人')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('to_group')));?>
											<?php echo $form->error($model, 'to_group' )?>
										</div>
									</div>
                                    <!-- <div class="form-group">
										<?php echo $form->label($model, yii::t('app','活动针对对象'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'to_group', array('1' => yii::t('app','关注微信的人群') , '2' => yii::t('app','会员等级') ,'3' => yii::t('app','会员个人')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('to_group')));?>
											<?php echo $form->error($model, 'to_group' )?>
										</div>
									</div><!-- 活动实施对象 --
									 -->
									<?php if($model->to_group=="2"):{?>
									<div id="yincang" style="display: ;" class="form-group ">
										<label class="col-md-3 control-label"><?php echo yii::t('app','会员等级');?></label>
										<div class="col-md-4" style="border:1px solid red;">
										<?php if($brdulvs) :{?>
										<?php $i=1;?>
										<?php foreach ($brdulvs as $brdulv):?>
										
											<tr class="odd gradeX">
												<td><input type="checkbox" id="<?php echo $i;?>" class="checkboxes " <?php if(!empty($userlvs)){foreach ($userlvs as $userlv){if($userlv['brand_user_lid'] == $brdulv->lid) echo 'checked' ;}}else echo "123";?>   value="<?php echo $brdulv->lid;?>" name="chk" /></td>
												<td><?php echo $i,$brdulv->level_name; ?></td>
												
											</tr>
										<?php $i=$i+1;?>
										<?php endforeach;?>
										<?php }endif;?>
										</div>
										<input type="hidden" id="hidden1" name="hidden1" value="" />
									</div>
								<?php }elseif($model->to_group!="2"):{?>
										<div id="yincang" style="display:none ;" class="form-group ">
										<label class="col-md-3 control-label"><?php echo yii::t('app','会员等级');?></label>
										<div class="col-md-4" style="border:1px solid red;">
										<?php if($brdulvs) :{?>
										<?php $i=1;?>
										<?php foreach ($brdulvs as $brdulv):?>
										
											<tr class="odd gradeX">
												<td><input type="checkbox" id="<?php echo $i;?>" class="checkboxes" check="" value="<?php echo $brdulv->lid;?>" name="chk" /></td>
												<td><?php echo $i,$brdulv->level_name; ?></td>
												
											</tr>
										<?php $i=$i+1;?>
										<?php endforeach;?>
										<?php }endif;?>
										</div>
										<input type="hidden" id="hidden1" name="hidden1" value="" />
									</div>
								<?php }endif;?>
									<div class="form-group">
										<?php echo $form->label($model, yii::t('app','是否生效'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'is_available', array('0' => yii::t('app','生效') , '1' => yii::t('app','不生效')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('is_available')));?>
											<?php echo $form->error($model, 'is_available' )?>
										</div>
									</div><!-- 活动是否生效 -->
									<div class="form-group" >
									<?php if($model->hasErrors('order_num')) echo 'has-error';?>
										<?php echo $form->label($model, yii::t('app','单个订单的数量限制'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'order_num',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('order_num')));?>
											<?php echo $form->error($model, 'order_num' )?>
										</div>
									</div><!-- 需要的积分 -->
                                    <div class="form-group">
											<label class="control-label col-md-3"><?php echo yii::t('app','活动有效期限');?></label>
											<div class="col-md-4">
												<!-- <div class="input-group date form_datetime" data-date="2012-12-21T15:25:00Z">                                       
													<input type="text" size="16" readonly class="form-control">
													<span class="input-group-btn">
													<button class="btn default date-reset" type="button"><i class="fa fa-times"></i></button>
													</span>
													<span class="input-group-btn">
													<button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
													</span>
												</div> -->
												 <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
													 <?php echo $form->textField($model,'begin_time',array('class' => 'form-control ui_timepicker','style'=>'width:160px;','placeholder'=>$model->getAttributeLabel('begin_time'))); ?>
													 <span class="input-group-addon"> ~ </span>
													 <?php echo $form->textField($model,'end_time',array('class'=>'form-control ui_timepicker','style'=>'width:160px;','placeholder'=>$model->getAttributeLabel('end_time'))); ?>
												</div> 
												<!-- /input-group -->
												<?php echo $form->error($model,'begin_time'); ?>
												<?php echo $form->error($model,'end_time'); ?>
											</div>
										</div>
									<div class="form-group">
										<?php echo $form->label($model, yii::t('app','图文说明'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-8">
											<?php echo $form->textArea($model, 'promotion_memo' , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('promotion_memo')));?>
											<?php echo $form->error($model, 'promotion_memo' )?>
										</div>
									</div><!-- 图文说明 -->
									<div class="form-actions fluid">
										<div class="col-md-offset-3 col-md-9">
											<button type="button" id="su" class="btn blue"><?php echo yii::t('app','确定');?></button>
											<a href="<?php echo $this->createUrl('privatepromotion/index' , array('companyId' => $model->dpid));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
										</div>
									</div>
							<?php $this->endWidget(); ?>
							<?php $this->widget('ext.kindeditor.KindEditorWidget',array(
								'id'=>'PrivatePromotion_promotion_memo',	//Textarea id
								'language'=>'zh_CN',
								// Additional Parameters (Check http://www.kindsoft.net/docs/option.html)
								'items' => array(
									'height'=>'200px',
									'width'=>'100%',
									'themeType'=>'simple',
									'resizeType'=>1,
									'allowImageUpload'=>true,
									'allowFileManager'=>true,
								),
							)); ?>
							
	<script type="text/javascript">    
	   
	 $(document).ready(function(){ 
	 $('#PrivatePromotion_to_group').change(function(){ 
	 //alert($(this).children('option:selected').val()); 
	 var p1=$(this).children('option:selected').val();//这就是selected的值 
		//alert(p1);
		 if(p1=="2"){
			 $("#yincang").show();
		 }else{
			$("#yincang").hide();
			 }
	
	 }) 
	 });

     $("#su").on('click',function() {
         //alert(11);
         var p1 = $('#PrivatePromotion_to_group').children('option:selected').val();
         var begintime = $('#PrivatePromotion_begin_time').val();
         var endtime = $('#PrivatePromotion_end_time').val();
         var aa = document.getElementsByName("chk");
         var str=new Array();
         //alert(p1);
         //var ss = "";
       // if(aa.checked){
         //alert(begintime);
         //alert(endtime);
         if(endtime<=begintime){
        	 alert("<?php echo yii::t('app','活动结束时间应该大于开始时间!!!');?>");
        	 return false;
         }
         if(p1=='2'){
         for (var i = 0; i < aa.length; i++) {
             if (aa[i].checked) {
                 str += aa[i].value +',';
             }
         }
         if(str!=''){
         str = str.substr(0,str.length-1);//除去最后一个“，”
         }else{
        	 alert("<?php echo yii::t('app','请选择相应的会员等级！！！');?>");
        	 return false;
        	 }
         }
         //else{
        //	 alert("<?php echo yii::t('app','请选择相应的会员等级！！！');?>");
          //   }
         //alert(str);
      //  }else{
        // alert(str);}
         $("#hidden1").val(str);
         $("#privatepromotion-form").submit();
     });
 
	
		function swfupload_callback(name,path,oldname)  {
			//alert(6789);
			$("#PrivatePromotion_main_picture").val(name);
			$("#thumbnails_1").html("<img src='"+name+"?"+(new Date()).getTime()+"' />"); 
		}
	</script>