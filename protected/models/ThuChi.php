<?php

/**
 * 
 */
class ThuChi extends CActiveRecord {

    const TIEN_MAT = '1';
    const CHUYEN_KHOAN = '2';
    const OTHER = '3';
    

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
            'order' => Yii::app()->session['thuchi_list_sort'],
                ) :
                array(
            'order' => Yii::app()->session['thuchi_list_sort'],
            'limit' => Yii::app()->params['number_of_items_per_page'],
                )
        ;
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'thuchi';
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
                $payment_method_id5=Yii::app()->db->createCommand("select payment_method_id5 from bill_input where id=".$this->bill_input_id)->queryScalar();
                if($payment_method_id5==""){
                    $controller="invoiceinputfull";
                }
                else{
                    $controller="internationalinput";
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
        if($temp[0]==  date("Y")&&$temp[1]==  date("m")&&$this->is_lock==0){
            $this->is_edit=true;
        }
        else{
            $this->is_edit=FALSE;
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

    public static function delete_record($id, $month, $year) {
        ThuChi::model()->deleteByPk($id);
        $tm = $so_du_dau_ky = Yii::app()->db->createCommand()->select("tm")->from("thuchi")->where("MONTH(created_at)=$month AND YEAR(created_at)=$year AND is_init=1")->queryScalar();
        $rows = Yii::app()->db->createCommand()->select()->from("thuchi")->where("MONTH(created_at)=$month AND YEAR(created_at)=$year AND is_init=0")->order("created_at ASC")->queryAll();
        ThuChi::model()->deleteAll("MONTH(created_at)=$month AND YEAR(created_at)=$year AND is_init=0");

        for ($i = 0; $i < count($rows); $i++) {
            $model = new ThuChi();
            $model->setIsNewRecord(true);
            if ($rows[$i]['type'] == ThuChi::TIEN_MAT) {
                $tm = $tm + $rows[$i]['thu'] - $rows[$i]['chi'];
            }
            $model->thu = $rows[$i]['thu'];
            $model->chi = $rows[$i]['chi'];
            $model->content = $rows[$i]['content'];
            $model->created_at = $rows[$i]['created_at'];
            $model->id = $rows[$i]['id'];
            $model->type = $rows[$i]['type'];
            $model->tm = $tm;
            if ($rows[$i]['bill_id'] == '') {
                $model->bill_id = NULL;
            } else {
                $model->bill_id = $rows[$i]['bill_id'];
            }
            if ($rows[$i]['bill_chi_phi_id'] == '') {
                $model->bill_chi_phi_id = NULL;
            } else {
                $model->bill_chi_phi_id = $rows[$i]['bill_chi_phi_id'];
            }
            if ($rows[$i]['bill_input_id'] == '') {
                $model->bill_input_id = NULL;
            } else {
                $model->bill_input_id = $rows[$i]['bill_input_id'];
            }

            $model->is_init = 0;
            $model->save(FALSE);
        }
    }
    public static function ket_so() {
        $count=Yii::app()->db->createCommand("select count(*) from thuchi where is_lock=0 AND MONTH(created_at)=".date("m")." AND YEAR(created_at)=".date("Y"))->queryScalar();
        if($count!='0'){
            Yii::app()->db->createCommand("update thuchi set is_lock=1 where MONTH(created_at)=".date("m")." AND YEAR(created_at)=".date("Y"))->execute();
            $tm =  Yii::app()->db->createCommand()->select("tm")->from("thuchi")->where("MONTH(created_at)=".date("m")." AND YEAR(created_at)=".date("Y"))->order("created_at DESC")->queryScalar();
            $user = User::model()->findByPk(Yii::app()->session['user_id']);
            $model1=new ThuChi();
            $model1->setIsNewRecord(true);
            $model1->content=$user->danh_xung . " " . $user->full_name." kết sổ";
            $model1->created_at=date("Y-m-d H:i:s");
            $model1->is_init = 1;
            $model1->type=1;
            $model1->tm=$tm;
            $model1->thu=$model1->chi=$model1->chuyen_khoan=$model1->khac=0;
            $model1->is_lock=1;
            $model1->save(FALSE);
        }
        
    }

    /**
     * sau khi insert hoặc update một record bất kỳ của tháng hiện tại
     * thi update tm cho các record khác trong tháng
     * @param string|int $month
     * @param string|int $year
     * @return void
     */
    public static function update_records($month, $year) {
        if($month!=  date("m")||$year!=  date("Y")){
            return true;
        }
          
        $tm = $so_du_dau_ky = Yii::app()->db->createCommand()->select("tm")->from("thuchi")->where("MONTH(created_at)=$month AND YEAR(created_at)=$year AND is_init=1")->order("created_at DESC")->queryScalar();
        $rows = Yii::app()->db->createCommand()->select()->from("thuchi")->where("MONTH(created_at)=$month AND YEAR(created_at)=$year AND is_init=0 AND is_lock=0")->order("created_at ASC")->queryAll();
        ThuChi::model()->deleteAll("MONTH(created_at)=$month AND YEAR(created_at)=$year AND is_init=0 AND is_lock=0");

        $insert="insert into thuchi (id,created_at,content,thu,chi,tm,type,bill_id,bill_input_id,bill_chi_phi_id,is_init,kho_hang,chuyen_khoan,khac,is_lock,sxdv_id,kxhd_id,lai_suat_id,chi_phi_khd_id,ung_tien_id,payment_method_id3,payment_method_id4,payment_method_id5,socai_id)";
        for ($i = 0; $i < count($rows); $i++) {
            $value="(";
            $value.=$rows[$i]['id'].",";
            $value.="'".$rows[$i]['created_at']."',";
            $value.="'".str_replace("'", "\'", $rows[$i]['content']) ."',";
            $value.=$rows[$i]['thu'].",";
            $value.=$rows[$i]['chi'].",";
            if ($rows[$i]['type'] == ThuChi::TIEN_MAT) {
                $tm = $tm + $rows[$i]['thu'] - $rows[$i]['chi'];
            }
            $value.=$tm.",";
            $value.=$rows[$i]['type'].",";
            if ($rows[$i]['bill_id'] == '') {
                $value.= 'NULL,';
            } else {
                $value.= $rows[$i]['bill_id'].",";
            }
            if ($rows[$i]['bill_input_id'] == '') {
                $value.= 'NULL,';
            } else {
                $value.= $rows[$i]['bill_input_id'].",";
            }
            if ($rows[$i]['bill_chi_phi_id'] == '') {
                $value.= 'NULL,';
            } else {
                $value.= $rows[$i]['bill_chi_phi_id'].",";
            }
            $value.="0,";
            $value.="'".str_replace("'", "\'", $rows[$i]['kho_hang']) ."',";
            if($rows[$i]['type']==ThuChi::CHUYEN_KHOAN){
                $value.="1,";
                $value.="0,";
            }
            else if($rows[$i]['type']==ThuChi::OTHER){
                $value.="0,";
                $value.="1,";
            }
            else{
                $value.="0,";
                $value.="0,";
            }
            $value.=$rows[$i]['is_lock'].",";
            if ($rows[$i]['sxdv_id'] == '') {
                $value.= 'NULL,';
            } else {
                $value.= $rows[$i]['sxdv_id'].",";
            }
            if ($rows[$i]['kxhd_id'] == '') {
                $value.= 'NULL,';
            } else {
                $value.= $rows[$i]['kxhd_id'].",";
            }
            if ($rows[$i]['lai_suat_id'] == '') {
                $value.= 'NULL,';
            } else {
                $value.= $rows[$i]['lai_suat_id'].",";
            }
            if ($rows[$i]['chi_phi_khd_id'] == '') {
                $value.= 'NULL,';
            } else {
                $value.= $rows[$i]['chi_phi_khd_id'].",";
            }
            if ($rows[$i]['ung_tien_id'] == '') {
                $value.= 'NULL,';
            } else {
                $value.= $rows[$i]['ung_tien_id'].",";
            }
            if ($rows[$i]['payment_method_id3'] == '') {
                $value.= 'NULL,';
            } else {
                $value.= $rows[$i]['payment_method_id3'].",";
            }
            if ($rows[$i]['payment_method_id4'] == '') {
                $value.= 'NULL,';
            } else {
                $value.= $rows[$i]['payment_method_id4'].",";
            }
            if ($rows[$i]['payment_method_id5'] == '') {
                $value.= 'NULL,';
            } else {
                $value.= $rows[$i]['payment_method_id5'].",";
            }
            if ($rows[$i]['socai_id'] == '') {
                $value.= 'NULL';
            } else {
                $value.= $rows[$i]['socai_id'];
            }
            $value.=")";
            $value_array[]=$value;
        }
        
        self::insert_or_update_init_next_month($month, $year, $tm);
        
        if(isset($value_array)&&count($value_array)>0){
            $insert.=" values ".implode(",", $value_array);
            $count=Yii::app()->db->createCommand($insert)->execute();
            if($count==count($rows)){
                return true;
            }
        }
        
        return FALSE;
        
        
    }
    /**
     * mỗi lần insert/update thông tin của tháng hiện tại
     * thi insert 1 record mới cho tháng tiếp theo
     * record mới này chính là giá trị ban đầu của tháng tiếp theo
     * nếu record này đã tồn tại thi update
     * @param string|int $month
     * @param string|int $year
     * @param string|int $tm
     * @return void
     */
    public static function insert_or_update_init_next_month(){
        if(date("m")=='12'){
            $month='1';
            $year=date('Y');
            $year++;
        }
        else{
            $month=date("m");
            $month++;
            $year=date('Y');
        }
        
        $sum=Yii::app()->db->createCommand()->select("sum(thu)-sum(chi)")->from("thuchi")->where("MONTH(created_at)=".date('m')." AND YEAR(created_at)=".date('Y')."")->queryScalar();
        if($sum==FALSE||$sum==""){
            $sum=0;
        }
        
        $tm_init=Yii::app()->db->createCommand()->select("tm")->from("thuchi")->where("MONTH(created_at)=".date('m')." AND YEAR(created_at)=".date('Y')." AND is_init=1")->queryScalar();
        if($tm_init==FALSE||$tm_init==""){
            $tm_init=0;
        }
        
        $row = Yii::app()->db->createCommand()->select()->from("thuchi")->where("MONTH(created_at)=$month AND YEAR(created_at)=$year AND is_init=1")->queryRow();
        if(is_array($row)&&count($row)>0){
            $model1=  ThuChi::model()->findByPk($row['id']);                
        }
        else{
            $model1=new ThuChi();
            $model1->setIsNewRecord(true);
        }

        $model1->content="Giá trị ban đầu";
        $model1->created_at=$year."-".$month."-01 00:00:00";
        $model1->is_init = 1;
        $model1->type=1;
        $model1->tm=$sum+$tm_init;
        $model1->thu=$model1->chi=$model1->chuyen_khoan=$model1->khac=0;
        $model1->is_lock=0;
        if($model1->save(FALSE)==true){
            return true;
        }
        
        return false;
        
        
    }
    

}
