<?php
App::uses('HttpSocket', 'Network/Http');
class AdminAppController extends AppController {  
    public $uses = array();
    public $helpers = array('Admin.Admin');

    var $adminOnly = false;

    public function beforeFilter() {
        parent::beforeFilter();

        // Login required
        if(!$this->user) {
            $this->Session->write('redirectFrom', $this->Tools->here());
            $this->Session->setFlash(__('You have to be logged in to access this page.'), 'flash_warning');
            $this->redirect('/auth/login');
        }

        // Admin or mod check
        if(!$this->user['User']['can']['full_permissions'] && !$this->user['User']['can']['limited_admin']) {
            $this->Session->setFlash(__('You don\'t have permission to access this page.'), 'flash_error');
            $this->redirect('/');
        }

        // Admin only
        if($this->adminOnly && !$this->user['User']['can']['full_permissions']) {
            $this->Session->setFlash(__('You don\'t have permission to access this page.'), 'flash_error');
            $this->redirect('/admin');
        }

        // $this->checkUpdate();

        $this->set('title_for_layout', __('MushRaider Admin'));
    }

    private function checkUpdate() {
        $mushraider = Configure::read('mushraider');
        if(empty($mushraider)) {
            $mushraider['version'] = 0;
            $mushraider['date'] = '0000-00-00';
        }
        $jsonUrl = 'http://medias.mushraider.com/version.json';
        $HttpSocket = new HttpSocket();
        $json = $HttpSocket->get($jsonUrl);
        $lastVersion = json_decode($json->body);
        // Check if version is different
        // Be sure the server is newer than the current app            
        if(($mushraider['version'] != $lastVersion->version && $mushraider['date'] < $lastVersion->date) || ($mushraider['version'] == $lastVersion->version && $mushraider['date'] < $lastVersion->date)) {
            $updateMsg = __('<strong>MushRaider %s</strong> is available! <a href="http://mushraider.com/download" target="_blank">Please update now</a>', $lastVersion->version);
            $this->set('updateAvailable', $updateMsg);
        }
    }
}