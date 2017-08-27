<?php
/**
 * controller nay chỉ dùng riêng cho việc ajax thôi
 */
class AjaxController extends Controller {
//    public function actionCheckerrortamung() { 
//        $giao_dich=Yii::app()->request->getParam("giao_dich");
//        $tong_tien=Yii::app()->request->getParam("tong_tien");
//        $socai_id=Yii::app()->request->getParam("socai_id");
//        $row_socai=  Socai::model()->findByPk($socai_id);
//    }
    public function actionSearch() { 
        
    }    
    /**
     * lưu thứ tự hiển thị menu
     */
    public function actionSavemenu() {  
        $parent_id_array=Yii::app()->request->getParam("parent","");
        $child_id_array=Yii::app()->request->getParam("child","");
        for($i=0;$i<count($parent_id_array);$i++){            
            Yii::app()->db->createCommand("update menu set `order`=".($i+1)." where id=".$parent_id_array[$i])->execute();
            $menus_temp=Yii::app()->db->createCommand()->select()->from("menu")->where("parent_id=".$parent_id_array[$i])->queryAll();
            if(is_array($menus_temp)&&count($menus_temp)>0){
                $array=array();
                foreach ($menus_temp as $value) {
                    $array[]=$value['id'];
                }
                $k=1;
                for($j=0;$j<count($child_id_array);$j++){
                    if(in_array($child_id_array[$j], $array)){
                        Yii::app()->db->createCommand("update menu set `order`=".($k++)." where id=".$child_id_array[$j])->execute();                        
                    }
                }
            }
            
        }
    }
    /**
     * kiểm tra SỐ HÓA ĐƠN NHẬP HÀNG/SỐ TỜ KHAI đã tồn tại hay chưa
     * cái này cần xem xét lại logic nghe tuetc     
     */
    public function actionCheckbillinputnumberexist() {                
        $id=Yii::app()->request->getParam("id","");
        $model=new BillInput();        
        if($id!=""){
            if(is_numeric($id)){                
                $model=  BillInput::model()->findByPk($id);
            }           
        } 
        else{
            $model->setIsNewRecord(true);
        }        
        $model->bill_number=Yii::app()->request->getParam("bill_number","");
        $model->is_international=Yii::app()->request->getParam("is_international",0);
        $error=CActiveForm::validate($model);
        if($error!='[]'){
            echo $error;            
        }   
        else{
            echo '';            
        }
        Yii::app()->end();
    }   
    public function actionCheckbillinputnumberexistcreate() {                
        $model=new BillInput();        
        $model->setIsNewRecord(true); 
        $bill_number=Yii::app()->request->getParam("bill_number","");
        $tax_code=Yii::app()->request->getParam("tax_code","");
        $created_at=Yii::app()->db->createCommand()
                ->select("bill_input.created_at")
                ->from("bill_input")
                ->join("branch", "branch.id=bill_input.branch_id")
                ->where("branch.tax_code='$tax_code'")
                ->andWhere("bill_number='$bill_number'")
                ->queryScalar();
        if($created_at!=FALSE&&$created_at!=""){
            $temp=  explode(" ", $created_at);
            $created_at=$temp[0];
            $created_at=  explode("-", $created_at);
            echo "Ngày ".$created_at[2]." tháng ".$created_at[1]." năm ".$created_at[0];
        }
        else{
            echo '';
        }
        Yii::app()->end();
    }   
    public function actionCheckbillinputnumberexistupdate() {                
        $id=Yii::app()->request->getParam("id","");
        $bill_number=Yii::app()->request->getParam("bill_number","");
        $tax_code=Yii::app()->request->getParam("tax_code","");
        $created_at=Yii::app()->db->createCommand()
                ->select("bill_input.created_at")
                ->from("bill_input")
                ->join("branch", "branch.id=bill_input.branch_id")
                ->where("branch.tax_code='$tax_code'")
                ->andWhere("bill_number='$bill_number'")
                ->andWhere("bill_input.id<>$id")
                ->queryScalar();
        if($created_at!=FALSE&&$created_at!=""){
            $temp=  explode(" ", $created_at);
            $created_at=$temp[0];
            $created_at=  explode("-", $created_at);
            echo "Ngày ".$created_at[2]." tháng ".$created_at[1]." năm ".$created_at[0];
        }
        else{
            echo '';
        }
        
        Yii::app()->end();
    }   
    public function actionCheckbillinputnumberexist1() {                
        $id=Yii::app()->request->getParam("id","");
        $model=new BillChiphi();        
        if($id!=""){
            if(is_numeric($id)){                
                $model= BillChiphi::model()->findByPk($id);
            }           
        } 
        else{
            $model->setIsNewRecord(true);
        }        
        $model->bill_number=Yii::app()->request->getParam("bill_number","");        
        $error=CActiveForm::validate($model);
        if($error!='[]'){
            echo $error;            
        }   
        else{
            echo '';            
        }
        Yii::app()->end();
    }   
    /**
     * active một kiểu hiển thị ngày tháng cho hệ thống     
     */
    public function actionSavedateformat() {        
        $date_format_id=Yii::app()->request->getParam("date_format_id",1);
        Yii::app()->db->createCommand()->update("date_format", array('active'=>0));
        Yii::app()->db->createCommand()->update("date_format", array('active'=>1),"id=$date_format_id");
        Yii::app()->end();
    }   
    /**
     * khi user click vào menu item trong menu để link đến các page: hàng hóa nhập kho, khách hàng,...
     * thi reset lại 5 session: start_date_common, end_date_common, customer_id_common, goods_id_common, all_time_common
     * xem như user chưa từng search
     */
    public function actionResetfilter() {     
        $DATE_FORMAT = Yii::app()->session['date_format'];
        if ($DATE_FORMAT == 'Y.m.d') {
            $value = date("Y.m.01");
        } elseif ($DATE_FORMAT == 'Y-m-d') {
            $value = date("Y-m-01");
        } elseif ($DATE_FORMAT == 'Y/m/d') {
            $value = date("Y/m/01");
        } elseif ($DATE_FORMAT == 'Ymd') {
            $value = date("Ym01");
        } 
        Yii::app()->session['start_date_common']=$value;
        Yii::app()->session['end_date_common']=date($DATE_FORMAT);
        Yii::app()->session['customer_id_common']= "";
        Yii::app()->session['goods_id_common']="";
        Yii::app()->session['all_time_common']='0';
        Yii::app()->session['goodsleft_equal_0']='0';
        Yii::app()->session['not_buy']='0';
        Yii::app()->end();
    }   
    /**
     * thiết lập đường dẫn thư muc lưu hóa đơn  
     */
    public function actionSavepathforsavebill() {        
        $path_for_save_bill=Yii::app()->request->getParam("path","");
        $error='';
        
        if(trim($path_for_save_bill)==''){
            $error='Thư mục lưu hóa đơn không được rỗng.';
        }
        else if(strpos($path_for_save_bill,"\\")!==FALSE){
            $error='Thư mục lưu hóa đơn không được chứa kí tự "\".';
        }
        if($error!=""){
            echo $error;            
            Yii::app()->end();
        }
        Yii::app()->db->createCommand()->update("save_bill_path", array('path'=>trim($path_for_save_bill)));        
        Yii::app()->end();
    }   
    /**
     * thiết lập phương thức làm tròn số của tổng tiền trong hóa đơn  
     */
    public function actionSavecalculateway() {        
        $calculate_way=Yii::app()->request->getParam("way",1);
        Yii::app()->db->createCommand()->update("calculate_way", array('way'=>$calculate_way));        
        Yii::app()->end();
    }  
    public function actionGetthuchi() {
        $id = Yii::app()->request->getParam("id", "0");
        $row=Yii::app()->db->createCommand()->select()->from("thuchi")->where("id=$id")->queryRow();
        $DATE_FORMAT = Yii::app()->session['date_format'];
        $temp = explode(" ", $row['created_at']);
        $row['created_at'] = $temp[0];
        if ($DATE_FORMAT == 'Y-m-d') {
            $row['created_at'] = implode("-", explode("-", $row['created_at']));
        } elseif ($DATE_FORMAT == 'Y/m/d') {
            $row['created_at'] = implode("/", explode("-", $row['created_at']));
        } elseif ($DATE_FORMAT == 'Ymd') {
            $row['created_at'] = implode("", explode("-", $row['created_at']));
        } else {
            $row['created_at'] = implode(".", explode("-", $row['created_at']));
        }
        echo CJSON::encode($row);
        Yii::app()->end();
    }
    public function actionGetsocai() {
        $id = Yii::app()->request->getParam("id", "0");
        $row=Yii::app()->db->createCommand()->select()->from("socai")->where("id=$id")->queryRow();
        $DATE_FORMAT = Yii::app()->session['date_format'];
        $temp = explode(" ", $row['created_at']);
        $row['created_at'] = $temp[0];
        if ($DATE_FORMAT == 'Y-m-d') {
            $row['created_at'] = implode("-", explode("-", $row['created_at']));
        } elseif ($DATE_FORMAT == 'Y/m/d') {
            $row['created_at'] = implode("/", explode("-", $row['created_at']));
        } elseif ($DATE_FORMAT == 'Ymd') {
            $row['created_at'] = implode("", explode("-", $row['created_at']));
        } else {
            $row['created_at'] = implode(".", explode("-", $row['created_at']));
        }
        if(is_numeric($row['bill_id'])){
            $sum=Yii::app()->db->createCommand()
                    ->select("sum(thu) as sum")
                    ->from("socai")
                    ->where("bill_id=".$row['bill_id']." and id<>$id")
                    ->queryScalar();
            $row1=Yii::app()->db->createCommand()
                    ->select("*")
                    ->from("bill")
                    ->where("id=".$row['bill_id'])
                    ->queryRow();
            if($sum==FALSE||$sum==''){
                $sum=0;
            }
            $row['so_tien_con_lai']=$row1['sum_and_sumtax']-$sum;
            $row['giao_dich_id']=$row['id'];
        }
        else if(is_numeric($row['sxdv_id'])){
            $sum=Yii::app()->db->createCommand()
                    ->select("sum(thu) as sum")
                    ->from("socai")
                    ->where("sxdv_id=".$row['sxdv_id']." and id<>$id")
                    ->queryScalar();
            $row1=Yii::app()->db->createCommand()
                    ->select("*")
                    ->from("sxdv")
                    ->where("id=".$row['sxdv_id'])
                    ->queryRow();
            if($sum==FALSE||$sum==''){
                $sum=0;
            }
            $row['so_tien_con_lai']=$row1['sum_and_sumtax']-$sum;
            $row['giao_dich_id']=$row['id'];
        }
        else if(is_numeric($row['kxhd_id'])){
            $sum=Yii::app()->db->createCommand()
                    ->select("sum(thu) as sum")
                    ->from("socai")
                    ->where("kxhd_id=".$row['kxhd_id']." and id<>$id")
                    ->queryScalar();
            $row1=Yii::app()->db->createCommand()
                    ->select("*")
                    ->from("kxhd")
                    ->where("id=".$row['kxhd_id'])
                    ->queryRow();
            if($sum==FALSE||$sum==''){
                $sum=0;
            }
            $row['so_tien_con_lai']=$row1['sum_and_sumtax']-$sum;
            $row['giao_dich_id']=$row['id'];
        }
        else if(is_numeric($row['lai_suat_id'])){
            $sum=Yii::app()->db->createCommand()
                    ->select("sum(thu) as sum")
                    ->from("socai")
                    ->where("lai_suat_id=".$row['lai_suat_id']." and id<>$id")
                    ->queryScalar();
            $row1=Yii::app()->db->createCommand()
                    ->select("*")
                    ->from("lai_suat")
                    ->where("id=".$row['lai_suat_id'])
                    ->queryRow();
            if($sum==FALSE||$sum==''){
                $sum=0;
            }
            $row['so_tien_con_lai']=$row1['sum_and_sumtax']-$sum;
            $row['giao_dich_id']=$row['id'];
        }
        else if(is_numeric($row['bill_input_id'])){
            $row1=Yii::app()->db->createCommand()
                    ->select("*")
                    ->from("bill_input")
                    ->where("id=".$row['bill_input_id'])
                    ->queryRow();
            if($row1['gia_tri_hang_hoa_vnd']==''){
                $row1['gia_tri_hang_hoa_vnd']=0;
            }
            if($row1['chi_phi_ngan_hang_vnd']==''){
                $row1['chi_phi_ngan_hang_vnd']=0;
            }
            if($row1['tien_thue_vnd']==''){
                $row1['tien_thue_vnd']=0;
            }
            if(is_numeric($row['payment_method_id3'])){
                $sum=Yii::app()->db->createCommand()
                    ->select("sum(chi) as sum")
                    ->from("socai")
                    ->where("bill_input_id=".$row['bill_input_id']." and id<>$id and payment_method_id3 is not null")
                    ->queryScalar();
                if($sum==FALSE||$sum==''){
                    $sum=0;
                }
                $row['so_tien_con_lai']=$row1['gia_tri_hang_hoa_vnd']-$sum;
            }
            else if(is_numeric($row['payment_method_id4'])){
                $sum=Yii::app()->db->createCommand()
                    ->select("sum(chi) as sum")
                    ->from("socai")
                    ->where("bill_input_id=".$row['bill_input_id']." and id<>$id and payment_method_id4 is not null")
                    ->queryScalar();
                if($sum==FALSE||$sum==''){
                    $sum=0;
                }
                $row['so_tien_con_lai']=$row1['chi_phi_ngan_hang_vnd']-$sum;
            }
            else if(is_numeric($row['payment_method_id5'])){
                $sum=Yii::app()->db->createCommand()
                    ->select("sum(chi) as sum")
                    ->from("socai")
                    ->where("bill_input_id=".$row['bill_input_id']." and id<>$id and payment_method_id5 is not null")
                    ->queryScalar();
                if($sum==FALSE||$sum==''){
                    $sum=0;
                }
                $row['so_tien_con_lai']=$row1['tien_thue_vnd']-$sum;
            }
            else{
                $sum=Yii::app()->db->createCommand()
                    ->select("sum(chi) as sum")
                    ->from("socai")
                    ->where("bill_input_id=".$row['bill_input_id']." and id<>$id")
                    ->queryScalar();
                if($sum==FALSE||$sum==''){
                    $sum=0;
                }
                $row['so_tien_con_lai']=$row1['sum_and_sumtax']-$sum;
            }    
            
            $row['giao_dich_id']=$row['id'];
        }
        else if(is_numeric($row['bill_chi_phi_id'])){
            $sum=Yii::app()->db->createCommand()
                    ->select("sum(chi) as sum")
                    ->from("socai")
                    ->where("bill_chi_phi_id=".$row['bill_chi_phi_id']." and id<>$id")
                    ->queryScalar();
            $row1=Yii::app()->db->createCommand()
                    ->select("*")
                    ->from("bill_chi_phi")
                    ->where("id=".$row['bill_chi_phi_id'])
                    ->queryRow();
            if($sum==FALSE||$sum==''){
                $sum=0;
            }
            $row['so_tien_con_lai']=$row1['sum_and_sumtax']-$sum;
            $row['giao_dich_id']=$row['id'];
        }
        else if(is_numeric($row['chi_phi_khd_id'])){
            $sum=Yii::app()->db->createCommand()
                    ->select("sum(chi) as sum")
                    ->from("socai")
                    ->where("chi_phi_khd_id=".$row['chi_phi_khd_id']." and id<>$id")
                    ->queryScalar();
            $row1=Yii::app()->db->createCommand()
                    ->select("*")
                    ->from("chi_phi_khd")
                    ->where("id=".$row['chi_phi_khd_id'])
                    ->queryRow();
            if($sum==FALSE||$sum==''){
                $sum=0;
            }
            $row['so_tien_con_lai']=$row1['sum_and_sumtax']-$sum;
            $row['giao_dich_id']=$row['id'];
        }
        echo CJSON::encode($row);
        Yii::app()->end();
    }
    public function actionGetungtien() {
        $id = Yii::app()->request->getParam("id", "0");
        $row=Yii::app()->db->createCommand()->select()->from("ung_tien")->where("id=$id")->queryRow();
        $DATE_FORMAT = Yii::app()->session['date_format'];
        $temp = explode(" ", $row['created_at']);
        $row['created_at'] = $temp[0];
        if ($DATE_FORMAT == 'Y-m-d') {
            $row['created_at'] = implode("-", explode("-", $row['created_at']));
        } elseif ($DATE_FORMAT == 'Y/m/d') {
            $row['created_at'] = implode("/", explode("-", $row['created_at']));
        } elseif ($DATE_FORMAT == 'Ymd') {
            $row['created_at'] = implode("", explode("-", $row['created_at']));
        } else {
            $row['created_at'] = implode(".", explode("-", $row['created_at']));
        }
        echo CJSON::encode($row);
        Yii::app()->end();
    }
    public function actionGetnghiphep() {
        $id = Yii::app()->request->getParam("id", "0");
        $row=Yii::app()->db->createCommand()->select()->from("nghiphep")->where("id=$id")->queryRow();
        $DATE_FORMAT = Yii::app()->session['date_format'];
        $temp = explode(" ", $row['start_date']);
        $row['start_date'] = $temp[0];
        $temp = explode(" ", $row['end_date']);
        $row['end_date'] = $temp[0];
        if ($DATE_FORMAT == 'Y-m-d') {
            $row['start_date'] = implode("-", explode("-", $row['start_date']));
            $row['end_date'] = implode("-", explode("-", $row['end_date']));
        } elseif ($DATE_FORMAT == 'Y/m/d') {
            $row['start_date'] = implode("/", explode("-", $row['start_date']));
            $row['end_date'] = implode("/", explode("-", $row['end_date']));
        } elseif ($DATE_FORMAT == 'Ymd') {
            $row['start_date'] = implode("", explode("-", $row['start_date']));
            $row['end_date'] = implode("", explode("-", $row['end_date']));
        } else {
            $row['start_date'] = implode(".", explode("-", $row['start_date']));
            $row['end_date'] = implode(".", explode("-", $row['end_date']));
        }
        if($row['so_ngay_nghi']!=''){
            if(intval($row['so_ngay_nghi'])==$row['so_ngay_nghi']){
                $row['so_ngay_nghi']=  intval($row['so_ngay_nghi']);
            }
            else{
                $row['so_ngay_nghi']=  str_replace(".", ",", $row['so_ngay_nghi']);
            }
        }
        echo CJSON::encode($row);
        Yii::app()->end();
    }
    /**
     * get 1 hàng hóa
     */    
    public function actionGetgoods() {
        /**
         * thông tin hàng hóa được lấy qua param goods_group_id này là để nhúng thêm item vào combobox hàng hóa tại các page create/update hóa đơn nhập kho/hóa đơn bán hàng/tờ khai
         */
        $goods_group_id = Yii::app()->request->getParam("goods_group_id", "");
        /**
         * thông tin hàng hóa được lấy qua param id này là để
         *             edit hàng hóa
         *             thay đổi thuế xyz khi chọn các đơn vị khác nhau của cùng một hàng hóa tại các page create/update hóa đơn nhập kho/hóa đơn bán hàng/tờ khai
         */
        $id = Yii::app()->request->getParam("id", "");
        $select=Yii::app()->db->createCommand()->select();
        $select->from("goods");
        if($goods_group_id!=""){
            $select->where("group_id=$goods_group_id");
        }
        else{
            $select->where("id=$id");            
        }        
        $row = $select->queryRow();
             
        
        if(is_array($row)&&count($row)>0){
            /**
             * nếu thông tin hàng hóa được lấy qua param id
             * thi lấy thêm thông tin số lượng còn lại của hàng đó trong kho
             */
            if($goods_group_id==''){
                $quantity_left=Yii::app()->db->createCommand()->select("quantity_left")->from("goods_left")->where("id=$id")->queryScalar();
                $row['quantity_left']=$quantity_left;
            }   
            echo CJSON::encode($row);
        }
        else{
            echo '';
        }
        
        Yii::app()->end();
    }
    /**
     * get 1 ký hiệu hóa đơn
     */    
    public function actionGetbillsigninfo() {        
        $id = Yii::app()->request->getParam("bill_type_id", 0);
        if(trim($id)==""){
            echo '';
            Yii::app()->end();
        }
        $date_format=  FunctionCommon::convertDateForDBSelect();
        $row=Yii::app()->db->createCommand()
                ->select(array(
                    'id',
                    'sign',
                    'init_bill_number',
                    'current_bill_number',
                    "date_format(start_date,'$date_format') AS start_date",
                    'mau_so',                    
                ))
                ->from("bill_sign")->where("bill_type_id=$id")->queryRow();
        if(is_array($row)&&count($row)>0){ 
            /**
             * current_bill_number trong db phai tăng lên 1 đơn vị mỗi khi create một hóa đơn bán hàng
             * do đó nếu hóa đơn bán hàng đã bắt đầu đi vào sử dụng thi current_bill_number phai khác với init_bill_number
             * 
             * nếu hóa đơn đã đi vào sử dụng thi không được sửa số hóa đơn bắt đầu nữa 
             * thông qua key allow_edit_init_bill_number thi tại view se disabled hay enable textbox số hóa đơn bắt đầu
             *             
             */
            if($row['init_bill_number']!=$row['current_bill_number']){
                $row['allow_edit_init_bill_number']='0';
            }
            else{
                $row['allow_edit_init_bill_number']='1';
            }
            echo CJSON::encode($row);
        }
        else{
            echo '';
        }        
        Yii::app()->end();
    }
    /**
     * lưu thông tin dc setting tại một mẫu hóa đơn nào đó
     */
    public function actionSavebillsign() {        
        $id=Yii::app()->request->getParam("id","");
        //
        $model=new BillSign();        
        if($id!=""){
            if(!is_numeric($id)){                
                Yii::app()->end();
            }
            $model=  BillSign::model()->findByPk($id);
        } 
        else{
            $model->setIsNewRecord(true);
        }
         
        $model->sign=Yii::app()->request->getParam("sign","");
        $model->bill_type_id=Yii::app()->request->getParam("bill_type_id","");
        $model->init_bill_number=Yii::app()->request->getParam("init_bill_number","");        
        $model->start_date=Yii::app()->request->getParam("start_date","");
        $model->mau_so=Yii::app()->request->getParam("mau_so","");

        
        $error=CActiveForm::validate($model);

        if($error!='[]'){
            echo $error;
            Yii::app()->end();
        }   
        $is_submit=Yii::app()->request->getParam("is_submit","0");
        if($is_submit=='1'){
            $model->save(FALSE);
            Yii::app()->session['sign']=$model->sign;
            Yii::app()->session['mau_so']=$model->mau_so;
            
        }
        
        echo '';
        Yii::app()->end();
    }  
    /**
     * get thông tin giá bán của 1 hàng hóa bất kỳ tại nhiều hóa đơn bán hàng khác nhau
     */    
    public function actionGetgoodspricelist() {       
        $id = Yii::app()->request->getParam("id", 0);        
        $rows=Yii::app()->db->createCommand()
                ->select(
                        array(
                            "bill_detail.bill_id",                            
                            "replace(format(bill_detail.price_has_tax,0),',','.') AS price_has_tax",
                            )
                        )
                ->from("goods")
                ->join("bill_detail", "bill_detail.goods_id=goods.id")
                ->where("goods.id=$id")
                ->queryAll();
        
        $this->renderPartial('getgoodspricelist', 
                array(                    
                    'rows'=>$rows,                    
                )
        );                
        Yii::app()->end();
    }
    /**
     * get thông tin giá bán của 1 hàng hóa bất kỳ tại nhiều HÓA ĐƠN NHẬP KHO/TỜ KHAI khác nhau
     */  
    public function actionGetgoodsinputpricelist() {       
        $id = Yii::app()->request->getParam("id", 0);        
        $rows=Yii::app()->db->createCommand()
                ->select(
                        array(
                            "bill_input_detail.bill_id",                            
                            "replace(format(bill_input_detail.price_has_tax,0),',','.') AS price_has_tax",
                            "bill_input_detail.is_international as is_international",
                            )
                        )
                ->from("goods")
                ->join("bill_input_detail", "bill_input_detail.goods_id=goods.id")
                ->where("goods.id=$id")
                ->queryAll();
        $this->renderPartial('getgoodsinputpricelist', 
                array(                    
                    'rows'=>$rows,                    
                )
        );                
        Yii::app()->end();
    }
    /**
     * get thông tin giá bán của 1 hàng hóa bất kỳ tại nhiều hóa đơn nhập khác nhau (các hóa đơn còn lại sau khi đã bán)
     */  
    public function actionGetgoodsleftpricelist() {       
        $id = Yii::app()->request->getParam("id", 0);  
        /**
         * lấy thông tin từ các hóa đơn nhập hàng lẫn tờ khai
         */
        $rows=Yii::app()->db->createCommand()
                ->select(
                        array(
                            "bill_input_detail.bill_id",                            
                            "replace(format(bill_input_detail.price_has_tax,0),',','.') AS price_has_tax",
                            "bill_input_detail.is_international as is_international",
                            )
                        )
                ->from("goods")
                ->join("bill_input_detail", "bill_input_detail.goods_id=goods.id")
                ->where("goods.id=$id")
                ->queryAll();
        /**
         * lấy tổng số lượng đã nhập từ tat cả các hóa đơn nhập hàng lẫn tờ khai
         */        
        $quantity_sum=Yii::app()->db->createCommand()
                ->select("sum(quantity) as sum")
                ->from("bill_detail")
                ->where("goods_id=$id")
                ->queryScalar();        
        if($quantity_sum==NULL||$quantity_sum==FALSE){
            $quantity_sum=0;
        }
        /**
         * lấy thông tin từ các hóa đơn bán hàng
         */
        $bill_input_detail=Yii::app()->db->createCommand()
                ->select("bill_input_detail.quantity,bill_input_detail.price_has_tax,bill_input.branch_id,bill_input.id")
                ->from("bill_input_detail")
                ->join("bill_input", "bill_input.id=bill_input_detail.bill_id")
                ->where("goods_id=$id")
                ->order("bill_input.created_at ASC")
                ->queryAll();
        /**
         * loại bớt thông tin từ các hóa đơn nhập hàng lẫn tờ khai
         * khi hàng này đã bán từ các hóa đơn bán hàng
         */
        for($i=0,$n=count($bill_input_detail);$i<$n;$i++){
            if($quantity_sum>=$bill_input_detail[$i]['quantity']){
                $quantity_sum-=$bill_input_detail[$i]['quantity'];                                
                foreach ($rows as $key => $value) {
                    if($value['bill_id']==$bill_input_detail[$i]['id']){
                        unset($rows[$key]);
                    }
                }
            }
            else{                
                break;
            }            
        }      
        $this->renderPartial('getgoodsleftpricelist', 
                array(                    
                    'rows'=>$rows,                    
                )
        );                
        Yii::app()->end();
    }
    /**
     * get tất cả hàng hóa
     */
    public function actionGetallgoods() {        
        $for_bill=Yii::app()->request->getParam("for_bill",'0');//cái này dùng để xác định, lấy danh sách hàng hóa để user chọn hàng hóa cho việc bán hàng hay nhập kho (tai các page update/create hóa đơn nhập kho/bán hàng)
        $goods=  Goods::getAllGoods($for_bill);      
        if(is_array($goods)&&count($goods)>0){            
            $this->renderPartial('getallgoods',array('goods'=>$goods));
        }
        else{
            echo '';
        }
        
        Yii::app()->end();
    }
    public function actionGetallgoods1() {        
        $this->renderPartial('getallgoods1');        
        Yii::app()->end();
    }
    
    /**
     * get tất cả hàng hóa nhập khẩu
     */
    public function actionGetallgoodsimport() {                
        $goods= Goods::get_all_import_goods();
        if(is_array($goods)&&count($goods)>0){            
            $this->renderPartial('getallgoodsimport',array('goods'=>$goods));
        }
        else{
            echo '';
        }        
        Yii::app()->end();
    }
    /**
     * tạo file image hóa đơn và lưu vào thư mục đã được admin setting
     */
    private function save_root($data){
//        $root_path=Yii::app()->params['PATH_ROOT'].Yii::app()->session['path_for_save_bill'];
        $root_path=Yii::app()->params['PATH_ROOT']."/".date("Y")."/";
        /**
         * tạo folder invoice
         */
        if (!file_exists($root_path)) {
//            return;
            if(mkdir($root_path)==FALSE){
                return ;
            }
        }
        /**
         * tạo folder invoice/bill_number
         */
        $bill_number = $_POST['bill_number'];
        $lien = $_POST['lien'];
        if (!file_exists($root_path.$bill_number)) {
            mkdir($root_path.$bill_number);
        }
        /**
         * tạo folder invoice/bill_number/lien1_Or_lien2
         */
        if (!file_exists($root_path.$bill_number."/lien$lien")) {
            mkdir($root_path.$bill_number."/lien$lien");
        }
        /**
         * tạo file image invoice/bill_number/lien1_Or_lien2/file_name.png
         */
        $file_name = $root_path.$bill_number."/lien$lien/mns_$bill_number"."_" . date("Ymd_His") . ".png";
        fopen($file_name, "a");
        file_put_contents($file_name, $data);
    }

    /**
     * tạo file image hóa đơn và lưu tạm vào thư mục invoice, sau đó xóa đi
     */
    public function actionPrint() {
        if (empty($_POST['content']) || empty($_POST['bill_number']) || empty($_POST['lien'])) {
            Yii::app()->end();
        }
        /**
         * get base64 của image
         */
        $str = "data:image/png;base64,";
        $data = str_replace($str, "", $_POST['content']);
        $data = base64_decode($data);
        $this->save_root($data);
        /**
         * tạo folder invoice
         */
        if (!file_exists("invoice")) {
            mkdir("invoice");
        }
        /**
         * tạo folder invoice/bill_number
         */
        $bill_number = $_POST['bill_number'];
        $lien = $_POST['lien'];
        if (!file_exists("invoice/$bill_number")) {
            mkdir("invoice/$bill_number");
        }
        /**
         * tạo folder invoice/bill_number/lien1_Or_lien2
         */
        if (!file_exists("invoice/$bill_number/lien$lien")) {
            mkdir("invoice/$bill_number/lien$lien");
        }
        /**
         * tạo file image invoice/bill_number/lien1_Or_lien2/file_name.png
         */
        $file_name = "invoice/$bill_number/lien$lien/mns_$bill_number"."_" . date("Ymd_His") . ".png";
        fopen($file_name, "a");
        file_put_contents($file_name, $data);

        Yii::app()->session['file_name']=$file_name;        
        Yii::app()->end();
    }
    /**
     * get thông tin lịch sử hóa đơn của một lần sửa
     */
    public function actionGetbillhistoryupdate() {
        $id = Yii::app()->request->getParam("id", "");
        if ($id == "" || !is_numeric($id)) {
            echo "";
            Yii::app()->end();
        }
        $row = Yii::app()->db->createCommand()
                ->select("reason,data")
                ->from("bill_history")
                ->where("id=$id")
                ->queryRow();
        if ($row == FALSE) {
            echo "";
            Yii::app()->end();
        }
        $curent_data = CJSON::decode($row['data']);
        $bill_details = $curent_data['bill_detail'];
        for ($i = 0; $i < count($bill_details); $i++) {
            $row1 = Yii::app()->db->createCommand()
                    ->select()
                    ->from("goods")
                    ->where("id=" . $bill_details[$i]['goods_id'])
                    ->queryRow();
            $bill_details[$i]['tax'] = $row1['tax'];
            $bill_details[$i]['goods_full_name'] = $row1['goods_full_name'];
            $bill_details[$i]['unit_full_name'] = $row1['unit_full_name'];
        }
        $invoicefull_model = $curent_data['bill'];
        $this->renderPartial('getbillhistoryupdate', 
                array(
                    'invoicefull_model'=>$invoicefull_model,
                    'bill_details'=>$bill_details,
                    'row'=>$row,
                    'is_input'=>'0',
                    
                )
        );
        Yii::app()->end();
    }
    /**
     * get thông tin lịch sử hóa đơn của một lần sửa
     */
    public function actionGetbillhistoryupdate1() {
        $id = Yii::app()->request->getParam("id", "");
        if ($id == "" || !is_numeric($id)) {
            echo "";
            Yii::app()->end();
        }
        $row = Yii::app()->db->createCommand()
                ->select("reason,data")
                ->from("sxdv_history")
                ->where("id=$id")
                ->queryRow();
        if ($row == FALSE) {
            echo "";
            Yii::app()->end();
        }
        $curent_data = CJSON::decode($row['data']);
        $bill_details = $curent_data['bill_detail'];

        $invoicefull_model = $curent_data['bill'];
        $this->renderPartial('getbillhistoryupdate1', 
                array(
                    'invoicefull_model'=>$invoicefull_model,
                    'bill_details'=>$bill_details,
                    'row'=>$row,
                    'is_input'=>'0',
                    
                )
        );
        Yii::app()->end();
    }
    /**
     * get thời gian lịch sử in hóa đơn
     */
    public function actionGettimeprinthistory() {
        $bill_id = Yii::app()->request->getParam("bill_id", 0);
        $print_type = Yii::app()->request->getParam("print_type", 1);        
        if (!is_numeric($bill_id)||!is_numeric($print_type)) {
            echo "";
            Yii::app()->end();
        }        
        $DATE_FORMAT=  FunctionCommon::convertDateForDBSelect();
        $printed_at_date_array = Yii::app()->db->createCommand()
                ->select("date_format(bill_history.printed_at,'$DATE_FORMAT - %H:%i:%s') AS printed_at_date")
                ->from("bill_history")
                ->where("bill_id=$bill_id and print_type=$print_type and is_preview = 0 and printed_at is not null")
                ->order("bill_history.id ASC")
                ->queryAll()
        ; 
        
        if (!is_array($printed_at_date_array)||count($printed_at_date_array)==0) {
            echo "";
            Yii::app()->end();
        }
        
        $this->renderPartial('gettimeprinthistory', 
                array(
                    'printed_at_date_array'=>$printed_at_date_array,                    
                )
        );
        Yii::app()->end();
    }
    /**
     * get thời gian lịch sử in hóa đơn
     */
    public function actionGettimeprinthistory1() {
        $bill_id = Yii::app()->request->getParam("bill_id", 0);
        $print_type = Yii::app()->request->getParam("print_type", 1);        
        if (!is_numeric($bill_id)||!is_numeric($print_type)) {
            echo "";
            Yii::app()->end();
        }        
        $DATE_FORMAT=  FunctionCommon::convertDateForDBSelect();
        $printed_at_date_array = Yii::app()->db->createCommand()
                ->select("date_format(sxdv_history.printed_at,'$DATE_FORMAT - %H:%i:%s') AS printed_at_date")
                ->from("sxdv_history")
                ->where("bill_id=$bill_id and print_type=$print_type and is_preview = 0 and printed_at is not null")
                ->order("sxdv_history.id ASC")
                ->queryAll()
        ; 
        
        if (!is_array($printed_at_date_array)||count($printed_at_date_array)==0) {
            echo "";
            Yii::app()->end();
        }
        
        $this->renderPartial('gettimeprinthistory', 
                array(
                    'printed_at_date_array'=>$printed_at_date_array,                    
                )
        );
        Yii::app()->end();
    }
    /**
     * get thông tin lịch sử hóa đơn của một lần sửa
     */
    public function actionGetbillinputhistoryupdate() {
        $id = Yii::app()->request->getParam("id", "");
        if ($id == "" || !is_numeric($id)) {
            echo "";
            Yii::app()->end();
        }
        $row = Yii::app()->db->createCommand()
                ->select("reason,data")
                ->from("bill_input_history")
                ->where("id=$id")
                ->queryRow();
        if ($row == FALSE) {
            echo "";
            Yii::app()->end();
        }
        $curent_data = CJSON::decode($row['data']);
        $bill_details = $curent_data['bill_detail'];
        for ($i = 0; $i < count($bill_details); $i++) {
            $row1 = Yii::app()->db->createCommand()
                    ->select()
                    ->from("goods")
                    ->where("id=" . $bill_details[$i]['goods_id'])
                    ->queryRow();
            $bill_details[$i]['tax'] = $row1['tax'];
            $bill_details[$i]['goods_full_name'] = $row1['goods_full_name'];
            $bill_details[$i]['unit_full_name'] = $row1['unit_full_name'];
        }
        $invoicefull_model = $curent_data['bill'];
        $this->renderPartial('getbillhistoryupdate', 
                array(
                    'invoicefull_model'=>$invoicefull_model,
                    'bill_details'=>$bill_details,
                    'row'=>$row,
                    'is_input'=>'1',
                    
                )
        );
        Yii::app()->end();
    }
    /**
     * get thông tin lịch sử hóa đơn của một lần sửa
     */
    public function actionGetbillimporthistoryupdate() {
        $id = Yii::app()->request->getParam("id", "");
        if ($id == "" || !is_numeric($id)) {
            echo "";
            Yii::app()->end();
        }
        $row = Yii::app()->db->createCommand()
                ->select("reason,data")
                ->from("bill_input_history")
                ->where("id=$id")
                ->queryRow();
        if ($row == FALSE) {
            echo "";
            Yii::app()->end();
        }
        $curent_data = CJSON::decode($row['data']);
        $bill_details = $curent_data['bill_detail'];
        for ($i = 0; $i < count($bill_details); $i++) {
            $row1 = Yii::app()->db->createCommand()
                    ->select()
                    ->from("goods")
                    ->where("id=" . $bill_details[$i]['goods_id'])
                    ->queryRow();
            $bill_details[$i]['tax'] = $row1['tax'];
            $bill_details[$i]['thue_tieu_thu_dac_biet'] = $row1['thue_tieu_thu_dac_biet'];
            $bill_details[$i]['thue_nhap_khau'] = $row1['thue_nhap_khau'];
            $bill_details[$i]['goods_full_name'] = $row1['goods_full_name'];
            $bill_details[$i]['unit_full_name'] = $row1['unit_full_name'];
            
            $price=  $bill_details[$i]['price'];
            $quantity=  $bill_details[$i]['quantity'];
            /**
             * tính toán lại các loại thuế
             */
            $sum_thue_tieu_thu_dac_biet=($price*$quantity*$row1['thue_tieu_thu_dac_biet'])/100;
            $bill_details[$i]['sum_thue_tieu_thu_dac_biet']= number_format($sum_thue_tieu_thu_dac_biet,0,',','.');        

            $sum_thue_nhap_khau=(($sum_thue_tieu_thu_dac_biet+$price*$quantity)*$row1['thue_nhap_khau'])/100;
            $bill_details[$i]['sum_thue_nhap_khau']= number_format($sum_thue_nhap_khau,0,',','.');

            $sum_tax=(($sum_thue_tieu_thu_dac_biet+$sum_thue_nhap_khau+$price*$quantity)*$row1['tax'])/100;
            $bill_details[$i]['sum_tax']= number_format($sum_tax,0,',','.');           
        }
        $invoicefull_model = $curent_data['bill'];
        $this->renderPartial('getbillimporthistoryupdate', 
                array(
                    'invoicefull_model'=>$invoicefull_model,
                    'bill_details'=>$bill_details,
                    'row'=>$row,
                    
                )
        );
        Yii::app()->end();
    }
    
    /**
     * 
     */
    public function actionGetbranchtaxcode() {     
        $param=Yii::app()->request->getParam("q");
        $param = str_replace("'", "\'", $param);        
        
        
        $units = Yii::app()->db->createCommand()
                ->select("tax_code,tax_code_chinhanh")
                ->from("branch")
                ->where("tax_code like '%$param%'")
                ->orWhere("tax_code_chinhanh like '%$param%'")
                ->queryAll();
        $temp = array();
        foreach ($units as $unit) {
            $temp[] = $unit['tax_code'].(($unit['tax_code_chinhanh']!='')?' - '.$unit['tax_code_chinhanh']:'');
        }
        if(is_array($temp)&&count($temp)>0){
            echo CJSON::encode($temp);
        }
        else{
            echo '';
        }
        Yii::app()->end();
    }
    
    
    /**
     * 
     */
    public function actionGetbranchinfo() {       
        $param = Yii::app()->request->getParam("tax_code", NULL);        
        if ($param == NULL) {
            $param = Yii::app()->request->getParam("full_name");
            $param = str_replace("'", "\'", $param);
            $where = "full_name='$param'";
        } else {            
            $param = str_replace("'", "\'", $param);
            $param=  explode(" - ", $param);
            if(count($param)==2){
                $where = "tax_code='".$param[0]."' and tax_code_chinhanh='".$param[1]."'";
            }
            else{
                $where = "tax_code='".$param[0]."'";
            }
            
        }

        $row = Yii::app()->db->createCommand()
                ->select("*")
                ->from("branch")
                ->where($where)
                ->queryRow();
        echo CJSON::encode($row);
        Yii::app()->end();
    }
    /**
     * 
     */
    public function actionGetbranchfullname() {        
        $units = Yii::app()->db->createCommand()
                ->select("full_name")
                ->from("branch")
                ->where("full_name like '%" . Yii::app()->request->getParam("q") . "%'")
                ->queryAll();
        $temp = array();
        foreach ($units as $unit) {
            $temp[] = $unit['full_name'];
        }
        if(is_array($temp)&&count($temp)>0){
            echo CJSON::encode($temp);
        }
        else{
            echo '';
        }
        
        Yii::app()->end();
    }
    /**
     * 
     */
    public function actionGetunits() {
        $goods_group_id = Yii::app()->request->getParam('goods_group_id');          
        $units = Yii::app()->db->createCommand()
                ->select("goods_left.quantity_left,goods.id,goods.unit_full_name,goods.tax,goods.thue_tieu_thu_dac_biet,goods.thue_nhap_khau")
                ->from("goods")
                ->join("goods_left", "goods_left.id=goods.id")
                ->where("group_id=$goods_group_id")
                ->queryAll();
        if(is_array($units)&&count($units)>0){
            echo CJSON::encode($units);
        }
        else{
            echo '';
        }
        
        Yii::app()->end();
    }
    /**
     * 
     */
    public function actionCheckerrorquantity() {
        $goods_ids = Yii::app()->request->getParam('goods_ids',array());          
        $quantities = Yii::app()->request->getParam('quantities',array());
        $bill_id = Yii::app()->request->getParam('bill_id',0);
        $error_quantity_array=array();
        for($i=0,$n=count($goods_ids);$i<$n;$i++){
            $quantity_left=Yii::app()->db->createCommand()->select("quantity_left")->from("goods_left")->where("id=".$goods_ids[$i])->queryScalar();
            $quantity_current=Yii::app()->db->createCommand()->select("quantity")->from("bill_detail")->where("bill_id=$bill_id and goods_id=".$goods_ids[$i])->queryScalar();
            if(str_replace(".", "", $quantities[$i])-$quantity_current>$quantity_left){
                $error_quantity_array[]=array('error_quantity'=>'1');
            }
            else{
                $error_quantity_array[]=array('error_quantity'=>'0');
            }
        }
        if(is_array($error_quantity_array)&&count($error_quantity_array)>0){
            echo CJSON::encode($error_quantity_array);
        }
        else{
            echo '';
        }
        
        Yii::app()->end();
    }
    public function actionCheckerrorquantity1() {
        $goods_ids = Yii::app()->request->getParam('goods_ids',array());          
        $quantities = Yii::app()->request->getParam('quantities',array());
        $thuchi_id = Yii::app()->request->getParam('thuchi_id',0);
        $error_quantity_array=array();
        for($i=0,$n=count($goods_ids);$i<$n;$i++){
            $quantity_left=Yii::app()->db->createCommand()->select("quantity_left")->from("goods_left")->where("id=".$goods_ids[$i])->queryScalar();
            $quantity_current=Yii::app()->db->createCommand()->select("quantity")->from("bill_detail")->where("thuchi_id=$thuchi_id and goods_id=".$goods_ids[$i])->queryScalar();
            if(str_replace(".", "", $quantities[$i])-$quantity_current>$quantity_left){
                $error_quantity_array[]=array('error_quantity'=>'1');
            }
            else{
                $error_quantity_array[]=array('error_quantity'=>'0');
            }
        }
        if(is_array($error_quantity_array)&&count($error_quantity_array)>0){
            echo CJSON::encode($error_quantity_array);
        }
        else{
            echo '';
        }
        
        Yii::app()->end();
    }
    /**
     * 
     */
    public function actionCheckerrorquantityonegoods() {
        $goods_id = Yii::app()->request->getParam('goods_id',array());          
        $quantity = Yii::app()->request->getParam('quantity',array());
        $bill_id = Yii::app()->request->getParam('bill_id',0);
        $error_quantity_array=array();
        $quantity_left=Yii::app()->db->createCommand()->select("quantity_left")->from("goods_left")->where("id=".$goods_id)->queryScalar();
        $quantity_current=Yii::app()->db->createCommand()->select("quantity")->from("bill_detail")->where("bill_id=$bill_id and goods_id=".$goods_id)->queryScalar();
        if(str_replace(".", "", $quantity)-$quantity_current>$quantity_left){
            $error_quantity_array=array('error'=>'1','quantity'=>$quantity_left+$quantity_current);
        }
        else{
            $error_quantity_array=array('error'=>'0','quantity'=>'');
        }
        echo CJSON::encode($error_quantity_array);        
        Yii::app()->end();
    }
    /**
     * 
     */
    public function actionCheckerrorquantityonegoods1() {
        $goods_id = Yii::app()->request->getParam('goods_id',array());          
        $quantity = Yii::app()->request->getParam('quantity',array());
        $thuchi_id = Yii::app()->request->getParam('thuchi_id',0);
        $error_quantity_array=array();
        $quantity_left=Yii::app()->db->createCommand()->select("quantity_left")->from("goods_left")->where("id=".$goods_id)->queryScalar();
        $quantity_current=Yii::app()->db->createCommand()->select("quantity")->from("bill_detail")->where("thuchi_id=$thuchi_id and goods_id=".$goods_id)->queryScalar();
        if(str_replace(".", "", $quantity)-$quantity_current>$quantity_left){
            $error_quantity_array=array('error'=>'1','quantity'=>$quantity_left+$quantity_current);
        }
        else{
            $error_quantity_array=array('error'=>'0','quantity'=>'');
        }
        echo CJSON::encode($error_quantity_array);        
        Yii::app()->end();
    }

    

}
