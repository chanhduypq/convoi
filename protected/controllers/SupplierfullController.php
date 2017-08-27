<?php

class SupplierfullController extends Controller {

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
            $where.=" and branch_id1=$customer_id_common";
            
        }
        if ($this->goods_id_common != "") {
            $goods_id_common = $this->goods_id_common;
            $where.=" and branch_id1 IN (select branch_id from bill_input join bill_input_detail on bill_input_detail.bill_id=bill_input.id where bill_input_detail.goods_id=$goods_id_common)";
            
        }       
        /**
         * 
         */
        $argPage       = (int) Yii::app()->request->getQuery('page', 0);        
        $dbCriteria             = new CDbCriteria;
        $dbCriteria->condition=$where;
        $dbCriteria->limit      = Yii::app()->params['number_of_items_per_page'];
        $dbCriteria->offset     = $argPage * $dbCriteria->limit;
        /**
         * 
         */
        $items = SupplierFull::model()->findAll($dbCriteria);        
        if(!is_array($items)||count($items)==0){
            echo '';
            Yii::app()->end();
        }
        $branch_id_array1=array();
        $branch_id_array2=array();
        foreach ($items as $value) {
            if($value->bill_count!='0'){
                $branch_id_array1[]=$value->branch_id1;
            }
            else{
                $branch_id_array2[]=$value->branch_id1;
            }
            
        }
        if(count($branch_id_array1)>0){
            $branch_id_array1= implode(",", $branch_id_array1);
            $items1=  Yii::app()->db->createCommand("select branch_id1,sum(bill_count) as sum_bill_count,sum(tong_tien) as sum_tong_tien,sum(quantity) as sum_quantity from supplier_full_view where $where and branch_id1 IN ($branch_id_array1) group by branch_id1")->queryAll();
        }
        else{
            $items1=array();
        }
        if(count($branch_id_array2)>0){
            $branch_id_array2= implode(",", $branch_id_array2);        
            $items2=  Yii::app()->db->createCommand("select branch_id,count(*) as count,sum(sum) as sum,sum(tax_sum) as tax_sum from bill_chi_phi where $where and branch_id IN ($branch_id_array2) group by branch_id")->queryAll();
        }
        else{
            $items2=array();
        }        
        
        for($i=0;$i<count($items);$i++){
            if($items[$i]->bill_count!='0'){
                foreach ($items1 as $row){
                    if($items[$i]->branch_id1==$row['branch_id1']){
                        $items[$i]->tong_tien=$row['sum_tong_tien'];
                        $items[$i]->quantity=$row['sum_quantity'];
                        $items[$i]->bill_count=$row['sum_bill_count'];
                        break;
                    }
                }             
            }
            else{
                foreach ($items2 as $row){
                    if($items[$i]->branch_id1==$row['branch_id']){
                        $items[$i]->tong_tien= $row['sum']+$row['tax_sum'];
                        $items[$i]->quantity="Hàng hóa chi phí và dịch vụ.";
                        $items[$i]->bill_count=$row['count'];
                        break;
                    }
                }    
            }
        }
        /**
         * code comment phía dưới chạy đúng logic nhưng chưa tối ưu về mysql
         * xem lại $items[$i]->quantity="Hàng hóa chi phí và dịch vụ.";, kết quả hiển thị khác nhau
         */
//        for($i=0;$i<count($items);$i++){
//            if($items[$i]->bill_count!='0'){
//                $row=Yii::app()->db->createCommand()
//                    ->select("sum(bill_count) as sum_bill_count,sum(tong_tien) as sum_tong_tien,sum(quantity) as sum_quantity")
//                    ->from('supplier_full_view')
//                    ->where("branch_id1=".$items[$i]->branch_id1)
//                    ->andWhere($where)
//                    ->queryRow();
//                $items[$i]->tong_tien=$row['sum_tong_tien'];
//                $items[$i]->quantity=$row['sum_quantity'];
//                $items[$i]->bill_count=$row['sum_bill_count'];
//            }
//            else{
//                $row=Yii::app()->db->createCommand()
//                        ->select("count(*) as count,sum(sum) as sum,sum(tax_sum) as tax_sum")
//                        ->from('bill_chi_phi')
//                        ->where("branch_id=".$items[$i]->branch_id1)
//                        ->andWhere($where)
//                        ->queryRow();
//                if(is_array($row)&&count($row)>0){
//                    $items[$i]->tong_tien= $row['sum']+$row['tax_sum'];
//                    $items[$i]->quantity="Hàng hóa chi phí và dịch vụ.";
//                    $items[$i]->bill_count=$row['count'];
//                }
//            }
//        }
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
        $where1 = $where2 = $where3 = $where;

        if ($this->customer_id_common != "") {
            $customer_id_common = $this->customer_id_common;
            $where1.=" and branch_id1=$customer_id_common";
            $where2.=" and branch_id1=$customer_id_common";
            $where3.=" and branch_id=$customer_id_common";
        }
        if ($this->goods_id_common != "") {
            $goods_id_common = $this->goods_id_common;
            $where1.=" and branch_id1 IN (select branch_id from bill_input join bill_input_detail on bill_input_detail.bill_id=bill_input.id where bill_input_detail.goods_id=$goods_id_common)";
            $where2.=" and branch_id1 IN (select branch_id from bill_input join bill_input_detail on bill_input_detail.bill_id=bill_input.id where bill_input_detail.goods_id=$goods_id_common)";
            $where3.=" and branch_id IN (select branch_id from bill_input join bill_input_detail on bill_input_detail.bill_id=bill_input.id where bill_input_detail.goods_id=$goods_id_common)";
        }
        //
        $items = SupplierFull::model()->findAll($where1);
        $branch_id_array1=array();
        $branch_id_array2=array();
        foreach ($items as $value) {
            if($value->bill_count!='0'){
                $branch_id_array1[]=$value->branch_id1;
            }
            else{
                $branch_id_array2[]=$value->branch_id1;
            }
            
        }
        if(count($branch_id_array1)>0){
            $branch_id_array1= implode(",", $branch_id_array1);
            $items1=  Yii::app()->db->createCommand("select branch_id1,sum(bill_count) as sum_bill_count,sum(tong_tien) as sum_tong_tien,sum(quantity) as sum_quantity from supplier_full_view where $where and branch_id1 IN ($branch_id_array1) group by branch_id1")->queryAll();
        }
        else{
            $items1=array();
        }
        if(count($branch_id_array2)>0){
            $branch_id_array2= implode(",", $branch_id_array2);        
            $items2=  Yii::app()->db->createCommand("select branch_id,count(*) as count,sum(sum) as sum,sum(tax_sum) as tax_sum from bill_chi_phi where $where and branch_id IN ($branch_id_array2) group by branch_id")->queryAll();
        }
        else{
            $items2=array();
        }  
        for($i=0;$i<count($items);$i++){
            if($items[$i]->bill_count!='0'){
                foreach ($items1 as $row){
                    if($items[$i]->branch_id1==$row['branch_id1']){
                        $items[$i]->tong_tien=$row['sum_tong_tien'];
                        $items[$i]->quantity=$row['sum_quantity'];
                        $items[$i]->bill_count=$row['sum_bill_count'];
                        break;
                    }
                }             
            }
            else{
                foreach ($items2 as $row){
                    if($items[$i]->branch_id1==$row['branch_id']){
                        $items[$i]->tong_tien= $row['sum']+$row['tax_sum'];
                        $items[$i]->quantity="Hàng hóa chi phí và dịch vụ.";
                        $items[$i]->bill_count=$row['count'];
                        break;
                    }
                }    
            }
        }
        /**
         * code comment phía dưới chạy đúng logic nhưng chưa tối ưu về mysql
         */
//        for($i=0;$i<count($items);$i++){
//            if($items[$i]->bill_count!='0'){
//                $row=Yii::app()->db->createCommand()
//                    ->select("sum(bill_count) as sum_bill_count,sum(tong_tien) as sum_tong_tien,sum(quantity) as sum_quantity")
//                    ->from('supplier_full_view')
//                    ->where("branch_id1=".$items[$i]->branch_id1)
//                    ->andWhere($where)
//                    ->queryRow();
//                $items[$i]->tong_tien=$row['sum_tong_tien'];
//                $items[$i]->quantity=$row['sum_quantity'];
//                $items[$i]->bill_count=$row['sum_bill_count'];
//            }
//            else{
//                $row=Yii::app()->db->createCommand()
//                        ->select("count(*) as count,sum(sum) as sum,sum(tax_sum) as tax_sum")
//                        ->from('bill_chi_phi')
//                        ->where("branch_id=".$items[$i]->branch_id1)
//                        ->andWhere($where)
//                        ->queryRow();
//                if(is_array($row)&&count($row)>0){
//                    $items[$i]->tong_tien= $row['sum']+$row['tax_sum'];
//                    $items[$i]->quantity="Hàng hóa chi phí và dịch vụ.";
//                    $items[$i]->bill_count=$row['count'];
//                }
//            }
//        }
        $params['items'] =$items;
        /**
         * lấy tổng tiền, tổng số lượng hàng hóa, tổng số hóa đơn
         */        
        $rows=SupplierFull::model()->disableLimitDefaultScope()->findAll($where1);
        for($i=0;$i<count($rows);$i++){
            if($rows[$i]->bill_count!='0'){
                $row=Yii::app()->db->createCommand()
                    ->select("sum(bill_count) as sum_bill_count,sum(tong_tien) as sum_tong_tien,sum(quantity) as sum_quantity")
                    ->from('supplier_full_view')
                    ->where("branch_id1=".$rows[$i]->branch_id1)
                    ->andWhere($where)
                    ->queryRow();
                $rows[$i]->tong_tien=$row['sum_tong_tien'];
                $rows[$i]->quantity=$row['sum_quantity'];
                $rows[$i]->bill_count=$row['sum_bill_count'];
            }
        }
        $sum=$bill_count=$sum_quantity=0;
        foreach ($rows as $row) {
            $sum+=(str_replace(".", "", $row->tong_tien));            
            $sum_quantity+=$row->quantity;
            $bill_count+=$row->bill_count;
        }
        $params['sum'] = number_format($sum, 0, ",", ".");        
        $params['count'] = number_format(count($rows), 0, ",", ".");
        $params['sum_quantity'] = number_format($sum_quantity, 0, ",", ".");
        $params['bill_count']=$bill_count;
        /**
         * 
         */
        $params['start_date_common'] = Yii::app()->session['start_date_common'];
        $params['end_date_common'] = Yii::app()->session['end_date_common'];
        $params['customer_id_common'] = Yii::app()->session['customer_id_common'];
        $params['goods_id_common'] = Yii::app()->session['goods_id_common'];
        $params['all_time_common'] = Yii::app()->session['all_time_common'];
        //
        $this->render('index', $params);
    }
    public function actionDelete(){
        $id=Yii::app()->request->getParam("id",0);
        $model=Branch::model()->findByPk($id);
        $model->delete();        
    }

}
