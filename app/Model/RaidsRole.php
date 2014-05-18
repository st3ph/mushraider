<?php
App::uses('AppModel', 'Model');
class RaidsRole extends AppModel {
    public $name = 'RaidsRole';
    public $displayField = 'title';

    var $actsAs = array('Containable');

    public $hasMany = array(
        'EventsRole' => array(
            'className' => 'EventsRole',
            'foreignKey' => 'raids_role_id'
        )
    );

    public $validate = array(
        'title' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Title cannot be empty.'
            ),
            'unique' => array(
                'rule' => 'isUnique',
                'message' => 'This role already exists.'
            )
        )
    );

    function __add($title = null) {
        if(!$title) {
            return false;
        }

        if($raidsRole = $this->find('first', array('fields' => array('id'), 'conditions' => array('title' => $title)))) {
            return $raidsRole['RaidsRole']['id'];
        }

        $toSave = array();
        $toSave['title'] = $title;
        $this->create();
        $this->save($toSave);
        return $this->getLastInsertId();
    }
}
