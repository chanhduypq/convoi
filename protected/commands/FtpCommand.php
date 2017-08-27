<?php

class FtpCommand extends CConsoleCommand {

    public function run($args) {
        $upload_dir = Yii::app()->basePath.'/../uploads/ftp';
        $upload_dir_bk = Yii::app()->basePath.'/../uploads/ftp_bk';

        error_reporting(E_ALL);
        ini_set("display_errors","1");

        try{
            if ($handle = opendir($upload_dir)) {
                while (false !== ($username = readdir($handle))) {
                    if (!in_array($username, array('.', '..', '.svn', '.DS_Store'))) {

                        $user = User::model()->findByAttributes(array('username' => $username));

                        if (is_dir($upload_dir.'/'.$username) && $handle2 = opendir($upload_dir.'/'.$username)) {
                            while (false !== ($xml_file = readdir($handle2))) {
                                if (!in_array($xml_file, array('.', '..', '.svn', '.DS_Store'))) {

                                    if ($user){
                                        $xml_file1 = $upload_dir.'/'.$username.'/'.$xml_file;

                                        //place to backup
                                        if (!file_exists($upload_dir_bk.'/'.$username)){
                                            mkdir($upload_dir_bk.'/'.$username, 0777);
                                        }

                                        if (strpos($xml_file, '.zip') !== false){
                                            $zip = new ZipArchive;
                                            if ($zip->open($xml_file1) === TRUE) {
                                                $zip->extractTo($upload_dir.'/'.$username);
                                                $zip->close();
                                                @rename($xml_file1, $upload_dir_bk.'/'.$username.'/'.$xml_file);

                                                $handle2 = opendir($upload_dir.'/'.$username);
                                            }
                                        }
                                        elseif (strpos($xml_file, '.xml') !== false){
                                            $result = Object::saveImportXML($user, $xml_file1, $upload_dir.'/'.$username);
                                            $this->_prepareResult($result, $user);

                                            @rename($xml_file1, $upload_dir_bk.'/'.$username.'/'.$xml_file);
                                        }
                                    }
                                }
                            }
                            closedir($handle2);
                        }
                    }
                }
                closedir($handle);
            }
        }
        catch(exception $e){
        }
    }

    protected function _prepareResult($result, $user){
        $notification = new NotificationLogs();
        $attr = array(
            'object_name' => 'object',
            'type_change' => $result['type'],
            'content' => $result['message'],
            'user_id' => $user->id,
            'received_user_id' => $user->id,
            'can_refuse' => 0,
            'can_accept' => 0,
            'can_view' => 1,
            'created' => date('Y-m-d H:i:s'),
            'is_viewed' => Lookup::NO
        );
        if ($result['type'] != NotificationLogs::TYPE_DELETE){
            $attr['object_id'] = $result['id'];
        }

        $notification->attributes = $attr;

        if (!$notification->insert())
            print_r($notification->getErrors());

    }
}