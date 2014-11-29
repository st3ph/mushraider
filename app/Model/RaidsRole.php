<?php
App::uses('AppModel', 'Model');
class RaidsRole extends AppModel {
    public $name = 'RaidsRole';
    public $displayField = 'title';

    var $actsAs = array('Containable');

    public $hasMany = array(
        'EventsRole' => array(
            'className' => 'EventsRole',
            'foreignKey' => 'raids_role_id',
            'dependent'=> true
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

    function __add($title = null, $order = null) {
        if(!$title) {
            return false;
        }

        if($raidsRole = $this->find('first', array('recursive' => -1, 'fields' => array('id'), 'conditions' => array('title' => $title)))) {
            return $raidsRole['RaidsRole']['id'];
        }

        if($order === null) {
            $order = 0;
            if($maxOrder = $this->find('first', array('recursive' => -1, 'fields' => array('order'), 'order' => 'order DESC'))) {
                $order = $maxOrder['RaidsRole']['order'] + 1;
            }
        }

        $toSave = array();
        $toSave['title'] = $title;
        $toSave['order'] = $order;
        $this->create();
        $this->save($toSave);
        return $this->getLastInsertId();
    }
}
