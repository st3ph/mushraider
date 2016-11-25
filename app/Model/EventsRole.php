<?php
class EventsRole extends AppModel {
	public $useTable = 'events_roles';
    var $actsAs = array('Containable');

    public $belongsTo = array(
        'Event' => array(
            'className' => 'Event',
            'foreignKey' => 'event_id'
        ),
        'RaidsRole' => array(
            'className' => 'RaidsRole',
            'foreignKey' => 'raids_role_id'
        )
    );

    function __add($toSave = array(), $cond = array(), $d = null, $e = null) {        
        if(empty($toSave)) {
            return false;
        }

        if($eventsRole = $this->find('first', array('fields' => array('id'), 'conditions' => array('raids_role_id' => $toSave['raids_role_id'], 'event_id' => $toSave['event_id'])))) {
            $toSave['id'] = $eventsRole['EventsRole']['id'];
        }else {
        	$this->create();        	
        }

        if($this->save($toSave)) {
            return !empty($toSave['id'])?$toSave['id']:$this->getLastInsertId();
        }

        return false;
    }
}