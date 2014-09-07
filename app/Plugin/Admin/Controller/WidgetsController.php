<?php
class WidgetsController extends AdminAppController {
    public $components = array();
    public $uses = array('Widget', 'Game');

    public function index() {
        $params = array();
        $params['recursive'] = -1;
        $params['order'] = array('id', 'title');
        $params['conditions']['status'] = array(0, 1);
        $widgets = $this->Widget->find('all', $params);
        $this->set('widgets', $widgets);
    }

    public function add() {
        $availableWidgets = $this->Widget->getAvailableWidgets();
        $selectedWidget = null;

        if(!empty($this->request->data['Widget'])) {
            $selectedWidget = !empty($availableWidgets[$this->request->data['Widget']['type']])?$this->request->data['Widget']['type']:null;

            if(!empty($this->request->data['Widget']['title'])) {
                list($wController, $wAction) = explode('_', $this->request->data['Widget']['type']);
                $toSave = array();
                $toSave['title'] = trim($this->request->data['Widget']['title']);
                $toSave['controller'] = $wController;
                $toSave['action'] = $wAction;
                $toSave['params'] = json_encode($this->request->data['Widget']['params']);
                $toSave['status'] = 1;
                if($this->Widget->save($toSave)) {
                    $this->Session->setFlash(__('%s has been added to your widgets list', $toSave['title']), 'flash_success');
                    return $this->redirect('/admin/widgets');
                }

                $this->Session->setFlash(__('Something goes wrong'), 'flash_error');
            }
        }
        
        $gamesList = $this->Game->find('list', array('order' => 'title ASC'));
        $this->set('gamesList', $gamesList);
        $this->set('availableWidgets', $availableWidgets);
        $this->set('selectedWidget', $selectedWidget);
    }

    public function edit($id = null) {
        if(!$id) {
            return $this->redirect('/admin/widgets');
        }

        $availableWidgets = $this->Widget->getAvailableWidgets();

        $params = array();
        $params['recursive'] = -1;
        $params['conditions']['Widget.id'] = $id;
        if(!$widget = $this->Widget->find('first', $params)) {
            $this->Session->setFlash(__('MushRaider is unable to find this widget oO'), 'flash_error');
            return $this->redirect('/admin/widgets');
        }
        $params = json_decode($widget['Widget']['params']);
        $widget['Widget']['params'] = array();
        if(!empty($params)) {
            foreach($params as $key => $param) {
                $widget['Widget']['params'][$key] = $param;
            }
        }

        if(!empty($this->request->data['Widget']) && $this->request->data['Widget']['id'] == $id) {
            if(!empty($this->request->data['Widget']['title'])) {
                $toSave = array();
                $toSave['id'] = $this->request->data['Widget']['id'];
                $toSave['title'] = trim($this->request->data['Widget']['title']);
                $toSave['params'] = json_encode($this->request->data['Widget']['params']);
                $toSave['status'] = 1;
                if($this->Widget->save($toSave)) {
                    $this->Session->setFlash(__('%s has been updated', $toSave['title']), 'flash_success');
                    return $this->redirect('/admin/widgets');
                }

                $this->Session->setFlash(__('Something goes wrong'), 'flash_error');
            }

            $widget['Widget'] = array_merge($widget['Widget'], $this->request->data['Widget']);
        }

        $this->request->data['Widget'] = $widget['Widget'];

        $gamesList = $this->Game->find('list', array('order' => 'title ASC'));
        $this->set('gamesList', $gamesList);
        $this->set('availableWidgets', $availableWidgets);
    }

    public function delete($id = null) {
        if($id) {
            $params = array();
            $params['fields'] = array('id');
            $params['recursive'] = -1;
            $params['conditions']['id'] = $id;
            $params['conditions']['status'] = array('0', '1');
            if(!$widget = $this->Widget->find('first', $params)) {
                $this->Session->setFlash(__('MushRaider can\'t find this widget to delete oO'), 'flash_warning');
            }elseif($this->Widget->delete($id)) {
                $this->Session->setFlash(__('The widget has been deleted'), 'flash_success');
            }else {
                $this->Session->setFlash(__('Something goes wrong'), 'flash_error');
            }
        }
 
        return $this->redirect('/admin/widgets');
    }

    public function disable($id = null) {
        if($id) {
            $toSave = array();
            $toSave['id'] = $id;
            $toSave['status'] = 0;
            if($this->Widget->save($toSave)) {
                $this->Session->setFlash(__('The widget has been disable'), 'flash_success');
            }else {
                $this->Session->setFlash(__('Something goes wrong'), 'flash_error');
            }
        }
 
        return $this->redirect('/admin/widgets');
    }

    public function enable($id = null) {
        if($id) {
            $toSave = array();
            $toSave['id'] = $id;
            $toSave['status'] = 1;
            if($this->Widget->save($toSave)) {
                $this->Session->setFlash(__('The widget has been enable'), 'flash_success');
            }else {
                $this->Session->setFlash(__('Something goes wrong'), 'flash_error');
            }
        }
 
        return $this->redirect('/admin/widgets');
    }
}