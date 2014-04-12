<?php
class EventsController extends AppController {
    public $components = array('Emailing');
    var $helpers = array('Sociable.Comment');
    var $uses = array('Game', 'Dungeon', 'Event', 'RaidsRole', 'EventsRole', 'EventsCharacter', 'Character');

    public function beforeFilter() {
        parent::beforeFilter();

        $this->breadcrumb[] = array('title' => __('Events'), 'url' => '/events');
    }

    public function index() {
        $this->pageTitle = __('Events').' - '.$this->pageTitle;

        $calendarOptions = array();
        $calendarOptions['month'] = !empty($this->request->params['named']['m'])?$this->request->params['named']['m']:date('n');
        $calendarOptions['year'] = !empty($this->request->params['named']['y'])?$this->request->params['named']['y']:date('Y');
        $calendarOptions['user'] = $this->user;

        // Get events
        $params = array();
        $params['fields'] = array('Event.id', 'Event.title', 'Event.game_id', 'Event.dungeon_id', 'Event.time_invitation', 'Game.title', 'Game.logo', 'Dungeon.title');
        $params['order'] = 'Event.time_invitation';
        $params['recursive'] = 2;
        $params['contain']['Game'] = array();
        $params['contain']['Dungeon'] = array();
        $params['contain']['EventsCharacter']['Character'] = array();
        $params['conditions']['Event.time_invitation >='] = $calendarOptions['year'] .'-'.$calendarOptions['month'].'-01';
        $params['conditions']['Event.time_invitation <='] = $calendarOptions['year'] .'-'.$calendarOptions['month'].'-31 23:59:59';
        $events = $this->Event->find('all', $params);
        
        $this->set('events', $events);
        $this->set('calendarOptions', $calendarOptions);
    }

    public function view($eventId) {
        $this->pageTitle = __('View event').' - '.$this->pageTitle;        

        $params = array();        
        $params['recursive'] = 2;
        $params['conditions']['Event.id'] = $eventId;
        $params['contain']['Game'] = array();
        $params['contain']['Dungeon'] = array();
        $params['contain']['User'] = array();
        $params['contain']['EventsRole']['RaidsRole'] = array();
        $params['contain']['EventsCharacter']['Character']['Classe'] = array();        
        $params['contain']['EventsCharacter']['Character']['User'] = array();        
        if(!$event = $this->Event->find('first', $params)) {
            $this->Session->setFlash(__('MushRaider can\'t find this event oO'), 'flash_warning');
            $this->redirect('/events');
        }
        $this->set('event', $event);

        // Get user characters for this event
        $params = array();
        $params['conditions']['user_id'] = $this->user['User']['id'];
        $params['conditions']['game_id']=  $event['Event']['game_id'];
        $params['conditions']['level >='] = $event['Event']['character_level'];
        $this->set('charactersList', $this->Character->find('list', $params));

        // Get bad guys
        $usersInEvent = array();
        if(!empty($event) && !empty($event['EventsCharacter'])) {
            foreach($event['EventsCharacter'] as $character) {
                array_push($usersInEvent, $character['Character']['User']['id']);
            }
        }

        $params = array();
        $params['recursive'] = -1;
        $params['fields'] = array('User.username');
        $params['joins'] = array(
                array(
                    'table' => 'characters',
                    'alias' => 'Character',
                    'type' => 'LEFT',
                    'conditions' => array('Character.user_id = User.id', 'Character.status > 0')
                )
            );
        $params['conditions']['NOT'] = array('User.id' => $usersInEvent);
        $params['conditions']['Character.game_id'] = $event['Event']['game_id'];
        $params['group'] = array('User.id');
        $badGuys = $this->User->find('all', $params);
        $this->set('badGuys', $badGuys);

        $this->breadcrumb[] = array('title' => (!empty($event['Event']['title'])?$event['Event']['title']:$event['Dungeon']['title']), 'url' => '');
    }

    public function add($date = null) {
        $this->pageTitle = __('Add event').' - '.$this->pageTitle;
        $this->breadcrumb[] = array('title' => __('Add event'), 'url' => '');

        if(!$this->user['User']['isOfficer'] && !$this->user['User']['isAdmin']) {
            $this->Session->setFlash(__('You don\'t have permission to access this page.'), 'flash_error');
            $this->redirect('/events');
        }

        if(!$date) {
            $date = date('Y-m-d');
        }

        if(!empty($this->request->data['Event'])) {
            $dates = explode('-', $date);

            $toSave = array();
            $toSave['title'] = strip_tags($this->request->data['Event']['title']);
            $toSave['description'] = nl2br($this->request->data['Event']['description']);
            $toSave['user_id'] = $this->user['User']['id'];
            $toSave['game_id'] = $this->request->data['Event']['game_id'];
            $toSave['dungeon_id'] = $this->request->data['Event']['dungeon_id'];
            $toSave['character_level'] = $this->request->data['Event']['character_level'];
            $toSave['time_invitation'] = date('Y-m-d H:i:s', mktime($this->request->data['Event']['time_invitation']['hour'], $this->request->data['Event']['time_invitation']['min'], 0, $dates[1], $dates[2], $dates[0]));
            $toSave['time_start'] = date('Y-m-d H:i:s', mktime($this->request->data['Event']['time_start']['hour'], $this->request->data['Event']['time_start']['min'], 0, $dates[1], $dates[2], $dates[0]));            

            if(!empty($this->request->data['Event']['roles'])) {
                if($this->Event->save($toSave)) {
                    $eventId = $this->Event->getLastInsertId();

                    $paramsEvent = array();
                    $paramsEvent['recursive'] = 1;
                    $paramsEvent['contain']['Game']['fields'] = array('Game.title');
                    $paramsEvent['contain']['Dungeon']['fields'] = array('Dungeon.title');
                    $paramsEvent['conditions']['Event.id'] = $eventId;
                    $event = $this->Event->find('first', $paramsEvent);

                    foreach($this->request->data['Event']['roles'] as $roleId => $roleNumber) {
                        $toSaveEventsRole = array();
                        $toSaveEventsRole['event_id'] = $eventId;
                        $toSaveEventsRole['raids_role_id'] = $roleId;
                        $toSaveEventsRole['count'] = $roleNumber?$roleNumber:'0';
                        $this->EventsRole->__add($toSaveEventsRole);
                    }

                    // If notifications are enable, send email to validate users
                    if($this->Setting->getOption('notifications')) {
                        // Get all users who have a character for this event
                        $params = array();
                        $params['recursive'] = 1;
                        $params['fields'] = array('id');
                        $params['group'] = array('Character.user_id');
                        $params['contain']['User']['fields'] = array('email', 'notifications_new');
                        $params['contain']['User']['conditions']['User.status'] = 1;
                        $params['conditions']['Character.game_id'] = $toSave['game_id'];
                        $params['conditions']['Character.level >='] = !empty($toSave['character_level'])?$toSave['character_level']:1;
                        if($users = $this->Character->find('all', $params)) {                            
                            foreach($users as $user) {
                                // Check if user have this notification enabled
                                if($user['User']['notifications_new']) {
                                    $this->Emailing->eventNew($user['User']['email'], $event);
                                }
                            }
                        }
                    }                    

                    $this->Session->setFlash(__('The event has been created.'), 'flash_success');
                    $this->redirect('/events');
                }
            }

            $this->Session->setFlash(__('Something wrong happen, please fix the errors below'), 'flash_error');
        }


        $gamesList = $this->Game->find('list', array('order' => 'title ASC'));
        $this->set('gamesList', $gamesList);
        $rolesList = $this->RaidsRole->find('list', array('order' => 'title ASC'));
        $this->set('rolesList', $rolesList);
        $this->set('eventDate', $date);
    }

    public function edit($eventId) {
        $this->pageTitle = __('Edit event').' - '.$this->pageTitle;

        if(!$this->user['User']['isOfficer'] && !$this->user['User']['isAdmin']) {
            $this->Session->setFlash(__('You don\'t have permission to access this page.'), 'flash_error');
            $this->redirect('/events');
        }

        if(!$eventId) {
            $this->redirect('/events');
        }

        if(!empty($this->request->data['Event']) && $this->request->data['Event']['id'] == $eventId) {
            // Get event date
            $params = array();        
            $params['recursive'] = -1;
            $params['fields'] = array('time_invitation');
            $params['conditions']['Event.id'] = $eventId;            
            if(!$event = $this->Event->find('first', $params)) {
                $this->redirect('/events');
            }            
            $date = explode(' ', $event['Event']['time_invitation']);
            $dates = explode('-', $date[0]);

            $toSave = array();
            $toSave['id'] = $this->request->data['Event']['id'];
            $toSave['title'] = strip_tags($this->request->data['Event']['title']);
            $toSave['description'] = nl2br($this->request->data['Event']['description']);
            $toSave['user_id'] = $this->user['User']['id'];
            $toSave['game_id'] = $this->request->data['Event']['game_id'];
            $toSave['dungeon_id'] = $this->request->data['Event']['dungeon_id'];
            $toSave['character_level'] = $this->request->data['Event']['character_level'];
            $toSave['time_invitation'] = date('Y-m-d H:i:s', mktime($this->request->data['Event']['time_invitation']['hour'], $this->request->data['Event']['time_invitation']['min'], 0, $dates[1], $dates[2], $dates[0]));
            $toSave['time_start'] = date('Y-m-d H:i:s', mktime($this->request->data['Event']['time_start']['hour'], $this->request->data['Event']['time_start']['min'], 0, $dates[1], $dates[2], $dates[0]));            
            if(!empty($this->request->data['Event']['roles'])) {
                if($this->Event->save($toSave)) {
                    foreach($this->request->data['Event']['roles'] as $roleId => $roleNumber) {
                        $toSaveEventsRole = array();
                        $toSaveEventsRole['event_id'] = $eventId;
                        $toSaveEventsRole['raids_role_id'] = $roleId;
                        $toSaveEventsRole['count'] = $roleNumber?$roleNumber:'0';
                        $this->EventsRole->__add($toSaveEventsRole);
                    }

                    $this->Session->setFlash(__('The event has been updated.'), 'flash_success');
                    $this->redirect('/events/view/'.$eventId);
                }
            }

            $this->Session->setFlash(__('Something wrong happen, please fix the errors below'), 'flash_error');
        }

        // Get event infos
        $params = array();        
        $params['recursive'] = 1;
        $params['conditions']['Event.id'] = $eventId;
        $params['contain']['EventsRole'] = array();        
        if(!$event = $this->Event->find('first', $params)) {
            $this->Session->setFlash(__('MushRaider can\'t find this event oO'), 'flash_warning');
            $this->redirect('/events');
        }
        $this->request->data['Event'] = !empty($this->request->data['Event'])?array_merge($event['Event'], $this->request->data['Event']):$event['Event'];        
        $this->request->data['EventsRole'] = $event['EventsRole'];

        $gamesList = $this->Game->find('list', array('order' => 'title ASC'));
        $this->set('gamesList', $gamesList);
        $rolesList = $this->RaidsRole->find('list', array('order' => 'title ASC'));
        $this->set('rolesList', $rolesList);
        $this->set('eventDate', $event['Event']['time_invitation']);
        $this->breadcrumb[] = array('title' => (!empty($event['Event']['title'])?$event['Event']['title']:$event['Dungeon']['title']), 'url' => '');
    }

    public function delete($eventId) {
        if(!$this->user['User']['isOfficer'] && !$this->user['User']['isAdmin']) {
            $this->Session->setFlash(__('You don\'t have permission to access this page.'), 'flash_error');
            $this->redirect('/events');
        }

        if(!$eventId) {
            $this->redirect('/events');
        }

        // Get event for email notifications
        $params = array();
        $params['recursive'] = -1;
        $params['conditions']['id'] = $eventId;
        if(!$event = $this->Event->find('first', $params)) {
            $this->redirect('/events');
        }

        if($this->Event->delete($eventId)) {
            // If notifications are enable, send email to validate users
            if($this->Setting->getOption('notifications')) {
                // Get all users validated
                $params = array();
                $params['recursive'] = 1;
                $params['fields'] = array('id');
                $params['contain']['User']['fields'] = array('email', 'notifications_cancel');
                $params['conditions']['EventsCharacter.event_id'] = $eventId;
                $params['conditions']['EventsCharacter.status'] = 2;
                if($users = $this->EventsCharacter->find('all', $params)) {
                    foreach($users as $user) {
                        // Check if user have this notification enabled
                        if($user['User']['notifications_cancel']) {
                            $this->Emailing->eventCancel($user['User']['email'], $event['Event']);
                        }
                    }
                }
            }

            // Delete childs
            $conditions = array('event_id' => $eventId);
            $this->EventsRole->deleteAll($conditions);
            $this->EventsCharacter->deleteAll($conditions);
            $this->Session->setFlash(__('The event has been deleted.'), 'flash_warning');
        }else {
            $this->Session->setFlash(__('MushRaider can\'t delete this event.'), 'flash_error');
        }
        $this->redirect('/events');
    }
}