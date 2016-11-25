<?php
class EventsCharacter extends AppModel {
	public $useTable = 'events_characters';
    var $actsAs = array('Containable');

    public $belongsTo = array(
        'Event' => array(
            'className' => 'Event',
            'foreignKey' => 'event_id'
        ),
        'Character' => array(
            'className' => 'Character',
            'foreignKey' => 'character_id'
        ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        )
    );

    public $validate = array(
        'event_id' => array(
            'isRequired' => array(
                'rule' => 'notBlank',
                'message' => 'Please choose an event.'
            )
        ),
        'user_id' => array(
            'isRequired' => array(
                'rule' => 'notBlank',
                'message' => 'Please choose a user.'
            )
        ),
        'character_id' => array(
            'isRequired' => array(
                'rule' => 'notBlank',
                'message' => 'Please choose a character for this event.'
            )
        ),
        'raids_role_id' => array(
            'isRequired' => array(
                'rule' => 'notBlank',
                'message' => 'Please choose a role for this event.'
            )
        )
    );

    function __add($toSave = array(), $cond = array(), $d = null, $e = null) {        
        if(empty($toSave)) {
            return false;
        }

        $params = array();
        $params['fields'] = array('id');
        $params['recursive'] = -1;
        $params['conditions']['user_id'] = $toSave['user_id'];
        $params['conditions']['event_id'] = $toSave['event_id'];
        if($eventsCharacter = $this->find('first', $params)) {
            $toSave['id'] = $eventsCharacter['EventsCharacter']['id'];
        }else {
        	$this->create();
        }

        if($this->save($toSave)) {
            return !empty($toSave['id'])?$toSave['id']:$this->getLastInsertId();
        }

        return false;
    }
}