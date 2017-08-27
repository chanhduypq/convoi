<?php

/**
 * 
 */
class ChiPhiKhdFull extends CActiveRecord {
    public $payment_method_text;
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
        return 'chi_phi_khd_full_view';
    }
    public function defaultScope(){
        return array(
            'order'=>Yii::app()->session['chi_phi_khd_list_sort'],
            'limit'=>Yii::app()->params['number_of_items_per_page'],
        );
    }    
    /**
     * 
     */    
    public function afterFind() {                        
        $this->convertCreatedAt();
        if($this->payment_method_id==PaymentMethod::TIEN_MAT){
            $this->payment_method_text="Tiền mặt";
        }
        else if($this->payment_method_id==PaymentMethod::CHUYEN_KHOAN_ACB){
            $this->payment_method_text="Chuyển khoản ACB";
        }
        else if($this->payment_method_id==PaymentMethod::CHUYEN_KHOAN_VIETCOMBANK){
            $this->payment_method_text="Chuyển khoản Vietcombank";
        }
        else if($this->payment_method_id==PaymentMethod::CHUA_THANH_TOAN){
            $this->payment_method_text="Chưa thanh toán";
        }
        else if($this->payment_method_id==PaymentMethod::KHONG_THANH_TOAN){
            $this->payment_method_text="Không thanh toán";
        }
        else if($this->payment_method_id==PaymentMethod::THANH_TOAN_KHAC){
            $this->payment_method_text="Thanh toán khác";
        }
        else if($this->payment_method_id==PaymentMethod::TAM_UNG){
            $this->payment_method_text="Tạm ứng";
        }
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
