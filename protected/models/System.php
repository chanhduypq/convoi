<?php

/**
 * 
 */
class System extends CActiveRecord {
    /**
     * get danh sách mẫu hóa đơn
     * @return array
     */
    public static function get_bill_type(){
        $bill_type_array=Yii::app()->db->createCommand()
                ->select()
                ->from("bill_type")
                ->where("id=1")
                ->queryAll();
        return $bill_type_array;
    }
    /**
     * get đường dẫn thư mục lưu trữ hóa đơn
     * @return string
     */
    public static function get_path_for_save_bill(){
        $path_for_save_bill=Yii::app()->db->createCommand()
                ->select("path")
                ->from("save_bill_path")
                ->queryScalar();
        if($path_for_save_bill==FALSE){
            $path_for_save_bill='';
        }
        return $path_for_save_bill;
    }
}
