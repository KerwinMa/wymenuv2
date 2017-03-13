<?php 
/**
 * 
 * 
 * 微信端代金券类
 *
 * 
 * 
 */
class WxCupon
{
	/**
	 * 
	 * 获取会员所以代金券
	 * 
	 */
	public static function getUserAllCupon($userId,$dpid){
		$user = WxBrandUser::get($userId,$dpid);
		$sql = 'select m.lid,m.is_used,n.cupon_title,n.main_picture,n.min_consumer,n.cupon_money,n.begin_time,n.end_time from (select * from nb_cupon_branduser where dpid=:dpid and to_group=3 and brand_user_lid=:userId and is_used > 0 and delete_flag=0' .
				' union select * from nb_cupon_branduser where dpid=:dpid and to_group=2 and brand_user_lid=:userLevelId and is_used > 0 and delete_flag=0)m ,nb_cupon n' .
				' where m.cupon_id=n.lid and m.dpid=n.dpid and n.delete_flag=0';
		$cupon = Yii::app()->db->createCommand($sql)
				  ->bindValue(':userId',$userId)
				  ->bindValue(':dpid',$dpid)
				  ->bindValue(':userLevelId',$user['user_level_lid'])
				  ->queryAll();
	    return $cupon;
	}
	/**
	 * 
	 * 未使用
	 * 
	 */
	public static function getUserNotUseCupon($userId,$dpid){
		$now = date('Y-m-d H:i:s',time());
		$user = WxBrandUser::get($userId,$dpid);
		$sql = 'select m.lid,m.dpid,m.is_used,n.cupon_title,n.main_picture,n.min_consumer,n.cupon_money,n.begin_time,n.end_time,n.cupon_memo from (select * from nb_cupon_branduser where dpid=:dpid and to_group=3 and brand_user_lid=:userId and is_used = 1 and delete_flag=0' .
				' union select * from nb_cupon_branduser where dpid=:dpid and to_group=2 and brand_user_lid=:userLevelId and is_used = 1 and delete_flag=0)m ,nb_cupon n' .
				' where m.cupon_id=n.lid and m.dpid=n.dpid and n.begin_time <=:now and :now <= n.end_time and n.delete_flag=0';
		
                $cupon = Yii::app()->db->createCommand($sql)
				  ->bindValue(':userId',$userId)
				  ->bindValue(':dpid',$dpid)
				  ->bindValue(':now',$now)
				  ->bindValue(':userLevelId',$user['user_level_lid'])
				  ->queryAll();
	    return $cupon;
	}
	/**
	 * 
	 * 已使用
	 * 
	 */
	public static function getUserUseCupon($userId,$dpid){
		$user = WxBrandUser::get($userId,$dpid);
		$sql = 'select m.lid,m.is_used,n.cupon_title,n.main_picture,n.min_consumer,n.cupon_money,n.begin_time,n.end_time ,n.cupon_memo from (select * from nb_cupon_branduser where dpid=:dpid and to_group=3 and brand_user_lid=:userId and is_used =2 and delete_flag=0' .
				' union select * from nb_cupon_branduser where dpid=:dpid and to_group=2 and brand_user_lid=:userLevelId and is_used = 2 and delete_flag=0)m ,nb_cupon n' .
				' where m.cupon_id=n.lid and m.dpid=n.dpid and n.delete_flag=0';
		$cupon = Yii::app()->db->createCommand($sql)
				  ->bindValue(':userId',$userId)
				  ->bindValue(':dpid',$dpid)
				  ->bindValue(':userLevelId',$user['user_level_lid'])
				  ->queryAll();
	    return $cupon;
	}
	/**
	 * 
	 * 已过期
	 * 
	 */
	 public static function getUserExpireCupon($userId,$dpid){
	 	$now = date('Y-m-d H:i:s',time());
		$user = WxBrandUser::get($userId,$dpid);
		$sql = 'select m.lid,m.is_used,n.cupon_title,n.main_picture,n.min_consumer,n.cupon_money,n.begin_time,n.end_time ,n.cupon_memo from (select * from nb_cupon_branduser where dpid=:dpid and to_group=3 and brand_user_lid=:userId and is_used > 0 and delete_flag=0' .
				' union select * from nb_cupon_branduser where dpid=:dpid and to_group=2 and brand_user_lid=:userLevelId and is_used > 0 and delete_flag=0)m ,nb_cupon n' .
				' where m.cupon_id=n.lid and m.dpid=n.dpid and :now > n.end_time and n.delete_flag=0';
		$cupon = Yii::app()->db->createCommand($sql)
				  ->bindValue(':userId',$userId)
				  ->bindValue(':dpid',$dpid)
				  ->bindValue(':now',$now)
				  ->bindValue(':userLevelId',$user['user_level_lid'])
				  ->queryAll();
	    return $cupon;
	}
	/**
	 * 
	 * 获取会员该订单可用
	 * 
	 */
	public static function getUserAvaliableCupon($total,$userId,$dpid){
		$isCanUse = true;
		$set = WxTotalPromotion::get($dpid);
		
		if($set){
			$orders = WxOrder::getOrderUseCupon($userId,$dpid);
			if($set['is_cupon'] >= 0 && count($orders) >= $set['is_cupon']){
				$isCanUse = false;
			}
		}
		if($isCanUse){
			$now = date('Y-m-d H:i:s',time());
			$user = WxBrandUser::get($userId,$dpid);
			$sql = 'select m.lid,n.cupon_title,n.main_picture,n.min_consumer,n.cupon_money from (select * from nb_cupon_branduser where dpid=:dpid and to_group=3 and brand_user_lid=:userId and is_used=1 and delete_flag=0' .
					' union select * from nb_cupon_branduser where dpid=:dpid and to_group=2 and brand_user_lid=:userLevelId and is_used=1 and delete_flag=0)m ,nb_cupon n' .
					' where m.cupon_id=n.lid and m.dpid=n.dpid and n.begin_time <=:now and :now <=n.end_time and n.min_consumer <=:total and n.delete_flag=0 and n.is_available=0';
			$cupon = Yii::app()->db->createCommand($sql)
					  ->bindValue(':userId',$userId)
					  ->bindValue(':dpid',$dpid)
					  ->bindValue(':userLevelId',$user['user_level_lid'])
					  ->bindValue(':now',$now)
					  ->bindValue(':total',$total)
					  ->queryAll();
		}else{
			$cupon = array();
		}
	    return $cupon;
	}
	/**
	 * 正在进行代金券列表
	 * 
	 */
	public static function getCuponList($dpid){
		$now = date('Y-m-d H:i:s',time());
		$sql = 'select * from nb_cupon where dpid=:dpid and begin_time <=:now and :now <= end_time and delete_flag=0';
		$cupon = Yii::app()->db->createCommand($sql)
				  ->bindValue(':dpid',$dpid)
				  ->bindValue(':now',$now)
				  ->queryAll();
	    return $cupon;
	}
	/**
	 * 代金券详情
	 * 
	 */
	public static function getCupon($dpid,$cuponId){
		$now = date('Y-m-d H:i:s',time());
		$sql = 'select * from nb_cupon where lid=:lid and dpid=:dpid and begin_time <=:now and :now <= end_time and delete_flag=0';
		$cupon = Yii::app()->db->createCommand($sql)
				  ->bindValue(':lid',$cuponId)
				  ->bindValue(':dpid',$dpid)
				  ->bindValue(':now',$now)
				  ->queryRow();
	    return $cupon;
	}
	/**
	 * 
	 * 使用核销代金券
	 * 
	 */
	public static function dealCupon($dpid,$cuponId,$status){
		$date = date('Y-m-d H:i:s',time());
		$sql = 'update nb_cupon_branduser set is_used='.$status.',used_time="'.$date.'" where lid='.$cuponId.' and dpid='.$dpid;
		$result = Yii::app()->db->createCommand($sql)->execute();
		return $result;
	}
	/**
	 * 
	 * 获取发放的代金券 活动
	 * 
	 */
	public static function getWxSentCupon($dpid,$type,$userId,$openId){
		$now = date('Y-m-d H:i:s',time());
		$sql = 'select t.* from nb_sentwxcard_promotion_detail t,nb_sentwxcard_promotion t1 where t.sentwxcard_pro_id=t1.lid and t.dpid=t1.dpid and t.dpid=:dpid and t1.type=:type and t1.begin_time <=:now and :now <= t1.end_time and t.delete_flag=0 and t1.delete_flag=0';
		$sentPromotion = Yii::app()->db->createCommand($sql)
							->bindValue(':dpid',$dpid)
							->bindValue(':now',$now)
							->bindValue(':type',$type)
							->queryAll();
		foreach ($sentPromotion as $promotion){
			self::sentCupon($dpid,$userId,$promotion['wxcard_id'],2,$promotion['sentwxcard_pro_id'],$openId);
		}
	}
	public static function getOneMonthByBirthday(){
		$todayBegain = date('m-d 00:00:00',strtotime('+1 month'));
		$todayEnd = date('m-d 23:59:59',strtotime('+1 month'));
		
		$sql = 'select * from nb_brand_user where user_birthday like "%'.$todayBegain.'" and user_birthday like "%'.$todayEnd.'" and unsubscribe = 0';
		$users = Yii::app()->db->createCommand($sql)->queryAll();
		if(!empty($users)){
			foreach ($users as $user){
				self::getWxSentCupon($user['dpid'],2,$user['lid'],$user['openid']);
			}
		}
	}
	/**
	 * 
	 * 发放代金券
	 */
	public static function sentCupon($dpid,$userId,$cuponId,$source,$source_id,$openId){
		$company = WxCompany::get($dpid);
		$now = date('Y-m-d H:i:s',time());
		$se = new Sequence("cupon_branduser");
		$lid = $se->nextval();
		$data = array(
				'lid'=>$lid,
	        	'dpid'=>$dpid,
	        	'create_at'=>$now,
	        	'update_at'=>$now,
	        	'cupon_id'=>$cuponId,
	        	'to_group'=>3,
	        	'brand_user_lid'=>$userId,
	        	'cupon_source'=>$source,
	        	'source_id'=>$source_id,
	        	'is_used'=>1,
	        	'is_sync'=>DataSync::getInitSync(),
				);
		$result = Yii::app()->db->createCommand()->insert('nb_cupon_branduser', $data);
		$cupon = self::getCupon($dpid, $cuponId);
		if($cupon){
			if($source==0){
				$sourceStr = '活动领取';
			}elseif($source==1){
				$sourceStr = '红包领取';
			}else{
				$sourceStr = '商家赠送';
			}
			$data = array(
					'touser'=>$openId,
					'url'=>Yii::app()->createAbsoluteUrl('/user/ticket',array('companyId'=>$dpid)),
					'first'=>'现金券已经领取成功',
					'keyword1'=>$cupon['cupon_money'].'元现金券一张',
					'keyword2'=>$sourceStr,
					'keyword3'=>$cupon['end_time'],
					'keyword4'=>$cupon['cupon_abstract'],
					'remark'=>'如果有任何疑问,欢迎拨打电话'.$company['	telephone'].'咨询'
			);
			new WxMessageTpl($dpid,$userId,1,$data);
		}
	    return $result;
	}
}