<?php

class DefaultController extends BackendController
{
        public function beforeAction($action) {
		parent::beforeAction($action);
		if(!$this->companyId) {
			Yii::app()->user->setFlash('error' , '请选择公司');
			$this->redirect(array('company/index'));
		}
		return true;
	}
    
	public function actionIndex()
	{
		$typeId = Yii::app()->request->getParam('typeId');
                $stypeId = Yii::app()->request->getParam('stypeId','0');
                $sistemp = Yii::app()->request->getParam('sistemp','0');
                $ssid = Yii::app()->request->getParam('ssid','0');
                $op = Yii::app()->request->getParam('op','0');
                $title='请选择餐桌';
                $geturl='/op/'.$op.'/sistemp/'.$sistemp.'/ssid/'.$ssid.'/stypeId/'.$stypeId;
                //$siteNmae='';
                $siteTypes = $this->getTypes();
                if(empty($siteTypes)) {
			$typeId='tempsite';
		}
                //$modelsitet = SiteType::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $typeId,':dpid'=>  $this->companyId));
                if($op=='switch')
                {
                    if($sistemp=='0')
                    {
                        $title='被换餐桌：'.$siteTypes[$stypeId];
                        $modelsite = Site::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $ssid,':dpid'=>  $this->companyId));
                        $title=$title.'-->'.$modelsite->serial.'('.$modelsite->site_level.')'.'::请选择目标餐桌';
                    }else{
                        $title='被换餐桌：临时台/排队-->'.($ssid%1000).'：：请选择目标餐桌';
                    }
                }                
		
                if($typeId != 'tempsite')
                {
                    $typeKeys = array_keys($siteTypes);
                    $typeId = array_search($typeId, $typeKeys) ? $typeId : $typeKeys[0] ;
                }
		$criteria = new CDbCriteria;
		$models=array();
                if($typeId == 'tempsite'){
                        $criteria->condition =  't.delete_flag = 0 and t.status in ("1","2","3") and t.is_temp = 1 and t.dpid='.$this->companyId ;
                        $criteria->order = ' t.create_at desc ';
                        $models = SiteNo::model()->findAll($criteria);
                }else{
                        $criteria->with = 'siteType';
                        $criteria->condition =  't.delete_flag = 0 and t.type_id = '.$typeId.' and t.dpid='.$this->companyId ;
                        $criteria->order = ' t.create_at desc ';
                        $models = Site::model()->findAll($criteria);
                }
                //var_dump($models);exit;
		$this->render('index',array(
				'siteTypes' => $siteTypes,
				'models'=>$models,
				'typeId' => $typeId,
                                'title' => $title,
                                'geturl' => $geturl,
                                'ssid' => $ssid,
                                'sistemp' => $sistemp
		));
	}
        
        private function getTypes(){
		$types = SiteType::model()->findAll('dpid=:companyId and delete_flag=0' , array(':companyId' => $this->companyId)) ;
		$types = $types ? $types : array();
		return CHtml::listData($types, 'lid', 'name');
	}
        
        public function actionButton() {
		$sid = Yii::app()->request->getParam('sid','0');
                $status = Yii::app()->request->getParam('status','0');
                $istemp = Yii::app()->request->getParam('istemp','0');
                $typeId = Yii::app()->request->getParam('typeId','0');
              
		$model=array();
		$this->renderPartial('button' , array(
				'model' => $model,
				'sid' => $sid,
                                'status' => $status,                                
                                'istemp' => $istemp,
                                'typeId' => $typeId
		));
	}
        
                
        private function getRandChar($length){
            $str = null;
            $strPol = "0123456789";
            $max = strlen($strPol)-1;

            for($i=0;$i<$length;$i++){
                $str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
            }
            return $str;
        }
        
        private function getCode($companyId){
		$code=  $this->getRandChar(6);
                //var_dump($code);exit;
                return $code;/*apc should be deleted*/
                $ccode = apc_fetch($companyId.$code);
                while(!empty($ccode))
                {
                    $code= $this->getRandChar(6);                    
                    $ccode = apc_fetch($companyId.$code);
                }
                apc_store($companyId.$code,'1',0);//永久存储用apc_delete($key)删除
                return $code;
	}
        
        public function actionOpensite() {
		if(Yii::app()->request->isPostRequest) {
			$sid = Yii::app()->request->getPost('sid');
                        $siteNumber = Yii::app()->request->getPost('siteNumber');
                        $companyId = Yii::app()->request->getPost('companyId');
                        //$sid = Yii::app()->request->getPost('sid');
                        $istemp = Yii::app()->request->getPost('istemp','0');                        
                        $db = Yii::app()->db;
                        $transaction = $db->beginTransaction();
                        try {                          
                            if($istemp=="0")
                            {
                                $sqlsite="update nb_site set status=1,number=:number where lid=:sid and dpid=:companyId";
                                $commandsite=$db->createCommand($sqlsite);
                                $commandsite->bindValue(":number" , $siteNumber);
                                $commandsite->bindValue(":sid" , $sid);
                                $commandsite->bindValue(":companyId" , $companyId);
                                $commandsite->execute();
                            }
                            
                            $se=new Sequence("site_no");
                            $lid = $se->nextval();
                            $site_id=$sid;
                            if($istemp!=0)
                            {
                                $se=new Sequence("temp_site");
                                $site_id = $se->nextval();                            
                            }
                            $code = $this->getCode($companyId);
                            $data = array(
                                'lid'=>$lid,
                                'dpid'=>$companyId,
                                'create_at'=>date('Y-m-d H:i:s',time()),
                                'is_temp'=>$istemp,
                                'site_id'=>$site_id,
                                'status'=>'1',
                                'code'=>$code,
                                'number'=>$siteNumber,
                                'delete_flag'=>'0'
                            );
                            $db->createCommand()->insert('nb_site_no',$data);                            
                            $transaction->commit(); //提交事务会真正的执行数据库操作
                            echo json_encode(array('status'=>1,'message'=>'开台成功'));  
                            return true;
                    } catch (Exception $e) {
                            $transaction->rollback(); //如果操作失败, 数据回滚
                            echo json_encode(array('status'=>0,'message'=>'开台失败')); 
                            return false;
                    }
		}
	}
        
        public function actionClosesite() {
		if(Yii::app()->request->isPostRequest) {
			$sid = Yii::app()->request->getPost('sid');
                        $companyId = Yii::app()->request->getPost('companyId');
                        $istemp = Yii::app()->request->getPost('istemp','0');
                        
                        $db = Yii::app()->db;
                        $transaction = $db->beginTransaction();
                        try {  
                            if($istemp=="0")
                            {
                                $sqlsite="update nb_site set status='7' where lid=:sid and dpid=:companyId";
                                $commandsite=$db->createCommand($sqlsite);
                                $commandsite->bindValue(":sid" , $sid);
                                $commandsite->bindValue(":companyId" , $companyId);
                                $commandsite->execute();
                            }

                            $sqlsiteno="update nb_site_no set status='7' where site_id=:sid and is_temp=:istemp and dpid=:companyId and status in ('1','2')";
                            $commandsiteno=$db->createCommand($sqlsiteno);
                            $commandsiteno->bindValue(":sid" , $sid);
                            $commandsiteno->bindValue(":istemp" , $istemp);
                            $commandsiteno->bindValue(":companyId" , $companyId);
                            $commandsiteno->execute();
                            
                            $sqlorder="update nb_order set order_status='7' where site_id=:sid and is_temp=:istemp and dpid=:companyId and order_status in ('1','2')";
                            $commandorder=$db->createCommand($sqlorder);
                            $commandorder->bindValue(":sid" , $sid);
                            $commandorder->bindValue(":istemp" , $istemp);
                            $commandorder->bindValue(":companyId" , $companyId);
                            $commandorder->execute();
                            $transaction->commit(); //提交事务会真正的执行数据库操作
                            //
                            $criteria = new CDbCriteria;
                            $criteria->condition =  't.dpid='.$companyId.' and t.site_id='.$sid.' and t.is_temp='.$istemp ;
                            $criteria->order = ' t.lid desc ';
                            $siteNo = SiteNo::model()->find($criteria);
                            //apc_delete($siteNo->dpid.$siteNo->code);
                            echo json_encode(array('status'=>1,'message'=>'撤台成功'));  
                            return true;
                    } catch (Exception $e) {
                            $transaction->rollback(); //如果操作失败, 数据回滚
                            echo json_encode(array('status'=>0,'message'=>'撤台失败'));
                            return false;
                    }
		}
	}
        
        public function actionSwitchsite() {
		if(Yii::app()->request->isPostRequest) {
			$sid = Yii::app()->request->getPost('sid');
                        $companyId = Yii::app()->request->getPost('companyId');
                        $istemp = Yii::app()->request->getPost('istemp','0');
                        $ssid = Yii::app()->request->getPost('ssid',0);
                        $sistemp = Yii::app()->request->getPost('sistemp','0');
                        //echo json_encode(array('status'=>0,'message'=>$sid.'dd'.$companyId.'dd'.$istemp.'dd'.$ssid.'dd'.$sistemp));exit;
                        $db = Yii::app()->db;
                        $transaction = $db->beginTransaction();
                        try {
                            $number=0;
                            $status='1';
                            
                            $modelsn = SiteNo::model()->find('dpid=:companyId and delete_flag=0 and site_id=:lid and is_temp=:istemp and status in ("1","2","3")' , array(':companyId' => $companyId,':lid'=>$ssid,':istemp'=>$sistemp)) ;
                            
                            if($sistemp=='0')
                            {
                                $model = Site::model()->find('dpid=:companyId and delete_flag=0 and lid=:lid' , array(':companyId' => $companyId,':lid'=>$ssid)) ;
                                $number=$model->number;
                                $status=$model->status;
                            }else{
                                $number=$modelsn->number;
                                $status=$modelsn->status;
                            }
                            //echo json_encode(array('status'=>0,'message'=>$number.'dd'.$status));exit;
                            if($istemp=="0")
                            {
                                $sqlsite="update nb_site set status=:status,number=:number where lid=:sid and dpid=:companyId";
                                $commandsite=$db->createCommand($sqlsite);
                                $commandsite->bindValue(":number" , $number);
                                $commandsite->bindValue(":status" , $status);
                                $commandsite->bindValue(":sid" , $sid);
                                $commandsite->bindValue(":companyId" , $companyId);
                                $commandsite->execute();
                            }
                            if($sistemp=="0")
                            {
                                $sqlsite="update nb_site set status='6' where lid=:sid and dpid=:companyId";
                                $commandsite=$db->createCommand($sqlsite);
                                $commandsite->bindValue(":sid" , $ssid);
                                $commandsite->bindValue(":companyId" , $companyId);
                                $commandsite->execute();
                            }
                            $se=new Sequence("site_no");
                            $lid = $se->nextval();
                            $data = array(
                                'lid'=>$lid,
                                'dpid'=>$companyId,
                                'create_at'=>date('Y-m-d H:i:s',time()),
                                'is_temp'=>$istemp,
                                'site_id'=>$sid,
                                'status'=>$status,
                                'code'=>$modelsn->code,
                                'number'=>$modelsn->number,
                                'delete_flag'=>'0'
                            );
                            $db->createCommand()->insert('nb_site_no',$data);
                            
                            $modelsn->status='6';
                            $modelsn->save();
                            
                            $sqlorder="update nb_order set is_temp=:istemp,site_id=:sid where site_id=:ssid and is_temp=:sistemp and dpid=:companyId and order_status in ('1','2','3')";
                            $commandorder=$db->createCommand($sqlorder);
                            $commandorder->bindValue(":sid" , $sid);
                            $commandorder->bindValue(":istemp" , $istemp);
                            $commandorder->bindValue(":ssid" , $ssid);
                            $commandorder->bindValue(":sistemp" , $sistemp);
                            $commandorder->bindValue(":companyId" , $companyId);
                            $commandorder->execute();
                            $transaction->commit(); //提交事务会真正的执行数据库操作
                            echo json_encode(array('status'=>1,'message'=>'换台成功'));  
                            return true;
                    } catch (Exception $e) {
                            $transaction->rollback(); //如果操作失败, 数据回滚
                            echo json_encode(array('status'=>0,'message'=>'换台失败'));
                            return false;
                    }
		}                
	}
        
        public function actionOrder(){
		$sid = Yii::app()->request->getParam('sid',0);
		$istemp = Yii::app()->request->getParam('istemp',0);
		$companyId = Yii::app()->request->getParam('companyId',0);
                $typeId = Yii::app()->request->getParam('typeId',0);
                $orderId = Yii::app()->request->getParam('orderId',0);
                $order=null;
                $siteNo=null;
                if($orderId !='0')
                {
                    $order = Order::model()->find('lid=:lid and dpid=:dpid' , array(':lid'=>$orderId,':dpid'=>$companyId));
                    $criteria = new CDbCriteria;
                    $criteria->condition =  't.dpid='.$companyId.' and t.site_id='.$order->site_id.' and t.is_temp='.$order->is_temp ;
                    $criteria->order = ' t.lid desc ';
                    $siteNo = SiteNo::model()->find($criteria);
                }else{
                    $criteria = new CDbCriteria;
                    $criteria->condition =  't.dpid='.$companyId.' and t.site_id='.$sid.' and t.is_temp='.$istemp ;
                    $criteria->order = ' t.lid desc ';
                    $order = Order::model()->find($criteria);
                    $siteNo = SiteNo::model()->find($criteria);
                }
                                
                if(empty($order))
                {
                    $order=new Order();
                    $se=new Sequence("order");
                    $order->lid = $se->nextval();
                    $order->dpid=$companyId;
                    $order->create_at = date('Y-m-d H:i:s',time());
                    $order->lock_status = '0';
                    $order->order_status = '1';
                    $order->site_id = $siteNo->site_id;
                    $order->number = $siteNo->number;
                    $order->is_temp = $siteNo->is_temp;
                    //var_dump($order);exit;
                    $order->save();
                }
                
		//$orderProducts = OrderProduct::model()->findAll('dpid=:dpid and order_id=:orderid',array(':dpid'=>$companyId,':orderid'=>$order->order_id));
		$orderProducts = OrderProduct::getOrderProducts($order->lid,$order->dpid);
                //var_dump($orderProducts);exit;
                $orderProduct = new OrderProduct();
                $orderProduct->order_id = $order->lid;
                $productTotal = OrderProduct::getTotal($order->lid,$order->dpid);
		$total = Helper::calOrderConsume($order,$siteNo, $productTotal);
                $categories = $this->getCategories();
                $categoryId=0;
                $products = $this->getProducts($categoryId);
                $productslist=CHtml::listData($products, 'lid', 'product_name');
		//var_dump($order);exit;
		if(Yii::app()->request->isPostRequest){
			$order->attributes = Yii::app()->request->getPost('Order');
			if($order->order_status){
				$siteNo->delete_flag = 1;
				$order->pay_time = time();
                                //apc_delete($siteNo->dpid.$siteNo->code)
			}
			$transaction = Yii::app()->db->beginTransaction();
			try{
				if($order->save()) {
					if($order->order_status){
						$siteNo->save();
						Yii::app()->db->createCommand('delete from nb_cart where company_id=:companyId and code=:code')
						->bindValue(':companyId',$this->companyId)
						->bindValue(':code',$siteNo->code)
						->execute();
						$status = Helper::printList($order);
					}
					if(!$status['status']) {
						Yii::app()->user->setFlash('error',$status['msg']);
						throw new CException('请选择打印机');
					} else {
						Yii::app()->user->setFlash('success','结单成功');
					}
				}
				$transaction->commit();
				$this->redirect(array('order/index' , 'companyId' => $this->companyId));
			} catch(Exception $e){
				$transaction->rollback();
			}
		}
		$paymentMethods = $this->getPaymentMethodList();
		$this->render('order' , array(
				'model'=>$order,
				'orderProducts' => $orderProducts,
                                'orderProduct' => $orderProduct,
				'productTotal' => $productTotal ,
				'total' => $total,
				'paymentMethods'=>$paymentMethods,
                                'typeId' => $typeId,
                                'categories' => $categories,
                                'products' => $productslist
		));
	}
        
        private function getPaymentMethodList(){
		$paymentMethods = PaymentMethod::model()->findAll() ;
		return CHtml::listData($paymentMethods, 'lid', 'name');
	}
        
        public function actionAccount() {
		$orderId = Yii::app()->request->getParam('orderId','0');
                $companyId = Yii::app()->request->getParam('companyId','0');
                $criteria = new CDbCriteria;
		$criteria->condition =  't.dpid='.$companyId.' and t.lid='.$orderId ;
                $criteria->order = ' t.lid desc ';
                $model = Order::model()->find($criteria);
                
		$this->renderPartial('account' , array(
				'model' => $model
		));
	}
        
        private function getCategories(){
		$criteria = new CDbCriteria;
		$criteria->with = 'company';
		$criteria->condition =  't.delete_flag=0 and t.dpid='.$this->companyId ;
		$criteria->order = ' tree,t.lid asc ';
		
		$models = ProductCategory::model()->findAll($criteria);
                
		//return CHtml::listData($models, 'lid', 'category_name','pid');
		$options = array();
		$optionsReturn = array('--请选择分类--');
		if($models) {
			foreach ($models as $model) {
				if($model->pid == '0') {
					$options[$model->lid] = array();
				} else {
					$options[$model->pid][$model->lid] = $model->category_name;
				}
			}
                        //var_dump($options);exit;
		}
		foreach ($options as $k=>$v) {
                    //var_dump($k,$v);exit;
			$model = ProductCategory::model()->find('t.lid = :lid and dpid=:dpid',array(':lid'=>$k,':dpid'=>  $this->companyId));
			$optionsReturn[$model->category_name] = $v;
		}
		return $optionsReturn;
	}
        
        private function getProducts($categoryId){
                if($categoryId==0)
                {
                    //var_dump ('2',$categoryId);exit;
                    $products = Product::model()->findAll('dpid=:companyId and delete_flag=0' , array(':companyId' => $this->companyId));
                }else{
                    //var_dump ('3',$categoryId);exit;
                    $products = Product::model()->findAll('dpid=:companyId and category_id=:categoryId and delete_flag=0' , array(':companyId' => $this->companyId,':categoryId'=>$categoryId)) ;
                }
                $products = $products ? $products : array();
                //var_dump($products);exit;
                return $products;
		//return CHtml::listData($products, 'lid', 'product_name');
	}
        
        private function getCategoryList(){
		$categories = ProductCategory::model()->findAll('delete_flag=0 and dpid=:companyId' , array(':companyId' => $this->companyId)) ;
		//var_dump($categories);exit;
		return CHtml::listData($categories, 'lid', 'category_name');
	}
        
        public function actionAddProduct() {
                $companyId=Yii::app()->request->getParam('companyId','0');
                $typeId=Yii::app()->request->getParam('typeId','0');
		if(Yii::app()->request->isPostRequest){
                        $orderProduct = new OrderProduct();
                        $orderProduct->dpid = $companyId;
                        $orderProduct->create_at = date('Y-m-d H:i:s',time());
                        $orderProduct->delete_flag = '0';
                        $orderProduct->product_order_status = '1';
			$orderProduct->attributes = Yii::app()->request->getPost('OrderProduct');
                        $se=new Sequence("order_product");
                        $orderProduct->lid = $se->nextval();
                        //var_dump($orderProduct);exit;                   
                        //第一个菜需要更新订单状态。。。。
                        //添加产品时，还可以添加套餐。。。
                        if($orderProduct->save()){
				Yii::app()->user->setFlash('success' , '修改成功');
				$this->redirect(array('default/order' , 'companyId' => $this->companyId,'orderId' => $orderProduct->order_id,'typeId'=>$typeId));
			}  else {
                                Yii::app()->user->setFlash('success' , '修改失败');
                        }
                }
	}
}