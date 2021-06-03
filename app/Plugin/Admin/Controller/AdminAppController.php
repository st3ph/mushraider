<?php
App::uses('HttpSocket', 'Network/Http');
class AdminAppController extends AppController {
    public $components = array('Admin.Mushstats');
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

        $this->checkUpdate();

        $this->set('title_for_layout', __('MushRaider Admin'));
    }

    private function checkUpdate() {
        $mushraider = Configure::read('mushraider');
        if(empty($mushraider)) {
            $mushraider['version'] = 0;
            $mushraider['date'] = '0000-00-00';
        }

        $jsonUrl = 'https://api.github.com/repos/st3ph/mushraider/tags?per_page=1';
        $HttpSocket = new HttpSocket(['timeout' => 1]);
		$response = $HttpSocket->get($jsonUrl);
		if ($response->isOk()) {
			$tag = json_decode($response->body);
			if (!$tag) {
				return;
			}
			$lastVersion = str_replace('v', '', $tag[0]->name);

			// Check if version is different
			// Be sure the server is newer than the current app
			if(version_compare($mushraider['version'], $lastVersion, '<')) {
				$updateMsg = __('<strong>MushRaider %s</strong> is available! <a href="https://github.com/st3ph/mushraider/tags" target="_blank">Please update now</a>', $lastVersion);
				$this->set('updateAvailable', $updateMsg);
			}
		}
    }
}
