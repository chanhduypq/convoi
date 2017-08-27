<?php

class UserController extends Controller {

    public function init() {
        parent::init();
    }
    
    public function actionMore() {        
        $params = array();
        //
        $argPage       = (int) Yii::app()->request->getQuery('page', 0);        
        $dbCriteria             = new CDbCriteria;
        $dbCriteria->limit      = Yii::app()->params['number_of_items_per_page'];
        $dbCriteria->offset     = $argPage * $dbCriteria->limit;
        //
        $items = User::model()->findAll($dbCriteria);        
        if(!is_array($items)||count($items)==0){
            echo '';
            Yii::app()->end();
        }
        $params['items'] = $items;
        $this->renderPartial('//render_partial/common/more', $params);
    }
    
    public function actionIndex() {    
        $params = array();             
        $items = User::model()->findAll();        
        $params['items'] = $items;        
        $params['count'] = number_format(User::model()->count(), 0, ",", ".");
        $this->render('index', $params);
    }
    /**
     * validate create/update user
     */
    public function actionSaveuser() {
        /**
         * save db
         */
        $id=Yii::app()->request->getParam("id","");
        //
        $model=new User();        
        if($id!=""){
            if(!is_numeric($id)){                
                Yii::app()->end();
            }
            $model=  User::model()->findByPk($id);
        } 
        else{
            $model->setIsNewRecord(true);
        }        
        $model->password_for_show=Yii::app()->request->getParam("password_for_show","");
        $model->re_password_for_show=Yii::app()->request->getParam("re_password_for_show","");
        $model->danh_xung=Yii::app()->request->getParam("danh_xung","");
        $model->full_name=Yii::app()->request->getParam("full_name","");
        $model->email=trim(Yii::app()->request->getParam("email",""));
        $model->phone=Yii::app()->request->getParam("phone","");
        $model->address=Yii::app()->request->getParam("address","");
      
        $error=CActiveForm::validate($model);
        if($error!='[]'){
            echo $error;
            Yii::app()->end();
        }   
        echo '';
        Yii::app()->end();
        
    }
    /**
     * validate edit profile user
     */
    public function actionSaveprofile() {
        /**
         * save db
         */
        $id=Yii::app()->request->getParam("id","");
        //
        $model=new User();        
        if($id!=""){
            if(!is_numeric($id)){                
                Yii::app()->end();
            }
            $model=  User::model()->findByPk($id);
        } 
        else{
            $model->setIsNewRecord(true);
        }       
        $password_old=Yii::app()->request->getParam("password_old","");        
        $model->danh_xung=Yii::app()->request->getParam("danh_xung","");
        $model->full_name=Yii::app()->request->getParam("full_name","");
        $model->email=trim(Yii::app()->request->getParam("email",""));
        $model->phone=Yii::app()->request->getParam("phone","");
        $model->address=Yii::app()->request->getParam("address","");
   
        $errors=CActiveForm::validate($model);
        if($errors!='[]'){ 
            $errors=  CJSON::decode($errors);
            /**
             * model user không có attribute password_old
             * do đó trong rules cũng se không cấu hình validate cho attribute này
             * cho nên phai tự dựng lên ở đây
             * đoạn code này là validate khi user click nút save tại form
             */
            if($password_old==''){
                $errors['User_password_old']='Vui lòng nhập Mật khẩu cũ';
            }
            else if($password_old!=Yii::app()->session['password']){
                $errors['User_password_old']='Mật khẩu cũ không đúng';                    
            }            
            echo CJSON::encode($errors);
            Yii::app()->end();
        } 
        else{
            /**
             * model user không có attribute password_old
             * do đó trong rules cũng se không cấu hình validate cho attribute này
             * cho nên phai tự dựng lên ở đây             
             */
            if($password_old==''){
                echo '{"User_password_old":["Vui lòng nhập mật khẩu cũ"]}';
            }
            else if($password_old!=Yii::app()->session['password']){
                echo '{"User_password_old":["Mật khẩu cũ không đúng"]}';
            }
            else{
                echo '';
                
            }
            Yii::app()->end();
        }
        
        
    }
    /**
     * lấy thông tin khách hàng
     */
    public function actionGetuser() {
        $id=Yii::app()->request->getParam("id","");
        if($id==""||!is_numeric($id)){            
            Yii::app()->end();
        }           
        //
        $row=Yii::app()->db->createCommand()
                ->select()
                ->from("user")
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
        $model=User::model()->findByPk($id);
        $model->delete();
    }
    public function actionCreate() {
        $params = array();
        $params['errors']='';    
        /**
         * 
         */
        if (Yii::app()->request->isPostRequest) {
            $model=new User();        
            $model->setIsNewRecord(true);     
            $model->password_for_show=Yii::app()->request->getParam("password_for_show","");
            $model->re_password_for_show=Yii::app()->request->getParam("re_password_for_show","");
            $model->danh_xung=Yii::app()->request->getParam("danh_xung","");
            $model->full_name=Yii::app()->request->getParam("full_name","");
            $model->email=trim(Yii::app()->request->getParam("email",""));
            $model->phone=Yii::app()->request->getParam("phone","");
            $model->address=Yii::app()->request->getParam("address","");

            $model->role=  Yii::app()->request->getParam("role","");
            if(isset($_FILES['photo']['name'])){            
                $model->photo = $_FILES['photo']['name'];                
            }

            $errors=CActiveForm::validate($model);
            if($errors!='[]'){
                $params['errors']=$errors; 
                $params['password_for_show']=$model->password_for_show;     
                $params['re_password_for_show']=$model->re_password_for_show;     
                $params['danh_xung']=$model->danh_xung;     
                $params['full_name']=$model->full_name;     
                $params['email']=$model->email;     
                $params['phone']=$model->phone;     
                $params['address']=$model->address;     
   
                $params['user_role']=$model->role;   
            }  
            else{
                if(isset($_FILES['photo']['name'])){                    
                    move_uploaded_file($_FILES['photo']['tmp_name'], Yii::getPathOfAlias('webroot').'/upload/' . $model->photo);
                }
                $model->password= sha1($model->password_for_show);
                $model->save(FALSE);
                $this->redirect(array("/user/index"));
            }
        }
        else{
            $params['password_for_show']='';     
            $params['re_password_for_show']='';     
            $params['danh_xung']='';     
            $params['full_name']='';          
            $params['email']='';     
            $params['phone']='';     
            $params['address']='';       
            $params['user_role']='';      
        }        
        
        $params['roles']=Yii::app()->db->createCommand()->select()->from("role")->queryAll();
        $this->render('create', $params);
    }
    public function actionUpdate() {
        $id=Yii::app()->request->getParam("id",'0');
        $model=User::model()->findByPk($id);
        $role=Yii::app()->db->createCommand()->select("role.id")->from("role")->join("user","user.role=role.id")->where("user.id=".$model->id)->queryScalar();
        $params = array();
        $params['errors']='';    
        /**
         * 
         */
        if (Yii::app()->request->isPostRequest) {            
            $model->password_for_show=Yii::app()->request->getParam("password_for_show","");
            $model->re_password_for_show=Yii::app()->request->getParam("re_password_for_show","");
            $model->danh_xung=Yii::app()->request->getParam("danh_xung","");
            $model->full_name=Yii::app()->request->getParam("full_name","");
            $model->email=trim(Yii::app()->request->getParam("email",""));
            $model->phone=Yii::app()->request->getParam("phone","");
            $model->address=Yii::app()->request->getParam("address","");
            $model->role=  Yii::app()->request->getParam("role","");
            $role=$model->role;
            if(isset($_FILES['photo']['name'])&&$_FILES['photo']['name']!=""){            
                $model->photo = $_FILES['photo']['name'];                
            }

            $errors=CActiveForm::validate($model);  
            
            if($errors!='[]'){
                $errors=  CJSON::decode($errors);
                if(isset($errors['User_re_password_for_show'])){
                    
                    if($model->re_password_for_show==''){
                        $errors['User_re_password_for_show']='Vui lòng nhập lại Mật khẩu';
                    }  

                }
                $params['errors']=  CJSON::encode($errors); 
                
            }  
            else{           
                if(isset($_FILES['photo']['name'])&&$_FILES['photo']['name']!=""){ 
                    $temp = explode(".", $_FILES['photo']['name']);
                    $extension = $temp[count($temp) - 1];
                    $extension = strtolower($extension);
                    $model->photo=sprintf('_%s.'.$extension, uniqid(md5(time()), true));
        
                    move_uploaded_file($_FILES['photo']['tmp_name'], Yii::getPathOfAlias('webroot').'/upload/' . $model->photo);
                }
                $model->password= sha1($model->password_for_show);
                $model->save(FALSE);
                if($model->id==Yii::app()->session['user_id']){
                    Yii::app()->session['photo']=  $model->photo;
                }
                $this->redirect(array("/user/index"));
            }
        }
        
        $params['password_for_show']=$model->password_for_show;     
        $params['re_password_for_show']=$model->re_password_for_show;     
        $params['danh_xung']=$model->danh_xung;     
        $params['full_name']=$model->full_name;     
        $params['email']=$model->email;     
        $params['phone']=$model->phone;     
        $params['address']=$model->address;         
        $params['photo']=$model->photo;   
        $params['user_role']=$role;
        $params['id']=$id;  
        $params['roles']=Yii::app()->db->createCommand()->select()->from("role")->queryAll();
        /**
         * 
         */
        $ung_tiens=  Ungtien::model()->findAll("user_id=$id");
        $params['ung_tiens']=$ung_tiens;   
        /**
         * 
         */
        $nghi_pheps= Nghiphep::model()->findAll("user_id=$id");
        $params['nghi_pheps']=$nghi_pheps; 
        $this->render('update', $params);
    }
    public function actionEditprofile() {        
        if(!isset(Yii::app()->session['back_url_from_edit_profile'])){
            Yii::app()->session['back_url_from_edit_profile']=Yii::app()->request->urlReferrer;
        }
        $id=Yii::app()->request->getParam("id",'0');
        $model=User::model()->findByPk($id);        
        $params = array();
        $params['errors']='';    
        $password_old=$password_for_show=$re_password_for_show='';
        /**
         * 
         */
        if (Yii::app()->request->isPostRequest) {            
            $password_old=Yii::app()->request->getParam("password_old","");
            $password_for_show=$model->password_for_show=Yii::app()->request->getParam("password_for_show","");
            $re_password_for_show=$model->re_password_for_show=Yii::app()->request->getParam("re_password_for_show","");
            $model->danh_xung=Yii::app()->request->getParam("danh_xung","");
            $model->full_name=Yii::app()->request->getParam("full_name","");
            $model->email=trim(Yii::app()->request->getParam("email",""));
            $model->phone=Yii::app()->request->getParam("phone","");
            $model->address=Yii::app()->request->getParam("address","");
            $model->role=Yii::app()->db->createCommand()->select("role")->from("user")->where("id=".$model->id)->queryScalar();
            
            
            if(isset($_FILES['photo']['name'])&&$_FILES['photo']['name']!=""){            
                $model->photo = $_FILES['photo']['name'];                
            }

            $errors=CActiveForm::validate($model);       
            if($errors!='[]'){
                $errors=  CJSON::decode($errors);
                if(isset($errors['User_password_for_show'])){
                    $errors['User_password_for_show']='Vui lòng nhập Mật khẩu mới';
                }
                if(isset($errors['User_re_password_for_show'])){
                    
                    if($model->re_password_for_show==''){
                        $errors['User_re_password_for_show']='Vui lòng nhập lại Mật khẩu mới';
                    }  

                }

                /**
                 * model user không có attribute password_old
                 * do đó trong rules cũng se không cấu hình validate cho attribute này
                 * cho nên phai tự dựng lên ở đây
                 * đoạn code này là validate khi user đã nhập đầy đủ thông tin mà chưa tính đến password_old
                 */
                if($password_old==''){
                    $errors['User_password_old']='Vui lòng nhập Mật khẩu cũ';
                }
                else if($password_old!=Yii::app()->session['password']){
                    $errors['User_password_old']='Mật khẩu cũ không đúng';                    
                }
                $params['errors']=  CJSON::encode($errors);
                
            }  
            else{  
                /**
                 * model user không có attribute password_old
                 * do đó trong rules cũng se không cấu hình validate cho attribute này
                 * do đó array $errors se k có key User_password_old
                 * cho nên phai tự dựng lên ở đây
                 * đoạn code này là validate khi user chưa nhập đầy đủ thông tin mà chưa tính đến password_old
                 */
                if($password_old==''){
                    $errors='{"User_password_old":["Vui lòng nhập mật khẩu cũ"]}';
                }
                else if($password_old!=Yii::app()->session['password']){
                    $errors='{"User_password_old":["Mật khẩu cũ không đúng"]}';
                }
                $params['errors']=  $errors;
                
                if($errors=='[]'){
                    
                    if(isset($_FILES['photo']['name'])&&$_FILES['photo']['name']!=""){ 
                        $temp = explode(".", $_FILES['photo']['name']);
                        $extension = $temp[count($temp) - 1];
                        $extension = strtolower($extension);
                        $model->photo=sprintf('_%s.'.$extension, uniqid(md5(time()), true));

                        move_uploaded_file($_FILES['photo']['tmp_name'], Yii::getPathOfAlias('webroot').'/upload/' . $model->photo);
                    }

                    $model->password= sha1($password_for_show);
                    
                    $model->save(FALSE);
                    
                    if($model->id==Yii::app()->session['user_id']){
                        Yii::app()->session['photo']=  $model->photo;
                    }
                    Yii::app()->session['password']=$password_for_show;
                    $back_url_from_edit_profile=Yii::app()->session['back_url_from_edit_profile'];
                    unset(Yii::app()->session['back_url_from_edit_profile']);
                    $this->redirect($back_url_from_edit_profile);
                }
            }
        }
        $params['password_old']=$password_old;     
        $params['password_for_show']=$password_for_show;     
        $params['re_password_for_show']=$re_password_for_show;     
        $params['danh_xung']=$model->danh_xung;     
        $params['full_name']=$model->full_name;      
        $params['email']=$model->email;     
        $params['phone']=$model->phone;     
        $params['address']=$model->address;
        $params['photo']=$model->photo;           
        $params['id']=$id;  
        /**
         * 
         */
        $ung_tiens=  Ungtien::model()->findAll("user_id=".Yii::app()->session['user_id']);
        $params['ung_tiens']=$ung_tiens; 
        /**
         * 
         */
        $nghi_pheps= Nghiphep::model()->findAll("user_id=".Yii::app()->session['user_id']);
        $params['nghi_pheps']=$nghi_pheps; 
        $this->render('editprofile', $params);
    }

    

}
