
<style>
.product_list {
        padding-right:10px;
}
.product_list {
        display:inline-block;
}
.product_list ul {
        padding-left:5px;
        padding-left:0px;
}
.product_list ul li {
        float:left;
        width:5.0em;
        height:4.2em;			
        border: 1px solid #add;
        margin:5px;
        list-style:none;
        text-align:center;
        vertical-align:middle;
}

.firstCategory {
        padding-right:10px;
}
.firstCategory {
        display:inline-block;
}
.firstCategory ul {
        padding-left:5px;
        padding-left:0px;
}
.firstCategory ul li {
        float:left;
        background-color: #DDDDDD;
        min-width: 5.0em;
        height:2.8em;			
        border: 1px solid #add;
        margin:5px;
        list-style:none;
        text-align:center;
        vertical-align:middle;
}

.navigation {
    width:100%;
    font-family:Arial;
    float: left;
    height: 98%;
}
.navigation ul {
    list-style-type:none;                /* 不显示项目符号 */
    margin:0px;
    padding:0px;
    height: 100%;
    overflow-y:auto; 
}
.navigation li {
    border-bottom:1px solid #ED9F9F;    /* 添加下划线 */
    font-size: 18px;
}
.navigation li{
    display:block;                        /* 区块显示 */
    padding:8px 8px 8px 0.5em;
    text-decoration:none;
    border-left:12px solid #711515;        /* 左边的粗红边 */
    border-right:1px solid #711515;        /* 右侧阴影 */
}
/*.navigation li a:link, .navigation li a:visited{
    //background-color:#c11136;
    color:#000;
}
.navigation li a:hover{                     鼠标经过时 
    background-color:#90111A;             改变背景色 
    color:#000;                         改变文字颜色 
}*/
.clear{
    clear: both;
}
.slectliclass{
    background-color:#0099FF !important;
}

.firstcategory{
    background-color:#DDDDDD;
}

.secondcategory{
    background-color:#DDDDDD;
}

.productstyle{
    background-color:#78ccf8;
}

.productClick{
    //background-color:#78ccf8;
}

.selectedproduct{
    background-color:#add;
}

.calc_zhe ul li{
    	line-height:30px;
        float: left;
        width: 40%;
        height: 30px;
        border: 1px solid #add;
        margin-top: 8px;
		margin-left: 8px;
		margin-right: 8px;
        font-size: 20px;
        font-weight: 700;
        background-color: pink;
        list-style: none;
        text-align: center;
        vertical-align: middle;
	
}
.calc_num {
        width: 72%;
        display: inline-block;
        margin-top: 10px;
    }
    .calc_button {
        width: 24%;
        display: inline-block;
        margin-top: 10px;
    	margin-left:0px;
    }
    .calc_num ul li {
    	line-height:50px;
        float: left;
        width: 20%;
        height: 2.5em;
        border: 1px solid #add;
        margin: 5px;
        font-size: 20px;
        font-weight: 700;
        background-color: #add;
        list-style: none;
        text-align: center;
        vertical-align: middle;
      }
      .calc_button ul li {
      	padding-left:0px;
      	line-height:50px;
        float: left;
        width: 100%;
        height: 3.5em;
        border: 1px solid #add;
        margin: 5px;
        font-size: 15px;
        font-weight: 700;        
        list-style: none;
        text-align: center;
       
      }
 .calc_dan {
		width:70%;
		display:inline-block;
		margin:5px;
}
	.calc_dan ul li{
		line-height:30px;
        float: left;
        width: 30%;
        height: 2.5em;
        border: 1px solid #add;
        margin: 5px;
        font-size: 20px;
        font-weight: 700;
        background-color: #add;
        list-style: none;
        text-align: center;
        vertical-align: middle;
	}

.dan_button {
		width:25%;
		display:inline-block;
		margin:5px;
}	
.dan_button ul li {
    line-height:45px;
    float: left;
    width: 90%;
    height: 3.5em;
    border: 1px solid #add;
    margin: 5px;
    font-size: 15px;
    font-weight: 700;        
    list-style: none;
    text-align: center;
}
.edit_span_select {
    border:1px solid red;
}
.edit_span_select_zero {
    border:1px solid red;
}
/*
.calc_num {
        width: 56%;
        display: inline-block;
        margin: 2%;
    }
    .calc_button {
        width: 33%;
        display: inline-block;
        margin: 2%;
    }
    .calc_num ul li {
        float: left;
        width: 20%;
        height: 100px;
        border: 1px solid #add;
        margin: 5px;
        font-size: 20px;
        font-weight: 700;
        background-color: #add;
        list-style: none;
        text-align: center;
        vertical-align: middle;
      }
      .calc_button ul li {
        float: left;
        width: 40%;
        height: 100px;
        border: 1px solid #add;
        margin: 5px;
        font-size: 15px;
        font-weight: 700;        
        list-style: none;
        text-align: center;
        vertical-align: middle;
      }*/
</style>

<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/default.css'); ?>
<div class="page-content">
	<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->               
	<div class="modal fade" id="portlet-config" tabindex="-2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
	<div class="modal fade" id="portlet-config3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-wide">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title">Modal title</h4>
				</div>
				<div class="modal-body">
					
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
        <div  id="order_row" style="display:none;">
        <div class="row">
                <div class="col-md-4">
			<!-- BEGIN PAGE TITLE & BREADCRUMB-->			
			<input style="margin:-10px 0 10px 0;" type="button" class="btn green" id="site_list_button" value="<?php echo yii::t('app','临时座');?>">
			<!--<input style="margin:-10px 0 10px 0;" type="button" class="btn green-stripe" id="create_btn" value="<?php echo yii::t('app','转台>>');?>">
			<input style="margin:-10px 0 10px 0;" type="button" class="btn green-stripe" id="create_btn" value="<?php echo yii::t('app','并台>>');?>">
			<input style="margin:-10px 0 10px 0;" type="button" class="btn green-stripe" id="create_btn" value="<?php echo yii::t('app','撤台>>');?>">-->
			<!-- END PAGE TITLE & BREADCRUMB-->
		</div>
                <div class="col-md-8" style="">
			<!-- BEGIN PAGE TITLE & BREADCRUMB-->			
                        <input style="margin:-10px 10px 10px 0;float:right;" type="button" class="btn blue" id="tempsave_btn" value="<?php echo yii::t('app','挂单--');?>">
			<input style="margin:-10px 10px 10px 0;float:right;" type="button" class="btn blue" id="alltaste_btn" value="<?php echo yii::t('app','全单设定');?>">
			<input style="margin:-10px 10px 10px 0;float:right;" type="button" class="btn blue" id="printerKitchen" value="<?php echo yii::t('app','下单&厨打&收银&结单');?>">
			<!-- END PAGE TITLE & BREADCRUMB-->
		</div>
	</div>
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	
	<div class="row">
		
                <div class="col-md-3">
                        <div class="navigation" id="orderdetailauto" style="">
                            <ul orderid="0000000000" class="selectProduct">
                                <span id="order_create_at">2004/12/12 12:00:00</span>
                                <li lid="0000000000" class="selectProductA">                                    
                                        已付<span id="order_should_pay">0</span>元/应付<span id="order_reality_pay">0</span>元
                                </li>    
                            </ul>
                            全单设定：
                            <span id="ordertasteall" tid=""></span>
                            <span id="ordertastememoall"></span>
                            
                        </div>
                        <div class="" id="productInfo" style="display: none;">
                                <div style="margin:0.5em;">
                                    <span style="font-size:2.0em;margin-left:1.0em;display: none;" id="spanLid"></span>
                                    <span style="font-size:2.0em;margin-left:1.0em;display: none;" id="spanProductId"></span>
                                    <span style="font-size:2.0em;margin-left:1.0em;display: none;" id="spanIsRetreat"></span>
                                    <span style="font-size:2.0em;margin-left:1.0em;display: none;" id="spanOrderStatus"></span>
                                    <span style="font-size:2.0em;margin-left:1.0em;display: none;" id="spanProductDiscountOrig"></span>
                                    <span style="font-size:2.0em;margin-left:1.0em;display: none;" id="spanNowPriceOrig"></span>
                                    <span style="font-size:2.0em;margin-left:1.0em;display: none;" id="spanTasteIds"></span>
                                    <span style="font-size:2.0em;margin-left:1.0em;display: none;" id="spanTasteMemo"></span>
                                    <span style="font-size:2.0em;margin-left:1.0em;" id="spanProductName">菜品名称</span>
                                    <input style="float:right;margin-right:1.0em;" type="button" class="btn green" id="btn-retreat" value="<?php echo yii::t('app','退菜');?>">
                                    <input style="float:right;margin-right:1.0em;" type="button" class="btn green" id="btn-reprint" value="<?php echo yii::t('app','单品厨打');?>">
                                </div>
                                <div style="float:left;width:65%;">
                               		 <div style="float:left;width:96%;margin:1px 5px 5px 10px;padding:8px;border:1px solid red;">
                                             <div style="float:left;font-size:1.5em;width:40%;"><?php echo yii::t('app','原价：');?><span id="spanOriginPrice">0.00</span></div>
                                             <div style="float:left;font-size:1.5em;width:20%;"><div style="width:100%;"><?php echo yii::t('app','数量：');?><span id="spanNumber">1</span></div></div>
                                            <div style="float:left;font-size:1.5em;width:40%;"><?php echo yii::t('app','现价：');?><span id="spanNowPrice">0.00</span></div>
                                            <div style="float:left;font-size:1.5em;width:100%;">
                                                优惠：
                                                <div style="width:50%;" class="btn-group" data-toggle="buttons" style="margin: 5px;border: 1px solid red;background: rgb(245,230,230);">
                                                    <label style="margin:5px;float: right;" id="checkboxDiscount" class="selectDiscount btn btn-default active"><input type="checkbox" class="toggle"> 折扣</label>
                                                    <label style="margin:5px;float: right;" id="checkboxMinus" class="selectDiscount btn btn-default"><input type="checkbox" class="toggle"> 减价</label>
                                                    <label style="margin:5px;float: right;" id="checkboxGiving" class="selectDiscount btn btn-default"><input type="checkbox" class="toggle"> 赠送</label>
                                                </div>
                                                <div style="float:right;font-size:1.5em;width:30%;"><div style="width:100%;background-color: #78ccf8;text-align: right;"><span id="spanProductDiscount">%</span></div></div>
                                            
                                            </div>    
	                            	 </div>
                                 <DIV style="width:96%;height:175px;margin:1px;border:0px solid green;">
 	                            <div style="margin-left:10px;border:0px solid red;" class="calc_dan">
                                      <ul style="padding:5px 20px 5px ;">
                                       <li>1</li>
                                       <li>2</li>
                                       <li>3</li>
                                                            
                                       <li>4</li>
                                       <li>5</li>
                                       <li>6</li>
                                                            
                                       <li>7</li>
                                       <li>8</li>
                                       <li>9</li>
                                                           
                                       <li>0</li>
                                       <li>00</li>
                                       <li>.</li>
                                                           
                                     </ul>
                                    </div>
                                    <div style="margin:3px;border:0px solid green;" class="dan_button">
                                     <ul style="padding:0px;">
                                       <li id="cl_one" style="background-color: #add"><?php echo yii::t('app','退格');?></li>
                                       <li id="cl_all" style="background-color: red"><?php echo yii::t('app','清除');?></li>
                                       <li id="product_yes" style="background-color: #0099FF"><?php echo yii::t('app','确认');?></li>
                                       <li id="product_close"><?php echo yii::t('app','取消');?></li>
                                     </ul>
                                    </div>	                            
	                        </DIV> 	                            	 
	                    </div>
                                <div style="float:left;width:33%;margin:1px;border:1px solid red;">
                                        <div style="float:left;height:2.0em;width:100%;line-height:2.0em;font-size:1.5em;"><?php echo yii::t('app','口味：');?></div>
                                        <div id="productTaste" style="width:100%;">

                                        </div>
                                </div>
	                            
                            </div>
                </div>	
            
                <div class="col-md-9">			
                        <div class="tabbable tabbable-custom">
                            <div class="firstCategory">
                                <ul class="">
                                        <?php 
                                        foreach ($categories as $categorie): 
                                            if($categorie->pid=="0000000000"):?>
                                            <li lid="<?php echo $categorie->lid; ?>" class="tabProduct"><?php echo $categorie->category_name; ?></li>
                                        <?php 
                                            endif;
                                        endforeach; ?>                                        
                                        <!--<li typeId="reserve" class="tabtitle"><a href="#tab_1_reserve" data-toggle="tab">套餐</a></li>-->
                                </ul>
                            </div>
                                <?php 
                                foreach ($categories as $categorie): 
                                    if($categorie->pid=="0000000000"):?>
                            <div class="tab-content" style="display:none;" lid="<?php echo $categorie->lid; ?>">                                        
                                        <div style="width:100%;height:100%;">
                                            <div class="product_list">
                                                <ul class="">
                                                    <?php 
                                                        foreach ($categories as $categorie2): 
                                                            if($categorie2->pid==$categorie->lid):?>
                                                        <li style="width:2.2em;background-color: #add" lid="<?php echo $categorie2->lid; ?>"><?php echo $categorie2->category_name; ?></li>
                                                            <?php 
                                                                foreach ($products as $product): 
                                                                    if($product->is_show=="1" and $product->category_id==$categorie2->lid):?>
                                                                    <li class="productClick" lid="<?php echo $product->lid; ?>" store="<?php echo $product->store_number; ?>" price="<?php echo $product->original_price; ?>"><?php echo $product->product_name; ?></li>                                                                    
                                                            <?php  endif;                                                         
                                                            endforeach; ?>
                                                    <?php 
                                                        endif;
                                                    endforeach; ?>
                                                </ul>
                                            </div>                                        												
                                        </div>
                                    </div>
                                <?php 
                                    endif;
                                endforeach; ?> 
                                
                        </div>
                </div>
            <!--------pay box begin-------->
                                    <div id="accountbox" style="display:none;">
                                        <div>
                                            <div style="width: 95%;margin:1.0em;font-size: 1.5em;">
                                                    <DIV style="float:left;width:27%;font-size: 1.5em;border:1px solid red;"><?php echo yii::t('app','总额');?><span id="payOriginAccount">10000.23</span></DIV>
                                                    <DIV class="edit_span" selectid="discount" style="float:left;width:15%;background-color:#9acfea;"><?php echo yii::t('app','折扣');?><span id="payDiscountAccount">100%</span></DIV>
                                                    <DIV class="edit_span" selectid="minus" style="float:left;width:20%;background-color:#9acfea;"><?php echo yii::t('app','优惠');?><span id="payMinusAccount">0</span></DIV>
                                                    <DIV class="" id="cancel_zero" style="float:left;width:10%;background-color:#9acfea;"><?php echo yii::t('app','抹零');?></DIV>
                                                    <DIV style="float:left;width:27%;font-size: 1.5em;border:1px solid red;"><?php echo yii::t('app','应付');?><span id="payShouldAccount">10000.23</span></DIV>
                                            </div>
                                            
                                            <div style="float: left;width:73%;margin-top: 2.0em;">
                                                <DIV style="float:left;width:100%;border:0px solid red;">
                                                 <div style="margin-left:0px;border:0px solid red;" class="calc_num">
                                                     <ul>
                                                         <li>1</li>
                                                         <li>2</li>
                                                         <li>3</li>
                                                         <li>10</li>
                                                         <li>4</li>
                                                         <li>5</li>
                                                         <li>6</li>
                                                         <li>20</li>
                                                         <li>7</li>
                                                         <li>8</li>
                                                         <li>9</li>
                                                         <li>50</li>
                                                         <li>0</li>
                                                         <li>00</li>
                                                         <li>.</li>
                                                         <li>100</li>
                                                     </ul>
                                                 </div>
                                                <div style="margin:0px;border:0px solid green;" class="calc_button">
                                                    <ul style="padding-left:0px;">
                                                        <li id="pay_clearone" style="background-color: #add"><?php echo yii::t('app','退格');?></li>
                                                        <li id="pay_clearall" style="background-color: red"><?php echo yii::t('app','清除/全额');?></li>
                                                        <li id="pay_btn" style="background-color: #0099FF"><?php echo yii::t('app','收银');?></li>    
                                                        <li id="layer2_close" class="default" style="background-color: #00FFFFFF"><?php echo yii::t('app','取消');?></li>
                                                    </ul>
                                                </div> 
                                              </DIV> 
                                            </div>
                                            <div style="float: left;width:25%;">
                                                <div style="width: 85%;margin:1.0em;font-size:1.5em;">
                                                    实收<span style="text-align:right;" id="payRealityAccount">0.00</span><br>
                                                    找零<span style="text-align:right;" id="payChangeAccount">0.00</span><br>
                                                    <DIV class="edit_span edit_span_select" selectid="pay_cash" style="float:left;width:100%;background-color:#9acfea;"><?php echo yii::t('app','现金');?><span id="payCashAccount">0.00</span></DIV>
                                                    <DIV class="edit_span" selectid="pay_member_card" style="float:left;width:100%;background-color:#9acfea;"><?php echo yii::t('app','会员卡');?><span  style="text-align:right;" cardno="0000000000" id="payMemberAccount">0.00</span></DIV>
                                                    <DIV class="edit_span" selectid="pay_union_card" style="float:left;width:100%;background-color:#9acfea;"><?php echo yii::t('app','银联卡');?><span style="text-align:right;" id="payUnionAccount">0.00</span></DIV>
                                                    
                                                </div>    
                                            </div>
                                        </div>
                                    </div>
            </div>
        </div>
            <!-------------site----------->
            <div class="row" id="site_row" style="display:block;">
                <div class="col-md-12">
                    <div class="tabbable tabbable-custom">
                        <div class="firstCategory">
                            <ul class="">
                            <?php if($siteTypes):?>
                            <?php foreach ($siteTypes as $key=>$siteType):?>
                                    <li typeId="<?php echo $key ;?>" class="tabSite <?php if($key == $typeId) echo 'slectliclass';?>"><?php echo $siteType ;?></li>
                            <?php endforeach;?>
                            <?php endif;?>
                                    <li typeId="tempsite" class="tabSite <?php if($typeId == 'tempsite') echo 'slectliclass';?>"><?php echo yii::t('app','临时座/排队');?></li>
                            </ul>
                        </div>
                            <div class="tab-content" id="tabsiteindex">
                                     <!-- END EXAMPLE TABLE PORTLET-->												
                            </div>
                        
                    </div>
		</div>
            </div>
            <!---------------taste------------------>
            <div id="tastebox" style="display: none">
                
            </div>
            <!---printRsultList printresult -->
            <div id="printRsultList" style="display: none">
                <div style="margin:10px;">
                <h4 id="printalljobs"></h4>
                <span style="color:red;" id="minustimes">30</span><?php echo yii::t('app','秒倒计时...');?></br>
                <span style="color:red;" id="successnumid">0</span><?php echo yii::t('app','...个菜品厨打已经成功');?></br>
                <span style="color:red;" id="notsurenumid">0</span><?php echo yii::t('app','...个菜品正在打印');?></br>
                <span style="color:red;" id="errornumid">0</span><?php echo yii::t('app','...个菜品厨打失败，');?></br></br>
                <?php echo yii::t('app','有打印失败的菜品，请重新厨打。');?><br><br>
                <div style="text-align:center;">
                    <input type="button" class="btn green" id="print_box_close" value="<?php echo yii::t('app','确 定');?>">
                </div>
                </div>
            </div>
            <!---membercardInfo  -->
            <div id="membercardInfo" style="display:none;">
                <div style="width: 95%;margin:1.0em;font-size: 1.5em;">
                        <DIV style="float:left;width:95%;font-size: 1.5em;border:1px solid red;"><?php echo yii::t('app','还欠:');?><span id="card_pay_span_money">10000.23</span></DIV>
                        <DIV style="float:left;width:95%;font-size: 1.5em;border:1px solid red;"><?php echo yii::t('app','会员卡支付：');?><input id="card_pay_input_money" style="width:50%;border:none;" type="text" value="10000.23"></DIV>
                        <DIV style="float:left;width:95%;font-size: 1.5em;border:1px solid red;"><?php echo yii::t('app','请刷卡');?><input id="card_pay_input_card" style="width:50%;border:none;" type="text" value="888123"></DIV>
                        <DIV style="float:left;width:95%;font-size: 1.5em;border:1px solid red;"><?php echo yii::t('app','密码');?><input id="card_pay_input_password" style="width:50%;border:none;" type="password" value="12345"></DIV>
                </div>
                <div style="text-align:center;width: 95%;margin:1.0em;">
                    <input style="margin:1.0em;" type="button" class="btn green" id="member_card_pay" value="<?php echo yii::t('app','确 定');?>">
                    <input style="margin:1.0em;" type="button" class="btn green" id="member_card_pay_close" value="<?php echo yii::t('app','取 消');?>">
                </div>
            </div>
            
        <script type="text/javascript">
            var gssid=0;
            var gsistemp=0;
            var gstypeid=0;
            var gop=0;
            var tabcurrenturl="";
            var layer_index1;
            var layer_index2;
            var layer_index3;
            //var layer_index_account;
            var layer_index_printresult;
            var layer_index_membercard;
            var first_tab="<?php echo $categories[0]['lid']; ?>";
            if (typeof Androidwymenuprinter == "undefined") {
                event_clicktouchstart="click";
                event_clicktouchend="click";
            }else{
                event_clicktouchstart="touchstart";
                event_clicktouchend="touchend";
            }
            //alert(event_clicktouchstart);
            $(document).ready(function() {
                $('body').addClass('page-sidebar-closed');
                //$('.nav-tabs').find('li[lid='+first_tab+']').addClass("slectliclass");
                $('.firstCategory').find('li[lid='+first_tab+']').addClass("slectliclass");
                $('.tab-content[lid='+first_tab+']').show();
                //tab-content
                tabcurrenturl='<?php echo $this->createUrl('defaultSite/showSite',array('typeId'=>$typeId,'companyId'=>$this->companyId));?>';
                $('#tabsiteindex').load(tabcurrenturl);
            });
            
            $('.tabSite').on(event_clicktouchstart, function(){
                $('.tabSite').removeClass('slectliclass');
                $(this).addClass('slectliclass');
                var typeId=$(this).attr('typeid');
                tabcurrenturl='<?php echo $this->createUrl('defaultSite/showSite',array('companyId'=>$this->companyId));?>'+'/typeId/'+typeId+'/sistemp/'+gsistemp+'/stypeId/'+gstypeid+'/ssid/'+gssid+'/op/'+gop;
                $('#tabsiteindex').load(tabcurrenturl); 
            });
            
            $('.tabProduct').on(event_clicktouchstart, function(){
                $('.tabProduct').removeClass('slectliclass');
                $(this).addClass('slectliclass');
                var lid=$(this).attr('lid');
                $('.tab-content').hide();
                $('.tab-content[lid='+lid+']').show();
            });
            
            $('.productClick').on(event_clicktouchstart, function(){
                var origin_price=$(this).attr("price");
                var lid=$(this).attr("lid");                //[lid='+lid+']
                var obj=$('.selectProductA[productid="'+lid+'"][order_status="0"]');//.find('span[class="badge"]');
                //alert(obj.attr("lid"));
                if(typeof obj.attr("lid")== "undefined")
                {
                    var appendstr=' <li lid="0000000000"' 
                                  +'      productid="'+lid+'"'
                                  +'      order_status="0"' 
                                  +'      is_giving="0" '
                                  +'      is_print="0" '
                                  +'       is_retreat="0"' 
                                  +'      tasteids="" tastememo=""' 
                                  +'      class="selectProductA">'
                                  +'  <span style="background-color:#005580;" class="special badge" content="">'
                                  +'      </span>'
                                  +'  <span style="font-size:20px !important;height:auto;" class="badge">1</span>'
                                  +'  <span class="selectProductPrice" style="color:#976125;display:none">'+origin_price+'</span>'
                                  +'  <span class="selectProductDiscount" style="color:#976125;display:none">100%</span>'
                                  +'      <span class="selectProductNowPrice" style="color:#976125">'+origin_price+'</span>'
                                  +'      <span style="position:absolute;" class="selectProductName">'+$(this).text()+'</span>'
                                  +'      <img class="selectProductDel" style="position: absolute;right:0.3em; width: 3.0em;height: 2.0em;padding:5px 10px 5px 10px;" '
                                  +'           src="<?php echo Yii::app()->request->baseUrl;?>/img/product/icon_cart_m.png"> '                                  
                                  +' </li>'
                    $(".selectProduct").append(appendstr);
                }else{
                    var curnum = parseFloat(obj.find('span[class="badge"]').text().replace(",",""))+1;                    
                    obj.find('span[class="badge"]').text(curnum);
                }
                
            });
            
            $('.selectProductInfo').on(event_clicktouchstart, function(){
               
                                                         
            });
            
            function getallproductinfo()
            {
                //取得整体订单的tasteids tastememo
                var ordertasteids=$("#ordertasteall").attr("tid");
                var ordertastememo=$("#ordertastememoall").text();
                var productlist="";
                var tempproduct="";
                //取得所有未下单状态的单品，没有打印和厨打都是0,1就不能修改了。
                $(".selectProductA[order_status='0']").each(function(){
                    tempproduct=$(this).attr("lid");
                    tempproduct=tempproduct+","+$(this).attr("productid");
                    tempproduct=tempproduct+","+$(this).attr("order_status");
                    tempproduct=tempproduct+","+$(this).find("span[class='badge']").text();
                    tempproduct=tempproduct+","+$(this).find("span[class='selectProductDiscount']").text();
                    tempproduct=tempproduct+","+$(this).find("span[class='selectProductNowPrice']").text();
                    tempproduct=tempproduct+","+$(this).attr("is_giving");
                    tempproduct=tempproduct+","+$(this).attr("tasteids");
                    tempproduct=tempproduct+","+$(this).attr("tastememo");
                    if(productlist!="")
                    {
                        productlist=productlist+";"+tempproduct;
                    }else{
                        productlist=tempproduct;
                    }
                    
                });
                //包括单品列表、单品口味列表，口味备注等
                return '&productlist='+productlist+
                        '&ordertasteids='+ordertasteids+
                        '&ordertastememo='+ordertastememo;                 
            }
            
            
            $('#alltaste_btn').on(event_clicktouchstart,function(){
                    var tids=$("#ordertasteall").attr("tid");
                    $(".selectTaste").removeClass("active");
                    $.each(tids.split("|"),function(index,data){
                        $(".selectTaste[tasteid="+data+"]").addClass("active");
                    });
                    $("#taste_memo_edit").val($("#ordertastememoall").text());                
                    layer_index3=layer.open({
                     type: 1,
                     shade: false,
                     title: false, //不显示标题
                     area: ['30%', 'auto'],
                     content: $('#tastebox'),//$('#productInfo'), //捕获的元素
                     cancel: function(index){
                         layer.close(index);
        //                        this.content.show();
        //                        layer.msg('捕获就是从页面已经存在的元素上，包裹layer的结构',{time: 5000});
                     }
                 });
            });
                        
            $('#tempsave_btn').on(event_clicktouchstart,function(){
                   //取得数据
                   var orderid=$(".selectProduct").attr("orderid");
                   //取得orderid
                    //var orderid=$(".selectProduct").attr("orderid");
                   //var orderstatus="1";
                   var sendjson=getallproductinfo();
                   var url="<?php echo $this->createUrl('defaultOrder/orderPause',array('companyId'=>$this->companyId));?>/orderid/"+orderid+"/orderstatus/1";
                   var index = layer.load(0, {shade: [0.3,'#fff']});
                   $.ajax({
                    url:url,
                    type:'POST',
                    data:sendjson,
                    async:false,
	            dataType: "json",
	            success:function(msg){
                        var data=msg;
	                if(data.status){
                            layer.close(index);
                            $('#orderdetailauto').load('<?php echo $this->createUrl('defaultOrder/orderPartial',array('companyId'=>$this->companyId));?>/orderId/'+orderid);
                            alert("保存成功！");
                        }else{
                            layer.close(index);
                            alert(data.msg);
                        }
                    },
                    error: function(msg){
                        layer.close(index);
                        alert("保存失败2！");
                    }
	     	});
                   
            });
            
            $('#printerKitchen').on(event_clicktouchstart, function(){
                var orderid=$(".selectProduct").attr("orderid");
                //var orderstatus="2";
//                //有新品
                if($(".selectProductA[order_status='0']").length>0)
                {
                        //取得数据
                        var sendjson=getallproductinfo();
                        var url="<?php echo $this->createUrl('defaultOrder/orderKitchen',array('companyId'=>$this->companyId,"callId"=>"0"));?>/orderid/"+orderid+"/orderstatus/2";
                        var statu = confirm("<?php echo yii::t('app','下单，并厨打，确定吗？');?>");
                         if(!statu){
                             return false;
                         }                   
                        $.ajax({
                            url:url,
                            type:'POST',
                            data:sendjson,
                            async:false,
                            dataType: "json",
                            success:function(msg){
                                //保存成功，刷新
                                $('#orderdetailauto').load('<?php echo $this->createUrl('defaultOrder/orderPartial',array('companyId'=>$this->companyId));?>/orderId/'+orderid);
                                var data=msg;
                                //alert(data.msg);
                                if(data.status){
                                    //取得打印结果,在layer中定时取得
                                    //alert(data.msg);
                                    $("#printalljobs").text(data.msg);
                                    layer_index_printresult=layer.open({
                                         type: 1,
                                         shade: false,
                                         title: false, //不显示标题
                                         area: ['30%', 'auto'],
                                         content: $('#printRsultList'),//$('#productInfo'), //捕获的元素
                                         cancel: function(index){
                                             layer.close(index);
                            //                        this.content.show();
                            //                        layer.msg('捕获就是从页面已经存在的元素上，包裹layer的结构',{time: 5000});
                                         }
                                     });
                                    var waitingsecond=30;
                                    var interval=setInterval(function(){ 
                                        $.get('<?php echo $this->createUrl('defaultOrder/printKitchenResultAll',array('companyId'=>$this->companyId));?>/orderId/'+orderid+'/timenum/'+waitingsecond,
                                            function(data){
                                                //waitingsecond--
                                                //alert(data.notsurenum);
                                                $("#minustimes").html(waitingsecond);
                                                $("#successnumid").html(data.successnum);
                                                $("#errornumid").html(data.errornum);
                                                $("#notsurenumid").html(data.notsurenum);
                                                if(data.finished && data.errornum==0 && data.notsurenum==0)
                                                {
                                                    //all success
                                                    clearInterval(interval);
                                                    layer.close(layer_index_printresult);
                                                }                                                
                                            },'json'); 
                                            waitingsecond--;
                                            if(waitingsecond<0)
                                            {                                                       
                                                $("#notsurenumid").html(0);
                                                $("#errornumid").html(data.errornum+data.notsurenum);
                                                clearInterval(interval);
                                            }
                                    },1000);                                    
                                }else{
                                    alert("下单成功，打印失败");
                                }
                               //以上是打印
                               //刷新orderPartial	                 
                            },
                            error: function(msg){
                                alert("保存失败2");
                            }
                        });
                }else{ //没有新品
                    //设置总额
                    var payOriginAccount=parseFloat($("#order_should_pay").text().replace(",",""));
                    $("#payOriginAccount").text(payOriginAccount);
                    var payDiscountAccount=parseFloat($("#payDiscountAccount").text().replace(",",""));
                    var payMinusAccount=parseFloat($("#payMinusAccount").text().replace(",",""));
                    $("#payShouldAccount").text((payOriginAccount*payDiscountAccount/100 - payMinusAccount).toFixed(2));
                        
                    //出现收银界面
                    layer_index2=layer.open({
                         type: 1,
                         shade: false,
                         title: false, //不显示标题
                         area: ['65%', '60%'],
                         content: $('#accountbox'),//$('#productInfo'), //捕获的元素
                         cancel: function(index){
                             layer.close(index);
            //                        this.content.show();
            //                        layer.msg('捕获就是从页面已经存在的元素上，包裹layer的结构',{time: 5000});
                         }
                     });   
                 }
            });
            
            $('#print_box_close').on(event_clicktouchstart, function(){               
                 layer.close(layer_index_printresult);                    
            });
            
            $('#layer2_close').on(event_clicktouchstart, function(){               
                 layer.close(layer_index2);                    
            });
            
            $('#site_list_button').on(event_clicktouchstart,function(){
                $('#site_row').show();
                $('#order_row').hide();
            });
            
            $('.selectProductDel').live(event_clicktouchstart, function(){
                var orderstatus=$(this).parent().attr("order_status");
                if(orderstatus!="0")
                {
                    alert("已经下单，不能删除，请退菜");
                    return false;
                }                
                var obj=$(this).parent().find('span[class="badge"]');
                var curnum=parseFloat(obj.text().replace(",",""));
                if(curnum==1)
                {
                    $(this).parent().remove();
                }else{
                    obj.text(curnum-1);
                }
                return false;
            });
            $('.selectProductName,.selectProductName,.badge').live('click', function(){
                var lid=$(this).parent().attr('lid');
                var productid=$(this).parent().attr('productid');
                var isgiving=$(this).parent().attr('is_giving');
                var originprice=$(this).parent().find(".selectProductPrice").text();
                var productnumber=$(this).parent().find("span[class='badge']").text();
                var nowprice=$(this).parent().find(".selectProductNowPrice").text();
                var productdiscount=$(this).parent().find(".selectProductDiscount").text();
                var productname=$(this).parent().find(".selectProductName").text();
                var tasteids=$(this).parent().attr("tasteids");
                var tastememo=$(this).parent().attr("tastememo");                
                var isretreat=$(this).parent().attr("is_retreat");
                var orderstatus=$(this).parent().attr("order_status");
                if(productdiscount.lastIndexOf("%")>=0)
                {
                    $(".selectDiscount").removeClass("active");
                    $(".selectDiscount[id='checkboxDiscount']").addClass("active");
                }else{
                    $(".selectDiscount").removeClass("active");
                    $(".selectDiscount[id='checkboxMinus']").addClass("active");
                }
                if(isgiving=="1")
                {
                    $(".selectDiscount").removeClass("active");
                    $(".selectDiscount[id='checkboxGiving']").addClass("active");
//                    $("#spanProductDiscount").text(originprice);
//                    $("#spanNowPrice").text("0");
                } 
                $("#spanLid").text(lid);
                $("#spanProductId").text(productid);
                $("#spanProductName").text(productname);
                $("#spanOriginPrice").text(originprice);
                $("#spanNumber").text(productnumber);
                $("#spanNowPrice").text(nowprice);
                $("#spanNowPriceOrig").text(nowprice);
                $("#spanProductDiscount").text(productdiscount);
                $("#spanProductDiscountOrig").text(productdiscount);
                $("#spanTasteIds").text(tasteids);
                $("#spanTasteMemo").text(tastememo);
                $("#spanIsRetreat").text(isretreat);
                $("#spanOrderStatus").text(orderstatus);
                $('#productTaste').load('<?php echo $this->createUrl('defaultOrder/productTasteAll',array('companyId'=>$this->companyId,'isall'=>'0'));?>/lid/'+productid);
                layer_index1=layer.open({
                     type: 1,
                     shade: false,
                     title: false, //不显示标题
                     area: ['60%', '65%'],
                     content: $('#productInfo'), //捕获的元素
                     cancel: function(index){
                         layer.close(index);
        //                        this.content.show();
        //                        layer.msg('捕获就是从页面已经存在的元素上，包裹layer的结构',{time: 5000});
                     }
                 });  
            });
            
            $('#btn-retreat').on(event_clicktouchstart,function(){
                var lid =$("#spanLid").text();
                var productid=$("#spanProductId").text();
                var isretreat=$("#spanIsRetreat").text();
                var orderstatus=$("#spanOrderStatus").text();
                if(isretreat=="1")
                {
                    alert("已经退菜！");
                    return false
                }
                if(orderstatus=="0" || orderstatus=="1")
                {
                    alert("还没有下单、直接删除就行！");
                    return false
                }    
                alert(lid);//不能刷新orderPartial，手动改变状态
                var $modal=$('#portlet-config');
                    $modal.find('.modal-content').load('<?php echo $this->createUrl('defaultOrder/retreatProduct',array('companyId'=>$this->companyId));?>/id/'+lid
                    ,'', function(){
                      $modal.modal();
                    });
             });
             
             $('#btn-reprint').on(event_clicktouchstart,function(){
                var lid =$("#spanLid").text();
                var orderid=$(".selectProduct").attr("orderid");
                var $modal=$('#portlet-config');
                //不能刷新orderPartial，手动改变状态
                $modal.find('.modal-content').load('<?php echo $this->createUrl('defaultOrder/printOneKitchen',array('companyId'=>$this->companyId));?>/orderProductId/'+lid+'/orderId/'+orderid
                        ,'', function(){
                                    $modal.modal();
                            });                                                                    
            });
            
            $('#cancel_zero').on(event_clicktouchstart,function(){
                var payRealityAccount=$("#payRealityAccount").text();
                var payOriginAccount=parseFloat($("#payOriginAccount").text().replace(",",""));
                var payDiscountAccount=parseFloat($("#payDiscountAccount").text().replace(",",""));
                var payMinusAccount=parseFloat($("#payMinusAccount").text().replace(",",""));
                var payShouldAccount=$("#payShouldAccount").text();                        
                //var payChangeAccount=$("#payChangeAccount").text();
                if($(this).hasClass("edit_span_select_zero"))
                {
                    $(this).removeClass("edit_span_select_zero");
                    $("#payShouldAccount").text((payOriginAccount*payDiscountAccount/100 - payMinusAccount).toFixed(2));
                    
                }else{
                    $(this).addClass("edit_span_select_zero");
                    payShouldAccount=payShouldAccount.substr(0,payShouldAccount.indexOf("."))+".00";
                    $("#payShouldAccount").text(payShouldAccount);
                    
                }
                var changeaccount=parseFloat(payRealityAccount.replace(",",""))-parseFloat($("#payShouldAccount").text().replace(",",""));
                if(changeaccount>0)
                {
                    $("#payChangeAccount").text(changeaccount.toFixed(2));
                }else{
                    $("#payChangeAccount").text("0.00");
                }
            });
            
            $('.edit_span').on(event_clicktouchstart,function(){
                $('.edit_span').removeClass("edit_span_select");
                $(this).addClass("edit_span_select");
                var payOriginAccount=$("#payOriginAccount").text();
                var selectid=$(this).attr("selectid");
                if(selectid=="pay_member_card")
                {
                    fn_member_card_pay();
                }
            });
            
            $('.calc_num').on(event_clicktouchstart,'li',function(){
                var nowval=$(this).text();
                var selectid=$(".edit_span_select").attr("selectid");
                var payOriginAccount=$("#payOriginAccount").text();
                var payDiscountAccount=$("#payDiscountAccount").text();
                var payMinusAccount=$("#payMinusAccount").text();
                var cancel_zero=$("#cancel_zero").hasClass("edit_span_select_zero");
                var payShouldAccount=$("#payShouldAccount").text();
                var payRealityAccount=$("#payRealityAccount").text();
                var payCashAccount=$("#payCashAccount").text();
                var payMemberAccount=$("#payMemberAccount").text();
                var payUnionAccount=$("#payUnionAccount").text();
                if(selectid=="discount")
                {   
                    if(nowval!="." && nowval!="00" && nowval!="10" && nowval!="20" && nowval!="50" && nowval!="100")
                    {
                        if(parseFloat(payDiscountAccount.replace(",",""))*10>100)
                        {
                            payDiscountAccount=nowval;
                        }else{
                            payDiscountAccount=parseInt(payDiscountAccount)*10 + parseInt(nowval);
                        }
                        $("#payDiscountAccount").text(payDiscountAccount+"%");
                        $("#payMinusAccount").text("0.00");
                        payOriginAccount=parseFloat($("#payOriginAccount").text().replace(",",""));
                        payDiscountAccount=parseFloat($("#payDiscountAccount").text().replace(",",""));
                        payMinusAccount=parseFloat($("#payMinusAccount").text().replace(",",""));
                        $("#payShouldAccount").text((payOriginAccount*payDiscountAccount/100 - payMinusAccount).toFixed(2));
                        if(cancel_zero)
                        {
                            payShouldAccount=$("#payShouldAccount").text();
                            payShouldAccount=payShouldAccount.substr(0,payShouldAccount.indexOf("."))+".00";
                            $("#payShouldAccount").text(payShouldAccount);
                        }
                        var changeaccount=parseFloat(payRealityAccount.replace(",",""))-parseFloat($("#payShouldAccount").text().replace(",",""));
                        if(changeaccount>0)
                        {
                            $("#payChangeAccount").text(changeaccount.toFixed(2));
                        }else{
                            $("#payChangeAccount").text("0.00");
                        }
                    }
                }else if(selectid=="minus")
                {
                    if(nowval=="10" || nowval=="20"|| nowval=="50"|| nowval=="100")
                    {
                        return;
                    }
                    //alert(payMinusAccount);alert(nowval);
                    if(payMinusAccount=="0.00" || payMinusAccount=="0" || payMinusAccount=="00")
                    {
                        if(nowval!=".")
                        {
                            $("#payMinusAccount").text(nowval);
                        }
                    }else{
                        if(payMinusAccount.indexOf(".")>0 && nowval==".")
                        {

                        }else{
                            $("#payMinusAccount").html(payMinusAccount+nowval);
                        }
                    }                    
                    $("#payDiscountAccount").text("100%");
                    payOriginAccount=parseFloat($("#payOriginAccount").text().replace(",",""));
                    payDiscountAccount=parseFloat($("#payDiscountAccount").text().replace(",",""));
                    payMinusAccount=parseFloat($("#payMinusAccount").text().replace(",",""));
                    var shouldpaytemp=payOriginAccount*payDiscountAccount/100 - payMinusAccount;
                    if(shouldpaytemp>0)
                    {
                        $("#payShouldAccount").text(shouldpaytemp.toFixed(2));
                    }else{
                        $("#payShouldAccount").text("0.00");
                    }
                    if(cancel_zero)
                    {
                        payShouldAccount=$("#payShouldAccount").text();
                        payShouldAccount=payShouldAccount.substr(0,payShouldAccount.indexOf("."))+".00";
                        $("#payShouldAccount").text(payShouldAccount);
                    }
                    var changeaccount=parseFloat(payRealityAccount.replace(",",""))-parseFloat($("#payShouldAccount").text().replace(",",""));
                    if(changeaccount>0)
                    {
                        $("#payChangeAccount").text(changeaccount.toFixed(2));
                    }else{
                        $("#payChangeAccount").text("0.00");
                    }
                }else if(selectid=="pay_cash")
                {
                    if(nowval=="10" || nowval=="20"|| nowval=="50"|| nowval=="100")
                    {
                        return;
                    }
                    //alert(payMinusAccount);alert(nowval);
                    if(payCashAccount=="0.00" || payCashAccount=="0" || payCashAccount=="00")
                    {
                        if(nowval!=".")
                        {
                            $("#payCashAccount").text(nowval);
                        }
                    }else{
                        if(payCashAccount.indexOf(".")>0 && nowval==".")
                        {

                        }else{
                            $("#payCashAccount").html(payCashAccount+nowval);
                        }
                    }                    
                    $("#payRealityAccount").html((parseFloat($("#payCashAccount").text().replace(",",""))+parseFloat(payMemberAccount.replace(",",""))+parseFloat(payUnionAccount.replace(",",""))).toFixed(2));
                    
                    var changeaccount=parseFloat($("#payRealityAccount").text().replace(",",""))-parseFloat($("#payShouldAccount").text().replace(",",""));
                    if(changeaccount>0)
                    {
                        $("#payChangeAccount").text(changeaccount.toFixed(2));
                    }else{
                        $("#payChangeAccount").text("0.00");
                    }
                }else if(selectid=="pay_member_card")
                {
                    return false;
                }else if(selectid=="pay_union_card")
                {
                    if(nowval=="10" || nowval=="20"|| nowval=="50"|| nowval=="100")
                    {
                        return;
                    }
                    //alert(payMinusAccount);alert(nowval);
                    if(payUnionAccount=="0.00" || payUnionAccount=="0" || payUnionAccount=="00")
                    {
                        if(nowval!=".")
                        {
                            $("#payUnionAccount").text(nowval);
                        }
                    }else{
                        if(payUnionAccount.indexOf(".")>0 && nowval==".")
                        {

                        }else{
                            $("#payUnionAccount").html(payUnionAccount+nowval);
                        }
                    }                    
                    $("#payRealityAccount").html((parseFloat($("#payUnionAccount").text().replace(",",""))+parseFloat(payMemberAccount.replace(",",""))+parseFloat(payCashAccount.replace(",",""))).toFixed(2));
                    
                    var changeaccount=parseFloat($("#payRealityAccount").text().replace(",",""))-parseFloat($("#payShouldAccount").text().replace(",",""));
                    if(changeaccount>0)
                    {
                        $("#payChangeAccount").text(changeaccount.toFixed(2));
                    }else{
                        $("#payChangeAccount").text("0.00");
                    }
                }
                
            });
            
            $('.calc_dan').on(event_clicktouchstart,'li',function(){
                if($(".selectDiscount[id='checkboxGiving']").hasClass("active"))
                {
                    return false;
                }
                if($("#checkboxDiscount").hasClass("active"))
                {
                    var discount=parseFloat($("#spanProductDiscount").text().replace(",",""));
                    var originprice=$("#spanOriginPrice").text();
                    var nowval=$(this).html();
                    if(nowval!="." && nowval!="00")
                    {
                        if(discount*10>99)
                        {
                            discount=nowval;
                        }else{
                            discount=parseInt(discount*10) + parseInt(nowval);
                        }
                        $("#spanProductDiscount").text(discount+"%");
                        $("#spanNowPrice").text((originprice*discount/100).toFixed(2));
                    }
                   return false;
                }
                if($("#checkboxMinus").hasClass("active"))
                {
                    var discount=$("#spanProductDiscount").text();
                    var originprice=parseFloat($("#spanOriginPrice").text().replace(",",""));
                    var nowval=$(this).html();
                    if(discount=="0.00" && nowval!=".")
                    {
                        $("#spanProductDiscount").html(nowval);
                    }
                    else if(discount=="0" || discount=="00")
                    {
                        if(nowval!=".")
                        {
                            $("#spanProductDiscount").text(nowval);
                        }
                    }else{
                        if(discount.indexOf(".")>0 && nowval==".")
                        {

                        }else{
                            $("#spanProductDiscount").html(discount+nowval);
                        }
                    }
                    var cashinf=parseFloat($("#spanProductDiscount").text().replace(",",""));
                    if(originprice-cashinf>0)
                    {
                        $("#spanNowPrice").html(Math.round((originprice-cashinf)*100)/100);//little than 0 not show
                    }else{
                        $("#spanNowPrice").html("0");
                    }
                }
            });

            $('#cl_one').on(event_clicktouchstart,function(){
                if($("#checkboxMinus").hasClass("active"))
                {
                    var discount=$("#spanProductDiscount").text();
                    var originprice=parseFloat($("#spanOriginPrice").text().replace(",",""));
                    if(discount.length==1)
                    {
                        $("#spanProductDiscount").html("0");
                        $("#spanNowPrice").html($("#spanOriginPrice").text());
                    }else{
                        $("#spanProductDiscount").html(discount.substr(0,discount.length-1));                    
                        var cashinf=parseFloat($("#spanProductDiscount").text().replace(",",""));
                        if(originprice-cashinf>0)
                        {
                            $("#spanNowPrice").html(Math.round((originprice-cashinf)*100)/100);//little than 0 not show
                        }else{
                            $("#spanNowPrice").html("0");
                        }
                    }
                }
            });
            
            $('#cl_all').on(event_clicktouchstart,function(){
                var discountOrig=$("#spanProductDiscountOrig").text();
                var spanOriginPrice=$("#spanOriginPrice").text();
                var nowpriceOrig=$("#spanNowPriceOrig").text();
                //alert(discountOrig);
                
                if(discountOrig.lastIndexOf("%")>=0)
                {
                    $("#spanProductDiscount").text(discountOrig);
                    $("#spanNowPrice").text(nowpriceOrig);
                    $(".selectDiscount").removeClass("active");
                    $(".selectDiscount[id='checkboxDiscount']").addClass("active");
                }else{                    
                    $("#spanProductDiscount").text("0.00");
                    $("#spanNowPrice").text(spanOriginPrice);
                    $(".selectDiscount").removeClass("active");
                    $(".selectDiscount[id='checkboxMinus']").addClass("active");
                }
            });
            
            $('#product_close').on(event_clicktouchstart,function(){
                layer.close(layer_index1);
            });
            
            $('#product_yes').on(event_clicktouchstart,function(){
                var orderstatus=$("#spanOrderStatus").text();
                if(orderstatus=="1")
                {
                    alert("已下单，不能再修改！");
                    return false;
                }
                lid=$("#spanLid").text();
                productid=$("#spanProductId").text();
                var obj=$(".selectProduct").find("li[lid='"+lid+"'][productid='"+productid+"']");
                //alert(lid);alert(productid);alert(obj.attr("is_giving"));
                var isgiving="0";
                var special="";
                var tasteids="";
                var tastememo="";
                
                tastememo=$("#Order_remark_taste").val();
                $("#productTaste").find("label[class='selectTaste btn btn-default active']").each(function(){
                    tasteids=tasteids+$(this).attr("tasteid")+"|";
                });
                obj.attr("tasteids",tasteids);
                obj.attr("tastememo",tastememo);
                //(tasteids);alert(tastememo);
                if($(".selectDiscount[id='checkboxGiving']").hasClass("active"))
                {
                    isgiving="1";
                    special=special+"赠";
                }
                //alert(special);
                obj.attr("is_giving",isgiving);
                if(obj.attr("is_retreat")=="1")
                {
                    special=special+"退";
                }
                if(obj.attr("is_print")=="1")
                {
                    special=special+"印";
                }
                if(tasteids!="" || tastememo!="")
                {
                    special=special+"味";
                }
                //alert(special);
                obj.find("span[class='special badge']").text(special);
                var nowprice=$("#spanNowPrice").text();
                obj.find("span[class='selectProductNowPrice']").text(nowprice);
                var productdiscount=$("#spanProductDiscount").text();
                obj.find("span[class='selectProductDiscount']").text(productdiscount);
                layer.close(layer_index1);
            });

            $('#pay_clearone').on(event_clicktouchstart,function(){
                var selectid=$(".edit_span_select").attr("selectid");
                var payOriginAccount=$("#payOriginAccount").text();
                var payDiscountAccount=$("#payDiscountAccount").text();
                var payMinusAccount=$("#payMinusAccount").text();
                var cancel_zero=$("#cancel_zero").hasClass("edit_span_select_zero");
                var payShouldAccount=$("#payShouldAccount").text();
                var payRealityAccount=$("#payRealityAccount").text();
                var payCashAccount=$("#payCashAccount").text();
                var payMemberAccount=$("#payMemberAccount").text();
                var payUnionAccount=$("#payUnionAccount").text();
                if(selectid=="minus")
                {
                    if(payMinusAccount=="0.00" || payMinusAccount=="0" || payMinusAccount=="00")
                    {
                        return false;
                    }
                    if(payMinusAccount.length==1)
                    {
                        $("#payMinusAccount").text("0.00");
                    }else{
                        $("#payMinusAccount").text(payMinusAccount.substr(0,payMinusAccount.length-1));
                    }
                    $("#payDiscountAccount").text("100%");
                    payOriginAccount=parseFloat($("#payOriginAccount").text().replace(",",""));
                    payDiscountAccount=parseFloat($("#payDiscountAccount").text().replace(",",""));
                    payMinusAccount=parseFloat($("#payMinusAccount").text().replace(",",""));
                    var shouldpaytemp=payOriginAccount*payDiscountAccount/100 - payMinusAccount;
                    if(shouldpaytemp>0)
                    {
                        $("#payShouldAccount").text(shouldpaytemp.toFixed(2));
                    }else{
                        $("#payShouldAccount").text("0.00");
                    }
                    if(cancel_zero)
                    {
                        payShouldAccount=$("#payShouldAccount").text();
                        payShouldAccount=payShouldAccount.substr(0,payShouldAccount.indexOf("."))+".00";
                        $("#payShouldAccount").text(payShouldAccount);
                    }
                    var changeaccount=parseFloat(payRealityAccount.replace(",",""))-parseFloat($("#payShouldAccount").text().replace(",",""));
                    if(changeaccount>0)
                    {
                        $("#payChangeAccount").text(changeaccount.toFixed(2));
                    }else{
                        $("#payChangeAccount").text("0.00");
                    }
                }else if(selectid=="pay_cash")
                {
                    if(payCashAccount=="0.00" || payCashAccount=="0" || payCashAccount=="00")
                    {
                        return false;
                    }
                    if(payCashAccount.length==1)
                    {
                        $("#payCashAccount").text("0.00");
                    }else{
                        $("#payCashAccount").text(payCashAccount.substr(0,payCashAccount.length-1));
                    }
                    $("#payRealityAccount").html((parseFloat($("#payCashAccount").text().replace(",",""))+parseFloat(payMemberAccount.replace(",",""))+parseFloat(payUnionAccount.replace(",",""))).toFixed(2));
                    
                    var changeaccount=parseFloat($("#payRealityAccount").text().replace(",",""))-parseFloat($("#payShouldAccount").text().replace(",",""));
                    if(changeaccount>0)
                    {
                        $("#payChangeAccount").text(changeaccount.toFixed(2));
                    }else{
                        $("#payChangeAccount").text("0.00");
                    }
                }else if(selectid=="pay_member_card")
                {
                    return false;
                }else if(selectid=="pay_union_card")
                {
                    if(payUnionAccount=="0.00" || payUnionAccount=="0" || payUnionAccount=="00")
                    {
                        return false;
                    }
                    if(payUnionAccount.length==1)
                    {
                        $("#payUnionAccount").text("0.00");
                    }else{
                        $("#payUnionAccount").text(payUnionAccount.substr(0,payUnionAccount.length-1));
                    }
                    $("#payRealityAccount").html((parseFloat($("#payUnionAccount").text().replace(",",""))+parseFloat(payMemberAccount.replace(",",""))+parseFloat(payCashAccount.replace(",",""))).toFixed(2));
                    
                    var changeaccount=parseFloat($("#payRealityAccount").text().replace(",",""))-parseFloat($("#payShouldAccount").text().replace(",",""));
                    if(changeaccount>0)
                    {
                        $("#payChangeAccount").text(changeaccount.toFixed(2));
                    }else{
                        $("#payChangeAccount").text("0.00");
                    }
                }
            });

            $('#pay_clearall').on(event_clicktouchstart,function(){
                var selectid=$(".edit_span_select").attr("selectid");
                var payOriginAccount=$("#payOriginAccount").text();
                var payDiscountAccount=$("#payDiscountAccount").text();
                var payMinusAccount=$("#payMinusAccount").text();
                var cancel_zero=$("#cancel_zero").hasClass("edit_span_select_zero");
                var payShouldAccount=$("#payShouldAccount").text();
                var payRealityAccount=$("#payRealityAccount").text();
                var payCashAccount=$("#payCashAccount").text();
                var payMemberAccount=$("#payMemberAccount").text();
                var payUnionAccount=$("#payUnionAccount").text();
                
                if(selectid=="discount")
                {   
                    
                        $("#payDiscountAccount").text("100%");
                        payOriginAccount=parseFloat($("#payOriginAccount").text().replace(",",""));
                        payDiscountAccount=parseFloat($("#payDiscountAccount").text().replace(",",""));
                        payMinusAccount=parseFloat($("#payMinusAccount").text().replace(",",""));
                        $("#payShouldAccount").text((payOriginAccount*payDiscountAccount/100 - payMinusAccount).toFixed(2));
                        if(cancel_zero)
                        {
                            payShouldAccount=$("#payShouldAccount").text();
                            payShouldAccount=payShouldAccount.substr(0,payShouldAccount.indexOf("."))+".00";
                            $("#payShouldAccount").text(payShouldAccount);
                        }
                        var changeaccount=parseFloat(payRealityAccount.replace(",",""))-parseFloat($("#payShouldAccount").text().replace(",",""));
                        if(changeaccount>0)
                        {
                            $("#payChangeAccount").text(changeaccount.toFixed(2));
                        }else{
                            $("#payChangeAccount").text("0.00");
                        }
                  
                }else if(selectid=="minus")
                {
                    $("#payMinusAccount").text("0.00");
                    //$("#payDiscountAccount").text("100%");
                    payOriginAccount=parseFloat($("#payOriginAccount").text().replace(",",""));
                    payDiscountAccount=parseFloat($("#payDiscountAccount").text().replace(",",""));
                    payMinusAccount=parseFloat($("#payMinusAccount").text().replace(",",""));
                    var shouldpaytemp=payOriginAccount*payDiscountAccount/100 - payMinusAccount;
                    if(shouldpaytemp>0)
                    {
                        $("#payShouldAccount").text(shouldpaytemp.toFixed(2));
                    }else{
                        $("#payShouldAccount").text("0.00");
                    }
                    if(cancel_zero)
                    {
                        payShouldAccount=$("#payShouldAccount").text();
                        payShouldAccount=payShouldAccount.substr(0,payShouldAccount.indexOf("."))+".00";
                        $("#payShouldAccount").text(payShouldAccount);
                    }
                    var changeaccount=parseFloat(payRealityAccount.replace(",",""))-parseFloat($("#payShouldAccount").text().replace(",",""));
                    if(changeaccount>0)
                    {
                        $("#payChangeAccount").text(changeaccount.toFixed(2));
                    }else{
                        $("#payChangeAccount").text("0.00");
                    }
                }else if(selectid=="pay_cash")
                {
                    var offvalue=parseFloat(payShouldAccount.replace(",","")) - parseFloat(payRealityAccount.replace(",",""))
                    if(parseFloat($("#payCashAccount").text().replace(",",""))==0)
                    {
                        if(offvalue>0)
                        {
                            $("#payCashAccount").text(offvalue.toFixed(2));
                        }
                    }else{
                        $("#payCashAccount").text("0.00");
                    }
                    $("#payRealityAccount").html((parseFloat($("#payCashAccount").text().replace(",",""))+parseFloat(payMemberAccount.replace(",",""))+parseFloat(payUnionAccount.replace(",",""))).toFixed(2));
                    
                    var changeaccount=parseFloat($("#payRealityAccount").text().replace(",",""))-parseFloat($("#payShouldAccount").text().replace(",",""));
                    if(changeaccount>0)
                    {
                        $("#payChangeAccount").text(changeaccount.toFixed(2));
                    }else{
                        $("#payChangeAccount").text("0.00");
                    }
                }else if(selectid=="pay_member_card")
                {
                    return false;
                }else if(selectid=="pay_union_card")
                {
                    var offvalue=parseFloat(payShouldAccount.replace(",","")) - parseFloat(payRealityAccount.replace(",",""))
                    if(parseFloat($("#payUnionAccount").text().replace(",",""))==0)
                    {
                        if(offvalue>0)
                        {
                            $("#payUnionAccount").text(offvalue.toFixed(2));
                        }
                    }else{
                        $("#payUnionAccount").text("0.00");
                    }
                    
                    $("#payRealityAccount").html((parseFloat($("#payUnionAccount").text().replace(",",""))+parseFloat(payMemberAccount.replace(",",""))+parseFloat(payCashAccount.replace(",",""))).toFixed(2));
                    
                    var changeaccount=parseFloat($("#payRealityAccount").text().replace(",",""))-parseFloat($("#payShouldAccount").text().replace(",",""));
                    if(changeaccount>0)
                    {
                        $("#payChangeAccount").text(changeaccount.toFixed(2));
                    }else{
                        $("#payChangeAccount").text("0.00");
                    }
                }
            });
            
            function fn_member_card_pay(){
                var nowcard=parseFloat($('#payMemberAccount').text().replace(",",""));
                var payShouldAccount=parseFloat($('#payShouldAccount').text().replace(",",""));
                var payRealityAccount=parseFloat($('#payRealityAccount').text().replace(",",""));
                $('#card_pay_span_money').text((payShouldAccount-payRealityAccount).toFixed(2));
                if(nowcard>0)
                {
                    $('#card_pay_input_money').val(nowcard);
                }else{
                    if(payShouldAccount-payRealityAccount>0)
                    {
                        $('#card_pay_input_money').val((payShouldAccount-payRealityAccount).toFixed(2));
                    }else{
                        $('#card_pay_input_money').val(0.00);
                    }
                }
                layer_index_membercard=layer.open({
                     type: 1,
                     shade: false,
                     title: false, //不显示标题
                     area: ['30%', 'auto'],
                     content: $('#membercardInfo'), //捕获的元素
                     cancel: function(index){
                         layer.close(index);
        //                        this.content.show();
        //                        layer.msg('捕获就是从页面已经存在的元素上，包裹layer的结构',{time: 5000});
                     }
                 });
            };
            
            $('#member_card_pay').on(event_clicktouchstart,function(){
                var cardmoney=$('#card_pay_input_money').val();
                var cardno=$('#card_pay_input_card').val();
                var cardpassword=$('#card_pay_input_password').val();
                //确认密码
                //如果密码正确，带回数据，并关闭
                $.get('<?php echo $this->createUrl(
                    'defaultOrder/memberCardPassword',array('companyId'=>$this->companyId));?>/passWord/'+cardpassword+"/cardno/"+cardno,
                    function(data){
                        //alert(data.msg);
                        if(data.status)
                        {
                            $('#payMemberAccount').text(cardmoney);
                            $('#payMemberAccount').attr("cardno",cardno);
                            $("#payRealityAccount").text((parseFloat($("#payCashAccount").text().replace(",",""))+parseFloat($("#payMemberAccount").text().replace(",",""))+parseFloat($("#payUnionAccount").text().replace(",",""))).toFixed(2));
                            var changeaccount=parseFloat($("#payRealityAccount").text().replace(",",""))-parseFloat($("#payShouldAccount").text().replace(",",""));
                            if(changeaccount>0)
                            {
                                $("#payChangeAccount").text(changeaccount.toFixed(2));
                            }else{
                                $("#payChangeAccount").text("0.00");
                            }
                            layer.close(layer_index_membercard);
                        }else{
                            alert("密码错误");
                            //$('#card_pay_input_password').clear();
                        }
                    },'json');               
            });
            
            $('#member_card_pay_close').on(event_clicktouchstart,function(){
                layer.close(layer_index_membercard);                
            });
            
            $('#pay_btn').on(event_clicktouchstart,function(){
                //accountManul
                //判断找零是否大于现金
                if(parseFloat($("#payChangeAccount").text().replace(",",""))> parseFloat($("#payCashAccount").text().replace(",","")))
                {
                    alert("金额有误");
                    return false;
                }
                 //改变order实收，打折等注释
                var ordermemo="";
                var payDiscountAccount=$("#payDiscountAccount").text()
                if(payDiscountAccount!="100%")
                {
                    ordermemo=ordermemo+" 折扣"+payDiscountAccount;
                }
                var payMinusAccount=$("#payMinusAccount").text()
                if(payMinusAccount!="100%")
                {
                    ordermemo=ordermemo+" 优惠"+payMinusAccount;
                }
                if($("#cancel_zero").hasClass("edit_span_select_zero"))
                {
                    ordermemo=ordermemo+" 抹零";
                }
                 //存数order order_pay 0现金，4会员卡，5银联                         
                 //写入会员卡消费记录，会员卡总额减少
                var orderid=$(".selectProduct").attr("orderid");
                var payCashAccount=$("#payCashAccount").text();
                var payShouldAccount=$("#payShouldAccount").text();
                var payOriginAccount=$("#payOriginAccount").text();
                var payRealityAccount=$("#payRealityAccount").text();
                var payMemberAccount=$("#payMemberAccount").text();
                var cardno=$("#payMemberAccount").attr("cardno");
                var payUnionAccount=$("#payUnionAccount").text();
                if(parseFloat(payRealityAccount.replace(",","")) < parseFloat(payShouldAccount.replace(",","")))
                {
                    alert("收款不够");
                    return false;
                }
                layer.close(layer_index2);
                bootbox.confirm("<?php echo yii::t('app','确定结单吗？');?>", function(result) {                    
                    if(result){
                        //ordermemo="11";
                        var url="<?php echo $this->createUrl('defaultOrder/orderAccount',array('companyId'=>$this->companyId));?>/orderid/"+orderid+"/orderstatus/4/cardno/"+cardno;
                        var sendjson='paycashaccount='+payCashAccount+
                                    '&paymemberaccount='+payMemberAccount+
                                    '&payunionaccount='+payUnionAccount+
                                    '&ordermemo='+ordermemo+
                                    '&payshouldaccount='+payShouldAccount+
                                    '&payoriginaccount='+payOriginAccount;
                            //alert(sendjson);
                            //alert(url);
                        $.ajax({
                            url:url,
                            type:'POST',
                            data:sendjson,
                            async:false,
                            dataType: "json",
                            success:function(msg){
                                var data=msg;
                                if(data.status){
                                    layer.close(layer_index2);
                                    alert(data.msg);
                                    //刷新座位页面
                                    
                                }else{
                                    //layer.close(layer_index2);
                                    alert(data.msg);
                                    layer_index2=layer.open({
                                        type: 1,
                                        shade: false,
                                        title: false, //不显示标题
                                        area: ['65%', '60%'],
                                        content: $('#accountbox'),//$('#productInfo'), //捕获的元素
                                        cancel: function(index){
                                            layer.close(index);
                           //                        this.content.show();
                           //                        layer.msg('捕获就是从页面已经存在的元素上，包裹layer的结构',{time: 5000});
                                        }
                                    });
                                }
                            },
                            error: function(msg){
                                //layer.close(index);
                                alert("结算失败2！");
                                layer_index2=layer.open({
                                    type: 1,
                                    shade: false,
                                    title: false, //不显示标题
                                    area: ['65%', '60%'],
                                    content: $('#accountbox'),//$('#productInfo'), //捕获的元素
                                    cancel: function(index){
                                        layer.close(index);
                       //                        this.content.show();
                       //                        layer.msg('捕获就是从页面已经存在的元素上，包裹layer的结构',{time: 5000});
                                    }
                                });
                            }
                        });
                         //返回座位列表页面
                    }
                });
            });
            
            $('#other-btn').on(event_clicktouchstart,function(){
                 bootbox.confirm("<?php echo yii::t('app','你确定切换到其他支付方式吗？');?>", function(result) {
                        if(result){
                                openaccount('0');
                        }
                 });
            });

            $('.selectDiscount').on(event_clicktouchstart,function(){
                var spanOriginPrice=$("#spanOriginPrice").text();
                var id=$(this).attr('id');
                var discountOrig=$("#spanProductDiscountOrig").text();
                var nowpriceOrig=$("#spanNowPriceOrig").text();
                //alert(spanOriginPrice);alert(discountOrig);alert(nowpriceOrig);
                $(".selectDiscount").removeClass("active");
                //$(".selectDiscount[id='checkboxGiving']").addClass("active");
                if(id=="checkboxDiscount")
                {
                    if(discountOrig.lastIndexOf("%")>="0")
                    {
                        $("#spanProductDiscount").text(discountOrig);
                        $("#spanNowPrice").text(nowpriceOrig);
                    }else{
                        $("#spanProductDiscount").text("100%");
                        $("#spanNowPrice").text(spanOriginPrice);
                    }
                }else if(id=="checkboxMinus"){
                    //alert(discountOrig.lastIndexOf("%"));
                    if(discountOrig.lastIndexOf("%")>="0")
                    {
                        $("#spanProductDiscount").text("0.00");
                        $("#spanNowPrice").text(spanOriginPrice);
                    }else{
                        $("#spanProductDiscount").text(discountOrig);
                        $("#spanNowPrice").text(nowpriceOrig);
                    }                    
                }else{                    
                    $("#spanProductDiscount").text(spanOriginPrice);
                    $("#spanNowPrice").text("0.00");
                }
            });
        //库存提示
        function sell_off(do_data) {
            //alert(do_data);
            var data = eval('(' + do_data + ')');
            	//for(var item in data.do_data){
            	for(var item in data){
                    $('div.blockCategory[product-id="'+data[item].product_id+'"]').attr('store',data[item].num);
                    if(parseInt(data[item].num)==0){
                    	$('div.blockCategory[product-id="'+data[item].product_id+'"]').find('.sellOff').remove();
                    	var str = '<div class="sellOff sellOut"><?php echo yii::t('app',"已售完");?></div>';
                    	$('div.blockCategory[product-id="'+data[item].product_id+'"]').find('a').append(str);
                    }else if(parseInt(data[item].num) > 0){
                    	$('div.blockCategory[product-id="'+data[item].product_id+'"]').find('.sellOff').remove();
                    	var str = '<div class="sellOff">仅剩<br/>'+data[item].num+'份</div>';
                    	$('div.blockCategory[product-id="'+data[item].product_id+'"]').find('a').append(str);
                    }else{
                    	$('div.blockCategory[product-id="'+data[item].product_id+'"]').find('.sellOff').remove();
                    }
            	}             
       }                
	</script>

