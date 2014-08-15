<?php
App::uses('AppModel', 'Model');
class RolePermission extends AppModel {
    public $name = 'RolePermission';
    public $displayField = 'title';

    var $actsAs = array('Containable');

    public $hasMany = array(
        'RolePermissionRole' => array(
            'className' => 'RolePermissionRole',
            'foreignKey' => 'role_permission_id'
        )
    );

    public $validate = array(
        'title' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Alias cannot be empty.',
                'last' => true,
            ),
            'validName' => array(
                'rule' => '/^([0-9a-zA-Z_\- ]{2,50})$/',
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
                'rule' => '/^([0-9a-zA-Z_\-]{2,50})$/',
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
        $params['recursive'] = -1;
        $params['conditions']['alias'] = $alias;        
        if($rolePermission = $this->find('first', $params)) {
            return $rolePermission['RolePermission']['id'];
        }

        return null;
    }
}

