<?php
App::uses('Security', 'Utility');

class StepController extends InstallAppController {
    public $components = array('Patcher', 'Tools');

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

        $systemCheckPassed = true;
        $systemCheck = array();

        $systemCheck['php']['passed'] = version_compare(PHP_VERSION, '5.3', '>=')?true:false;
        $systemCheck['php']['version'] = phpversion();
        $systemCheckPassed = $systemCheck['php']['passed']?$systemCheckPassed:false;

        $phpExtensions = get_loaded_extensions();
        $systemCheck['mysql']['passed'] = (in_array('mysql', $phpExtensions) || in_array('mysqli', $phpExtensions) || in_array('pdo_mysql', $phpExtensions))?true:false;
        $systemCheckPassed = $systemCheck['mysql']['passed']?$systemCheckPassed:false;

        if(function_exists('apache_get_modules')) {
            $systemCheck['rewrite']['passed'] = in_array('mod_rewrite', apache_get_modules())?true:false;
        }else {
            $systemCheck['rewrite']['passed'] = false;
            $systemCheck['rewrite']['warning'] = true;
        }

        $systemCheck['config']['passed'] = is_writable('../Config')?true:false;
        $systemCheckPassed = $systemCheck['config']['passed']?$systemCheckPassed:false;
        $systemCheck['tmp']['passed'] = is_writable('../tmp')?true:false;
        $systemCheckPassed = $systemCheck['tmp']['passed']?$systemCheckPassed:false;
        $systemCheck['files']['passed'] = is_writable('../webroot/files')?true:false;
        $systemCheckPassed = $systemCheck['files']['passed']?$systemCheckPassed:false;

        $this->set('systemCheckPassed', $systemCheckPassed);
        $this->set('systemCheck', $systemCheck);
    }

    private function step2() {
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
                Configure::write('Database', $this->Tools->quoteArray($databaseConfig));
                Configure::dump('config.ini', 'configini', array('Database'));

                $this->Session->setFlash(__('MushRaider successfully connect to your database, one more step to go !'), 'flash_success');
                $this->redirect('/install/step/3');
            }

            // Error
            $this->Session->setFlash(__('MushRaider can\'t connect to your database, please verify the settings.'), 'flash_error');

        }

        $this->set('dbDatasources', $this->dbDatasources);
    }

    private function step3() {
        $missingDatabase = false;
        if(!$databaseConfig = Configure::read('Database')) {
            $missingDatabase = true;
        }
        if($missingDatabase) {
            $this->Session->setFlash(__('MushRaider can\'t find your database settings, please complete this form to proceed to next step'), 'flash_error');
            $this->redirect('/install/step/2');
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
                        $this->postInstallData($siteTitle);

                    	$settingsConfig['installed'] = true;
                        Configure::write('Database', $this->Tools->quoteArray($databaseConfig));
                        Configure::write('Settings', $this->Tools->quoteArray($settingsConfig));
                        Configure::dump('config.ini', 'configini', array('Database', 'Settings'));

                        $this->Session->delete('Settings');
                        $this->Session->setFlash(__('MushRaider successfully install !'), 'flash_success');
                        $this->redirect('/auth/login');
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

    private function postInstallData($siteTitle) {
        // Add default settings
        $host = substr_count($_SERVER['HTTP_HOST'], '.') > 1?substr($_SERVER['HTTP_HOST'], strpos($_SERVER['HTTP_HOST'], '.') + 1):$_SERVER['HTTP_HOST'];                        
        $host = strpos($host, ':') !== false?substr($host, 0, strpos($host, ':')):$host; // Remove port if present on unusual configurations

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
        $defaultSettings['notifications'] = json_encode(array(
                                                'enabled' => 1,
                                                'signup' => 0,
                                                'contact' => ''
                                            ));
        $defaultSettings['email'] = json_encode(array(
                                            'name' => 'MushRaider',
                                            'from' => 'mushraider@'.$host,
                                            'encoding' => '',
                                            'transport' => 'Mail',
                                            'host' => '',
                                            'port' => '',
                                            'username' => '',
                                            'password' => ''
                                        ));
        $defaultSettings['Mushstats'] = time();
        $defaultSettings['calendar'] = json_encode(array(
                                            'weekStartDay' => 1,
                                            'title' => 'event',
                                            'timeToDisplay' => 'time_invitation',
                                            'gameIcon' => 1,
                                            'dungeonIcon' => 1
                                        ));
        $defaultSettings['timezone'] = 'Europe/Paris';
        
        foreach($defaultSettings as $option => $value) {
            $settingModel->create();
            $settingModel->save(array('option' => $option, 'value' => $value));
        }

        // Add default roles permissions
        $rolesPermissions = array(
            array('title' => __('Full permissions'), 'alias' => 'full_permissions', 'description' => __('Like Chuck Norris, he can do anything. This overwrite every permissions')),
            array('title' => __('Limited admin access'), 'alias' => 'limited_admin', 'description' => __('Like Robin, he can do some things but not all (like driving the batmobile or change user role)')),
            array('title' => __('Can manage events'), 'alias' => 'manage_events', 'description' => __('Can create, edit and delete events. Can also manage the roster for each events')),
            array('title' => __('Can manage own events only'), 'alias' => 'manage_own_events', 'description' => __('Can create, edit and delete own events only. Can also manage the roster for his events')),
            array('title' => __('Can create templates'), 'alias' => 'create_templates', 'description' => __('Can create events templates')),
            array('title' => __('Can create reports'), 'alias' => 'create_reports', 'description' => __('Can create events reports'))
        );
        App::uses('RolePermission', 'Model');
        $RolePermissionModel = new RolePermission();
        foreach($rolesPermissions as $rolesPermission) {
            $RolePermissionModel->create();
            $RolePermissionModel->save($rolesPermission);
        }

        // Add new roles permissions to existing roles
        App::uses('Role', 'Model');
        $RoleModel = new Role();
        App::uses('RolePermissionRole', 'Model');
        $RolePermissionRoleModel = new RolePermissionRole();
        $RolePermissionRoleModel->__add(array('role_id' => $RoleModel->getIdByAlias('admin'), 'role_permission_id' => $RolePermissionModel->getIdByAlias('full_permissions')));
        $RolePermissionRoleModel->__add(array('role_id' => $RoleModel->getIdByAlias('officer'), 'role_permission_id' => $RolePermissionModel->getIdByAlias('limited_admin')));
        $RolePermissionRoleModel->__add(array('role_id' => $RoleModel->getIdByAlias('officer'), 'role_permission_id' => $RolePermissionModel->getIdByAlias('manage_events')));
        $RolePermissionRoleModel->__add(array('role_id' => $RoleModel->getIdByAlias('officer'), 'role_permission_id' => $RolePermissionModel->getIdByAlias('create_templates')));
        $RolePermissionRoleModel->__add(array('role_id' => $RoleModel->getIdByAlias('officer'), 'role_permission_id' => $RolePermissionModel->getIdByAlias('create_reports')));
    }
}