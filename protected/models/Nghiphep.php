<?php

/**
 * 
 */
class Nghiphep extends CActiveRecord {


    /**
     * Returns the static model of the specified AR class.
     * @return Products the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    public function defaultScope() {
        return array(
            'order' =>'start_date ASC',
                )
        ;
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'nghiphep';
    }

    public function afterFind() {
        parent::afterFind();

        $this->convertCreatedAt();
        $this->convertEnddate();
        $this->convertStartdate();
    }
    public function beforeSave() {        
        if($this->getIsNewRecord()){
            $this->created_at =date("Y-m-d H:i:s");
        }
        return parent::beforeSave();
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
        
        if($this->so_ngay_nghi!=''){
            if(intval($this->so_ngay_nghi)==$this->so_ngay_nghi){
                $this->so_ngay_nghi=  intval($this->so_ngay_nghi);
            }
            else{
                $this->so_ngay_nghi=  str_replace(".", ",", $this->so_ngay_nghi);
            }
        }
        if($this->so_ngay_con_lai!=''){
            if(intval($this->so_ngay_con_lai)==$this->so_ngay_con_lai){
                $this->so_ngay_con_lai=  intval($this->so_ngay_con_lai);
            }
            else{
                $this->so_ngay_con_lai=  str_replace(".", ",", $this->so_ngay_con_lai);
            }
        }
    }
    protected function convertEnddate() {
        $DATE_FORMAT = Yii::app()->session['date_format'];
        $temp = explode(" ", $this->end_date);
        $this->end_date = $temp[0];



        if ($DATE_FORMAT == 'Y-m-d') {
            $this->end_date = implode("-", explode("-", $this->end_date));
        } elseif ($DATE_FORMAT == 'Y/m/d') {
            $this->end_date = implode("/", explode("-", $this->end_date));
        } elseif ($DATE_FORMAT == 'Ymd') {
            $this->end_date = implode("", explode("-", $this->end_date));
        } else {
            $this->end_date = implode(".", explode("-", $this->end_date));
        }
    }
    protected function convertStartdate() {
        $DATE_FORMAT = Yii::app()->session['date_format'];
        $temp = explode(" ", $this->start_date);
        $this->start_date = $temp[0];



        if ($DATE_FORMAT == 'Y-m-d') {
            $this->start_date = implode("-", explode("-", $this->start_date));
        } elseif ($DATE_FORMAT == 'Y/m/d') {
            $this->start_date = implode("/", explode("-", $this->start_date));
        } elseif ($DATE_FORMAT == 'Ymd') {
            $this->start_date = implode("", explode("-", $this->start_date));
        } else {
            $this->start_date = implode(".", explode("-", $this->start_date));
        }
    }

    

}
