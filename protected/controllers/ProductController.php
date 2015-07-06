<?php

class ProductController extends Controller
{
	public $companyId = 0;
	public $wifMac = 0;
	public $moMac = 0;
	public $siteNoId = 0;
	public $isPad = 0;
	public $padId = 0;
	
	public $layout = '/layouts/productmain';
	public function init(){
		session_start();
		$moMac = Yii::app()->request->getParam('momac',0);
		if($moMac){
			$_SESSION['momac'] = $moMac;
		}
		$mac = Yii::app()->request->getParam('wuyimenusysosyoyhmac',0);
		$padId = Yii::app()->request->getParam('padid',0);
		if($padId){
			$companyId = Yii::app()->request->getParam('companyid',0);
			$padId = Yii::app()->request->getParam('padid',0);
			$this->companyId = $companyId;
			$_SESSION['companyId'] = $this->companyId;
			$this->isPad = 1;
			$this->padId = $padId;
			Yii::app()->theme = 'pad';
		}
		if($mac){
			$companyWifi = CompanyWifi::model()->find('macid=:macId',array(':macId'=>$mac));
			$this->companyId = $companyWifi?$companyWifi->dpid:0;
			$_SESSION['companyId'] = $this->companyId;
		}
		if(!$this->companyId){
			$this->companyId = isset($_SESSION['companyId'])?$_SESSION['companyId']:0;
		}
		$checkCode = Yii::app()->request->getParam('checkcode',0);
		if($checkCode){
			$siteNo = SiteNo::model()->find('dpid=:companyId and code=:code',array(':companyId'=>$this->companyId,':code'=>$checkCode));
			$_SESSION['siteNoId'] = $siteNo['lid'];
		}
		if(!$this->siteNoId){
			$this->siteNoId = isset($_SESSION['siteNoId'])?$_SESSION['siteNoId']:1;
		}
		$this->moMac = isset($_SESSION['momac'])?$_SESSION['momac']:0;
	}
	/**
	 * //----n---
	 * 获取一级分类 没启用
	 */
	public function actionProductCategory(){
		$totalCatgorys = array();
		$command = Yii::app()->db;
		$sql = 'select lid,category_name from nb_product_category where dpid=:companyId and pid=0 and delete_flag=0 order by order_num DESC';
		$parentCategorys = $command->createCommand($sql)->bindValue(':companyId',$this->companyId)->queryAll();
		foreach($parentCategorys as $category){
			$csql = 'select lid,category_name from nb_product_category where dpid=:companyId and pid=:pid and delete_flag=0 order by order_num DESC';
			$categorys = $command->createCommand($csql)->bindValue(':companyId',$this->companyId)->bindValue(':pid',$category['lid'])->queryAll();
			$category['children'] = $categorys;
			array_push($totalCatgorys,$category);
		}
		$this->renderPartial('parentcategory',array('parentCategorys'=>$totalCatgorys));
	}
	/**
	 * 
	 * 获取分类商品
	 */
	public function actionIndex()
	{
		$pid = Yii::app()->request->getParam('pid',0);
		$type = Yii::app()->request->getParam('type',0);
		$categoryId = Yii::app()->request->getParam('categoryId',0);

		if(!$categoryId){
			$categorys = ProductClass::getFirstCategoryId($this->companyId);
                        //var_dump($categorys);exit;
			$pid = $categorys['pid'];
			$categoryId = $categorys['lid'];
		}
		$this->render('product',array('pid'=>$pid,'categoryId'=>$categoryId,'siteNoId'=>$this->siteNoId,'type'=>$type,'isPad'=>$this->isPad));
	}
        
        public function actionPrintCheck(){                
                $companyId = Yii::app()->request->getParam('companyId');
                $padId = Yii::app()->request->getParam('padId');
                $pad=Pad::model()->with('printer')->find(' t.dpid=:dpid and t.lid=:lid',array(':dpid'=>$companyId,'lid'=>$padId));
                //var_dump($pad);exit;
                //要判断打印机类型错误，必须是local。
                if($pad->printer->printer_type!='1')
                {
                    Yii::app()->end(json_encode(array('status'=>false,'dpid'=>$companyId,'jobid'=>"0",'type'=>'local','msg'=>yii::t('app','必须是本地打印机！'))));
                }else{
                    Yii::app()->end(json_encode(Helper::printCheck($pad)));
                }
        }
        
	/**
	 * 
	 * 商品详情
	 */
	 public function actionProductInfo(){
	 	$id = Yii::app()->request->getParam('id',0);
	 	$product = Product::model()->findByPk($id);
	 	$this->render('productinfo',array('product'=>$product));
	 }
	 /**
	  * 
	  * 推荐商品
	  */
	 public function actionRecommend(){
	 	$this->render('recommend');
	 }
	public function actionGetJson()
	{
//		$page = Yii::app()->request->getParam('page',1);
		$type = Yii::app()->request->getParam('type',0);// 0 普通产品  1推荐品 2套餐 3点赞 4点单
		if($type){
			$product = ProductClass::getHotsProduct($this->companyId,$type,$this->siteNoId);
		}else{
			$categoryId = Yii::app()->request->getParam('cat',0);
			$pad = Yii::app()->request->getParam('pad',0);
			$product = ProductClass::getCategoryProducts($this->companyId,$categoryId,$this->siteNoId,$pad);
		}
		Yii::app()->end(json_encode($product));
	}
	public function actionGetOrderListJson()
	{
		$orderProductList = array();
		$orderList = new OrderList($this->companyId,$this->siteNoId);
		if($orderList->order){
			$orderProductList = $orderList->OrderProductList($orderList->order['lid'],0,1);
			foreach($orderProductList as $key=>$val){
				$orderProductList[$key]['category_name'] = OrderList::GetCatoryName($key,$this->companyId);
				if(!$key){
					foreach($val as $k=>$v){
						$orderProductList[$key][$k]['product_id'] = ProductSetClass::GetProductSetProductIds($this->companyId,$v['set_id']);
					}
				}	
			}
		}
		Yii::app()->end(json_encode($orderProductList));
	}
	/**
	 * 点单
	 * 
	 */
	public function actionCreateCart(){
		$isAddOrder = Yii::app()->request->getPost('isAddOrder');
		$productId = Yii::app()->request->getPost('productId');
		$type = Yii::app()->request->getPost('type');//是否是套餐
		$product = array('lid'=>$productId,'type'=>$type);
		if($isAddOrder){
			//增加
			$createOrder = new CreateOrder($this->companyId,$this->siteNoId,$product);
			if($createOrder->hasOrderProduct()){
				echo 0;
			}else{
				if($createOrder->createOrder()){
					echo 1;
				}else{
					echo 0;
				}
			}
		}else{
			//删除
			$createOrder = new CreateOrder($this->companyId,$this->siteNoId,$product);
			if($createOrder->deleteOrderProduct()){
				echo 1;
			}else{
				echo 0;
			}
		}
		
		exit;
	}
	/**
	 * 商品点赞
	 */
	public function actionFavorite(){
		$productId = Yii::app()->request->getParam('id');
		$model = Product::model()->find('lid=:lid',array(':lid'=>$productId));
		$model->favourite_number = $model->favourite_number + 1; 
		if($model->update()){
			echo 1;
		}else{
			echo 0;
		}
		exit;
	}
	 //订单列表
	public function actionOrderList(){
		$confirm = Yii::app()->request->getParam('confirm',0);
		$goodsIds = isset($_POST) ?$_POST :array();
		if($confirm){
			//确认订单
			$orderId = Yii::app()->request->getParam('orderId',0);
			$orderlist = new OrderList($this->companyId,$this->siteNoId);
			if(!$orderlist->ConfirmOrder($orderId,$goodsIds)){
			   $this->redirect(array('/product/order','orderId'=>$orderId));
			}
		}
		
	 	$this->render('orderlist');
	}
	//确认订单
	public function actionConfirmPadOrder(){
		$goodsIds = isset($_POST) ?$_POST :array();
		$padOrder = json_encode(array('status'=>false,'msg'=>yii::t('app','订单为空')),JSON_UNESCAPED_UNICODE);
	 	if(!empty($goodsIds)){
	 		try{
	 			$padOrder = CreateOrder::createPadOrder($this->companyId,$goodsIds,$this->padId); 
	 		}catch (Exception $e) {
	 			$padOrder = $e->getMessage();
	 		}
			
	 	}
	 	Yii::app()->end($padOrder);
	}
        //打印清单
        public function actionPrintPadList(){                
                $orderId = Yii::app()->request->getParam('orderId',0);
				$companyId = Yii::app()->request->getParam('companyId');
                $padId = Yii::app()->request->getParam('padId');
                $order = Order::model()->with('company')->find('t.lid=:id and t.dpid=:dpid' , array(':id'=>$orderId,':dpid'=>$companyId));
                $pad=Pad::model()->with('printer')->find(' t.dpid=:dpid and t.lid=:lid',array(':dpid'=>$order->dpid,'lid'=>$padId));
                //要判断打印机类型错误，必须是local。
                if($pad->printer->printer_type!='1')
                {
                    Yii::app()->end(json_encode(array('status'=>false,'jobid'=>"0",'type'=>'local','msg'=>yii::t('app','必须是本地打印机！'))));
                }else{
                    //前面加 barcode
                    $precode="1D6B450B".strtoupper(implode('',unpack('H*', 'A'.$order->lid)))."0A".strtoupper(implode('',unpack('H*', 'A'.$order->lid)))."0A";
                    Yii::app()->end(json_encode(Helper::printList($order , $pad,$precode)));
                }
        }
	//确认订单
	public function actionOrder(){
		$orderId = Yii::app()->request->getParam('orderId');
		$goodsIds = isset($_POST) ?$_POST :array();
		if(!($goodsIds && OrderList::UpdateOrder($this->companyId,$orderId,$goodsIds))){
			$this->redirect(array('/product/orderList'));
		}
		
	 	$this->render('order');
	}
	//微信支付
	public function actionWeixPayOrder(){
		$dpid = Yii::app()->request->getParam('dpid',0);
		$orderId = Yii::app()->request->getParam('orderId',0);
		$this->render('wxpayOrder',array('dpid'=>$dpid,'orderId'=>$orderId));
	}
	//获取商品口味
	public function actionGetProductPicJson()
	{
		$id = Yii::app()->request->getParam('id');
		$pic = ProductClass::getProductPic($this->companyId,$id);
                //var_dump($pic);exit;
                if(empty($pic))
                {
                    echo 'nopic';
                }else{
                    $this->renderPartial('_productImg',array('pics'=>$pic));
                }
	}
	//获取商品口味
	public function actionGetTasteJson()
	{
		$tasteArr = array();
		$type = Yii::app()->request->getParam('type');
		$id = Yii::app()->request->getParam('id');
		if($type==1){ //全单口味
			$allOrderTastes = TasteClass::getAllOrderTaste($this->companyId,$type,$this->companyId);
		}elseif($type==2){ //产品口味
			$productId = Yii::app()->request->getParam('productId');
			$allOrderTastes = TasteClass::getProductTaste($productId,$this->companyId);
		}
		$tasteMemo = TasteClass::getOrderTasteMemo($id,$type,$this->companyId);
		$orderTastes = TasteClass::getOrderTaste($id,$type,$this->companyId);
		$tasteArr['taste'] = $allOrderTastes;
		foreach($allOrderTastes as $key=>$val){
			if(in_array($val['lid'],$orderTastes)){
				$tasteArr['taste'][$key]['has'] = 1;
			}else{
				$tasteArr['taste'][$key]['has'] = 0;
			}
		}
		$tasteArr['taste_memo'] = $tasteMemo;
		Yii::app()->end(json_encode($tasteArr));
	}
	public function actionSetOrderTaste(){
		$type = Yii::app()->request->getPost('type');
		$id = Yii::app()->request->getPost('id');
		$tasteMemo = Yii::app()->request->getPost('tasteMemo');
		$tasteIds = Yii::app()->request->getPost('tasteIds');
		if($type==1){ //全单口味
			$result = TasteClass::save($this->companyId, 1, $id, $tasteIds, $tasteMemo);
		}elseif($type==2){ //产品口味
			$result = TasteClass::save($this->companyId, 0, $id, $tasteIds, $tasteMemo);
		}elseif($type==3){
			$result = FeedBackClass::save($this->companyId, $this->siteNoId, 1, $id, $tasteIds, $tasteMemo);
		}
		if($result){
			echo 1;
		}else{
			echo 0;
		}
		exit;
	}
	//获取商品口味 array('feeback_id'=>feeback_mome,)
	public function actionGetFeebackJson()
	{
		$type = Yii::app()->request->getParam('type');
		$id = Yii::app()->request->getParam('id');
		if($type==3){ //全单口味
			$orderFeeback = FeedBackClass::getOrderFeeBack($id,1);
			$allOrderFeeback = FeedBackClass::getAllFeeBack($this->companyId,1);
			if(!empty($orderFeeback)){
				foreach($allOrderFeeback as $key=>$feeback){
					foreach($orderFeeback as $ofeeback){
						if($feeback['lid']==$ofeeback['feedback_id']){
							$allOrderFeeback[$key]['feedback_memo'] = $ofeeback['feedback_memo'];
						}
					}
				}
			}
		}
		Yii::app()->end(json_encode($allOrderFeeback));
	}
	public function actionAddProductAddition(){
		$orderId = Yii::app()->request->getParam('orderId');
		$lid = Yii::app()->request->getParam('id');
		$data = array('status'=>false,'msg'=>yii::t('app','加菜失败!'));
		$productAddition = new ProductAdditionClass($this->companyId,$orderId,$lid);
		if($productAddition->save()){
			$data = array('status'=>true,'msg'=>yii::t('app','加菜成功!'),'data'=>$productAddition->productAddition,'lastLid'=>sprintf("%010d",$productAddition->lastLid));
			Yii::app()->end(json_encode($data));
		}else{
			Yii::app()->end(json_encode($data));
		}
	}
	public function actionQrcode(){
		$url = urldecode('http://www.baidu.com');
		QRcode::png($url,$outfile = false, $level = QR_ECLEVEL_L, $size = 6, $margin = 4, $saveandprint=false);
		exit;
	}
	public function actionExportOrder(){
		$this->exportOrder();
		exit;
	}
	private function exportOrder($models,$type=0,$orderStatus = 0,$params=array(),$export = 'xml'){
 		$attributes = array(
			'order_id'=>'订单编号',
			'create_time'=>'下单时间',
			'card_id'=>'会员号',
			'goods_name'=>'所购商品',
			'order_goods_number'=>'商品总数量',
			'cost'=>'商品总价(元)',
			'total'=>'订单总价(元)',
			'shop_name'=>'门店名'
		);
 		$data[1] = array_values($attributes);
 		$fields = array_keys($attributes);
 		
		foreach($models as $model){
			$arr = array();
			foreach($fields as $f){
				if($f == 'create_time'){
					$arr[] = date('Y-m-d H:i:s',$model[$f]);
				}elseif(in_array($f,array('cost','total'))){
					$arr[] = $model[$f]/100;
				}elseif(in_array($f,array('card_id'))){
					$arr[] = substr($model[$f],5);
				}elseif(in_array($f,array('order_goods_number'))){
					$arr[] = $model[$f];
				}elseif(in_array($f,array('goods_name'))){
					$goodsName='';
					if($model['offGoods']){
						foreach($model['offGoods'] as $goods){
							$goodsName.=$goods['goods_num'].'×'.$goods['goods_name'].';';
						}
					}
					$arr[] =rtrim($goodsName,';');
				}else{
					$arr[] = $model[$f];
				}
			}
			$data[] = $arr;
		}
 		Until::exportFile($data,$export,$fileName=date('Y_m_d_H_i_s'));
	}
}