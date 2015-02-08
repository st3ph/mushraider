<?php
class StatsController extends AdminAppController {
    public $uses = array('Game', 'Event', 'EventsCharacter', 'EventsRole', 'Character');

    function beforeFilter() {
        parent::beforeFilter();
    }

    public function index() {
        if(!empty($this->request->data['Stats']['game_id'])) {
            $this->Event->Behaviors->detach('Commentable');

            $gameId = $this->request->data['Stats']['game_id'];

            $params = array();
            $params['recursive'] = 1;
            $params['fields'] = array('id', 'title', 'created');
            $params['order'] = array('Character.title' => 'asc');
            $params['contain']['User'] = array();
            $params['contain']['User']['fields'] = array('username');
            $params['contain']['EventsCharacter'] = array();
            $params['contain']['EventsCharacter']['fields'] = array('status');
            if(!empty($this->request->data['Stats']['start'])) {
                $params['contain']['EventsCharacter']['conditions']['created >='] = $this->Tools->reverseDate($this->request->data['Stats']['start']);
            }
            if(!empty($this->request->data['Stats']['end'])) {
                $params['contain']['EventsCharacter']['conditions']['created <='] = $this->Tools->reverseDate($this->request->data['Stats']['end']);
            }
            $params['contain']['Classe'] = array();
            $params['contain']['Classe']['fields'] = array('title', 'color');
            $params['contain']['RaidsRole'] = array();
            $params['contain']['RaidsRole']['fields'] = array('title');
            $params['conditions']['Character.game_id'] = $gameId;
            if(!isset($this->request->data['Stats']['characters']) || $this->request->data['Stats']['characters']) {
                $params['conditions']['Character.main'] = 1;
            }
            if($characters = $this->Character->find('all', $params)) {
                foreach($characters as $key => $character) {
                    $characters[$key]['stats'] = array();
                    $characters[$key]['stats']['total'] = count($character['EventsCharacter']);
                    for($status = 0;$status <= 2;$status++) {
                        $characters[$key]['stats']['status_'.$status] = count(Set::extract('/EventsCharacter[status='.$status.']', $character));
                    }

                    // Not signin at all
                    $characters[$key]['stats']['events_total'] = 0;
                    $characters[$key]['stats']['events_registered'] = 0;
                    $characters[$key]['stats']['events_unregistered'] = 0;

                    $params = array();
                    $params['recursive'] = 1;
                    $params['fields'] = array('id');
                    $params['contain']['EventsCharacter'] = array();
                    $params['contain']['EventsCharacter']['fields'] = array('user_id');
                    $params['contain']['EventsCharacter']['conditions']['EventsCharacter.character_id'] = $character['Character']['id'];
                    $params['conditions']['Event.time_start >='] = $character['Character']['created'];
                    $params['conditions']['Event.game_id'] = $gameId;
                    if(!empty($this->request->data['Stats']['start'])) {
                        $params['conditions']['Event.time_start >='] = $this->Tools->reverseDate($this->request->data['Stats']['start']);
                    }
                    if(!empty($this->request->data['Stats']['end'])) {
                        $params['conditions']['Event.time_start <='] = $this->Tools->reverseDate($this->request->data['Stats']['end']);
                    }
                    if($events = $this->Event->find('all', $params)) {
                        $characters[$key]['stats']['events_total'] = count($events);
                        foreach($events as $event) {
                            if(empty($event['EventsCharacter'])) {
                                $characters[$key]['stats']['events_unregistered']++;
                            }else {
                                $characters[$key]['stats']['events_registered']++;
                            }
                        }
                    }
                }
            }

            $this->set('characters', $characters);
        }

        $gamesList = $this->Game->find('list', array('order' => 'title ASC'));        
        $this->set('gamesList', $gamesList);
    }
}