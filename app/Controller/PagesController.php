<?php
App::uses('AppController', 'Controller');
class PagesController extends AppController {
	public $uses = array('Page');

	public function display() {
		$path = func_get_args();

		$count = count($path);
		if (!$count) {
			$this->redirect('/');
		}
		$page = $subpage = $title_for_layout = null;

		if (!empty($path[0])) {
			$page = $path[0];
		}
		if (!empty($path[1])) {
			$subpage = $path[1];
		}
		if (!empty($path[$count - 1])) {
			$title_for_layout = Inflector::humanize($path[$count - 1]);
		}
		$this->set(compact('page', 'subpage', 'title_for_layout'));
		$this->render(implode('/', $path));
	}

	public function preview($idPage) {
		$params = array();
        $params['recursive'] = -1;
        $params['conditions']['status'] = array(0, 1);
        $params['conditions']['id'] = $idPage;
        if(!$page = $this->Page->find('first', $params)) {
        	throw new NotFoundException();
        }

        $this->set('page', $page);
	}
}
