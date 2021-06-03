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
App::uses('Controller', 'Controller');
App::uses('IniReader', 'Configure');

/*
* * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
    public $components = array('Session', 'Cookie', 'Lang', 'Tools', 'Patcher');
    public $uses = array('User', 'Role', 'Setting', 'Character');

    public $user = null;
    public $userRequired = true;
    public $pageTitle = 'MushRaider';
	public $pageDescription = 'MushRaider raid planner';
    public $breadcrumb = array();
    public $appLocales = array('eng' => 'English', 'fra' => 'Français', 'deu' => 'Deutsch');
	public $appLocales_ = array('en-en' => 'English', 'fr-fr' => 'Français', 'de-de' => 'Deutsch');

	public function beforeFilter() {
		parent::beforeFilter();

        // Check conf file
        if(!file_exists(CONFIG . 'config.ini')) {
            file_put_contents(CONFIG . 'config.ini', '');
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

        switch($language) {
            case 'fr-fr':
                $language = 'fra';
                break;
            case 'en-en':
            case 'en-us':
                $language = 'eng';
                break;
            case 'de-de':
                $language = 'deu';
                break;
        }

        Configure::write('Config.language', $language);

        // Some usefull infos
        Configure::write('Config.maxFileSize', ini_get('upload_max_filesize'));
        Configure::write('Config.maxPostSize', ini_get('post_max_size'));
        Configure::write('Config.appUrl', rtrim('http://'.$_SERVER['HTTP_HOST'].$this->webroot, '/'));

        // Timezone
        date_default_timezone_set($this->Setting->getOption('timezone'));

        // Log in user
        if($this->Session->check('User.id')) {
			$userID = $this->Session->read('User.id');
			if(!empty($userID)) {
                $params = array();
                $params['recursive'] = 1;
                $params['contain']['Role'] = array();
                $params['contain']['Character'] = array();
                $params['conditions']['User.id'] = $userID;
				if($this->user = $this->User->find('first', $params)) {
                    $this->user['User']['can'] = $this->Role->getPermissions($this->user['User']['role_id']);
                }
			}else {
				$this->Session->delete('User.id');
			}
		}

        // Is a patch needed ?
        if(strtolower($this->name) != 'patcher' && strtolower($this->name) != 'auth') {
            $this->Patcher->patchNeeded();
        }

        $this->pageTitle = $this->Setting->getOption('title');

        Configure::write('Config.email', json_decode($this->Setting->getOption('email')));
        Configure::write('Config.notifications', json_decode($this->Setting->getOption('notifications')));
	}

	public function beforeRender() {
		if($this->userRequired && !$this->user) {
            if($this->name != 'CakeError') {
                $this->Session->write('redirectFrom', $this->Tools->here());
                $this->Session->setFlash(__('You have to be logged in to access this page.'), 'flash_warning');
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
