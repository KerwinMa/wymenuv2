<?php
class MicroPayModel
{
	public static function insert($data){
		$time = time();
		$isSync = DataSync::getInitSync ();
		$dpid = $data['dpid'];
		$payType = $data['pay_type'];
		$outTradeNo = $data['out_trade_no'];
		$totalFee = $data['total_fee'];
		$se = new Sequence ( "micro_pay" );
		$microPayId = $se->nextval ();
		$microPayData = array (
				'lid' => $microPayId,
				'dpid' => $dpid,
				'create_at' => date ( 'Y-m-d H:i:s', $time ),
				'update_at' => date ( 'Y-m-d H:i:s', $time ),
				'pay_type' => $payType,
				'out_trade_no' => $outTradeNo,
				'total_fee' => $totalFee,
				'is_sync' => $isSync
		);
		$reslut = Yii::app ()->db->createCommand ()->insert ( 'nb_micro_pay', $microPayData );
		if($reslut){
			$msg = array('status'=>true);
		}else{
			$msg = array('status'=>false);
		}
		return $msg;
	}
	public static function update($dpid,$out_trade_no,$transactionId,$result){
		$sql = "update nb_micro_pay set transaction_id='".$transactionId."', pay_result='".$result."' where dpid=".$dpid." and out_trade_no='".$out_trade_no."'";
		Yii::app ()->db->createCommand ($sql)->execute();
	}
}