<?php
class EventsController extends ApiAppController {
    var $uses = array('Event');

    public function index() {
        $calStart = date('Y-m-d');
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
        $params['contain']['Game']['fields'] = array('title');
        $params['contain']['Dungeon']['fields'] = array('title');
        $params['conditions']['Event.time_invitation >='] = $calStart;
        $params['conditions']['Event.time_invitation <='] = $calEnd;
        if(!empty($this->request->params['named']['game'])) {
            $params['conditions']['Event.game_id'] = $this->request->params['named']['game'];
        }
        $events = $this->Event->find('all', $params);
        $this->set(array(
            'events' => $events,
            '_serialize' => array('events')
        )); 
    }

    public function view($id) {
        $params = array();
        $params['recursive'] = 2;
        $params['fields'] = array('User.id', 'User.username');
        $params['contain']['Role']['fields'] = array('title');
        $params['contain']['Character']['fields'] = array('id', 'title', 'level', 'status');
        $params['contain']['Character']['Game']['fields'] = array('title');
        $params['contain']['Character']['Classe']['fields'] = array('title');
        $params['contain']['Character']['Race']['fields'] = array('title');
        $params['conditions']['User.id'] = $id;
        $user = $this->User->find('first', $params);
        $this->set(array(
            'user' => $user,
            '_serialize' => array('user')
        ));   
    }
}