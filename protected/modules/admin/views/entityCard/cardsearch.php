<style>
    body{
        font-size: 15px;
    }

.portlet-body>.row{
    margin:15px 0 30px 0;
}
.item-header{
    text-align: right;
    padding:0px;
}


input[type='button']{
   
  
}
@media (max-width: 768px) {
    .item-header{
        text-align: left;
        font-size:15px;
        margin-bottom: 10px;
        background-color:#f9f9f9;
        padding:10px;
        
    }
    .form-group{
        width:66.666%!important;
}
}
@media (min-width: 768px) {
.find{
    margin-top: 20px;
    margin-left: 250px;
    margin-bottom: 20px;
} 
.find_item1{
    padding-right: 0px !important;
}
.find_item2{
    padding-left: 5px !important;
}
}
ul {
    padding:0;
    margin:0
}
li{
   
   list-style-type :none;
}
.person_info{
        font-size: 16px;
        padding-left: 8px;
        margin-bottom: 30px;
        font-weight: bold;
    }

.small{
    font-size: 14px;
    color: #696969;
}
.person_info li{
    
    margin-right: 40px;
}
.base_info{
    margin-bottom: 30px;
}
.info_header{
    padding-left: 8px;
    font-weight: bold;
    margin-bottom: 3px;
    
}

.nav-tabs>li.active>a, .nav-tabs>li.active>a:hover, .nav-tabs>li.active>a:focus {
  
    border: 0px;
    color:#2d78f4;
    border-bottom:1px solid #2d78f4; 
}
.nav a{
   color:#000;
   font-weight: bold;
}
.contentheadtip{
		width: 96%;
		margin-left: 2%;
		padding: 4px;
		border-bottom: 1px solid white;
	}

.contenthead{
		width: 96%;
		margin-left: 2%;
		padding: 4px;
		border-bottom: 1px solid red;
	}
.contentdiv{
        text-align: center;
        width: 50%;
        float: left;
}
.clear{
        clear: both;
}
.font20{
        font-size: 20px;
}
.font18{
        font-size: 18px;
}
.detaildivtip{
        color: blue;		
        width: 96%;
        margin-left: 2%;
        padding: 6px;
        border-bottom: 1px solid blue;
}
.detailcontent{
        width: 96%;
        margin-left: 2%;
        padding: 4px;		
        border-bottom: 1px solid blue;
}
.detaildiv{
        text-align: center;
        width: 33%;
        float: left;
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
    <div id="main2" name="main2" 
         style="min-width: 500px;min-height:300px;display:none;" 
         onMouseOver="this.style.backgroundColor='rgba(255,222,212,1)'" 
         onmouseout="this.style.backgroundColor=''">
    </div>
 <?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','实体卡'),'url'=>$this->createUrl('entityCard/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','卡查询'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('entityCard/list' , array('companyId' => $this->companyId,'type'=>0)))));?>
<div class="row">   
    <div class="col-md-12">

    <div class="portlet purple box">

        <div class="portlet-body" >
            <form action="" method="post" >     
                    <div class="row find">
                            <div class="col-xs-12 col-sm-5 form-group find_item1">                                    
                                <input type="text" name="num" class="form-control"  placeholder="" >
                            </div>     
                            <div class="col-xs-12 col-sm-2 find_item2">
                                <input type="submit"   class=' btn  btn-primary  ' value="查 询"/>           
                            </div>
                    </div>              
            </form>   
            <div class="info">
                <div class="person_info">
                      <?php if($card_model) :?>
                    <ul>
                        <li class="pull-left">
                            <span><?php echo $card_model->name;?></span>
                           
                        </li>
                        <li class="pull-left">
                            <span> 性别：
                                <?php if(($card_model->sex)=="m")  echo "男";?>
                                <?php if(($card_model->sex)=="f") echo "女";?>
                            </span>
                        </li>
                        <li class="pull-left">
                             <?php  
                               
                                if(($card_model->brandUserLevel)){
                                   echo $card_model->brandUserLevel->level_name;
                                }
                           ?>
                        </li>
                        <li class="pull-left">
                            <span><?php echo $card_model->mobile;?></span>
                            <span>（卡号：<span><?php echo $num;?></span>）</span>     
                        </li>
                        <li class="pull-left">
                            <span>生日：<?php echo $card_model->birthday;?></span>
                            <span></span>
                        </li>
                    </ul>
                    <div style="clear:both;"></div>       
                </div>
                
                <div class="base_info">
                    <div class="info_header"></div>
                    <div class="info_content"> 
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>                                  
                                <th>年龄</th>
                                <th>邮箱</th>
                                <th>余额</th>
                                <th>积分</th>
                                <th>卡状态</th>
                                <th>有效期</th>
                                </tr>
                            </thead>
                            <tbody>
                                 <tr>
                                    <td><?php echo $card_model->ages;?></td>
                                    <td><?php echo $card_model->email;?></td>
                                    <td><?php echo $card_model->all_money;?></td>
                                    <td><?php echo $card_model->all_points;?></td>
                                    <td> 
                                        <?php if(($card_model->card_status)=="0")  echo "正常";?>
                                        <?php if(($card_model->card_status)=="1")  echo "挂失";?>
                                        <?php if(($card_model->card_status)=="2")  echo "注销";?>
                                    </td>
                                    <td><?php echo $card_model->enable_date;?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
     
         
            </div>
            
            <div class="detail_info">
                
                <div class="info_header">历史账单明细</div>
                <ul class="nav nav-tabs"id="attr_info" role="tablist">
                    <li role="presentation" class="active"><a href="javascript:void(0)" data-target ='order_table'>账单</a></li>
                    <li role="presentation" ><a href="javascript:void(0)" data-target ='point_table'>积分</a></li>
                    <li role="presentation"><a href="javascript:void(0)" data-target ='recharge_table'>充值</a></li>
                    
                   
                </ul>
                <div class="info_content"> 
                        <table class="info_item table table-striped table-bordered table-hover" id="order_table">
                             <thead>
                                <tr>
                                    <th>账单号</th>    
                                    <th>时间</th>
                                    <th>金额</th>
                                                               
                                </tr>
                            </thead>
                            <?php 
                             if(!empty($orderPay)):
                                 foreach ($orderPay as $v): 

                             ?>                           
                            <tbody>
                                 <tr>
                                    <td class="accountno" 
                                         accountno="<?php echo $v->account_no;?>" 
                                         orderid="<?php echo $v->order_id?>" 
                                         originalp="<?php echo sprintf("%.2f",$v->order4->reality_total);?>" 
                                         shouldp="<?php echo sprintf("%.2f",$v->order4->should_total);?>" 
                                         youhuip="<?php echo sprintf("%.2f",($v->order4->reality_total)-($v->order4->should_total));?>"
                                         >
                                        <?php echo $v->account_no;?>
                                    </td> 
                                    
                                    <td><?php echo $v->create_at;?></td> 
                                    <td><?php echo $v->pay_amount;?></td> 
                                </tr>
                            </tbody>
                            <?php 
                             endforeach;
                            endif;
                            ?>
                        </table>
                        <table class="info_item table table-striped table-bordered table-hover" id="point_table">
                            <thead>
                                <tr>
                                    <th>来源</th> 
                                    <th>积分</th>
                                    <th>时间</th>
                                                                   
                                </tr>
                            </thead>
                            <?php 
                             if(!empty($card_model->point)):
                                 foreach (($card_model->point) as $v): 

                             ?>                           
                            <tbody>
                                 <tr>
                                    <td> 
                                        <?php if(($v->point_resource)=="0")  echo "消费";?>
                                         <?php if(($v->point_resource)=="1")  echo "充值";?>
                                    </td> 
                                     <td><?php echo $v->points;?></td> 
                                    <td><?php echo $v->create_at;?></td>
                                                                  
                                </tr>
                            </tbody>
                            <?php 
                             endforeach;
                            endif;
                            ?>
                        </table>
                        <table class="info_item table table-striped table-bordered table-hover" id="recharge_table" style="display: none">
                            <thead>
                                <tr>
                                    <th>充值金额</th>
                                    <th>赠送金额</th>
                                    <th>充值人员</th>
                                    <th>时间</th>
                                </tr>
                            </thead>
                            <?php 
                             if(!empty($card_model->recharge)):
                                 foreach (($card_model->recharge) as $v): 

                             ?>                           
                            <tbody>
                                 <tr>
                                    <td><?php echo $v->reality_money;?></td>
                                    <td><?php echo $v->give_money;?></td>
                                    <td><?php echo $v->admin_id;?></td>
                                    <td><?php echo $v->create_at;?></td>
                                </tr>
                            </tbody>
                            <?php 
                             endforeach;
                            endif;
                            ?>
                            
                        </table>
                </div>
            </div>
         <?php endif;?>
        </div> 
    </div>
        
</div>
</div>
</div>


<script>
    $(function(){
        $("input[name=num]").focus();
        
        $(".info_item").hide();
        $(".info_item").eq(0).show();
        $("#attr_info li a").click(function(){
            $(this).parent("li").siblings("li").removeClass("active");
            $(this).parent("li").addClass("active");
            //全部隐藏
            $(".info_item").hide();
            //当前对应的显示
           $("#"+$(this).attr("data-target")).show();
        });
 
    });
    
$('.accountno').click(function() {
          //alert(111);
        $('#orderdetaildiv').remove();
        var orderid = $(this).attr('orderid');
        var accountno = $(this).attr('accountno');
        var originalp = $(this).attr('originalp');
        var shouldp = $(this).attr('shouldp');
        var youhuip = $(this).attr('youhuip');
         //alert(originalp); alert(shouldp);
        var url = "<?php echo $this->createUrl('entityCard/accountDetail',array('companyId'=>$this->companyId));?>/orderid/"+orderid;
        $.ajax({
                   url:url,
                   type:'POST',
                   data:orderid,//CF
                   //async:false,
                   dataType: "json",
                   success:function(msg){
                       var data=msg;
                       if(data.status){
                    //alert(data.msg);
                            var model = data.msg;
                            var change = data.change;
                            var money = data.money;
                            var prodDetailDivAll = '<div id="orderdetaildiv"><div class="contentheadtip font20">账单号：'+accountno+'</div><div class="contenthead font20"><div class="contentdiv"><span>菜品名称</span></div><div class="contentdiv"><span>数量</span></div><div class="clear"></div></div>';
                            var prodDetailEnd = '</div>';
                            var proDetailpayAll = '';
                            for (var i in model){
                                    prodName = model[i].product_name;
                                    prodNum = model[i].all_amount;
                                    setName = model[i].set_name;
                                    var sets = '';
                                    if(setName){
                                            sets = '('+setName+')';
                                            }
                                        //alert(prodName);alert(prodNum);
                                        var prodDetailDivBody = '<div class="contenthead font18"><div class="contentdiv"><span>'+prodName+sets+'</span></div><div class="contentdiv"><span>'+prodNum+'</span></div><div class="clear"></div></div>' 
                                        prodDetailDivAll = prodDetailDivAll + prodDetailDivBody;
                                        }
                                    var proDetailBodyEnd = '<div class="font20 detaildivtip">账单详情</div>'
                                                                                    +'<div class="detailcontent font18"><div class="detaildiv">原价:<span>'+originalp+'</span></div><div class="detaildiv">折后价:<span>'+shouldp+'</span></div><div class="detaildiv">优惠:<span>'+youhuip+'</span></div><div class="clear"></div></div>'
                                                                                    +'<div class="detailcontent font18"><div class="detaildiv">收款现金:<span>'+money+'</span></div><div class="detaildiv">找零:<span>'+change+'</span></div><div class="clear"></div></div>';
                                    //var proDetailDiv = prodDetailDivAll+proDetailBodyEnd;
                                    var proDetailDiv = prodDetailDivAll;//去掉账单收支详情
                                    if(data.allpayment){
                                            var proDetailpayHead = '<div class="font20 detaildivtip">其他支付方式:</div>'
                                            var allpayment = data.allpayment;
                                            var proDetailpaymentall = '';
                                            for (var a in allpayment){
                                                    var name = allpayment[a].name; 
                                                    var nameprice = allpayment[a].pay_amount;
                                                    var paytype = allpayment[a].paytype;
                                                    if(name){
                                                            //alert(name);
                                                            var proDetailpayment = '<div class="detailcontent font18"><div class="detaildiv">'+name+':<span>'+nameprice+'</span></div><div class="clear"></div></div>';

                                                            }else if(paytype){
                                                                    //alert(paytype);
                                                                    var paytypename = '';
                                                                    if (paytype==1){
                                                                            paytypename = '微信支付';
                                                                    }else if(paytype==2){
                                                                            paytypename = '支付宝支付';
                                                                    }else if(paytype==4){
                                                                            paytypename = '会员卡支付';
                                                                    }else if(paytype==5){
                                                                            paytypename = '银联支付';
                                                                    }else if(paytype==9){
                                                                            paytypename = '微信代金券';
                                                                    }else if(paytype==10){
                                                                            paytypename = '微信余额支付';
                                                                    }
                                                                    var proDetailpayment = '<div class="detailcontent font18"><div class="detaildiv">'+paytypename+':<span>'+nameprice+'</span></div><div class="clear"></div></div>';
                                                                    }
                                                    var proDetailpaymentall = proDetailpaymentall + proDetailpayment;
                                                    }
                                            var proDetailpayAll =  proDetailpayHead + proDetailpaymentall + prodDetailEnd;
                                            }
                                    var proDetail = proDetailDiv + proDetailpayAll;
                                    $("#main2").append(proDetail);
            			   layer_zhexiantu=layer.open({
            				     type: 1,
            				     //shift:5,
            				     shade: [0.5,'#fff'],
            				     move:'#main2',
            				     moveOut:true,
            				     offset:['10px','350px'],
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
                          
                       }else{
                           
                       }
                   },
                   error: function(msg){
                       layer.msg('网络错误！！！');
                   }
               });
			   

	        });
</script>

