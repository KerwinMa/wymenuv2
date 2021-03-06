<?php

/**
 * This is the model class for table "nb_product_material_stock".
 *
 * The followings are the available columns in table 'nb_product_material_stock':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property integer $material_id
 * @property string $stock
 * @property string $stock_cost
 * @property integer $delete_flag
 * @property string $is_sync
 */
class ProductMaterialStock extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_product_material_stock';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('update_at, material_id', 'required'),
			array('material_id, delete_flag', 'numerical', 'integerOnly'=>true),
			array('lid, dpid, stock, stock_cost', 'length', 'max'=>10),
			array('is_sync', 'length', 'max'=>50),
			array('create_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, material_id, stock, stock_cost, delete_flag, is_sync', 'safe', 'on'=>'search'),
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
			'material_id' => '品项的lid',
			'stock' => '库存',
			'stock_cost' => '库存成本',
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
		$criteria->compare('material_id',$this->material_id);
		$criteria->compare('stock',$this->stock,true);
		$criteria->compare('stock_cost',$this->stock_cost,true);
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
	 * @return ProductMaterialStock the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	/**
	 * 
	 * 入库存
	 * 
	 */
	public static function updateStock($dpid,$materialId,$stock,$stockCost)
	{
		$sql = 'update nb_product_material_stock set stock = stock+'.$stock.',stock_cost =+'.$stockCost.' where dpid='.$dpid.' and 	material_id='.$materialId.' and delete_flag=0';
		Yii::app()->db->createCommand($sql)->execute();
	}
	public static function updateStock2($dpid,$materialId,$stock)
	{
		$sql = 'update nb_product_material_stock set stock = '.$stock.' where dpid='.$dpid.' and 	material_id='.$materialId.' and delete_flag=0';
		Yii::app()->db->createCommand($sql)->execute();
	}
}
