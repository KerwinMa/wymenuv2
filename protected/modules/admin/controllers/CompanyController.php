<?php
class CompanyController extends BackendController
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
	public function actionList(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		
		$this->render('list');
	}
	public function actionIndex(){
		$provinces = Yii::app()->request->getParam('province',0);
		$citys = Yii::app()->request->getParam('city',0);
		$areas = Yii::app()->request->getParam('area',0);
		
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
	
		$criteria = new CDbCriteria;
		$criteria->with = 'property';
		if(Yii::app()->user->role <= User::POWER_ADMIN_VICE)
		{
			$criteria->condition =' t.delete_flag=0 ';
		}else if(Yii::app()->user->role >= '5' && Yii::app()->user->role <= '9')
		{
			$criteria->condition =' t.delete_flag=0 and dpid in (select tt.dpid from nb_company tt where tt.comp_dpid='.Yii::app()->user->companyId.' and tt.delete_flag=0 ) or dpid='.Yii::app()->user->companyId;
		}else{
			$criteria->condition = ' t.delete_flag=0 and dpid='.Yii::app()->user->companyId ;
		}
		$province = $provinces;
		$city = $citys;
		$area = $areas;
		//var_dump($criteria);exit;
		if($citys == '市辖区'|| $citys == '省直辖县级行政区划' || $citys == '市辖县'){
			$city = '0';
		}
		if($areas == '市辖区'){
			$area = '0';
		}
		if($province){
			$criteria->addCondition('province like "'.$province.'"');
		}
		if($city){
			$criteria->addCondition('city like "'.$city.'"');
		}
		if($area){
			$criteria->addCondition('county_area like "'.$area.'"');
		}
		$pages = new CPagination(Company::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = Company::model()->findAll($criteria);
		$this->render('index',array(
				'models'=> $models,
				'pages'=>$pages,
				'province'=>$provinces,
				'city'=>$citys,
				'area'=>$areas,
		));
	}
	public function actionIndex1(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
                
		$criteria = new CDbCriteria;
                if(Yii::app()->user->role == User::POWER_ADMIN)
                {
                    $criteria->condition =' delete_flag=0 ';
                }else if(Yii::app()->user->role == '2')
                {
                    $criteria->condition =' delete_flag=0 and dpid in (select tt.company_id from nb_user_company tt, nb_user tt1 where tt.dpid=tt1.dpid and tt.user_id=tt1.lid and tt.delete_flag=0 and tt.dpid='.Yii::app()->user->companyId.' and tt1.username="'.Yii::app()->user->id.'" )';
                }else{
                    $criteria->condition = ' delete_flag=0 and dpid='.Yii::app()->user->companyId ;
                }
		//var_dump($criteria);exit;
		
		$pages = new CPagination(Company::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = Company::model()->findAll($criteria);
		
		$this->render('index1',array(
				'models'=> $models,
				'pages'=>$pages,
		));
	}
	protected function afterSave()
	{
		if(parent::afterSave()) {
			if($this->isPostRequest) {
				$this->comp_dpid = Yii::app()->db->getLastInsertID();
			} else {
				//$this->update_time = date("Y-m-d H:i:s");
			}
			return true;
		} else {
			return false;
		}
	}
	public function actionCreate(){
		$type = '-1';
		$type2 = 'create';
		if(Yii::app()->user->role <= User::ADMIN_AREA) {
		
		$model = new Company();
		$model->create_at = date('Y-m-d H:i:s');
		//var_dump($model);exit;
		$db = Yii::app()->db;
		if(Yii::app()->user->role <= User::POWER_ADMIN_VICE){
			if(Yii::app()->request->isPostRequest) {
				$model->attributes = Yii::app()->request->getPost('Company');
				
				$pay_online = Yii::app()->request->getParam('pay_online');
				
				$province = Yii::app()->request->getParam('province1');
				$city = Yii::app()->request->getParam('city1');
				$area = Yii::app()->request->getParam('area1');
				
				$model->country = 'china';
				$model->province = $province;
				$model->city = $city;
				$model->county_area = $area;
	            $model->create_at = date('Y-m-d H:i:s',time());
	            $model->update_at = date('Y-m-d H:i:s',time());
	            //$model->comp_dpid=mysql_insert_id();
	            $model->type="0";
				if($model->save()){
					$comp_dpid = Yii::app()->db->getLastInsertID();
					$userid = new Sequence("company_property");
					$id = $userid->nextval();
					$data = array(
							'lid'=>$id,
							'dpid'=>$comp_dpid,
							'create_at'=>date('Y-m-d H:i:s',time()),
							'update_at'=>date('Y-m-d H:i:s',time()),
							'pay_type'=>$pay_online,
							'pay_channel'=>'',
							'delete_flag'=>'0',
					);
					$command = $db->createCommand()->insert('nb_company_property',$data);
						
					$sql = 'update nb_company set comp_dpid = '.$comp_dpid.' where delete_flag = 0 and dpid = '.$comp_dpid;
					$command=Yii::app()->db->createCommand($sql);
					$command->execute();
					//$model->comp_dpid = $post->attributes['dpid'];
					//var_dump($id);exit;
					Yii::app()->user->setFlash('success',yii::t('app','创建成功'));
					$this->redirect(array('company/index','companyId'=> $this->companyId));
				} else {
					Yii::app()->user->setFlash('error',yii::t('app','创建失败'));
				}
			}
		}
		elseif(Yii::app()->user->role > 3 && Yii::app()->user->role <= 9){
			if(Yii::app()->request->isPostRequest) {
				$model->attributes = Yii::app()->request->getPost('Company');
				
				$pay_online = Yii::app()->request->getParam('pay_online');
				
				$province = Yii::app()->request->getParam('province1');
				$city = Yii::app()->request->getParam('city1');
				$area = Yii::app()->request->getParam('area1');
				
				$model->country = 'china';
				$model->province = $province;
				$model->city = $city;
				$model->county_area = $area;
				$model->create_at=date('Y-m-d H:i:s',time());
				$model->update_at=date('Y-m-d H:i:s',time());
				$model->comp_dpid = $this->getCompanyId(Yii::app()->user->username);
				//var_dump($model);exit;
				//$model->type="0";
				if($model->save()){
					$comp_dpid = Yii::app()->db->getLastInsertID();
					$userid = new Sequence("company_property");
					$id = $userid->nextval();
					$data = array(
							'lid'=>$id,
							'dpid'=>$comp_dpid,
							'create_at'=>date('Y-m-d H:i:s',time()),
							'update_at'=>date('Y-m-d H:i:s',time()),
							'pay_type'=>$pay_online,
							'pay_channel'=>'',
							'delete_flag'=>'0',
					);
					$command = $db->createCommand()->insert('nb_company_property',$data);
					
					Yii::app()->user->setFlash('success',yii::t('app','创建成功'));
					$this->redirect(array('company/index','companyId'=> $this->companyId));
				} else {
					Yii::app()->user->setFlash('error',yii::t('app','创建失败'));
				}
			}
		}
			$role = Yii::app()->user->role;
			$printers = $this->getPrinterList();
			//var_dump($printers);exit;
			return $this->render('create',array(
					'model' => $model,
					'printers'=>$printers,
					'role'=>$role,
	                'companyId'=>  $this->companyId,
					'type'=> $type,
					'type2'=> $type2,
			));
		}else{
			$this->redirect(array('company/index','companyId'=>  $this->companyId));
		}
	}
	public function actionUpdate(){
		$role = Yii::app()->user->role;
		$dpid = Helper::getCompanyId(Yii::app()->request->getParam('dpid'));
		$type = Yii::app()->request->getParam('type');
		$type2 = 'update';
		$model = Company::model()->find('dpid=:companyId' , array(':companyId' => $dpid)) ;
		if(Yii::app()->user->role >= User::SHOPKEEPER) {
			Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
			$this->redirect(array('company/index' , 'companyId' => $this->companyId)) ;
		}
		if(Yii::app()->request->isPostRequest) {
            //Until::isUpdateValid(array(0),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
			$model->attributes = Yii::app()->request->getPost('Company');
			$province = Yii::app()->request->getParam('province1');
			$city = Yii::app()->request->getParam('city1');
			$area = Yii::app()->request->getParam('area1');
			
			$model->country = 'china';
			$model->province = $province;
			$model->city = $city;
			$model->county_area = $area;
            $model->update_at=date('Y-m-d H:i:s',time());
			
			//var_dump($model);exit;
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','修改成功'));
				$this->redirect(array('company/index','companyId'=>$this->companyId));
			} else {
				Yii::app()->user->setFlash('error',yii::t('app','修改失败'));
			}
		}
		$printers = $this->getPrinterList();
		return $this->render('update',array(
				'model'=>$model,
				'printers'=>$printers,
				'role'=>$role,
                'companyId'=>$this->companyId,
				'type'=>$type,
				'type2'=>$type2,
		));
	}
	public function actionDelete(){
		$ids = Yii::app()->request->getPost('companyIds');
        //Until::isUpdateValid(array(0),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_company set delete_flag=1,update_at="'.date('Y-m-d H:i:s',time()).'" where dpid in ('.implode(',' , $ids).')')
			->execute();
			
		}
		$this->redirect(array('company/index','companyId'=>$this->companyId));
	}
	/**
	 * 生成店铺二维码
	 */
	public function actionGenWxQrcode(){
		$dpid = Yii::app()->request->getParam('dpid',0);
		$account = WeixinServiceAccount::model()->find('dpid=:dpid',array(':dpid'=>$dpid));
		if($account&&$account['appid']&&$account['appsecret']){
			$companyDpid = $dpid;
		}else{
			$company = Company::model()->find('dpid=:dpid',array(':dpid'=>$dpid));
			$companyDpid = $company['comp_dpid'];
		}
		$model = CompanyProperty::model()->find('dpid=:dpid and delete_flag=0',array(':dpid'=>$dpid));
		$data = array('msg'=>'请求失败！','status'=>false,'qrcode'=>'');
	
		$wxQrcode = new WxQrcode($companyDpid);
		$qrcode = $wxQrcode->getQrcode(WxQrcode::COMPANY_QRCODE,$model->dpid,strtotime('2050-01-01 00:00:00'));
	
		if($qrcode){
			$model->saveAttributes(array('qr_code'=>$qrcode));
			$data['msg'] = '生成二维码成功！';
			$data['status'] = true;
			$data['qrcode'] = $qrcode;
		}
		Yii::app()->end(json_encode($data));
	}
	private function getPrinterList(){
		$printers = Printer::model()->findAll('dpid=:dpid',array(':dpid'=>$this->companyId)) ;
		return CHtml::listData($printers, 'printer_id', 'name');
	}
	private function getCompanyId($username){
		$companyId = User::model()->find('username=:username',array(':username'=>$username)) ;
		return $companyId['dpid'];
	}
	public function actionStore(){
		$dpid = Yii::app()->request->getParam('companyId');
		$appid = Yii::app()->request->getParam('appid');
		$code = Yii::app()->request->getParam('code');
		//var_dump($dpid,$appid);exit;
	
		//****查询公司的产品分类。。。****
		$db = Yii::app()->db;
		$compros = CompanyProperty::model()->find('dpid=:companyId and delete_flag=0' , array(':companyId'=>$dpid));
		if(!empty($compros)){
			$sql = 'update nb_company_property set update_at ="'.date('Y-m-d H:i:s',time()).'",appId ="'.$appid.'",code ="'.$code.'" where dpid ='.$dpid;
			$command = $db->createCommand($sql);
			$command->execute();
		}else{
			$se = new Sequence("company_property");
			$id = $se->nextval();
			$data = array(
					'lid'=>$id,
					'dpid'=>$dpid,
					'create_at'=>date('Y-m-d H:i:s',time()),
					'update_at'=>date('Y-m-d H:i:s',time()),
					'pay_type'=>'1',
					'pay_channel'=>'2',
					'appId'=>$appid,
					'code'=>$code,
					'delete_flag'=>'0',
			);
			//var_dump($dataprod);exit;
			$command = $db->createCommand()->insert('nb_company_property',$data);
		}
		Yii::app()->end(json_encode(array("status"=>"success",'msg'=>'成功')));
	}
	
}