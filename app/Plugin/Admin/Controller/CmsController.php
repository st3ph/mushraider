<?php
class CmsController extends AdminAppController {
    public $uses = array('Page');

    function beforeFilter() {
        parent::beforeFilter();
    }

    public function index() {
        $params = array();
        $params['recursive'] = -1;
        $params['order'] = 'created desc';
        $params['conditions']['published'] = array(0, 1);
        $pages = $this->Page->find('all', $params);
        $this->set('pages', $pages);
    }

    public function add() {
        if(!empty($this->request->data['Page'])) {
            $toSave = array();
            $toSave['title'] = trim($this->request->data['Page']['title']);
            $toSave['slug'] = $this->Tools->slugMe($toSave['title']);
            $toSave['content'] = $this->request->data['Page']['content'];
            $toSave['public'] = $this->request->data['Page']['public'];
            $toSave['published'] = $this->request->data['Page']['published'];
            if($this->Page->save($toSave)) {
                $this->Session->setFlash(__('%s has been added to your pages list', $toSave['title']), 'flash_success');
                return $this->redirect('/admin/cms');
            }

            $this->Session->setFlash(__('Something goes wrong'), 'flash_error');
        }
    }

    public function delete($id = null) {
        if($id) {
            $params = array();
            $params['fields'] = array('id');
            $params['recursive'] = -1;
            $params['conditions']['id'] = $id;
            $params['conditions']['published'] = array('0', '1');
            if(!$page = $this->Page->find('first', $params)) {
                $this->Session->setFlash(__('MushRaider can\'t find this page to delete oO'), 'flash_warning');
            }elseif($this->Page->delete($id)) {
                $this->Session->setFlash(__('The page has been deleted'), 'flash_success');
            }else {
                $this->Session->setFlash(__('Something goes wrong'), 'flash_error');
            }
        }
 
        return $this->redirect('/admin/cms');
    }

    public function disable($id = null) {
        if($id) {
            $toSave = array();
            $toSave['id'] = $id;
            $toSave['published'] = 0;
            if($this->Page->save($toSave)) {
                $this->Session->setFlash(__('The page has been sent to draft'), 'flash_success');
            }else {
                $this->Session->setFlash(__('Something goes wrong'), 'flash_error');
            }
        }
 
        return $this->redirect('/admin/cms');
    }

    public function enable($id = null) {
        if($id) {
            $toSave = array();
            $toSave['id'] = $id;
            $toSave['published'] = 1;
            if($this->Page->save($toSave)) {
                $this->Session->setFlash(__('The page has been published'), 'flash_success');
            }else {
                $this->Session->setFlash(__('Something goes wrong'), 'flash_error');
            }
        }
 
        return $this->redirect('/admin/cms');
    }
}