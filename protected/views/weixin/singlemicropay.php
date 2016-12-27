<?php
$now = time();
$rand = rand(100,999);
$orderId = $now.'-'.$dpid.'-'.$rand;

$company = WxCompany::get($dpid);
if(isset($auth_code) && $auth_code != ""){
	$input = new WxPayMicroPay();
	$input->SetAuth_code($auth_code);
	$input->SetBody($company['company_name']);
	$input->SetTotal_fee($should_total*100);
	$input->SetOut_trade_no($orderId);
	
	$microPay = new MicroPay();
	$result = $microPay->pay($input);
	if($result){
		if($result["return_code"] == "SUCCESS" && $result["result_code"] == "SUCCESS"){
			$msg = array('status'=>true, 'result'=>true, 'trade_no'=>$orderId);
		}elseif($result["return_code"] == "SUCCESS" && $result["result_code"] == "CANCEL"){
			Helper::writeLog('1');
			$msg = array('status'=>true, 'result'=>false, 'trade_no'=>$orderId);
		}else{
			Helper::writeLog('2');
			$msg = array('status'=>false, 'result'=>false,);
		}
	}else{
		Helper::writeLog('3');
		$msg = array('status'=>false, 'result'=>false,);
	}
}else{
	$msg = array('status'=>false, 'result'=>false,);
}
Helper::writeLog(json_encode($msg));
echo json_encode($msg);
exit;
?>

