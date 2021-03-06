<?php
class GoodsinvoiceController extends BackendController
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
			Yii::app()->user->setFlash('error' , yii::t('app','请选择公司'));
			$this->redirect(array('company/index'));
		}
		return true;
	}


	public function actionGoodsinvoice(){
		$content = Yii::app()->request->getParam('content',0);
		if (is_numeric($content)) {
			if ($content) {
				$str =' and t.goods_order_accountno = '.$content;
			} else {
				$str = '';
			}
		}else{
			$str = '';
		}
		$gdid = Yii::app()->request->getParam('gdid');
		// p($gdid);
		$db = Yii::app()->db;
		if($gdid){
			$sql = 'select k.* from (select c.company_name,t.* from nb_goods_invoice t left join nb_company c on(t.dpid = c.dpid) where t.dpid ='.$this->companyId.' and t.goods_delivery_id = '.$gdid.') k';
		}else{
			$sql = 'select k.* from (select c.company_name,t.* from nb_goods_invoice t left join nb_company c on(t.dpid = c.dpid) where t.dpid ='.$this->companyId.$str.') k order by status asc';
		}
		$count = $db->createCommand(str_replace('k.*','count(*)',$sql))->queryScalar();
		//var_dump($count);exit;
		$pages = new CPagination($count);
		$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
		$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
		$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
		$models = $pdata->queryAll();

		$this->render('goodsinvoice',array(
				'models'=>$models,
				'pages'=>$pages,
				'content'=>$content,
		));

	}

	public function actionDetailindex(){
		$goid = Yii::app()->request->getParam('lid');
		$name = Yii::app()->request->getParam('name');
		$papage = Yii::app()->request->getParam('papage');
		$db = Yii::app()->db;
		$sqls = 'select t.*,ga.pcc,ga.street,ga.mobile as amobile,ga.name as ganame,c.company_name,cc.company_name as dianming,cc.comp_dpid from nb_goods_invoice t left join nb_goods_address ga on(t.goods_address_id=ga.lid) left join nb_company c on(t.dpid=c.dpid) left join nb_company cc on(ga.dpid=cc.dpid) where t.lid ='.$goid;
		$model = $db->createCommand($sqls)->queryRow();
		// p($model);
		$sql = 'select k.* from (select ggm.goods_name,ggm.erp_code,ggm.goods_unit,t.*,mu.unit_name,mc.category_name from nb_goods_invoice_details t '
		.' left join (select g.*,gm.material_code,gm.unit_code from nb_goods g left join nb_goods_material gm on (g.lid=gm.goods_id )) ggm on(t.goods_id = ggm.lid)'
		.' left join nb_company c on(c.dpid = t.dpid)'
		.' left join (select m.unit_specifications,m.unit_name,m.dpid,mr.unit_code from nb_material_unit m inner join nb_material_unit_ratio mr on(m.lid=mr.stock_unit_id)) mu on(mu.dpid=c.comp_dpid and mu.unit_code=ggm.unit_code) '
		.' left join (select mc0.lid,mc1.category_name,mc1.dpid from nb_material_category mc0 left join nb_material_category mc1 on(mc0.pid=mc1.lid) ) mc on(mc.lid=ggm.category_id and mc.dpid='.$model["comp_dpid"].')'
		.' where t.goods_invoice_id = '.$goid.' order by t.lid) k';
		$materials= $db->createCommand($sql)->queryAll();

		$models =array();
		foreach ($materials as $key => $product) {
			if(!isset($models[$product['category_name']])){
				$models[$product['category_name']] = array();
			}
			array_push($models[$product['category_name']], $product);
		}
		// p($models);
		$bottoms =GoodsOrderLabel::model()->findAll('dpid=:dpid and delete_flag=0',array(':dpid'=>$this->companyId));

		$this->render('detailindex',array(
				'models'=>$models,
				'model'=>$model,
				'bottoms'=>$bottoms,
				'name'=>$name,
				'goid'=>$goid,
		));
	}

	public function actionaddbottom(){
		$formdata = Yii::app()->request->getPost('acon');
		if(Yii::app()->request->isPostRequest) {
			$data = explode(",",$formdata);
			// p($data);
			$num = 0;
			foreach ($data as $key => $value) {
				$info = explode("_",$value);
				if(empty($info[2])) {
					$bottoms = new GoodsOrderLabel();
					$se=new Sequence("goods_order_label");
		            $lid = $se->nextval();
		            $bottoms->lid = $lid;
		            $bottoms->dpid = $this->companyId;
		            $bottoms->create_at = date('Y-m-d H:i:s');
		            $bottoms->update_at = date('Y-m-d H:i:s');
		            $bottoms->content = $info[1];
		            $bottoms->sort_no = $info[0];
					// p($bottoms);
		        	if ($bottoms->insert()) {
		        		$num+=1;
					}
				}else{
					$bottoms =GoodsOrderLabel::model()->find('lid=:lid and dpid=:dpid and delete_flag=0',array(':lid'=>$info[2],':dpid'=>$this->companyId));
					// p($bottoms);
					$bottoms->update_at = date('Y-m-d H:i:s');
		            $bottoms->content = $info[1];
		            $bottoms->sort_no = $info[0];
					// p($bottoms);
		        	if ($bottoms->update()) {
		        		$num+=1;
					}
				}
			}
			if ($num==count($data)) {
				echo json_encode(1);exit;
			}else{
				echo json_encode(0);exit;
			}

		}
	}


	public function actiondelete(){
		$lid = Yii::app()->request->getPost('lid');
		if(Yii::app()->request->isPostRequest) {
			$bottoms =GoodsOrderLabel::model()->find('lid=:lid and dpid=:dpid and delete_flag=0',array(':lid'=>$lid,':dpid'=>$this->companyId));
			if ($bottoms) {
				$bottoms->update_at = date('Y-m-d H:i:s');
				$bottoms->delete_flag=1;
				// p($bottoms);
				if($bottoms->update()) {
					echo json_encode(1);exit;
				}else{
					echo json_encode(0);exit;
				}
			}else{
				echo json_encode(2);exit;
			}
			
		}
	}


	public function actionDetailindexExport(){
		$objPHPExcel = new PHPExcel();
		$goid = Yii::app()->request->getParam('goid');
		// p($goid);
		$db = Yii::app()->db;

		$sqls = 'select t.*,ga.*,ga.mobile as phone,t.mobile as mobile from nb_goods_invoice t left join nb_goods_address ga on(t.goods_address_id=ga.lid) where t.lid ='.$goid;
		$model = $db->createCommand($sqls)->queryRow();

		$sqlstock = 'select t.* from nb_company t where t.type = 2 and t.comp_dpid ='.$this->companyId;
		$stocks = $db->createCommand($sqlstock)->queryAll();

		$sql = 'select k.* from (select c.goods_name,co.company_name as stock_name,t.* from nb_goods_invoice_details t left join nb_goods c on(t.goods_id = c.lid) left join nb_company co on(co.dpid = t.dpid ) where t.goods_invoice_id = '.$goid.' order by t.lid) k';

		$models = $db->createCommand($sql)->queryAll();
		// var_dump($models);exit;
		// p($model);

        //设置第1行的行高
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
        //设置第2行的行高
        $objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(30);
        $objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(20);
        //设置字体
        $objPHPExcel->getDefaultStyle()->getFont()->setName('宋体');
        $objPHPExcel->getDefaultStyle()->getFont()->setSize(16);
        $styleArray1 = array(
                        'font' => array(
                                        'bold' => true,
                                        'color'=>array(
                                                        'rgb' => '000000',
                                        ),
                                        'size' => '20',
                        ),
                        'alignment' => array(
                                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                        ),
        );
        $styleArray2 = array(
                        'font' => array(
                                        'color'=>array(
                                                        'rgb' => 'ff0000',
                                        ),
                                        'size' => '16',
                        ),
                        'alignment' => array(
                                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                        ),
        );
        //大边框样式 边框加粗
        $lineBORDER = array(
                        'borders' => array(
                                        'outline' => array(
                                                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                                                        'color' => array('argb' => '000000'),
                                        ),
                        ),
        );
        //$objPHPExcel->getActiveSheet()->getStyle('A1:E'.$j)->applyFromArray($lineBORDER);
        //细边框样式
        $linestyle = array(
                        'borders' => array(
                                        'outline' => array(
                                                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                                                        'color' => array('argb' => 'FF000000'),
                                        ),
                        ),
        );

        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1',yii::t('app','壹点吃餐饮管理系统仓库发货单'))
        ->setCellValue('A2',yii::t('app','发货单号:'.$model['invoice_accountno'].'--订单号:'.$model['goods_order_accountno']))
        ->setCellValue('A3',yii::t('app','总金额:'.$model['invoice_amount'].'--状态:'.($model['pay_status']?'已支付':'未支付')))
        ->setCellValue('A4',yii::t('app','收货地址:'.$model['pcc'].' '.$model['street']))
        ->setCellValue('A5',yii::t('app','收货人:'.$model['name'].'--联系电话:'.$model['phone']))
        ->setCellValue('A6',yii::t('app','货品名称'))
        ->setCellValue('B6',yii::t('app','价格'))
        ->setCellValue('C6',yii::t('app','数量'))
        ->setCellValue('D6',yii::t('app','发货仓库'));
        $j=7;
        if($models){
            foreach ($models as $key => $v) {
                $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$j,$v['goods_name'])
                ->setCellValue('B'.$j,$v['price'])
                ->setCellValue('C'.$j,$v['num'])
                ->setCellValue('D'.$j,$v['stock_name']);

                //细边框引用
                $objPHPExcel->getActiveSheet()->getStyle('A'.$j.':D'.$j)->applyFromArray($linestyle);
                //设置字体靠左
                $objPHPExcel->getActiveSheet()->getStyle('A'.$j.':D'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $j++;
            }
        }
        //冻结窗格
        $objPHPExcel->getActiveSheet()->freezePane('A7');
        //合并单元格
        $objPHPExcel->getActiveSheet()->mergeCells('A1:D1');
        $objPHPExcel->getActiveSheet()->mergeCells('A2:D2');
        $objPHPExcel->getActiveSheet()->mergeCells('A3:D3');
        $objPHPExcel->getActiveSheet()->mergeCells('A4:D4');
        $objPHPExcel->getActiveSheet()->mergeCells('A5:D5');
        //单元格加粗，居中：
        // $objPHPExcel->getActiveSheet()->getStyle('A1:J'.$jj)->applyFromArray($lineBORDER);//大边框格式引用
        // 将A1单元格设置为加粗，居中
        $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray1);
        $objPHPExcel->getActiveSheet()->getStyle('A2:D2')->applyFromArray($linestyle);
        $objPHPExcel->getActiveSheet()->getStyle('A3:D3')->applyFromArray($linestyle);
        $objPHPExcel->getActiveSheet()->getStyle('A4:D4')->applyFromArray($linestyle);
        $objPHPExcel->getActiveSheet()->getStyle('A5:D5')->applyFromArray($linestyle);
        //加粗字体
        $objPHPExcel->getActiveSheet()->getStyle('A2:D2')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A4:D4')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A5:D5')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A6:D6')->getFont()->setBold(true);
        //设置字体垂直居中
        $objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        //设置字体水平居中
        $objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        //设置每列宽度
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        //输出
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $filename="壹点吃餐饮管理系统仓库发货单---（".date('m-d',time())."）.xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        $objWriter->save('php://output');
	}

	public function actionStore(){
		$pid = Yii::app()->request->getParam('pid');
		//var_dump($pid);//exit;
		$dpid = $this->companyId;
		$db = Yii::app()->db;
		$transaction = $db->beginTransaction();
		try
		{
			$is_sync = DataSync::getInitSync();
			$db->createCommand('update nb_goods_invoice set status =1,update_at ="'.date('Y-m-d H:i:s',time()).'" where lid ='.$pid)
			->execute();

			$transaction->commit();
			Yii::app()->end(json_encode(array("status"=>"success",'msg'=>'成功')));
			//return true;
		}catch (Exception $e) {
			$transaction->rollback(); //如果操作失败, 数据回滚
			Yii::app()->end(json_encode(array("status"=>"fail")));
			//return false;
		}
	}

	public function actionStorestock(){
		$name = Yii::app()->request->getParam('name');
		$nums = Yii::app()->request->getParam('nums');
		$sender = Yii::app()->request->getParam('sender');
		$phone = Yii::app()->request->getParam('phone');
		$gid = Yii::app()->request->getParam('gid');
		$type = Yii::app()->request->getParam('type');
		//var_dump($name);
		//var_dump($nums);
		//exit;
		$dpid = $this->companyId;
		$db = Yii::app()->db;
		$transaction = $db->beginTransaction();
		try
		{
			$is_sync = DataSync::getInitSync();
			if ($type == 1) {
				$db->createCommand('update nb_goods_invoice set sent_type ='.$type.',sent_personnel="'.$name.'",mobile="'.$nums.'",update_at ="'.date('Y-m-d H:i:s',time()).'" where lid ='.$gid)->execute();
			} else {
				$db->createCommand('update nb_goods_invoice set sent_type ='.$type.',sent_personnel="'.$name.'",mobile="'.$nums.'",sent_personnel_2="'.$sender.'",mobile_2="'.$phone.'",update_at ="'.date('Y-m-d H:i:s',time()).'" where lid ='.$gid)->execute();
			}
			
			$transaction->commit();
			Yii::app()->end(json_encode(array("status"=>"success",'msg'=>'成功')));
			//return true;
		}catch (Exception $e) {
			$transaction->rollback(); //如果操作失败, 数据回滚
			Yii::app()->end(json_encode(array("status"=>"fail")));
			//return false;
		}
	}

	public function actionAddp(){
		$this->layout = '/layouts/main_picture';
		$gid = Yii::app()->request->getParam('gid',0);

		$db = Yii::app()->db;
		$sql ='select t.* from nb_goods_invoice t where t.lid ='.$gid.' and t.delete_flag =0 ';
		$models = $db->createCommand($sql)->queryRow();
// p($models);
		$sql2 = 'select t.* from nb_takeaway_member t where t.delete_flag =0 and t.dpid ='.$this->companyId.' or t.dpid in(select c.dpid from nb_company c where c.delete_flag =0 and c.comp_dpid ='.$this->companyId.')';
		$pers = $db->createCommand($sql2)->queryAll();

		$this->render('addp' , array(
				'models' => $models,
				'pers' => $pers,
				'gid'=>$gid,
				'action' => $this->createUrl('goodsinvoice/addp' , array('companyId'=>$this->companyId))
		));
	}

}