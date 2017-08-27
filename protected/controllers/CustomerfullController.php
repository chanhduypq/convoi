<?php

class CustomerfullController extends Controller {

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
            if($this->not_buy=='0'){
                $where.=" and created_at is not null";
            }
            else{
                $where.=" and created_at is null";                
            }
        }
        else{
            if($this->not_buy=='0'){
                $where = "date(created_at) >= '" . FunctionCommon::convertDateForDB($this->start_date_common) . "'";
                $where.=" and date(created_at) <= '" . FunctionCommon::convertDateForDB($this->end_date_common) . "'";        
            }
            else{
                $where = "("
                        . "("
                            . "("
                                . "date(created_at) < '" . FunctionCommon::convertDateForDB($this->start_date_common) . "'";
                        $where .= " or date(created_at) > '" . FunctionCommon::convertDateForDB($this->end_date_common) . "'"
                            . ")";
                    $where.=" and branch_id1 not in"
                            . "("
                            . "select branch_id1 from customer_full_view where ";
                    $where .= "date(created_at) >= '" . FunctionCommon::convertDateForDB($this->start_date_common) . "'";
                    $where.=" and date(created_at) <= '" . FunctionCommon::convertDateForDB($this->end_date_common) . "'";        
                        $where.=")";                        
                $where.=")";
                $where.=" or created_at is null)";
            }
            
        }
        //
        if ($this->customer_id_common != "") {
            $customer_id_common = $this->customer_id_common;
            $where.=" and branch_id1=$customer_id_common";
            
        }
        if ($this->goods_id_common != "") {
            $goods_id_common = $this->goods_id_common;
            $where.=" and branch_id1 IN (select branch_id from bill join bill_detail on bill_detail.bill_id=bill.id where bill_detail.goods_id=$goods_id_common)";
            
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
        $items = CustomerFull::model()->findAll($dbCriteria);        
        if(!is_array($items)||count($items)==0){
            echo '';
            Yii::app()->end();
        }
        $branch_id_array=array();
        foreach ($items as $value) {
            $branch_id_array[]=$value->branch_id1;
        }
        
        if(count($branch_id_array)>0){
            $branch_id_array= implode(",", $branch_id_array);
            $items1=  Yii::app()->db->createCommand("select branch_id1,sum(bill_count) as sum_bill_count,sum(tong_tien) as sum_tong_tien,sum(quantity) as sum_quantity from customer_full_view where $where and branch_id1 IN ($branch_id_array) group by branch_id1")->queryAll();
        }
        else{
            $items1=array();
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
            if($this->not_buy=='1'){
                $items[$i]->tong_tien='0';
                $items[$i]->quantity='0';
                $items[$i]->bill_count='0';
            }
        }
        /**
         * code comment phía dưới chạy đúng logic nhưng chưa tối ưu về mysql
         */
//        for($i=0;$i<count($items);$i++){
//            if($items[$i]->bill_count!='0'){
//                $row=Yii::app()->db->createCommand()
//                    ->select("sum(bill_count) as sum_bill_count,sum(tong_tien) as sum_tong_tien,sum(quantity) as sum_quantity")
//                    ->from('customer_full_view')
//                    ->where("branch_id1=".$items[$i]->branch_id1)
//                    ->andWhere($where)
//                    ->queryRow();
//                $items[$i]->tong_tien=$row['sum_tong_tien'];
//                $items[$i]->quantity=$row['sum_quantity'];
//                $items[$i]->bill_count=$row['sum_bill_count'];
//            }
//            if($this->not_buy=='1'){
//                $items[$i]->tong_tien='0';
//                $items[$i]->quantity='0';
//                $items[$i]->bill_count='0';
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
            if($this->not_buy=='0'){
                $where.=" and created_at is not null";
            }
            else{
                $where.=" and created_at is null";                
            }
        }
        else{
            if($this->not_buy=='0'){
                $where = "date(created_at) >= '" . FunctionCommon::convertDateForDB($this->start_date_common) . "'";
                $where.=" and date(created_at) <= '" . FunctionCommon::convertDateForDB($this->end_date_common) . "'";        
            }
            else{
//                $where = "(date(created_at) < '" . FunctionCommon::convertDateForDB($this->start_date_common) . "'";
//                $where .= " or date(created_at) > '" . FunctionCommon::convertDateForDB($this->end_date_common) . "'";
//                $where.=" or created_at is null)";
                $where = "("
                        . "("
                            . "("
                                . "date(created_at) < '" . FunctionCommon::convertDateForDB($this->start_date_common) . "'";
                        $where .= " or date(created_at) > '" . FunctionCommon::convertDateForDB($this->end_date_common) . "'"
                            . ")";
                    $where.=" and branch_id1 not in"
                            . "("
                            . "select branch_id1 from customer_full_view where ";
                    $where .= "date(created_at) >= '" . FunctionCommon::convertDateForDB($this->start_date_common) . "'";
                    $where.=" and date(created_at) <= '" . FunctionCommon::convertDateForDB($this->end_date_common) . "'";        
                    $where.=")";                        
                $where.=")";
                $where.=" or created_at is null)";
            }
            
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
            $where1.=" and branch_id1 IN (select branch_id from bill join bill_detail on bill_detail.bill_id=bill.id where bill_detail.goods_id=$goods_id_common)";
            $where2.=" and branch_id1 IN (select branch_id from bill join bill_detail on bill_detail.bill_id=bill.id where bill_detail.goods_id=$goods_id_common)";
            $where3.=" and branch_id IN (select branch_id from bill join bill_detail on bill_detail.bill_id=bill.id where bill_detail.goods_id=$goods_id_common)";
        }
        //
        $items= CustomerFull::model()->findAll($where1);
        $branch_id_array=array();
        foreach ($items as $value) {
            $branch_id_array[]=$value->branch_id1;
        }
        if(count($branch_id_array)>0){
            $branch_id_array= implode(",", $branch_id_array);
            $items1=  Yii::app()->db->createCommand("select branch_id1,sum(bill_count) as sum_bill_count,sum(tong_tien) as sum_tong_tien,sum(quantity) as sum_quantity from customer_full_view where $where and branch_id1 IN ($branch_id_array) group by branch_id1")->queryAll();
        }
        else{
            $items1=array();
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
            if($this->not_buy=='1'){
                $items[$i]->tong_tien='0';
                $items[$i]->quantity='0';
                $items[$i]->bill_count='0';
            }
        }
        /**
         * code comment phía dưới chạy đúng logic nhưng chưa tối ưu về mysql
         */
//        for($i=0;$i<count($items);$i++){
//            if($items[$i]->bill_count!='0'){
//                $row=Yii::app()->db->createCommand()
//                    ->select("sum(bill_count) as sum_bill_count,sum(tong_tien) as sum_tong_tien,sum(quantity) as sum_quantity")
//                    ->from('customer_full_view')
//                    ->where("branch_id1=".$items[$i]->branch_id1)
//                    ->andWhere($where)
//                    ->queryRow();
//                $items[$i]->tong_tien=$row['sum_tong_tien'];
//                $items[$i]->quantity=$row['sum_quantity'];
//                $items[$i]->bill_count=$row['sum_bill_count'];
//            }
//            if($this->not_buy=='1'){
//                $items[$i]->tong_tien='0';
//                $items[$i]->quantity='0';
//                $items[$i]->bill_count='0';
//            }
//        }
        $params['items'] =$items;
        /**
         * lấy tổng tiền, tổng số lượng hàng hóa, tổng số hóa đơn
         */
        $rows=CustomerFull::model()->disableLimitDefaultScope()->findAll($where1);
        for($i=0;$i<count($rows);$i++){
            if($rows[$i]->bill_count!='0'){
                $row=Yii::app()->db->createCommand()
                    ->select("sum(bill_count) as sum_bill_count,sum(tong_tien) as sum_tong_tien,sum(quantity) as sum_quantity")
                    ->from('customer_full_view')
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
        $params['not_buy'] = Yii::app()->session['not_buy'];
        //
        $this->render('index', $params);
    }
    

}
