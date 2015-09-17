<?php
App::uses('AppModel', 'Model');
class Event extends AppModel {
    var $actsAs = array('Containable', 'Sociable.Commentable');

    public $name = 'Event';
    public $displayField = 'title';

    public $order = 'Event.time_start ASC';

    public $belongsTo = array(
        'Dungeon' => array(
            'className' => 'Dungeon',
            'foreignKey' => 'dungeon_id'
        ),
        'Game' => array(
            'className' => 'Game',
            'foreignKey' => 'game_id'
        ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        )
    );

    public $hasMany = array(
        'EventsRole' => array(
            'className' => 'EventsRole',
            'foreignKey' => 'event_id',
            'dependent'=> true
        ),
        'EventsCharacter' => array(
            'className' => 'EventsCharacter',
            'foreignKey' => 'event_id',
            'dependent'=> true
        )
    );

    public $hasOne = array(
        'Report' => array(
            'className' => 'Report',
            'foreignKey' => 'event_id',
            'dependent' => true
        )
    );

    public $validate = array(
        'game_id' => array(
            'isRequired' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'Please choose a game for this event.'
            )
        ),
        'dungeon_id' => array(
            'isRequired' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'Please choose a dungeon for this event.'
            )
        ),
        'user_id' => array(
            'isRequired' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'Please choose a owner for this event.'
            )
        ),
        'time_invitation' => array(
            'isRequired' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'Please choose an invitation time for this event.'
            )
        ),
        'time_start' => array(
            'isRequired' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'Please choose a start time for this event.'
            )
        ),
        'character_level' => array(
            'isRequired' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'Please choose a minimum character level for this event.'
            )
        )
    );

    public function __add($data, $eventUser, $date, $dateInscriptionInterval = null) {
        if(!empty($data)) {
            $dates = explode('-', $date);

            $toSave = array();
            $toSave['title'] = trim(strip_tags($data['Event']['title']));
            $toSave['description'] = nl2br($data['Event']['description']);
            $toSave['user_id'] = $eventUser['User']['id'];
            $toSave['game_id'] = $data['Event']['game_id'];
            $toSave['dungeon_id'] = $data['Event']['dungeon_id'];
            $toSave['character_level'] = $data['Event']['character_level'];
            $toSave['time_invitation'] = date('Y-m-d H:i:s', mktime($data['Event']['time_invitation']['hour'], $data['Event']['time_invitation']['min'], 0, $dates[1], $dates[2], $dates[0]));
            $toSave['time_start'] = date('Y-m-d H:i:s', mktime($data['Event']['time_start']['hour'], $data['Event']['time_start']['min'], 0, $dates[1], $dates[2], $dates[0]));
            if(!empty($data['Event']['time_inscription'])) {
                if(preg_match('/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/', $data['Event']['time_inscription'], $matches)) {
                    $time_inscription = explode('/', $data['Event']['time_inscription']);
                    $dateTime = new DateTime($time_inscription[2].'-'.$time_inscription[1].'-'.$time_inscription[0].' 23:59:59');
                    if($dateInscriptionInterval) {  
                        $dateTime->add($dateInscriptionInterval);
                    }

                    $toSave['time_inscription'] = $dateTime->format('Y-m-d H:i:s');
                    if($toSave['time_inscription'] > $toSave['time_start']) {
                        $toSave['time_inscription'] = $toSave['time_start'];
                    }
                }
            }else {
                $toSave['time_inscription'] = $toSave['time_start'];
            }
            $toSave['open'] = $data['Event']['open']?1:0;            

            if(!empty($data['Event']['roles'])) {
                $this->create();
                if($this->save($toSave)) {
                    App::uses('EventsRole', 'Model');
                    $EventsRole = new EventsRole();

                    $eventId = $this->getLastInsertId();

                    $paramsEvent = array();
                    $paramsEvent['recursive'] = 1;
                    $paramsEvent['contain']['Game']['fields'] = array('Game.title');
                    $paramsEvent['contain']['Dungeon']['fields'] = array('Dungeon.title');
                    $paramsEvent['conditions']['Event.id'] = $eventId;
                    $event = $this->find('first', $paramsEvent);

                    foreach($data['Event']['roles'] as $roleId => $roleNumber) {
                        $toSaveEventsRole = array();
                        $toSaveEventsRole['event_id'] = $eventId;
                        $toSaveEventsRole['raids_role_id'] = $roleId;
                        $toSaveEventsRole['count'] = $roleNumber?$roleNumber:'0';
                        $EventsRole->__add($toSaveEventsRole);
                    }

                    return $event;
                }
            }
        }

        return false;
    }

    public function copy($eventId, $templateName) {
        if(isset($eventId) && !empty($templateName)) {
            // Get event infos
            $params = array();
            $params['recursive'] = 1;
            $params['contain']['EventsRole'] = array();
            $params['conditions']['id'] = $eventId;
            if($event = $this->find('first', $params)) {
                $toSave = array();
                $toSave['EventsTemplate']['title'] = $templateName;
                $toSave['EventsTemplate']['event_title'] = $event['Event']['title'];
                $toSave['EventsTemplate']['event_description'] = $event['Event']['description'];
                $toSave['EventsTemplate']['game_id'] = $event['Event']['game_id'];
                $toSave['EventsTemplate']['dungeon_id'] = $event['Event']['dungeon_id'];
                $toSave['EventsTemplate']['time_invitation'] = $event['Event']['time_invitation'];
                $toSave['EventsTemplate']['time_start'] = $event['Event']['time_start'];
                $toSave['EventsTemplate']['character_level'] = $event['Event']['character_level'];
                $toSave['EventsTemplate']['open'] = $event['Event']['open'];
                if(!empty($event['EventsRole'])) {
                    foreach($event['EventsRole'] as $key => $eventRole) {
                        $toSave['EventsTemplatesRole'][$key]['raids_role_id'] = $eventRole['raids_role_id'];
                        $toSave['EventsTemplatesRole'][$key]['count'] = $eventRole['count'];
                    }
                }

                App::uses('EventsTemplate', 'Model');
                $EventsTemplate = new EventsTemplate();
                if($EventsTemplate->saveAll($toSave)) {
                    return true;
                }
            }
        }

        return false;
    }

    public function afterSave($created, $options) {
        // If users are absent for this event, add them as "absent" automatically
        App::uses('Availability', 'Model');
        $AvailabilityModel = new Availability(); 

        $params = array();       
        $params['recursive'] = 1;
        $params['fields'] = array('Availability.id', 'Availability.user_id', 'Availability.comment');
        $params['contain']['User'] = array();
        $params['conditions']['start <='] = $this->data['Event']['time_start'];
        $params['conditions']['end >='] = $this->data['Event']['time_start'];
        if($availabilities = $AvailabilityModel->find('all', $params)) {
            App::uses('Character', 'Model');
            $CharacterModel = new Character(); 
            foreach($availabilities as $availability) {
                $params = array();       
                $params['recursive'] = -1;
                $params['fields'] = array('Character.id', 'Character.game_id', 'Character.level', 'Character.default_role_id');
                $params['group'] = 'game_id';
                $params['conditions']['user_id'] = $availability['Availability']['user_id'];
                $params['conditions']['game_id'] = $this->data['Event']['game_id'];
                $params['conditions']['main'] = 1;
                if($characters = $CharacterModel->find('all', $params)) {
                    App::uses('EventsCharacter', 'Model');
                    $EventsCharacterModel = new EventsCharacter();
                    foreach($characters as $character) {
                        // If already registered to this event, update it
                        $paramsEventsCharacter = array();
                        $paramsEventsCharacter['recursive'] = -1;
                        $paramsEventsCharacter['fields'] = array('id');
                        $paramsEventsCharacter['conditions']['event_id'] = $this->data['Event']['id'];
                        $paramsEventsCharacter['conditions']['user_id'] = $availability['Availability']['user_id'];
                        if($eventCharacter = $EventsCharacterModel->find('first', $paramsEventsCharacter)) {
                            $eventCharacter['EventsCharacter']['status'] = 0;
                            $EventsCharacterModel->save($eventCharacter['EventsCharacter']);
                        }else {
                            $toSave = array();
                            $toSave['event_id'] = $this->data['Event']['id'];
                            $toSave['user_id'] = $availability['Availability']['user_id'];
                            $toSave['character_id'] = $character['Character']['id'];
                            $toSave['raids_role_id'] = $character['Character']['default_role_id'];
                            $toSave['comment'] = $availability['Availability']['comment'];
                            $toSave['status'] = 0;
                            $EventsCharacterModel->__add($toSave);
                        }
                    }
                }
            }
        }
    }

    public function onComment($comment, $EmailingComponent) {
        parent::onComment($comment, $EmailingComponent);

        App::uses('Setting', 'Model');
        $SettingModel = new Setting();
        $notifications = json_decode($SettingModel->getOption('notifications'));
        if(!$notifications->enabled || !$notifications->comments || empty($notifications->contact)) {
            return;
        }

        $EmailingComponent->commentEvent($notifications->contact, $comment);
    }
}