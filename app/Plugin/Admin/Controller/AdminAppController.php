<?php
class AdminAppController extends AppController {  
    public $uses = array();
    public $helpers = array('Admin.Admin');

    var $adminOnly = false;

    public function beforeFilter() {
        parent::beforeFilter();

        // Login required
        if(!$this->user) {
            $this->Session->setFlash(__('You must login to access this page.'), 'flash_error');
            $this->redirect('/auth/login');
        }

        // Admin or mod check
        if(!$this->user['User']['isAdmin'] && !$this->user['User']['isOfficer']) {
            $this->Session->setFlash(__('You don\'t have permission to access this page.'), 'flash_error');
            $this->redirect('/');
        }

        // Admin only
        if($this->adminOnly && !$this->user['User']['isAdmin']) {
            $this->Session->setFlash(__('You don\'t have permission to access this page.'), 'flash_error');
            $this->redirect('/admin');
        }

        $this->set('title_for_layout', __('MushRaider Admin'));
    }
}