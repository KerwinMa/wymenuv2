<?php

class PadController extends Controller
{
	public $layout = '/layouts/padmain';
	/**
	 * 
	 * setting the companyId and padId
	 */
	public function actionIndex()
	{
		session_start();
		$companyId = Yii::app()->request->getParam('companyid',0);
                $padId = Yii::app()->request->getParam('padid',0);
                
		if($companyId){
			$_SESSION['companyId'] = $companyId;
		}
                if($padId){
			$_SESSION['padId'] = $padId;
		}
                //var_dump($companyId,$padId);
                if(!empty($companyId)&&!empty($padId))
                {
                    //$model=  Pad::model()->
                    //收银pad
                    $this->redirect(array('admin/login'));
                    //点餐pad
                    //detell whether this pad site is opensite!
                    //has to the site else opensite and go to the product page!
                    //取密码pad
                    
                }else{
                    $model = new Pad();
                    $this->render('create' , array('model' => $model));
                }		
	}
        
        public function actionBind(){
		$companyid = Yii::app()->request->getParam('companyid',0);
                $padid = Yii::app()->request->getParam('padid',0);
                if(empty($companyid)||empty($padid))
                {
                    echo "fail";
                }
                $pad=Pad::model()->find(' dpid=:companyId and lid=:padid', array(':companyId'=>$companyid,':padid'=>$padid));
                $pad->is_bind='1';
                if($pad->save())
                {
                    echo "success";
                }
	}
        
        public function actionGetInfo(){
		$companyid = Yii::app()->request->getParam('companyid',0);
                $padid = Yii::app()->request->getParam('padid',0);
                if(empty($companyid)||empty($padid))
                {
                    echo "0";
                }
                $pad=Pad::model()->find(' dpid=:companyId and lid=:padid', array(':companyId'=>$companyid,':padid'=>$padid));
                if($pad)
                {
                    echo $pad->server_address;
                }else{
                    echo "0";
                }
	}
	
        public function actionGetJob(){
		$companyid = Yii::app()->request->getParam('companyid',0);
                $jobid = Yii::app()->request->getParam('jobid',0);                
                Gateway::getOnlineStatus();
                $store = Store::instance('wymenu');
                $printData = $store->get($companyid."_".$jobid);
                if(empty($printData))
                {
                    $printData="";
                }
                echo $printData;
	}
        
        public function actionGetPadList(){
		$companyid = Yii::app()->request->getParam('companyid',0);
                if(!$companyid){
			Yii::app()->end(json_encode(array('data'=>array(),'delay'=>400)));
		}
		
                $treeDataSource = array('data'=>array(),'delay'=>400);
		$pads = Pad::model()->findAll('dpid=:companyId and delete_flag=0' , array(':companyId' => $companyid));
	
		foreach($pads as $c){
			$tmp['name'] = $c['name'];
			$tmp['id'] = $c['lid'];
			$treeDataSource['data'][] = $tmp;
		}
		Yii::app()->end(json_encode($treeDataSource));
	}
}