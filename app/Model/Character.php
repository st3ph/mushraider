<?php
App::uses('AppModel', 'Model');
class Character extends AppModel {
    var $actsAs = array('Containable');

    public $name = 'Character';
    public $displayField = 'title';

    public $order = 'Character.title ASC, Character.level ASC';

    public $belongsTo = array(
        'Classe' => array(
            'className' => 'Classe',
            'foreignKey' => 'classe_id'
        ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        ),
        'Race' => array(
            'className' => 'Race',
            'foreignKey' => 'race_id'
        ),
        'Game' => array(
            'className' => 'Game',
            'foreignKey' => 'game_id'
        ),
        'RaidsRole' => array(
            'className' => 'RaidsRole',
            'foreignKey' => 'default_role_id'
        ),
    );

    public $hasMany = array(
        'EventsCharacter' => array(
            'className' => 'EventsCharacter',
            'foreignKey' => 'character_id'
        )
    );

    public $validate = array(
        'title' => array(
            'isRequired' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'Character name is mandatory.'
            )
        ),
        'game_id' => array(
            'isRequired' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'Please choose a game for this character.'
            )
        ),
        'classe_id' => array(
            'isRequired' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'Please choose a classe for this character.'
            )
        ),
        'race_id' => array(
            'isRequired' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'Please choose a race for this character.'
            )
        ),
        'level' => array(
            'isRequired' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'Please fill the level for this character.'
            )
        )
    );
}