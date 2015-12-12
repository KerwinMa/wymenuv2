<?php
/**
 * 
 * 支付通知回调基础类
 * @author widyhu
 *
 */
class Notify extends WxPayNotify
{
	//查询订单
	public function Queryorder($out_trade_no)
	{
		$input = new WxPayOrderQuery();
		$input->SetOut_trade_no($out_trade_no);
		$result = WxPayApi::orderQuery($input);
		if(array_key_exists("return_code", $result)
			&& array_key_exists("result_code", $result)
			&& $result["return_code"] == "SUCCESS"
			&& $result["result_code"] == "SUCCESS")
		{
			return true;
		}
		return false;
	}
	
	//重写回调处理函数
	public function NotifyProcess($data, &$msg)
	{
		$notfiyOutput = array();
		
		if(!array_key_exists("out_trade_no", $data)){
			$msg = "输入参数不正确";
			return false;
		}
		//查询订单，判断订单真实性
		if(!$this->Queryorder($data["out_trade_no"])){
			$msg = "订单查询失败";
			return false;
		}
		
		//记录通知 并更改订单状态
		$this->checkNotify($data);
		
		return true;
	}
	
	public function checkNotify($data){
		$sql = 'SELECT (SELECT count(*) FROM nb_notify WHERE transaction_id = "' .$data['transaction_id']. '") + (SELECT count(*) FROM nb_notify WHERE out_trade_no= "' .$data['out_trade_no']. '") as count';
		$count = Yii::app()->db->createCommand($sql)->queryRow();
		if(!$count['count']){
			$this->insertNotify($data);
		}
	}
	public function insertNotify($data){
		$orderIdArr = explode('-',$data["out_trade_no"]);
		$brandUser = WxBrandUser::getFromOpenId($data['openid']);
		
		$se = new Sequence("notify");
        $lid = $se->nextval();
		$notifyData = array(
			'lid'=>$lid,
        	'dpid'=>$orderIdArr[1],
        	'create_at'=>date('Y-m-d H:i:s',time()),
        	'update_at'=>date('Y-m-d H:i:s',time()),
        	'user_id'=>$brandUser['lid'],
        	'out_trade_no'=>$data['out_trade_no'],
        	'transaction_id'=>$data['transaction_id'],
        	'total_fee'=>$data['total_fee'],
        	'time_end'=>$data['time_end'],
        	'attach'=>isset($data['attach'])?$data['attach']:'',
        	'is_sync'=>DataSync::getInitSync(),
			);	
		Yii::app()->db->createCommand()->insert('nb_notify', $notifyData);
		
		//orderpay表插入数据
		$order = WxOrder::getOrder($orderIdArr[0],$orderIdArr[1]);
		WxOrder::insertOrderPay($order,1);
		$myfile = fopen("/tmp/newfile.txt", "w");
		$txt = "Bill Gates\n";
		fwrite($myfile, $txt);
		//修改订单状态
		WxOrder::updateOrderStatus($orderIdArr[0],$orderIdArr[1]);
		$txt = "Steve Jobs\n";
		fwrite($myfile, $txt);
		//修改订单产品状态
		WxOrder::updateOrderProductStatus($orderIdArr[0],$orderIdArr[1]);
		$txt = "Kebi LBJ\n";
		fwrite($myfile, $txt);
		//修改座位状态
		if($order['order_type']==1){
			WxSite::updateSiteStatus($order['site_id'],$order['dpid'],3);
		}
		fclose($myfile);
	}
}