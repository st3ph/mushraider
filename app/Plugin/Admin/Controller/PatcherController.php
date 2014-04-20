<?php
class PatcherController extends AdminAppController {
    public $components = array('Patcher');
    public $uses = array('Character', 'EventsCharacter', 'Event');

    var $adminOnly = true;
    var $availiblePatchs = array('beta-2', 'beta-3', 'v-1.1');

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
    		$error = false;
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
	        	// If there is code to execute...
	        	$methodeName = str_replace('-', '', $patch);
	        	if(method_exists($this, $methodeName)) {
	        		$this->$methodeName();	        		
	        	}

                // Delete cache for obvious reasons :p
                Cache::clear(false, '_cake_core_');
                Cache::clear(false, '_cake_model_');

	        	$this->Session->setFlash(__('MushRaider successfully apply the patch !'), 'flash_success');
	        }

	        return $this->redirect('/admin');
	    }

	    $this->set('patch', $patch);
    }

    public function beta3() {
    	// Update new 'created' field of each character
    	// It will match the date of the first event the character signin
    	// This ensure the stats to be accurate

    	// Generate new schema
    	$this->Character->schemaBeta3();
    	Cache::clear();

    	$params = array();
    	$params['recursive'] = -1;
    	$params['fields'] = array('id', 'game_id');
    	if($characters = $this->Character->find('all', $params)) {
    		foreach($characters as $character) {
    			$toSaveCharacter['id'] = $character['Character']['id'];

				// Get first event
				$params = array();
				$params['recursive'] = -1;
				$params['fields'] = array('id', 'created');
				$params['order'] = 'created ASC';
				$params['conditions']['character_id'] = $character['Character']['id'];
				if($eventCharacter = $this->EventsCharacter->find('first', $params)) {
					$toSaveCharacter['created'] = $eventCharacter['EventsCharacter']['created'];
				}else {
					$toSaveCharacter['created'] = date('Y-m-d H:i:s');
				}
												
				$this->Character->save($toSaveCharacter);
    		}
    	}
    }
}