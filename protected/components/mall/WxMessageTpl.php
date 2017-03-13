<?php 
/**
 * 
 * 
 * 消息模板类
 * 
 * $type 0 支付成功通知
 * 
 */
class WxMessageTpl
{
	
	public function __construct($dpid,$userId,$type,$data){
		$this->dpid = $dpid;
		$this->userId = $userId;
		$this->type = $type;
		$this->data = $data;
		$accessToken = new AccessToken($dpid);
        $this->access_token = $accessToken->accessToken;
		$this->getMsgTpl();
		$this->getData();
		$this->sent();
	}
	public function getMsgTpl(){
		$sql = 'select * from nb_weixin_messagetpl where dpid=:dpid and message_type=:type and delete_flag=0';
		$this->msgTpl = Yii::app()->db->createCommand($sql)
				  ->bindValue(':dpid',$this->dpid)
				  ->bindValue(':type',$this->type)
				  ->queryRow();
	}
	public function getData(){
		if(!$this->msgTpl){
			return ;
		}
		$msgTplId = $this->msgTpl['message_tpl_id'];
		
		$this->megTplData = array(
			array(
				'touser'=>$this->data['touser'],
	            'template_id'=>$msgTplId,
	            'url'=>$this->data['url'],
	            'data' => array(
	                'first'=>array(
	                    'value'=>$this->data['first'],
	                    'color'=>'#0A0A0A',
	                ),
	                'keyword1'=>array(
	                    'value'=>$this->data['keyword1'],
	                    'color'=>'#0A0A0A',
	                ),
	                'keyword2'=>array(
	                    'value'=>$this->data['keyword2'],
	                    'color'=>'#FF0000',
	                ),
	                'keyword3'=>array(
	                    'value'=>$this->data['keyword3'],
	                    'color'=>'#0A0A0A',
	                ),
	                'keyword4'=>array(
	                    'value'=>$this->data['keyword4'],
	                    'color'=>'#0A0A0A',
	                ),
	                'remark'=>array(
	                    'value'=>$this->data['remark'],
	                    'color'=>'#173177',
	                )
	            )
			),
		);
		if($this->data['keyword1']){
			$this->megTplData['data']['keyword1']['value'] = $this->data['keyword1'];
		}else{
			unset($this->megTplData['data']['keyword1']);
		}
		
		if($this->data['keyword2']){
			$this->megTplData['data']['keyword2']['value'] = $this->data['keyword2'];
		}else{
			unset($this->megTplData['data']['keyword2']);
		}
		
		if($this->data['keyword3']){
			$this->megTplData['data']['keyword3']['value'] = $this->data['keyword3'];
		}else{
			unset($this->megTplData['data']['keyword3']);
		}
		
		if($this->data['keyword4']){
			$this->megTplData['data']['keyword4']['value'] = $this->data['keyword4'];
		}else{
			unset($this->megTplData['data']['keyword4']);
		}
	}
	public function sent(){
		$tplUrl = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$this->access_token;
		Curl::httpsRequest($tplUrl, json_encode($this->megTplData[$this->type]));
	}
	
	
}