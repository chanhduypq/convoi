<?php

/**
 * 
 */
class CustomerFull extends CActiveRecord {
    private $_defaultLimitScopeDisabled = false;
    public $firstname_lastname_phone_email;
    public $separator=" | ";
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
        return 'customer_full_view';
    }
    public function defaultScope(){
        return $this->getLimitDefaultScopeDisabled() ?
            array(
                'group'=>'branch_id1',
                'order'=>Yii::app()->session['customer_list_sort'],
            )
            : 
            array(
                'group'=>'branch_id1',
                'order'=>Yii::app()->session['customer_list_sort'],
                'limit'=>Yii::app()->params['number_of_items_per_page'],
            )
        ;

    }
    public function afterFind() {
        parent::afterFind();
        $this->set_firstname_lastname_phone_email();  
    }
    /**
     * first_name:a; last_name: b; phone: 0905; email: all@mns.vn
     * ->firstname_lastname_phone_email: a b | 0905 | all@mns.vn
     * @return void
     */
    protected function set_firstname_lastname_phone_email(){
        $this->firstname_lastname_phone_email="";
        if($this->first_name!=""||$this->last_name!=""){
            $this->firstname_lastname_phone_email.=$this->first_name.' '.$this->last_name;
        }
        if($this->phone!=""){
            if($this->firstname_lastname_phone_email!=""){
                $this->firstname_lastname_phone_email.=$this->separator.$this->phone;
            }
            else{
                $this->firstname_lastname_phone_email.=$this->phone;
            }
        }
        if($this->email!=""){
            if($this->firstname_lastname_phone_email!=""){
                $this->firstname_lastname_phone_email.=$this->separator.$this->email;
            }
            else{
                $this->firstname_lastname_phone_email.=$this->email;
            }
        }     
        if($this->tax_code_chinhanh!=''){
            $this->tax_code.=' - '.$this->tax_code_chinhanh;
        }
    }

}
