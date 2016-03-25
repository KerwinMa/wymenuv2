<?php
class NormalpromotionController extends BackendController
{

	public function actions() {
		return array(
				'upload'=>array(
						'class'=>'application.extensions.swfupload.SWFUploadAction',
						//注意这里是绝对路径,.EXT是文件后缀名替代符号
						'filepath'=>Helper::genFileName().'.EXT',
						//'onAfterUpload'=>array($this,'saveFile'),
				)
		);
	}
    public function beforeAction($action) {
    	parent::beforeAction($action);
    	if(!$this->companyId && $this->getAction()->getId() != 'upload') {
    		Yii::app()->user->setFlash('error' , '请选择公司˾');
    		$this->redirect(array('company/index'));
    	}
    	return true;
    }

    
    public function actionIndex(){
    	//$brand = Yii::app()->admin->getBrand($this->companyId);
    	$criteria = new CDbCriteria;
    	$criteria->select = 't.*';
    	$criteria->order = ' update_at desc';
    	$criteria->addCondition("t.dpid= ".$this->companyId);
    	$criteria->addCondition('delete_flag=0');
    	//$criteria->params[':brandId'] = $brand->brand_id;
    
    	$pages = new CPagination(NormalPromotion::model()->count($criteria));
    	$pages->applyLimit($criteria);
    	$models = NormalPromotion::model()->findAll($criteria);
    	 
    	$this->render('index',array(
    			'models'=>$models,
    			'pages'=>$pages,
    	));
    }
    
    
    
	public function saveFile($event){
		$fullName = $event->sender['name'];
		$extensionName = $event->sender['uploadedFile']->getExtensionName();
		$path = $event->sender['path'];
		
		$fileName = substr($fullName,0,strpos($fullName,'.'));
		$image = Yii::app()->image->load($path.'/'.$fullName);
		$image->resize(160,160)->quality(100)->sharpen(20);
		$image->save($path.'/'.$fileName.'_thumb.'.$extensionName); // or $image->save('images/small.jpg');
		return true;
	}
	/**
	 * 创建活动，并发送系统消息
	 */
	public function actionCreate(){
		$model = new NormalPromotion();
		$model->dpid = $this->companyId ;
		$brdulvs = $this->getBrdulv();
		//$model->create_time = time();
		//var_dump($model);exit;
		$is_sync = DataSync::getInitSync();
		if(Yii::app()->request->isPostRequest) {
			$db = Yii::app()->db;
			//$transaction = $db->beginTransaction();
			//try{
			$model->attributes = Yii::app()->request->getPost('NormalPromotion');
			$groupID = Yii::app()->request->getParam('hidden1');
			$weekdayID = Yii::app()->request->getParam('weekday');
			$gropids = array();
			$gropids = explode(',',$groupID);
			
			//$db = Yii::app()->db;
		
			$se=new Sequence("normal_promotion");
			$model->lid = $se->nextval();
			if(!empty($groupID)){
				//$sql = 'delete from nb_normal_branduser where normal_promotion_id='.$lid.' and dpid='.$this->companyId;
				//$command=$db->createCommand($sql);
				//$command->execute();
				foreach ($gropids as $gropid){
					$userid = new Sequence("normal_branduser");
					$id = $userid->nextval();
					
					$data = array(
							'lid'=>$id,
							'dpid'=>$this->companyId,
							'create_at'=>date('Y-m-d H:i:s',time()),
							'update_at'=>date('Y-m-d H:i:s',time()),
							'normal_promotion_id'=>$model->lid,
							'to_group'=>"2",
							'brand_user_lid'=>$gropid,
							'delete_flag'=>'0',
							'is_sync'=>$is_sync,
					);
					$command = $db->createCommand()->insert('nb_normal_branduser',$data);
					//var_dump($gropid);exit;
				}
			}
			$model->create_at = date('Y-m-d H:i:s',time());
			$model->update_at = date('Y-m-d H:i:s',time());
			$model->weekday = $weekdayID;
			$model->delete_flag = '0';
			$model->is_sync = $is_sync;
				
			//$transaction->commit(); //提交事务会真正的执行数据库操作
			//return true;
			//}catch (Exception $e) {
			//	$transaction->rollback(); //如果操作失败, 数据回滚
			//Yii::app()->end(json_encode(array("status"=>"fail")));
			//	return false;
			//}
				
			//$py=new Pinyin();
			//$model->simple_code = $py->py($model->product_name);
			//var_dump($model);exit;
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
				$this->redirect(array('normalpromotion/index' , 'companyId' => $this->companyId ));
			}
		}
		
		//$categories = $this->getCategoryList();
		//$departments = $this->getDepartments();
		//echo 'ss';exit;
		$this->render('create' , array(
				'model' => $model ,
				'brdulvs'=>$brdulvs,
				//'categories' => $categories
		));
	}	

	
	/**
	 * 编辑活动
	 */
	public function actionUpdate(){
		
		$lid = Yii::app()->request->getParam('lid');
		//echo 'ddd';
		//$groupID = Yii::app()->request->getParam('str');
		//var_dump($groupID);exit;
		$brdulvs = $this->getBrdulv();
		$model = NormalPromotion::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $lid,':dpid'=> $this->companyId));
		Until::isUpdateValid(array($lid),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		$is_sync = DataSync::getInitSync();
		//$db = Yii::app()->db;
// 		$transaction = $db->beginTransaction();
// 		try
// 		{
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('NormalPromotion');
			$groupID = Yii::app()->request->getParam('hidden1');
			$weekdayID = Yii::app()->request->getParam('weekday');
			$gropids = array();
			$gropids = explode(',',$groupID);
			$db = Yii::app()->db;
			if(!empty($groupID)){
				
				//$sql = 'delete from nb_normal_branduser where normal_promotion_id='.$lid.' and dpid='.$this->companyId;
				$sql = 'update nb_normal_branuser set delete_flag = "1",is_sync ='.$sync.' where normal_promotion_id='.$lid.' and dpid='.$this->companyId;
				$command=$db->createCommand($sql);
				$command->execute();
				foreach ($gropids as $gropid){
					$se = new Sequence("normal_branduser");
					$id = $se->nextval();
					$is_sync = DataSync::getInitSync();
					$data = array(
							'lid'=>$id,
							'dpid'=>$this->companyId,
							'create_at'=>date('Y-m-d H:i:s',time()),
							'update_at'=>date('Y-m-d H:i:s',time()),
							'normal_promotion_id'=>$lid,
							'to_group'=>"2",
							'brand_user_lid'=>$gropid,
							'delete_flag'=>'0',
							'is_sync'=>$is_sync,
					);
					$command = $db->createCommand()->insert('nb_normal_branduser',$data);
					//var_dump($gropid);exit;
				}
			}else{
				//$is_sync = DataSync::getInitSync();
				$sql = 'update nb_normal_branduser set delete_flag = 1,is_sync ='.$is_sync.' where normal_promotion_id='.$lid.' and dpid='.$this->companyId;
				$command=$db->createCommand($sql);
				$command->execute();
			}
			//print_r(explode(',',$groupID));
			//var_dump($gropid);exit;
			$model->update_at=date('Y-m-d H:i:s',time());
			$model->weekday = $weekdayID;
			$model->is_sync=$is_sync;
			//$gropid = array();
			//$gropid = (dexplode(',',$groupID));
			//var_dump(dexplode(',',$groupID));exit;
			//($model->attributes);var_dump(Yii::app()->request->getPost('Printer'));exit;
			//$transaction->commit(); //提交事务会真正的执行数据库操作
			if($model->save()){
				Yii::app()->user->setFlash('success' , yii::t('app','修改成功'));
				$this->redirect(array('normalpromotion/index' , 'companyId' => $this->companyId));
			}
				//return true;
		}
		$this->render('update' , array(
				'model'=>$model,
				'brdulvs'=>$brdulvs,
		));
	}		
		public function actionDetailindex(){
			//$sc = Yii::app()->request->getPost('csinquery');
			$promotionID = Yii::app()->request->getParam('lid');
			$typeId = Yii::app()->request->getParam('typeId');
			$categoryId = Yii::app()->request->getParam('cid',"");
			$fromId = Yii::app()->request->getParam('from','sidebar');
			$csinquery=Yii::app()->request->getPost('csinquery',"");
			//var_dump($csinquery);exit;
			$db = Yii::app()->db;
			if($typeId=='product')
			{
					
				if(empty($promotionID)){
					$promotionID = Yii::app()->request->getParam('promotionID');
				}
				if(empty($promotionID)){
					echo "操作有误！请点击右上角的返回继续编辑";
					exit;
				}
				//$sql = 'select t1.promotion_money,t1.promotion_discount,t1.order_num,t1.is_set,t1.product_id,t1.normal_promotion_id,t.* from nb_product t left join nb_normal_promotion_detail t1 on(t.dpid = t1.dpid and t.lid = t1.product_id and t1.delete_flag = 0) where t.delete_flag = 0 and t.dpid='.$this->companyId;
				// 			$command=$db->createCommand($sql);
				// 			$models= $command->queryAll();
				// 			//var_dump($sql);exit;
					
					
				// 			$criteria = new CDbCriteria;
				// 			$criteria->with = array('company','category','normalPromotionDetail');
				// 			$criteria->condition =  't.delete_flag=0 and t.dpid='.$this->companyId ;
				// 			//var_dump($criteria);exit;
				if(!empty($categoryId)){
					//$criteria->condition.=' and t.category_id = '.$categoryId;
					$sql = 'select k.* from(select t1.promotion_money,t1.promotion_discount,t1.order_num,t1.is_set,t1.product_id,t1.normal_promotion_id,t.* from nb_product t left join nb_normal_promotion_detail t1 on(t.dpid = t1.dpid and t.lid = t1.product_id and t1.is_set = 0 and t1.delete_flag = 0 and t1.normal_promotion_id = '.$promotionID.') where t.delete_flag = 0 and t.dpid='.$this->companyId.' and t.category_id = '.$categoryId.' ) k';
						
				}
		
				elseif(!empty($csinquery)){
					//$criteria->condition.=' and t.simple_code like "%'.strtoupper($csinquery).'%"';
					$sql = 'select k.* from(select t1.promotion_money,t1.promotion_discount,t1.order_num,t1.is_set,t1.product_id,t1.normal_promotion_id,t.* from nb_product t left join nb_normal_promotion_detail t1 on(t.dpid = t1.dpid and t.lid = t1.product_id and t1.is_set = 0 and t1.delete_flag = 0 and t1.normal_promotion_id = '.$promotionID.') where t.delete_flag = 0 and t.dpid='.$this->companyId.' and t.simple_code like "%'.strtoupper($csinquery).'%" ) k';
						
				}else{
					$sql = 'select k.* from(select t1.promotion_money,t1.promotion_discount,t1.order_num,t1.is_set,t1.product_id,t1.normal_promotion_id,t.* from nb_product t left join nb_normal_promotion_detail t1 on(t.dpid = t1.dpid and t.lid = t1.product_id and t1.is_set = 0 and t1.delete_flag = 0 and t1.normal_promotion_id = '.$promotionID.') where t.delete_flag = 0 and t.dpid='.$this->companyId.') k' ;
						
				}
				//var_dump($sql);exit;
				$count = $db->createCommand(str_replace('k.*','count(*)',$sql))->queryScalar();
				//var_dump($count);exit;
				$pages = new CPagination($count);
				$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
				$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
				$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
				$models = $pdata->queryAll();
				$categories = $this->getCategories();
				//var_dump($models);exit;
				//			$criteria = new CDbCriteria;
				// 			$pages = new CPagination(count($models));
				// 				    $pages->setPageSize(1);
				// 			$pages->applyLimit($criteria);
				// 			$categories = $this->getCategories();
					
				//$pages = new CPagination(Product::model()->count($criteria));
				//	    $pages->setPageSize(1);
				//$pages->applyLimit($criteria);
				//$models = Product::model()->findAll($criteria);
					
				//$categories = $this->getCategories();
				//var_dump($promotionID);exit;
				$this->render('detailindex',array(
						'models'=>$models,
						'pages'=>$pages,
						'categories'=>$categories,
						'categoryId'=>$categoryId,
						'typeId' => $typeId,
						'promotionID'=>$promotionID
				));
			}else{
				if(empty($promotionID)){
					$promotionID = Yii::app()->request->getParam('promotionID');
				}
				$sql = 'select k.* from(select t1.promotion_money,t1.promotion_discount,t1.order_num,t1.is_set,t1.product_id,t1.normal_promotion_id,t.* from nb_product_set t left join nb_normal_promotion_detail t1 on(t.dpid = t1.dpid and t.lid = t1.product_id and t1.is_set = 1 and t1.delete_flag = 0 and t1.normal_promotion_id = '.$promotionID.') where t.delete_flag = 0 and t.dpid='.$this->companyId.') k';
				$count = $db->createCommand(str_replace('k.*','count(*)',$sql))->queryScalar();
				//var_dump($count);exit;
				$pages = new CPagination($count);
				$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
				$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
				$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
				$models = $pdata->queryAll();
		
		
				// 			$criteria = new CDbCriteria;
				// 			$criteria->with = array('normalPromotionDetail');
				// 			$criteria->condition =  't.delete_flag=0 and t.dpid='.$this->companyId ;
				// 			$pages = new CPagination(ProductSet::model()->count($criteria));
				// 			$pages->applyLimit($criteria);
				// 			$models = ProductSet::model()->findAll($criteria);
				//var_dump($promotionID);exit;
				$this->render('detailindex',array(
						'models'=>$models,
						'pages'=>$pages,
						'typeId' => $typeId,
						'promotionID'=>$promotionID
				));
			}
		}

		public function actionStore(){
			$id = Yii::app()->request->getParam('id');
			$promotionID = Yii::app()->request->getParam('promotionID');
			$typeId = Yii::app()->request->getParam('typeId');
			$proID = Yii::app()->request->getParam('proID');
			$proNum = Yii::app()->request->getParam('proNum');
			$dpid = $this->companyId;
			//$promotion_money = Yii::app()->request->getParam('promotion_money');
			//$promotion_discount = Yii::app()->request->getParam('promotion_discount');
			//$order_num = Yii::app()->request->getParam('order_num');
			$is_set = Yii::app()->request->getParam('is_set');
			//$db = Yii::app()->db;
			//Yii::app()->end(json_encode(array("status"=>"success")));
			//var_dump($order_num);exit;
			//$sql='';
		
			//Yii::app()->end(json_encode(array("status"=>"success")));
			$db = Yii::app()->db;
			$transaction = $db->beginTransaction();
			try
			{	
				$is_sync = DataSync::getInitSync();
				$se=new Sequence("normal_promotion_detail");
				$lid = $se->nextval();
				//$create_at = date('Y-m-d H:i:s',time());
				//$update_at = date('Y-m-d H:i:s',time());
				$sql='';
				// 			$sql = 'delete from nb_normal_promotion_detail where dpid="'.$dpid.'" and normal_promotion_id="'.$promotionID.'" and product_id="'.$id.'"';
				// 			$command=$db->createCommand($sql);
				// 			$command->execute();
		
				if($typeId=='product')
				{
					//$sql = 'delete from nb_normal_promotion_detail where is_set=0 and dpid='.$dpid.' and normal_promotion_id='.$promotionID.' and product_id='.$id;
					//var_dump($sql);exit;
					$sql = 'update nb_normal_promotion_detail set delete_flag = "1", is_sync ='.$is_sync.' where is_set=0 and dpid='.$dpid.' and normal_promotion_id='.$promotionID.' and product_id='.$id;
						
					$command=$db->createCommand($sql);
					$command->execute();
						
					if($proID=='0'){
						
						$data = array(
								'lid'=>$lid,
								'dpid'=>$dpid,
								'create_at'=>date('Y-m-d H:i:s',time()),
								'update_at'=>date('Y-m-d H:i:s',time()),
								'normal_promotion_id'=>$promotionID,
								'product_id'=>$id,
								'is_set'=>0,
								'is_discount'=>0,
								'promotion_money'=>$proNum,
								'promotion_discount'=>'1.00',
								'order_num'=>'0',
								'delete_flag'=>'0',
								'is_sync'=>$is_sync,
						);
						//$db->createCommand()->insert('normal_promotion_detail',$data);
		
						//$sql="insert into nb_normal_promotion_detail (lid,dpid,create_at,update_at,normal_promotion_id,product_id,is_set,promotion_money,promotion_discount,order_num,)
						//		values ('$model->lid','$this->companyId','')";
					}elseif($proID=="1"){
							
						// 			$sql = 'delete from nb_normal_promotion_detail where is_set=0 and dpid='.$dpid.' and normal_promotion_id='.$promotionID.' and product_id='.$id;
						// 			$command=$db->createCommand($sql);
						// 			$command->execute();
						//$is_sync = DataSync::getInitSync();
						//$sql='insert nb_normal_promotion_detail set is_set="0", product_id = '.$id.', promotion_discount = '.$proNum.', order_num = "'.$order_num.'" where lid='.$id.' and dpid='.$this->companyId;
						$data = array(
								'lid'=>$lid,
								'dpid'=>$dpid,
								'create_at'=>date('Y-m-d H:i:s',time()),
								'update_at'=>date('Y-m-d H:i:s',time()),
								'normal_promotion_id'=>$promotionID,
								'product_id'=>$id,
								'is_set'=>0,
								'is_discount'=>1,
								'promotion_money'=>'0.00',
								'promotion_discount'=>$proNum,
								'order_num'=>'0',
								'delete_flag'=>'0',
								'is_sync'=>$is_sync,
						);
						//$db->createCommand()->insert('nb_normal_promotion_detail',$data);
		
		
					}
					//var_dump($sql);exit;
				}else{
					//$sql = 'delete from nb_normal_promotion_detail where is_set=1 and dpid='.$dpid.' and normal_promotion_id='.$promotionID.' and product_id='.$id;
					$sql = 'update nb_normal_promotion_detail set delete_flag = "1", is_sync ='.$is_sync.' where is_set=1 and dpid='.$dpid.' and normal_promotion_id='.$promotionID.' and product_id='.$id;
						
					$command=$db->createCommand($sql);
					$command->execute();
						
					if($proID=='0')
					{
						//$is_sync = DataSync::getInitSync();
						$data = array(
								'lid'=>$lid,
								'dpid'=>$dpid,
								'create_at'=>date('Y-m-d H:i:s',time()),
								'update_at'=>date('Y-m-d H:i:s',time()),
								'normal_promotion_id'=>$promotionID,
								'product_id'=>$id,
								'is_set'=>1,
								'is_discount'=>0,
								'promotion_money'=>$proNum,
								'promotion_discount'=>'1.00',
								'order_num'=>'0',
								'delete_flag'=>'0',
								'is_sync'=>$is_sync,
						);
							
					}elseif($proID=='1'){
						// 				$sql = 'delete from nb_normal_promotion_detail where is_set=1 and dpid='.$dpid.' and normal_promotion_id='.$promotionID.' and product_id='.$id;
						// 				$command=$db->createCommand($sql);
						// 				$command->execute();
						//$is_sync = DataSync::getInitSync();
						//$sql='insert nb_normal_promotion_detail set is_set="1", product_id = '.$id.', promotion_discount = '.$proNum.', order_num = '.$order_num.' where lid='.$id.' and dpid='.$this->companyId;
						$data = array(
								'lid'=>$lid,
								'dpid'=>$dpid,
								'create_at'=>date('Y-m-d H:i:s',time()),
								'update_at'=>date('Y-m-d H:i:s',time()),
								'normal_promotion_id'=>$promotionID,
								'product_id'=>$id,
								'is_set'=>1,
								'is_discount'=>1,
								'promotion_money'=>'0.00',
								'promotion_discount'=>$proNum,
								'order_num'=>'0',
								'delete_flag'=>'0',
								'is_sync'=>$is_sync
						);
					}
				}//Yii::app()->end(json_encode(array("status"=>"success","promotion"=>$promotionID)));
				//$db->createCommand()->insert('normal_promotion_detail',$data);
				$command = $db->createCommand()->insert('nb_normal_promotion_detail',$data);
				//Yii::app()->end(json_encode(array("status"=>"success","promotion"=>$promotionID)));
		
				$transaction->commit(); //提交事务会真正的执行数据库操作
				Yii::app()->end(json_encode(array("status"=>"success","promotion"=>$promotionID)));
				// 		if($command->execute()){
				// 			Yii::app()->end(json_encode(array("status"=>"success")));
				// 		}else{
				// 			Yii::app()->end(json_encode(array("status"=>"fail")));
				// 		}
				return true;
			}catch (Exception $e) {
				$transaction->rollback(); //如果操作失败, 数据回滚
				Yii::app()->end(json_encode(array("status"=>"fail")));
				return false;
			}		
		}		
		
		
		public function actionDetaildelete(){
			$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
			$ids = Yii::app()->request->getParam('id');
			$is_sync = DataSync::getInitSync();
			//        Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
			if(!empty($ids)) {
				Yii::app()->db->createCommand('update nb_normal_promotion_detail set delete_flag="1", is_sync ='.$is_sync.' where product_id in('.$ids.') and dpid = :companyId')
				->execute(array( ':companyId' => $this->companyId));
				Yii::app()->end(json_encode(array("status"=>"success")));
				//$this->redirect(array('normalpromotion/detailindex' , 'companyId' => $companyId)) ;
			} else {
				Yii::app()->user->setFlash('error' , yii::t('app','请选择要移除的项目'));
				$this->redirect(array('normalpromotion/detailindex' , 'companyId' => $companyId)) ;
			}
		}
		
		
		public function actionPromotiondetail(){
			$sc = Yii::app()->request->getPost('csinquery');
			$promotionID = Yii::app()->request->getParam('lid');
			//$typeId = Yii::app()->request->getParam('typeId');
			$categoryId = Yii::app()->request->getParam('cid',"");
			$fromId = Yii::app()->request->getParam('from','sidebar');
			$csinquery=Yii::app()->request->getPost('csinquery',"");
			//var_dump($csinquery);exit;
			$db = Yii::app()->db;
			
					
				if(empty($promotionID)){
					$promotionID = Yii::app()->request->getParam('promotionID');
				}
				if(empty($promotionID)){
					echo "操作有误！请点击右上角的返回继续编辑";
					exit;
				}
				
				if(!empty($categoryId)){
					//$criteria->condition.=' and t.category_id = '.$categoryId;
					$sql = 'select k.* from(select t.promotion_money,t.promotion_discount,t.order_num,t.is_set,t.product_id,t.normal_promotion_id,t1.* 
							from nb_normal_promotion_detail t left join nb_product t1 on(t.dpid = t1.dpid and t1.lid = t.product_id  and t1.delete_flag = 0 ) 
							where t.delete_flag = 0 and t.dpid='.$this->companyId.' and t.is_set = 0 and t.normal_promotion_id = '.$promotionID.' and t1.category_id = '.$categoryId.' ) k';
				
				}
				
				elseif(!empty($csinquery)){
					//$criteria->condition.=' and t.simple_code like "%'.strtoupper($csinquery).'%"';
					$sql = 'select k.* from(select t.promotion_money,t.promotion_discount,t.order_num,t.is_set,t.product_id,t.normal_promotion_id,t1.* 
							from nb_normal_promotion_detail t left join nb_product t1 on(t.dpid = t1.dpid and t1.lid = t.product_id and t1.delete_flag = 0 ) 
									where t.delete_flag = 0 and t.dpid='.$this->companyId.' and t.is_set = 0 and t.normal_promotion_id = '.$promotionID.' and t1.simple_code like "%'.strtoupper($csinquery).'%" ) k';
				
				}else{
					$sql = 'select k.* from(select t.promotion_money,t.promotion_discount,t.order_num,t.is_set,t.product_id,t.normal_promotion_id,t1.* 
							from nb_normal_promotion_detail t left join nb_product t1 on(t.dpid = t1.dpid and t1.lid = t.product_id and t1.delete_flag = 0 ) 
									where t.delete_flag = 0 and t.dpid='.$this->companyId.' and t.is_set = 0 and t.normal_promotion_id = '.$promotionID.') k' ;
				
				}
					//$sql = 'select k.* from(select t1.promotion_money,t1.promotion_discount,t1.order_num,t1.is_set,t1.product_id,t1.normal_promotion_id,t.* from nb_normal_promotion_detail t1  where t1.is_set = 0 and t1.normal_promotion_id ='.$promotionID.' t1.delete_flag = 0 and t1.dpid='.$this->companyId.') k' ;
		
				
				//var_dump($sql);exit;
				$count = $db->createCommand(str_replace('k.*','count(*)',$sql))->queryScalar();
				//var_dump($count);exit;
				$pages = new CPagination($count);
				$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
				$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
				$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
				$models = $pdata->queryAll();
				$categories = $this->getCategories();
				$this->render('promotiondetail',array(
						'models'=>$models,
						'pages'=>$pages,
						'categories'=>$categories,
						'categoryId'=>$categoryId,
						//'typeId' => $typeId,
						'promotionID'=>$promotionID
				));
			
		}
		
		
		
			private function getBrdulv(){
				$criteria = new CDbCriteria;
				$criteria->with = '';
				$criteria->condition = ' t.delete_flag=0 and t.dpid='.$this->companyId ;
				$criteria->order = ' t.min_total_points asc ' ;
				$brdules = BrandUserLevel::model()->findAll($criteria);
				if(!empty($brdules)){
					return $brdules;
				}
				// 		else{
				// 			return flse;
				// 		}
			}
			private function getCategories(){
				$criteria = new CDbCriteria;
				$criteria->with = 'company';
				$criteria->condition =  't.delete_flag=0 and t.dpid='.$this->companyId ;
				$criteria->order = ' tree,t.lid asc ';
			
				$models = ProductCategory::model()->findAll($criteria);
			
				//return CHtml::listData($models, 'lid', 'category_name','pid');
				$options = array();
				$optionsReturn = array(yii::t('app','--请选择分类--'));
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
			//var_dump($sc);exit;

	/**
	 * 删除现金券
	 */
	public function actionDelete(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('ids');
		$is_sync = DataSync::getInitSync();
        //        Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_normal_promotion set delete_flag=1, is_sync ='.$is_sync.' where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			$this->redirect(array('normalpromotion/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目!!!'));
			$this->redirect(array('normalpromotion/index' , 'companyId' => $companyId)) ;
		}
	}

	/**
	 * 现金券列表
	 */

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Cashcard the loaded model
	 * @throws CHttpException
	 */
	
	public function getProductSetPrice($productSetId,$dpid){
		$proSetPrice = '';
		$sql = 'select sum(t.price*t.number) as all_setprice,t.set_id from nb_product_set_detail t where t.set_id ='.$productSetId.' and t.dpid ='.$dpid.' and t.delete_flag = 0 and is_select = 1 ';
		$connect = Yii::app()->db->createCommand($sql);
		//	$connect->bindValue(':site_id',$siteId);
		//	$connect->bindValue(':dpid',$dpid);
		$proSetPrice = $connect->queryRow();
		//var_dump($proSetPrice);exit;
		if(!empty($proSetPrice)){
			return $proSetPrice['all_setprice'] ;
		}
		else{
			return flse;
		}
	}
	

	/*
	 *
	* 获取会员等级。。。
	*
	* */

}
