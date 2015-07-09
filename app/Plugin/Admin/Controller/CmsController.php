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
            $toSave['onMenu'] = $this->request->data['Page']['onMenu'];
            if($this->Page->save($toSave)) {
                // Add the page to the main menu
                if($toSave['onMenu'] && $toSave['published']) {
                    $customLinks = json_decode($this->Setting->getOption('links'), true);
                    $customLinks[] = array(
                        'title' => $toSave['title'],
                        'url' => rtrim(Configure::read('Config.appUrl').'/pages/'.$this->Page->getLastInsertId().'/'.$toSave['slug'], '/')
                    );

                    $this->Setting->setOption('links', json_encode($customLinks));
                }

                $this->Session->setFlash(__('%s has been added to your pages list', $toSave['title']), 'flash_success');
                return $this->redirect('/admin/cms');
            }

            $this->Session->setFlash(__('Something goes wrong'), 'flash_error');
        }
    }

    public function edit($id = null) {
        if(!$id) {
            $this->redirect('/admin/cms');
        }

        $params = array();
        $params['recursive'] = -1;
        $params['conditions']['id'] = $id;
        $params['conditions']['published'] = array(0, 1);
        if(!$page = $this->Page->find('first', $params)) {
            $this->Session->setFlash(__('MushRaider is unable to find this page oO'), 'flash_error');
            $this->redirect('/admin/cms');
        }

        if(!empty($this->request->data['Page']) && $this->request->data['Page']['id'] == $id) {
            $toSave = array();
            $toSave['id'] = $id;
            $toSave['title'] = trim($this->request->data['Page']['title']);
            $toSave['content'] = $this->request->data['Page']['content'];
            $toSave['public'] = $this->request->data['Page']['public'];
            $toSave['published'] = $this->request->data['Page']['published'];
            $toSave['onMenu'] = $this->request->data['Page']['onMenu'];
            if($this->Page->save($toSave)) {

                // Add the page to the main menu
                if($toSave['onMenu'] && $toSave['published']) {
                    $customLinks = json_decode($this->Setting->getOption('links'), true);
                    $link = array(
                        'title' => $toSave['title'],
                        'url' => rtrim(Configure::read('Config.appUrl').'/pages/'.$toSave['id'].'/'.$page['Page']['slug'], '/'),
                    );
                    if(!empty($customLinks)) {
                        $founded = false;
                        foreach($customLinks as $key => $customLink) {
                            if($link['url'] == $customLink['url']) {
                                $founded = $key;
                            }
                        }

                        if(!$founded) {
                            $customLinks[] = $link;
                        }else {
                            $customLinks[$key] = $link;
                        }
                    }else {
                        $customLinks[] = $link;
                    }
                    $this->Setting->setOption('links', json_encode($customLinks));
                }else { // remove it :p
                    $this->removeFromMenu($toSave['id']);
                }

                $this->Session->setFlash(__('Page %s has been updated', $toSave['title']), 'flash_success');
                return $this->redirect('/admin/cms');
            }

            $this->Session->setFlash(__('Something goes wrong'), 'flash_error');

            $page['Page'] = array_merge($page['Page'], $this->request->data['Page']);
        }

        $this->request->data['Page'] = $page['Page'];
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
                $this->removeFromMenu($id);
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
                $this->removeFromMenu($id);
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

    private function removeFromMenu($pageId) {
        $params = array();
        $params['recursive'] = -1;
        $params['conditions']['id'] = $pageId;
        $params['conditions']['published'] = array(0, 1);
        if(!$page = $this->Page->find('first', $params)) {
            return;
        }

        $customLinks = json_decode($this->Setting->getOption('links'), true);
        $link = array(
            'title' => $page['Page']['title'],
            'url' => rtrim(Configure::read('Config.appUrl').'/pages/'.$page['Page']['id'].'/'.$page['Page']['slug'], '/'),
        );

        if(!empty($customLinks)) {
            foreach($customLinks as $key => $customLink) {
                if($link['url'] == $customLink['url']) {
                    unset($customLinks[$key]);
                }
            }
        }

        $this->Setting->setOption('links', json_encode($customLinks));

        $page['Page']['onMenu'] = 0;
        $this->Page->save($page);
    }
}