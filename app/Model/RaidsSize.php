<?php
App::uses('AppModel', 'Model');
class RaidsSize extends AppModel {
    public $name = 'RaidsSize';
    public $displayField = 'size';

    var $actsAs = array('Containable');

    public $hasMany = array(
        'Dungeon' => array(
            'className' => 'Dungeon',
            'foreignKey' => 'raidssize_id',
            'dependent'=> true
        )
    );

    public $validate = array(
        'size' => array(
            'notEmpty' => array(
                'rule' => 'notBlank',
                'message' => 'Size cannot be empty.'
            )
        )
    );

    function __add($size = null, $cond = array(), $d = null, $e = null) {
        if(!$size) {
            return false;
        }

        if($raidSize = $this->find('first', array('fields' => array('id'), 'conditions' => array('size' => $size)))) {
            return $raidSize['RaidsSize']['id'];
        }

        $toSave = array();
        $toSave['size'] = $size;
        $this->create();
        $this->save($toSave);
        return $this->getLastInsertId();
    }
}
