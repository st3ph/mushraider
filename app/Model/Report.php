<?php
App::uses('AppModel', 'Model');
class Report extends AppModel {
    var $actsAs = array('Containable', 'Sociable.Commentable');

    public $name = 'Report';

    public $belongsTo = array(
        'Event' => array(
            'className' => 'Event',
            'foreignKey' => 'event_id'
        )
    );
}