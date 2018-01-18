<?php

class MtpayController extends Controller
{
	public function actionMtwappay(){
	
		//$result = SqbPay::pay($dpid,$_POST);
		//$obj = json_decode($result,true);
		$data = array(
				'outTradeNo'=>'20180118'.time(),
				'dpid'=>'27',
				'totalFee'=>'1',
				'subject'=>'壹点吃',
				'body'=>'壹点吃支付测试',
				'channel'=>'wx_scan_pay',
				'expireMinutes'=>'3',
				'tradeType'=>'JSAPI',
				'notifyUrl'=>'http://www.wymenu.com/wymenuv2/mtpay/mtwappayresult',
				'merchantId'=>'4282256',
				'appId'=>'31140',
				'random'=>'1234565432',
		);
		$result = MtpPay::preOrder($data);
		var_dump($result);exit;
	}
	public function actionMtwappayresult(){
		Helper::writeLog('进入方法.返回参数');
		//收钱吧异步回调数据接收及解析...
		$xml = $GLOBALS['HTTP_RAW_POST_DATA'];
		//Helper::writeLog('异步通知的参数:'.$xml);
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
		Helper::writeLog('进入方法'.$xml);


	}
}