<?php
class EventsController extends AdminAppController {
    public $uses = array('EventsTemplate', 'EventsTemplatesRole');

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