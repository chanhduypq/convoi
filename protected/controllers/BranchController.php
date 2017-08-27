<?php

class BranchController extends Controller {

    public function init() {
        parent::init();
    }
    /**
     * lưu khách hàng
     */
    public function actionSavebranch() {
        /**
         * save db
         */
        $id=Yii::app()->request->getParam("id","");
        //
        $model=new Branch();        
        if($id!=""){
            if(!is_numeric($id)){                
                Yii::app()->end();
            }
            $model=  Branch::model()->findByPk($id);
        } 
        else{
            $model->setIsNewRecord(true);
        }
        $model->tax_code=Yii::app()->request->getParam("tax_code","");
        $model->tax_code_chinhanh=Yii::app()->request->getParam("tax_code_chinhanh","");
        $model->address=Yii::app()->request->getParam("address","");
        $model->email=Yii::app()->request->getParam("email","");
        $model->phone=Yii::app()->request->getParam("phone","");
        $model->first_name=Yii::app()->request->getParam("first_name","");
        $model->last_name=Yii::app()->request->getParam("last_name","");
        $model->full_name=Yii::app()->request->getParam("full_name","");
        $model->short_hand_name=Yii::app()->request->getParam("short_hand_name","");
        if($model->getIsNewRecord()){
            $model->type=$model->type_init=Yii::app()->request->getParam("type",1);
        }        
        $model->full_name_for_unique_validate=  FunctionCommon::getStringForValidate(Yii::app()->request->getParam("full_name",""));
        /**
         * với nhà cung ứng quốc tế, MST không bắt buộc
         * do đó, trong trường hợp đang create hoặc edit nhà cung ứng quốc tế
         * nếu user không nhập tax_code thi set tax_code="111" để validate không báo lỗi mục MST này
         */
        if((trim($model->tax_code)==""||!is_numeric(trim($model->tax_code)))&&Yii::app()->request->getParam("is_international","0")=='1'){
            $model->tax_code="111";
        }
       
        
        $error=CActiveForm::validate($model);
        if($error!='[]'){
            
            $error=  CJSON::decode($error);
            /**
             * nếu user chỉ nhập những khoảng trắng với mã số thuế 
             * thi rules trong model Branch sẽ bật lên 2 lỗi: rỗng và không đúng quy định (vi quy định nhập mã số thuế chỉ dc nhập các chữ số, khoảng trắng cũng không được cho phép)
             * do đó, nếu xảy ra trường hợp như vậy thi phai bỏ lỗi khong đúng quy định đi, chỉ hiển thị lỗi rỗng thôi
             */
            if(isset($error['Branch_tax_code'])&&count($error['Branch_tax_code'])==2){
                unset($error['Branch_tax_code'][1]);
            }
            /**
             * về mã số thuế: nếu user nhập 1 hoặc nhiều khoảng trắng liên tục, rồi sau đó bắt đầu gõ 1 hoặc nhiều chữ số mà không có khoảng trắng ở giữa
             * như vậy ve nguyên tắc rules trong model Branch vẫn hiểu là mã số thuế này đang có khoảng trắng và vẫn bật lên lỗi
             * trong khi user thấy vẫn đang hợp lý
             * do đó phai bỏ lỗi đó đi
             * 
             * tương tự với Phần mở rộng mã số thuế cũng thế
             * do đó phai làm điều này với cả hai
             */
//            if(isset($error['Branch_tax_code'])&&count($error['Branch_tax_code'])==1&&trim($model->tax_code)!=""&&  is_numeric(trim($model->tax_code))){
//                unset($error['Branch_tax_code']);
//            }
//            if(isset($error['Branch_tax_code_chinhanh'])&&count($error['Branch_tax_code_chinhanh'])==1&&trim($model->tax_code_chinhanh)!=""&&  is_numeric(trim($model->tax_code_chinhanh))){
//                unset($error['Branch_tax_code_chinhanh']);
//            }
            $error=  CJSON::encode($error);            
            echo $error;
            Yii::app()->end();            
        }   
        $is_submit=Yii::app()->request->getParam("is_submit","0");
        if($is_submit=='1'){
            /**
             * vi với nhà cung ứng quốc tế, MST không bắt buộc nên tạm set tax_code="111" để validate không báo lỗi mục MST này
             * tuy nhiên, nếu validate k bị lỗi và bắt đầu save vao db thi phai lấy giá trị thật khi user input
             * do đó phai lấy lại 1 lần nữa
             */
            $model->tax_code=Yii::app()->request->getParam("tax_code","");
            $model->is_international=Yii::app()->request->getParam("is_international","0");
            $model->save(FALSE);
        }
        
        echo '';
        Yii::app()->end();
        
    }
    /**
     * lấy thông tin khách hàng
     */
    public function actionGetbranch() {
        $id=Yii::app()->request->getParam("id","");
        if($id==""||!is_numeric($id)){            
            Yii::app()->end();
        }           
        //
        $row=Yii::app()->db->createCommand()
                ->select()
                ->from("branch")
                ->where("id=$id")
                ->queryRow();        
        if(is_array($row)&&count($row)>0){
            echo CJSON::encode($row);
        }
        else{
            echo '';
        }
        Yii::app()->end();
    }
    public function actionDelete(){
        $id=Yii::app()->request->getParam("id",0);
        $from_page=Yii::app()->request->getParam("from_page","");//cái này dùng để xác định, lúc xóa khách hàng/nhà cung ứng này, user đang đứng ở page nào
        if($id==0||!is_numeric($id)||($from_page!='supplierfull'&&$from_page!='customerfull'&&$from_page!='international')){
            Yii::app()->end();
        }
        /**
         * nếu quản lý kho hàng muốn xóa nhà cung cấp thi kiểm tra nhà cung cấp đó đã từng mua hàng chưa (tức là có trong hóa đơn bán hàng hay không)
         *            nếu không thi xóa khỏi hệ thống
         *            nếu có thi cho nó thành khách hàng
         * nếu quản lý bán hàng muốn xóa khách hàng thi kiểm tra khách hàng đó đã từng cung cấp hàng chưa (tức là có trong hóa đơn nhập kho hay không)
         *            nếu không thi xóa khỏi hệ thống
         *            nếu có thi cho nó thành nhà cung cấp
         */
        if($from_page=='customerfull'){//page khách hàng
            $count1=  BillInput::model()->count("branch_id=$id");
            $count2= BillChiphi::model()->count("branch_id=$id");
            $model=Branch::model()->findByPk($id);
            /**
             * khách hàng này chưa từng cung cấp hàng, có nghĩa là khách hàng này không có trong hóa đơn nhập kho, hóa đơn chi phí
             * (dĩ nhiên tại page khách hàng, hiển thị nút xóa cho khách hàng này thi đúng là nó không có trong hóa đơn bán hàng rồi)
             * thi xóa khách hàng này khỏi database
             * nếu không thi cho nó trở thành kiểu nhà cung ứng
             */
            if($count1==0&&$count2==0){
                $model->delete();  
            }
            else{
                $model->type_init=$model->type=  Branch::SUPPLIER;
                $model->save(FALSE);
            }
        }
        else if($from_page=='supplierfull'||$from_page=='international'){//page nhà cung ứng nội địa hoặc quốc tế
            $count1=Bill::model()->count("branch_id=$id");
            $count2=  Kxhd::model()->count("branch_id=$id");
            $count3=  Sxdv::model()->count("branch_id=$id");
            $model=Branch::model()->findByPk($id);
            /**
             * nhà cung ứng này chưa từng mua hàng, có nghĩa là nhà cung ứng này không có trong hóa đơn bán hàng
             * (dĩ nhiên tại page nhà cung ứng, hiển thị nút xóa cho nhà cung ứng này thi đúng là nó không có trong hóa đơn nhập hàng/tờ khai rồi)
             * thi xóa khách hàng này khỏi database
             * nếu không thi cho nó trở thành kiểu khách hàng
             */
            if($count1==0&&$count2==0&&$count3==0){//nhà cung ứng này chưa từng mua hàng, có nghĩa là nhà cung ứng này không có trong hóa đơn bán hàng, không xuất hóa đơn, sản xuất & dịch vụ                
                $model->delete();  
            }
            else{
                $model->type_init=$model->type=  Branch::CUSTOMER;
                $model->save(FALSE);
            }
        }
        
              
    }

}
