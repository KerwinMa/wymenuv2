<?php
/* @var $this ProductController */
	$parentCategorys = ProductCategory::getCategorys($this->companyId);	
	$result = ProductClass::getCartInfo($this->companyId,$siteNoId);	
	$resArr = explode(':',$result);
	$price = $resArr[0];
	$nums = $resArr[1];
//	var_dump($parentCategorys);exit;
?>
<?php 
	$baseUrl = Yii::app()->baseUrl;
?>
<link rel="stylesheet" type="text/css"  href="<?php echo $baseUrl.'/css/product/categorypad.css';?>" />
<div class="fixed-top">
  <div class="top-left"> 
      
  	<div class="top-left-right">            
	<span class="category-all"><a href="javascript:;">分类</a></span>&nbsp;&nbsp;<span class="category-all-name"></span>
	</div>
  </div>
    
    <div class="padsetting"></div>
  
   <!--<div class="top-middle">
   	<button id="updatePadOrder">下单并打印</button>
   </div>-->
  <div class="top-right">
	  <div class="shoppingCart">
	     <div class="total-num num-circel"><?php echo $nums;?></div>
	  </div>
	  <div class="total-price"><?php if(Yii::app()->language=='jp') echo (int)Money::priceFormat($price); else echo Money::priceFormat($price);?></div>
          <div class="top-right-button">
                <button id="infoPadOrder"><?php echo yii::t('app','订单详情...');?></button>
           </div>
  	<div class="clear"></div>
  </div>
</div>
<div class="category-level1">
	<?php if($parentCategorys):?>
		<?php foreach($parentCategorys as $categorys):?>
		<div class="category-level1-item" category-id="<?php echo $categorys['lid'];?>" category-name="<?php echo $categorys['category_name'];?>"><div class="pad-productbuy"><div class="inmiddle" style="text-align:center;height:1.5em;"><?php echo $categorys['category_name'];?></div></div><img src="<?php echo $categorys['main_picture'];?>"/></div>
		<?php endforeach;?>
	<?php endif;?>
	<div class="clear"></div>
</div>
<?php if(Yii::app()->language=='jp'):?>
<input type="hidden"  name="language" value="1"  />
<?php else:?>
<input type="hidden"  name="language" value="0"  />
<?php endif;?>
