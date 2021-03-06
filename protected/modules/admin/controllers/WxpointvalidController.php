<?php
class WxpointvalidController extends BackendController
{
	public function beforeAction($action) {
		parent::beforeAction($action);
		if(!$this->companyId) {
			Yii::app()->user->setFlash('error' ,yii::t('app','请选择公司'));
			$this->redirect(array('company/index'));
		}
		return true;
	}
	public function actionIndex() {
		$criteria = new CDbCriteria;
		$criteria->addCondition('t.dpid=:dpid and t.delete_flag=0');
		$criteria->order = ' t.lid desc ';
		$criteria->params[':dpid']=$this->companyId;
		
		$pages = new CPagination(PointsValid::model()->count($criteria));
		//$pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = PointsValid::model()->findAll($criteria);
		
		$this->render('index',array(
				'models'=>$models,
				'pages' => $pages
		));
	}
	public function actionCreate() {
		$model = new PointsValid ;
		$model->dpid = $this->companyId ;
		$model->is_available = '1';
                
		if(Yii::app()->request->isPostRequest && Yii::app()->user->role <= User::SHOPKEEPER) {
			$model->attributes = Yii::app()->request->getPost('PointsValid');
                        if($model->is_available=="0")
                        {
                            $sqlavailable = "update nb_points_valid set is_available='1' where dpid=".$this->companyId;                    
                            Yii::app()->db->createCommand($sqlavailable)->execute();                    
                        }
                        $se=new Sequence("points_valid");
                        $model->lid = $se->nextval();
                        $model->create_at=date('Y-m-d H:i:s',time());
                        $model->update_at=date('Y-m-d H:i:s',time());
			$model->delete_flag = '0';
                        //var_dump($model);exit;
			if($model->save()) {
				Yii::app()->user->setFlash('success' , yii::t('app','添加成功'));
				$this->redirect(array('wxpointvalid/index' , 'companyId' => $this->companyId));
			}
		}else{Yii::app()->user->setFlash('error' , yii::t('app','无权限'));}
		$this->render('create' , array(
				'model' => $model
		));
	}
	public function actionUpdate(){
		$lid = Yii::app()->request->getParam('lid');
		$model = PointsValid::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $lid,':dpid'=>  $this->companyId));
		//Until::isUpdateValid(array($lid),$this->companyId,$this);//0,表示企业任何时候都在云端更新。			
                        
		if(Yii::app()->request->isPostRequest && Yii::app()->user->role <= User::SHOPKEEPER) {
			$model->attributes = Yii::app()->request->getPost('PointsValid');
                        if($model->is_available=="0")
                        {
                            $sqlavailable = "update nb_points_valid set is_available='1' where dpid=".$this->companyId;                    
                            Yii::app()->db->createCommand($sqlavailable)->execute();                    
                        }
			$model->update_at=date('Y-m-d H:i:s',time());
                        //var_dump($model->attributes);exit;
			if($model->save()){
				Yii::app()->user->setFlash('success' , yii::t('app','修改成功'));
				$this->redirect(array('wxpointvalid/index' , 'companyId' => $this->companyId));
			}
		}else{Yii::app()->user->setFlash('error' , yii::t('app','无权限'));}
		$this->render('update' , array(
			'model'=>$model
		));
	}
	public function actionDelete(){
		if(Yii::app()->user->role > User::SHOPKEEPER) {
			Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
			$this->redirect(array('wxpointvalid/index' , 'companyId' => $this->companyId)) ;
		}
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('lid');
		//Until::isUpdateValid($ids,$this->companyId,$this);//0,表示企业任何时候都在云端更新。			
                if(!empty($ids)) {
			foreach ($ids as $id) {
				$model = PointsValid::model()->find('lid=:id and dpid=:companyId' , array(':id' => $id , ':companyId' => $companyId)) ;
				if($model) {
					$model->saveAttributes(array('delete_flag'=>1,'update_at'=>date('Y-m-d H:i:s',time())));
				}
			}
			$this->redirect(array('wxpointvalid/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('wxpointvalid/index' , 'companyId' => $companyId)) ;
		}
	}
	
}