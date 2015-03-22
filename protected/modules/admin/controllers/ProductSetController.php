<?php
class ProductSetController extends BackendController
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
		if(!$this->companyId) {
			Yii::app()->user->setFlash('error' , '请选择公司');
			$this->redirect(array('company/index'));
		}
		return true;
	}
	public function actionIndex(){
		$criteria = new CDbCriteria;
		$criteria->condition =  't.dpid='.$this->companyId .' and delete_flag=0';
		$pages = new CPagination(ProductSet::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		
		$models = ProductSet::model()->findAll($criteria);
		
		$this->render('index',array(
			'models'=>$models,
			'pages'=>$pages
		));
	}
	public function actionCreate(){
		$model = new ProductSet();
		$model->dpid = $this->companyId ;
		
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('ProductSet');
                        $se=new Sequence("porduct_set");
                        $model->lid = $se->nextval();
                        $model->create_at = date('Y-m-d H:i:s',time());
                        $model->delete_flag = '0';
                        $py=new Pinyin();
                        $model->simple_code = $py->py($model->set_name);
                        //var_dump($model);exit;
			if($model->save()) {
				Yii::app()->user->setFlash('success' , '添加成功');
				$this->redirect(array('productSet/index','companyId' => $this->companyId));
			}
		}
		$this->render('create' , array(
				'model' => $model 
		));
	}
	public function actionUpdate(){
		$lid = Yii::app()->request->getParam('lid');
                //echo 'ddd';
		$model = ProductSet::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $lid,':dpid'=> $this->companyId));
		//var_dump($model);exit;
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('ProductSet');
                        $py=new Pinyin();
                        $model->simple_code = $py->py($model->set_name);
                        //var_dump($model->attributes);var_dump(Yii::app()->request->getPost('ProductSet'));exit;
			if($model->save()){
				Yii::app()->user->setFlash('success' , '修改成功');
				$this->redirect(array('productSet/index' , 'companyId' => $this->companyId));
			}
		}
		$this->render('update' , array(
				'model'=>$model,
		));
	}
        
	public function actionDelete(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('ids');
                //var_dump($ids);exit;
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_product_set set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			
			Yii::app()->db->createCommand('update nb_product_set_detail set delete_flag=1 where set_id in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			$this->redirect(array('productSet/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , '请选择要删除的项目');
			$this->redirect(array('productSet/index' , 'companyId' => $companyId)) ;
		}
	}
        
        public function actionDetailIndex(){
		$pwlid = Yii::app()->request->getParam('lid');
                $criteria = new CDbCriteria;
                $criteria->with = array('product');
                //$criteria->with = 'printer';
		$criteria->condition =  't.dpid='.$this->companyId .' and t.set_id='.$pwlid.' and t.delete_flag=0 and product.delete_flag=0';
                $criteria2 = new CDbCriteria;
		$criteria2->condition =  't.dpid='.$this->companyId .' and t.lid='.$pwlid.' and t.delete_flag=0';
		$pages = new CPagination(ProductSetDetail::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		
		$models = ProductSetDetail::model()->findAll($criteria);
                
		$psmodel = ProductSet::model()->find($criteria2);
               // var_dump($psmodel);exit;
		$this->render('detailindex',array(
			'models'=>$models,
                        'psmodel'=>$psmodel,
			'pages'=>$pages
		));
	}
	public function actionDetailCreate(){
		$model = new ProductSetDetail();
		$model->dpid = $this->companyId ;
		$pslid = Yii::app()->request->getParam('psid');
                $model->set_id=$pslid;
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('ProductSetDetail');
                        $se=new Sequence("porduct_set_detail");
                        $model->lid = $se->nextval();
                        $model->create_at = date('Y-m-d H:i:s',time());
                        $model->delete_flag = '0';
                        //var_dump($model);exit;
			if($model->save()) {
				Yii::app()->user->setFlash('success' , '添加成功');
				$this->redirect(array('productSet/detailindex','companyId' => $this->companyId,'lid'=>$model->set_id));
			}
		}
                //$printers = $this->getPrinters();
                //$products = $this->getProducts();
                $categories = $this->getCategories();
                $categoryId=0;
                $products = $this->getProducts($categoryId);
                $productslist=CHtml::listData($products, 'lid', 'product_name');
		$this->render('detailcreate' , array(
				'model' => $model,
                                'categories' => $categories,
                                'categoryId' => $categoryId,
                                'products' => $productslist
		));
	}
	public function actionDetailUpdate(){
		$lid = Yii::app()->request->getParam('lid');
                //echo 'ddd';
		$model = ProductSetDetail::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $lid,':dpid'=> $this->companyId));
		//var_dump($model);exit;
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('ProductSetDetail');
                        //var_dump($model);var_dump(Yii::app()->request->getPost('ProductSetDetail'));exit;
			if($model->save()){
				Yii::app()->user->setFlash('success' , '修改成功');
				$this->redirect(array('productSet/detailindex' , 'companyId' => $this->companyId,'lid' => $model->set_id));
			}
		}
                //$printers = $this->getPrinters();
                $categories = $this->getCategories();
                $categoryId=  $this->getCategoryId($lid);
                $products = $this->getProducts($categoryId);
                $productslist=CHtml::listData($products, 'lid', 'product_name');
		$this->render('detailupdate' , array(
				'model'=>$model,
                                'categories' => $categories,
                                'categoryId' => $categoryId,
                                'products' => $productslist
		));
	}
        
	public function actionDetailDelete(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
                $printset = Yii::app()->request->getParam('psid');
		$ids = Yii::app()->request->getPost('ids');
                //var_dump($ids);exit;
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_product_set_detail set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			$this->redirect(array('productSet/detailindex' , 'companyId' => $companyId,'lid'=>$printset)) ;
		} else {
			Yii::app()->user->setFlash('error' , '请选择要删除的项目');
			$this->redirect(array('productSet/detailindex' , 'companyId' => $companyId,'lid'=>$printset)) ;
		}
	}	
        
        public function actionGetChildren(){
		$categoryId = Yii::app()->request->getParam('pid',0);
		if(!$categoryId){
			Yii::app()->end(json_encode(array('data'=>array(),'delay'=>400)));
		}
		
                $treeDataSource = array('data'=>array(),'delay'=>400);
		$produts=  $this->getProducts($categoryId);
	
		foreach($produts as $c){
			$tmp['name'] = $c['product_name'];
			$tmp['id'] = $c['lid'];
			$treeDataSource['data'][] = $tmp;
		}
		Yii::app()->end(json_encode($treeDataSource));
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
        
        private function getCategoryId($lid){
                $db = Yii::app()->db;
                $sql = "SELECT category_id from nb_product_set_detail sd,nb_product p where sd.dpid=p.dpid and sd.product_id=p.lid and sd.lid=:lid";
                $command=$db->createCommand($sql);
                $command->bindValue(":lid" , $lid);
                return $command->queryScalar();
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
        
        private function getCategoryList(){
		$categories = ProductCategory::model()->findAll('delete_flag=0 and dpid=:companyId' , array(':companyId' => $this->companyId)) ;
		//var_dump($categories);exit;
		return CHtml::listData($categories, 'lid', 'category_name');
	}
}