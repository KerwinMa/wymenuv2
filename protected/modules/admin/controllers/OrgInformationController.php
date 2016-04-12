<?php
class OrgInformationController extends BackendController
{
	public function actionIndex(){
		$orgclassId = Yii::app()->request->getParam('cid',0);
		$criteria = new CDbCriteria;
		$criteria->with = array('company','orgclass');
		$criteria->condition =  't.delete_flag=0 and t.dpid='.$this->companyId;
		if($orgclassId){
			$criteria->condition.=' and t.classification_id = '.$orgclassId;
		}
		//$criteria->condition.=' and t.lid = '.$orgclassId;
		$pages = new CPagination(OrganizationInformation::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = OrganizationInformation::model()->findAll($criteria);
		
		$categories = $this->getCategories();
                //var_dump($categoryId);exit;
		$this->render('index',array(
				'models'=>$models,
				'pages'=>$pages,
				'categories'=>$categories,
				'orgclassId'=>$orgclassId
		));
	}
	public function actionCreate(){
		$model = new OrganizationInformation();
		$model->dpid = $this->companyId ;

		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('OrganizationInformation');
                        $se=new Sequence("product_material");
                        $model->lid = $se->nextval();
                        $model->create_at = date('Y-m-d H:i:s',time());
                        $model->update_at = date('Y-m-d H:i:s',time());
                        $model->delete_flag = '0';
                        $py=new Pinyin();
                        $model->material_name = $py->py($model->material_name);
                         var_dump($model);exit;
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
				$this->redirect(array('orgInformation/index' , 'companyId' => $this->companyId ));
			}
		}
		$categories = $this->getSockUnit();
		$categories = $categories = OrganizationInformation::model()->findAll('delete_flag=0 and dpid=:companyId' , array(':companyId' => $this->companyId)) ;
		//var_dump($categories);exit;
		$this->render('create' , array(
			'model' => $model ,
			'categories' => $categories
		));
	}
	
	public function actionUpdate(){
		$id = Yii::app()->request->getParam('id');
		$model = OrganizationInformation::model()->find('lid=:materialId and dpid=:dpid' , array(':materialId' => $id,':dpid'=>  $this->companyId));
		$model->dpid = $this->companyId;
		Until::isUpdateValid(array($id),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('OrganizationInformation');
                        $py=new Pinyin();
                        $model->material_name = $py->py($model->material_name);
			$model->update_at=date('Y-m-d H:i:s',time());
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','修改成功！'));
				$this->redirect(array('orgInformation/index' , 'companyId' => $this->companyId ));
			}
		}
		$categories = $this->getCategoryList();
		//$departments = $this->getDepartments();
		$this->render('update' , array(
				'model' => $model ,
				'categories' => $categories
		));
	}
	public function actionDelete(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('ids');
                Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_product_material set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			$this->redirect(array('orgInformation/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('orgInformation/index' , 'companyId' => $companyId)) ;
		}
	}
	
	public function actionGetChildren(){
		$pid = Yii::app()->request->getParam('pid',0);
		if(!$pid){
			Yii::app()->end(json_encode(array('data'=>array(),'delay'=>400)));
		}
		$treeDataSource = array('data'=>array(),'delay'=>400);
		$categories = Helper::getCategory($this->companyId,$pid);
	
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
		$criteria->order = ' t.lid asc ';
		
		$models = OrganizationClassification::model()->findAll($criteria);
                
		//return CHtml::listData($models, 'lid', 'category_name','pid');
		$options = array();
		$optionsReturn = array(yii::t('app','--请选择分类--'));
		foreach ($options as $k=>$v) {
                    //var_dump($k,$v);exit;
			$model = OrganizationClassification::model()->find('t.lid = :lid and dpid=:dpid',array(':lid'=>$k,':dpid'=>  $this->companyId));
			$optionsReturn[$model->classification_name] = $v;
		}
		return $optionsReturn;
	}
	private function getSockUnit(){
		
	}
}









