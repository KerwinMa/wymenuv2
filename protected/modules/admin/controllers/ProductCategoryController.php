<?php
class ProductCategoryController extends BackendController
{
	public function beforeAction($action) {
		parent::beforeAction($action);
		if(!$this->companyId) {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择公司'));
			$this->redirect(array('company/index'));
		}
		return true;
	}
	public function actionIndex(){
		//$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$criteria = new CDbCriteria;
		$criteria->with = 'company';
		$criteria->condition =  't.delete_flag=0 and t.dpid='.$this->companyId ;
		$criteria->order = ' tree,lid asc ';
		
		$models = ProductCategory::model()->findAll($criteria);
		
		$id = Yii::app()->request->getParam('id',0);
		$expandModel = ProductCategory::model()->find('lid=:id and dpid=:dpid and delete_flag=0',array(':id'=>$id,':dpid'=>  $this->companyId));
		
                $expandNode = $expandModel?explode(',',$expandModel->tree):array(0);
		//var_dump(substr('0000000000'.$expandNode[2],-10,10));exit;
		$this->render('index',array(
				'models'=>$models,
				'expandNode'=>$expandNode
		));
	}
	public function actionCreate() {
		$pid = Yii::app()->request->getParam('pid',0);
		$model = new ProductCategory() ;
		$model->dpid = $this->companyId ;
		if($pid) {
			$model->pid = $pid;
		}
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('ProductCategory');
			//var_dump($_POST['ProductCategory'],$model->attributes);exit;
                        $se=new Sequence("product_category");
                        $model->lid = $se->nextval();
                        $model->create_at = date('Y-m-d H:i:s',time());
                        $model->delete_flag = '0';
                        $model->update_at=date('Y-m-d H:i:s',time());
			if($model->save()){
                                //var_dump($model);exit;
				if($model->pid!='0'){
					$parent = ProductCategory::model()->find('lid=:pid and dpid=:dpid' , array(':pid'=>$model->pid,':dpid'=>  $this->companyId));
					$model->tree = $parent->tree.','.$model->lid;
				} else {
					$model->tree = '0,'.$model->lid;
				}
                                //var_dump($model);exit;
				$model->save();
				Yii::app()->user->setFlash('success' ,yii::t('app', '添加成功'));
				$this->redirect(array('productCategory/index' , 'id'=>$model->lid,'companyId' => $this->companyId));
			}
		}
		$this->renderPartial('_form1' , array(
				'model' => $model,
				'action' => $this->createUrl('productCategory/create' , array('companyId'=>$this->companyId))
		));
	}
	public function actionUpdate() {
		$id = Yii::app()->request->getParam('id');
		$model = ProductCategory::model()->find('lid=:id and dpid=:dpid', array(':id' => $id,':dpid'=>  $this->companyId));
                Until::isUpdateValid(array($id),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('ProductCategory');
                        $model->update_at=date('Y-m-d H:i:s',time());
			if($model->save()){
				Yii::app()->user->setFlash('success' ,yii::t('app', '修改成功'));
				$this->redirect(array('productCategory/index' , 'id'=>$model->lid,'companyId' => $this->companyId));
			}
		}
		$this->renderPartial('_form1' , array(
				'model' => $model,
				'action' => $this->createUrl('productCategory/update' , array(
						'companyId'=>$this->companyId,
						'id'=>$model->lid
				))
		));
	}
	public function actionDelete(){
		$id = Yii::app()->request->getParam('id');
                Until::isUpdateValid(array($id),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		$model = ProductCategory::model()->find('lid=:id and dpid=:companyId' , array(':id'=>$id,':companyId'=>$this->companyId));
		//var_dump($id,  $this->companyId,$model);exit;
		if($model) {
			$model->deleteCategory();
			Yii::app()->user->setFlash('success',yii::t('app','删除成功！'));
		}
		$this->redirect(array('productCategory/index','companyId'=>$this->companyId,'id'=>$model->pid));
	}
	
	
	
}