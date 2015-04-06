							<table class="table table-striped table-bordered table-hover" id="sample_1">
                                                            <?php if($models):?>
                                                                    <thead>
                                                                            <tr>
                                                                                    <th>单品名称</th>
                                                                                    <th>套餐价格</th>
                                                                                    <th>数量</th>
                                                                                    <th>选择</th>
                                                                            </tr>
                                                                    </thead>
                                                                    <tbody>

                                                                    <?php foreach ($models as $model):?>
                                                                            <tr class="<?php echo $model->group_no%2==0?'active':'success';?>">
                                                                                    <td ><?php echo $model->product->product_name ;?></td>
                                                                                    <td><?php echo $model->price;?></td>
                                                                                    <td><?php echo $model->number;?></td>
                                                                                    <td>
                                                                                        分组<?php echo $model->group_no;?><input name="group<?php echo $model->group_no;?>" value="<?php echo $model->product_id.'|'.$model->number.'|'.$model->price;?>" <?php if($model->is_select) echo 'checked'; ?> type="radio"  class="toggle groupradio"/> 

                                                                                    </td>                                                                                                             
                                                                            </tr>
                                                                    <?php endforeach;?>
                                                                    </tbody>
                                                                    <?php endif;?>
                                                            </table>
							<script>
                                                         var setlistid=function(){
                                                             var objs=$('.groupradio');
                                                             var setsel='';
                                                             objs.each(function(){
                                                                    //这里是处理 obj 的函数
                                                                    var chk=$(this).val();
                                                                    
                                                                    if($(this).is(':checked'))
                                                                    {
                                                                        if(setsel.length===0)
                                                                        {
                                                                            setsel=chk;
                                                                        }else{
                                                                            setsel=setsel+','+chk;
                                                                        }
                                                                    }                                                                    
                                                                });  
                                                                $('#selsetlistid').val(setsel);
                                                         };
                                                         setlistid();
                                                         $('.groupradio').click(function(){
                                                            setlistid();
                                                         });	
							</script>