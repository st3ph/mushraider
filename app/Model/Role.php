<?php
App::uses('AppModel', 'Model');
class Role extends AppModel {
    public $name = 'Role';
    public $displayField = 'title';

    var $actsAs = array('Containable');

    public $validate = array(
        'title' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Alias cannot be empty.',
                'last' => true,
            ),
            'validName' => array(
                'rule' => 'validName',
                'message' => 'This field must be alphanumeric',
                'last' => true,
            ),
        ),
        'alias' => array(
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'This alias has already been taken.',
                'last' => true,
            ),
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Alias cannot be empty.',
                'last' => true,
            ),
            'validAlias' => array(
                'rule' => 'validAlias',
                'message' => 'This field must be alphanumeric',
                'last' => true,
            ),
        )
    );

    public function getIdByAlias($alias = null) {
        if(!$alias) {
            return null;
        }
        $params = array();
        $params['fields'] = array('id');
        $params['conditions']['alias'] = $alias;        
        if($role = $this->find('first', $params)) {
            return $role['Role']['id'];
        }

        return null;
    }

    public function is($id = null, $alias = null) {
        if(!$id || !$alias) {
            return null;
        }
        $params = array();
        $params['fields'] = array('alias');
        $params['conditions']['id'] = $id;        
        $params['conditions']['alias'] = $alias;
        if($role = $this->find('first', $params)) {
            return $role['Role']['alias'];
        }

        return null;
    }
}
