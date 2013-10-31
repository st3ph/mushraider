<?php
App::uses('AppModel', 'Model');
class Dungeon extends AppModel {
    var $actsAs = array('Containable');

    public $name = 'Dungeon';
    public $displayField = 'title';

    public $belongsTo = array(
        'RaidsSize' => array(
            'className' => 'RaidsSize',
            'foreignKey' => 'raidssize_id'
        ),
        'Game' => array(
            'className' => 'Game',
            'foreignKey' => 'game_id'
        )
    );

    public $validate = array(
        'title' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'This field cannot be left blank.',
                'last' => true,
            )
        ),
        'raidssize_id' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'This field cannot be left blank.',
                'last' => true,
            )
        ),
        'game_id' => array(
            'rule' => array('uniqPerGame')            
        )
    );

    public function uniqPerGame($check) {        
        if(!empty($check['game_id']) && empty($this->data['Dungeon']['id'])) {
            $params = array();
            $params['recursive'] = -1;
            $params['fields'] = array('id');
            $params['conditions']['title'] = $this->data['Dungeon']['title'];
            $params['conditions']['game_id'] = $check['game_id'];
            if($this->find('first', $params)) {
                return __('This dungeon already exist for this game.');
            }
        }
        return true;
    }
}