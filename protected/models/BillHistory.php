<?php

/**
 * 
 */
class BillHistory extends CActiveRecord {
    const PRINT_LIEN1=1;
    const PRINT_LIEN2=2;

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
        return 'bill_history';
    }
    /**
     * 
     * @param int $bill_id
     * @param int $type
     * @return int
     * đếm số lần in liên 1 hoặc liên 2
     */
    public static function getPrintCount($bill_id,$type){
        $count_print =  BillHistory::model()->count("bill_id=$bill_id "
                                                    . "and (is_preview is null or is_preview=0) "
                                                    . "and printed_at is not null "
                                                    . "and print_type=$type");
        if($count_print==FALSE){
            $count_print=0;
        }
        return $count_print;
    }     
    /**
     * lấy lịch sử hóa đơn
     * @param int $bill_id
     * @return array
     */
    public static function getBillHistoty($bill_id){        
        $DATE_FORMAT=  FunctionCommon::convertDateForDBSelect();
        $histoty_array = Yii::app()->db->createCommand()
                ->select("user.danh_xung,user.full_name,is_preview,print_type,bill_history.id,date_format(bill_history.updated_at,'$DATE_FORMAT - %H:%i:%s') AS updated_at_date,date_format(bill_history.printed_at,'$DATE_FORMAT - %H:%i:%s') AS printed_at_date,data,reason")
                ->from("bill_history")
                ->leftJoin("user", "user.id=bill_history.user_id")
                ->where("bill_id=$bill_id")// and (is_preview is null or is_preview=0)")
                ->order("bill_history.id ASC")
                ->queryAll()
        ;
        self::setIconForPrint($histoty_array);
        return $histoty_array;
    }
    /**
     * hiển thị icon check hoặc dấu 'X'
     * ví dụ: 
     *      ngày 01/01/2015 đã in liên 1 thi liên 1 được check còn liên 2 hiển thị dấu 'X'
     *      ngày 02/01/2015 chỉ sửa mà k in thi cả liên 1 và liên 2 đều hiển thị dấu 'X'
     * @param array $histoty_array
     * @return void
     */
    public static function setIconForPrint(&$histoty_array){
        for($i=0;$i<count($histoty_array);$i++){            
            if($histoty_array[$i]['printed_at_date']!=''){
                $histoty_array[$i]['date']=$histoty_array[$i]['printed_at_date'];         
                if($histoty_array[$i]['print_type']==self::PRINT_LIEN1){ 
                    if($histoty_array[$i]['is_preview']==''||$histoty_array[$i]['is_preview']==0){
                        $count_lien1='<img style="width: 15px;height: 15px;" src="'.Yii::app()->theme->baseUrl.'/images/icon/checked_icon.png"/>';
                    }
                    else{
                        $count_lien1='X';
                    }
                    $count_lien2='X';
                }
                else if($histoty_array[$i]['print_type']==self::PRINT_LIEN2){
                    $count_lien1='X';
                    if($histoty_array[$i]['is_preview']==''||$histoty_array[$i]['is_preview']==0){
                        $count_lien2='<img style="width: 15px;height: 15px;" src="'.Yii::app()->theme->baseUrl.'/images/icon/checked_icon.png"/>';
                    }
                    else{
                        $count_lien2='X';
                    }
                    
                }
            }
            else if($histoty_array[$i]['updated_at_date']!=''){
                $histoty_array[$i]['date']=$histoty_array[$i]['updated_at_date'];   
                $count_lien1='X';
                $count_lien2='X';
            }
            $histoty_array[$i]['count_lien1']=$count_lien1;            
            $histoty_array[$i]['count_lien2']=$count_lien2;
        }
    }
    public function beforeSave() {
        $this->user_id=Yii::app()->session['user_id'];
        return parent::beforeSave();
    }
}
