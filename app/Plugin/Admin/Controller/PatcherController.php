<?php
class PatcherController extends AdminAppController {
    public $components = array('Patcher');
    public $uses = array();

    var $adminOnly = true;
    var $availiblePatchs = array('beta-2');

    function beforeFilter() {
        parent::beforeFilter();
    }

    public function index() {
    }

    public function apply($patch = null) {
    	if(!in_array($patch, $this->availiblePatchs)) {
    		$this->Session->setFlash(__('MushRaider can\'t find this patch'), 'flash_error');
    		return $this->redirect('/admin');
    	}

    	if(!empty($this->request->data['Patcher'])) {
	    	$databaseConfig = Configure::read('Database');
	        if(!$mysqlLink = mysqli_connect($databaseConfig['host'], $databaseConfig['login'], $databaseConfig['password'], $databaseConfig['database'], $databaseConfig['port'])) {
	            $error = true;
	        }
	    	
	    	$sqlReport = $this->Patcher->run_sql_file($mysqlLink, '../Config/Schema/sql/mushraider_patch_'.$patch.'.sql', $databaseConfig['prefix']);    			    	
	    	$mysqlLink = null;

	        if($sqlReport['success'] != $sqlReport['total']) {
	            $error = true;
	        }

	        if($error) {
	        	$this->Session->setFlash(__('MushRaider can\'t apply the SQL patch, please try again or apply it by yourself using the following file : /app/Config/Schema/sql/mushraider_patch_%s.sql', $patch), 'flash_error');
	        }else {
	        	$this->Session->setFlash(__('MushRaider successfully apply the patch !'), 'flash_success');
	        }

	        return $this->redirect('/admin');
	    }

	    $this->set('patch', $patch);
    }
}