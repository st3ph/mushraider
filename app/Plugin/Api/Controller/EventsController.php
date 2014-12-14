<?php
class EventsController extends ApiAppController {
    var $uses = array('Event');

    public function index() {
        $calStart = date('Y-m-d H:i:s');
        $calEnd = date('Y-m-d H:i:s', mktime(23, 59, 59, date('n'), date('j') + 7, date('Y')));

        if(!empty($this->request->params['named']['start'])) {
            $calStart = date('Y-m-d H:i:s', $this->request->params['named']['start']);
        }

        if(!empty($this->request->params['named']['end'])) {
            $calEnd = date('Y-m-d H:i:s', $this->request->params['named']['end']);
        }

        $params = array();
        $params['recursive'] = 1;
        $params['order'] = 'Event.time_invitation';
        $params['fields'] = array('Event.id', 'Event.title', 'Event.game_id', 'Event.dungeon_id', 'Event.time_invitation', 'Event.time_start');
        $params['contain']['Game']['fields'] = array('title', 'logo');
        $params['contain']['Dungeon']['fields'] = array('title');
        $params['conditions']['Event.time_start >='] = $calStart;
        $params['conditions']['Event.time_start <='] = $calEnd;
        if(!empty($this->request->params['named']['game'])) {
            $params['conditions']['Event.game_id'] = $this->request->params['named']['game'];
        }
        $events = $this->Event->find('all', $params);
        $this->set(array(
            'events' => $events,
            '_serialize' => array('events')
        )); 
    }

    public function view() {
        if(empty($this->request->params['named']['event'])) {
            $this->set(array(
                'event' => array(),
                '_serialize' => array('event')
            ));
            return;
        }

        $params = array();
        $params['recursive'] = 2;
        $params['fields'] = array('Event.id', 'Event.title', 'Event.game_id', 'Event.dungeon_id', 'Event.time_invitation', 'Event.time_start');
        $params['contain']['Game']['fields'] = array('title', 'logo');
        $params['contain']['Dungeon']['fields'] = array('title');
        $params['contain']['User']['fields'] = array('username');
        $params['contain']['EventsRole']['RaidsRole']['fields'] = array('title');
        $params['contain']['EventsCharacter']['Character']['Classe']['fields'] = array('title');      
        $params['contain']['EventsCharacter']['Character']['User']['fields'] = array('username');
        $params['conditions']['Event.id'] = $this->request->params['named']['event'];
        $event = $this->Event->find('first', $params);
        $this->set(array(
            'event' => $event,
            '_serialize' => array('event')
        ));   
    }
}