<?php
class WelcomeController extends BackendController
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

    public function actionList() {
    	$type = Yii::app()->request->getParam('type');
    	$this->render('list',array(
    			'companyId' => $this->companyId,
    			'type'=>$type,
    	));
    }
    public function actionList2() {
    	$type = Yii::app()->request->getParam('type');
    	$this->render('list2',array(
    			'companyId' => $this->companyId,
    			'type'=>$type,
    	));
    }

}
