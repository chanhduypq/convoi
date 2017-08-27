<?php

/**
 * 
 */
class InvoiceFull extends CActiveRecord {
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
        return 'bill_full_view';
    }
    public function defaultScope(){
        return $this->getLimitDefaultScopeDisabled() ?
            array(
                'order'=>Yii::app()->session['invoice_list_sort'],
            )
            : 
            array(
                'order'=>Yii::app()->session['invoice_list_sort'],
                'limit'=>Yii::app()->params['number_of_items_per_page'],
            )
        ;

    }
    

    

    
    /**
     * 
     */    
    public function afterFind() {                
        $this->convertBillNumber();
        $this->convertCreatedAt();
        //
        parent::afterFind();        
             
    }   
    /**
     * chuyển đổi số hóa đơn thành một dãy 7 chữ số:
     * ví dụ: 300->0000300; 55120->0055120;...
     * @return void
     */
    protected function convertBillNumber(){
        $bill_number=$this->bill_number;
        $zero_string="";
        $NUMBER_OF_DIGIT_OF_BILLNUMBER=Yii::app()->params['NUMBER_OF_DIGIT_OF_BILLNUMBER'];
        for($i=0;$i<$NUMBER_OF_DIGIT_OF_BILLNUMBER-strlen($bill_number);$i++){
            $zero_string.="0";
        }
        $this->bill_number=$zero_string.$bill_number;
    }
    /**
     * hiển thị ngày tháng tạo hóa đơn theo định dạng đã được setting
     * năm/tháng/ngày năm-tháng-ngày nămthángngày năm.tháng.ngày
     * @return void
     */
    protected function convertCreatedAt(){
        $DATE_FORMAT = Yii::app()->session['date_format'];
        /**
         * trong view bill_input_full_view
         * field created_at (đồng nghĩa với attribute created_at)
         * đã được định dạng kiểu năm.tháng.ngày
         * do đó, ở đây chỉ if elseif theo 3 trường hợp mà thôi: năm/tháng/ngày năm-tháng-ngày nămthángngày
         */
        if ($DATE_FORMAT == 'Y-m-d') {            
            $this->created_at=  implode("-", explode(".", $this->created_at));
        } elseif ($DATE_FORMAT == 'Y/m/d') {
            $this->created_at=  implode("/", explode(".", $this->created_at));
        } elseif ($DATE_FORMAT == 'Ymd') {
            $this->created_at=  implode("", explode(".", $this->created_at));
        } 
    }

}
