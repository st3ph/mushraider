<?php
class ExportController extends AppController {    
    var $helpers = array();
    var $uses = array('Event');
    var $components = array('RequestHandler');

    var $userRequired = false;

    public function beforeFilter() {
        parent::beforeFilter();

    }

    public function events($key = null, $filterGameId = null) {
        if(!$key) {
            throw new NotFoundException();
        }

        $params = array();
        $params['fields'] = array('id');
        $params['conditions']['calendar_key'] = $key;
        if(!$this->User->find('first', $params)) {
            throw new NotFoundException();
        }

        $this->layout = false;

        switch($this->request->params['ext']) {
            case 'ics':
                $this->RequestHandler->respondAs('text/calendar');
                break;
            case 'xml':
                $this->RequestHandler->respondAs('text/xml');
                break;
        }

        // Get events
        $params = array();
        $params['fields'] = array('Event.id', 'Event.title', 'Event.description', 'Event.game_id', 'Event.time_invitation', 'Game.title', 'Dungeon.title');
        $params['order'] = 'Event.time_invitation ASC';
        $params['recursive'] = 1;
        $params['contain']['Game'] = array();
        $params['contain']['Dungeon'] = array();
        $params['conditions']['Event.time_invitation >='] = date('Y-m-d 00:00:00');
        if($filterGameId) {
            $params['conditions']['Event.game_id'] = $filterGameId[0];
        }
        $events = $this->Event->find('all', $params);

        $timezone = $this->Setting->getOption('timezone');
        $calendarOptions = json_decode($this->Setting->getOption('calendar'));

        $this->set('events', $events);
        $this->set('timezone', $timezone);
        $this->set('calendarOptions', $calendarOptions);
    }
}