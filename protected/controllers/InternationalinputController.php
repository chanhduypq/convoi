<?php

class InternationalinputController extends Controller {

    private $action;

    public function init() {
        parent::init();
        /**
         * tại page index se có link đến CHI TIẾT/SỬA tờ khai
         * nếu user có quyền chỉnh sửa thi se link đến page chỉnh sửa(action update), còn không thi link đến page xem chi tiết (action view)
         * ở đây, $this->action làm nhiệm vụ đưa qua view để mỗi link trong page index se link đến page chỉnh sửa hay page xem chi tiết
         */
        if (FunctionCommon::get_role() == Role::QUAN_LY_KHO_HANG || FunctionCommon::get_role() == Role::ADMIN) {
            $this->action = 'update';
        } else {
            $this->action = 'view';
        }
    }

    public function actionIndex() {
        $params = array();
        /**
         * 
         */
        if ($this->all_time_common == '1') {
            $where = "1=1";
        } else {
            $where = "date(created_at) >= '" . FunctionCommon::convertDateForDB($this->start_date_common) . "'";
            $where.=" and date(created_at) <= '" . FunctionCommon::convertDateForDB($this->end_date_common) . "'";
        }

        if ($this->customer_id_common != "") {
            $customer_id_common = $this->customer_id_common;
            $where.=" and branch_id=$customer_id_common";
        }
        if ($this->goods_id_common != "") {
            $goods_id_common = $this->goods_id_common;
            $where.=" and id IN (select bill_id from bill_input_detail where goods_id=$goods_id_common)";
        }
        $items = InternationalInputFull::model()->findAll($where);
        $params['items'] = $items;
        /**
         * lấy tổng tiền, tổng thuế, tổng số hóa đơn
         */
        $row = Yii::app()->db->createCommand()
                ->select("sum(`sum_and_sumtax`) as `sum_and_sumtax`,sum(tax_sum) as tax_sum, count(*) as bill_count")
                ->from("bill_input")
                ->where($where)
                ->andWhere("is_international=1")
                ->queryRow();
        $v1 = Yii::app()->db->createCommand("select sum(gia_tri_hang_hoa_vnd) as all_sum from bill_input where $where AND is_international=1")->queryScalar();
        $v2 = Yii::app()->db->createCommand("select sum(chi_phi_ngan_hang_vnd) as all_sum from bill_input where $where AND is_international=1")->queryScalar();
        $v3 = Yii::app()->db->createCommand("select sum(tax_sum) as all_sum from bill_input where $where AND is_international=1")->queryScalar();
        if ($v1 == "") {
            $v1 = 0;
        }
        if ($v2 == "") {
            $v2 = 0;
        }
        if ($v3 == "") {
            $v3 = 0;
        }
        $params['sum_and_sumtax'] = number_format($v1 + $v2 + $v3, 0, ",", ".");
        $params['tax_sum'] = number_format($row['tax_sum'], 0, ",", ".");
        $params['bill_count'] = $row['bill_count'];
        /**
         * 
         */
        $params['start_date_common'] = Yii::app()->session['start_date_common'];
        $params['end_date_common'] = Yii::app()->session['end_date_common'];
        $params['customer_id_common'] = Yii::app()->session['customer_id_common'];
        $params['goods_id_common'] = Yii::app()->session['goods_id_common'];
        $params['all_time_common'] = Yii::app()->session['all_time_common'];

        $params['action'] = $this->action;
        //
        $this->render('index', $params);
    }

    public function actionMore() {
        $params = array();
        /**
         * 
         */
        if ($this->all_time_common == '1') {
            $where = "1=1";
        } else {
            $where = "date(created_at) >= '" . FunctionCommon::convertDateForDB($this->start_date_common) . "'";
            $where.=" and date(created_at) <= '" . FunctionCommon::convertDateForDB($this->end_date_common) . "'";
        }
        if ($this->customer_id_common != "") {
            $customer_id_common = $this->customer_id_common;
            $where.=" and branch_id=$customer_id_common";
        }
        if ($this->goods_id_common != "") {
            $goods_id_common = $this->goods_id_common;
            $where.=" and id IN (select bill_id from bill_input_detail where goods_id=$goods_id_common)";
        }
        $argPage = (int) Yii::app()->request->getQuery('page', 0);
        $dbCriteria = new CDbCriteria;
        $dbCriteria->condition = $where;
        $dbCriteria->limit = Yii::app()->params['number_of_items_per_page'];
        $dbCriteria->offset = $argPage * $dbCriteria->limit;
        //        
        $items = InternationalInputFull::model()->findAll($dbCriteria);
        if (!is_array($items) || count($items) == 0) {
            echo '';
            Yii::app()->end();
        }
        $params['items'] = $items;
        $params['action'] = $this->action;
        $this->renderPartial('//render_partial/common/more', $params);
    }

    public function actionCreate() {
        $params = array();
        //
        if (Yii::app()->request->isPostRequest) {
            $this->create();
            $this->redirect(array("/internationalinput/index"));
        }
        $params['payment_method'] = PaymentMethod::model()->findAll();
        $params['socai_ids'] = Yii::app()->request->getParam("socai_ids", "");
        $params['gia_tri_hang_hoa']='0';
        $params['chi_phi_ngan_hang']='0';
        $params['tien_thue']='0';
        if ($params['socai_ids'] != "") {
            $socai_ids = explode(",", $params['socai_ids']);
            for ($i = 0; $i < count($socai_ids); $i++) {
                $temp=  explode("|", $socai_ids[$i]);
                $socai_id =$temp[0];
                $payment_method_type =$temp[1];                
                $model_socai=Socai::model()->findByPk($socai_id);                
                if ($payment_method_type == '3') {
                    $params['gia_tri_hang_hoa']+=$model_socai->thu+$model_socai->chi;
                } else if ($payment_method_type == '4') {
                    $params['chi_phi_ngan_hang']+=$model_socai->thu+$model_socai->chi;
                } else if ($payment_method_type == '5') {
                    $params['tien_thue']+=$model_socai->thu+$model_socai->chi;
                }
            }
        }
        

        //
        $this->render('create', $params);
    }

    protected function update() {
        /**
         * get parameter (GET/POST)
         */
        $branch_id = Yii::app()->request->getParam("branch_id");
        $description = Yii::app()->request->getParam("description");
        $bill_number = Yii::app()->request->getParam("bill_number");
        $goods_id = Yii::app()->request->getParam("goods_id");
        $price_not_tax = Yii::app()->request->getParam("price_not_tax");
        $price_has_tax = Yii::app()->request->getParam("price_has_tax");
        $created_at = Yii::app()->request->getParam("created_at");
        $quantity = Yii::app()->request->getParam("quantity");
        $sum = Yii::app()->request->getParam("sum", "0");
        $sum = str_replace(".", "", $sum);
        $tax_sum = Yii::app()->request->getParam("tax_sum", "0");
        $tax_sum = str_replace(".", "", $tax_sum);

        $gia_tri_hang_hoa_usd = Yii::app()->request->getParam("gia_tri_hang_hoa_usd", "0");
        $gia_tri_hang_hoa_usd = str_replace(".", "", $gia_tri_hang_hoa_usd);
        $gia_tri_khau_tru_usd = Yii::app()->request->getParam("gia_tri_khau_tru_usd", "");
        if ($gia_tri_khau_tru_usd == "") {
            $gia_tri_khau_tru_usd = NULL;
        } else {
            $gia_tri_khau_tru_usd = str_replace(".", "", $gia_tri_khau_tru_usd);
        }
        $gia_tri_hang_hoa_vnd = Yii::app()->request->getParam("gia_tri_hang_hoa_vnd", "0");
        $gia_tri_hang_hoa_vnd = str_replace(".", "", $gia_tri_hang_hoa_vnd);
        $chi_phi_ngan_hang_vnd = Yii::app()->request->getParam("chi_phi_ngan_hang_vnd", "");
        if ($chi_phi_ngan_hang_vnd == "") {
            $chi_phi_ngan_hang_vnd = NULL;
        } else {
            $chi_phi_ngan_hang_vnd = str_replace(".", "", $chi_phi_ngan_hang_vnd);
        }
        $tien_thue_vnd = Yii::app()->request->getParam("tien_thue_vnd", "");
        if ($tien_thue_vnd == "") {
            $tien_thue_vnd = NULL;
        } else {
            $tien_thue_vnd = str_replace(".", "", $tien_thue_vnd);
        }
        //
        $payment_method_id1 = Yii::app()->request->getParam("payment_method_id1", "");
        if ($payment_method_id1 == "") {
            $payment_method_id1 = NULL;
        }
        $payment_method_id2 = Yii::app()->request->getParam("payment_method_id2", "");
        if ($payment_method_id2 == "") {
            $payment_method_id2 = NULL;
        }
        
        if ($branch_id == NULL) {
            return;
        }
        /**
         * lưu hóa đơn
         */
        $bill_model = BillInput::model()->findByPk(Yii::app()->request->getParam("id"));
        if ($sum != "0") {
            $bill_model->sum = $sum;
            $bill_model->tax_sum = $tax_sum;
        }
        $bill_model->gia_tri_hang_hoa_usd = $gia_tri_hang_hoa_usd;
        $bill_model->gia_tri_khau_tru_usd = $gia_tri_khau_tru_usd;
        $bill_model->gia_tri_hang_hoa_vnd = $gia_tri_hang_hoa_vnd;
        $bill_model->chi_phi_ngan_hang_vnd = $chi_phi_ngan_hang_vnd;
        $bill_model->tien_thue_vnd = $tien_thue_vnd;

        $bill_model->payment_method_id1 = $payment_method_id1;
        $bill_model->payment_method_id2 = $payment_method_id2;


        $bill_model->branch_id = $branch_id;
        $bill_model->description = $description;
        $bill_model->bill_number = $bill_number;
        $bill_model->created_at = $created_at;
        $bill_model->save(FALSE);
        /**
         * xóa thông tin chi tiết hóa đơn cũ trước khi lưu thông tin mới         * 
         */
        foreach (BillInputDetail::model()->findAll("bill_id=" . $bill_model->id) as $bill_detail_model) {
            $bill_detail_model->delete();
        }
        if ($sum == "0") {
            return;
        }
        /**
         * lưu chi tiết hóa đơn
         */
        for ($i = 0; $i < count($goods_id); $i++) {
            $bill_detail_model = new BillInputDetail();
            $bill_detail_model->setIsNewRecord(true);
            $bill_detail_model->goods_id = $goods_id[$i];
            $bill_detail_model->quantity = $quantity[$i];
            $bill_detail_model->price = $price_not_tax[$i];
            $bill_detail_model->price_has_tax = $price_has_tax[$i];
            $bill_detail_model->bill_id = $bill_model->id;
            $bill_detail_model->is_international = 1;
            $bill_detail_model->save(FALSE);
        }
        /**
         * nếu số tờ khai thay đổi thi số tờ khai bên sổ cái cũng thay đổi theo
         */
        Yii::app()->db->createCommand("update socai set tham_chieu='$bill_number' where bill_input_id=" . Yii::app()->request->getParam("id"))->execute();
        /**
         * 
         */
        if($bill_model->is_complete==1){
            $this->update_complete_and_socai($bill_model);
        }
        /**
         * 
         */
        $sum=Yii::app()->db->createCommand()
                ->select("sum(chi)")
                ->from("socai")
                ->where("bill_input_id=" . $bill_model->id . " and chi<>0 and payment_method_id3 is not null")
                ->queryScalar();
        if($sum==FALSE||$sum==''){
            $sum=0;
        }
        $so_tien_con_lai=$bill_model->gia_tri_hang_hoa_vnd-$sum;
        Yii::app()->db->createCommand("update socai set tm=$so_tien_con_lai where bill_input_id=" . $bill_model->id." and chi=0 and payment_method_id3 is not null")->execute();
        //
        $sum=Yii::app()->db->createCommand()
                ->select("sum(chi)")
                ->from("socai")
                ->where("bill_input_id=" . $bill_model->id . " and chi<>0 and payment_method_id4 is not null")
                ->queryScalar();
        if($sum==FALSE||$sum==''){
            $sum=0;
        }
        $so_tien_con_lai=$bill_model->chi_phi_ngan_hang_vnd-$sum;
        Yii::app()->db->createCommand("update socai set tm=$so_tien_con_lai where bill_input_id=" . $bill_model->id." and chi=0 and payment_method_id4 is not null")->execute();
        //
        $sum=Yii::app()->db->createCommand()
                ->select("sum(chi)")
                ->from("socai")
                ->where("bill_input_id=" . $bill_model->id . " and chi<>0 and payment_method_id5 is not null")
                ->queryScalar();
        if($sum==FALSE||$sum==''){
            $sum=0;
        }
        $so_tien_con_lai=$bill_model->tien_thue_vnd-$sum;
        Yii::app()->db->createCommand("update socai set tm=$so_tien_con_lai where bill_input_id=" . $bill_model->id." and chi=0 and payment_method_id5 is not null")->execute();
    }
    /**
     * sau khi update, nếu số tiền lớn hơn tổng tiền bên sổ cái
     * thi update các icon (các icon complete thành các icon chưa complete)
     * insert thêm 1 record vào sổ cái nữa
     * 
     * làm 3 lần như vay đối với: giá trị hàng hóa VND, chi phí ngân hàng VND, tiền thuế VND
     */
    protected function update_and_insert_socai($model){
        /**
         * giá trị hàng hóa VND
         */
        $sum=Yii::app()->db->createCommand()
                ->select("sum(chi)")
                ->from("socai")
                ->where("bill_input_id=".$model->id." and payment_method_id3 is not null")
                ->queryScalar();
        if($sum==FALSE||$sum==''){
            $sum=0;
        }
        if($sum<$model->gia_tri_hang_hoa_vnd){
            $rows = Yii::app()->db->createCommand()
                    ->select("*")
                    ->from("socai")
                    ->where("bill_input_id=" . $model->id . " and chi<>0 and payment_method_id3 is not null")
                    ->queryAll();
            for($i=0;$i<count($rows);$i++){
                $r=$rows[$i];
                $r['trang_thai']=  str_replace("26px", "47px", $r['trang_thai']);
                $r['trang_thai']=  str_replace('<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/complete.png"/>', "", $r['trang_thai']);
                if(count($rows)==1){
                    $r['trang_thai'] = '<div style="position: absolute;margin-top: -40px;margin-left: 47px;width: 20px;height: 20px;">1</div>'.
                                       '<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/not_complete.png"/>';
                }
                Yii::app()->db->createCommand("update socai set trang_thai='".$r['trang_thai']."' where id=" . $r['id'])->execute();

                $tham_chieu=$r['tham_chieu'];
                $content=$r['content'];
            }
            //vì số tiền vừa update lớn hơn số tiền trong sổ cái, nên phai insert 1 record vào sổ cái.
            $trang_thai = '<div style="position: absolute;margin-top: -40px;margin-left: 47px;width: 20px;height: 20px;">' . (count($rows)+1) . '</div>' .
                    '<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/not_complete.png"/>';
            Yii::app()->db->createCommand("insert into socai ("
                            . "thu,"
                            . "chi,"
                            . "created_at,"
                            . "bill_id,"
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
                            . $model->id . ","
                            . "'Tờ khai\nGiá trị hàng hóa (VND)',"
                            . PaymentMethod::CHUA_THANH_TOAN . ","
                            . "'" . $tham_chieu . "',"
                            . "'" . $content . "',"
                            .($model->gia_tri_hang_hoa_vnd-$sum).","
                            . "'" . $trang_thai . "'"
                            . ")")
                    ->execute();
        }
        /**
         * chi phí ngân hàng VND
         */
        $sum=Yii::app()->db->createCommand()
                ->select("sum(chi)")
                ->from("socai")
                ->where("bill_input_id=".$model->id." and payment_method_id4 is not null")
                ->queryScalar();
        if($sum==FALSE||$sum==''){
            $sum=0;
        }
        if($sum<$model->chi_phi_ngan_hang_vnd){
            $rows = Yii::app()->db->createCommand()
                    ->select("*")
                    ->from("socai")
                    ->where("bill_input_id=" . $model->id . " and chi<>0 and payment_method_id4 is not null")
                    ->queryAll();
            for($i=0;$i<count($rows);$i++){
                $r=$rows[$i];
                $r['trang_thai']=  str_replace("26px", "47px", $r['trang_thai']);
                $r['trang_thai']=  str_replace('<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/complete.png"/>', "", $r['trang_thai']);
                if(count($rows)==1){
                    $r['trang_thai'] = '<div style="position: absolute;margin-top: -40px;margin-left: 47px;width: 20px;height: 20px;">1</div>'.
                                       '<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/not_complete.png"/>';
                }
                Yii::app()->db->createCommand("update socai set trang_thai='".$r['trang_thai']."' where id=" . $r['id'])->execute();

                $tham_chieu=$r['tham_chieu'];
                $content=$r['content'];
            }
            //
            $trang_thai = '<div style="position: absolute;margin-top: -40px;margin-left: 47px;width: 20px;height: 20px;">' . (count($rows)+1) . '</div>' .
                    '<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/not_complete.png"/>';
            Yii::app()->db->createCommand("insert into socai ("
                            . "thu,"
                            . "chi,"
                            . "created_at,"
                            . "bill_id,"
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
                            . $model->id . ","
                            . "'Tờ khai\nChi phí ngân hàng (VND)',"
                            . PaymentMethod::CHUA_THANH_TOAN . ","
                            . "'" . $tham_chieu . "',"
                            . "'" . $content . "',"
                            .($model->chi_phi_ngan_hang_vnd-$sum).","
                            . "'" . $trang_thai . "'"
                            . ")")
                    ->execute();
        }
        /**
         * tiền thuế VND
         */
        $sum=Yii::app()->db->createCommand()
                ->select("sum(chi)")
                ->from("socai")
                ->where("bill_input_id=".$model->id." and payment_method_id5 is not null")
                ->queryScalar();
        if($sum==FALSE||$sum==''){
            $sum=0;
        }
        if($sum<$model->tien_thue_vnd){
            $rows = Yii::app()->db->createCommand()
                    ->select("*")
                    ->from("socai")
                    ->where("bill_input_id=" . $model->id . " and chi<>0 and payment_method_id5 is not null")
                    ->queryAll();
            for($i=0;$i<count($rows);$i++){
                $r=$rows[$i];
                $r['trang_thai']=  str_replace("26px", "47px", $r['trang_thai']);
                $r['trang_thai']=  str_replace('<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/complete.png"/>', "", $r['trang_thai']);
                if(count($rows)==1){
                    $r['trang_thai'] = '<div style="position: absolute;margin-top: -40px;margin-left: 47px;width: 20px;height: 20px;">1</div>'.
                                       '<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/not_complete.png"/>';
                }
                Yii::app()->db->createCommand("update socai set trang_thai='".$r['trang_thai']."' where id=" . $r['id'])->execute();

                $tham_chieu=$r['tham_chieu'];
                $content=$r['content'];
            }
            //
            $trang_thai = '<div style="position: absolute;margin-top: -40px;margin-left: 47px;width: 20px;height: 20px;">' . (count($rows)+1) . '</div>' .
                    '<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/not_complete.png"/>';
            Yii::app()->db->createCommand("insert into socai ("
                            . "thu,"
                            . "chi,"
                            . "created_at,"
                            . "bill_id,"
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
                            . $model->id . ","
                            . "'Tờ khai\nTiền thuế (VND)',"
                            . PaymentMethod::CHUA_THANH_TOAN . ","
                            . "'" . $tham_chieu . "',"
                            . "'" . $content . "',"
                            .($model->tien_thue_vnd-$sum).","
                            . "'" . $trang_thai . "'"
                            . ")")
                    ->execute();
        }
    }
    public function update_complete_and_socai($model){
        /**
         * update complete
         */
        $sum=Yii::app()->db->createCommand()
                ->select("sum(chi)")
                ->from("socai")
                ->where("bill_input_id=".$model->id)
                ->queryScalar();
        if($sum==FALSE||$sum==''){
            $sum=0;
        }
        if($sum==$model->gia_tri_hang_hoa_vnd+$model->chi_phi_ngan_hang_vnd+$model->tien_thue_vnd){
            return;
        }
        $model->is_complete=0;
        $model->is_paying=1;
        $model->save(FALSE);
        
        $this->update_and_insert_socai($model);        
    }

    public function actionUpdate() {
        $params = array();
        /**
         * 
         */
        if (Yii::app()->request->isPostRequest) {
            $this->update();
            $this->save_history_for_update();
            $this->redirect(array("/internationalinput/index"));
        }
        $id = Yii::app()->request->getParam("id", '0');
        //
        $params['invoicefull_model'] = InternationalInputFull::model()->findByAttributes(array('id' => $id));
        $params['bill_details'] = BillInputDetaiInternationallFull::model()->findAllByAttributes(array('bill_id' => Yii::app()->request->getParam("id")));
        /**
         * 
         */
        $update_histoty_array = BillInputHistory::getUpdateHistoty($id);
        $params['update_histoty_array'] = $update_histoty_array;
        /**
         * 
         */
        $goods = Yii::app()->db->createCommand()
                ->select("group_id,goods_full_name")
                ->from("goods")
                ->where("is_international=1")
                ->group("group_id")
                ->queryAll();

        $params['goods'] = $goods;
        $DATE_FORMAT = FunctionCommon::convertDateForDBSelect();
        $created_user = Yii::app()->db->createCommand()
                ->select("user.danh_xung,user.full_name,date_format(bill_input.created_at,'$DATE_FORMAT - %H:%i:%s') AS created_at_date")
                ->from("bill_input")
                ->leftJoin("user", "user.id=bill_input.user_id")
                ->where("bill_input.id=$id")
                ->queryRow()
        ;
        $params['created_user'] = $created_user;
        
        $rows=Yii::app()->db->createCommand()
                ->select("*")
                ->from("socai")
                ->where("bill_input_id=$id")
                ->queryAll();
        $sum_socai_payment_method3=$sum_socai_payment_method4=$sum_socai_payment_method5=0;
        if(is_array($rows)&&count($rows)>0){
            foreach ($rows as $row){
                if($row['payment_method_id3']!=""){
                    $sum_socai_payment_method3+=$row['chi'];
                }
                else if($row['payment_method_id4']!=""){
                    $sum_socai_payment_method4+=$row['chi'];
                }
                else if($row['payment_method_id5']!=""){
                    $sum_socai_payment_method5+=$row['chi'];
                }
            }
        }
        $params['sum_socai_payment_method3'] = $sum_socai_payment_method3;
        $params['sum_socai_payment_method4'] = $sum_socai_payment_method4;
        $params['sum_socai_payment_method5'] = $sum_socai_payment_method5;
        /**
         * 
         */
        $params['payment_method'] = PaymentMethod::model()->findAll();
        $this->render('update', $params);
    }

    public function actionView() {
        $params = array();
        $id = Yii::app()->request->getParam("id", '0');
        //
        $params['invoicefull_model'] = InternationalInputFull::model()->findByAttributes(array('id' => $id));
        $params['bill_details'] = BillInputDetaiInternationallFull::model()->findAllByAttributes(array('bill_id' => Yii::app()->request->getParam("id")));
        /**
         * 
         */
        $update_histoty_array = BillInputHistory::getUpdateHistoty($id);
        $params['update_histoty_array'] = $update_histoty_array;
        /**
         * 
         */
        $goods = Yii::app()->db->createCommand()
                ->select("group_id,goods_full_name")
                ->from("goods")
                ->group("group_id")
                ->queryAll();

        $params['goods'] = $goods;
        $DATE_FORMAT = FunctionCommon::convertDateForDBSelect();
        $created_user = Yii::app()->db->createCommand()
                ->select("user.danh_xung,user.full_name,date_format(bill_input.created_at,'$DATE_FORMAT - %H:%i:%s') AS created_at_date")
                ->from("bill_input")
                ->leftJoin("user", "user.id=bill_input.user_id")
                ->where("bill_input.id=$id")
                ->queryRow()
        ;
        $params['created_user'] = $created_user;
        $params['payment_method'] = PaymentMethod::model()->findAll();
        /**
         * 
         */
        $this->render('view', $params);
    }

    protected function save_history_for_update() {
        $reason = Yii::app()->request->getParam("reason");

        $bill_id = Yii::app()->request->getParam("id");

        $bill = Yii::app()->db->createCommand()
                ->select("branch_id,sum,tax_sum,last_updated_at")
                ->from("bill_input")
                ->where("id=$bill_id")
                ->queryRow()
        ;
        $last_updated_at = $bill['last_updated_at'];
        unset($bill['last_updated_at']);
        $bill_detail = Yii::app()->db->createCommand()
                ->select("goods_id,quantity,price,price_has_tax")
                ->from("bill_input_detail")
                ->where("bill_id=$bill_id")
                ->queryAll()
        ;
        $data = array('bill' => $bill, 'bill_detail' => $bill_detail);
        $data = CJSON::encode($data);
        $model = new BillInputHistory();
        $model->bill_id = $bill_id;
        $model->data = $data;
        $model->updated_at = $last_updated_at;
        $model->reason = $reason;
        $model->setIsNewRecord(true);
        $model->save(FALSE);
    }
    protected function build_array($socai_ids){
        $socai_ids = explode(",", $socai_ids);
        for ($i = 0; $i < count($socai_ids); $i++) {
            $temp=  explode("|", $socai_ids[$i]);
            $payment_method_type_array[] =$temp[1];
        }
        $payment_method_type_array=  array_unique($payment_method_type_array);
        foreach ($payment_method_type_array as $payment_method_type){
            $array=array();
            for ($i = 0; $i < count($socai_ids); $i++) {
                $temp=  explode("|", $socai_ids[$i]);
                if($temp[1]==$payment_method_type){
                    $array[]=$temp[0];
                }
            }
            $array_all["$payment_method_type"]=$array;
        }
        return $array_all;
    }

    /**
     * nếu create có chọn tạm ứng thi update sổ cái, 
     *                                update chính nó(update complete=1) nếu số tiền bằng số tiền tạm ứng
     */
    protected function process_socai($bill_id,$bill_number) {
        $socai_ids = Yii::app()->request->getParam("socai_ids", "");
        if ($socai_ids != "") {
            $sum1=$sum2=0;
            $array_all= $this->build_array($socai_ids);
            /**
             * lấy tổng tiền bao gồm: chi phí ngân hàng, giá trị hàng hóa,tiền thuế
             */
            $sum11 = Yii::app()->db->createCommand()
                            ->select("gia_tri_hang_hoa_vnd")
                            ->from("bill_input")
                            ->where("id=$bill_id")
                            ->queryScalar();
            if($sum11==FALSE||$sum11==''){
                $sum11=0;
            }
            $sum1+=$sum11;
            $sum12 = Yii::app()->db->createCommand()
                            ->select("chi_phi_ngan_hang_vnd")
                            ->from("bill_input")
                            ->where("id=$bill_id")
                            ->queryScalar();
            if($sum12==FALSE||$sum12==''){
                $sum12=0;
            }
            $sum1+=$sum12;
            $sum13 = Yii::app()->db->createCommand()
                            ->select("tien_thue_vnd")
                            ->from("bill_input")
                            ->where("id=$bill_id")
                            ->queryScalar();
            if($sum13==FALSE||$sum13==''){
                $sum13=0;
            }
            $sum1+=$sum13;
            /**
             * xử lý cho từng loại tiền bao gồm: chi phí ngân hàng, giá trị hàng hóa,tiền thuế
             */
            foreach ($array_all as $key=>$value){
                $content_for_update='';
                if ($key == '3') {
                    $payment_method = 'payment_method_id3';
                    $select = 'gia_tri_hang_hoa_vnd';
                    $content_for_update="Giá trị hàng hóa (VND) tờ khai: \n";
                } else if ($key == '4') {
                    $payment_method = 'payment_method_id4';
                    $select = 'chi_phi_ngan_hang_vnd';
                    $content_for_update="Chi phí ngân hàng (VND) tờ khai: \n";
                } else if ($key == '5') {
                    $payment_method = 'payment_method_id5';
                    $select = 'tien_thue_vnd';
                    $content_for_update="Tiền thuế (VND) tờ khai: \n";
                }
                $payment_method_id = Yii::app()->db->createCommand()
                                ->select("$payment_method")
                                ->from("socai")
                                ->where("id=".$value[0])
                                ->queryScalar();

                Yii::app()->db->createCommand("update socai set bill_input_id=$bill_id where id IN (".  implode(",", $value).");update bill_input set $payment_method=$payment_method_id,is_paying=1 where id=$bill_id")->execute();
                $rows = Yii::app()->db->createCommand()
                                ->select()
                                ->from("socai")
                                ->where("bill_input_id=$bill_id and $payment_method is not null")
                                ->order("id ASC")
                                ->queryAll();
                if (is_array($rows) && count($rows) >0) {
                    $sum_thu_chi =0;
                    for($i=0;$i<count($rows);$i++){
                        $sum_thu_chi+=$rows[$i]['thu']+$rows[$i]['chi'];
                    }
                    $sum2+=$sum_thu_chi;
                    $sum_and_sumtax = Yii::app()->db->createCommand()
                            ->select("$select")
                            ->from("bill_input")
                            ->where("id=$bill_id")
                            ->queryScalar();
                    if($sum_and_sumtax==FALSE||$sum_and_sumtax==''){
                        $sum_and_sumtax=0;
                    }
                    $row_trang_thai2 = Yii::app()->db->createCommand("select * from socai where bill_input_id=$bill_id and thu=0 and chi=0 and $payment_method is not null")->queryRow();
                    if ($sum_and_sumtax == $sum_thu_chi) {
                        Yii::app()->db->createCommand("delete from socai where thu=0 and chi=0 and bill_input_id=$bill_id and $payment_method is not null")->execute();
                        if(count($value)==1){
                            $trang_thai = '<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/complete.png"/>';
                            Yii::app()->db->createCommand("update socai set trang_thai='$trang_thai',tham_chieu='$bill_number',content='" . $row_trang_thai2['content'] . "' where bill_input_id=$bill_id and $payment_method is not null")->execute();
                        }
                        else{
                            for($i=0;$i<count($value);$i++){
                                $trang_thai1 = '<div style="position: absolute;margin-top: -40px;margin-left: 26px;width: 20px;height: 20px;">' . ($i + 1) . '</div>' .
                                                    '<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/not_complete.png"/>' .
                                                    '<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/complete.png"/>';
                                Yii::app()->db->createCommand("update socai set trang_thai='$trang_thai1',tham_chieu='$bill_number',content='" . $row_trang_thai2['content'] . "' where id=".$value[$i])->execute();
                            }
                        }
                        
                    }
                    else{
                        for($i=0;$i<count($value);$i++){
                            $trang_thai1 = '<div style="position: absolute;margin-top: -40px;margin-left: 47px;width: 20px;height: 20px;">'.($i+1).'</div>' .
                                        '<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/not_complete.png"/>';
                            Yii::app()->db->createCommand("update socai set trang_thai='$trang_thai1',content='" . $row_trang_thai2['content'] . "',tham_chieu='" . $row_trang_thai2['tham_chieu'] . "' where id=".$value[$i])->execute();
                        }

                        $trang_thai2 = '<div style="position: absolute;margin-top: -40px;margin-left: 47px;width: 20px;height: 20px;">'.(count($value)+1).'</div>' .
                                '<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/not_complete.png"/>';


                        Yii::app()->db->createCommand("update socai set trang_thai='$trang_thai2',tm=" . ($sum_and_sumtax - $sum_thu_chi) . " where bill_input_id=$bill_id and thu=0 and chi=0 and $payment_method is not null")->execute();   
                    }
                    for($i=0;$i<count($value);$i++){
                        Yii::app()->db->createCommand("update thuchi set content='$content_for_update" . str_replace("'", "\'", $row_trang_thai2['content']) . "',bill_input_id=$bill_id where socai_id=".$value[$i])->execute();
                        Yii::app()->db->createCommand("update tai_khoan_acb set content='$content_for_update" . str_replace("'", "\'", $row_trang_thai2['content']) . "',bill_input_id=$bill_id where socai_id=".$value[$i])->execute();
                    }
                }

            }
            if($sum1==$sum2&&$sum1!=0){
                Yii::app()->db->createCommand("update bill_input set is_complete=1 where id=$bill_id")->execute();
            }
        }
        
    }

    protected function create() {
        /**
         * get parameter (GET/POST)
         */
        $branch_id = Yii::app()->request->getParam("branch_id");
        $description = Yii::app()->request->getParam("description");
        $bill_number = Yii::app()->request->getParam("bill_number");
        $goods_id = Yii::app()->request->getParam("goods_id");
        $created_at = Yii::app()->request->getParam("created_at");
        $price_not_tax = Yii::app()->request->getParam("price_not_tax");
        $price_has_tax = Yii::app()->request->getParam("price_has_tax");
        $quantity = Yii::app()->request->getParam("quantity");
        $sum = Yii::app()->request->getParam("sum", "0");
        $sum = str_replace(".", "", $sum);
        $tax_sum = Yii::app()->request->getParam("tax_sum", "0");
        $tax_sum = str_replace(".", "", $tax_sum);

        $gia_tri_hang_hoa_usd = Yii::app()->request->getParam("gia_tri_hang_hoa_usd", "0");
        $gia_tri_hang_hoa_usd = str_replace(".", "", $gia_tri_hang_hoa_usd);
        $gia_tri_khau_tru_usd = Yii::app()->request->getParam("gia_tri_khau_tru_usd", "");
        if ($gia_tri_khau_tru_usd == "") {
            $gia_tri_khau_tru_usd = NULL;
        } else {
            $gia_tri_khau_tru_usd = str_replace(".", "", $gia_tri_khau_tru_usd);
        }
        $gia_tri_hang_hoa_vnd = Yii::app()->request->getParam("gia_tri_hang_hoa_vnd", "0");
        $gia_tri_hang_hoa_vnd = str_replace(".", "", $gia_tri_hang_hoa_vnd);
        $chi_phi_ngan_hang_vnd = Yii::app()->request->getParam("chi_phi_ngan_hang_vnd", "");
        if ($chi_phi_ngan_hang_vnd == "") {
            $chi_phi_ngan_hang_vnd = NULL;
        } else {
            $chi_phi_ngan_hang_vnd = str_replace(".", "", $chi_phi_ngan_hang_vnd);
        }
        $tien_thue_vnd = Yii::app()->request->getParam("tien_thue_vnd", "");
        if ($tien_thue_vnd == "") {
            $tien_thue_vnd = NULL;
        } else {
            $tien_thue_vnd = str_replace(".", "", $tien_thue_vnd);
        }
        //
        $payment_method_id1 = Yii::app()->request->getParam("payment_method_id1", "");
        if ($payment_method_id1 == "") {
            $payment_method_id1 = NULL;
        }
        $payment_method_id2 = Yii::app()->request->getParam("payment_method_id2", "");
        if ($payment_method_id2 == "") {
            $payment_method_id2 = NULL;
        }
        $payment_method_id3 = Yii::app()->request->getParam("payment_method_id3", PaymentMethod::CHUA_THANH_TOAN);
        if ($payment_method_id3 == "") {
            $payment_method_id3 = NULL;
        }
        $payment_method_id4 = Yii::app()->request->getParam("payment_method_id4", PaymentMethod::CHUA_THANH_TOAN);
        if ($payment_method_id4 == "") {
            $payment_method_id4 = NULL;
        }
        $payment_method_id5 = Yii::app()->request->getParam("payment_method_id5", PaymentMethod::CHUA_THANH_TOAN);
        if ($payment_method_id5 == "") {
            $payment_method_id5 = NULL;
        }
        
        Yii::app()->db->beginTransaction();
        $success=true;
        /**
         * lưu hóa đơn
         */
        $bill_model = new BillInput();
        $bill_model->setIsNewRecord(true);
        if ($sum != "0") {
            $bill_model->sum = $sum;
            $bill_model->tax_sum = $tax_sum;
        }
        $bill_model->gia_tri_hang_hoa_usd = $gia_tri_hang_hoa_usd;
        $bill_model->gia_tri_khau_tru_usd = $gia_tri_khau_tru_usd;
        $bill_model->gia_tri_hang_hoa_vnd = $gia_tri_hang_hoa_vnd;
        $bill_model->chi_phi_ngan_hang_vnd = $chi_phi_ngan_hang_vnd;
        $bill_model->tien_thue_vnd = $tien_thue_vnd;

        $bill_model->payment_method_id1 = $payment_method_id1;
        $bill_model->payment_method_id2 = $payment_method_id2;
        $bill_model->payment_method_id3 = $payment_method_id3;
        $bill_model->payment_method_id4 = $payment_method_id4;
        $bill_model->payment_method_id5 = $payment_method_id5;

        $bill_model->description = $description;
        $bill_model->bill_number = $bill_number;
        $bill_model->branch_id = $branch_id;
        $bill_model->created_at = $created_at;
        $bill_model->is_international = 1;
        if($bill_model->save(FALSE)==FALSE){
            $success=FALSE;
        }
        $bill_id = $bill_model->id;
        if($bill_model->success==FALSE){
            $success=FALSE;
        }

        if ($sum == "0") {
            if($success==FALSE){
                Yii::app()->db->getCurrentTransaction()->rollback();
            }
            else{
                Yii::app()->db->getCurrentTransaction()->commit();
            }
            return;
        }
        /**
         * lưu chi tiết hóa đơn
         */
        for ($i = 0; $i < count($goods_id); $i++) {
            $bill_detail_model = new BillInputDetail();
            $bill_detail_model->setIsNewRecord(true);
            $bill_detail_model->goods_id = $goods_id[$i];
            $bill_detail_model->quantity = $quantity[$i];
            $bill_detail_model->price = $price_not_tax[$i];
            $bill_detail_model->price_has_tax = $price_has_tax[$i];
            $bill_detail_model->bill_id = $bill_id;
            $bill_detail_model->is_international = 1;
            if($bill_detail_model->save(FALSE)==FALSE){
                $success=FALSE;
            }
        }
        /**
         * update sổ cái
         */
        $this->process_socai($bill_id,$bill_number);
        
        if($success==FALSE){
            Yii::app()->db->getCurrentTransaction()->rollback();
            
            Yii::app()->session['error_mysql']='1';
            $this->redirect(array("/internationalinput/create"));      
        }
        else{
            Yii::app()->db->getCurrentTransaction()->commit();
        }
    }

}
