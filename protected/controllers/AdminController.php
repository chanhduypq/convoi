<?php

class AdminController extends Controller {

    public function init() {
        parent::init();
    }
    
    public function actionIndex() {        
        $params = array();        
        //
        $this->render('index', $params);
    }

    

}
