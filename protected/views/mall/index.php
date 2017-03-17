<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('自助点单');
?>
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/style.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/index.css">
<style type="text/css">
.layui-layer-content img{
	width:100%;
	height:100%;
}
.boll {
	width: 15px;
	height: 15px;
	background-color: #FF5151;
	position: absolute;
	-moz-border-radius: 15px;
	-webkit-border-radius: 15px;
	border-radius: 15px;
	z-index:5;
	display:none;
}
.none{
	display:none;
}

</style>
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/Adaptive.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/parabola.js"></script>
<div class="nav-lf">
<ul id="nav">
  
</ul>
</div>


<div id="container" class="container">
<!-- 特价优惠  -->

<!-- end特价优惠  -->


   
</div>

<footer>
	<div class="ft-lt">
        <p>合计:<span id="total" class="total">0.00元</span><span class="nm">(<label class="share"></label>份)</span></p>
    </div>
    <?php if($this->type==2):?>
	    <?php if($start&&$start['fee_price']):?>
		    <div class="ft-rt start" start-price="<?php echo $start['fee_price'];?>">
		    	<p><a href="<?php echo $this->createUrl('/mall/checkOrder',array('companyId'=>$this->companyId,'type'=>$this->type));?>">选好了</a></p>
		    </div>
		    <div class="ft-rt no-start" style="background:#6A706E" start-price="<?php echo $start['fee_price'];?>">
		    	<p><?php echo (int)$start['fee_price'];?>元起送</p>
		    </div>
	    <?php else:?>
		    <div class="ft-rt" start-price="0">
		    	<p><a href="<?php echo $this->createUrl('/mall/checkOrder',array('companyId'=>$this->companyId,'type'=>$this->type));?>">选好了</a></p>
		    </div>
	    <?php endif;?>
     <?php else:?>
     <div class="ft-rt">
    	<p><a href="<?php echo $this->createUrl('/mall/checkOrder',array('companyId'=>$this->companyId,'type'=>$this->type));?>">选好了</a></p>
    </div>
    <?php endif;?>
    <div class="clear"></div>
</footer>

<div id="boll" class="boll"></div>

<script> 
function setTotal(){ 
    var s=0;
    var v=0;
    var n=0;
    <!--计算总额--> 
    $(".lt-rt").each(function(){ 
    	s+=parseInt($(this).find('input[class*=result]').val())*parseFloat($(this).siblings().find('span[class*=price]').text()); 
    });

    <!--计算菜种-->
    $('li').each(function(){
    	var nIn = $(this).find("a").attr("href");
	    $(nIn).find("input[type='text']").each(function() {
	    	if(parseInt($(this).val()) > 0){
	    		n++;
	    	}
	    });
	    if(n>0){
    		$(this).find("b").html(n).show();		
	    }else{
	    	$(this).find("b").hide();		
	    }
	    n = 0;	
    });

    <!--计算总份数-->
    $("input[type='text']").each(function(){
    	v += parseInt($(this).val());
    });
    
    $(".share").html(v);
    $("#total").html(s.toFixed(2)); 
    <?php if($this->type==2):?>
    var startPrice = $('.ft-rt').attr('start-price');
    var total = $("#total").html();
    if(parseFloat(startPrice) > parseFloat(total)){
    	$('.no-start').removeClass('none');
    	$('.start').addClass('none');
    }else{
    	$('.no-start').addClass('none');
    	$('.start').removeClass('none');
    }
    <?php endif;?>
} 
function getProduct(){
	layer.load(2);
	var timestamp=new Date().getTime()
    var random = ''+timestamp + parseInt(Math.random()*899+100)+'';
	$.ajax({
		url:'<?php echo $this->createUrl('/mall/getProduct',array('companyId'=>$this->companyId,'userId'=>$userId));?>',
		data:{random:random},
		dataType:'json',
		timeout:'30000',
		success:function(data){
			var categorys = data.categorys;
			var promotions = data.promotions;
			var products = data.products;
			var productSets = data.productSets;
			var navLi = '';
			var promotionStr = '';
			var productStr = '';
			var productSetStr = '';
			
			if(promotions.length > 0){
				navLi += '<li class="current"><a href="#st-1">优惠</a><b></b></li>';
				promotionStr +='<div class="section" id="st-1"><div class="prt-title">优惠专区</div>';
				for(var i=0; i<promotions.length; i++){
					var promotion = promotions[i];
					var promotionProduct = promotion['product'];
					promotionStr +='<div class="prt-lt"><div class="lt-lt"><img src="'+promotionProduct.main_picture+'"></div>';
					promotionStr +='<div class="lt-ct"><p><span>'+ promotionProduct.product_name +'</span> <span>';
					if(promotionProduct.spicy==1){
						promotionStr +='<img src="<?php echo $baseUrl;?>/img/mall/index/spicy1.png" style="width:15px;height:20px;"/>';
					}else if(promotionProduct.spicy==2){
						promotionStr +='<img src="<?php echo $baseUrl;?>/img/mall/index/spicy2.png" style="width:15px;height:20px;"/>';
					}else if(promotionProduct.spicy==3){
						promotionStr +='<img src="<?php echo $baseUrl;?>/img/mall/index/spicy3.png" style="width:15px;height:20px;"/></span></p>';
					}
					promotionStr +='<p class="pr">¥<span class="price">'+promotionProduct.price+'</span>';
					if(promotionProduct.price != promotionProduct.original_price){
						promotionStr +='<span class="oprice"><strike>¥'+promotionProduct.original_price+'</strike></span>';
					}
             		promotionStr +='</p></div>';
             		if(parseInt(promotionProduct.num)){
             				promotionStr +='<div class="lt-rt"><div class="minus">-</div><input type="text" class="result" product-id="'+promotionProduct.lid+'" promote-id="'+promotion.normal_promotion_id+'" to-group="'+promotion.to_group+'" store-number="'+promotion.all_order_num+'" readonly value="'+promotionProduct.num+'">';
            				promotionStr +='<div class="add">+</div><div class="clear"></div></div></div>';
             		}else{
             			if(parseInt(promotionProduct.store_number) != 0){
             				promotionStr +='<div class="lt-rt"><div class="minus zero">-</div><input type="text" class="result zero" product-id="'+promotionProduct.lid+'" promote-id="'+promotion.normal_promotion_id+'" to-group="'+promotion.to_group+'" store-number="'+promotion.all_order_num+'" readonly value="0">';
            				promotionStr +='<div class="add">+</div><div class="clear"></div><div class="sale-out zero"> 已售罄  </div></div></div>';
             			}else{
             				promotionStr +='<div class="lt-rt"><div class="minus zero">-</div><input type="text" class="result zero" product-id="'+promotionProduct.lid+'" promote-id="'+promotion.normal_promotion_id+'" to-group="'+promotion.to_group+'" store-number="'+promotion.all_order_num+'" readonly value="0">';
            				promotionStr +='<div class="add zero">+</div><div class="clear"></div><div class="sale-out"> 已售罄  </div></div></div>';
             			}
             		}
				}
				promotionStr +='</div>';
			}
			
			for(var k in categorys){
				var category = categorys[k];
				if((k==0) && (promotions.length==0)){
					navLi += '<li class="current"><a href="#st' + category.lid + '">' + category.category_name + '</a><b></b></li>';
				}else{
					navLi += '<li class=""><a href="#st' + category.lid + '">' + category.category_name + '</a><b></b></li>';
				}
			}
			
			for(var p in products){
				var product = products[p];
				productStr +='<div class="section" id="st'+ product.lid  +'"><div class="prt-title">' + product.category_name + '</div>';
				for(var pp in product.product_list){
					var pProduct = product.product_list[pp];
					productStr +='<div class="prt-lt"><div class="lt-lt"><img src="'+pProduct.main_picture+'"></div>';
					productStr +='<div class="lt-ct"><p><span>'+ pProduct.product_name +'</span> <span>';
					if(pProduct.spicy==1){
						productStr +='<img src="<?php echo $baseUrl;?>/img/mall/index/spicy1.png" style="width:15px;height:20px;"/>';
					}else if(pProduct.spicy==2){
						productStr +='<img src="<?php echo $baseUrl;?>/img/mall/index/spicy2.png" style="width:15px;height:20px;"/>';
					}else if(pProduct.spicy==3){
						productStr +='<img src="<?php echo $baseUrl;?>/img/mall/index/spicy3.png" style="width:15px;height:20px;"/></span></p>';
					}
					productStr +='<p class="pr">¥<span class="price">'+pProduct.original_price+'</span>';
         			productStr +='</p></div>';
         			if(parseInt(pProduct.num)){
         				productStr +='<div class="lt-rt"><div class="minus">-</div><input type="text" class="result" is-set="0" product-id="'+pProduct.lid+'" promote-id="-1" to-group="-1" store-number="'+pProduct.store_number+'" readonly value="'+pProduct.num+'">';
        				productStr +='<div class="add">+</div><div class="clear"></div></div><div class="clear"></div></div>';
         			}else{
         				if(parseInt(pProduct.store_number) != 0){
	         				productStr +='<div class="lt-rt"><div class="minus zero">-</div><input type="text" class="result zero" is-set="0" product-id="'+pProduct.lid+'" promote-id="-1" to-group="-1" store-number="'+pProduct.store_number+'" readonly value="0">';
	        				productStr +='<div class="add">+</div><div class="clear"></div><div class="sale-out zero"> 已售罄  </div></div><div class="clear"></div></div>';
         				}else{
         					productStr +='<div class="lt-rt"><div class="minus zero">-</div><input type="text" class="result zero" is-set="0" product-id="'+pProduct.lid+'" promote-id="-1" to-group="-1" store-number="'+pProduct.store_number+'" readonly value="0">';
	        				productStr +='<div class="add zero">+</div><div class="clear"></div><div class="sale-out"> 已售罄  </div></div><div class="clear"></div></div>';
         				}
         			}
				}
				productStr +='</div>';
			}
			if(productSets.length > 0){
				navLi += '<li class=""><a href="#st-set">套餐</a><b></b></li>';
				productSetStr +='<div class="section" id="st-set"><div class="prt-title">套餐</div>';
				for(var q in productSets){
					var pProductSet = productSets[q];
					var pDetail = pProductSet['detail'];
					productSetStr +='<div class="prt-lt"><div class="lt-lt"><img src="'+pProductSet.main_picture+'"></div>';
					productSetStr +='<div class="lt-ct"><p><span>'+ pProductSet.set_name +'</span> <span>';
					if(pProductSet.spicy==1){
						productSetStr +='<img src="<?php echo $baseUrl;?>/img/mall/index/spicy1.png" style="width:15px;height:20px;"/>';
					}else if(pProductSet.spicy==2){
						productSetStr +='<img src="<?php echo $baseUrl;?>/img/mall/index/spicy2.png" style="width:15px;height:20px;"/>';
					}else if(pProductSet.spicy==3){
						productSetStr +='<img src="<?php echo $baseUrl;?>/img/mall/index/spicy3.png" style="width:15px;height:20px;"/></span></p>';
					}
					productSetStr +='<p class="pr">¥<span class="price">'+pProductSet.set_price+'</span>';
					productSetStr +='</p></div>';
         			if(parseInt(pProductSet.num)){
         				productSetStr +='<div class="lt-rt"><div class="minus">-</div><input type="text" class="result" is-set="1" product-id="'+pProductSet.lid+'" promote-id="-1" to-group="-1" store-number="'+pProductSet.store_number+'" readonly value="'+pProductSet.num+'">';
         				productSetStr +='<div class="add">+</div><div class="clear"></div></div><div class="clear"></div>';
         			}else{
         				if(parseInt(pProductSet.store_number) != 0){
         					productSetStr +='<div class="lt-rt"><div class="minus zero">-</div><input type="text" class="result zero" is-set="1" product-id="'+pProductSet.lid+'" promote-id="-1" to-group="-1" store-number="'+pProductSet.store_number+'" readonly value="0">';
         					productSetStr +='<div class="add">+</div><div class="clear"></div><div class="sale-out zero"> 已售罄  </div></div><div class="clear"></div>';
         				}else{
         					productSetStr +='<div class="lt-rt"><div class="minus zero">-</div><input type="text" class="result zero" is-set="1" product-id="'+pProductSet.lid+'" promote-id="-1" to-group="-1" store-number="'+pProductSet.store_number+'" readonly value="0">';
         					productSetStr +='<div class="add zero">+</div><div class="clear"></div><div class="sale-out"> 已售罄  </div></div><div class="clear"></div>';
         				}
         			}
         			// 套餐详情
         			productSetStr +='<div class="tips">';
         			for(var ps=0; ps<pDetail.length; ps++){
             			var detail = pDetail[ps]
             			for(var ps1=0;ps1<detail.length;ps1++){
							var detailItem = detail[ps1];
							if(detailItem['is_select']=='1'){
								productSetStr +=detailItem['product_name']+'x'+detailItem['number']+' ';
							}
                 		}
             		}
         			productSetStr +='</div>';
         			productSetStr +='</div>';
				}
				productSetStr +='</div>';
			}
			$('#nav').append(navLi);
			$('#container').append(promotionStr + productStr + productSetStr);
			setTotal();
			layer.closeAll('loading');
		},
	});
}
$(document).ready(function(){ 
	var i = 0;
	var j = 0;
	window.load = getProduct(); 
	
    $('#nav').on('click','li',function(){
    	var _this = $(this);
    	var href = _this.find('a').attr('href');
        $('#nav').find('li').removeClass('current');
        $(href).scrollTop();
        _this.addClass('current');
    });
    $('#container').scroll(function(){
        var ptHeight = $('.prt-title').outerHeight();
    	$('.prt-title').removeClass('top');
        $('.section').each(function(){
        	var id = $(this).attr('id');
            var top = $(this).offset().top;
            var height = $(this).outerHeight();
            if(top < ptHeight && (parseInt(top) + parseInt(height) - parseInt(ptHeight)) >= 0){
                if(top!=0){
                	$(this).find('.prt-title').addClass('top');
                }
	    		$('a[href=#'+id+']').parents('ul').find('li').removeClass('current');
	        	$('a[href=#'+id+']').parent('li').addClass('current');
	        	return false;
            }
        });
       
    });

    $("body").on('touchstart','.add',function(){
    	var height = $('body').height();
    	var top = $(this).offset().top;
    	var left = $(this).offset().left;
    	
        var t=$(this).parent().find('input[class*=result]');
        var productId = t.attr('product-id');
        var promoteId = t.attr('promote-id');
        var toGroup = t.attr('to-group');
        var isSet = t.attr('is-set');
        
        var timestamp=new Date().getTime()
        var random = ''+timestamp + parseInt(Math.random()*899+100)+'';
        $.ajax({
        	url:'<?php echo $this->createUrl('/mall/addCart',array('companyId'=>$this->companyId));?>',
        	data:{productId:productId,promoteId:promoteId,isSet:isSet,toGroup:toGroup,random:random},
        	success:function(msg){
        		if(msg.status){
        			 t.val(parseInt(t.val())+1);
			        if(parseInt(t.val()) > 0){
			            t.siblings(".minus").removeClass('zero');
			            t.removeClass('zero');
			        }
			        setTotal();
			        //动画
			        var str = '<div id="boll'+i+'" class="boll"></div>';
			    	$('body').append(str);
			    	$('#boll'+i).css({top:top,left:left,display:"block"});
			    	var bool = new Parabola({
						el: "#boll"+i,
						offset: [-left+10, height-top-25],
						curvature: 0.005,
						duration: 1000,
						callback:function(){
							$('#boll'+j).css('display','none');
							j++;
						},
						stepCallback:function(x,y){
						}
					});
					
					bool.start();
					i++;
        		}else{
        			$('#boll'+(i-1)).css('display','none');
        			layer.msg(msg.msg);
        		}
        	},
        	dataType:'json'
        });
    });
     
    $("body").on('touchstart','.minus',function(){ 
        var t=$(this).parent().find('input[class*=result]');
        var productId = t.attr('product-id');
        var promoteId = t.attr('promote-id');
        var toGroup = t.attr('to-group');
        var isSet = t.attr('is-set');
        var storeNum = t.attr('store-number');
        
        var timestamp=new Date().getTime()
        var random = ''+timestamp + parseInt(Math.random()*899+100)+'';
        $.ajax({
        	url:'<?php echo $this->createUrl('/mall/deleteCart',array('companyId'=>$this->companyId));?>',
        	data:{productId:productId,promoteId:promoteId,isSet:isSet,toGroup:toGroup,random:random},
        	success:function(msg){
        		if(msg.status){
    			  if(parseInt(t.val())==1){
			          t.siblings(".minus").addClass('zero');
			          t.addClass('zero');
			          if(parseInt(storeNum)==0){
			          	t.siblings(".add").addClass('zero');
			          	t.siblings(".sale-out").removeClass('zero');
			          }
			       }
			       t.val(parseInt(t.val())-1);
			       if(parseInt(t.val()) < 0){ 
			           t.val(0); 
			   	    } 
			    	setTotal(); 
        		}else{
        			layer.msg(msg.msg);
        		}
        	},
        	dataType:'json'
        });
   });
    $("body").on('click','.lt-lt',function(){
    	var str = $(this).html();
    	layer.open({
		    type: 1,
		    title: false,
		    closeBtn: 0,
		    area: ['100%', 'auto'],
		    skin: 'layui-layer-nobg', //没有背景色
		    shadeClose: true,
		    content: str
		});
		$('.layui-layer-content').css('overflow','hidden');
    });
});
</script> 