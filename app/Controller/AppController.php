<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('IniReader', 'Configure');
App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
    public $components = array('Session', 'Cookie', 'Lang', 'Tools', 'Patcher');
    var $uses = array('User', 'Role', 'Setting', 'Character');

    var $user = null;
    var $userRequired = true;
    var $pageTitle = 'MushRaider';
	var $pageDescription = 'MushRaider raid planner';
	var $breadcrumb = array();

	public function beforeFilter() {
		parent::beforeFilter();

        // Check conf file
        if(!file_exists('../Config/config.ini')) {
            file_put_contents('../Config/config.ini', '');
        }

        Configure::config('configini', new IniReader());
        Configure::load('config.ini', 'configini');

		// If config isn't set, redirect to install process to create one
		if(!Configure::read('Settings.installed') && strtolower($this->plugin) != 'install') {
        	$this->Session->setFlash(__('MushRaider is ready for install, what about you ?'), 'flash_info');
    	    $this->redirect('/install/step/1');	        
        }

        // Language
        $language = Configure::read('Settings.language');
        $language = $this->Cookie->check('Lang')?$this->Cookie->read('Lang'):$language;
        $language = !empty($language)?$language:'eng';
        Configure::write('Config.language', $language);

        // Log in user
        if($this->Session->check('User.id')) {
			$userID = $this->Session->read('User.id');			
			if(!empty($userID)) {
                $params = array();
                $params['recursive'] = 1;
                $params['contain']['Role'] = array();
                $params['contain']['Character'] = array();
                $params['conditions']['User.id'] = $userID;
				$this->user = $this->User->find('first', $params);
                $this->user['User']['isAdmin'] = $this->Role->is($this->user['User']['role_id'], 'admin');
                $this->user['User']['isOfficer'] = $this->Role->is($this->user['User']['role_id'], 'officer');
			}else {
				$this->Session->delete('User.id');
			}
		}

        // Is a patch needed ?
        if($this->user && $this->user['User']['isAdmin'] && strtolower($this->plugin) != 'admin' && strtolower($this->name) != 'patcher') {
            $this->Patcher->patchNeeded();
        }

        $this->pageTitle = $this->Setting->getOption('title');

        Configure::write('Config.email', json_decode($this->Setting->getOption('email')));
	}

	public function beforeRender() {
		if($this->userRequired && !$this->user) {
            if($this->name != 'CakeError') {
                $this->Session->write('redirectFrom', $this->Tools->here());
                $this->redirect('/auth/login');
            }
        }

        // Theming
        $this->set('mushraiderLinks', json_decode($this->Setting->getOption('links')));
        $this->set('mushraiderTagline', $this->Setting->getOption('title'));
        $themeOptions = json_decode($this->Setting->getOption('theme'));
        $themeOptions->css = $this->Setting->getOption('css');
        $this->set('mushraiderTheme', $themeOptions);

        // SEO
        $this->pageTitle .= !empty($this->request->params['named']['page'])?' - page '.$this->request->params['named']['page']:'';
        $this->pageDescription .= !empty($this->request->params['named']['page'])?' - page '.$this->request->params['named']['page']:'';
        
        $this->set('title_for_layout', $this->pageTitle);
        $this->set('description_for_layout', $this->pageDescription);
        $this->set('breadcrumb', $this->breadcrumb);

        // You
        $this->set('user', $this->user);
	}
}
