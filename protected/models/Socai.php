<?php

/**
 * 
 */
class Socai extends CActiveRecord {

    public $payment_method_to_khai;
    public $success_before_save=false;
    public $success_after_save=false;

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
        return 'socai';
    }
    

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {

        return array(
        );
    }

    /**
     * nếu đã thanh toán đủ tiền, thi tat ca các dòng ứng với giao dịch đó se phai có icon complete
     * function này làm chuyện này
     */
    protected function update_complete($type_id, $id) {
        $rows = Yii::app()->db->createCommand()
                ->select()
                ->from("socai")
                ->where("$type_id=" . $id . " and (is_tamung is null or is_tamung=0) and id<>" . $this->id)
                ->queryAll();
        if (is_array($rows) && count($rows) > 0) {
            foreach ($rows as $r) {
                $r['trang_thai'] = str_replace("47px", "26px", $r['trang_thai']);
                if(strpos($r['trang_thai'], "socai/complete.png")==FALSE){
                    $r['trang_thai'].='<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/complete.png"/>';
                }
                
                Yii::app()->db->createCommand("update socai set trang_thai='" . $r['trang_thai'] . "' where id=" . $r['id'])->execute();
                
            }
            
        }
        
    }

    /**
     * nếu đã thanh toán đủ tiền, thi tat ca các dòng ứng với giao dịch tờ khai + loại tiền (chi phí ngân hàng VND, giá trị hàng hóa VND, tiền thuế VND) đó se phai có icon complete
     * function này làm chuyện này
     */
    protected function update_complete_for_tokhai($id, $payment_method_id) {
        $rows = Yii::app()->db->createCommand()
                ->select()
                ->from("socai")
                ->where("bill_input_id=" . $id . " and (is_tamung is null or is_tamung=0) and $payment_method_id is not null and id<>" . $this->id)
                ->queryAll();
        if (is_array($rows) && count($rows) > 0) {
            foreach ($rows as $r) {
                $r['trang_thai'] = str_replace("47px", "26px", $r['trang_thai']);
                if(strpos($r['trang_thai'], "socai/complete.png")==FALSE){
                    $r['trang_thai'].='<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/complete.png"/>';
                }
                Yii::app()->db->createCommand("update socai set trang_thai='" . $r['trang_thai'] . "' where id=" . $r['id'])->execute();
            }
        }
    }
    protected function delete_socai($type_id, $id){
        Yii::app()->db->createCommand("delete from socai where $type_id=$id and thu=0 and chi=0")->execute();
    }
    protected function delete_socai_for_to_khai($id,$payment_method_id){
        Yii::app()->db->createCommand("delete from socai where bill_input_id=$id and thu=0 and chi=0 and $payment_method_id is not null")->execute();
    }

    /**
     * mỗi lần nhập liệu số tiền trong trang sổ cái
     * nếu số tiền không đủ thi se hiển thị thêm một dòng cuối cùng có số tiền còn lại ứng với giao dịch đó
     * ví dụ hóa đơn thương mại có tổng tiền 1.000.000, nhưng thanh toán bên sổ cái chỉ có 600.000
     * thi hệ thống phai sinh ra một dòng ứng có số tiền còn lại là 400.000 ứng với số hóa đơn đó
     * function này làm chuyện này
     */
    protected function insert_next_record($type_id, $id, $stt,$so_tien_con_lai) {
        $sum_count = Yii::app()->db->createCommand()
                ->select("count(*) as count")
                ->from("socai")
                ->where("$type_id=" . $id . " and thu=0 and chi=0 and id<>" . $this->id)
                ->queryScalar();
        if ($sum_count != FALSE && $sum_count != 0) {
            return true;
        }
        $trang_thai = '<div style="position: absolute;margin-top: -40px;margin-left: 47px;width: 20px;height: 20px;">' . $stt . '</div>' .
                '<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/not_complete.png"/>';
        $count=Yii::app()->db->createCommand("insert into socai ("
                        . "thu,"
                        . "chi,"
                        . "created_at,"
                        . "$type_id,"
                        . "giao_dich,"
                        . "thanh_toan,"
                        . "tham_chieu,"
                        . "content,"
                        ."tm,"
                        . "trang_thai"
                        . ") "
                        . "values ("
                        . "0,"
                        . "0,"
                        . "'" . FunctionCommon::get_last_time_of_current_month() . "',"
                        . $id . ","
                        . "'" . $this->giao_dich . "',"
                        . PaymentMethod::CHUA_THANH_TOAN . ","
                        . "'" . $this->tham_chieu . "',"
                        . "'" . $this->content . "',"
                        .$so_tien_con_lai.","
                        . "'" . $trang_thai . "'"
                        . ")")
                ->execute();
        if($count==1){
            return true;
        }
        return false;
    }

    /**
     * mỗi lần nhập liệu số tiền trong trang sổ cái
     * nếu số tiền không đủ thi se hiển thị thêm một dòng cuối cùng có số tiền còn lại ứng với giao dịch tờ khai + loại tiền (chi phí ngân hàng VND, giá trị hàng hóa VND, tiền thuế VND)
     * ví dụ tờ khai có chi phí ngân hàng VND 1.000.000, nhưng thanh toán bên sổ cái chỉ có 600.000
     * thi hệ thống phai sinh ra một dòng ứng có số tiền còn lại là 400.000 ứng với số tờ khai và loại tiền đó
     * function này làm chuyện này
     */
    protected function insert_next_record_for_tokhai($id, $stt, $payment_method_id,$so_tien_con_lai) {
        $sum_count = Yii::app()->db->createCommand()
                ->select("count(*) as count")
                ->from("socai")
                ->where("bill_input_id=" . $id . " and thu=0 and chi=0 and $payment_method_id is not null and id<>" . $this->id)
                ->queryScalar();
        if ($sum_count != FALSE && $sum_count != 0) {
            return true;
        }
        $trang_thai = '<div style="position: absolute;margin-top: -40px;margin-left: 47px;width: 20px;height: 20px;">' . $stt . '</div>' .
                '<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/not_complete.png"/>';
        $count=Yii::app()->db->createCommand("insert into socai ("
                        . "thu,"
                        . "chi,"
                        . "created_at,"
                        . "bill_input_id,"
                        . "giao_dich,"
                        . "thanh_toan,$payment_method_id,"
                        . "tham_chieu,"
                        . "content,"
                        ."tm,"
                        . "trang_thai"
                        . ") "
                        . "values ("
                        . "0,"
                        . "0,"
                        . "'" . date("Y-m-d H:i:s") . "',"
                        . $id . ","
                        . "'" . $this->giao_dich . "',"
                        . PaymentMethod::CHUA_THANH_TOAN . "," . PaymentMethod::CHUA_THANH_TOAN . ","
                        . "'" . $this->tham_chieu . "',"
                        . "'" . $this->content . "',"
                        .$so_tien_con_lai.","
                        . "'" . $trang_thai . "'"
                        . ")")
                ->execute();
        if($count==1){
            return true;
        }
        return false;
    }
    
    protected function process_hoa_don_thuong_mai() {
        if($this->tham_chieu==''||$this->tham_chieu==NULL){
            return true;
        }
        $row = Yii::app()->db->createCommand()
                ->select("*")
                ->from("bill")
                ->where("bill_number=" . intval($this->tham_chieu))
                ->queryRow();
        if (!is_array($row) || count($row) == 0) {
            return FALSE;
        }
        $this->bill_id = $row['id'];
        $sum_count = Yii::app()->db->createCommand()
                ->select("sum(thu) as sum,count(*) as count")
                ->from("socai")
                ->where("bill_id=" . $row['id'] . " and thu<>0 and id<>" . $this->id)
                ->queryRow();
        $sum = $sum_count['sum'];
        $count = $sum_count['count'];
        if ($count == '0') {
            if ($this->thu == $row['sum_and_sumtax']) {
                Yii::app()->db->createCommand("update bill set is_complete=1 where id=" . $row['id'])->execute();
                $this->trang_thai = '<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/complete.png"/>';
                
            } else {
                Yii::app()->db->createCommand("update bill set is_paying=1,is_complete=0 where id=" . $row['id'])->execute();
                $this->trang_thai = '<div style="position: absolute;margin-top: -40px;margin-left: 47px;width: 20px;height: 20px;">1</div>' .
                        '<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/not_complete.png"/>';
                //new
                $this->insert_next_record("bill_id", $row['id'], 2,$row['sum_and_sumtax']-$this->thu);
            }
        } else {
            if ($sum == '') {
                $sum = 0;
            }
            if ($this->thu + $sum == $row['sum_and_sumtax']) {
                Yii::app()->db->createCommand("update bill set is_complete=1 where id=" . $row['id'])->execute();

                $this->trang_thai = str_replace("47px", "26px", $this->trang_thai);
                if(strpos($this->trang_thai, "socai/complete.png")==FALSE){
                    $this->trang_thai .='<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/complete.png"/>';          
                }

                $this->update_complete("bill_id", $row['id']);
            } else {
                Yii::app()->db->createCommand("update bill set is_paying=1,is_complete=0 where id=" . $row['id'])->execute();
                $this->insert_next_record("bill_id", $row['id'], $count + 2,$row['sum_and_sumtax']-$this->thu-$sum);
            }
        }

        Yii::app()->db->createCommand("update bill set payment_method_id=".$this->thanh_toan." where id=".$row['id'])->execute();
        
        return true;
    }

    protected function process_sxdv() {
        if($this->tham_chieu==''||$this->tham_chieu==NULL){
            return true;
        }
        $row = Yii::app()->db->createCommand()
                ->select("*")
                ->from("sxdv")
                ->where("bill_number=" . intval($this->tham_chieu))
                ->queryRow();
        if (!is_array($row) || count($row) == 0) {
            return FALSE;
        }
        $this->sxdv_id = $row['id'];

        $sum_count = Yii::app()->db->createCommand()
                ->select("sum(thu) as sum,count(*) as count")
                ->from("socai")
                ->where("sxdv_id=" . $row['id'] . " and thu<>0 and id<>" . $this->id)
                ->queryRow();
        $sum = $sum_count['sum'];
        $count = $sum_count['count'];
        if ($count == '0') {
            if ($this->thu == $row['sum_and_sumtax']) {
                Yii::app()->db->createCommand("update sxdv set is_complete=1 where id=" . $row['id'])->execute();
                $this->trang_thai = '<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/complete.png"/>';
                
            } else {
                Yii::app()->db->createCommand("update sxdv set is_paying=1,is_complete=0 where id=" . $row['id'])->execute();
                $this->trang_thai = '<div style="position: absolute;margin-top: -40px;margin-left: 47px;width: 20px;height: 20px;">1</div>' .
                        '<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/not_complete.png"/>';
                //new
                $this->insert_next_record("sxdv_id", $row['id'], 2,$row['sum_and_sumtax']-$this->thu);
            }
        } else {
            if ($sum == '') {
                $sum = 0;
            }
            if ($this->thu + $sum == $row['sum_and_sumtax']) {
                Yii::app()->db->createCommand("update sxdv set is_complete=1 where id=" . $row['id'])->execute();
                $this->trang_thai = str_replace("47px", "26px", $this->trang_thai);
                if(strpos($this->trang_thai, "socai/complete.png")==FALSE){
                    $this->trang_thai .='<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/complete.png"/>';          
                }
                $this->update_complete("sxdv_id", $row['id']);
            } else {
                Yii::app()->db->createCommand("update sxdv set is_paying=1,is_complete=0 where id=" . $row['id'])->execute();
                $this->insert_next_record("sxdv_id", $row['id'], $count + 2,$row['sum_and_sumtax']-$this->thu-$sum);
            }
        }
        Yii::app()->db->createCommand("update sxdv set payment_method_id=".$this->thanh_toan." where id=".$row['id'])->execute();
        
        return true;
    }

    protected function process_kxhd() {
        if($this->tham_chieu==''||$this->tham_chieu==NULL){
            return true;
        }
        $row = Yii::app()->db->createCommand()
                ->select("*")
                ->from("kxhd")
                ->where("stt=" . $this->tham_chieu)
                ->queryRow();
        if (!is_array($row) || count($row) == 0) {
            return FALSE;
        }
        $this->kxhd_id = $row['id'];

        $sum_count = Yii::app()->db->createCommand()
                ->select("sum(thu) as sum,count(*) as count")
                ->from("socai")
                ->where("kxhd_id=" . $row['id'] . " and thu<>0 and id<>" . $this->id)
                ->queryRow();
        $sum = $sum_count['sum'];
        $count = $sum_count['count'];
        if ($count == '0') {
            if ($this->thu == $row['sum_and_sumtax']) {
                Yii::app()->db->createCommand("update kxhd set is_complete=1 where id=" . $row['id'])->execute();
                $this->trang_thai = '<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/complete.png"/>';
                
            } else {
                Yii::app()->db->createCommand("update kxhd set is_paying=1,is_complete=0 where id=" . $row['id'])->execute();
                $this->trang_thai = '<div style="position: absolute;margin-top: -40px;margin-left: 47px;width: 20px;height: 20px;">1</div>' .
                        '<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/not_complete.png"/>';
                //new
                $this->insert_next_record("kxhd_id", $row['id'], 2,$row['sum_and_sumtax']-$this->thu);
            }
        } else {
            if ($sum == '') {
                $sum = 0;
            }
            if ($this->thu + $sum == $row['sum_and_sumtax']) {
                Yii::app()->db->createCommand("update kxhd set is_complete=1 where id=" . $row['id'])->execute();
                $this->trang_thai = str_replace("47px", "26px", $this->trang_thai);
                if(strpos($this->trang_thai, "socai/complete.png")==FALSE){
                    $this->trang_thai .='<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/complete.png"/>';          
                }                        
                $this->update_complete("kxhd_id", $row['id']);
            } else {
                Yii::app()->db->createCommand("update kxhd set is_paying=1,is_complete=0 where id=" . $row['id'])->execute();
                $this->insert_next_record("kxhd_id", $row['id'], $count + 2,$row['sum_and_sumtax']-$this->thu-$sum);
            }
        }
        Yii::app()->db->createCommand("update kxhd set payment_method_id=".$this->thanh_toan." where id=".$row['id'])->execute();
        
        return true;
    }

    protected function process_laisuat() {
        if($this->tham_chieu==''||$this->tham_chieu==NULL){
            return true;
        }
        $row = Yii::app()->db->createCommand()
                ->select("*")
                ->from("lai_suat")
                ->where("stt=" . $this->tham_chieu)
                ->queryRow();
        if (!is_array($row) || count($row) == 0) {
            return FALSE;
        }
        $this->lai_suat_id = $row['id'];

        $sum_count = Yii::app()->db->createCommand()
                ->select("sum(thu) as sum,count(*) as count")
                ->from("socai")
                ->where("lai_suat_id=" . $row['id'] . " and thu<>0 and id<>" . $this->id)
                ->queryRow();
        $sum = $sum_count['sum'];
        $count = $sum_count['count'];
        if ($count == '0') {
            if ($this->thu == $row['sum_and_sumtax']) {
                Yii::app()->db->createCommand("update lai_suat set is_complete=1 where id=" . $row['id'])->execute();
                $this->trang_thai = '<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/complete.png"/>';
                
            } else {
                Yii::app()->db->createCommand("update lai_suat set is_paying=1,is_complete=0 where id=" . $row['id'])->execute();
                $this->trang_thai = '<div style="position: absolute;margin-top: -40px;margin-left: 47px;width: 20px;height: 20px;">1</div>' .
                        '<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/not_complete.png"/>';

                $this->insert_next_record("lai_suat_id", $row['id'], 2,$row['sum_and_sumtax']-$this->thu);
            }
        } else {
            if ($sum == '') {
                $sum = 0;
            }
            if ($this->thu + $sum == $row['sum_and_sumtax']) {
                Yii::app()->db->createCommand("update lai_suat set is_complete=1 where id=" . $row['id'])->execute();
                $this->trang_thai = str_replace("47px", "26px", $this->trang_thai);
                if(strpos($this->trang_thai, "socai/complete.png")==FALSE){
                    $this->trang_thai .='<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/complete.png"/>';          
                }
                $this->update_complete("lai_suat_id", $row['id']);
            } else {
                Yii::app()->db->createCommand("update lai_suat set is_paying=1,is_complete=0 where id=" . $row['id'])->execute();
                $this->insert_next_record("lai_suat_id", $row['id'], $count + 2,$row['sum_and_sumtax']-$this->thu-$sum);
            }
        }
        Yii::app()->db->createCommand("update lai_suat set payment_method_id=".$this->thanh_toan." where id=".$row['id'])->execute();
        
        return true;
    }

    protected function process_tokhai() {
        if($this->tham_chieu==''||$this->tham_chieu==NULL){
            return true;
        }
        $row = Yii::app()->db->createCommand()
                ->select("*")
                ->from("bill_input")
                ->where("bill_number='" . $this->tham_chieu . '\' and is_international=1')
                ->queryRow();
        if (!is_array($row) || count($row) == 0) {
            return FALSE;
        }
        /**
         * 
         */
        $sum_count = Yii::app()->db->createCommand()
                ->select("sum(chi) as sum")
                ->from("socai")
                ->where("bill_input_id=" . $row['id'] . " and chi<>0 and id<>" . $this->id)
                ->queryRow();
        $sum = $sum_count['sum'];
        if ($sum == '') {
            $sum = 0;
        }
        if ($this->chi + $sum == $row['gia_tri_hang_hoa_vnd'] + $row['chi_phi_ngan_hang_vnd'] + $row['tien_thue_vnd']) {
            Yii::app()->db->createCommand("update bill_input set is_complete=1 where id=" . $row['id'])->execute();
        } else {
            Yii::app()->db->createCommand("update bill_input set is_paying=1,is_complete=0 where id=" . $row['id'])->execute();
        }
        /**
         * 
         */
        $this->bill_input_id = $row['id'];
        $model = BillInput::model()->findByPk($row['id']);
        $tien = 0;
        $and_where = '';
        if ($this->payment_method_to_khai == 'Giá trị hàng hóa (VND)') {
            $tien = $row['gia_tri_hang_hoa_vnd'];
            $this->payment_method_id3 = $this->thanh_toan;
            $model->payment_method_id3 = $this->thanh_toan;
            $this->giao_dich.="\nGiá trị hàng hóa (VND)";
            $and_where = " and payment_method_id3 is not null";
            $payment_method_id = "payment_method_id3";
        } else if ($this->payment_method_to_khai == 'Chi phí ngân hàng (VND)') {
            $tien = $row['chi_phi_ngan_hang_vnd'];
            $this->payment_method_id4 = $this->thanh_toan;
            $model->payment_method_id4 = $this->thanh_toan;
            $this->giao_dich.="\nChi phí ngân hàng (VND)";
            $and_where = " and payment_method_id4 is not null";
            $payment_method_id = "payment_method_id4";
        } else if ($this->payment_method_to_khai == 'Tiền thuế (VND)') {
            $tien = $row['tien_thue_vnd'];
            $this->payment_method_id5 = $this->thanh_toan;
            $model->payment_method_id5 = $this->thanh_toan;
            $this->giao_dich.="\nTiền thuế (VND)";
            $and_where = " and payment_method_id5 is not null";
            $payment_method_id = "payment_method_id5";
        }

        $sum_count = Yii::app()->db->createCommand()
                ->select("sum(chi) as sum,count(*) as count")
                ->from("socai")
                ->where("bill_input_id=" . $row['id'] . " and chi<>0 and id<>" . $this->id . $and_where)
                ->queryRow();
        $sum = $sum_count['sum'];
        $count = $sum_count['count'];
        if ($count == '0') {
            if ($this->chi == $tien) {
                $this->trang_thai = '<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/complete.png"/>';
                
            } else {
                $this->trang_thai = '<div style="position: absolute;margin-top: -40px;margin-left: 47px;width: 20px;height: 20px;">1</div>' .
                        '<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/not_complete.png"/>';

                $this->insert_next_record_for_tokhai($row['id'], 2, $payment_method_id,$tien-$this->chi);
            }
        } else {
            if ($sum == '') {
                $sum = 0;
            }
            if ($this->chi + $sum == $tien) {
                $this->trang_thai = str_replace("47px", "26px", $this->trang_thai);
                if(strpos($this->trang_thai, "socai/complete.png")==FALSE){
                    $this->trang_thai .='<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/complete.png"/>';          
                }
                $this->update_complete_for_tokhai($row['id'], $payment_method_id);
            } else {
                $this->insert_next_record_for_tokhai($row['id'], $count + 2, $payment_method_id,$tien-$this->chi-$sum);
            }
        }
        $model->thu_chi = $this->chi;

        if ($this->payment_method_to_khai == 'Giá trị hàng hóa (VND)') {
            Yii::app()->db->createCommand("update bill_input set payment_method_id=".$this->thanh_toan.",payment_method_id3=".$this->thanh_toan." where id=".$row['id'])->execute();
        } else if ($this->payment_method_to_khai == 'Chi phí ngân hàng (VND)') {
            Yii::app()->db->createCommand("update bill_input set payment_method_id=".$this->thanh_toan.",payment_method_id4=".$this->thanh_toan." where id=".$row['id'])->execute();
        } else if ($this->payment_method_to_khai == 'Tiền thuế (VND)') {
            Yii::app()->db->createCommand("update bill_input set payment_method_id=".$this->thanh_toan.",payment_method_id5=".$this->thanh_toan." where id=".$row['id'])->execute();
        }
        
        return true;
    }

    protected function process_nhap_kho_kinh_doanh() {
        if($this->tham_chieu==''||$this->tham_chieu==NULL){
            return true;
        }
        $row = Yii::app()->db->createCommand()
                ->select("*")
                ->from("bill_input")
                ->where("id=".$this->bill_input_id)
                ->queryRow();
        if (!is_array($row) || count($row) == 0) {
            return FALSE;
        }
        $this->bill_input_id = $row['id'];

        $sum_count = Yii::app()->db->createCommand()
                ->select("sum(chi) as sum,count(*) as count")
                ->from("socai")
                ->where("bill_input_id=" . $row['id'] . " and chi<>0 and id<>" . $this->id)
                ->queryRow();
        $sum = $sum_count['sum'];
        $count = $sum_count['count'];
        if ($count == '0') {
            if ($this->chi == $row['sum_and_sumtax']) {
                Yii::app()->db->createCommand("update bill_input set is_complete=1 where id=" . $row['id'])->execute();
                $this->trang_thai = '<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/complete.png"/>';
                
            } else {
                Yii::app()->db->createCommand("update bill_input set is_paying=1,is_complete=0 where id=" . $row['id'])->execute();
                $this->trang_thai = '<div style="position: absolute;margin-top: -40px;margin-left: 47px;width: 20px;height: 20px;">1</div>' .
                        '<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/not_complete.png"/>';
                $this->insert_next_record("bill_input_id", $row['id'], 2,$row['sum_and_sumtax']-$this->chi);
            }
        } else {
            if ($sum == '') {
                $sum = 0;
            }
            if ($this->chi + $sum == $row['sum_and_sumtax']) {
                Yii::app()->db->createCommand("update bill_input set is_complete=1 where id=" . $row['id'])->execute();
                $this->trang_thai = str_replace("47px", "26px", $this->trang_thai);
                if(strpos($this->trang_thai, "socai/complete.png")==FALSE){
                    $this->trang_thai .='<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/complete.png"/>';          
                }
                $this->update_complete("bill_input_id", $row['id']);
            } else {
                Yii::app()->db->createCommand("update bill_input set is_paying=1,is_complete=0 where id=" . $row['id'])->execute();
                $this->insert_next_record("bill_input_id", $row['id'], $count + 2,$row['sum_and_sumtax']-$this->chi-$sum);
            }
        }
        Yii::app()->db->createCommand("update bill_input set payment_method_id=".$this->thanh_toan." where id=".$row['id'])->execute();
        
        return true;
    }

    protected function process_chi_phi_co_hoa_don() {
        if($this->tham_chieu==''||$this->tham_chieu==NULL){
            return true;
        }
        $row = Yii::app()->db->createCommand()
                ->select("*")
                ->from("bill_chi_phi")
                ->where("bill_number='" . $this->tham_chieu . "'")
                ->queryRow();
        if (!is_array($row) || count($row) == 0) {
            return FALSE;
        }
        $this->bill_chi_phi_id = $row['id'];

        $sum_count = Yii::app()->db->createCommand()
                ->select("sum(chi) as sum,count(*) as count")
                ->from("socai")
                ->where("bill_chi_phi_id=" . $row['id'] . " and chi<>0 and id<>" . $this->id)
                ->queryRow();
        $sum = $sum_count['sum'];
        $count = $sum_count['count'];
        if ($count == '0') {
            if ($this->chi == $row['sum_and_sumtax']) {
                Yii::app()->db->createCommand("update bill_chi_phi set is_complete=1 where id=" . $row['id'])->execute();
                $this->trang_thai = '<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/complete.png"/>';
                
            } else {
                Yii::app()->db->createCommand("update bill_chi_phi set is_paying=1,is_complete=0 where id=" . $row['id'])->execute();
                $this->trang_thai = '<div style="position: absolute;margin-top: -40px;margin-left: 47px;width: 20px;height: 20px;">1</div>' .
                        '<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/not_complete.png"/>';
                $this->insert_next_record("bill_chi_phi_id", $row['id'], 2,$row['sum_and_sumtax']-$this->chi);
            }
        } else {
            if ($sum == '') {
                $sum = 0;
            }
            if ($this->chi + $sum == $row['sum_and_sumtax']) {
                Yii::app()->db->createCommand("update bill_chi_phi set is_complete=1 where id=" . $row['id'])->execute();
                $this->trang_thai = str_replace("47px", "26px", $this->trang_thai);
                if(strpos($this->trang_thai, "socai/complete.png")==FALSE){
                    $this->trang_thai .='<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/complete.png"/>';          
                }
                $this->update_complete("bill_chi_phi_id", $row['id']);
            } else {
                Yii::app()->db->createCommand("update bill_chi_phi set is_paying=1,is_complete=0 where id=" . $row['id'])->execute();
                $this->insert_next_record("bill_chi_phi_id", $row['id'], $count + 2,$row['sum_and_sumtax']-$this->chi-$sum);
            }
        }
        Yii::app()->db->createCommand("update bill_chi_phi set payment_method_id=".$this->thanh_toan." where id=".$row['id'])->execute();
        
        return true;
    }

    protected function process_chi_phi_khong_hoa_don() {
        if($this->tham_chieu==''||$this->tham_chieu==NULL){
            return true;
        }
        $row = Yii::app()->db->createCommand()
                ->select("*")
                ->from("chi_phi_khd")
                ->where("stt=" . $this->tham_chieu)
                ->queryRow();
        if (!is_array($row) || count($row) == 0) {
            return FALSE;
        }
        $this->chi_phi_khd_id = $row['id'];

        $sum_count = Yii::app()->db->createCommand()
                ->select("sum(chi) as sum,count(*) as count")
                ->from("socai")
                ->where("chi_phi_khd_id=" . $row['id'] . " and chi<>0 and id<>" . $this->id)
                ->queryRow();
        $sum = $sum_count['sum'];
        $count = $sum_count['count'];
        if ($count == '0') {
            if ($this->chi == $row['sum_and_sumtax']) {
                Yii::app()->db->createCommand("update chi_phi_khd set is_complete=1 where id=" . $row['id'])->execute();
                $this->trang_thai = '<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/complete.png"/>';
                
            } else {
                Yii::app()->db->createCommand("update chi_phi_khd set is_paying=1,is_complete=0 where id=" . $row['id'])->execute();
                $this->trang_thai = '<div style="position: absolute;margin-top: -40px;margin-left: 47px;width: 20px;height: 20px;">1</div>' .
                        '<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/not_complete.png"/>';
                $this->insert_next_record("chi_phi_khd_id", $row['id'], 2,$row['sum_and_sumtax']-$this->chi);
            }
        } else {
            if ($sum == '') {
                $sum = 0;
            }
            if ($this->chi + $sum == $row['sum_and_sumtax']) {
                Yii::app()->db->createCommand("update chi_phi_khd set is_complete=1 where id=" . $row['id'])->execute();
                $this->trang_thai = str_replace("47px", "26px", $this->trang_thai);
                if(strpos($this->trang_thai, "socai/complete.png")==FALSE){
                    $this->trang_thai .='<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/complete.png"/>';          
                }                        
                $this->update_complete("chi_phi_khd_id", $row['id']);
            } else {
                Yii::app()->db->createCommand("update chi_phi_khd set is_paying=1,is_complete=0 where id=" . $row['id'])->execute();
                $this->insert_next_record("chi_phi_khd_id", $row['id'], $count + 2,$row['sum_and_sumtax']-$this->chi-$sum);
            }
        }
        Yii::app()->db->createCommand("update chi_phi_khd set payment_method_id=".$this->thanh_toan." where id=".$row['id'])->execute();
        
        return true;
    }

    protected function process_hoa_don_thuong_mai1() {
        if($this->tham_chieu==''||$this->tham_chieu==NULL){
            return true;
        }
        $row = Yii::app()->db->createCommand()
                ->select("*")
                ->from("bill")
                ->where("bill_number=" . intval($this->tham_chieu))
                ->queryRow();
        if (!is_array($row) || count($row) == 0) {
            return FALSE;
        }
        
        $sum_count = Yii::app()->db->createCommand()
                ->select("sum(thu) as sum,count(*) as count")
                ->from("socai")
                ->where("bill_id=" . $row['id'] . " and thu<>0 and id<>" . $this->id)
                ->queryRow();
        $sum = $sum_count['sum'];
        $count = $sum_count['count'];
        if ($count == '0') {
            if ($this->thu == $row['sum_and_sumtax']) {
                
                $this->delete_socai("bill_id", $row['id']);
            } 
            else if($this->thu  < $row['sum_and_sumtax']){
                $left=$row['sum_and_sumtax']-$this->thu;
                Yii::app()->db->createCommand("update socai set tm=$left where chi=0 and thu=0 and bill_id=".$row['id'])->execute();
                /**
                 * khi đã thanh toán đủ số tiền thi các icon đã là complete
                 * sau khi update số tiền, trở thành chưa thanh toán đủ
                 * thi phai làm mất các icon complete
                 */
                $rows = Yii::app()->db->createCommand()
                    ->select("*")
                    ->from("socai")
                    ->where("bill_id=" . $row['id'] . " and thu<>0")
                    ->queryAll();
                for($i=0;$i<count($rows);$i++){
                    $r=$rows[$i];
                    $r['trang_thai']=  str_replace("26px", "47px", $r['trang_thai']);
                    $r['trang_thai']=  str_replace('<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/complete.png"/>', "", $r['trang_thai']);
                    Yii::app()->db->createCommand("update socai set trang_thai='".$r['trang_thai']."' where id=" . $r['id'])->execute();
                }
            }
        } else {
            if ($sum == '') {
                $sum = 0;
            }
            if ($this->thu + $sum == $row['sum_and_sumtax']) {
                
                $this->delete_socai("bill_id", $row['id']);
                
            } 
            else if($this->thu + $sum < $row['sum_and_sumtax']){
                $left=$row['sum_and_sumtax']-($this->thu + $sum);
                Yii::app()->db->createCommand("update socai set tm=$left where chi=0 and thu=0 and bill_id=".$row['id'])->execute();
                /**
                 * khi đã thanh toán đủ số tiền thi các icon đã là complete
                 * sau khi update số tiền, trở thành chưa thanh toán đủ
                 * thi phai làm mất các icon complete
                 */
                $rows = Yii::app()->db->createCommand()
                    ->select("*")
                    ->from("socai")
                    ->where("bill_id=" . $row['id'] . " and thu<>0")
                    ->queryAll();
                for($i=0;$i<count($rows);$i++){
                    $r=$rows[$i];
                    $r['trang_thai']=  str_replace("26px", "47px", $r['trang_thai']);
                    $r['trang_thai']=  str_replace('<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/complete.png"/>', "", $r['trang_thai']);
                    Yii::app()->db->createCommand("update socai set trang_thai='".$r['trang_thai']."' where id=" . $r['id'])->execute();
                }
            }
        }
        
        return true;

    }

    protected function process_sxdv1() {
        if($this->tham_chieu==''||$this->tham_chieu==NULL){
            return true;
        }
        $row = Yii::app()->db->createCommand()
                ->select("*")
                ->from("sxdv")
                ->where("bill_number=" . intval($this->tham_chieu))
                ->queryRow();
        if (!is_array($row) || count($row) == 0) {
            return FALSE;
        }
       

        $sum_count = Yii::app()->db->createCommand()
                ->select("sum(thu) as sum,count(*) as count")
                ->from("socai")
                ->where("sxdv_id=" . $row['id'] . " and thu<>0 and id<>" . $this->id)
                ->queryRow();
        $sum = $sum_count['sum'];
        $count = $sum_count['count'];
        if ($count == '0') {
            if ($this->thu == $row['sum_and_sumtax']) {
                
                $this->delete_socai("sxdv_id", $row['id']);
            }
            else if($this->thu  < $row['sum_and_sumtax']){
                $left=$row['sum_and_sumtax']-$this->thu ;
                Yii::app()->db->createCommand("update socai set tm=$left where chi=0 and thu=0 and sxdv_id=".$row['id'])->execute();
                /**
                 * khi đã thanh toán đủ số tiền thi các icon đã là complete
                 * sau khi update số tiền, trở thành chưa thanh toán đủ
                 * thi phai làm mất các icon complete
                 */
                $rows = Yii::app()->db->createCommand()
                    ->select("*")
                    ->from("socai")
                    ->where("sxdv_id=" . $row['id'] . " and thu<>0")
                    ->queryAll();
                for($i=0;$i<count($rows);$i++){
                    $r=$rows[$i];
                    $r['trang_thai']=  str_replace("26px", "47px", $r['trang_thai']);
                    $r['trang_thai']=  str_replace('<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/complete.png"/>', "", $r['trang_thai']);
                    Yii::app()->db->createCommand("update socai set trang_thai='".$r['trang_thai']."' where id=" . $r['id'])->execute();
                }
            }
        } else {
            if ($sum == '') {
                $sum = 0;
            }
            if ($this->thu + $sum == $row['sum_and_sumtax']) {
               
                $this->delete_socai("sxdv_id", $row['id']);
                
            } 
            else if($this->thu + $sum < $row['sum_and_sumtax']){
                $left=$row['sum_and_sumtax']-($this->thu + $sum);
                Yii::app()->db->createCommand("update socai set tm=$left where chi=0 and thu=0 and sxdv_id=".$row['id'])->execute();
                /**
                 * khi đã thanh toán đủ số tiền thi các icon đã là complete
                 * sau khi update số tiền, trở thành chưa thanh toán đủ
                 * thi phai làm mất các icon complete
                 */
                $rows = Yii::app()->db->createCommand()
                    ->select("*")
                    ->from("socai")
                    ->where("sxdv_id=" . $row['id'] . " and thu<>0")
                    ->queryAll();
                for($i=0;$i<count($rows);$i++){
                    $r=$rows[$i];
                    $r['trang_thai']=  str_replace("26px", "47px", $r['trang_thai']);
                    $r['trang_thai']=  str_replace('<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/complete.png"/>', "", $r['trang_thai']);
                    Yii::app()->db->createCommand("update socai set trang_thai='".$r['trang_thai']."' where id=" . $r['id'])->execute();
                }
            }
        }
        
        return true;

    }

    protected function process_kxhd1() {
        if($this->tham_chieu==''||$this->tham_chieu==NULL){
            return true;
        }
        $row = Yii::app()->db->createCommand()
                ->select("*")
                ->from("kxhd")
                ->where("stt=" . $this->tham_chieu)
                ->queryRow();
        if (!is_array($row) || count($row) == 0) {
            return FALSE;
        }


        $sum_count = Yii::app()->db->createCommand()
                ->select("sum(thu) as sum,count(*) as count")
                ->from("socai")
                ->where("kxhd_id=" . $row['id'] . " and thu<>0 and id<>" . $this->id)
                ->queryRow();
        $sum = $sum_count['sum'];
        $count = $sum_count['count'];
        if ($count == '0') {
            if ($this->thu == $row['sum_and_sumtax']) {
                
                $this->delete_socai("kxhd_id", $row['id']);
            } 
            else if($this->thu  < $row['sum_and_sumtax']){
                $left=$row['sum_and_sumtax']-$this->thu ;
                Yii::app()->db->createCommand("update socai set tm=$left where chi=0 and thu=0 and kxhd_id=".$row['id'])->execute();
                /**
                 * khi đã thanh toán đủ số tiền thi các icon đã là complete
                 * sau khi update số tiền, trở thành chưa thanh toán đủ
                 * thi phai làm mất các icon complete
                 */
                $rows = Yii::app()->db->createCommand()
                    ->select("*")
                    ->from("socai")
                    ->where("kxhd_id=" . $row['id'] . " and thu<>0")
                    ->queryAll();
                for($i=0;$i<count($rows);$i++){
                    $r=$rows[$i];
                    $r['trang_thai']=  str_replace("26px", "47px", $r['trang_thai']);
                    $r['trang_thai']=  str_replace('<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/complete.png"/>', "", $r['trang_thai']);
                    Yii::app()->db->createCommand("update socai set trang_thai='".$r['trang_thai']."' where id=" . $r['id'])->execute();
                }
            }
        } else {
            if ($sum == '') {
                $sum = 0;
            }
            if ($this->thu + $sum == $row['sum_and_sumtax']) {

                $this->delete_socai("kxhd_id", $row['id']);
                
            } 
            else if($this->thu + $sum < $row['sum_and_sumtax']){
                $left=$row['sum_and_sumtax']-($this->thu + $sum);
                Yii::app()->db->createCommand("update socai set tm=$left where chi=0 and thu=0 and kxhd_id=".$row['id'])->execute();
                /**
                 * khi đã thanh toán đủ số tiền thi các icon đã là complete
                 * sau khi update số tiền, trở thành chưa thanh toán đủ
                 * thi phai làm mất các icon complete
                 */
                $rows = Yii::app()->db->createCommand()
                    ->select("*")
                    ->from("socai")
                    ->where("kxhd_id=" . $row['id'] . " and thu<>0")
                    ->queryAll();
                for($i=0;$i<count($rows);$i++){
                    $r=$rows[$i];
                    $r['trang_thai']=  str_replace("26px", "47px", $r['trang_thai']);
                    $r['trang_thai']=  str_replace('<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/complete.png"/>', "", $r['trang_thai']);
                    Yii::app()->db->createCommand("update socai set trang_thai='".$r['trang_thai']."' where id=" . $r['id'])->execute();
                }
            }
        }
        
        return true;

    }

    protected function process_laisuat1() {
        if($this->tham_chieu==''||$this->tham_chieu==NULL){
            return true;
        }
        $row = Yii::app()->db->createCommand()
                ->select("*")
                ->from("lai_suat")
                ->where("stt=" . $this->tham_chieu)
                ->queryRow();
        if (!is_array($row) || count($row) == 0) {
            return FALSE;
        }
        

        $sum_count = Yii::app()->db->createCommand()
                ->select("sum(thu) as sum,count(*) as count")
                ->from("socai")
                ->where("lai_suat_id=" . $row['id'] . " and thu<>0 and id<>" . $this->id)
                ->queryRow();
        $sum = $sum_count['sum'];
        $count = $sum_count['count'];
        if ($count == '0') {
            if ($this->thu == $row['sum_and_sumtax']) {
                
                $this->delete_socai("lai_suat_id", $row['id']);
            } 
            else if($this->thu  < $row['sum_and_sumtax']){
                $left=$row['sum_and_sumtax']-$this->thu ;
                Yii::app()->db->createCommand("update socai set tm=$left where chi=0 and thu=0 and lai_suat_id=".$row['id'])->execute();
                /**
                 * khi đã thanh toán đủ số tiền thi các icon đã là complete
                 * sau khi update số tiền, trở thành chưa thanh toán đủ
                 * thi phai làm mất các icon complete
                 */
                $rows = Yii::app()->db->createCommand()
                    ->select("*")
                    ->from("socai")
                    ->where("lai_suat_id=" . $row['id'] . " and thu<>0")
                    ->queryAll();
                for($i=0;$i<count($rows);$i++){
                    $r=$rows[$i];
                    $r['trang_thai']=  str_replace("26px", "47px", $r['trang_thai']);
                    $r['trang_thai']=  str_replace('<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/complete.png"/>', "", $r['trang_thai']);
                    Yii::app()->db->createCommand("update socai set trang_thai='".$r['trang_thai']."' where id=" . $r['id'])->execute();
                }
            }
        } else {
            if ($sum == '') {
                $sum = 0;
            }
            if ($this->thu + $sum == $row['sum_and_sumtax']) {

                $this->delete_socai("lai_suat_id", $row['id']);
                
            } 
            else if($this->thu + $sum < $row['sum_and_sumtax']){
                $left=$row['sum_and_sumtax']-($this->thu + $sum);
                Yii::app()->db->createCommand("update socai set tm=$left where chi=0 and thu=0 and lai_suat_id=".$row['id'])->execute();
                /**
                 * khi đã thanh toán đủ số tiền thi các icon đã là complete
                 * sau khi update số tiền, trở thành chưa thanh toán đủ
                 * thi phai làm mất các icon complete
                 */
                $rows = Yii::app()->db->createCommand()
                    ->select("*")
                    ->from("socai")
                    ->where("lai_suat_id=" . $row['id'] . " and thu<>0")
                    ->queryAll();
                for($i=0;$i<count($rows);$i++){
                    $r=$rows[$i];
                    $r['trang_thai']=  str_replace("26px", "47px", $r['trang_thai']);
                    $r['trang_thai']=  str_replace('<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/complete.png"/>', "", $r['trang_thai']);
                    Yii::app()->db->createCommand("update socai set trang_thai='".$r['trang_thai']."' where id=" . $r['id'])->execute();
                }
            }
        }
        
        return true;

    }

    protected function process_tokhai1() {
        if($this->tham_chieu==''||$this->tham_chieu==NULL){
            return true;
        }
        $row = Yii::app()->db->createCommand()
                ->select("*")
                ->from("bill_input")
                ->where("bill_number='" . $this->tham_chieu . '\' and is_international=1')
                ->queryRow();
        if (!is_array($row) || count($row) == 0) {
            return FALSE;
        }
        /**
         * 
         */
        $sum_count = Yii::app()->db->createCommand()
                ->select("sum(chi) as sum")
                ->from("socai")
                ->where("bill_input_id=" . $row['id'] . " and chi<>0 and id<>" . $this->id)
                ->queryRow();
        $sum = $sum_count['sum'];
        if ($sum == '') {
            $sum = 0;
        }
        
        /**
         * 
         */
        
        $model = BillInput::model()->findByPk($row['id']);
        $tien = 0;
        $and_where = '';
        if ($this->payment_method_to_khai == 'Giá trị hàng hóa (VND)') {
            $tien = $row['gia_tri_hang_hoa_vnd'];
            $this->payment_method_id3 = $this->thanh_toan;
            $model->payment_method_id3 = $this->thanh_toan;
            $this->giao_dich.="\nGiá trị hàng hóa (VND)";
            $and_where = " and payment_method_id3 is not null";
            $payment_method_id = "payment_method_id3";
        } else if ($this->payment_method_to_khai == 'Chi phí ngân hàng (VND)') {
            $tien = $row['chi_phi_ngan_hang_vnd'];
            $this->payment_method_id4 = $this->thanh_toan;
            $model->payment_method_id4 = $this->thanh_toan;
            $this->giao_dich.="\nChi phí ngân hàng (VND)";
            $and_where = " and payment_method_id4 is not null";
            $payment_method_id = "payment_method_id4";
        } else if ($this->payment_method_to_khai == 'Tiền thuế (VND)') {
            $tien = $row['tien_thue_vnd'];
            $this->payment_method_id5 = $this->thanh_toan;
            $model->payment_method_id5 = $this->thanh_toan;
            $this->giao_dich.="\nTiền thuế (VND)";
            $and_where = " and payment_method_id5 is not null";
            $payment_method_id = "payment_method_id5";
        }

        $sum_count = Yii::app()->db->createCommand()
                ->select("sum(chi) as sum,count(*) as count")
                ->from("socai")
                ->where("bill_input_id=" . $row['id'] . " and chi<>0 and id<>" . $this->id . $and_where)
                ->queryRow();
        $sum = $sum_count['sum'];
        $count = $sum_count['count'];
        if ($count == '0') {
            if ($this->chi == $tien) {
                
                $this->delete_socai_for_to_khai($row['id'], $payment_method_id);
            } 
            else if($this->chi  < $tien){
                $left=$tien-$this->chi ;
                Yii::app()->db->createCommand("update socai set tm=$left where $payment_method_id is not null and chi=0 and thu=0 and bill_input_id=".$row['id'])->execute();
                /**
                 * khi đã thanh toán đủ số tiền thi các icon đã là complete
                 * sau khi update số tiền, trở thành chưa thanh toán đủ
                 * thi phai làm mất các icon complete
                 */
                $rows = Yii::app()->db->createCommand()
                    ->select("*")
                    ->from("socai")
                    ->where("bill_input_id=" . $row['id'] . " and chi<>0 and $payment_method_id is not null")
                    ->queryAll();
                for($i=0;$i<count($rows);$i++){
                    $r=$rows[$i];
                    $r['trang_thai']=  str_replace("26px", "47px", $r['trang_thai']);
                    $r['trang_thai']=  str_replace('<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/complete.png"/>', "", $r['trang_thai']);
                    Yii::app()->db->createCommand("update socai set trang_thai='".$r['trang_thai']."' where id=" . $r['id'])->execute();
                }
            }
        } else {
            if ($sum == '') {
                $sum = 0;
            }
            if ($this->chi + $sum == $tien) {

                $this->delete_socai_for_to_khai($row['id'], $payment_method_id);

            } 
            else if($this->chi + $sum < $tien){
                $left=$tien-($this->chi + $sum);
                Yii::app()->db->createCommand("update socai set tm=$left where $payment_method_id is not null and chi=0 and thu=0 and bill_input_id=".$row['id'])->execute();
                /**
                 * khi đã thanh toán đủ số tiền thi các icon đã là complete
                 * sau khi update số tiền, trở thành chưa thanh toán đủ
                 * thi phai làm mất các icon complete
                 */
                $rows = Yii::app()->db->createCommand()
                    ->select("*")
                    ->from("socai")
                    ->where("bill_input_id=" . $row['id'] . " and chi<>0 and $payment_method_id is not null")
                    ->queryAll();
                for($i=0;$i<count($rows);$i++){
                    $r=$rows[$i];
                    $r['trang_thai']=  str_replace("26px", "47px", $r['trang_thai']);
                    $r['trang_thai']=  str_replace('<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/complete.png"/>', "", $r['trang_thai']);
                    Yii::app()->db->createCommand("update socai set trang_thai='".$r['trang_thai']."' where id=" . $r['id'])->execute();
                }
            }
        }
        
        return true;

    }

    protected function process_nhap_kho_kinh_doanh1() {
        if($this->tham_chieu==''||$this->tham_chieu==NULL){
            return true;
        }
        $row = Yii::app()->db->createCommand()
                ->select("*")
                ->from("bill_input")
                ->where("id=".$this->bill_input_id)
                ->queryRow();
        if (!is_array($row) || count($row) == 0) {
            return FALSE;
        }
        $this->bill_input_id = $row['id'];

        $sum_count = Yii::app()->db->createCommand()
                ->select("sum(chi) as sum,count(*) as count")
                ->from("socai")
                ->where("bill_input_id=" . $row['id'] . " and chi<>0 and id<>" . $this->id)
                ->queryRow();
        $sum = $sum_count['sum'];
        $count = $sum_count['count'];
        if ($count == '0') {
            if ($this->chi == $row['sum_and_sumtax']) {
               
                $this->delete_socai("bill_input_id", $row['id']);
            }
            else if($this->chi < $row['sum_and_sumtax']){
                $left=$row['sum_and_sumtax']-$this->chi;
                Yii::app()->db->createCommand("update socai set tm=$left where chi=0 and thu=0 and bill_input_id=".$row['id'])->execute();
                /**
                 * khi đã thanh toán đủ số tiền thi các icon đã là complete
                 * sau khi update số tiền, trở thành chưa thanh toán đủ
                 * thi phai làm mất các icon complete
                 */
                $rows = Yii::app()->db->createCommand()
                    ->select("*")
                    ->from("socai")
                    ->where("bill_input_id=" . $row['id'] . " and chi<>0")
                    ->queryAll();
                for($i=0;$i<count($rows);$i++){
                    $r=$rows[$i];
                    $r['trang_thai']=  str_replace("26px", "47px", $r['trang_thai']);
                    $r['trang_thai']=  str_replace('<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/complete.png"/>', "", $r['trang_thai']);
                    Yii::app()->db->createCommand("update socai set trang_thai='".$r['trang_thai']."' where id=" . $r['id'])->execute();
                }
            }
        } else {
            if ($sum == '') {
                $sum = 0;
            }
            if ($this->chi + $sum == $row['sum_and_sumtax']) {
                
                $this->delete_socai("bill_input_id", $row['id']);
                
            } 
            else if($this->chi + $sum < $row['sum_and_sumtax']){
                $left=$row['sum_and_sumtax']-($this->chi + $sum);
                Yii::app()->db->createCommand("update socai set tm=$left where chi=0 and thu=0 and bill_input_id=".$row['id'])->execute();
                /**
                 * khi đã thanh toán đủ số tiền thi các icon đã là complete
                 * sau khi update số tiền, trở thành chưa thanh toán đủ
                 * thi phai làm mất các icon complete
                 */
                $rows = Yii::app()->db->createCommand()
                    ->select("*")
                    ->from("socai")
                    ->where("bill_input_id=" . $row['id'] . " and chi<>0")
                    ->queryAll();
                for($i=0;$i<count($rows);$i++){
                    $r=$rows[$i];
                    $r['trang_thai']=  str_replace("26px", "47px", $r['trang_thai']);
                    $r['trang_thai']=  str_replace('<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/complete.png"/>', "", $r['trang_thai']);
                    Yii::app()->db->createCommand("update socai set trang_thai='".$r['trang_thai']."' where id=" . $r['id'])->execute();
                }
            }
        }
        
        return true;

    }

    protected function process_chi_phi_co_hoa_don1() {
        if($this->tham_chieu==''||$this->tham_chieu==NULL){
            return true;
        }
        $row = Yii::app()->db->createCommand()
                ->select("*")
                ->from("bill_chi_phi")
                ->where("bill_number='" . $this->tham_chieu . "'")
                ->queryRow();
        if (!is_array($row) || count($row) == 0) {
            return FALSE;
        }
        

        $sum_count = Yii::app()->db->createCommand()
                ->select("sum(chi) as sum,count(*) as count")
                ->from("socai")
                ->where("bill_chi_phi_id=" . $row['id'] . " and chi<>0 and id<>" . $this->id)
                ->queryRow();
        $sum = $sum_count['sum'];
        $count = $sum_count['count'];
        if ($count == '0') {
            if ($this->chi == $row['sum_and_sumtax']) {
                
                $this->delete_socai("bill_chi_phi_id", $row['id']);
            } 
            else if($this->chi  < $row['sum_and_sumtax']){
                $left=$row['sum_and_sumtax']-$this->chi ;
                Yii::app()->db->createCommand("update socai set tm=$left where chi=0 and thu=0 and bill_chi_phi_id=".$row['id'])->execute();
                /**
                 * khi đã thanh toán đủ số tiền thi các icon đã là complete
                 * sau khi update số tiền, trở thành chưa thanh toán đủ
                 * thi phai làm mất các icon complete
                 */
                $rows = Yii::app()->db->createCommand()
                    ->select("*")
                    ->from("socai")
                    ->where("bill_chi_phi_id=" . $row['id'] . " and chi<>0")
                    ->queryAll();
                for($i=0;$i<count($rows);$i++){
                    $r=$rows[$i];
                    $r['trang_thai']=  str_replace("26px", "47px", $r['trang_thai']);
                    $r['trang_thai']=  str_replace('<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/complete.png"/>', "", $r['trang_thai']);
                    Yii::app()->db->createCommand("update socai set trang_thai='".$r['trang_thai']."' where id=" . $r['id'])->execute();
                }
            }
        } else {
            if ($sum == '') {
                $sum = 0;
            }
            if ($this->chi + $sum == $row['sum_and_sumtax']) {
                
                $this->delete_socai("bill_chi_phi_id", $row['id']);
                
            }
            else if($this->chi + $sum < $row['sum_and_sumtax']){
                $left=$row['sum_and_sumtax']-($this->chi + $sum);
                Yii::app()->db->createCommand("update socai set tm=$left where chi=0 and thu=0 and bill_chi_phi_id=".$row['id'])->execute();
                /**
                 * khi đã thanh toán đủ số tiền thi các icon đã là complete
                 * sau khi update số tiền, trở thành chưa thanh toán đủ
                 * thi phai làm mất các icon complete
                 */
                $rows = Yii::app()->db->createCommand()
                    ->select("*")
                    ->from("socai")
                    ->where("bill_chi_phi_id=" . $row['id'] . " and chi<>0")
                    ->queryAll();
                for($i=0;$i<count($rows);$i++){
                    $r=$rows[$i];
                    $r['trang_thai']=  str_replace("26px", "47px", $r['trang_thai']);
                    $r['trang_thai']=  str_replace('<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/complete.png"/>', "", $r['trang_thai']);
                    Yii::app()->db->createCommand("update socai set trang_thai='".$r['trang_thai']."' where id=" . $r['id'])->execute();
                }
            }
        }
        
        return true;
        

    }

    protected function process_chi_phi_khong_hoa_don1() {
        if($this->tham_chieu==''||$this->tham_chieu==NULL){
            return true;
        }
        $row = Yii::app()->db->createCommand()
                ->select("*")
                ->from("chi_phi_khd")
                ->where("stt=" . $this->tham_chieu)
                ->queryRow();
        if (!is_array($row) || count($row) == 0) {
            return FALSE;
        }
        

        $sum_count = Yii::app()->db->createCommand()
                ->select("sum(chi) as sum,count(*) as count")
                ->from("socai")
                ->where("chi_phi_khd_id=" . $row['id'] . " and chi<>0 and id<>" . $this->id)
                ->queryRow();
        $sum = $sum_count['sum'];
        $count = $sum_count['count'];
        if ($count == '0') {
            if ($this->chi == $row['sum_and_sumtax']) {
                
                $this->delete_socai("chi_phi_khd_id", $row['id']);
            } 
            else if($this->chi < $row['sum_and_sumtax']){
                $left=$row['sum_and_sumtax']-$this->chi ;
                Yii::app()->db->createCommand("update socai set tm=$left where chi=0 and thu=0 and chi_phi_khd_id=".$row['id'])->execute();
                /**
                 * khi đã thanh toán đủ số tiền thi các icon đã là complete
                 * sau khi update số tiền, trở thành chưa thanh toán đủ
                 * thi phai làm mất các icon complete
                 */
                $rows = Yii::app()->db->createCommand()
                    ->select("*")
                    ->from("socai")
                    ->where("chi_phi_khd_id=" . $row['id'] . " and chi<>0")
                    ->queryAll();
                for($i=0;$i<count($rows);$i++){
                    $r=$rows[$i];
                    $r['trang_thai']=  str_replace("26px", "47px", $r['trang_thai']);
                    $r['trang_thai']=  str_replace('<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/complete.png"/>', "", $r['trang_thai']);
                    Yii::app()->db->createCommand("update socai set trang_thai='".$r['trang_thai']."' where id=" . $r['id'])->execute();
                }
            }
        } else {
            if ($sum == '') {
                $sum = 0;
            }
            if ($this->chi + $sum == $row['sum_and_sumtax']) {
                
                $this->delete_socai("chi_phi_khd_id", $row['id']);
                
            } 
            else if($this->chi + $sum < $row['sum_and_sumtax']){
                $left=$row['sum_and_sumtax']-($this->chi + $sum);
                Yii::app()->db->createCommand("update socai set tm=$left where chi=0 and thu=0 and chi_phi_khd_id=".$row['id'])->execute();
                /**
                 * khi đã thanh toán đủ số tiền thi các icon đã là complete
                 * sau khi update số tiền, trở thành chưa thanh toán đủ
                 * thi phai làm mất các icon complete
                 */
                $rows = Yii::app()->db->createCommand()
                    ->select("*")
                    ->from("socai")
                    ->where("chi_phi_khd_id=" . $row['id'] . " and chi<>0")
                    ->queryAll();
                for($i=0;$i<count($rows);$i++){
                    $r=$rows[$i];
                    $r['trang_thai']=  str_replace("26px", "47px", $r['trang_thai']);
                    $r['trang_thai']=  str_replace('<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/complete.png"/>', "", $r['trang_thai']);
                    Yii::app()->db->createCommand("update socai set trang_thai='".$r['trang_thai']."' where id=" . $r['id'])->execute();
                }
            }
        }      
        
        return true;
    }
    public function afterSave() {
        parent::afterSave();
        
        if($this->getIsNewRecord()==FALSE){
            $giao_dich = $this->giao_dich;
            if ($giao_dich == 'Hóa đơn thương mại') {
                if($this->process_hoa_don_thuong_mai1()==true){
                    $this->success_after_save=true;
                }
            } else if ($giao_dich == 'HĐ Sản xuất & dịch vụ') {
                if($this->process_sxdv1()==true){
                    $this->success_after_save=true;
                }
            } else if ($giao_dich == 'Không xuất hóa đơn') {
                if($this->process_kxhd1()==true){
                    $this->success_after_save=true;
                }
            } 
            else if (strpos($giao_dich, "Tờ khai")!==FALSE) {
                if($this->process_tokhai1()==true){
                    $this->success_after_save=true;
                }
            } 
            else if ($giao_dich == 'Nhập kho kinh doanh') {
                if($this->process_nhap_kho_kinh_doanh1()==true){
                    $this->success_after_save=true;
                }
            } else if ($giao_dich == 'Chi phí dịch vụ có hóa đơn') {
                if($this->process_chi_phi_co_hoa_don1()==true){
                    $this->success_after_save=true;
                }
            } else if ($giao_dich == 'Lãi suất') {
                if($this->process_laisuat1()==true){
                    $this->success_after_save=true;
                }
            } else if ($giao_dich == 'Chi phí dịch vụ không hóa đơn') {
                if($this->process_chi_phi_khong_hoa_don1()==true){
                    $this->success_after_save=true;
                }
            }

        }
        
    }


    public function beforeSave() {
        $giao_dich = $this->giao_dich;
        if ($this->getIsNewRecord()) {
            if ($this->payment_method_to_khai == 'Giá trị hàng hóa (VND)') {
                $this->payment_method_id3 = $this->thanh_toan;
                $this->giao_dich.="\nGiá trị hàng hóa (VND)";
            } else if ($this->payment_method_to_khai == 'Chi phí ngân hàng (VND)') {
                $this->payment_method_id4 = $this->thanh_toan;
                $this->giao_dich.="\nChi phí ngân hàng (VND)";
            } else if ($this->payment_method_to_khai == 'Tiền thuế (VND)') {
                $this->payment_method_id5 = $this->thanh_toan;
                $this->giao_dich.="\nTiền thuế (VND)";
            }
            $this->tham_chieu = NULL;
            $this->trang_thai = '<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/tam_ung.png"/>';

            return parent::beforeSave();
        }
        if ($giao_dich == 'Hóa đơn thương mại') {
            if($this->process_hoa_don_thuong_mai()==true){
                $this->success_before_save=true;
            }
        } else if ($giao_dich == 'HĐ Sản xuất & dịch vụ') {
            if($this->process_sxdv()==true){
                $this->success_before_save=true;
            }
        } else if ($giao_dich == 'Không xuất hóa đơn') {
            if($this->process_kxhd()==true){
                $this->success_before_save=true;
            }
        } else if ($giao_dich == 'Tờ khai') {
            if($this->process_tokhai()==true){
                $this->success_before_save=true;
            }
        } else if ($giao_dich == 'Nhập kho kinh doanh') {
            if($this->process_nhap_kho_kinh_doanh()==true){
                $this->success_before_save=true;
            }
        } else if ($giao_dich == 'Chi phí dịch vụ có hóa đơn') {
            if($this->process_chi_phi_co_hoa_don()==true){
                $this->success_before_save=true;
            }
        } else if ($giao_dich == 'Lãi suất') {
            if($this->process_laisuat()==true){
                $this->success_before_save=true;
            }
        } else if ($giao_dich == 'Chi phí dịch vụ không hóa đơn') {
            if($this->process_chi_phi_khong_hoa_don()==true){
                $this->success_before_save=true;
            }
        }
        return parent::beforeSave();
    }

    /**
     * sau khi insert hoặc update một record bất kỳ của tháng hiện tại
     * thi update tm cho các record khác trong tháng
     * @param string|int $month
     * @param string|int $year
     * @return void
     */
    public static function update_records($month, $year) {
        if ($month != date("m") || $year != date("Y")) {
            return true;
        }

        $tm = 0;

        $rows = Yii::app()->db->createCommand()->select()->from("socai")->where("MONTH(created_at)=$month AND YEAR(created_at)=$year")->order("created_at ASC")->queryAll();
        Socai::model()->deleteAll("MONTH(created_at)=$month AND YEAR(created_at)=$year");

        $insert = "insert into socai (id,created_at,content,thu,chi,tm,bill_id,bill_input_id,bill_chi_phi_id,sxdv_id,kxhd_id,lai_suat_id,chi_phi_khd_id,giao_dich,thanh_toan,tham_chieu,trang_thai,payment_method_id3,payment_method_id4,payment_method_id5)";
        for ($i = 0; $i < count($rows); $i++) {
            $value = "(";
            $value.=$rows[$i]['id'] . ",";
            $value.="'" . $rows[$i]['created_at'] . "',";
            $value.="'" . str_replace("'", "\'", $rows[$i]['content']) . "',";
            $value.=$rows[$i]['thu'] . ",";
            $value.=$rows[$i]['chi'] . ",";
            if ($rows[$i]['thanh_toan'] != PaymentMethod::KHONG_THANH_TOAN) {
                if($rows[$i]['thanh_toan'] != PaymentMethod::CHUA_THANH_TOAN){
                    $tm = $tm + $rows[$i]['thu'] - $rows[$i]['chi'];                    
                }
                else{
                    $tm=$rows[$i]['tm'];
                }
                
            }

            $value.=$tm . ",";
            if ($rows[$i]['bill_id'] == '') {
                $value.= 'NULL,';
            } else {
                $value.= $rows[$i]['bill_id'] . ",";
            }
            if ($rows[$i]['bill_input_id'] == '') {
                $value.= 'NULL,';
            } else {
                $value.= $rows[$i]['bill_input_id'] . ",";
            }
            if ($rows[$i]['bill_chi_phi_id'] == '') {
                $value.= 'NULL,';
            } else {
                $value.= $rows[$i]['bill_chi_phi_id'] . ",";
            }
            if ($rows[$i]['sxdv_id'] == '') {
                $value.= 'NULL,';
            } else {
                $value.= $rows[$i]['sxdv_id'] . ",";
            }
            if ($rows[$i]['kxhd_id'] == '') {
                $value.= 'NULL,';
            } else {
                $value.= $rows[$i]['kxhd_id'] . ",";
            }
            if ($rows[$i]['lai_suat_id'] == '') {
                $value.= 'NULL,';
            } else {
                $value.= $rows[$i]['lai_suat_id'] . ",";
            }
            if ($rows[$i]['chi_phi_khd_id'] == '') {
                $value.= 'NULL,';
            } else {
                $value.= $rows[$i]['chi_phi_khd_id'] . ",";
            }

            $value.="'" . str_replace("'", "\'", $rows[$i]['giao_dich']) . "',";
            if ($rows[$i]['thanh_toan'] == '') {
                $value.= 'NULL,';
            } else {
                $value.= $rows[$i]['thanh_toan'] . ",";
            }
            $value.="'" . str_replace("'", "\'", $rows[$i]['tham_chieu']) . "',";
            $value.="'" . str_replace("'", "\'", $rows[$i]['trang_thai']) . "',";
            if ($rows[$i]['payment_method_id3'] == '') {
                $value.= 'NULL,';
            } else {
                $value.= $rows[$i]['payment_method_id3'] . ",";
            }
            if ($rows[$i]['payment_method_id4'] == '') {
                $value.= 'NULL,';
            } else {
                $value.= $rows[$i]['payment_method_id4'] . ",";
            }
            if ($rows[$i]['payment_method_id5'] == '') {
                $value.= 'NULL';
            } else {
                $value.= $rows[$i]['payment_method_id5'];
            }
            $value.=")";
            $value_array[] = $value;
        }
        if (isset($value_array) && count($value_array) > 0) {
            $insert.=" values " . implode(",", $value_array);
            $count=Yii::app()->db->createCommand($insert)->execute();
            if($count==count($rows)){
                return true;
            }
        }
        
        return FALSE;
    }

}
