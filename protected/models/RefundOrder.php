<?php

/**
 * This is the model class for table "nb_refund_order".
 *
 * The followings are the available columns in table 'nb_refund_order':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $manufacturer_id
 * @property string $organization_id
 * @property string $refund_account_no
 * @property string $admin_id
 * @property string $storage_account_no
 * @property string $refund_date
 * @property string $remark
 * @property integer $status
 * @property integer $delete_flag
 * @property string $is_sync
 */
class RefundOrder extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_refund_order';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('update_at,', 'required'),
			array('status, delete_flag', 'numerical', 'integerOnly'=>true),
			array('lid, dpid, manufacturer_id, organization_id, admin_id', 'length', 'max'=>10),
			array('refund_account_no, storage_account_no', 'length', 'max'=>32),
			array('remark', 'length', 'max'=>255),
			array('is_sync', 'length', 'max'=>50),
			array('create_at, refund_date', 'safe'),
			array('manufacturer_id','compare','compareValue'=>'0','operator'=>'>','message'=>yii::t('app','请选择厂商名称')),
			array('organization_id','compare','compareValue'=>'0','operator'=>'>','message'=>yii::t('app','请选择退货商名称')),
			//array('admin_id','compare','compareValue'=>'0','operator'=>'>','message'=>yii::t('app','请选择管理员')),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, manufacturer_id, organization_id, refund_account_no, admin_id, storage_account_no, refund_date, remark, status, delete_flag, is_sync', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		'company' => array(self::BELONGS_TO , 'Company' , 'dpid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'lid' => '自身id，统一dpid下递增',
			'dpid' => '店铺id',
			'create_at' => 'Create At',
			'update_at' => '更新时间',
			'manufacturer_id' => '供应厂商',
			'organization_id' => '退货组织',
			'refund_account_no' => '退货单号',
			'admin_id' => '经办人员',
			'storage_account_no' => '原始单号',
			'refund_date' => '退货日期',
			'remark' => '备注',
			'status' => '审核状态',
			'delete_flag' => '删除 0未删除 1删除',
			'is_sync' => 'Is Sync',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('lid',$this->lid,true);
		$criteria->compare('dpid',$this->dpid,true);
		$criteria->compare('create_at',$this->create_at,true);
		$criteria->compare('update_at',$this->update_at,true);
		$criteria->compare('manufacturer_id',$this->manufacturer_id,true);
		$criteria->compare('organization_id',$this->organization_id,true);
		$criteria->compare('refund_account_no',$this->refund_account_no,true);
		$criteria->compare('admin_id',$this->admin_id,true);
		$criteria->compare('storage_account_no',$this->storage_account_no,true);
		$criteria->compare('refund_date',$this->refund_date,true);
		$criteria->compare('remark',$this->remark,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('delete_flag',$this->delete_flag);
		$criteria->compare('is_sync',$this->is_sync,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RefundOrder the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
