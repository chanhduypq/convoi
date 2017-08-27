<?php

class GoodsfullController extends Controller {

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
        $items = GoodsFull::model()->findAll($dbCriteria);
        if (!is_array($items) || count($items) == 0) {
            echo '';
            Yii::app()->end();
        }
        $arr=array();
        for($i=0;$i<count($items);$i++){
            if($items[$i]->so_hoa_don!='0'){
                $arr[]=$items[$i]->goodsunit_id;
            }            
        }
        if(count($arr)>0){
            $str=  implode(",", $arr);
            $row=Yii::app()->db->createCommand()
                    ->select("goodsunit_id,sum(tong_tien_number) as sum_tong_tien,sum(so_hoa_don) as sum_so_hoa_don,sum(so_luong_da_ban_number) as sum_so_luong_da_ban,count(distinct branch_id) as sum_so_khach_hang")
                    ->from('goods_full_view')
                    ->where("goodsunit_id IN ($str)")
                    ->andWhere($where)
                    ->group("goodsunit_id")
                    ->queryAll();
            for($i=0;$i<count($items);$i++){
                if($items[$i]->so_hoa_don!='0'){
                    for($j=0;$j<count($row);$j++){
                        if($row[$j]['goodsunit_id']==$items[$i]->goodsunit_id){
                            $items[$i]->so_hoa_don=$row[$j]['sum_so_hoa_don'];
                            $items[$i]->so_khach_hang=$row[$j]['sum_so_khach_hang'];
                            $items[$i]->so_luong_da_ban=number_format($row[$j]['sum_so_luong_da_ban'], 0, ",", ".");
                            $items[$i]->tong_tien=number_format($row[$j]['sum_tong_tien'], 0, ",", ".");
                            break;
                        }
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
        $items = GoodsFull::model()->findAll($where1);
        $arr=array();
        for($i=0;$i<count($items);$i++){
            if($items[$i]->so_hoa_don!='0'){
                $arr[]=$items[$i]->goodsunit_id;
            }            
        }
        if(count($arr)>0){
            $str=  implode(",", $arr);
            $row=Yii::app()->db->createCommand()
                    ->select("goodsunit_id,sum(tong_tien_number) as sum_tong_tien,sum(so_hoa_don) as sum_so_hoa_don,sum(so_luong_da_ban_number) as sum_so_luong_da_ban,count(distinct branch_id) as sum_so_khach_hang")
                    ->from('goods_full_view')
                    ->where("goodsunit_id IN ($str)")
                    ->andWhere($where1)
                    ->group("goodsunit_id")
                    ->queryAll();
            for($i=0;$i<count($items);$i++){
                if($items[$i]->so_hoa_don!='0'){
                    for($j=0;$j<count($row);$j++){
                        if($row[$j]['goodsunit_id']==$items[$i]->goodsunit_id){
                            $items[$i]->so_hoa_don=$row[$j]['sum_so_hoa_don'];
                            $items[$i]->so_khach_hang=$row[$j]['sum_so_khach_hang'];
                            $items[$i]->so_luong_da_ban=number_format($row[$j]['sum_so_luong_da_ban'], 0, ",", ".");
                            $items[$i]->tong_tien=number_format($row[$j]['sum_tong_tien'], 0, ",", ".");
                            break;
                        }
                    }
                }
            }
        }
        
        
        $params['items'] =$items;
        /**
         * lấy tổng tiền, tổng số lượng hàng hóa, tổng số hóa đơn
         */
        $row = Yii::app()->db->createCommand()
                ->select("sum(tong_tien_number) as sum,count(distinct goodsunit_id) as count,sum(so_luong_da_ban_number) as sum_quantity")
                ->from("goods_full_view")
                ->where($where1)
                ->queryRow();

        $params['sum'] = number_format($row['sum'], 0, ",", ".");
        $params['count'] = number_format($row['count'], 0, ",", ".");
        $params['sum_quantity'] = number_format($row['sum_quantity'], 0, ",", ".");
        $row=Yii::app()->db->createCommand("select count(distinct created_at) as bill_count,count(distinct branch_id) as customer_count from goods_full_view where $where1")->queryRow();
        $params['bill_count'] =$row['bill_count'];
        $params['customer_count'] =$row['customer_count'];
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
    /**
     * tạo mới hàng hóa mua nội địa
     */
    public function actionCreategoods() {
        $model =new Goods();
        $model->setIsNewRecord(true);
        $model->unit_full_name = Yii::app()->request->getParam("unit_full_name", "");        
        $model->goods_full_name = Yii::app()->request->getParam("goods_full_name", "");
        $model->goods_short_hand_name = Yii::app()->request->getParam("goods_short_hand_name", "");
        $model->tax = Yii::app()->request->getParam("tax", "");        
        $model->goods_full_name_for_unique_validate=  FunctionCommon::getStringForValidate($model->goods_full_name);
        $error = CActiveForm::validate($model);
        if ($error != '[]') {
            echo $error;
            Yii::app()->end();
        }


        
        $is_submit=Yii::app()->request->getParam("is_submit","0");
        if($is_submit=='1'){
            $model->save(FALSE);
        }
        else{
            echo '';
            Yii::app()->end();
        }
        
        

        echo Yii::app()->db->createCommand()->select("group_id")->from("goods")->where("id=".$model->id)->queryScalar();
        Yii::app()->end();
    }
    /**
     * tạo mới hàng hóa nhập khẩu
     */
    public function actionCreategoodsimport() {
        $model =new Goods();
        $model->setIsNewRecord(true);
        $model->unit_full_name = Yii::app()->request->getParam("unit_full_name", "");        
        $model->goods_full_name = Yii::app()->request->getParam("goods_full_name", "");
        $model->goods_short_hand_name = Yii::app()->request->getParam("goods_short_hand_name", "");
        $model->tax = Yii::app()->request->getParam("tax", 0);        
        $model->thue_tieu_thu_dac_biet = Yii::app()->request->getParam("thue_tieu_thu_dac_biet", 0);        
        $model->thue_nhap_khau = Yii::app()->request->getParam("thue_nhap_khau", 0);        
        $model->goods_full_name_for_unique_validate=  FunctionCommon::getStringForValidate($model->goods_full_name);
        $error = CActiveForm::validate($model);
        if ($error != '[]'||$model->thue_tieu_thu_dac_biet==''||$model->thue_nhap_khau=='') {
            echo $error;
            Yii::app()->end();
        }


        
        $is_submit=Yii::app()->request->getParam("is_submit","0");
        if($is_submit=='1'){
            $model->is_international=1;
            $model->save(FALSE);
        }
        else{
            echo '';
            Yii::app()->end();
        }
        

        echo Yii::app()->db->createCommand()->select("group_id")->from("goods")->where("id=".$model->id)->queryScalar();
        Yii::app()->end();
    }

    

    public function actionSavegoods() {

        $model =  Goods::model()->findByPk(Yii::app()->request->getParam("id"));        
        $model->unit_full_name = Yii::app()->request->getParam("unit_full_name", "");        
        $model->goods_full_name = Yii::app()->request->getParam("goods_full_name", "");
        $model->goods_short_hand_name = Yii::app()->request->getParam("goods_short_hand_name", "");
        $model->tax = Yii::app()->request->getParam("tax", 0);
        $model->goods_full_name_for_unique_validate=  FunctionCommon::getStringForValidate($model->goods_full_name);
        $error = CActiveForm::validate($model);
        if ($error != '[]') {
            echo $error;
            Yii::app()->end();
        }


        
        $is_submit=Yii::app()->request->getParam("is_submit","0");
        if($is_submit=='1'){
            /**
             * nếu đây là hàng hóa nhập khẩu
             * thi trong form chắc chắn se có 2 textbox thuế nhập khẩu và thuế tiêu thụ đặc biệt
             * nên cần láy value mà user nhập để lưu vào db
             */
            if($model->is_international=='1'){
                $model->thue_tieu_thu_dac_biet = Yii::app()->request->getParam("thue_tieu_thu_dac_biet", 0);        
                $model->thue_nhap_khau = Yii::app()->request->getParam("thue_nhap_khau", 0);      
            }
            $model->save(FALSE);
        }
        

        echo '';
        Yii::app()->end();
    }

    public function actionDelete() {
        $id=Yii::app()->request->getParam("id",0);
        $from_page=Yii::app()->request->getParam("from_page","");//cái này dùng để xác định, lúc xóa hàng hóa này, user đang đứng ở page nào
        if($id==0||!is_numeric($id)||($from_page!='goodsfull'&&$from_page!='goodsinputfull'&&$from_page!='goodsleftfull')){
            Yii::app()->end();
        }
        /**         
         * nếu xóa hàng hóa từ page hàng hóa nhập kho thi xóa hàng hóa này ra khỏi hệ thống
         * nếu xóa hàng hóa từ page hàng hóa đã bán thi chỉ xóa hàng hóa khỏi page này thôi chứ không phải xóa khỏi hệ thống, có nghĩa là chỉ ẩn nó đi thôi. Như vậy lúc chọn hàng hóa để bán tại page bán hàng (create/update hóa đơn bán hàng), cũng se không thấy hàng hóa này nữa.
         * nếu xóa hàng hóa từ page hàng hóa lưu kho thi chỉ xóa hàng hóa khỏi page này thôi chứ không phải xóa khỏi hệ thống, có nghĩa là chỉ ẩn nó đi thôi. 
         */
        if($from_page=='goodsfull'){//page hàng hóa đã bán            
            $model=Goods::model()->findByPk($id);
            $model->show_goodsfull=0;
            $model->save(FALSE);            
        }
        else if($from_page=='goodsinputfull'){//page hàng hóa nhập kho            
            $model=Goods::model()->findByPk($id);
            $model->delete();  
        }
        else if($from_page=='goodsleftfull'){//page hàng hóa lưu kho
            $model=Goods::model()->findByPk($id);
            $model->show_goodsleftfull=0;
            $model->save(FALSE);  
        }
        
        
    }

}
