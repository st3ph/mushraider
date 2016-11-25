<?php
App::uses('AppModel', 'Model');
class Race extends AppModel {
    public $name = 'Race';
    public $displayField = 'title';

    var $actsAs = array('Containable');

    public $belongsTo = array(
        'Game' => array(
            'className' => 'Game',
            'foreignKey' => 'game_id'
        )
    );

    public $validate = array(
        'title' => array(
            'notEmpty' => array(
                'rule' => 'notBlank',
                'message' => 'Title cannot be empty.'
            )
        ),
        'game_id' => array(
            'rule' => array('uniqPerGame')            
        )
    );

    public function uniqPerGame($check) {        
        if(!empty($check['game_id']) && empty($this->data['Race']['id'])) {
            $params = array();
            $params['recursive'] = -1;
            $params['fields'] = array('id');
            $params['conditions']['title'] = $this->data['Race']['title'];
            $params['conditions']['game_id'] = $check['game_id'];
            if($this->find('first', $params)) {
                return __('This race already exist for this game.');
            }
        }
        return true;
    }
}
