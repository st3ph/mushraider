<?php
class RostersController extends AdminAppController {
    public $uses = array('Character', 'Game', 'Classe', 'Race', 'RaidsRole', 'Availability');

    function beforeFilter() {
        parent::beforeFilter();
    }

    public function index() {
        $characterTypes = array(
            'all' => __('All'),
            'main' => __('Main characters'),
            'reroll' => __('Reroll characters'),
        );
        $this->set('characterTypes', $characterTypes);

        $rolesList = $this->RaidsRole->find('list', array('order' => array('order ASC', 'title ASC')));
        $this->set('rolesList', $rolesList);

        $gamesList = $this->Game->find('list', array('order' => array('title ASC')));
        $this->set('gamesList', $gamesList);

        // If only 1 game, select it by default
        if(count($gamesList) == 1) {
            $this->request->data['Roster']['game_id'] = key($gamesList);
            $this->request->data['Roster']['type'] = 'all';
        }


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
            switch($this->request->data['Roster']['type']) {
                case 'main':
                    $params['conditions']['Character.main'] = 1;
                    break;
                case 'reroll':
                    $params['conditions']['Character.main'] = 0;
                    break;
            }
            $characters = $this->Character->find('all', $params);
            $this->set('characters', $characters);

            // Absents    
            $params = array();
            $params['recursive'] = 2;
            $params['fields'] = array('Availability.start', 'Availability.end', 'Availability.comment', 'User.username');
            $params['order'] = array('Availability.start ASC');
            $params['contain'] = array();
            $params['contain']['User']['Character']['fields'] = array('Character.title', 'Character.level');
            $params['contain']['User']['Character']['conditions']['Character.game_id'] = $this->request->data['Roster']['game_id'];
            $params['contain']['User']['Character']['Classe'] = array();
            $params['contain']['User']['Character']['Classe']['fields'] = array('Classe.icon', 'Classe.color', 'Classe.title');
            $params['contain']['User']['Character']['RaidsRole'] = array();
            $params['conditions']['Availability.end >='] = date('Y-m-d');
            if($absents = $this->Availability->find('all', $params)) {
                // Clean up for this game id
                foreach($absents as $key => $absent) {
                    if(empty($absent['User']['Character'])) {
                        unset($absents[$key]);
                    }
                }
            }
            $this->set('absents', $absents);
        }
    }
}