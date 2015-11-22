<?php

class MallController extends Controller
{
	public $companyId;
	public $userId = -1;
	public $weixinServiceAccount;
	public $brandUser;
	public $layout = '/layouts/mallmain';
	
	public function init() 
	{
		$companyId = Yii::app()->request->getParam('companyId');
		$this->companyId = $companyId;
//		如果微信浏览器
//		if(Helper::isMicroMessenger()){
//			$this->weixinServiceAccount();
//			$baseInfo = new WxUserBase($this->weixinServiceAccount['appid'],$this->weixinServiceAccount['appsecret']);
//			$userInfo = $baseInfo->getSnsapiBase();
//			$openid = $userInfo['openid'];
//			
//			$this->brandUser($openid);
//			if(!$this->brandUser){
//				$newBrandUser = new NewBrandUser($this->postArr['FromUserName'], $this->brandId);
//	    		$this->brandUser = $newBrandUser->brandUser;
//			}
//			$this->userId = $this->brandUser['lid'];
//		}
		$this->userId = 0000000000;
		Yii::app()->session['qrcode-'.$this->userId] = 0000000000;
	}
	public function actionIndex()
	{
		$product = new WxProduct($this->companyId);
		$categorys = $product->categorys;
		$products = $product->categoryProductLists;
		$this->render('index',array('companyId'=>$this->companyId,'categorys'=>$categorys,'models'=>$products));
	}
	/**
	 * 
	 * 购物车
	 * 
	 */
	public function actionCart()
	{
		$siteId = Yii::app()->session['qrcode-'.$this->userId];
		
		$cartObj = new WxCart($this->companyId,$this->userId,$productArr = array(),$siteId);
		$carts = $cartObj->getCart();
		$this->render('cart',array('companyId'=>$this->companyId,'models'=>$carts));
	}
	/**
	 * 
	 * 添加购物车
	 * 
	 */
	public function actionAddCart()
	{
		if($this->userId < 0){
			Yii::app()->end(json_encode(array('status'=>false,'msg'=>'请关注微信公众号我要点单进行点餐')));
		}
		if(isset(Yii::app()->session['qrcode-'.$this->userId])){
			$siteId = Yii::app()->session['qrcode-'.$this->userId];
		}else{
			Yii::app()->end(json_encode(array('status'=>false,'msg'=>'请先扫描餐桌二维码,然后再进行点单')));
		}
		$productId = Yii::app()->request->getParam('productId');
		$promoteId = Yii::app()->request->getParam('promoteId');
		
		$productArr = array('product_id'=>$productId,'num'=>1,'privation_promotion_id'=>$promoteId);
		$cart = new WxCart($this->companyId,$this->userId,$productArr,$siteId);
		if($cart->addCart()){
			Yii::app()->end(json_encode(array('status'=>true,'msg'=>'ok')));
		}else{
			Yii::app()->end(json_encode(array('status'=>false,'msg'=>'点单失败,请重新点单')));
		}
	}
	/**
	 * 
	 * 删除购物车
	 * 
	 */
	public function actionDeleteCart()
	{
		if($this->userId < 0){
			Yii::app()->end(json_encode(array('status'=>false,'msg'=>'请关注微信公众号我要点单进行点餐')));
		}
		if(isset(Yii::app()->session['qrcode-'.$this->userId])){
			$siteId = Yii::app()->session['qrcode-'.$this->userId];
		}else{
			Yii::app()->end(json_encode(array('status'=>false,'msg'=>'请先扫描餐桌二维码,然后再进行点单')));
		}
		
		$productId = Yii::app()->request->getParam('productId');
		$promoteId = Yii::app()->request->getParam('promoteId');
		$productArr = array('product_id'=>$productId,'num'=>1,'privation_promotion_id'=>$promoteId);
		
		$cart = new WxCart($this->companyId,$this->userId,$productArr,$siteId);
		if($cart->deleteCart()){
			Yii::app()->end(json_encode(array('status'=>true,'msg'=>'ok')));
		}else{
			Yii::app()->end(json_encode(array('status'=>false,'msg'=>'请重新操作')));
		}
	}
	private function weixinServiceAccount() {	
		$sql = 'select * from nb_weixin_service_account where dpid = '.$this->companyId;
		$this->weixinServiceAccount = Yii::app()->db->createCommand($sql)->queryRow();
	}
	private function brandUser($openId) {	
		$sql = 'select * from nb_brand_user where openid = "'.$openId.'"';
		$this->brandUser = Yii::app()->db->createCommand($sql)->queryRow();
	}
}