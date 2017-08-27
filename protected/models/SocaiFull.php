<?php

/**
 * 
 */
class SocaiFull extends CActiveRecord {
    

    private $_defaultLimitScopeDisabled = false;
    public $is_edit;
    public $link;

    public function disableLimitDefaultScope() {
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

    public function defaultScope() {
        return $this->getLimitDefaultScopeDisabled() ?
                array(
            'order' =>Yii::app()->session['socai_list_sort'],
                ) :
                array(
            'order' =>Yii::app()->session['socai_list_sort'],
            'limit' => Yii::app()->params['number_of_items_per_page'],
                )
        ;
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'socai_full_view';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {

        return array(
        );
    }

    public function afterFind() {
        parent::afterFind();
        /**
         * tạo link cho các tham chiếu
         */
        if ($this->bill_id != '' || $this->bill_input_id != '' || $this->bill_chi_phi_id != ''|| $this->sxdv_id != '' || $this->kxhd_id != ''|| $this->lai_suat_id != '' || $this->chi_phi_khd_id != '') {
            if($this->bill_id != ''){
                if(FunctionCommon::get_role()==Role::ADMIN||FunctionCommon::get_role()==Role::QUAN_LY_BAN_HANG){
                    $this->link="/invoicefull/update/id/".$this->bill_id;
                }
                else{
                    $this->link="/invoicefull/view/id/".$this->bill_id;
                }                
            }
            else if($this->bill_input_id != ''){
                $is_international=Yii::app()->db->createCommand("select is_international from bill_input where id=".$this->bill_input_id)->queryScalar();
                if($is_international=="1"){
                    $controller="internationalinput";
                }
                else{
                    $controller="invoiceinputfull";
                }
                if(FunctionCommon::get_role()==Role::ADMIN||FunctionCommon::get_role()==Role::QUAN_LY_KHO_HANG){                    
                    $this->link="/$controller/update/id/".$this->bill_input_id;
                }
                else{
                    $this->link="/$controller/view/id/".$this->bill_input_id;
                }
            }
            else if($this->bill_chi_phi_id != ''){
                if(FunctionCommon::get_role()==Role::ADMIN||FunctionCommon::get_role()==Role::QUAN_LY_KHO_HANG){
                    $this->link="/invoicechiphifull/update/id/".$this->bill_chi_phi_id;
                }
                else{
                    $this->link="/invoicechiphifull/view/id/".$this->bill_chi_phi_id;
                }
            }
            else if($this->sxdv_id != ''){
                if(FunctionCommon::get_role()==Role::ADMIN||FunctionCommon::get_role()==Role::QUAN_LY_BAN_HANG){
                    $this->link="/sxdvfull/update/id/".$this->sxdv_id;
                }
                else{
                    $this->link="/sxdvfull/view/id/".$this->sxdv_id;
                }
            }
            else if($this->kxhd_id != ''){
                if(FunctionCommon::get_role()==Role::ADMIN||FunctionCommon::get_role()==Role::QUAN_LY_BAN_HANG){
                    $this->link="/kxhdfull/update/id/".$this->kxhd_id;
                }
                else{
                    $this->link="/kxhdfull/view/id/".$this->kxhd_id;
                }
            }
            else if($this->lai_suat_id != ''){
                if(FunctionCommon::get_role()==Role::ADMIN||FunctionCommon::get_role()==Role::QUAN_LY_BAN_HANG){
                    $this->link="/laisuatfull/update/id/".$this->lai_suat_id;
                }
                else{
                    $this->link="/laisuatfull/view/id/".$this->lai_suat_id;
                }
            }
            else if($this->chi_phi_khd_id != ''){
                if(FunctionCommon::get_role()==Role::ADMIN||FunctionCommon::get_role()==Role::QUAN_LY_KHO_HANG){
                    $this->link="/chiphikhdfull/update/id/".$this->chi_phi_khd_id;
                }
                else{
                    $this->link="/chiphikhdfull/view/id/".$this->chi_phi_khd_id;
                }
            }
        } else {
            $this->link='';
        }
        $this->convertCreatedAt();
        /**
         * nếu thu=0 hoặc chi=0 thi không cho hiển thị, tức là hiển thị rỗng
         * còn nếu khác 0 thi format lại số cho nó dễ nhìn
         * ví dụ: 1000000 thi hiển thị 1.000.000
         */
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
        
        $temp=  explode("-", $this->created_at);
        /**
         * nếu giao dịch là ứng tiền hoặc hoàn tiền thi không cho edit
         */
        if($this->giao_dich=='Ứng tiền'||$this->giao_dich=='Hoàn tiền'){
            $this->is_edit=FALSE;
        }
        else{
            $this->is_edit=true;
        }

        
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
