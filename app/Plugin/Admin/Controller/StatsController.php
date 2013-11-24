<?php
class StatsController extends AdminAppController {
    public $uses = array('Game', 'Event', 'EventsCharacter', 'EventsRole', 'Character');

    var $adminOnly = true;

    function beforeFilter() {
        parent::beforeFilter();
    }

    public function index() {
        if(!empty($this->request->data['Stats']['game_id'])) {
            $gameId = $this->request->data['Stats']['game_id'];

            $params = array();
            $params['recursive'] = 1;
            $params['fields'] = array('id', 'title');
            $params['order'] = array('Character.title' => 'asc');
            $params['contain']['User'] = array();
            $params['contain']['User']['fields'] = array('username');
            $params['contain']['EventsCharacter'] = array();
            $params['contain']['EventsCharacter']['fields'] = array('status');
            $params['contain']['Classe'] = array();
            $params['contain']['Classe']['fields'] = array('title', 'color');
            $params['contain']['RaidsRole'] = array();
            $params['contain']['RaidsRole']['fields'] = array('title');
            $params['conditions']['Character.game_id'] = $gameId;
            if($characters = $this->Character->find('all', $params)) {
                foreach($characters as $key => $character) {
                    $characters[$key]['stats'] = array();
                    $characters[$key]['stats']['total'] = count($character['EventsCharacter']);
                    for($status = 0;$status <= 2;$status++) {
                        $characters[$key]['stats']['status_'.$status] = count(Set::extract('/EventsCharacter[status='.$status.']', $character));
                    }
                }
            }

            $this->set('characters', $characters);
        }

        $gamesList = $this->Game->find('list', array('order' => 'title ASC'));        
        $this->set('gamesList', $gamesList);
    }
}