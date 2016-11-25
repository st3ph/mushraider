<?php
App::uses('AppModel', 'Model');
class Game extends AppModel {
    public $name = 'Game';
    public $displayField = 'title';

    var $actsAs = array('Containable');

    public $hasMany = array(
        'Character' => array(
            'className' => 'Character',
            'foreignKey' => 'game_id',
            'dependent'=> true
        ),
        'Classe' => array(
            'className' => 'Classe',
            'foreignKey' => 'game_id',
            'dependent'=> true
        ),
        'Dungeon' => array(
            'className' => 'Dungeon',
            'foreignKey' => 'game_id',
            'dependent'=> true
        ),
        'Event' => array(
            'className' => 'Event',
            'foreignKey' => 'game_id',
            'dependent'=> true            
        ),
        'EventsTemplate' => array(
            'className' => 'EventsTemplate',
            'foreignKey' => 'game_id',
            'dependent'=> true            
        ),
        'Race' => array(
            'className' => 'Race',
            'foreignKey' => 'game_id',
            'dependent'=> true
        )
    );

    public $validate = array(
        'title' => array(
            'notEmpty' => array(
                'rule' => 'notBlank',
                'message' => 'Title cannot be empty.'
            )
        )
    );

}
