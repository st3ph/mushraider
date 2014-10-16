<?php
class EventsController extends AppController {
    public $components = array('Emailing', 'Image');
    var $helpers = array('Sociable.Comment');
    var $uses = array('Game', 'Dungeon', 'Event', 'RaidsRole', 'EventsRole', 'EventsGroup', 'EventsCharacter', 'Character', 'EventsTemplate', 'EventsTemplatesRole', 'Report');

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

        // Filter events ?
        $filterEventsGameId = $this->Cookie->read('filterEvents');

        // Get events
        $params = array();
        $params['fields'] = array('Event.id', 'Event.title', 'Event.game_id', 'Event.dungeon_id', 'Event.time_invitation', 'Event.time_start', 'Game.title', 'Game.logo', 'Dungeon.title', 'Dungeon.icon');
        $params['order'] = 'Event.time_invitation';
        $params['recursive'] = 2;
        $params['contain']['Game'] = array();
        $params['contain']['Dungeon'] = array();
        $params['contain']['EventsRole']['RaidsRole'] = array();
        $params['contain']['EventsCharacter']['Character'] = array();
        $params['contain']['Report']['fields'] = array('id');
        $params['conditions']['Event.time_invitation >='] = $calendarOptions['year'] .'-'.$calendarOptions['month'].'-01';
        $params['conditions']['Event.time_invitation <='] = $calendarOptions['year'] .'-'.$calendarOptions['month'].'-31 23:59:59';
        if($filterEventsGameId != 0) {
            $params['conditions']['Event.game_id'] = $filterEventsGameId;
        }
        $events = $this->Event->find('all', $params);

        $gamesList = $this->Game->find('list', array('order' => 'title ASC'));
        $gamesList['0'] = __('-- All games');
        asort($gamesList);
        $this->set('gamesList', $gamesList);
        
        $this->set('events', $events);
        $this->set('calendarOptions', $calendarOptions);
        $this->set('filterEventsGameId', $filterEventsGameId);
    }

    private function orderRoles($a, $b) {
        if(!empty($a['RaidsRole']) && !empty($b['RaidsRole'])) {
            if($a['RaidsRole']['order'] < $b['RaidsRole']['order']) {
                return -1;
            }elseif($a['RaidsRole']['order'] > $b['RaidsRole']['order']) {
                return 1;
            }else {
                return 0;
            }
        }

        return 0;
    }

    public function view($eventId) {
        $this->pageTitle = __('View event').' - '.$this->pageTitle;        

        $params = array();        
        $params['recursive'] = 2;
        $params['conditions']['Event.id'] = $eventId;
        $params['contain']['Game'] = array();
        $params['contain']['Dungeon'] = array();
        $params['contain']['User'] = array();
        $params['contain']['EventsGroup'] = array();
        $params['contain']['EventsRole']['RaidsRole'] = array();
        $params['contain']['EventsCharacter']['Character']['Classe'] = array();        
        $params['contain']['EventsCharacter']['Character']['User'] = array();        
        $params['contain']['Report'] = array();        
        if(!$event = $this->Event->find('first', $params)) {
            $this->Session->setFlash(__('MushRaider can\'t find this event oO'), 'flash_warning');
            $this->redirect('/events');
        }

        // Reorder raids roles
        usort($event['EventsRole'], array($this, 'orderRoles'));

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
        $params['fields'] = array('User.id', 'User.username');
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

        if(!$this->user['User']['can']['manage_events'] && !$this->user['User']['can']['full_permissions']) {
            $this->Session->setFlash(__('You don\'t have permission to access this page.'), 'flash_error');
            $this->redirect('/events');
        }

        if(!$date) {
            $date = date('Y-m-d');
        }

        if(!empty($this->request->data['Event'])) {
            $dates = explode('-', $date);

            $toSave = array();
            $toSave['title'] = trim(strip_tags($this->request->data['Event']['title']));
            $toSave['description'] = nl2br($this->request->data['Event']['description']);
            $toSave['user_id'] = $this->user['User']['id'];
            $toSave['game_id'] = $this->request->data['Event']['game_id'];
            $toSave['dungeon_id'] = $this->request->data['Event']['dungeon_id'];
            $toSave['character_level'] = $this->request->data['Event']['character_level'];
            $toSave['nb_groups'] = $this->request->data['Event']['nb_groups'];
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

                    $nbGroups = $toSave['nb_groups'];
                    for ($i = 1; $i <= $nbGroups; $i++) { 
                        $toSaveEventGroup = array();
                        $toSaveEventGroup['events_group_id'] = -1;
                        $toSaveEventGroup['title'] = __('Group').' '.$i;
                        $toSaveEventGroup['event_id'] = $eventId;
                        $this->EventsGroup->__add($toSaveEventGroup);
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

                    // If we have to create a template based on this event
                    if($this->user['User']['can']['create_templates'] || $this->user['User']['can']['full_permissions']) {
                        if($this->request->data['Event']['template']) {
                            $templateName = !empty($this->request->data['Event']['templateName'])?trim($this->request->data['Event']['templateName']):$toSave['title'];
                            $this->Event->copy($eventId, $templateName);
                        }
                    }

                    $this->Session->setFlash(__('The event has been created.'), 'flash_success');
                    $this->redirect('/events/index/m:'.(int)$dates[1].'/y:'.$dates[0]);
                }
            }

            $this->Session->setFlash(__('Something wrong happen, please fix the errors below'), 'flash_error');
        }

        $gamesList = $this->Game->find('list', array('order' => 'title ASC'));
        $this->set('gamesList', $gamesList);
        $rolesList = $this->RaidsRole->find('list', array('order' => 'title ASC'));
        $this->set('rolesList', $rolesList);
        $this->set('eventDate', $date);

        // Get templates
        $tplList = array();
        $params = array();
        $params['recursive'] = 1;
        $params['fields'] = array('id', 'title');
        $params['order'] = array('Game.title ASC', 'EventsTemplate.title ASC');
        $params['contain']['Game']['fields'] = array('title');
        if($templates = $this->EventsTemplate->find('all', $params)) {
            $currentGame = null;
            foreach($templates as $template) {
                $currentGame = $template['Game']['title'] == $currentGame?$currentGame:$template['Game']['title'];                
                $tplList[$currentGame][$template['EventsTemplate']['id']] = $template['EventsTemplate']['title'];
            }
        }
        $this->set('tplList', $tplList);        
    }

    public function edit($eventId) {
        $this->pageTitle = __('Edit event').' - '.$this->pageTitle;

        if(!$this->user['User']['can']['manage_events'] && !$this->user['User']['can']['full_permissions']) {
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
            $params['fields'] = array('time_invitation', 'nb_groups');
            $params['conditions']['Event.id'] = $eventId;            
            if(!$event = $this->Event->find('first', $params)) {
                $this->redirect('/events');
            }            
            $date = explode(' ', $event['Event']['time_invitation']);
            $dates = explode('-', $date[0]);
            Debugger::log($event['Event']);

            $toSave = array();
            $toSave['id'] = $this->request->data['Event']['id'];
            $toSave['title'] = strip_tags($this->request->data['Event']['title']);
            $toSave['description'] = nl2br($this->request->data['Event']['description']);
            $toSave['user_id'] = $this->user['User']['id'];
            $toSave['game_id'] = $this->request->data['Event']['game_id'];
            $toSave['dungeon_id'] = $this->request->data['Event']['dungeon_id'];
            $toSave['character_level'] = $this->request->data['Event']['character_level'];
            $toSave['nb_groups'] = $this->request->data['Event']['nb_groups'];
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

                    $currentGroupNb = $event['Event']['nb_groups'];
                    $requiredGroupNb = $this->request->data['Event']['nb_groups'];

                    if($currentGroupNb < $requiredGroupNb){
                        // more groups required
                        for($i = $currentGroupNb + 1; $i <= $requiredGroupNb; $i++){
                            $toSaveEventsGroup = array();
                            $toSaveEventsGroup['events_group_id'] = -1;
                            $toSaveEventsGroup['title'] = __('Group').' '.$i;
                            $toSaveEventsGroup['event_id'] = $eventId;
                            $this->EventsGroup->__add($toSaveEventsGroup);
                        }
                    }
                    else if ($currentGroupNb > $requiredGroupNb) {
                        // fewer groups required
                        $params = array();
                        $params['recursive'] = -1;
                        $params['fields'] = array('id');
                        $params['conditions']['EventsGroup.event_id'] = $eventId;
                        $params['order'] = array('EventsGroup.id');
                        $groups = $this->EventsGroup->find('all', $params);
                        Debugger::log($groups);

                        $idsToDelete = array();
                        for($i = $requiredGroupNb; $i < $currentGroupNb; $i++){
                            $idsToDelete[] = $groups[$i]['EventsGroup']['id'];
                        }

                        Debugger::log($idsToDelete);

                        $this->EventsGroup->__delete(array('EventsGroup.id' => $idsToDelete));
                        $this->EventsCharacter->multipleUnsubscribe(array('EventsCharacter.events_group_id' => $idsToDelete));
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

    public function close($eventId) {
        if(!$this->user['User']['can']['create_reports'] && !$this->user['User']['can']['full_permissions']) {
            $this->Session->setFlash(__('You don\'t have permission to access this page.'), 'flash_error');
            $this->redirect('/events/view/'.$eventId);
        }

        if(!$eventId) {
            $this->redirect('/events');
        }

        $this->pageTitle = __('Close event').' - '.$this->pageTitle;

        // Get event
        $params = array();
        $params['recursive'] = 1;
        $params['conditions']['Event.id'] = $eventId;
        $params['contain']['Report'] = array();
        $params['contain']['Dungeon'] = array();
        if(!$event = $this->Event->find('first', $params)) {
            $this->redirect('/events');
        }

        if(!empty($this->request->data['Report'])) {
            $screenshotError = false;
            $toSave = array();
            if(!empty($event['Report']['id'])) {
                $toSave['id'] = $event['Report']['id'];
            }
            $toSave['event_id'] = $eventId;
            $toSave['description'] = nl2br($this->request->data['Report']['description']);
            for($i = 1;$i <= 4;$i++) {
                $imageName = $this->screenshot($this->request->data['Report']['screenshot_'.$i], 'screenshot_'.$i, $eventId);
                $screenshotError = isset($imageName['error'])?true:$screenshotError;
                $toSave['screenshot_'.$i] = !empty($imageName['name'])?$imageName['name']:$event['Report']['screenshot_'.$i];
            }

            if(!$screenshotError) {
                if($this->Report->save($toSave)) {
                    $this->Session->setFlash(__('The event has been closed and the report created.'), 'flash_success');
                    $this->redirect('/events/report/'.$eventId);
                }else {
                    $this->Session->setFlash(__('Something wrong happen, please fix the errors below'), 'flash_error');
                }
            }
        }        

        $this->set('event', $event);
        $this->request->data = array_merge($event, $this->request->data); 

        $this->breadcrumb[] = array('title' => (!empty($event['Event']['title'])?$event['Event']['title']:$event['Dungeon']['title']), 'url' => '/events/view/'.$eventId);
        $this->breadcrumb[] = array('title' => __('Close & create a report'));
    }

    public function report($eventId) {
        if(!$eventId) {
            $this->redirect('/events');
        }

        $this->pageTitle = __('View event report').' - '.$this->pageTitle;

        // Get event
        $params = array();
        $params['recursive'] = 2;
        $params['conditions']['Event.id'] = $eventId;
        $params['contain']['Report']['fields'] = array('id');
        $params['contain']['Dungeon'] = array();
        if(!$event = $this->Event->find('first', $params)) {
            $this->Session->setFlash(__('MushRaider can\'t find this event oO'), 'flash_warning');
            $this->redirect('/events');
        }

        // Get report (separated from event because of the comments behavior)
        $params = array();
        $params['recursive'] = 1;
        $params['conditions']['Report.id'] = $event['Report']['id'];
        if(!$report = $this->Report->find('first', $params)) {
            $this->Session->setFlash(__('MushRaider can\'t find this report oO'), 'flash_warning');
            $this->redirect('/events/view/'.$event['Event']['id']);
        }

        $this->set('event', $event);
        $this->set('report', $report);

        $this->breadcrumb[] = array('title' => (!empty($event['Event']['title'])?$event['Event']['title']:$event['Dungeon']['title']), 'url' => '/events/view/'.$eventId);
        $this->breadcrumb[] = array('title' => __('Report'));
    }

    public function delete($eventId) {
        if(!$this->user['User']['can']['manage_events'] && !$this->user['User']['can']['full_permissions']) {
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
            $this->EventsRole->deleteAll(array('EventsRole.event_id' => $eventId));
            $this->EventsCharacter->deleteAll(array('EventsCharacter.event_id' => $eventId));
            $this->EventsGroup->deleteAll(array('EventsGroup.event_id' => $eventId));
            $this->Session->setFlash(__('The event has been deleted.'), 'flash_success');
        }else {
            $this->Session->setFlash(__('MushRaider can\'t delete this event.'), 'flash_error');
        }
        $this->redirect('/events');
    }

    private function screenshot($image, $name, $eventId) {
        $return = array();
        if(!$image['error']) {
            $imageName = 'report_'.$eventId.'_'.$name;
            $this->Image->resize($image['tmp_name'], 'files/reports', $imageName.'_t.png', 250, null);
            $this->Image->resize($image['tmp_name'], 'files/reports', $imageName.'_m.png', 600, null);
            $this->Image->resize($image['tmp_name'], 'files/reports', $imageName.'_o.png', null, null);
            $return['name'] = $imageName;
        }else {            
            switch($image['error']) {
                case 1:
                case 2:
                    $error = __('At least one of the screenshots is too big');
                    break;
                case 3:
                    $error = __('An error occur while uploading');
                    break;
                case 4:
                    $return['name'] = null;
                    break;
            }
            if(!empty($error)) {
                $this->Session->setFlash($error, 'flash_error');  
                $return['error'] = true;
            }
        }

        return $return;
    }
}