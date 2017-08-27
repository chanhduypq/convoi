<?php

/**
 * 
 */
class InternationalInputFull extends CActiveRecord {
    private $_defaultLimitScopeDisabled = false;
    public function disableLimitDefaultScope()
    {
          $this->_defaultLimitScopeDisabled = true;
          return $this;
    }

    public function getLimitDefaultScopeDisabled() {
        return $this->_defaultLimitScopeDisabled;
    }
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
        return 'bill_international_input_full_view';
    }   
    public function defaultScope(){
        return $this->getLimitDefaultScopeDisabled() ?
            array(
                'order'=>Yii::app()->session['international_input_list_sort'],
            )
            : 
            array(
                'order'=>Yii::app()->session['international_input_list_sort'],
                'limit'=>Yii::app()->params['number_of_items_per_page'],
            )
        ;

    }
    /**
     * 
     */    
    public function afterFind() {                        
        $this->convertCreatedAt();
        //
        parent::afterFind();        
             
    }       
    /**
     * hiển thị ngày tháng tạo hóa đơn theo định dạng đã được setting
     * năm/tháng/ngày năm-tháng-ngày nămthángngày năm.tháng.ngày
     * @return void
     */
    protected function convertCreatedAt(){
        $DATE_FORMAT = Yii::app()->session['date_format'];
        if ($DATE_FORMAT == 'Y-m-d') {            
            $this->created_at=  implode("-", explode(".", $this->created_at));
        } elseif ($DATE_FORMAT == 'Y/m/d') {
            $this->created_at=  implode("/", explode(".", $this->created_at));
        } elseif ($DATE_FORMAT == 'Ymd') {
            $this->created_at=  implode("", explode(".", $this->created_at));
        } 
    }

}
