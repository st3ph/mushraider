<?php
App::uses('Security', 'Utility');

class StepController extends InstallAppController {
    public $components = array('Patcher');

    var $dbDatasources = array('Mysql');

    public function beforeFilter() {
        parent::beforeFilter();
    }

    public function index($step = 1) {
        $methodName = 'step'.$step;
        $this->$methodName();

        $this->set('step', $step);
        $this->render('step'.$step);
    }

    private function step1() {
        $this->Session->delete('Settings');
        
        if(!empty($this->request->data['Config'])) {
            $dataSource = !empty($this->request->data['Config']['datasource']) && !empty($this->dbDatasources[$this->request->data['Config']['datasource']])?$this->dbDatasources[$this->request->data['Config']['datasource']]:'Mysql';

            $databaseConfig = array();
            $databaseConfig['datasource'] = 'Database/'.$dataSource;
            $databaseConfig['host'] = !empty($this->request->data['Config']['host'])?trim($this->request->data['Config']['host']):'localhost';
            $databaseConfig['database'] = trim($this->request->data['Config']['database']);
            $databaseConfig['login'] = trim($this->request->data['Config']['login']);
            $databaseConfig['password'] = $this->request->data['Config']['password'];
            $databaseConfig['port'] = !empty($this->request->data['Config']['port'])?trim($this->request->data['Config']['port']):ini_get("mysqli.default_port");            
            $databaseConfig['prefix'] = trim($this->request->data['Config']['prefix']);

            if($link = @mysqli_connect($databaseConfig['host'], $databaseConfig['login'], $databaseConfig['password'], $databaseConfig['database'], $databaseConfig['port'])) {
                Configure::write('Database', $databaseConfig);
                Configure::dump('config.ini', 'configini', array('Database'));

                $this->Session->setFlash(__('MushRaider successfully connect to your database, one more step to go !'), 'flash_success');
                $this->redirect('/install/step/2');
            }

            // Error
            $this->Session->setFlash(__('MushRaider can\'t connect to your database, please verify the settings.'), 'flash_error');

        }

        $this->set('dbDatasources', $this->dbDatasources);
    }

    private function step2() {
        $missingDatabase = false;
        if(!$databaseConfig = Configure::read('Database')) {
            $missingDatabase = true;
        }
        if($missingDatabase) {
            $this->Session->setFlash(__('MushRaider can\'t find your database settings, please complete this form to proceed to next step'), 'flash_error');
            $this->redirect('/install/step/1');
        }

        if(!empty($this->request->data['Config'])) {
            $siteTitle = trim($this->request->data['Config']['sitetitle']);
            $siteLanguage = trim($this->request->data['Config']['sitelang']);
            $adminemail = trim($this->request->data['Config']['adminemail']);
            $adminlogin = trim($this->request->data['Config']['adminlogin']);
            $adminpassword = md5($this->request->data['Config']['adminpassword']);

            if(!empty($siteTitle) && !empty($adminlogin) && !empty($adminpassword)) {
                $settingsConfig = array();
                $settingsConfig['language'] = $siteLanguage;
                $settingsConfig['salt'] = Security::generateAuthKey();
                $settingsConfig['cipherSeed'] = mt_rand() . mt_rand();

                $error = false;
                if(!$mysqlLink = mysqli_connect($databaseConfig['host'], $databaseConfig['login'], $databaseConfig['password'], $databaseConfig['database'], $databaseConfig['port'])) {
                    $error = true;
                }
                // Create tables
                $sqlReport = $this->Patcher->run_sql_file($mysqlLink, '../Config/Schema/sql/mushraider.sql', $databaseConfig['prefix']);                
                if($sqlReport['success'] != $sqlReport['total']) {
                    $error = true;
                }
                // Add datas
                $sqlReport = $this->Patcher->run_sql_file($mysqlLink, '../Config/Schema/sql/mushraider_data.sql', $databaseConfig['prefix']);
                if($sqlReport['success'] != $sqlReport['total']) {
                    $error = true;
                }

                $mysqlLink = null;

                // No error, we continue by creating the admin user
                if(!$error) {
                    App::uses('User', 'Model');
                    $userModel = new User();

                    $toSave = array();
                    $toSave['role_id'] = 1;
                    $toSave['username'] = $adminlogin;
                    $toSave['password'] = $adminpassword;
                    $toSave['email'] = $adminemail;
                    $toSave['status'] = 1;
                    $toSave['activation_key'] = md5(uniqid());
                    $userModel->create();
                    if($userModel->save($toSave)) {
                        // Add default settings
                        App::uses('Setting', 'Model');
                        $settingModel = new Setting();
                        $defaultSettings = array();
                        $defaultSettings['title'] = $siteTitle;
                        $defaultSettings['theme'] = json_encode(array(
                                                            'logo' => '/img/logo.png',
                                                            'bgcolor' => '#444444',
                                                            'bgimage' => $this->request->webroot.'img/bg.png',
                                                            'bgrepeat' => 'repeat'
                                                        ));
                        $defaultSettings['css'] = '';
                        $defaultSettings['notifications'] = 1;
                        foreach($defaultSettings as $option => $value) {
                            $settingModel->create();
                            $settingModel->save(array('option' => $option, 'value' => $value));
                        }


                    	$settingsConfig['installed'] = true;
                        Configure::write('Settings', $settingsConfig);
                        Configure::dump('config.ini', 'configini', array('Database', 'Settings'));

                        $this->Session->delete('Settings');
                        $this->Session->setFlash(__('MushRaider successfully install !'), 'flash_success');
                        $this->redirect('/admin');
                    }else {
                        $this->Session->setFlash(__('MushRaider can\'t create admin user, please try again.'), 'flash_error');
                        $flashed = true;
                    }
                }
            }

            // Error
            if(!isset($flashed)) {
                $this->Session->setFlash(__('MushRaider can\'t verify the settings, please be sure to fill all the fields to continue.'), 'flash_error');
            }
        }
    }
}