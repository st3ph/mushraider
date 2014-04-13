<?php
class EventsTemplatesRole extends AppModel {
    public $useTable = 'events_templates_roles';
    var $actsAs = array('Containable');

    public $belongsTo = array(
        'EventsTemplate' => array(
            'className' => 'Event',
            'foreignKey' => 'event_tpl_id'
        ),
        'RaidsRole' => array(
            'className' => 'RaidsRole',
            'foreignKey' => 'raids_role_id'
        )
    );

    function __add($toSave = array()) {        
        if(empty($toSave)) {
            return false;
        }

        if($eventsTplRole = $this->find('first', array('fields' => array('id'), 'conditions' => array('raids_role_id' => $toSave['raids_role_id'], 'event_tpl_id' => $toSave['event_tpl_id'])))) {
            $toSave['id'] = $eventsTplRole['EventsTemplatesRole']['id'];
        }else {
            $this->create();            
        }

        $this->save($toSave);
        return $this->getLastInsertId();
    }
}