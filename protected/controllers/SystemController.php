<?php

class SystemController extends Controller {

    public function init() {
        parent::init();
    }
    
    public function actionIndex() {        
        $params = array();       
        //
        $bill_type_array=  System::get_bill_type();        
        $params['bill_type_array']=$bill_type_array;
        //
        $path_for_save_bill=  System::get_path_for_save_bill();
        $params['path_for_save_bill']=$path_for_save_bill;
        $this->render('index', $params);
    }

    

}
