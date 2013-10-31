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
}