<?php

class AutodownorderController extends BaseYmallController
{

	/**
	 * @Author    zhang
	 * @DateTime  2017-11-01T14:45:22+0800
	 * @copyright [copyright]
	 * @license   [license]
	 * @version   [version]
	 * @return    [type]   自动生成采购订单/先删除购物车的商品/
	 *            [description]  查询店铺原料 剩余库存
	 *            				 循环比较库存与安全库存 (需要换算)
	 *            				 /比较库存与最大库存 (需要换算)
	 *            				 对低于安全库存的进行购物车添加(需要系数换算)
	 *
	 * 							 其他情况待定
	 */
	public function actionIndex()
	{
		//查询购物车信息->删除
		$user_id = substr(Yii::app()->user->userId,0,10);
		$user_name = Yii::app()->user->name;
		$db = Yii::app()->db;
		$info = GoodsCarts::model()->deleteAll('dpid=:dpid and  user_id=:user_id',array(':dpid'=>$this->companyId,':user_id'=>$user_id));
		$companyId = Company::model()->find('dpid=:dpid and delete_flag=0',array(':dpid'=>$this->companyId))->comp_dpid;
		if (!$companyId) {
			$companyId=$this->companyId;
		}
		$sql = 'SELECT t.lid,t.mphs_code,t.material_name,SUM(stock.stock) AS stock,safe.safe_stock,safe.max_stock,unit.unit_name FROM nb_product_material t
			LEFT JOIN nb_product_material_stock stock ON (t.lid=stock.material_id and stock.dpid=t.dpid AND stock.delete_flag=0)
			LEFT JOIN (select a.* from nb_product_material_safe a where a.dpid = '.$this->companyId.' AND  UNIX_TIMESTAMP(a.create_at) in(select max(UNIX_TIMESTAMP(b.create_at)) from nb_product_material_safe b where b.dpid=a.dpid and b.material_id=a.material_id) ) `safe` ON (t.lid=safe.material_id and safe.dpid=t.dpid AND safe.delete_flag=0)
			LEFT JOIN (SELECT u.unit_name,r.sales_unit_id FROM nb_material_unit u LEFT JOIN nb_material_unit_ratio r on(u.lid=r.sales_unit_id AND r.delete_flag=0 ) where u.delete_flag=0) `unit` ON (t.sales_unit_id=unit.sales_unit_id )
			WHERE (t.delete_flag=0 and t.dpid='.$this->companyId.') GROUP BY t.lid';
		$stocks = $db->createCommand($sql)->queryAll();
		$product_name = '';
		$product_lost = '';
		if($stocks){
			// p($stocks);
			foreach ($stocks as  $stock) {
				//判断是否有最大库存数据 
				// 有:比较实时库存和最大库存的一半
				// 		大于 跳过
				// 		小于 执行添加购物车
				// 无:代表这最近一个月没有消耗 ,查看库存是否为0
				// 		为0 提示该产品需要手动添加购物车
				// 		不为0 不进行任何操作
				if($stock['max_stock']){
					//如果原料库存少于最大库存的一半就将该产品列入添加队列
					if ($stock['stock'] < ($stock['max_stock']/2)) {
						//优先默认仓库  单位系数按照总部
						$sql1 = 'select g.goods_code,g.goods_name,g.lid as glid,g.main_picture,g.original_price,g.member_price,g.goods_unit,c.company_name,c.dpid,mc.lid,mc.category_name,gm.material_code,mu.unit_ratio from nb_goods g '
								.' left join nb_company c on(c.dpid=g.dpid) '
								.' left join nb_material_category mc on (mc.lid=g.category_id )'
								.' left join nb_goods_material gm on (g.lid=gm.goods_id )'
								.' left join nb_material_unit_ratio mu ON( gm.unit_code=mu.unit_code and mu.dpid='.$companyId.' )'
								.' where g.dpid in(select ad.depot_id from nb_area_group_depot ad where ad.delete_flag=0 and ad.area_group_id in (select area_group_id from nb_area_group_company where company_id='.$this->companyId.' and delete_flag=0) and ad.is_selected=1) and g.delete_flag=0 and gm.material_code='.$stock['mphs_code'];
						$product = $db->createCommand($sql1)->queryRow();
						if (!$product) {
							$sql2 = 'select g.goods_code,g.goods_name,g.lid as glid,g.main_picture,g.original_price,g.member_price,g.goods_unit,c.company_name,c.dpid,mc.lid,mc.category_name,gm.material_code,mu.unit_ratio from nb_goods g '
									.' left join nb_company c on(c.dpid=g.dpid) '
									.' left join nb_material_category mc on (mc.lid=g.category_id )'
									.' left join nb_goods_material gm on (g.lid=gm.goods_id )'
									.' left join nb_material_unit_ratio mu ON( gm.unit_code=mu.unit_code and mu.dpid='.$companyId.' )'
									.' where g.dpid in(select ad.depot_id from nb_area_group_depot ad where ad.delete_flag=0 and ad.area_group_id in (select area_group_id from nb_area_group_company where company_id='.$this->companyId.' and delete_flag=0)) and g.delete_flag=0 and gm.material_code='.$stock['mphs_code'];
							$product = $db->createCommand($sql2)->queryRow();
						}
						if ($product) {
							$num = ceil(($stock['max_stock']-$stock['safe_stock'])/$product['unit_ratio']);
							$goods_cart = new GoodsCarts();
							$se=new Sequence("goods_carts");
							$lid = $se->nextval();
							$is_sync = DataSync::getInitSync();
							$goods_cart->lid = $lid;
							$goods_cart->dpid = $this->companyId;
							$goods_cart->create_at = date('Y-m-d H:i:s',time());
							$goods_cart->update_at = date('Y-m-d H:i:s',time());
							$goods_cart->stock_dpid = $product['dpid'];
							$goods_cart->goods_name = $product['goods_name'];
							$goods_cart->goods_id = $product['glid'];
							$goods_cart->goods_code = $product['goods_code'];
							$goods_cart->material_code = $product['material_code'];
							$goods_cart->user_id = $user_id;
							$goods_cart->user_name = $user_name;
							$goods_cart->promotion_price = $product['member_price'];
							$goods_cart->price = $product['original_price'];
							$goods_cart->num = $num;
							$goods_cart->end_time = '';
							$goods_cart->delete_flag=0;
							$goods_cart->is_sync = $is_sync;
							$goods_cart->insert();
						} else{
							//没有查找到商品原料
							$product_lost .= ' '.$stock['material_name'];
						}
					}
				}else{
					//近一个月没有该原料的使用数据
					if ($stock['stock']==0 || $stock['stock']<0) {
						$product_name .= ' '.$stock['material_name'];
					}
				}
			}
		}
		echo json_encode($product_name.'-'.$product_lost);exit;


	}

}