<?php
App::uses('IniReader', 'Configure');
App::uses('Controller', 'Controller');
class InstallAppController extends Controller {
	public function beforeFilter() {		
		parent::beforeFilter();

        if(!file_exists('../Config/config.ini')) {
            file_put_contents('../Config/config.ini', '');
        }
        
        Configure::config('configini', new IniReader());
        Configure::load('config.ini', 'configini');

		// If config already set, redirect to root
		if(file_exists('../Config/config.ini')) {
            if(Configure::read('Settings.installed')) {
            	$this->Session->setFlash(__('MushRaider can see the database config, why do you want to install ?'), 'flash_warning');
            	$this->redirect('/');            	
            }
		}

        $this->set('title_for_layout', __('MushRaider Installer'));

        $languages = array('eng' => 'English', 'fra' => 'Français');
        $this->set('languages', $languages);
	}
}