<?php

/**
 * 
 */
class ThuchiHistory extends CActiveRecord {
    public $nguoi_thuc_hien;
    public $tm_ck_khac;
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
        return 'thuchi_history';
    }
    /**
     * 
     */
    public function afterFind() {
        parent::afterFind();
        $DATE_FORMAT = Yii::app()->session['date_format'];
        $newtime = strtotime($this->log_date) + (7 * 60 * 60); 
        $this->log_date=date($DATE_FORMAT.' - H:i:s', $newtime);  
        $this->convertCreatedAt();
        $user=  User::model()->findByPk($this->user_id);
        $this->nguoi_thuc_hien=$user->danh_xung." ".$user->full_name;
        if($this->type==ThuChi::TIEN_MAT){
            $this->tm_ck_khac="Tiền mặt";
        }
        else if($this->type==ThuChi::CHUYEN_KHOAN){
            $this->tm_ck_khac="Chuyển khoản";
        }
        else if($this->type==ThuChi::OTHER){
            $this->tm_ck_khac="Khác";
        }
        if ($this->thu == '0') {
            $this->thu = '';
        } else {
            $this->thu = number_format($this->thu, 0, ",", ".");
        }
        if ($this->chi == '0') {
            $this->chi = '';
        } else {
            $this->chi = number_format($this->chi, 0, ",", ".");
        }
        if ($this->tm != "") {
            $this->tm = number_format($this->tm, 0, ",", ".");
        }
    }
    protected function convertCreatedAt() {
        $DATE_FORMAT = Yii::app()->session['date_format'];
        $temp = explode(" ", $this->created_at);
        $this->created_at = $temp[0];

        if ($DATE_FORMAT == 'Y-m-d') {
            $this->created_at = implode("-", explode("-", $this->created_at));
        } elseif ($DATE_FORMAT == 'Y/m/d') {
            $this->created_at = implode("/", explode("-", $this->created_at));
        } elseif ($DATE_FORMAT == 'Ymd') {
            $this->created_at = implode("", explode("-", $this->created_at));
        } else {
            $this->created_at = implode(".", explode("-", $this->created_at));
        }
    }
    
}
