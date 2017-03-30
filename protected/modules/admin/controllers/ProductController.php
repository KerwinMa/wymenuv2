<?php
class ProductController extends BackendController
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
			Yii::app()->user->setFlash('error' , yii::t('app','请选择公司'));
			$this->redirect(array('company/index'));
		}
		return true;
	}
	public function actionList(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$type = Yii::app()->request->getParam('type');
		$this->render('list',array('type'=>$type));
	}
	public function actionIndex(){
		
		$company = Company::model()->find('dpid=:companyId and delete_flag=0' , array(':companyId'=>$this->companyId));
		$comtype = $company->type;
		
		//var_dump($comtype);exit;
		$categoryId = Yii::app()->request->getParam('cid',0);
		$criteria = new CDbCriteria;
		$criteria->with = array('company','category');
		$criteria->condition =  't.delete_flag=0 and t.dpid='.$this->companyId;
		if($categoryId){
			$criteria->condition.=' and t.category_id = '.$categoryId;
		}
		$criteria->order = 't.sort asc,t.lid asc';
		$pages = new CPagination(Product::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = Product::model()->findAll($criteria);
		//var_dump($models);exit;
		$categories = $this->getCategories();
//                var_dump($categories);exit;
		$this->render('index',array(
				'models'=>$models,
				'pages'=>$pages,
				'categories'=>$categories,
				'categoryId'=>$categoryId,
				'comtype'=>$comtype,
		));
	}

	public function actionCreate(){
		$msg = '';
		if(Yii::app()->request->isAjaxRequest){
			$path = Yii::app()->basePath.'/../uploads/company_'.$this->companyId;
			$up = new CFileUpload();
			//设置属性(上传的位置， 大小， 类型， 名是是否要随机生成)
			$up -> set("path", $path);
			$up -> set("maxsize", 2*1024*1024);
			$up -> set("allowtype", array("png", "jpg","jpeg"));
		
			if($up -> upload("file")) {
				$msg = '/wymenuv2/uploads/company_'.$this->companyId.'/'.$up->getFileName();
			}else{
				$msg = $up->getErrorMsg();
			}
			echo $msg;exit;
		}
		$model = new Product();
		$istempp = Yii::app()->request->getParam('istempp',0);
		$model->dpid = $this->companyId ;
		//$model->create_time = time();
		if(Yii::app()->user->role > User::SHOPKEEPER) {
			Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
			$this->redirect(array('product/index' , 'companyId' => $this->companyId)) ;
		}
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Product');
			
			$cateID = $model->category_id;
			if(!empty($cateID)){
				$db = Yii::app()->db;
				$sql = 'select t.* from nb_product_category t where t.delete_flag = 0 and t.lid = '.$cateID;
				$command = $db->createCommand($sql);
				$categoryId = $command->queryRow();
				//var_dump($categoryId['chs_code']);exit;
				if(empty($model->member_price)){
					$model->member_price = $model->original_price;
				}
				$se=new Sequence("product");
				$lid = $se->nextval();
				$model->lid = $lid;
				$code=new Sequence("phs_code");
				$phs_code = $code->nextval();
				
				$model->create_at = date('Y-m-d H:i:s',time());
				$model->update_at = date('Y-m-d H:i:s',time());
				$model->chs_code = $categoryId['chs_code'];
				$model->phs_code = ProductCategory::getChscode($this->companyId, $lid, $phs_code);
				$model->delete_flag = '0';
				$py=new Pinyin();
				$model->simple_code = $py->py($model->product_name);
				//var_dump($model);exit;
				if($model->save()){
					Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
					$this->redirect(array('product/index' , 'companyId' => $this->companyId ));
				}
			}else{
				 $model->addError('category_id','必须添加二级分类');
			}
			
		}
		$categories = $this->getCategoryList();
		//$departments = $this->getDepartments();
                //echo 'ss';exit;
		$this->render('create' , array(
			'model' => $model ,
			'categories' => $categories,
			'istempp' => $istempp,
		));
	}
	
	public function actionUpdate(){
		$msg = '';
		if(Yii::app()->request->isAjaxRequest){
			$path = Yii::app()->basePath.'/../uploads/company_'.$this->companyId;
			$up = new CFileUpload();
			//设置属性(上传的位置， 大小， 类型， 名是是否要随机生成)
			$up -> set("path", $path);
			$up -> set("maxsize", 2*1024*1024);
			$up -> set("allowtype", array("png", "jpg","jpeg"));
				
			if($up -> upload("file")) {
				$msg = '/wymenuv2/uploads/company_'.$this->companyId.'/'.$up->getFileName();
			}else{
				$msg = $up->getErrorMsg();
			}
			echo $msg;exit;
		}
		if(Yii::app()->user->role > User::SHOPKEEPER) {
			Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
			$this->redirect(array('product/index' , 'companyId' => $this->companyId)) ;
		}
		$id = Yii::app()->request->getParam('id');
		$istempp = Yii::app()->request->getParam('istempp');
		$papage = Yii::app()->request->getParam('papage');
		//var_dump($istempp);exit;
		$model = Product::model()->find('lid=:productId and dpid=:dpid' , array(':productId' => $id,':dpid'=>  $this->companyId));
		$model->dpid = $this->companyId;
		//Until::isUpdateValid(array($id),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Product');
			if($model->category_id){
				$categoryId = ProductCategory::model()->find('lid=:lid and dpid=:companyId and delete_flag=0' , array(':lid'=>$model->category_id,':companyId'=>$this->companyId));
				$model->chs_code = $categoryId['chs_code'];
			}
                $py=new Pinyin();
                $model->simple_code = $py->py($model->product_name);
			$model->update_at=date('Y-m-d H:i:s',time());
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','修改成功！'.$msg));
				$this->redirect(array('product/index' , 'companyId' => $this->companyId ,'page' => $papage));
			}
		}
		$categories = $this->getCategoryList();
		//$departments = $this->getDepartments();
		$this->render('update' , array(
				'model' => $model ,
				'categories' => $categories,
				'istempp' => $istempp,
				'papage' => $papage,
		));
	}
	public function actionDelete(){
		if(Yii::app()->user->role > User::SHOPKEEPER) {
			Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
			$this->redirect(array('product/index' , 'companyId' => $this->companyId)) ;
		}
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('ids');
        //Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_product set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			Yii::app()->db->createCommand('update nb_product_set_detail set delete_flag=1 where product_id in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			Yii::app()->user->setFlash('success' , yii::t('app','删除成功'));
			$this->redirect(array('product/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('product/index' , 'companyId' => $companyId)) ;
		}
	}
	public function actionStatus(){
		$id = Yii::app()->request->getParam('id');
		$product = Product::model()->find('lid=:id and dpid=:companyId' , array(':id'=>$id,':companyId'=>$this->companyId));
		//var_dump($product->status);
		if($product){
			$product->saveAttributes(array('status'=>$product->status?0:1,'update_at'=>date('Y-m-d H:i:s',time())));
		}
		exit;
	}
	public function actionRecommend(){
		$id = Yii::app()->request->getParam('id');
		$product = Product::model()->find('lid=:id and dpid=:companyId' , array(':id'=>$id,':companyId'=>$this->companyId));
		
		if($product){
			$product->saveAttributes(array('recommend'=>$product->recommend==0?1:0,'update_at'=>date('Y-m-d H:i:s',time())));
		}
		exit;
	}
	private function getCategoryList(){
		$categories = ProductCategory::model()->findAll('delete_flag=0 and dpid=:companyId' , array(':companyId' => $this->companyId)) ;
		//var_dump($categories);exit;
		return CHtml::listData($categories, 'lid', 'category_name');
	}
	public function actionGetChildren(){
		$pid = Yii::app()->request->getParam('pid',0);
		if(!$pid){
			Yii::app()->end(json_encode(array('data'=>array(),'delay'=>400)));
		}
		$treeDataSource = array('data'=>array(),'delay'=>400);
		$categories = Helper::getCategories($this->companyId,$pid);
	
		foreach($categories as $c){
			$tmp['name'] = $c['category_name'];
			$tmp['id'] = $c['lid'];
			$treeDataSource['data'][] = $tmp;
		}
		Yii::app()->end(json_encode($treeDataSource));
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
	private function getDepartments(){
		$departments = Department::model()->findAll('company_id=:companyId',array(':companyId'=>$this->companyId)) ;
		return CHtml::listData($departments, 'department_id', 'name');
	}
	
	

	public function actionStore(){
		$pid = Yii::app()->request->getParam('pid');//菜品lid编号
		$showtype = Yii::app()->request->getParam('showtype');//下架类型，0表示自上下架，1表示统一上下架。
		$shownum = Yii::app()->request->getParam('shownum');//表示下架后菜品is_show字段的数值，0表示单品不显示，1表示都显示，6表示公司统一下架，7表示自下架。
		$pcode = Yii::app()->request->getParam('pcode');//菜品在公司内的唯一编码.
		$dpid = $this->companyId;
		$db = Yii::app()->db;
		$transaction = $db->beginTransaction();
		//$msg = $pid.'@@'.$shownum.'##'.$showtype.'$$'.$pcode.'%%'.$dpid;
		try
		{
			$is_sync = DataSync::getInitSync();
			//盘点日志
			//盘点日志
			if($showtype==0){
				Yii::app()->db->createCommand('update nb_product set is_show = '.$shownum.' where lid in ('.$pid.') and dpid = :companyId')
				->execute(array( ':companyId' => $this->companyId));
				//Yii::app()->user->setFlash('success' , yii::t('app','删除成功'));
				//$this->redirect(array('product/index' , 'companyId' => $companyId)) ;
				$transaction->commit();
				Yii::app()->end(json_encode(array("status"=>"success",'msg'=>'成功')));
			}else{
				$dpids = '000';
				$companys = Company::model()->findAll('dpid=:companyId or comp_dpid=:companyId and delete_flag=0' , array(':companyId'=>$this->companyId));
				foreach ($companys as $company){
					$dpids = $dpids .','.$company->dpid;
				}
				Yii::app()->db->createCommand('update nb_product set is_show = '.$shownum.' where phs_code in ('.$pcode.') and dpid in ('.$dpids.')')
				->execute();
				$transaction->commit();
				Yii::app()->end(json_encode(array("status"=>"success",'msg'=>'成功')));
			}
			return true;
		}catch (Exception $e) {
			$transaction->rollback(); //如果操作失败, 数据回滚
			Yii::app()->end(json_encode(array("status"=>"fail")));
			return false;
		}
	}
	
	
}