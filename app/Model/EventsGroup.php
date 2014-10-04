<?php
class EventsGroup extends AppModel {
	public $useTable = 'events_groups';
    var $actsAs = array('Containable');

    public $belongsTo = array(
        'Event' => array(
            'className' => 'Event',
            'foreignKey' => 'event_id'
        )
    );

    function __add($toSave = array()) {        
        if(empty($toSave)) {
            return false;
        }

        if($eventsGroup = $this->find('first', array('fields' => array('id'), 'conditions' => array('EventsGroup.id' => $toSave['events_group_id'], 'EventsGroup.event_id' => $toSave['event_id'])))) {
            // $toSave['id'] = $eventsGroup['EventsGroup']['id'];
        }else {
        	$this->create();        	
        }

        $this->save($toSave);
        return $this->getLastInsertId();
    }

    function __delete($toDelete = array()){
        if(empty($toDelete)) {
            return false;
        }

        $this->deleteAll($toDelete, false);
    }
}