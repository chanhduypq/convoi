<?php

class GoodsleftfullController extends Controller {

    public function init() {
        parent::init();
    }

    public function actionMore() {
        $params = array();
        /**
         * 
         */
        if($this->goodsleft_equal_0=='1'){
            $where = "so_luong_da_ban_number = 0";            
        }
        else{
            $where="so_luong_da_ban_number > 0";
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
        $items = GoodsLeftFull::model()->findAll($dbCriteria);
        if (!is_array($items) || count($items) == 0) {
            echo '';
            Yii::app()->end();
        }
        $params['items'] = $items;
        $this->renderPartial('//render_partial/common/more', $params);
    }
    
    

    public function actionIndex() {        
        $params = array();
        /**
         * 
         */
        $where = "1=1";
        $where1 = $where2 = $where;
        $where3 = " and $where";
        $where42 = "1=1 ";
        
        if ($this->customer_id_common != "") {
            $customer_id_common = $this->customer_id_common;
            $temp=" and branch_id=$customer_id_common";
            $where1.=$temp;
            $where2.=$temp;
            $where_branch_id=$temp;
        }
        else{
            $where_branch_id="";
        }
        if ($this->goods_id_common != "") {
            $goods_id_common = $this->goods_id_common;
            $temp=" and goodsunit_id=$goods_id_common";                                   
            $where1.=$temp;
            $where3.=" and goods_id=$goods_id_common";
            $where42.=$temp;
        }
        /**
         * 
         */
        if($this->goodsleft_equal_0=='1'){
            $where1 .= " and so_luong_da_ban_number = 0";            
        }
        else{
            $where1.=" and so_luong_da_ban_number > 0";            
        }
        
        $params['items'] =GoodsLeftFull::model()->findAll($where1);//Yii::app()->db->createCommand("select * from goods_left_full_view where $where1 limit 20")->queryAll();// GoodsLeftFull::model()->findAll($where1);
        
        $rows=GoodsLeftFull::model()->disableLimitDefaultScope()->findAll($where1);
        
        $sum=$bill_count=$customer_count=$bill_count_is_international=$customer_count_is_international=$sum_quantity=0;
        foreach ($rows as $row) {
            $sum+=$row->tong_tien_number;            
            $sum_quantity+=$row->so_luong_da_ban_number;
        }
        $params['sum'] = number_format($sum, 0, ",", ".");        
        
        $params['count'] = number_format(count($rows), 0, ",", ".");
        $params['sum_quantity'] = number_format($sum_quantity, 0, ",", ".");

        $params['bill_count'] = Yii::app()->db->createCommand(
                                                        "select count(distinct bill_id) from bill_input_detail join goods_left on bill_input_detail.goods_id=goods_left.id and goods_left.quantity_left>0 where goods_id IN "
                                                                                                                                               . "  (select goodsunit_id from goods_left_full_view where $where1 $where_branch_id) "
                                                                                           
                                                                                       . "$where3 and is_international=0"
                                                )
                ->queryScalar();



        $params['customer_count'] = Yii::app()->db->createCommand(
                "select count(distinct branch_id) from goods_left_full_view "
                . "join goods_left on goods_left_full_view.goodsunit_id=goods_left.id and goods_left.quantity_left>0 "
                . "join bill_input_detail on bill_input_detail.goods_id=goods_left.id "
                . "where $where1 and is_international=0")->queryScalar();
        $params['bill_count_is_international'] = Yii::app()->db->createCommand(
                                                        "select count(distinct bill_id) from bill_input_detail join goods_left on bill_input_detail.goods_id=goods_left.id and goods_left.quantity_left>0 where goods_id IN "
                                                                                                                                               . "  (select goodsunit_id from goods_left_full_view where $where1 $where_branch_id) "

                                                                                       . "$where3 and is_international=1"
                                                )
                ->queryScalar();


        $params['customer_count_is_international'] = Yii::app()->db->createCommand(
                "select count(distinct branch_id) from goods_left_full_view "
                . "join goods_left on goods_left_full_view.goodsunit_id=goods_left.id and goods_left.quantity_left>0 "
                . "join bill_input_detail on bill_input_detail.goods_id=goods_left.id "
                . "where $where1 and is_international=1")->queryScalar();
        /**
         * 
         */
        $params['start_date_common'] = Yii::app()->session['start_date_common'];
        $params['end_date_common'] = Yii::app()->session['end_date_common'];
        $params['customer_id_common'] = Yii::app()->session['customer_id_common'];
        $params['goods_id_common'] = Yii::app()->session['goods_id_common'];
        $params['all_time_common'] = Yii::app()->session['all_time_common'];
        $params['goodsleft_equal_0'] = Yii::app()->session['goodsleft_equal_0'];
        $this->render('index', $params);
    }

    

}
