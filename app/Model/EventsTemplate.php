<?php
App::uses('AppModel', 'Model');
class EventsTemplate extends AppModel {
    var $actsAs = array('Containable');

    public $name = 'EventsTemplate';
    public $displayField = 'title';

    public $order = array('EventsTemplate.game_id ASC', 'EventsTemplate.title ASC');

    public $belongsTo = array(
        'Dungeon' => array(
            'className' => 'Dungeon',
            'foreignKey' => 'dungeon_id'
        ),
        'Game' => array(
            'className' => 'Game',
            'foreignKey' => 'game_id'
        )
    );
    public $hasMany = array(
        'EventsTemplatesRole' => array(
            'className' => 'EventsTemplatesRole',
            'foreignKey' => 'event_tpl_id'
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
        'character_level' => array(
            'isRequired' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'Please choose a minimum character level for this event.'
            )
        )
    );
}