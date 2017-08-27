<?php

/**
 * 
 */
class KxhdHistory extends CActiveRecord {    
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
        return 'kxhd_history';
    }      
    /**
     * lấy lịch sử sửa hóa đơn
     * @param int $bill_id
     * @return array
     */
    public static function getUpdateHistoty($bill_id){        
        $DATE_FORMAT=  FunctionCommon::convertDateForDBSelect();
        $update_histoty_array = Yii::app()->db->createCommand()
                ->select("user.danh_xung,user.full_name,kxhd_history.id,date_format(kxhd_history.updated_at,'$DATE_FORMAT - %H:%i:%s') AS updated_at_date,data,reason")
                ->from("kxhd_history")
                ->leftJoin("user", "user.id=kxhd_history.user_id")
                ->where("bill_id=$bill_id")
                ->order("kxhd_history.id ASC")
                ->queryAll()
        ;
        return $update_histoty_array;
    }
    public function beforeSave() {
        $this->user_id=Yii::app()->session['user_id'];
        return parent::beforeSave();
    }

    

    

}
