<?php

/**
 * 
 */
class GoodslLeft extends CActiveRecord {
     

    /**
     * Returns the static model of the specified AR class.
     * @return Products the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'goods_left';
    }
    /**
     * update giá trung bình của 1 hàng hóa ứng với số lượng tồn trong kho
     * @param int $goods_id
     * @return void
     */
    public static function update_avg_price($goods_id){
        /**
         * lấy tổng số lượng đã bán
         */
        $quantity_sum=Yii::app()->db->createCommand()
                ->select("sum(quantity) as sum")
                ->from("bill_detail")
                ->where("goods_id=$goods_id")
                ->queryScalar();        
        if($quantity_sum==NULL||$quantity_sum==FALSE){
            $quantity_sum=0;
        }
        /**
         * lấy số lượng nhập về (bao gồm mua trong nước và nhập khẩu)
         */
        $bill_input_detail=Yii::app()->db->createCommand()
                ->select("bill_input_detail.quantity,bill_input_detail.price_has_tax")
                ->from("bill_input_detail")
                ->join("bill_input", "bill_input.id=bill_input_detail.bill_id")
                ->where("goods_id=$goods_id")
                ->order("bill_input.created_at ASC")
                ->queryAll();
        /**
         * ứng với mỗi số lượng trong mỗi lần mua về:
         *     - tổng số lượng bán se bị trừ bớt
         *     - set số lượng mua đó của lần đó = 0
         * cho đến khi tổng số lượng bán nhỏ hơn số lượng mua lần đó. Đến luc này thi
         *     - set số lượng mua lần đó = số lượng mua lần đó trừ tổng số lượng bán còn lại (tổng số lượng bán đã trừ nhiều lần)
         */
        for($i=0,$n=count($bill_input_detail);$i<$n;$i++){
            if($quantity_sum>=$bill_input_detail[$i]['quantity']){
                $quantity_sum-=$bill_input_detail[$i]['quantity'];
                $bill_input_detail[$i]['quantity']=0;
            }
            else{
                $new_quantity=$bill_input_detail[$i]['quantity']-$quantity_sum;
                $bill_input_detail[$i]['quantity']=$new_quantity;
                break;
            }            
        }
        /**
         * sau khi đã thay đổi giá trị của các element trong array $bill_input_detail
         * tính lại tổng tiền và tính lại số lượng
         * rồi chia ra dc giá trung bình
         */
        $sum1=$sum2=0;
        foreach ($bill_input_detail as $value) {
            $sum1+=($value['quantity']*$value['price_has_tax']);
            $sum2+=$value['quantity'];
        }
        if($sum2==0){
            $price_has_tax= 0;
        }
        else{
            $price_has_tax=intval(ceil($sum1/$sum2));
        }
        Yii::app()->db->createCommand("update goods_left set avg_price=$price_has_tax,tong_tien=$sum1 where id=$goods_id")->execute();       
    }
    /**
     * update số nhà cung ứng trong nước, nhà cung ứng quốc tế, số hóa đơn bán hàng, số tờ khai của 1 hàng hóa
     * @param int $goods_id
     * @return void
     */
    public static function update_so_khach_hang_so_hoa_don($goods_id){
        /**
         * lấy tổng số lượng đã bán
         */
        $quantity_sum=Yii::app()->db->createCommand()
                ->select("sum(quantity) as sum")
                ->from("bill_detail")
                ->where("goods_id=$goods_id")
                ->queryScalar();        
        if($quantity_sum==NULL||$quantity_sum==FALSE){
            $quantity_sum=0;
        }
        /**
         * lấy số lượng nhập về (bao gồm mua trong nước và nhập khẩu)
         */
        $bill_input_detail=Yii::app()->db->createCommand()
                ->select("bill_input_detail.quantity,bill_input_detail.price_has_tax,bill_input.branch_id,bill_input.id,bill_input_detail.is_international")
                ->from("bill_input_detail")
                ->join("bill_input", "bill_input.id=bill_input_detail.bill_id")
                ->where("goods_id=$goods_id")
                ->order("bill_input.created_at ASC")
                ->queryAll();
        /**
         * ứng với mỗi số lượng trong mỗi lần mua về:
         *     - tổng số lượng bán se bị trừ bớt
         *     - set branch_id của lần đó = 0 có nghĩa là loại bỏ nhà cung ứng đó ra có nghía la lúc tính tổng số nhà cung ứng thi bớt đi một đơn vị tại element của array $bill_input_detail
         *     - set id của lần đó = 0 có nghĩa là loại bỏ tờ khai/số hóa đơn bán hàng đó ra có nghía la lúc tính tổng số tờ khai/số hóa đơn bán hàng thi bớt đi một đơn vị tại element của array $bill_input_detail
         * cho đến khi tổng số lượng bán nhỏ hơn số lượng mua lần đó. Đến luc này thi break, không làm gì cả
         */
        for($i=0,$n=count($bill_input_detail);$i<$n;$i++){
            if($quantity_sum>=$bill_input_detail[$i]['quantity']){
                $quantity_sum-=$bill_input_detail[$i]['quantity'];                
                $bill_input_detail[$i]['branch_id']=0;
                $bill_input_detail[$i]['id']=0;
            }
            else{                
                break;
            }            
        }
        
        $khach_hang=array();
        $nguoi_nuoc_ngoai=array();
        $hoa_don=array();
        $to_khai=array();
        foreach ($bill_input_detail as $value) {            
            if($value['branch_id']!=0){
                if($value['is_international']=='1'){
                    $nguoi_nuoc_ngoai[$value['branch_id']]='';
                }
                else{
                    $khach_hang[$value['branch_id']]='';
                }
                
            }
            if($value['id']!=0){
                if($value['is_international']=='1'){
                    $to_khai[$value['id']]='';
                }
                else{
                    $hoa_don[$value['id']]='';
                }
                
            }
        }
        $so_khach_hang=count($khach_hang);
        $so_nguoi_nuoc_ngoai=count($nguoi_nuoc_ngoai);
        $so_hoa_don=count($hoa_don);
        $so_to_khai=count($to_khai);
        
        Yii::app()->db->createCommand("update goods_left set so_khach_hang=$so_khach_hang,so_nguoi_nuoc_ngoai=$so_nguoi_nuoc_ngoai,so_hoa_don=$so_hoa_don,so_to_khai=$so_to_khai where id=$goods_id")->execute();       
    }

       

}
