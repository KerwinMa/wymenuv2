<?php
/**
 * NewBrandUser.php
 * 生成新用户类
 * 
 * @property String $openId 微信会员对微信服务号唯一识别标识openId
 * @property Integer $brandId 品牌主键
 * @property Boolean $success 是否创建成功
 * @property Mixed $errorMessage 如果创建失败，存入错误信息
 */

class NewBrandUser {
	public $openId;
	public $brandId;
	public $success = false;
	public $errorMessage;
	
	/**
	 * @param String $openId 微信会员对微信服务号唯一识别标识openId
	 * @param Mixed $brandId 品牌身份唯一标识，此处可以用品牌的主键或者微信服务号唯一标识appId
	 */
	public function __construct($opendId, $brandId) {
		$transaction = Yii::app()->db->beginTransaction();
		try {
			$this->openId = $opendId;
			$this->brandId = $brandId;
			$this->newBrandUser();
			$this->pullUserInfo();
			$this->success = true;
			$transaction->commit();
		} catch(Exception $e) {
			$this->errorMessage = $e->getMessage();
			$transaction->rollBack();
		}
		
	}
	
	/**
     * 添加新用户记录
     * 要获得当前品牌最低会员等级，把该等级的主键ID，插入会员记录的brand_user_rank_id字段
     * 同时也要向yk_brand_user_information中插入一条该用户的信息记录
     * 此处memcache()方法是设置用于登录的$this->login
     */
    public function newBrandUser() {
  		$time = time();
        $insertBrandUserArr = array(
        	'dpid'=>$this->brandId,
        	'openid'=>$this->openId,
        	'card_id'=>$this->newBrandUserCardId(),
        	'user_rank'=>1,
        	'create_time'=>$time,
        	'update_time'=>$time, 	
        );
        
        Yii::app()->db->createCommand()->insert('nb_brand_user', $insertBrandUserArr);
        $this->userId = $lastInsertId = Yii::app()->db->getLastInsertID();
        
         $this->brandUser = BrandUser::get($lastInsertId);
    }
	
	/**
     * 计算会员卡号，如果该品牌之前已经有会员卡号，则查询出最大的会员卡号，再加一，为下一个会员卡号
     * 如果该品牌之前没有会员卡号（没有会员），则用会员卡号的规则，写入第一个会员卡号。规则：(10000 + $brandId)*1000000000 + 801;
     */
    public function newBrandUserCardId() {
        $sql = 'SELECT max(card_id) as maxCardId FROM nb_brand_user WHERE dpid = ' . $this->brandId;
        $data = Yii::app()->db->createCommand($sql)->queryRow(); 
        if($data['maxCardId'] >= 10000000000000) 
			return $data['maxCardId'] + 1;
		else 
			return (10000 + $this->brandId)*1000000000 + 801;
        
    }
	
	/**
	 * 拉取微信用户的信息
	 */
	public function pullUserInfo() {
		new PullUserInfo($this->brandId, $this->userId);
	}
	
}
 
 
?>