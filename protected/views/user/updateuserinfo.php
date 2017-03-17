<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('完善个人资料');
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/reset.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/common.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/members.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/wechat_css/mobiscroll.min.css">

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/weui.min.css">

<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/wechat_js/mobiscroll.min.js"></script>
<style>
.weui_select {
    padding-left: 0px!important;
    height: 1.41176471em!important;
  
}
.bttn_small {
	padding: 4px 10px;
	font-size: 16px;
        background-color: #74d2d4;
        color: #000;
}
.btn_birth{
       font-size: 22px; 
       margin-top: 15px;
        margin-bottom: 15px;
        color:#04BE02;
}
.update_confirm{
    font-size: 22px; 
       margin-top: 15px;
        margin-bottom: 15px;
}
.birth_box{
    text-align: center;
    margin-top: 25px;    
    font-size: 22px;
    border-bottom: 1px solid #CFCFCF;
    padding-bottom: 25px;
}
</style>

<body class="add_address bg_lgrey2">
<form id="user-info" action="<?php echo Yii::app()->createUrl('/user/saveUserInfo',array('companyId'=>$this->companyId));?>" method="post" >

<div class="page cell">
	<div class="weui_cells_title">完善个人资料</div>
    <div class="weui_cells weui_cells_form">
        <div class="weui_cell">
            <div class="weui_cell_hd"><label class="weui_label">姓名</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input" id="name" name="user[user_name]" type="text" placeholder="请输入姓名" value="<?php echo $user['user_name'];?>"/>
            </div>
        </div>
        <div class="weui_cell">
            <div class="weui_cell_hd"><label class="weui_label">性别</label></div>
            <div id='sex-val-box' class="weui_cell_bd weui_cell_primary " >
            
            <select class="weui_select" id="sex" name="user[sex]" >
                <option value="0">保密</option>
                <option value="1">男</option>
                <option value="2">女</option>
            </select>
            </div>
        </div>
       <div class="weui_cell">
            <div class="weui_cell_hd"><label class="weui_label">手机</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input" <?php echo $user['mobile_num']?'readonly="readonly"':'' ?>  id="mobile"  name="user[mobile_num]" type="tel" placeholder="请输入联系方式" value="<?php echo $user['mobile_num'];?>"/>
                <input type='hidden' id='old_phone' value='<?php echo $user['mobile_num'];?>'/>
            </div>
            <div class="weui_cell_ft sentMessage" style="display: <?php echo $user['mobile_num']?'none':'block';?>;font-size:100%;padding-left:5px;border-left:1px solid #888;">
                <span id="countSpan">获取验证码</span>
                <span id="countdown"></span>
            </div>
            <div class="weui_cell_ft revise" style="display: <?php echo $user['mobile_num']?'block':'none';?>; font-size:100%;padding-left:5px;">
                <button class=" bttn_small" id="" type="button">修改</button>
            </div>
        </div>
        <div class="weui_cell code_box" style="display:<?php echo $user['mobile_num']?'none':'flex'?>">
            <div class="weui_cell_hd"><label class="weui_label">验证码</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input" id="verifyCode"  show=""  name="verifyCode" type="tel" placeholder="请输入验证码" value=""/>
            </div>
        </div>
        <div class="weui_cell">
            <div class="weui_cell_hd"><label for="" class="weui_label">生日</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                
                <input class="weui_input" <?php echo $user['user_birthday']?'readonly="readonly"':'' ?> data="<?php echo $user['user_birthday']?'true':'false'; ?>" id="birthday" name="user[user_birthday]" type="text" value="<?php echo date('Y-m-d',strtotime($user['user_birthday']));?>" placeholder="请填写生日" />
            </div>
        </div>
    </div>
</div>
<div class="bttnbar">
	<button class="bttn_black2 bttn_large backUrl" type="button">取消</button>
	<button class="bttn_black2 bttn_large" type="submit" onclick="return validate()">保存</button>
</div>
<input type="hidden" name="user[lid]" value="<?php echo $user['lid'];?>"/>
</form>
<div class="weui_dialog_alert" id="dialog2" style="display: none;">
	<div class="weui_mask"></div>
	<div class="weui_dialog">
	    <div class="weui_dialog_hd"><strong class="weui_dialog_title">提示</strong></div>
	    <div class="weui_dialog_bd"></div>
	    <div class="weui_dialog_ft">
	        <a href="javascript:;" id="confirm" class="weui_btn_dialog primary">确定</a>
	    </div>
	</div>
</div>

<div id="no_revise" style="z-index:90;position: absolute; display: none;">                     
        <div class="weui_dialog" >                           
            
            <div  class="birth_box">
                亲，生日不能修改哦
            </div> 
            <div class="btn_ewm_out btn_birth" >    
                <a href="javascript:;" class=" btn_birth">算你狠</a>
            </div>
        </div>
</div>  
<div class="sp-lightbox1"  style="z-index:50;position: absolute;top:0; left: 0; height: 100%;width: 100%;background: rgba(0, 0, 0, .6);cursor: -webkit-zoom-out;cursor: -moz-zoom-out;cursor: zoom-out;display: none;"></div>

<div id="revise_birth" style="z-index:90;position: absolute; display: none;">                     
        <div class="weui_dialog" >                           
            
            <div  class="birth_box">
                亲，生日提交后不能修改哦！
            </div> 
            <div class="btn_ewm_out update_confirm" >    
                <a href="javascript:;" class="btn-update" style="color:red;display: inline-block;margin-right: 70px;">修改</a>
                <a href="javascript:;" class="btn-confirm" style="color:#04BE02">提交</a>
            </div>           
        </div>
</div>  


</body>
<script type="text/javascript">
  function validate() {
        if($('#name').val() == ''){
        	$('#dialog2').find('.weui_dialog_bd').html('请填写姓名！');
            $('#dialog2').show();
            return false;}
        if($('#mobile').val() == ''){
            $('#dialog2').find('.weui_dialog_bd').html('请填写联系方式！');
            $('#dialog2').show();
            return false;}
        
        var verify_flag = false;
        if( $(".code_box").css("display")=='flex' ){
            verify_flag = true;
            if( $("#verifyCode").val() == '' ){
                $('#dialog2').find('.weui_dialog_bd').html('请填写验证码！');
                $('#dialog2').show();
                return false;
            }
        }
       
        if($('#birthday').val() == ''){
            $('#dialog2').find('.weui_dialog_bd').html('请填写生日！');
            $('#dialog2').show();
            return false;
        }
        
        if(verify_flag){
            var success = true;
            var verifyCode = $('#verifyCode').val();
            var mobile = $('#mobile').val()
            $.ajax({
                    url:'<?php echo $this->createUrl('/user/ajaxVerifyCode',array('companyId'=>$this->companyId));?>',
                    data:{mobile:mobile,code:verifyCode},
                    async: false,
                    success:function(msg){
                            if(!parseInt(msg)){
                                    $('#dialog2').find('.weui_dialog_bd').html('验证码错误');
                            $('#dialog2').show();
                            success = false;
                             
                            }
                    }
            });
           if(success == false){
              return false; 
           }
            
        }
     
        if($("#birthday").attr("data")=='true'){
           return true;
        }else{
             $("#revise_birth").css("display","block");
              $('.sp-lightbox1').css('display','block');
              return false;           
        }
    }
    
   var countdown = 60;
   function setTime(){
    	var obj = $('#countdown');
    	if (countdown == 0) { 
			obj.removeClass("disable");    
			obj.html(''); 
			countdown = 60; 
			return;
		} else { 
			obj.html('('+countdown+')'); 
			countdown--; 
		} 
		setTimeout(function(){ 
			setTime();
		},1000);
    }
$('document').ready(function(){
    $('.sentMessage').click(function(){
            if($(this).hasClass('disable')){
                    return;
            }

        var mobile = $('#mobile').val();

        var myreg = /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/; 
        if(!myreg.test(mobile)){ 
            alert('请输入有效的手机号码！'); 
            return false; 
        }
        if(mobile == $("#old_phone").val()){
            alert('该手机号码已存在！'); 
            return false; 
        }
        $('.sentMessage').addClass('disable');
        $.ajax({
                url:'<?php echo $this->createUrl('/user/ajaxSentMessage',array('companyId'=>$this->companyId));?>',
                data:{mobile:mobile},
                success:function(msg){
                        if(!parseInt(msg)){
                                $('#dialog2').find('.weui_dialog_bd').html('发送失败!'+msg);
                                $('#dialog2').show();
                        }else{
                                setTime();
                        }
                }
        });
    });
    	
    $('#confirm').click(function(){
            $('#dialog2').hide();
    });
    $('.backUrl').click(function(){
            history.go(-1);
    });
       
    $('.bttn_small').click(function(){
               $('#mobile').removeAttr('readonly');
               $('.sentMessage').css('display','block');
               $('.revise').css('display','none'); 
               $('.code_box').css('display','flex');              
               });
    $("#birthday[data = 'false']").mobiscroll().date({
        theme: 'android-holo-light',
        lang: 'zh',
        display: 'center',
    });
    $("#birthday[data = 'true']").click(function(){
        $('#no_revise').css('display','block');
         $('.sp-lightbox1').css('display','block');
            });
    $('.btn_birth').on('click', function () {
                $('#no_revise').hide();
                $(".sp-lightbox1").hide();
            });  
    $('.btn-update').on('click', function () {
                $('#revise_birth').hide();
                $(".sp-lightbox1").hide();
    });  
    $('.btn-confirm').on('click', function () {
                $('#user-info').submit();
               
    });  
       
    });
</script>
