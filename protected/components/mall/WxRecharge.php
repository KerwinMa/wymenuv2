<?php 
/**
 * 
 * 
 * 获取微信充值模板
 * 
 * 
 */
class WxRecharge
{
	public function __construct($rechargeId,$dpid,$userId){
		$this->rechargeId = $rechargeId;
		$this->dpid = $dpid;
		$this->userId = $userId;
		$myfile = fopen("/tmp/newfile1.txt", "w");
		$transaction = Yii::app()->db->beginTransaction();
 		try {
 			fwrite($myfile,'begain recharge ');
			$this->getRecharge();
			fwrite($myfile,'getRecharge ');
			$this->recharge();
			fwrite($myfile,'recharge ');
			$this->updateBrandUser();
			fwrite($myfile,'updateBrandUser ');
			$this->getPointsValid();
			fwrite($myfile,'getPointsValid ');
			$this->insertPoints();
			fwrite($myfile,'insertPoints ');
			fclose($myfile);
			$transaction->commit();	
		 } catch (Exception $e) {
            $transaction->rollback(); //如果操作失败, 数据回滚
            throw new Exception($e->getMessage());
        } 
	}
	public function getRecharge(){
		$sql = 'select * from nb_weixin_recharge where lid=:lid and dpid=:dpid and is_available=0 and delete_flag=0';
		$this->recharge = Yii::app()->db->createCommand($sql)
						  ->bindValue(':dpid',$this->dpid)
						  ->bindValue(':lid',$this->rechargeId)
						  ->queryRow();
	}
	/**
	 * 
	 * 充值记录
	 * 
	 */
	 public function recharge(){
	 	$time = time();
	 	$se = new Sequence("recharge_record");
        $lid = $se->nextval();
        $insertDataArr = array(
        	'lid'=>$lid,
        	'dpid'=>$this->dpid,
        	'create_at'=>date('Y-m-d H:i:s',$time),
        	'update_at'=>date('Y-m-d H:i:s',$time),
        	'recharge_lid'=>$this->rechargeId,
        	'recharge_money'=>$this->recharge['recharge_money'],
        	'cashback_num'=>$this->recharge['recharge_cashback'],
        	'point_num'=>$this->recharge['recharge_pointback'],
        	'brand_user_lid'=>$this->userId,
        	'is_sync'=>DataSync::getInitSync(),	
        	);
       $result = Yii::app()->db->createCommand()->insert('nb_recharge_record', $insertDataArr);
       if(!$result){
       		throw new Exception('插入记录失败!');
       }
	 }
	 /**
	  * 
	  *更改会员信息 
	  * 
	  */
	  public function updateBrandUser(){
		  $isSync = DataSync::getInitSync();
		  $sql = 'update nb_brand_user set remain_money = remain_money + '.$this->recharge['recharge_money'].',remain_back_money =remain_back_money + '.$this->recharge['recharge_cashback'].',is_sync='.$isSync.' where lid='.$this->userId.' and dpid='.$this->dpid;
		  $result = Yii::app()->db->createCommand($sql)->execute();
		  if(!$result){
       		throw new Exception('更新会员余额失败!');
       	   }
	  }
	/**
	 * 
	 * 
	 * 获取积分有效期
	 * 
	 */
	 public function getPointsValid(){
	 	$sql = 'select * from nb_points_valid where dpid='.$this->dpid.' and is_available=0 and delete_flag=0';
		$this->pointsValid = Yii::app()->db->createCommand($sql)->queryRow();
	 }
	  /**
	   * 
	   * 插入积分记录
	   * 
	   */
	   public function insertPoints(){
	   	   $time = time();
	   	   if($this->recharge['recharge_pointback']){
	   	   		if($this->pointsValid){
					$endTime = date('Y-m-d H:i:s',strtotime('+'.$this->pointsValid['valid_days'].' day'));
				}else{
					$endTime = date('Y-m-d H:i:s',strtotime('+1 year'));
				}
				$se = new Sequence("point_record");
			    $lid = $se->nextval();
				$pointRecordData = array(
									'lid'=>$lid,
						        	'dpid'=>$this->dpid,
						        	'create_at'=>date('Y-m-d H:i:s',$time),
						        	'update_at'=>date('Y-m-d H:i:s',$time),
						        	'point_type'=>1,
						        	'type_lid'=>$this->rechargeId,
						        	'point_num'=>$this->recharge['recharge_pointback'],
						        	'brand_user_lid'=>$this->userId,
						        	'end_time'=>$endTime,
						        	'is_sync'=>DataSync::getInitSync(),
									);
				$result = Yii::app()->db->createCommand()->insert('nb_point_record', $pointRecordData);
				if(!$result){
	       		throw new Exception('插入积分失败!');
	       	   }
	   	   }
	   }
	/**
	 * 
	 * 获取微信充值模板
	 * 
	 */
	public static function getWxRecharge($dpid){
		$sql = 'select * from nb_weixin_recharge where dpid=:dpid and is_available=0 and delete_flag=0';
		$recharges = Yii::app()->db->createCommand($sql)
				  ->bindValue(':dpid',$dpid)
				  ->queryAll();
	    return $recharges;		  
	}
}