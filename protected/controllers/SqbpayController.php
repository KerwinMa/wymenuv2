<?php

class SqbpayController extends Controller
{
	public function actionWappayresultceshi(){
		$dpid = '0000000027';
		
		$now = time();
		$rand = rand(100,999);
		$orderId = $now.'-'.$dpid.'-'.$rand;
		
		$company = WxCompany::get($dpid);
		$data = array(
				'dpid' => $dpid,
				'pay_type' => 0,
				'out_trade_no' => $orderId,
				'total_fee' => '0.01',
		);
		$result = MicroPayModel::insert($data);
		
		if($result['status']){
			$result = SqbPay::preOrder(array(
					'dpid'=>$dpid,
					'client_sn'=>$orderId,
					'total_amount'=>'0.01',
					'payway'=>'3',
					'subject'=>'wymenu',
					'operator'=>'admin',
					'notify_url'=>'http://menu.wymenu.com/wymenuv2/sqbpay/wappayreturn/companyId/0000000027',
					'return_url'=>'http://menu.wymenu.com/wymenuv2/sqbpay/wappayresult/companyId/0000000027',
			));
		}else{
			echo 'error';
		}
	}
	public function actionWappayresult(){
		$is_success = Yii::app()->request->getParam('is_success');
		$status = Yii::app()->request->getParam('status');
		$sign = Yii::app()->request->getParam('sign');
		if($is_success == 'F'){
			$error_code = Yii::app()->request->getParam('error_code');
			$error_message = Yii::app()->request->getParam('error_message');
		}else{
			$terminal_sn = Yii::app()->request->getParam('terminal_sn');
			$sn = Yii::app()->request->getParam('sn');
			$trade_no = Yii::app()->request->getParam('trade_no');
			$client_sn = Yii::app()->request->getParam('client_sn');
			$status = Yii::app()->request->getParam('status');
			$reflect = Yii::app()->request->getParam('reflect');
			$sign = Yii::app()->request->getParam('sign');
			
			$result_code = Yii::app()->request->getParam('result_code');
			$result_message = Yii::app()->request->getParam('result_message');
			
			$data = '{"from":"result";"is_success":"'.$is_success.'";"client_sn":"'.$client_sn.'";"trade_no":"'.$trade_no.'";"status":"'.$status.'";"result_code":"'.$result_code.'";}';
			Helper::writeLog($data);
		}
		$this->render('wappayresult',array(
				'is_success'=>$is_success,
				'status'=>$status,
				'sign'=>$sign,
				'result_code'=>$result_code,
				'result_message'=>$result_message,
		));
		
	}
	public function actionWappayreturn(){
		$companyId = Yii::app()->request->getParam('companyId','000000');
		
		//收钱吧异步回调数据接收及解析...
		$xml = $GLOBALS['HTTP_RAW_POST_DATA'];
		//Helper::writeLog('进入方法;数据:'.$xml);
		/*$mxl如下：
		 * {
		 * "sn":"7895259485469125",*
		 * "client_sn":"1490611690-0000000027-409",*
		 * "client_tsn":"1490611690-0000000027-409",
		 * "ctime":"1490611690929",*
		 * "status":"FAIL_CANCELED",*
		 * "payway":"3",*
		 * "sub_payway":"3",*
		 * "order_status":"PAY_CANCELED",*
		 * "payer_uid":"",
		 * "trade_no":"6521100249201703286121293325",
		 * "total_amount":"1",*
		 * "net_amount":"0",*
		 * "finish_time":"1490611957891",*
		 * "subject":"wymenu",*
		 * "store_id":"f35d19cb-a316-499f-b43d-76b882d7caf5",*
		 * "terminal_id":"1cfcd666-6aa8-42fc-b031-b3eadbf2c9ed",*
		 * "operator":"admin"*
		 * }
		 * 
		 * */
		//$obj = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
		$obj = json_decode($xml,true);
		$sn = $obj['sn'];
		$client_sn = $obj['client_sn'];
		$client_tsn = $obj['client_tsn'];
		$ctime = $obj['ctime'];
		$status = $obj['status'];
		$payway = $obj['payway'];
		$sub_payway = $obj['sub_payway'];
		$order_status = $obj['order_status'];
		$payer_uid = $obj['payer_uid'];
		$trade_no = $obj['trade_no'];
		$total_amount = $obj['total_amount'];
		$net_amount = $obj['net_amount'];
		$finish_time = $obj['finish_time'];
		$subject = $obj['subject'];
		$store_id = $obj['store_id'];
		$terminal_id = $obj['terminal_id'];
		$operator = $obj['operator'];
		
		Helper::writeLog('进入方法'.$sn.';店铺:'.$companyId);
		
		$sql = 'select * from nb_notify_wxwap where dpid ='.$companyId.' and sn="'.$sn.'"';
		$notify = Yii::app()->db->createCommand($sql)
		->queryRow();
		if(!empty($notify)){
			if($order_status == $notify['order_status']){
				Helper::writeLog('相同的'.$sn);
			}else{
		
				//像微信公众号支付记录表插入记录...
				$se = new Sequence ( "notify_wxwap" );
				$notifyWxwapId = $se->nextval ();
				$notifyWxwapData = array (
						'lid' => $notifyWxwapId,
						'dpid' => $dpid,
						'create_at' => date ( 'Y-m-d H:i:s', $time ),
						'update_at' => date ( 'Y-m-d H:i:s', $time ),
						'sn' => $sn,
						'client_sn' => $client_sn,
						'client_tsn' => $client_tsn,
						'ctime' => $ctime,
						'status' => $status,
						'payway' => $payway,
						'sub_payway' => $sub_payway,
						'order_status' => $order_status,
						'total_amount' => $total_amount,
						'net_amount' => $net_amount,
						'finish_time' => $finish_time,
						'subject' => $subject,
						'store_id' => $store_id,
						'terminal_id' => $terminal_id,
						'operator' => $operator,
				);
				$result = Yii::app ()->db->createCommand ()->insert ( 'nb_notify_wxwap', $notifyWxwapData );
				Helper::writeLog('不同的'.$sn);
			}
		}else{
		
			//像微信公众号支付记录表插入记录...
			$se = new Sequence ( "notify_wxwap" );
			$notifyWxwapId = $se->nextval ();
			$notifyWxwapData = array (
					'lid' => $notifyWxwapId,
					'dpid' => $dpid,
					'create_at' => date ( 'Y-m-d H:i:s', $time ),
					'update_at' => date ( 'Y-m-d H:i:s', $time ),
					'sn' => $sn,
					'client_sn' => $client_sn,
					'client_tsn' => $client_tsn,
					'ctime' => $ctime,
					'status' => $status,
					'payway' => $payway,
					'sub_payway' => $sub_payway,
					'order_status' => $order_status,
					'total_amount' => $total_amount,
					'net_amount' => $net_amount,
					'finish_time' => $finish_time,
					'subject' => $subject,
					'store_id' => $store_id,
					'terminal_id' => $terminal_id,
					'operator' => $operator,
			);
			$result = Yii::app ()->db->createCommand ()->insert ( 'nb_notify_wxwap', $notifyWxwapData );
			Helper::writeLog('第一次'.$sn);
		}
		
		if($order_status == 'PAID'){
			//订单成功支付...
		}elseif($order_status == 'PAY_CANCELED'){
			//订单支付失败...
		}
		Helper::writeLog('进入方法'.$sns.';店铺:'.$companyId);
		
	}
}