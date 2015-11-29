<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('订单');
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/reset.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/user.css">
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/jquery-1.9.1.min.js"></script>
<section class="prolist">
	<ul class="orderList">
		<li class="mar-bm-2"><div class="item col-4">时间</div><div class="item col-2">类别</div><div class="item col-2">金额</div><div class="item col-2">状态</div><div class="clear"></div></li>
		<?php foreach($models as $model):?>
		<li class="mar-bm-1"><div class="item col-4"><?php echo $model['create_at'];?></div><div class="item col-2"><?php if($model['order_type']==1) echo '堂吃';else echo '外卖';?></div><div class="item col-2"><?php echo $model['should_total'];?>元</div><div class="item col-2"><?php if($model['order_status'] < 3) echo '<span class="ispay">待支付</span>';elseif($model['order_status'] == 3) echo '已支付';else echo '已完成';?></div><div class="clear"></div></li>
		<?php endforeach;?>
	</ul>
</section>