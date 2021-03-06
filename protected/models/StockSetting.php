<?php

/**
 * This is the model class for table "nb_stock_setting".
 *
 * The followings are the available columns in table 'nb_stock_setting':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property integer $dsales_day
 * @property integer $dsafe_min_day
 * @property integer $dsafe_max_day
 * @property integer $csales_day
 * @property integer $csafe_min_day
 * @property integer $csafe_max_day
 * @property integer $delete_flag
 * @property string $is_sync
 */
class StockSetting extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_stock_setting';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('update_at', 'required'),
			array('dsales_day, dsafe_min_day, dsafe_max_day, csales_day, csafe_min_day, csafe_max_day, delete_flag', 'numerical', 'integerOnly'=>true),
			array('lid, dpid', 'length', 'max'=>10),
			array('is_sync', 'length', 'max'=>50),
			array('create_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, dsales_day, dsafe_min_day, dsafe_max_day, csales_day, csafe_min_day, csafe_max_day, delete_flag, is_sync', 'safe', 'on'=>'search'),
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
			'dsales_day' => '店铺日均销量计算系数',
			'dsafe_min_day' => '店铺小安全天数',
			'dsafe_max_day' => '店铺大安全天数',
			'csales_day' => '仓库日均销量计算系数',
			'csafe_min_day' => '仓库小安全天数',
			'csafe_max_day' => '仓库大安全天数',
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
		$criteria->compare('dsales_day',$this->dsales_day);
		$criteria->compare('dsafe_min_day',$this->dsafe_min_day);
		$criteria->compare('dsafe_max_day',$this->dsafe_max_day);
		$criteria->compare('csales_day',$this->csales_day);
		$criteria->compare('csafe_min_day',$this->csafe_min_day);
		$criteria->compare('csafe_max_day',$this->csafe_max_day);
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
	 * @return StockSetting the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
