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
            'foreignKey' => 'event_id'
        ),
        'EventsCharacter' => array(
            'className' => 'EventsCharacter',
            'foreignKey' => 'event_id'
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