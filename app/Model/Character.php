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
                'required' => 'create',
                'message' => 'Character name is mandatory.'
            )
        ),
        'game_id' => array(
            'isRequired' => array(
                'rule' => 'notEmpty',
                'required' => 'create',
                'message' => 'Please choose a game for this character.'
            )
        ),
        'classe_id' => array(
            'isRequired' => array(
                'rule' => 'notEmpty',
                'required' => 'create',
                'message' => 'Please choose a classe for this character.'
            )
        ),
        'race_id' => array(
            'isRequired' => array(
                'rule' => 'notEmpty',
                'required' => 'create',
                'message' => 'Please choose a race for this character.'
            )
        ),
        'level' => array(
            'isRequired' => array(
                'rule' => 'notEmpty',
                'required' => 'create',
                'message' => 'Please fill the level for this character.'
            )
        )
    );

    // Update schema for path beta 3
    // Usefull to avoir schema cache errors
    function schemaBeta3() {
        $this->_schema = array_merge($this->_schema, array('modified' => array('type' => 'datetime'), 'created' => array('type' => 'datetime')));
    }

    function beforeFind($params) {
        if(!empty($this->_schema['status'])) { // Because of patch 1.1 to avoid errors
            if(!isset($params['conditions']['status']) && !isset($params['conditions']['Character.status'])) {
                $params['conditions']['Character.status'] = 1;
            }
        }

        return $params;
    }
}