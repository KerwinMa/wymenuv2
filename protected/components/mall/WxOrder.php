<?php 
/**
 * 
 * 
 * 微信端订单类
 * //堂吃必须有siteId
 *$type 0 临时座 1 堂吃 2 外卖 3 预约
 *$normalPromotionIds 菜品普通优惠id
 *
 * 
 */
class WxOrder
{
	public $dpid;
	public $userId;
	public $user;
	public $siteId;
	public $type;
	public $number;
	public $cartNumber = 0;
	public $isTemp = 0;
	public $seatingFee = 0;
	public $packingFee = 0;
	public $freightFee = 0;
	public $cart = array();
	public $normalPromotionIds = array();
	public $tastes = array();//原始产品口味
	public $productTastes = array();//处理后的产品口味
	public $setDetail = array();  // 套餐详情 set_id - product_id - price
	public $productSetDetail = array();// 处理套餐详情 array(product_id=>array(set_id,product_id,price))
	public $order = false;
	
	public function __construct($dpid,$user,$siteId = null,$type = 1,$number = 1,$productSet = array(),$tastes = array()){
		$this->dpid = $dpid;
		$this->userId = $user['lid'];
		$this->user = $user;
		$this->siteId = $siteId;
		$this->type = $type;
		$this->number = $number;
		$this->tastes = $tastes;
		$this->setDetail = $productSet;
		$this->getCart();
		$this->dealTastes();
		$this->dealProductSet();
		if($this->type==1){
			$this->isTemp = 0;
			$this->getSite();
			$this->getSeatingFee();
		}elseif($this->type==2){
			$this->isTemp = 1;
			$this->orderOpenSite();
			$this->getPackingFee();
			$this->getFreightFee();
		}elseif($this->type==3){
			$this->isTemp = 1;
			$this->orderOpenSite();
			$this->getPackingFee();
		}else{
			$this->isTemp = 1;
			$this->orderOpenSite();
		}
	}
	//获取购物车信息
	public function getCart(){
		$sql = 'select t.dpid,t.product_id,t.is_set,t.num,t.promotion_id,t.to_group,t1.product_name,t1.main_picture,t1.original_price from nb_cart t,nb_product t1 where t.product_id=t1.lid and t.dpid=t1.dpid and t.dpid=:dpid and t.user_id=:userId and t.is_set=0';
		$sql .= ' union select t.dpid,t.product_id,t.is_set,t.num,t.promotion_id,t.to_group,t1.set_name as product_name,t1.main_picture,t1.set_price as original_price from nb_cart t,nb_product_set t1 where t.product_id=t1.lid and t.dpid=t1.dpid and t.dpid=:dpid and t.user_id=:userId and t.is_set=1';
		$results = Yii::app()->db->createCommand($sql)
				  ->bindValue(':dpid',$this->dpid)
				  ->bindValue(':userId',$this->userId)
				  ->bindValue(':siteId',$this->siteId)
				  ->queryAll();
		foreach($results as $k=>$result){
			$store = $this->checkStoreNumber($this->dpid,$result['product_id'],$result['is_set'],$result['num']);
			if(!$store['status']){
				throw new Exception($store['msg']);
			}
			$results[$k]['store_number'] = $store['msg'];
			if($result['promotion_id'] > 0){
				$productPrice = WxPromotion::getPromotionPrice($result['dpid'],$this->userId,$result['product_id'],$result['is_set'],$result['promotion_id'],$result['to_group']);
				$results[$k]['price'] = $productPrice['price'];
				$results[$k]['promotion'] = $productPrice;
			}else{
				$productPrice = new WxProductPrice($result['product_id'],$result['dpid'],$result['is_set']);
				$results[$k]['price'] = $productPrice->price;
				$results[$k]['promotion'] = $productPrice->promotion;
			}
			$this->cartNumber +=$result['num'];
		}
		$this->cart = $results;
	}
	//判断产品库存
	public function checkStoreNumber($dpid,$productId,$isSet,$num){
		if($isSet){
			$sql = 'select * from nb_product_set where lid=:productId and dpid=:dpid and delete_flag=0';
			$product = Yii::app()->db->createCommand($sql)
					->bindValue(':dpid',$dpid)
					->bindValue(':productId',$productId)
					->queryRow();
			if($product['store_number']==0){
				return array('status'=>false,'msg'=>$product['set_name'].'该产品已售罄!');
			}
		}else{
			$sql = 'select * from nb_product where lid=:productId and dpid=:dpid and delete_flag=0';
			$product = Yii::app()->db->createCommand($sql)
						->bindValue(':dpid',$dpid)
						->bindValue(':productId',$productId)
						->queryRow();
			if($product['store_number']==0){
				return array('status'=>false,'msg'=>$product['product_name'].'该产品已售罄!');
			}
		}
		
		if($product['store_number'] > 0){
			if($num > $product['store_number']){
				return array('status'=>false,'msg'=>'超出库存,库存剩余'.$product['store_number'].'!');
			}
		}
		return array('status'=>true,'msg'=>$product['store_number']);
	}
	//处理订单口味
	public function dealTastes(){
		if(!empty($this->tastes)){
			foreach($this->tastes as $taste){
				$tasteArr = explode('-',$taste);
				if(count($tasteArr)>1){
					$this->productTastes[$tasteArr[0]][] = $tasteArr;
				}
			}
		}
	}
	//处理订单口味
	public function dealProductSet(){
		if(!empty($this->setDetail)){
			foreach($this->setDetail as $detail){
				$detailArr = explode('-',$detail);
				if(count($detailArr) > 1){
					$this->productSetDetail[$detailArr[0]][] = $detailArr;
				}
			}
			// 套餐内单品
			foreach ($this->productSetDetail as $k=>$setdetail){
				$totalOriginPrice = 0;
				foreach ($setdetail as $key=>$val){
					$setProduct = WxProduct::getProduct($val[1], $this->dpid);
					$totalOriginPrice += $setProduct['original_price']*$val[2];
					$this->productSetDetail[$k][$key]['product_name'] = $setProduct['product_name'];
					$this->productSetDetail[$k][$key]['main_picture'] = $setProduct['main_picture'];
					$this->productSetDetail[$k][$key]['original_price'] = $setProduct['original_price'];
				}
				$this->productSetDetail[$k]['total_original_price'] = $totalOriginPrice;
			}
		}
	}
	//获取座位状态
	public function getSite(){
		$site = WxSite::get($this->siteId,$this->dpid);
		if(!in_array($site['status'],array(1,2,3))){
			if(empty($this->number)){
				 throw new Exception('开台餐位数不能为0，请添加餐位数！');
			}
			$this->orderOpenSite();
		}elseif($site['status'] == 1){
			$this->order = self::getOrderBySiteId($this->siteId,$this->dpid);
		}
	}
	//获取餐位费
	public function getSeatingFee(){
		$isSeatingFee = WxCompanyFee::get(1,$this->dpid);
		if($isSeatingFee){
			$this->seatingFee = $isSeatingFee['fee_price'];
		}else{
			$this->seatingFee = 0;
		}
	}
	//获取打包费
	public function getPackingFee(){
		$isPackingFee = WxCompanyFee::get(2,$this->dpid);
		if($isPackingFee){
			$this->packingFee = $isPackingFee['fee_price'];
		}else{
			$this->packingFee = 0;
		}
	}
	//获取运费
	public function getFreightFee(){
		$isFreightFee = WxCompanyFee::get(3,$this->dpid);
		if($isFreightFee){
			$this->freightFee = $isFreightFee['fee_price'];
		}else{
			$this->freightFee = 0;
		}
	}
	//座位开台
	public function orderOpenSite(){
		$result = SiteClass::openSite($this->dpid,$this->number,$this->isTemp,$this->siteId);
		if($this->isTemp==1){
			$this->siteId = $result['siteid'];
		}
	}
	//生成订单
	public function createOrder(){
		$time = time();
		$orderPrice = 0;
		$realityPrice = 0;
		$accountNo = 0;
		$se = new Sequence("order");
	    $orderId = $se->nextval();
	    
	    if($this->type==1 && $this->order){
			$accountNo = $this->order['account_no'];
		}else{
			$accountNo = self::getAccountNo($this->dpid,$this->siteId,0,$orderId);
		}
		
	    $insertOrderArr = array(
	        	'lid'=>$orderId,
	        	'dpid'=>$this->dpid,
	        	'create_at'=>date('Y-m-d H:i:s',$time),
	        	'update_at'=>date('Y-m-d H:i:s',$time), 
	        	'account_no'=>$accountNo,
	        	'user_id'=>$this->userId,
	        	'site_id'=>$this->siteId,
	        	'is_temp'=>$this->isTemp,
	        	'number'=>$this->number,
	        	'order_status'=>2,
	        	'order_type'=>$this->type,
	        	'is_sync'=>DataSync::getInitSync(),
	        );
		$result = Yii::app()->db->createCommand()->insert('nb_order', $insertOrderArr);
		
		//外卖订单地址
		if($this->type==2){
			$address = WxAddress::getDefault($this->userId,$this->dpid);
			if($address){
				WxOrderAddress::addOrderAddress($orderId,$address);
			}
		}
		//整单口味
		if(isset($this->productTastes[0]) && !empty($this->productTastes[0])){
			foreach($this->productTastes[0] as $ordertaste){
				if($ordertaste[2] > 0){
					$orderPrice +=$ordertaste[2];
					$realityPrice +=$ordertaste[2];
				}
				$se = new Sequence("order_taste");
    			$orderTasteId = $se->nextval();
		 		$orderTasteData = array(
		 								'lid'=>$orderTasteId,
										'dpid'=>$this->dpid,
										'create_at'=>date('Y-m-d H:i:s',$time),
			        					'update_at'=>date('Y-m-d H:i:s',$time),
			        					'taste_id'=>$ordertaste[1],
			        					'order_id'=>$orderId,
			        					'is_order'=>1,
			        					'is_sync'=>DataSync::getInitSync(),
		 								);
		 		$result = Yii::app()->db->createCommand()->insert('nb_order_taste',$orderTasteData);
			}
		}
		$levelDiscount = 1;
		if($this->user['level']){
			$birthday = date('m-d',strtotime( $this->user['user_birthday']));
			$today = date('m-d',time());
			if($birthday==$today){
				$levelDiscunt =  $this->user['level']['birthday_discount'];
			}else{
				$levelDiscunt =  $this->user['level']['level_discount'];
			}
		}
		foreach($this->cart as $cart){
			$ortherPrice = 0;
			if($cart['is_set'] > 0){
				$hasPrice = 0;
				// 套餐 插入套餐明细  计算单个套餐数量  $detail = array(set_id,product_id,num,price); price 套餐内加价
				$totalProductPrice = $this->productSetDetail[$cart['product_id']]['total_original_price'];
				foreach ($this->productSetDetail[$cart['product_id']] as $i=>$detail){
					if($i==='total_original_price'){
						continue;
					}
					$ortherPrice = $detail[3];
					$eachPrice = $detail['original_price']*$detail[2]/$totalProductPrice*$cart['price'];
					$hasPrice += $eachPrice;
					if($i+2 == count($detail)){
						$leavePrice = $hasPrice - $cart['price'];
						if($leavePrice > 0){
							$itemPrice =  $eachPrice - $leavePrice + $ortherPrice;
						}else{
							$itemPrice =  $eachPrice - $leavePrice + $ortherPrice;
						}
					}else{
						$itemPrice = $eachPrice + $ortherPrice;
					}
					$itemPrice = number_format($itemPrice,4);
					
					$se = new Sequence("order_product");
					$orderProductId = $se->nextval();
					$orderProductData = array(
							'lid'=>$orderProductId,
							'dpid'=>$this->dpid,
							'create_at'=>date('Y-m-d H:i:s',$time),
							'update_at'=>date('Y-m-d H:i:s',$time),
							'order_id'=>$orderId,
							'set_id'=>$cart['product_id'],
							'product_id'=>$detail[1],
							'product_name'=>$detail['product_name'],
							'product_pic'=>$detail['main_picture'],
							'price'=>$itemPrice,
							'original_price'=>$detail['original_price']+$ortherPrice,
							'amount'=>$cart['num']*$detail[2],
							'zhiamount'=>$cart['num'],
							'product_order_status'=>9,
							'is_sync'=>DataSync::getInitSync(),
					);
					Yii::app()->db->createCommand()->insert('nb_order_product',$orderProductData);
				}
				$isSync = DataSync::getInitSync();
				if($cart['store_number'] > 0){
					$sql = 'update nb_product_set set store_number =  store_number-'.$cart['num'].',is_sync='.$isSync.' where lid='.$cart['product_id'].' and dpid='.$this->dpid.' and delete_flag=0';
					Yii::app()->db->createCommand($sql)->execute();
				}
			}else{
				$se = new Sequence("order_product");
				$orderProductId = $se->nextval();
				//单品 插入产品口味
				if(isset($this->productTastes[$cart['product_id']]) && !empty($this->productTastes[$cart['product_id']])){
					foreach($this->productTastes[$cart['product_id']] as $taste){
						if($taste[2] > 0){
							$ortherPrice +=$taste[2];
						}
						$se = new Sequence("order_taste");
						$orderTasteId = $se->nextval();
						$orderTasteData = array(
								'lid'=>$orderTasteId,
								'dpid'=>$this->dpid,
								'create_at'=>date('Y-m-d H:i:s',$time),
								'update_at'=>date('Y-m-d H:i:s',$time),
								'taste_id'=>$taste[1],
								'order_id'=>$orderProductId,
								'is_order'=>0,
								'is_sync'=>DataSync::getInitSync(),
						);
						Yii::app()->db->createCommand()->insert('nb_order_taste',$orderTasteData);
					}
				}
				$orderProductData = array(
						'lid'=>$orderProductId,
						'dpid'=>$this->dpid,
						'create_at'=>date('Y-m-d H:i:s',$time),
						'update_at'=>date('Y-m-d H:i:s',$time),
						'order_id'=>$orderId,
						'set_id'=>0,
						'product_id'=>$cart['product_id'],
						'product_name'=>$cart['product_name'],
						'product_pic'=>$cart['main_picture'],
						'price'=>$cart['price']+$ortherPrice,
						'original_price'=>$cart['original_price']+$ortherPrice,
						'amount'=>$cart['num'],
						'product_order_status'=>9,
						'is_sync'=>DataSync::getInitSync(),
				);
				Yii::app()->db->createCommand()->insert('nb_order_product',$orderProductData);
				
				$isSync = DataSync::getInitSync();
				if($cart['store_number'] > 0){
					$sql = 'update nb_product set store_number =  store_number-'.$cart['num'].',is_sync='.$isSync.' where lid='.$cart['product_id'].' and dpid='.$this->dpid.' and delete_flag=0';
					Yii::app()->db->createCommand($sql)->execute();
				}
			}
			
			 
			 //插入订单优惠
			 if($cart['promotion_id'] > 0){
			 	foreach($cart['promotion']['promotion_info'] as $promotion){
			 		$se = new Sequence("order_product_promotion");
	    			$orderproductpromotionId = $se->nextval();
			 		$orderProductPromotionData =array(
		 										'lid'=>$orderproductpromotionId,
												'dpid'=>$this->dpid,
												'create_at'=>date('Y-m-d H:i:s',$time),
					        					'update_at'=>date('Y-m-d H:i:s',$time), 
												'order_id'=>$orderId,
												'order_product_id'=>$orderProductId,
												'account_no'=>$accountNo,
												'promotion_type'=>$cart['promotion']['promotion_type'],
												'promotion_id'=>$promotion['poromtion_id'],
												'promotion_money'=>$promotion['promotion_money'],
												'delete_flag'=>0,
												'is_sync'=>DataSync::getInitSync(),
		 										);
		 			Yii::app()->db->createCommand()->insert('nb_order_product_promotion',$orderProductPromotionData);								
			 	}
			 	$orderPrice +=  ($cart['price']*$levelDiscount+$ortherPrice)*$cart['num'];
			 }else{
			 	$orderPrice +=  ($cart['price']*$levelDiscount+$ortherPrice)*$cart['num'];
			 }
			 $realityPrice += ($cart['original_price']+$ortherPrice)*$cart['num'];
		}
		 if(($this->type==1||$this->type==3) && $this->seatingFee > 0){
			 	$se = new Sequence("order_product");
		    	$orderProductId = $se->nextval();
	         	$orderProductData = array(
								'lid'=>$orderProductId,
								'dpid'=>$this->dpid,
								'create_at'=>date('Y-m-d H:i:s',$time),
	        					'update_at'=>date('Y-m-d H:i:s',$time), 
								'order_id'=>$orderId,
								'set_id'=>0,
								'product_id'=>0,
								'product_name'=>'餐位费',
								'product_pic'=>'',
								'product_type'=>1,
								'price'=>$this->seatingFee,
								'original_price'=>$this->seatingFee,
								'amount'=>$this->number,
								'product_order_status'=>9,
								'is_sync'=>DataSync::getInitSync(),
								);
				 Yii::app()->db->createCommand()->insert('nb_order_product',$orderProductData);
				$orderPrice +=  $this->seatingFee*$this->number;
			 	$realityPrice += $this->seatingFee*$this->number;
		  }elseif($this->type==2){
		  		if($this->packingFee > 0){
		  			$se = new Sequence("order_product");
			    	$orderProductId = $se->nextval();
		         	$orderProductData = array(
									'lid'=>$orderProductId,
									'dpid'=>$this->dpid,
									'create_at'=>date('Y-m-d H:i:s',$time),
		        					'update_at'=>date('Y-m-d H:i:s',$time), 
									'order_id'=>$orderId,
									'set_id'=>0,
									'product_id'=>0,
									'product_name'=>'包装费',
									'product_pic'=>'',
									'product_type'=>2,
									'price'=>$this->packingFee,
									'original_price'=>$this->packingFee,
									'amount'=>$this->cartNumber,
									'product_order_status'=>9,
									'is_sync'=>DataSync::getInitSync(),
									);
					 Yii::app()->db->createCommand()->insert('nb_order_product',$orderProductData);
					$orderPrice +=  $this->packingFee*$this->cartNumber;
				 	$realityPrice += $this->packingFee*$this->cartNumber;
		  		}
			 	if($this->type==2 && $this->freightFee > 0){
			 		$se = new Sequence("order_product");
			    	$orderProductId = $se->nextval();
		         	$orderProductData = array(
									'lid'=>$orderProductId,
									'dpid'=>$this->dpid,
									'create_at'=>date('Y-m-d H:i:s',$time),
		        					'update_at'=>date('Y-m-d H:i:s',$time), 
									'order_id'=>$orderId,
									'set_id'=>0,
									'product_id'=>0,
									'product_name'=>'配送费',
									'product_pic'=>'',
									'product_type'=>3,
									'price'=>$this->freightFee,
									'original_price'=>$this->freightFee,
									'amount'=>1,
									'product_order_status'=>9,
									'is_sync'=>DataSync::getInitSync(),
									);
					 Yii::app()->db->createCommand()->insert('nb_order_product',$orderProductData);
					$orderPrice +=  $this->freightFee;
				 	$realityPrice += $this->freightFee;
			 	}
		  }
			 
		if($orderPrice==0){
			$sql = 'update nb_order set should_total='.$orderPrice.',reality_total='.$realityPrice.',order_status=3,is_sync='.$isSync.' where lid='.$orderId.' and dpid='.$this->dpid;
			Yii::app()->db->createCommand($sql)->execute();
		}else{
			$sql = 'update nb_order set should_total='.$orderPrice.',reality_total='.$realityPrice.',is_sync='.$isSync.' where lid='.$orderId.' and dpid='.$this->dpid;
			echo $sql;exit;
			Yii::app()->db->createCommand($sql)->execute();
		}
		
		//清空购物车
		$sql = 'delete from nb_cart where user_id='.$this->userId.' and dpid='.$this->dpid;
		Yii::app()->db->createCommand($sql)->execute();
        return $orderId;
	}
	public static function getOrder($orderId,$dpid){
		$sql = 'select * from nb_order where lid=:lid and dpid=:dpid';
		$order = Yii::app()->db->createCommand($sql)
				  ->bindValue(':lid',$orderId)
				  ->bindValue(':dpid',$dpid)
				  ->queryRow();
		if($order){
			$order['taste'] = self::getOrderTaste($orderId, $dpid, 1);
		}
	    return $order;
	}
	/**
	 * 
	 * 获取未付款的 订单产品
	 * 
	 */
	 public static function getNoPayOrderProduct($orderId,$dpid){
		$sql = 'select * from nb_order_product where order_id=:lid and dpid=:dpid and delete_flag=0 and product_order_status=9';
		$order = Yii::app()->db->createCommand($sql)
				  ->bindValue(':lid',$orderId)
				  ->bindValue(':dpid',$dpid)
				  ->queryAll();
	    return $order;
	}
	/**
	 * 
	 * 通过siteid获取订单未支付
	 * 
	 */
	public static function getOrderBySiteId($siteId,$dpid){
		$sql = 'select * from nb_order where site_id=:siteId and dpid=:dpid and order_status=1 and order_type=1';
		$order = Yii::app()->db->createCommand($sql)
				  ->bindValue(':siteId',$siteId)
				  ->bindValue(':dpid',$dpid)
				  ->queryRow();
	    return $order;
	}
	public static function getOrderProduct($orderId,$dpid){
		$sql = 'select lid,order_id,main_id,set_id,price,amount,zhiamount,is_retreat,product_id,product_name,product_pic,original_price from nb_order_product  where order_id = :orderId and dpid = :dpid and product_type=0 and delete_flag=0 and set_id=0';
		$sql .=' union select t.lid,t.order_id,t.main_id,t.set_id,sum(t.price) as price,t.amount,t.zhiamount,t.is_retreat,t.product_id,t1.set_name as product_name,t.product_pic,t.original_price from nb_order_product t,nb_product_set t1  where t.set_id=t1.lid and t.dpid=t1.dpid and t.order_id = :orderId and t.dpid = :dpid and t.product_type=0 and t.delete_flag=0 and t.set_id>0 group by t.set_id,t.main_id';
		$orderProduct = Yii::app()->db->createCommand($sql)
					    ->bindValue(':orderId',$orderId)
					    ->bindValue(':dpid',$dpid)
					    ->queryAll();
		foreach ($orderProduct as $k=>$product){
			if($product['set_id']>0){
				$productSet = self::getOrderProductSetDetail($product['order_id'],$dpid,$product['set_id'],$product['main_id']);
				$orderProduct[$k]['detail'] = $productSet;
			}else{
				$productTaste = self::getOrderTaste($product['lid'],$dpid,0);
				$orderProduct[$k]['taste'] = $productTaste;
			}
		}
	    return $orderProduct;
	}
	public static function getOrderProductSetDetail($orderId,$dpid,$setId,$mainId){
		$sql = 'select * from nb_order_product where order_id=:orderId and set_id=:setId and dpid=:dpid and main_id=:mainId and product_type=0 and delete_flag=0';
		$orderProductSet = Yii::app()->db->createCommand($sql)
					->bindValue(':orderId',$orderId)
					->bindValue(':dpid',$dpid)
					->bindValue(':setId',$setId)
					->bindValue(':mainId',$mainId)
					->queryAll();
		return $orderProductSet;
	}
	public static function getOrderTaste($orderId,$dpid,$isOrder){
		$sql = 'select t.*,t1.name,t1.price from nb_order_taste t,nb_taste t1 where t.taste_id=t1.lid and t.dpid=t1.dpid and t.dpid=:dpid and t.is_order=:isOrder and t.order_id=:orderId';
		$orderTaste = Yii::app()->db->createCommand($sql)
						->bindValue(':orderId',$orderId)
						->bindValue(':dpid',$dpid)
						->bindValue(':isOrder',$isOrder)
						->queryAll();
		return $orderTaste;
	}
	public static function getOrderProductByType($orderId,$dpid,$type){
		$sql = 'select t.price,t.amount,t.is_retreat from nb_order_product t where t.order_id = :orderId and t.dpid = :dpid and t.product_type=:type and t.delete_flag=0';
		$orderProduct = Yii::app()->db->createCommand($sql)
				  ->bindValue(':orderId',$orderId)
				  ->bindValue(':dpid',$dpid)
				  ->bindValue(':type',$type)
				  ->queryAll();
	    return $orderProduct;
	}
	public static function getUserOrderList($userId,$dpid,$type){
		$user = WxBrandUser::get($userId, $dpid);
		$dpid = WxCompany::getDpids($dpid);
		if($type==1){
			$sql = 'select m.* from (select * from nb_order where dpid in (:dpid) and user_id=:userId and order_type in (1,2,3,6) and order_status in (1,2)';
			$sql .= ' union select t.* from nb_order t left join nb_order_pay t1 on t.lid=t1.order_id and t.dpid=t1.dpid where t.dpid in (:dpid) and t.order_type=0 and t.order_status in (1,2) and t1.remark=:cardId)m where 1 order by lid desc limit 20';
		}elseif($type==2){
			$sql = 'select m.* from (select * from nb_order where dpid in (:dpid) and user_id=:userId and order_type in (1,2,3,6) and order_status in (3,4)';
			$sql .= ' union select t.* from nb_order t left join nb_order_pay t1 on t.lid=t1.order_id and t.dpid=t1.dpid where t.dpid in (:dpid) and t.order_type=0 and t.order_status in (3,4) and t1.remark=:cardId)m where 1 order by lid desc limit 20';
		}else{
			$sql = 'select m.* from (select * from nb_order where dpid in (:dpid) and user_id=:userId and order_type in (1,2,3,6) and order_status in (1,2,3,4)';
			$sql .= ' union select t.* from nb_order t left join nb_order_pay t1 on t.lid=t1.order_id and t.dpid=t1.dpid where t.dpid in (:dpid) and t.order_type=0 and t.order_status in (1,2,3,4) and t1.remark=:cardId)m where 1 order by lid desc limit 20';
		}
		$orderList = Yii::app()->db->createCommand($sql)
				  ->bindValue(':userId',$userId)
				  ->bindValue(':dpid',$dpid)
				  ->bindValue(':cardId',$user['card_id'])
				  ->queryAll();
	    return $orderList;
	}
	public static function getOrderAddress($orderId,$dpid){
		$sql = 'select * from nb_order_address where order_lid=:orderId and dpid=:dpid and delete_flag=0';
		$address = Yii::app()->db->createCommand($sql)
				  ->bindValue(':orderId',$orderId)
				  ->bindValue(':dpid',$dpid)
				  ->queryRow();
	    return $address;
	}
	/**
	 * 
	 * 获取当天改会员使用现金券支付的订单
	 * 
	 */
	 public static function getOrderUseCupon($userId,$dpid){
	 	$now = date('Y-m-d',time());
	 	$sql = 'select * from nb_order where dpid=:dpid and user_id=:userId and cupon_branduser_lid > 0 and order_status in (1,2,3,4,8) and create_at >= :now';
		$order = Yii::app()->db->createCommand($sql)
				  ->bindValue(':userId',$userId)
				  ->bindValue(':dpid',$dpid)
				  ->bindValue(':now',$now)
				  ->queryAll();
		return $order;
	}
	public static function updateOrderStatus($orderId,$dpid){
		$now = date('Y-m-d H:i:s',time());
		$isSync = DataSync::getInitSync();
		$sql = 'update nb_order set order_status=3,paytype=1,pay_time="'.$now.'",is_sync='.$isSync.' where lid='.$orderId.' and dpid='.$dpid;
		Yii::app()->db->createCommand($sql)->execute();
	}
	/**
	 * 
	 * 更改订单产品表状态
	 * 
	 */
	public static function updateOrderProductStatus($orderId,$dpid){
		$isSync = DataSync::getInitSync();
		$sql = 'update nb_order_product set product_order_status=8,is_sync='.$isSync.' where order_id='.$orderId.' and dpid='.$dpid.' and delete_flag=0';
		Yii::app()->db->createCommand($sql)->execute();
	}
	/**
	 * 
	 * 更改订单支付方式
	 * 
	 * 
	 */
	 public static function updatePayType($orderId,$dpid,$paytype = 1){
	 	$isSync = DataSync::getInitSync();
		$sql = 'update nb_order set paytype='.$paytype.',is_sync='.$isSync.' where lid='.$orderId.' and dpid='.$dpid;
		Yii::app()->db->createCommand($sql)->execute();
	}
	/**
	 * 
	 * 插入订单代金券表
	 * 并减少订单相应的金额
	 * 
	 */
	public static function updateOrderCupon($orderId,$dpid,$cuponBranduserLid){
		$now = date('Y-m-d H:i:s',time());
		$order = self::getOrder($orderId,$dpid);
		$sql = 'select t.cupon_id,t1.cupon_money,t1.min_consumer from nb_cupon_branduser t,nb_cupon t1 where t.cupon_id=t1.lid and t.dpid=t1.dpid and  t.lid='.$cuponBranduserLid.
				' and t.dpid='.$dpid.' and t1.begin_time <= "'.$now.'" and "'.$now.'" <= t1.end_time and t1.delete_flag=0 and t1.is_available=0';
		$result = Yii::app()->db->createCommand($sql)->queryRow();
		if($result && $order['should_total'] >= $result['min_consumer']){
			$isSync = DataSync::getInitSync();
			$money = ($order['should_total'] - $result['cupon_money']) >0 ? $order['should_total'] - $result['cupon_money'] : 0;
			$cuponMoney = $result['cupon_money'];
			
			$se = new Sequence("order_pay");
		    $orderPayId = $se->nextval();
		    $insertOrderPayArr = array(
		        	'lid'=>$orderPayId,
		        	'dpid'=>$order['dpid'],
		        	'create_at'=>$now,
		        	'update_at'=>$now, 
		        	'order_id'=>$order['lid'],
		        	'account_no'=>$order['account_no'],
		        	'pay_amount'=>$cuponMoney,
		        	'paytype'=>9,
		        	'paytype_id'=>$result['cupon_id'],
		        	'is_sync'=>$isSync,
		     );
			$orderPay = Yii::app()->db->createCommand()->insert('nb_order_pay', $insertOrderPayArr);
			
			WxCupon::dealCupon($order['dpid'], $cuponBranduserLid, 2);
			if($money == 0){
				//修改订单状态
				WxOrder::updateOrderStatus($order['lid'],$order['dpid']);
				//修改订单产品状态
				WxOrder::updateOrderProductStatus($order['lid'],$order['dpid']);
				//修改座位状态
				if($order['order_type']==1){
					WxSite::updateSiteStatus($order['site_id'],$order['dpid'],3);
				}else{
					WxSite::updateTempSiteStatus($order['site_id'],$order['dpid'],3);
				}
			}
		}
	}
	/**
	 * 
	 * 更改订单信息
	 * 
	 * 
	 */
	 public static function update($orderId,$dpid,$contion){
	 	$isSync = DataSync::getInitSync();
		$sql = 'update nb_order set '.$contion.'is_sync='.$isSync.' where lid='.$orderId.' and dpid='.$dpid;
		Yii::app()->db->createCommand($sql)->execute();
	}
	/**
	 * 
	 * 取消订单
	 * 
	 */
	 public static function cancelOrder($orderId,$dpid){
	 	$order = self::getOrder($orderId,$dpid);
	 	
	 	$sql = 'select * from nb_order_product where order_id=:orderId and dpid=:dpid and is_print=1';
	 	$resluts = Yii::app()->db->createCommand($sql)
	 							 ->bindValue(':orderId',$orderId)
	 							 ->bindValue(':dpid',$dpid)
	 							 ->queryAll();
	 	if(!empty($resluts)){
	 		return 0;
	 	}else{
	 		$isSync = DataSync::getInitSync();
	 		foreach($resluts as $orderProduct){
	 			$sql = 'select * from nb_product where lid=:productId and dpid=:dpid and delete_flag=0';
				$product = Yii::app()->db->createCommand($sql)
							  ->bindValue(':dpid',$dpid)
							  ->bindValue(':productId',$orderProduct['product_id'])
							  ->queryRow();
				if($product['store_number'] >= 0){
					$sql = 'update nb_product set store_number =  store_number+'.$orderProduct['amount'].',is_sync='.$isSync.' where lid='.$orderProduct['product_id'].' and dpid='.$dpid.' and delete_flag=0';
			 		Yii::app()->db->createCommand($sql)->execute();
				}
	 		}
			$sql = 'update nb_order set order_status=7,is_sync='.$isSync.' where lid='.$orderId.' and dpid='.$dpid;
			$result = Yii::app()->db->createCommand($sql)->execute();
			if($order['cupon_branduser_lid'] > 0){
				$sql = 'update nb_cupon_branduser set is_used=1,is_sync='.$isSync.' where lid='.$order['cupon_branduser_lid'].' and dpid='.$order['dpid'].' and to_group=3';
				$cuponBranduser = Yii::app()->db->createCommand($sql)->execute();
			}
			return $result;
	 	}
	}
	/**
	 * 
	 * 微信支付 通知时 使用该方法
	 * order——pay表记录支付数据
	 * 
	 */
	 public static function insertOrderPay($order,$paytype = 1){
	 	$time = time();
	 	if($paytype==10){
	 		$user = WxBrandUser::get($order['user_id'],$order['dpid']);
	 		if(!$user){
	 			throw new Exception('不存在该会员!');
	 		}
	 		$payMoney = self::reduceYue($user,$order);
	 		
	 		$se = new Sequence("order_pay");
		    $orderPayId = $se->nextval();
		    $insertOrderPayArr = array(
		        	'lid'=>$orderPayId,
		        	'dpid'=>$order['dpid'],
		        	'create_at'=>date('Y-m-d H:i:s',$time),
		        	'update_at'=>date('Y-m-d H:i:s',$time), 
		        	'order_id'=>$order['lid'],
		        	'account_no'=>$order['account_no'],
		        	'pay_amount'=>$payMoney,
		        	'paytype'=>$paytype,
		        	'is_sync'=>DataSync::getInitSync(),
		        );
			$result = Yii::app()->db->createCommand()->insert('nb_order_pay', $insertOrderPayArr);
	 		
	 	}else{
	 		// 微信支付
	 		$payYue = 0.00;
	 		$payCupon = 0.00;
	 		$payPoints = 0.00;
	 		$orderPays = $orderPays = WxOrderPay::get($order['dpid'],$order['lid']);
	 		if(!empty($orderPays)){
	 			foreach($orderPays as $orderPay){
	 				if($orderPay['paytype']==10){
	 					$payYue = $orderPay['pay_amount'];
	 				}elseif($orderPay['paytype']==9){
	 					$payCupon = $orderPay['pay_amount'];
	 				}elseif($orderPay['paytype']==8){
	 					$payPoints = $orderPay['pay_amount'];
	 				}
	 			}
	 		}
	 		$payPrice = number_format($order['should_total'] - $payYue - $payCupon - $payPoints,2);
	 		$se = new Sequence("order_pay");
		    $orderPayId = $se->nextval();
		    $insertOrderPayArr = array(
		        	'lid'=>$orderPayId,
		        	'dpid'=>$order['dpid'],
		        	'create_at'=>date('Y-m-d H:i:s',$time),
		        	'update_at'=>date('Y-m-d H:i:s',$time), 
		        	'order_id'=>$order['lid'],
		        	'account_no'=>$order['account_no'],
		        	'pay_amount'=>$payPrice,
		        	'paytype'=>$paytype,
		        	'is_sync'=>DataSync::getInitSync(),
		        );
			$result = Yii::app()->db->createCommand()->insert('nb_order_pay', $insertOrderPayArr);
	 	}
	 }
	/**
	 * 
	 * 扣除会员余额
	 * 
	 */
	 public static function reduceYue($user,$order){
	 	$payMoney = 0;
	 	$userId = $user['lid'];
	 	$orderId = $order['lid'];
	 	$dpid = $order['dpid'];
	 	
	 	$orderTotal = $order['should_total'];
	 	$payCupon = 0.00;
	 	$payPoints = 0.00;
	 	$orderPays = WxOrderPay::get($dpid,$orderId);
		if(!empty($orderPays)){
			foreach($orderPays as $orderPay){
				if($orderPay['paytype']==9){
					$payCupon = $orderPay['pay_amount']; 
				}elseif($orderPay['paytype']==8){
					$payPoints = $orderPay['pay_amount'];
				}
			}
		}
		$total = $orderTotal - $payCupon - $payPoints;
		
	 	$isSync = DataSync::getInitSync();
	 	
	 	$yue = WxBrandUser::getYue($userId,$dpid);//余额
	 	$cashback = WxBrandUser::getCashBackYue($userId,$dpid);//返现余额
	 	
	 	if($cashback > 0){
	 		//返现余额大于等于支付
	 		if($cashback >= $total){
	 			WxCashBack::userCashBack($total,$userId,$dpid,0);
	 			//修改订单状态
				WxOrder::updateOrderStatus($order['lid'],$order['dpid']);
				//修改订单产品状态
				WxOrder::updateOrderProductStatus($order['lid'],$order['dpid']);
				//修改座位状态
				if($order['order_type']==1){
					WxSite::updateSiteStatus($order['site_id'],$order['dpid'],3);
				}else{
					WxSite::updateTempSiteStatus($order['site_id'],$order['dpid'],3);
				}
				$payMoney = $total;
	 		}else{
	 			WxCashBack::userCashBack($total,$userId,$dpid,1);
	 			if($yue > $total){//剩余充值大于支付
 					$sql = 'update nb_brand_user set remain_money = remain_money-'.($total - $cashback).',is_sync='.$isSync.' where lid='.$user['lid'].' and dpid='.$dpid;
					$result = Yii::app()->db->createCommand($sql)->execute();
					
					//修改订单状态
					WxOrder::updateOrderStatus($order['lid'],$order['dpid']);
					//修改订单产品状态
					WxOrder::updateOrderProductStatus($order['lid'],$order['dpid']);
					//修改座位状态
					if($order['order_type']==1){
						WxSite::updateSiteStatus($order['site_id'],$order['dpid'],3);
					}else{
						WxSite::updateTempSiteStatus($order['site_id'],$order['dpid'],3);
					}
					
					$payMoney = $total;
	 			}else{
	 				$sql = 'update nb_brand_user set remain_money = 0,is_sync='.$isSync.' where lid='.$user['lid'].' and dpid='.$dpid;
					$result = Yii::app()->db->createCommand($sql)->execute();
					
					$payMoney = $yue;
	 			}
	 		}
	 	}else{
	 		if($yue > $total){
				$sql = 'update nb_brand_user set remain_money = remain_money-'.$total.',is_sync='.$isSync.' where lid='.$user['lid'].' and dpid='.$dpid;
				$result = Yii::app()->db->createCommand($sql)->execute();
				
				//修改订单状态
				WxOrder::updateOrderStatus($order['lid'],$order['dpid']);
				//修改订单产品状态
				WxOrder::updateOrderProductStatus($order['lid'],$order['dpid']);
				//修改座位状态
				if($order['order_type']==1){
					WxSite::updateSiteStatus($order['site_id'],$order['dpid'],3);
				}else{
					WxSite::updateTempSiteStatus($order['site_id'],$order['dpid'],3);
				}
				
				$payMoney = $total;
 			}else{
 				$sql = 'update nb_brand_user set remain_money = 0,is_sync='.$isSync.' where lid='.$user['lid'].' and dpid='.$dpid;
				$result = Yii::app()->db->createCommand($sql)->execute();
				
				$payMoney = $yue;
 			}
	 	}
	 	
	 	return $payMoney;
	 }
     /**
      * 
      * 输入金额订单
      * 
      */
     public static function createBillOrder($dpid,$userId,$order_price,$offprice){
        $time = time();
        $accountNo = 0;
        $orignprice = $order_price + $offprice;
		$se = new Sequence("order");
	    $orderId = $se->nextval();
	    
		$accountNo = self::getAccountNo($dpid,0,1,$orderId);
		
		$transaction = Yii::app()->db->beginTransaction();
			try{
        	    $insertOrderArr = array(
        	        	'lid'=>$orderId,
        	        	'dpid'=>$dpid,
        	        	'create_at'=>date('Y-m-d H:i:s',$time),
        	        	'update_at'=>date('Y-m-d H:i:s',$time), 
        	        	'account_no'=>$accountNo,
        	        	'user_id'=>$userId,
        	        	'site_id'=>0,
        	        	'is_temp'=>1,
        	        	'number'=>1,
                        'should_total'=>$order_price,
                        'reality_total'=>$orignprice,
        	        	'order_status'=>1,
        	        	'order_type'=>5,
        	        	'is_sync'=>DataSync::getInitSync(),
        	        );
        		$result = Yii::app()->db->createCommand()->insert('nb_order', $insertOrderArr);
                
                $se = new Sequence("order_product");
		    	$orderProductId = $se->nextval();
	         	$orderProductData = array(
								'lid'=>$orderProductId,
								'dpid'=>$dpid,
								'create_at'=>date('Y-m-d H:i:s',$time),
	        					'update_at'=>date('Y-m-d H:i:s',$time), 
								'order_id'=>$orderId,
								'set_id'=>0,
								'product_id'=>0,
								'product_name'=>'扫码支付',
								'product_pic'=>'',
								'product_type'=>0,
								'price'=>$order_price,
								'original_price'=>$orignprice,
                                'offprice'=>$offprice,
								'amount'=>1,
								'product_order_status'=>9,
								'is_sync'=>DataSync::getInitSync(),
								);
				 Yii::app()->db->createCommand()->insert('nb_order_product',$orderProductData);
        	    $transaction->commit();
                $msg = json_encode(array('status'=>true,'order_id'=>$orderId));
			}catch (Exception $e) {
				$transaction->rollback();
				$msg = json_encode(array('status'=>false,'order_id'=>0));
			}
            return $msg;
     }
	 /**
	  * 
	  * 订单流水单号
	  * 
	  */
	  public static function getAccountNo($dpid,$siteId,$isTemp,$orderId){
            $sql="select ifnull(min(account_no),'000000000000') as account_no from nb_order where dpid="
                    .$dpid." and site_id=".$siteId." and is_temp=".$isTemp
                    ." and order_status in ('1','2','3')";
            $ret=Yii::app()->db->createCommand($sql)->queryScalar();      
            if($isTemp || empty($ret) || $ret=="0000000000")
            {
                $ret=substr(date('Ymd',time()),-6).substr("0000000000".$orderId, -6);
            }
            return $ret;
        }
}