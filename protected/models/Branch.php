<?php

/**
 * 
 */
class Branch extends CActiveRecord {

    const CUSTOMER = '1';
    const SUPPLIER = '2';
    const BOTH_CUSTOMER_SUPPLIER = '3';
    const QUOC_TE='1';
    const NOI_DIA='0';

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
        return 'branch';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('tax_code,address,full_name,short_hand_name', 'required', 'message' => 'Vui lòng nhập {attribute}.'),
            array('email', 'email', 'message' => "Email không đúng, vui lòng kiểm tra lại."),
            array(
                'tax_code',
                'match', 'not' => true, 'pattern' => '/[^0-9]/',
                'message' => '{attribute} không đúng quy định.',
            ),
            array(
                'tax_code_chinhanh',
                'match', 'not' => true, 'pattern' => '/[^0-9]/',
                'message' => 'Phần mở rộng của mã số thuế không đúng quy định.',
            ),
            array(
                'phone',
                'match', 'not' => true, 'pattern' => '/[^0-9_ ]/',
                'message' => '{attribute} chỉ được nhập bằng chữ số và khoảng trắng.',
            ),
            array('full_name_for_unique_validate', 'unique', 'on' => 'insert,update', 'message' => 'Đã tồn tại {attribute} này rồi. Vui lòng nhập lại.'),
            array('tax_code', 'unique', 'criteria' => array(
                    'condition' => '`tax_code_chinhanh`=:tax_code_chinhanh',
                    'params' => array(
                        ':tax_code_chinhanh' => $this->tax_code_chinhanh
                    )
                ),
                'message' => 'Đã tồn tại mã số thuế này rồi. Vui lòng nhập lại.'
            ),
            array('tax_code_chinhanh', 'unique', 'criteria' => array(
                    'condition' => '`tax_code`=:tax_code',
                    'params' => array(
                        ':tax_code' => $this->tax_code
                    )
                ),
                'message' => 'Đã tồn tại mã số thuế này rồi. Vui lòng nhập lại.'
            ),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'tax_code' => 'Mã số thuế',
            'address' => 'Địa chỉ',
            'email' => 'Email',
            'phone' => 'Số điện thoại',
            'first_name' => '',
            'last_name' => 'Tên người đại diện',
            'full_name' => 'Tên công ty',
            'short_hand_name' => 'Tên viết tắt',
            'full_name_for_unique_validate' => 'Tên công ty',
        );
    }
    /**
     * sau khi kiểm tra trong các hóa đơn bán hàng và hóa đơn nhập hàng, 
     * update kiểu công ty là khách hàng hay nhà cung ứng hay cả hai
     * @param int $branch_id
     * @return void
     */
    public static function update_type_after_update_bill_or_bill_input($branch_id) {
        if(trim($branch_id)==""||!is_numeric($branch_id)){
            return;
        }
        /**
         * công ty này có trong hóa đơn nhập không
         */
        $count_bill_input =  BillInput::model()->count("branch_id=$branch_id");
        /**
         * công ty này có trong hóa đơn xuất không
         */
        $count_bill =  Bill::model()->count("branch_id=$branch_id");
        /**
         * nếu không có trong cả hai loại hóa đơn nhập và xuất đều chưa có
         * thí update kiểu công ty đó trở về trạng thái ban đầu lúc tạo
         */
        if (($count_bill_input == '0' || $count_bill_input == FALSE) && ($count_bill == '0' || $count_bill == FALSE)) {            
            $model = Branch::model()->findByPk($branch_id);
            if ($model != NULL && $model instanceof Branch) {
                $model->type = $model->type_init;
                $model->save(FALSE);
            }
        } 
        /**
         * nếu có trong hóa đơn xuất nhưng không có trong hóa đơn nhập
         * thí update kiểu công ty đó là khách hàng
         */
        else if (($count_bill_input == '0' || $count_bill_input == FALSE) && ($count_bill != '0' && $count_bill != FALSE)) {
            $model = Branch::model()->findByPk($branch_id);
            if ($model != NULL && $model instanceof Branch) {
                $model->type = Branch::CUSTOMER;
                $model->save(FALSE);
            }
        } 
        /**
         * nếu có trong hóa đơn nhập nhưng không có trong hóa đơn xuất
         * thí update kiểu công ty đó là nhà cung ứng
         */
        else if (($count_bill == '0' || $count_bill == FALSE) && ($count_bill_input != '0' && $count_bill_input != FALSE)) {
            $model = Branch::model()->findByPk($branch_id);
            if ($model != NULL && $model instanceof Branch) {
                $model->type = Branch::SUPPLIER;
                $model->save(FALSE);
            }
        }
        /**
         * nếu có trong cả hai loại hóa đơn nhập và xuất
         * thí update kiểu công ty đó vừa là khách hàng vừa là nhà cung ứng
         */
        else{
            $model = Branch::model()->findByPk($branch_id);
            if ($model != NULL && $model instanceof Branch) {
                $model->type = Branch::BOTH_CUSTOMER_SUPPLIER;
                $model->save(FALSE);
            }
        }
    }
    /**
     * sau khi kiểm tra trong các hóa đơn nhập hàng, 
     * update kiểu công ty là khách hàng hay cả hai
     * @param int $branch_id
     * @return void
     */
    public static function update_type_after_create_bill($branch_id) {
        /**
         * công ty này có trong hóa đơn nhập không
         */
        $count =  BillInput::model()->count("branch_id=$branch_id");
        /**
         * nếu có
         * thí update kiểu công ty đó vừa là khách hàng vừa là nhà cung ứng
         */
        if ($count != '0' && $count != FALSE) {
            $model = Branch::model()->findByPk($branch_id);
            if ($model != NULL && $model instanceof Branch) {
                $model->type = Branch::BOTH_CUSTOMER_SUPPLIER;
                $model->save(FALSE);
            }
        }
        /**
         * nếu không
         * thí dù nó đang là kiểu gì, thi cũng update kiểu công ty đó là khách hàng
         */
        else{                
            $model = Branch::model()->findByPk($branch_id);                
            if ($model != NULL && $model instanceof Branch) {                    
                $model->type = Branch::CUSTOMER;
                $model->save(FALSE);

            }
        }
        
    }
    /**
     * sau khi kiểm tra trong các hóa đơn bán hàng, 
     * update kiểu công ty là nhà cung ứng hay cả hai
     * @param int $branch_id
     * @return void
     */
    public static function update_type_after_create_bill_input($branch_id) {
        if(trim($branch_id)==""||!is_numeric($branch_id)){
            return;
        }
        /**
         * công ty này có trong hóa đơn bán hàng không
         */
        $count =Bill::model()->count("branch_id=$branch_id");
        /**
         * nếu có
         * thí update kiểu công ty đó vừa là khách hàng vừa là nhà cung ứng
         */
        if ($count != '0' && $count != FALSE) {
            $model = Branch::model()->findByPk($branch_id);
            if ($model != NULL && $model instanceof Branch) {
                $model->type = Branch::BOTH_CUSTOMER_SUPPLIER;
                $model->save(FALSE);
            }
        }
        /**
         * nếu không
         * thí dù nó đang là kiểu gì, thi cũng update kiểu công ty đó là nhà cung ứng
         */
        else{                
            $model = Branch::model()->findByPk($branch_id);                
            if ($model != NULL && $model instanceof Branch) {                    
                $model->type = Branch::SUPPLIER;
                $model->save(FALSE);

            }
        }
        
    }

}
