<?php
/*
 * Created on 2014-2-11
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class WxCardQrcode {
	public $db;
	public $account;
	public $dpid;
	
	public function __construct($dpid,$cardId = null){
		$this->db = Yii::app()->db;
		$this->cardId = $cardId;
		$this->dpid = $dpid;
		$this->getWxAccount($dpid);
	}
	public function getWxAccount($brandId){
		$this->account = WeixinServiceAccount::model()->find('dpid=:brandId',array(':brandId'=>$this->dpid));
	}
	/**
	 * 生成access token
	 */
	public function genAccessToken(){
		$wxSdk = new AccessToken($this->dpid);
      	$accessToken = $wxSdk->accessToken;
      	return $accessToken;
	}
	/**
	 * token是否过期
	 */
	public function isOverdue(){
		return $this->account->expire < time();
	}
	/**
	 * 获取场景ID
	 */
	public function getSceneId($type,$id,$expireTime = null){
		$scene = Scene::model()->find('dpid=:brandId and type=:type and id=:id',array(':brandId'=>$this->dpid,':type'=>$type,':id'=>$id));
		$sceneId = $scene?$scene->scene_id:false;
		if($sceneId){
			$isSync = DataSync::getAfterSync();
			$scene->expire_time = $expireTime;
			$scene->is_sync = $isSync;
		    $scene->update();
			return $sceneId;
		}else{
				$sql ='select max(scene_id) as maxId from nb_scene where dpid = '.$this->brandId;
				$maxSceneArr = $this->db->createCommand($sql)->queryRow();
				
				$maxSceneId = $maxSceneArr['maxId'];
				$newSceneId = $maxSceneId+1;
				
				$scene = new Scene;
				$time = time();
				$isSync = DataSync::getAfterSync();
				$se=new Sequence("scene");
            	$lid = $se->nextval();
				$scene->attributes = array('lid'=>$lid,'dpid'=>$this->brandId,'create_at'=>date('Y-m-d H:i:s',$time),'update_at'=>date('Y-m-d H:i:s',$time),'scene_id'=>$newSceneId,'type'=>$type,'id'=>$id,'expire_time'=>$expireTime,'is_sync'=>$isSync);
				$scene->save();				
			}
			return $scene->scene_id;
	}
	/**
	 * 生成限制二维码
	 */
	public function getLimitQrcodeTicket($sceneId){
		$accessToken = $this->genAccessToken();
		if(!$accessToken){
			return false;
		}
		$limitTicketUrl = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$accessToken;
		$postdata = '{"action_name": "QR_CARD", "action_info": {"card": {"card_id": "'.$this->cardId.'","expire_seconds": "1800"，"is_unique_code": false ,"outer_id" : '.$sceneId.'}}}';
		$result = Curl::postHttps($limitTicketUrl,$postdata);
		$result = json_decode($result);
		if(isset($result->ticket)){
			return $result->ticket;
		}else{
			return false;
		}
	}
	public function getTmpQrcodeTicket(){
		
	}
	/**
	 * 二维码存储路径
	 */
    public function genDir(){
   		$path = Yii::app()->basePath.'/../uploads';
   		if($this->dpid){
   			$path .= '/company_'.$this->dpid;
   			if(!is_dir($path)){
   				mkdir($path, 0777,true);
   			}
			$path .= '/qrcode';
   			if(!is_dir($path)){
   				mkdir($path, 0777,true);
   			}
   		}
   		return $path;
    }
	public function getQrcode($type,$id,$expireTime = null,$limit = true){	
//		$sceneId = $this->getSceneId($type,$id,$expireTime);
		if($limit){
			$ticket = $this->getLimitQrcodeTicket(0);
		}else{
			$ticket = $this->getTmpQrcodeTicket(0);
		}
		if(!$ticket){
			return false;
		}
		$url = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.urlencode($ticket);
		$qrcodeContents = file_get_contents($url);
		$dir = $this->genDir();
		$dir = substr($this->genDir(),strpos($this->genDir(),'upload'));
		
		$fileName = $dir.'/'.Helper::genFileName().'.jpg';
		
		file_put_contents($fileName,$qrcodeContents);
		return $fileName;
	}
	
	//设置卡券白名单
	public function setOpenUser($user){
		$accessToken = $this->genAccessToken();
		if(!$accessToken){
			return false;
		}
		$url = 'https://api.weixin.qq.com/card/testwhitelist/set?access_token='.$accessToken;
		$postdata = '{"username":["'.$user.'"]}';
		$result = Curl::postHttps($url,$postdata);
		$result = json_decode($result);
		return $result;
	}
} 
?>
