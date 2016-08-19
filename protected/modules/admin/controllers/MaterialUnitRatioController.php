<?php
class MaterialUnitRatioController extends BackendController
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
	public function actionIndex(){
		//$categoryId = Yii::app()->request->getParam('cid',0);
		$criteria = new CDbCriteria;
		$criteria->with =array('company') ;
		$criteria->condition =  't.delete_flag=0 and t.dpid='.$this->companyId;
		$criteria->order = ' t.lid desc ';
        $pages = new CPagination(MaterialUnitRatio::model()->count($criteria));
        //$pages->setPageSize(1);
        $pages->applyLimit($criteria);
        //var_dump($pages);exit;
 		$models = MaterialUnitRatio::model()->findAll($criteria);
		$this->render('index',array(
				'models'=>$models,
				'pages'=>$pages,
		));
	}
	public function actionSetMealList() {

	}
	public function actionCreate(){
		$model = new MaterialUnitRatio();
		$model->dpid = $this->companyId ;
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('MaterialUnitRatio');
			
			$db = Yii::app()->db;
			$sql = 'select t.* from nb_material_unit t where t.delete_flag = 0 and t.lid = '.$model->stock_unit_id;
			$command2 = $db->createCommand($sql);
			$stockUnitId = $command2->queryRow()['muhs_code'];
				
			$sql = 'select t.* from nb_material_unit t where t.delete_flag = 0 and t.lid = '.$model->sales_unit_id;
			$command3 = $db->createCommand($sql);
			$salesUnitId = $command3->queryRow()['muhs_code'];
			
			if($stockUnitId&&$salesUnitId){
				$se=new Sequence("material_unit_ratio");
				$model->lid = $se->nextval();
				$model->create_at = date('Y-m-d H:i:s',time());
				$model->update_at = date('Y-m-d H:i:s',time());
				$model->delete_flag = '0';
				$model->mulhs_code = $stockUnitId;
		        $model->mushs_code = $salesUnitId;
				//var_dump($model);exit;
				if($model->save()){
					Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
					$this->redirect(array('materialUnitRatio/index' , 'companyId' => $this->companyId ));
				}
			}else{
				Yii::app()->user->setFlash('error',yii::t('app','添加失败！'));
				$this->redirect(array('materialUnitRatio/index' , 'companyId' => $this->companyId ));
			}
		}
		$this->render('create' , array(
				'model' => $model ,
		));
	}

	public function actionUpdate(){
		$id = Yii::app()->request->getParam('id');
		$model = MaterialUnitRatio::model()->find('lid=:unitId and dpid=:dpid' , array(':unitId' => $id,':dpid'=>  $this->companyId));
		$model->dpid = $this->companyId;
		Until::isUpdateValid(array($id),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('MaterialUnitRatio');
			$model->update_at=date('Y-m-d H:i:s',time());
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','修改成功！'));
				$this->redirect(array('materialUnitRatio/index' , 'companyId' => $this->companyId ));
			}
		}

		$this->render('update' , array(
				'model' => $model ,
		));
	}
	public function actionDelete(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('ids');
		Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_material_unit_ratio set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')
					->execute(array( ':companyId' => $this->companyId));
			$this->redirect(array('materialUnitRatio/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('m  aterialUnitRatio/index' , 'companyId' => $companyId)) ;
		}
	}
}