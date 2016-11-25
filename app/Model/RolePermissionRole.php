<?php
class RolePermissionRole extends AppModel {
    public $useTable = 'role_permission_roles';
    var $actsAs = array('Containable');

    public $belongsTo = array(
        'Role' => array(
            'className' => 'Role',
            'foreignKey' => 'role_id'
        ),
        'RolePermission' => array(
            'className' => 'RolePermission',
            'foreignKey' => 'role_permission_id'
        )
    );

    function __add($toSave = array(), $cond = array(), $d = null, $e = null) {        
        if(empty($toSave)) {
            return false;
        }

        if($rolePermissionRole = $this->find('first', array('fields' => array('id'), 'conditions' => array('role_id' => $toSave['role_id'], 'role_permission_id' => $toSave['role_permission_id'])))) {
            $toSave['id'] = $rolePermissionRole['RolePermissionRole']['id'];
        }else {
            $this->create();            
        }

        $this->save($toSave);
        return $this->getLastInsertId();
    }
}