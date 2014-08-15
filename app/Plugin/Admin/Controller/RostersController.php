<?php
class RostersController extends AdminAppController {
    public $uses = array('Character', 'Game', 'Classe', 'Race', 'RaidsRole');

    function beforeFilter() {
        parent::beforeFilter();
    }

    public function index() {
        if(!empty($this->request->data['Roster']['game_id'])) {
            $params = array();
            $params['recursive'] = 1;
            $params['order'] = array('Character.title');
            $params['contain'] = array();
            $params['contain']['Classe'] = array();
            $params['contain']['Race'] = array();
            $params['contain']['User'] = array();
            $params['contain']['RaidsRole'] = array();
            $params['conditions']['Character.game_id'] = $this->request->data['Roster']['game_id'];
            $characters = $this->Character->find('all', $params);
            $this->set('characters', $characters);    
        }

        $gamesList = $this->Game->find('list', array('order' => array('title ASC')));
        $this->set('gamesList', $gamesList);

        $rolesList = $this->RaidsRole->find('list', array('order' => array('order ASC', 'title ASC')));
        $this->set('rolesList', $rolesList);
    }
}