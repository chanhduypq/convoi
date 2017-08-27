<?php

/**
 * 
 */
class User extends CActiveRecord {

    public $re_password_for_show;
    public $password_for_show;
    public $phone_email_birthday;
    public $separator=" | ";

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
        return 'user';
    }

    public function defaultScope() {
        return array(
            'order' => Yii::app()->session['user_list_sort'],
            'limit' => Yii::app()->params['number_of_items_per_page'],
//            'condition'=>Yii::app()->controller->action->id == "index"?"`id` <> ".Yii::app()->session['user_id']:'true',
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'username' => 'username',
            'password_for_show' => 'Mật khẩu',
            're_password_for_show' => ' lại mật khẩu',
            'danh_xung' => 'Danh xưng',
            'full_name' => 'Tên đầy đủ',
            'photo' => 'Ảnh đại diện',
            'email' => 'email',
            'phone' => 'Số điện thoại',
            'address' => 'Địa chỉ liên hệ',
            'role'=>'Quyền user',
        );
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('password_for_show,re_password_for_show,full_name,email,phone,address', 'required', 'message' => 'Vui lòng nhập {attribute}'),
            array('danh_xung,role', 'required', 'message' => 'Vui lòng chọn {attribute}'),
            array('email', 'email', 'message' => "Email không đúng, vui lòng kiểm tra lại."),
            array('email', 'unique', 'on' => 'insert,update', 'message' => 'Đã tồn tại {attribute} này rồi. Vui lòng nhập lại.'),
            array(
                'phone',
                'match', 'not' => true, 'pattern' => '/[^0-9_ ]/',
                'message' => '{attribute} chỉ được nhập bằng chữ số và khoảng trắng.',
            ),
            array('re_password_for_show', 'compare', 'compareAttribute' => 'password_for_show', 'on' => 'insert,update', 'message' => 'Mật khẩu không trùng nhau.'),
            array('photo', 'checkFileType'),
//            array('birthday', 'type', 'type' => 'date', 'message' => '{attribute}: không đúng định dạng ngày tháng!', 'dateFormat' => FunctionCommon::convertDateForValidation()),
        );
    }

    public function checkFileType($attribute, $params) {        
        if($this->$attribute==''){
            return ;
        }
        $temp = explode(".", $this->$attribute);
        $extension = $temp[count($temp) - 1];
        $extension = strtolower($extension);
        if (!in_array(strtolower($extension), array('jpg', 'gif', 'png', 'jpeg'))) {
            $this->addError('photo', "Vui lòng chọn đúng định dạng photo");           
        }
        if($this->getIsNewRecord()==FALSE){
            /**
             * vì tại controller có dòng code này $model->photo = $_FILES['photo']['name']; trước cả validate
             * nên cần quay về value cũ của nó (value hiện có trong db)
             * để tránh khi validate chưa thành công
             * thi photo nó mặc định được mang theo qua view
             * se bị lỗi tại 2 dòng code này
             * $url = ltrim('/upload/' . $photo, '/');
             * $size = getimagesize($url);  
             * lý do lúc đó value của biến $photo không còn là value trong db nữa
             * có nghĩa là file ảnh mang value $photo chưa có trên host
             */
            $this->$attribute=Yii::app()->db->createCommand()
                                        ->select("photo")
                                        ->from("user")
                                        ->where("id=".$this->id)
                                        ->queryScalar();
        }
        
    }

    public function login() {
        $this->username = str_replace("'", "\'", $this->username);
        $this->email = str_replace("'", "\'", $this->email);
        
        $row = Yii::app()->db->createCommand()
                ->select()
                ->from("user")
                ->where("username='" . $this->username . "' and password='" .sha1($this->password) . "'")
                ->orWhere("email='" . $this->email . "' and password='" . sha1($this->password) . "'")
                ->queryRow();

        if (is_array($row) && count($row) > 0) {
            if($row['role']!=""&&$row['role']!="[]"){
                Yii::app()->session['role']=  $row['role'];
            }
            else{
                Yii::app()->session['role']=  array();
            }
            Yii::app()->session['user_id']=  $row['id'];
            Yii::app()->session['password']=  $this->password;
            Yii::app()->session['photo']=  $row['photo'];
            Yii::app()->session['danh_xung_full_name']=  $row['danh_xung'].' '.$row['full_name'];
            Yii::app()->session['email']=$this->email;
            
            return true;
        }
        return FALSE;
    }
    
    private function delete_old_photo() {
        $model= $this->findByPk($this->id);
        if($model->photo!=$this->photo){
            @unlink(Yii::getPathOfAlias('webroot').'/upload/' . $model->photo);
        }
    }
    
    public function afterDelete() {
        parent::afterDelete();
        @unlink(Yii::getPathOfAlias('webroot').'/upload/' . $this->photo);
    }

    public function beforeSave() {

        if($this->getIsNewRecord()==FALSE){
            $this->delete_old_photo();
        }
//        $this->birthday=  FunctionCommon::convertDateForDB($this->birthday);
            
        return parent::beforeSave();
    }

    public function afterFind() {
        parent::afterFind();
        $this->re_password_for_show = $this->password_for_show;
        $temp=  explode(" ", $this->birthday);
        $temp=$temp[0];
        $DATE_FORMAT = Yii::app()->session['date_format'];
        if ($DATE_FORMAT == 'Y.m.d') {
            $this->birthday= str_replace("-", ".", $temp);
        } elseif ($DATE_FORMAT == 'Y-m-d') {
            $this->birthday= $temp;
        } elseif ($DATE_FORMAT == 'Y/m/d') {
            $this->birthday= str_replace("-", "/", $temp);
        } elseif ($DATE_FORMAT == 'Ymd') {
            $this->birthday= str_replace("-", "", $temp);            
        }         
        $this->set_phone_email_birthday();
        $this->role=Yii::app()->db->createCommand()->select("role")->from("role")->where("id=".$this->role)->queryScalar();
    }
    /**
     * @return void
     */
    protected function set_phone_email_birthday(){
        $this->phone_email_birthday="";        
        if($this->phone!=""){
            $this->phone_email_birthday.=$this->phone;
        }
        if($this->email!=""){
            if($this->phone_email_birthday!=""){
                $this->phone_email_birthday.=$this->separator.$this->email;
            }
            else{
                $this->phone_email_birthday.=$this->email;
            }
        }     
     
    }

}
