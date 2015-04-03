<?php
class TasteClass
{
	//产品口味 列表
	public static function getProductTaste($productId){
		$sql = 'select t.taste_id as lid,t1.name from nb_product_taste t,nb_taste t1 where t.taste_id=t1.lid and t.product_id=:productId and t.delete_flag=0';
		$conn = Yii::app()->db->createCommand($sql);
		$conn->bindValue(':productId',$productId);
		$result = $conn->queryAll();
		return $result;
	}
	
	//全订单口味列表 1 整单 0 非整单
	public static function getAllOrderTaste($dpid,$type){
		$sql = 'select lid,name from nb_taste where dpid=:dpid and allflae=:allflae and delete_flag=0';
		$conn = Yii::app()->db->createCommand($sql);
		$conn->bindValue(':dpid',$dpid);
		$conn->bindValue(':allflae',$type);
		$result = $conn->queryAll();
		return $result;
	}
	
	//订单口味 type = 1 全单口味 2 订单产品口味
	public static function getOrderTaste($orderId,$type){
		$result = array();
		if($type==1){
			$sql = 'select t.taste_id from nb_order_taste t where t.order_id=:orderId and t.is_order=1';
			$conn = Yii::app()->db->createCommand($sql);
			$conn->bindValue(':orderId',$orderId);
		}elseif($type==2){
			$sql = 'select t.taste_id from nb_order_taste t where t.order_id=:orderId and t.is_order=0';
			$conn = Yii::app()->db->createCommand($sql);
			$conn->bindValue(':orderId',$orderId);
		}
		$results = $conn->queryAll();
		foreach($results as $val){
			array_push($result,$val['taste_id']);
		}
		return $result;
	}
	//订单口味 type = 1 全单口味 2 订单产品口味
	public static function getOrderTasteMemo($orderId,$type){
		$result = array();
		if($type==1){
			$sql = 'select t.taste_memo from nb_order t where t.lid=:orderId';
			$conn = Yii::app()->db->createCommand($sql);
			$conn->bindValue(':orderId',$orderId);
		}elseif($type==2){
			$sql = 'select t.taste_memo from nb_order_product t where t.lid=:orderId';
			$conn = Yii::app()->db->createCommand($sql);
			$conn->bindValue(':orderId',$orderId);
		}
		$result = $conn->queryRow();
		return $result?$result['taste_memo']:'';
	}
	//保存订单口味
	public static function save($dpid, $type, $id = 0, $tastesIds = array(), $tastMemo=null){
		$transaction = Yii::app()->db->beginTransaction();
		try {
			$sql = 'delete from nb_order_taste where dpid=:dpid and is_order=:type and order_id=:orderId';
			$conn = Yii::app()->db->createCommand($sql);
			$conn->bindValue(':dpid',$dpid);
			$conn->bindValue(':type',$type);
			$conn->bindValue(':orderId',$id);
			$conn->execute();
			
			if(!empty($tastesIds)){
				foreach($tastesIds as $taste){
					$sql = 'SELECT NEXTVAL("order_taste") AS id';
					$maxId = Yii::app()->db->createCommand($sql)->queryRow();
					$data = array(
					 'lid'=>$maxId['id'],
					 'dpid'=>$dpid,
					 'create_at'=>date('Y-m-d H:i:s',time()),
					 'taste_id'=>$taste,
					 'order_id'=>$id,
					 'is_order'=>$type
					);
					Yii::app()->db->createCommand()->insert('nb_order_taste',$data);
				}
			}
			if($tastMemo){
				if($type){
					$sql = 'update nb_order set taste_memo=:tastMemo where lid=:lid';
				}else{
					$sql = 'update nb_order_product set taste_memo=:tastMemo where lid=:lid';
				}
				$conn = Yii::app()->db->createCommand($sql);
				$conn->bindValue(':tastMemo',$tastMemo);
				$conn->bindValue(':lid',$id);
				$conn->execute();
			}
			$transaction->commit(); //提交事务会真正的执行数据库操作
			return true;
		} catch (Exception $e) {
			$transaction->rollback(); //如果操作失败, 数据回滚
			return false;
		}
	}
	//保存产品口味
	public static function saveProductTaste($dpid,$productId,$tasteIds=array()){
		$transaction = Yii::app()->db->beginTransaction();
		try {
			$sql = 'delete from nb_product_taste where dpid=:dpid and product_id=:productId';
			$conn = Yii::app()->db->createCommand($sql);
			$conn->bindValue(':dpid',$dpid);
			$conn->bindValue(':productId',$productId);
			$conn->execute();
			if(!empty($tastesIds)){
				var_dump($tastesIds);
				foreach($tastesIds as $taste){
					$sql = 'SELECT NEXTVAL("product_taste") AS id';
					$maxId = Yii::app()->db->createCommand($sql)->queryRow();
					$data = array(
					 'lid'=>$maxId['id'],
					 'dpid'=>$dpid,
					 'create_at'=>date('Y-m-d H:i:s',time()),
					 'taste_id'=>$taste,
					 'product_id'=>$productId,
					);
					var_dump($data);exit;
					Yii::app()->db->createCommand()->insert('nb_product_taste',$data);
				}
			}
			$transaction->commit(); //提交事务会真正的执行数据库操作
			return true;
		} catch (Exception $e) {
			$transaction->rollback(); //如果操作失败, 数据回滚
			return false;
		}
	}
	public static function getTasteName($tasteId){
		$sql = 'SELECT name from nb_taste where lid=:lid';
		$taste = Yii::app()->db->createCommand($sql)->bindValue(':lid',$tasteId)->queryRow();
		return $taste['name'];
	}
	
}