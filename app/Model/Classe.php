<?php
App::uses('AppModel', 'Model');
class Classe extends AppModel {
    public $name = 'Classe';
    public $displayField = 'title';

    var $actsAs = array('Containable');

    public $belongsTo = array(
        'Game' => array(
            'className' => 'Game',
            'foreignKey' => 'game_id'
        )
    );

    public $hasMany = array(
        'Character' => array(
            'className' => 'Character',
            'foreignKey' => 'game_id',
            'dependent'=> true
        )
    );

    public $validate = array(
        'title' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Title cannot be empty.'
            )
        ),
        'color' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Color cannot be empty.'
            ),
            'length' => array(
                'rule' => array('between', 6, 20),
                'message' => 'Color size is wrong.'
            )
        ),
        'game_id' => array(
            'rule' => array('uniqPerGame')            
        )
    );

    public function uniqPerGame($check) {        
        if(!empty($check['game_id']) && empty($this->data['Classe']['id'])) {
            $params = array();
            $params['recursive'] = -1;
            $params['fields'] = array('id');
            $params['conditions']['title'] = $this->data['Classe']['title'];
            $params['conditions']['game_id'] = $check['game_id'];
            if($this->find('first', $params)) {
                return __('This class already exist for this game.');
            }
        }
        return true;
    }
}
