<?php

class GoodsinputfullController extends Controller {

    public function init() {
        parent::init();
    }

    public function actionMore() {
        $params = array();
        /**
         * 
         */
        if($this->all_time_common=='1'){
            $where = "1=1";         
        }
        else{
            $where = "(date(created_at) >= '" . FunctionCommon::convertDateForDB($this->start_date_common) . "' OR created_at is null)";
            $where.=" and (date(created_at) <= '" . FunctionCommon::convertDateForDB($this->end_date_common) . "' OR created_at is null)";        
        }        
        //
        if ($this->customer_id_common != "") {
            $customer_id_common = $this->customer_id_common;
            $where.=" and branch_id=$customer_id_common";
        }
        if ($this->goods_id_common != "") {
            $goods_id_common = $this->goods_id_common;
            $where.=" and goodsunit_id=$goods_id_common";
        }
        /**
         * 
         */
        $argPage = (int) Yii::app()->request->getQuery('page', 0);
        $dbCriteria = new CDbCriteria;
        $dbCriteria->condition = $where;
        $dbCriteria->limit = Yii::app()->params['number_of_items_per_page'];
        $dbCriteria->offset = $argPage * $dbCriteria->limit;
        /**
         * 
         */
        $items = GoodsInputFull::model()->findAll($dbCriteria);
        if (!is_array($items) || count($items) == 0) {
            echo '';
            Yii::app()->end();
        }
        $arr=array();
        for($i=0;$i<count($items);$i++){
            $arr[]=$items[$i]->goodsunit_id;
        }
        if(count($arr)>0){
            $str=  implode(",", $arr);
            $row=Yii::app()->db->createCommand()
                    ->select("goodsunit_id,sum(so_luong_da_ban_number) as sum_so_luong_da_ban,sum(tong_tien_number) as sum_tong_tien,sum(so_hoa_don) as sum_so_hoa_don,sum(so_to_khai) as sum_so_to_khai")
                    ->from('goods_input_full_view')
                    ->where("goodsunit_id IN ($str)")
                    ->andWhere($where)
                    ->group("goodsunit_id")
                    ->queryAll();
            for($i=0;$i<count($items);$i++){
                for($j=0;$j<count($row);$j++){
                    if($row[$j]['goodsunit_id']==$items[$i]->goodsunit_id){
                        $items[$i]->so_hoa_don=$row[$j]['sum_so_hoa_don'];
                        $items[$i]->so_to_khai=$row[$j]['sum_so_to_khai'];
                        $items[$i]->tong_tien=number_format($row[$j]['sum_tong_tien'], 0, ",", ".");
                        $items[$i]->so_luong_da_ban=number_format($row[$j]['sum_so_luong_da_ban'], 0, ",", ".");
                        break;
                    }
                }
            }
            //
            $row=Yii::app()->db->createCommand()
                    ->select("goodsunit_id,count(distinct branch_id) as sum_so_khach_hang")
                    ->from('goods_input_full_view')
                    ->where("goodsunit_id IN ($str)")
                    ->andWhere($where)
                    ->andWhere("so_khach_hang > 0")
                    ->group("goodsunit_id")
                    ->queryAll();
            for($i=0;$i<count($items);$i++){
                for($j=0;$j<count($row);$j++){
                    if($row[$j]['goodsunit_id']==$items[$i]->goodsunit_id){
                        $items[$i]->so_khach_hang=$row[$j]['sum_so_khach_hang'];
                        break;
                    }
                }
            }
            //
            $row=Yii::app()->db->createCommand()
                    ->select("goodsunit_id,count(distinct branch_id) as sum_so_khach_hang")
                    ->from('goods_input_full_view')
                    ->where("goodsunit_id IN ($str)")
                    ->andWhere($where)
                    ->andWhere("so_nguoi_nuoc_ngoai > 0")
                    ->group("goodsunit_id")
                    ->queryAll();
            for($i=0;$i<count($items);$i++){
                for($j=0;$j<count($row);$j++){
                    if($row[$j]['goodsunit_id']==$items[$i]->goodsunit_id){
                        $items[$i]->so_nguoi_nuoc_ngoai=$row[$j]['sum_so_khach_hang'];
                        break;
                    }
                }
            }
            
        }
        $params['items'] = $items;
        $this->renderPartial('//render_partial/common/more', $params);
    }

    public function actionIndex() {        
        $params = array();
        /**
         * 
         */
        if($this->all_time_common=='1'){
            $where = "1=1";              
        }
        else{
            $where = "(date(created_at) >= '" . FunctionCommon::convertDateForDB($this->start_date_common) . "' OR created_at is null)";
            $where.=" and (date(created_at) <= '" . FunctionCommon::convertDateForDB($this->end_date_common) . "' OR created_at is null)";        
        }    
        $where1 = $where2 = $where;
        $where41 = " and $where";
        $where42 = "1=1 ";
        if ($this->customer_id_common != "") {
            $customer_id_common = $this->customer_id_common;
            $temp=" and branch_id=$customer_id_common";
            $where1.=$temp;
            $where2.=$temp;
            $where41.=$temp;
        }
        if ($this->goods_id_common != "") {
            $goods_id_common = $this->goods_id_common;
            $temp=" and goodsunit_id=$goods_id_common";                                    
            $where1.=$temp;
            $where42.=$temp;
        }
        /**
         * 
         */
        $items = GoodsInputFull::model()->findAll($where1);
        $arr=array();
        for($i=0;$i<count($items);$i++){
            $arr[]=$items[$i]->goodsunit_id;
        }
        if(count($arr)>0){
            $str=  implode(",", $arr);
            $row=Yii::app()->db->createCommand()
                    ->select("goodsunit_id,sum(so_luong_da_ban_number) as sum_so_luong_da_ban,sum(tong_tien_number) as sum_tong_tien,sum(so_hoa_don) as sum_so_hoa_don,sum(so_to_khai) as sum_so_to_khai")
                    ->from('goods_input_full_view')
                    ->where("goodsunit_id IN ($str)")
                    ->andWhere($where1)
                    ->group("goodsunit_id")
                    ->queryAll();
            for($i=0;$i<count($items);$i++){
                for($j=0;$j<count($row);$j++){
                    if($row[$j]['goodsunit_id']==$items[$i]->goodsunit_id){
                        $items[$i]->so_hoa_don=$row[$j]['sum_so_hoa_don'];
                        $items[$i]->so_to_khai=$row[$j]['sum_so_to_khai'];
                        $items[$i]->tong_tien=number_format($row[$j]['sum_tong_tien'], 0, ",", ".");
                        $items[$i]->so_luong_da_ban=number_format($row[$j]['sum_so_luong_da_ban'], 0, ",", ".");
                        break;
                    }
                }
            }
            //
            $row=Yii::app()->db->createCommand()
                    ->select("goodsunit_id,count(distinct branch_id) as sum_so_khach_hang")
                    ->from('goods_input_full_view')
                    ->where("goodsunit_id IN ($str)")
                    ->andWhere($where1)
                    ->andWhere("so_khach_hang > 0")
                    ->group("goodsunit_id")
                    ->queryAll();
            for($i=0;$i<count($items);$i++){
                for($j=0;$j<count($row);$j++){
                    if($row[$j]['goodsunit_id']==$items[$i]->goodsunit_id){
                        $items[$i]->so_khach_hang=$row[$j]['sum_so_khach_hang'];
                        break;
                    }
                }
            }
            //
            $row=Yii::app()->db->createCommand()
                    ->select("goodsunit_id,count(distinct branch_id) as sum_so_khach_hang")
                    ->from('goods_input_full_view')
                    ->where("goodsunit_id IN ($str)")
                    ->andWhere($where1)
                    ->andWhere("so_nguoi_nuoc_ngoai > 0")
                    ->group("goodsunit_id")
                    ->queryAll();
            for($i=0;$i<count($items);$i++){
                for($j=0;$j<count($row);$j++){
                    if($row[$j]['goodsunit_id']==$items[$i]->goodsunit_id){
                        $items[$i]->so_nguoi_nuoc_ngoai=$row[$j]['sum_so_khach_hang'];
                        break;
                    }
                }
            }
            
        }
        $params['items'] = $items;
        
        /**
         * lấy tổng tiền, tổng số lượng hàng hóa, tổng số hóa đơn
         */
        $row = Yii::app()->db->createCommand()
                ->select("sum(tong_tien_number) as sum,count(distinct goodsunit_id) as count,sum(so_luong_da_ban_number) as sum_quantity")
                ->from("goods_input_full_view")
                ->where($where1)
                ->queryRow();
        $params['sum'] = number_format($row['sum'], 0, ",", ".");
        $params['count'] = number_format($row['count'], 0, ",", ".");
        $params['sum_quantity'] = number_format($row['sum_quantity'], 0, ",", ".");

        $params['customer_count'] =Yii::app()->db->createCommand("select count(distinct branch_id) as sum_so_khach_hang from goods_input_full_view where $where1 AND so_khach_hang > 0")->queryScalar();
        $params['customer_count_is_international'] =Yii::app()->db->createCommand("select count(distinct branch_id) as sum_so_khach_hang from goods_input_full_view where $where1 AND so_nguoi_nuoc_ngoai > 0")->queryScalar();
        
        $params['bill_count'] =Yii::app()->db->createCommand("select count(distinct created_at) as sum_so_khach_hang from goods_input_full_view where $where1 AND so_hoa_don > 0")->queryScalar();
        $params['bill_count_is_international'] =Yii::app()->db->createCommand("select count(distinct created_at) as sum_so_khach_hang from goods_input_full_view where $where1 AND so_to_khai > 0")->queryScalar();
        /**
         * 
         */
        $params['start_date_common'] = Yii::app()->session['start_date_common'];
        $params['end_date_common'] = Yii::app()->session['end_date_common'];
        $params['customer_id_common'] = Yii::app()->session['customer_id_common'];
        $params['goods_id_common'] = Yii::app()->session['goods_id_common'];
        $params['all_time_common'] = Yii::app()->session['all_time_common'];
        $this->render('index', $params);
    }

    public function actionSaveallgoods() {


        $full_name_goods_unit = Yii::app()->request->getParam("full_name_goods_unit", "");
        $price = Yii::app()->request->getParam("price", "");
        $goods_group_id=0;

        if (is_array($full_name_goods_unit) && count($full_name_goods_unit) > 0) {
            for ($i = 0; $i < count($full_name_goods_unit); $i++) {
                $model_goods_unit = new Goods();
                $model_goods_unit->unit_full_name = $full_name_goods_unit[$i];
                $model_goods_unit->goods_full_name = Yii::app()->request->getParam("full_name_goods", "");
                $model_goods_unit->goods_short_hand_name = Yii::app()->request->getParam("short_hand_name", "");
                $model_goods_unit->quantity = 0;
                $model_goods_unit->price = $price[$i];
                $model_goods_unit->price = str_replace(".", "", $model_goods_unit->price);
                $model_goods_unit->tax = Yii::app()->request->getParam("tax", 0);
                $model_goods_unit->goods_full_name_for_unique_validate=  FunctionCommon::getStringForValidate($model_goods_unit->goods_full_name);
                $error = CActiveForm::validate($model_goods_unit);                
            }
            if ($error != '[]') {
                echo $error;
                Yii::app()->end();
            }
            $is_submit=Yii::app()->request->getParam("is_submit","0");
            if($is_submit=='1'){
                for ($i = 0; $i < count($full_name_goods_unit); $i++) {
                    $model_goods_unit = new Goods();
                    $model_goods_unit->unit_full_name = $full_name_goods_unit[$i];
                    $model_goods_unit->goods_full_name = Yii::app()->request->getParam("full_name_goods", "");
                    $model_goods_unit->goods_full_name_for_unique_validate=  FunctionCommon::getStringForValidate($model_goods_unit->goods_full_name);
                    $model_goods_unit->goods_short_hand_name = Yii::app()->request->getParam("short_hand_name", "");
                    $model_goods_unit->quantity = 0;
                    $model_goods_unit->price = $price[$i];
                    $model_goods_unit->price = str_replace(".", "", $model_goods_unit->price);
                    $model_goods_unit->tax = Yii::app()->request->getParam("tax", 0);
                    $model_goods_unit->setIsNewRecord(true);
                    $model_goods_unit->group_id=$goods_group_id;
                    $model_goods_unit->save(FALSE);
                    if($goods_group_id==0){
                        $goods_group_id=Yii::app()->db->createCommand("select group_id from goods where id=".$model_goods_unit->id)->queryScalar();
                    }                
                }
            }
            
        }
        echo $goods_group_id;
        Yii::app()->end();
    }

    

    public function actionSavegoods() {

        $model =  Goods::model()->findByPk(Yii::app()->request->getParam("id"));        
        $model->unit_full_name = Yii::app()->request->getParam("unit_full_name", "");
        $model->price = Yii::app()->request->getParam("price", "");
        $model->price=  str_replace(".", "", $model->price);
        $model->goods_full_name = Yii::app()->request->getParam("goods_full_name", "");
        $model->goods_short_hand_name = Yii::app()->request->getParam("goods_short_hand_name", "");
        $model->tax = Yii::app()->request->getParam("tax", "");
        $model->goods_full_name_for_unique_validate=  FunctionCommon::getStringForValidate($model->goods_full_name);
        $error = CActiveForm::validate($model);
        if ($error != '[]') {
            echo $error;
            Yii::app()->end();
        }


        if ($model->id != "") {
            $model->setIsNewRecord(FALSE);
        } else {
            $model->setIsNewRecord(true);
        }
        $is_submit=Yii::app()->request->getParam("is_submit","0");
        if($is_submit=='1'){
            $model->save(FALSE);
        }
        

        echo '';
        Yii::app()->end();
    }

    public function actionDelete() {
        $id=Yii::app()->request->getParam("id",0);
        $model=Goods::model()->findByPk($id);
        $model->delete();        
    }

}
