<?php

class IndexController extends Controller {
    

    /**
     * Displays the login page
     */
    public function actionLogin() {
        
        $this->layout = 'login';
        $model = new User();



        // collect user input data
        if(Yii::app()->request->isPostRequest){
            $model->username=$model->email=$_POST['username'];            
            $model->password= $_POST['password'];
            
            // validate user input and redirect to the previous page if valid
            if ($model->login()) {                
                Yii::app()->session['username']=$_POST['username'];
                $this->redirect(array("/thongke/index"));               

            }
        }

        // display the login form
        $this->render('login', array('model' => $model));
    }

    

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        Yii::app()->session->clear();
        Yii::app()->session->destroy();
        $this->redirect(array("/index/login"));
    }
    public function actionIndex() {
        $this->redirect(array("/index/login"));
    }
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
        
    }

}
