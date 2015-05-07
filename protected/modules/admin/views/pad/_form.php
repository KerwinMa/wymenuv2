							<?php $form=$this->beginWidget('CActiveForm', array(
									'id' => 'printer-form',
									'errorMessageCssClass' => 'help-block',
									'htmlOptions' => array(
										'class' => 'form-horizontal',
										'enctype' => 'multipart/form-data'
									),
							)); ?>
								<div class="form-body">
								<?php if(!$model->dpid):?>
									<div class="form-group">
										<?php echo $form->label($model, 'dpid',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'dpid', array('0' => '-- 请选择 --') +Helper::genCompanyOptions() ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('dpid')));?>
											<?php echo $form->error($model, 'dpid' )?>
										</div>
									</div>
								<?php endif;?>
                                                                        <div class="form-group">
                                                                                <?php echo $form->label($model, 'name',array('class' => 'col-md-3 control-label'));?>
                                                                                <div class="col-md-4">
                                                                                        <?php echo $form->textField($model, 'name',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('name')));?>
                                                                                        <?php echo $form->error($model, 'name' )?>
                                                                                </div>
                                                                        </div>
                                                                        <div class="form-group">
										<?php echo $form->label($model, 'printer_id',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'printer_id', array('0' => '-- 请选择 --') +$printers ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('printer_id')));?>
											<?php echo $form->error($model, 'printer_id' )?>
										</div>
									</div>
                                                                        <div class="form-group">
										<?php echo $form->label($model, 'server_address',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'server_address',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('server_address')));?>
											<?php echo $form->error($model, 'server_address' )?>
                                                                                    是打印服务器填写：IP和端口号，如：192.168.100.100:3030，<br>非打印服务器请勿填写，<br>一个店铺只有一个打印服务器。
										</div>
									</div>
                                                                        <div class="form-group">
										<?php echo $form->label($model, 'pad_type',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'pad_type', array('0' => '收银台' , '1' => '点单PAD','2'=>'开台PAD') , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('pad_type')));?>
											<?php echo $form->error($model, 'pad_type' )?>
										</div>
									</div>
									
									<div class="form-actions fluid">
										<div class="col-md-offset-3 col-md-9">
											<button type="submit" class="btn blue">确定</button>
											<a href="<?php echo $this->createUrl('printer/index' , array('companyId' => $model->dpid));?>" class="btn default">返回</a>                              
										</div>
									</div>
							<?php $this->endWidget(); ?>