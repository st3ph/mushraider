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

    public function __add($data, $eventUser, $date) {
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
                    $toSave['time_inscription'] = date('Y-m-d H:i:s', mktime(23, 59, 59, $time_inscription[1], $time_inscription[0], $time_inscription[2]));
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

}