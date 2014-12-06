<?php
class EventsController extends AdminAppController {
    public $uses = array('EventsTemplate', 'EventsTemplatesRole', 'Dungeon');

    function beforeFilter() {
        parent::beforeFilter();
    }

    public function templates() {
        $params = array();
        $params['recursive'] = 1;
        $params['order'] = array('Game.title ASC', 'EventsTemplate.title ASC');
        $params['contain']['Game']['fields'] = array('title');
        $params['contain']['EventsTemplatesRole'] = array();
        $params['contain']['Dungeon']['fields'] = array('title');
        $params['contain']['EventsTemplatesRole']['RaidsRole']['fields'] = array('title');
        $templates = $this->EventsTemplate->find('all', $params);
        $this->set('templates', $templates);
    }

    public function template_edit($id = null) {
        if(!$id) {
            return $this->redirect('/admin/events/templates');
        }

        $params = array();
        $params['recursive'] = 1;
        $params['order'] = array('Game.title ASC', 'EventsTemplate.title ASC');
        $params['contain']['Game']['fields'] = array('title');
        $params['contain']['EventsTemplatesRole'] = array();
        $params['contain']['Dungeon']['fields'] = array('title');
        $params['contain']['EventsTemplatesRole']['RaidsRole']['fields'] = array('title');
        $params['conditions']['EventsTemplate.id'] = $id;
        if(!$EventsTemplate = $this->EventsTemplate->find('first', $params)) {
            $this->Session->setFlash(__('MushRaider is unable to find this event template oO'), 'flash_error');
            return $this->redirect('/admin/events/templates');
        }

        if(!empty($this->request->data['EventsTemplate'])) {
            $dates = explode('-', date('Y-m-d'));

            $toSave = array();
            $toSave['EventsTemplate']['id'] = $EventsTemplate['EventsTemplate']['id'];
            $toSave['EventsTemplate']['title'] = $this->request->data['EventsTemplate']['title'];
            $toSave['EventsTemplate']['event_title'] = $this->request->data['EventsTemplate']['event_title'];
            $toSave['EventsTemplate']['event_description'] = $this->request->data['EventsTemplate']['event_description'];
            $toSave['EventsTemplate']['dungeon_id'] = $this->request->data['EventsTemplate']['dungeon_id'];
            $toSave['EventsTemplate']['time_invitation'] = date('Y-m-d H:i:s', mktime($this->request->data['EventsTemplate']['time_invitation']['hour'], $this->request->data['EventsTemplate']['time_invitation']['min'], 0, $dates[1], $dates[2], $dates[0]));
            $toSave['EventsTemplate']['time_start'] = date('Y-m-d H:i:s', mktime($this->request->data['EventsTemplate']['time_start']['hour'], $this->request->data['EventsTemplate']['time_start']['min'], 0, $dates[1], $dates[2], $dates[0]));
            $toSave['EventsTemplate']['character_level'] = $this->request->data['EventsTemplate']['character_level'];
            $toSave['EventsTemplate']['open'] = $this->request->data['EventsTemplate']['open'];
            if(!empty($this->request->data['EventsTemplatesRole'])) {
                foreach($this->request->data['EventsTemplatesRole']['id'] as $key => $value) {
                    $toSave['EventsTemplatesRole'][$key]['id'] = $key;
                    $toSave['EventsTemplatesRole'][$key]['count'] = $value;
                }
            }

            if($this->EventsTemplate->saveAll($toSave)) {
                $this->Session->setFlash(__('Event template %s has been updated', $toSave['EventsTemplate']['title']), 'flash_success');
                return $this->redirect('/admin/events/templates');
            }

            $this->Session->setFlash(__('Something goes wrong'), 'flash_error');
            $role = array_merge($role, $this->request->data);
        }

        $this->request->data = $EventsTemplate;

        $dungeonsList = $this->Dungeon->find('list', array('conditions' => array('game_id' => $this->request->data['EventsTemplate']['game_id']), 'order' => 'title ASC'));
        $this->set('dungeonsList', $dungeonsList);
    }

    public function template_delete($id = null) {
        if($id) {
            $params = array();
            $params['fields'] = array('id', 'title');
            $params['recursive'] = -1;
            $params['conditions']['id'] = $id;
            if($template = $this->EventsTemplate->find('first', $params)) {
                if($this->EventsTemplate->delete($id)) {
                    // Delete childs
                    $conditions = array('event_tpl_id' => $id);
                    $this->EventsTemplatesRole->deleteAll($conditions);                   

                    $this->Session->setFlash(__('The template %s has been deleted', $template['EventsTemplate']['title']), 'flash_success');
                }                
            }else {
                $this->Session->setFlash(__('Something goes wrong'), 'flash_error');
            }
        }
 
        return $this->redirect('/admin/events/templates');
    }    
}