<?php
App::uses('AppModel', 'Model');
class Game extends AppModel {
    public $name = 'Game';
    public $displayField = 'title';

    var $actsAs = array('Containable');

    public $hasMany = array(
        'Character' => array(
            'className' => 'Character',
            'foreignKey' => 'game_id'
        ),
        'Classe' => array(
            'className' => 'Classe',
            'foreignKey' => 'game_id'
        ),
        'Dungeon' => array(
            'className' => 'Dungeon',
            'foreignKey' => 'game_id'
        ),
        'Event' => array(
            'className' => 'Event',
            'foreignKey' => 'game_id'
        ),
        'Race' => array(
            'className' => 'Race',
            'foreignKey' => 'game_id'
        )
    );

    public $validate = array(
        'title' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Title cannot be empty.'
            )
        )
    );

}
