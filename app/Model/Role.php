<?php
App::uses('AppModel', 'Model');
class Role extends AppModel {
    public $name = 'Role';
    public $displayField = 'title';

    var $actsAs = array('Containable');

    public $hasMany = array(
        'RolePermissionRole' => array(
            'className' => 'RolePermissionRole',
            'foreignKey' => 'role_id',
            'dependent'=> true
        )
    );

    public $validate = array(
        'title' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Alias cannot be empty.',
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
        $params['recursive'] = -1;
        $params['conditions']['id'] = $id;        
        $params['conditions']['alias'] = $alias;
        if($role = $this->find('first', $params)) {
            return $role['Role']['alias'];
        }

        return null;
    }

    public function getPermissions($roleId = null) {
        if(!$roleId) {
            return null;
        }

        // Check if the table exists (patch not installed yet)
        $db = ConnectionManager::getDataSource('default');
        $tables = $db->listSources();

        if(!in_array($this->tablePrefix.'role_permission_roles', $tables)) {
            $permissions['full_permissions'] = $roleId == 1;
            $permissions['limited_admin'] = false;
            $permissions['manage_events'] = false;
            $permissions['manage_own_events'] = false;
            $permissions['create_templates'] = false;
            $permissions['create_reports'] = false;

            return $permissions;
        }

        $permissions = array();
        $rolePermissionsAssigned = array();

        App::uses('RolePermissionRole', 'Model');
        $RolePermissionRoleModel = new RolePermissionRole();
        App::uses('RolePermission', 'Model');
        $RolePermissionModel = new RolePermission();

        $params = array();
        $params['recursive'] = 1;
        $params['fields'] = array('id', 'role_id');
        $params['contain']['RolePermission']['fields'] = array('alias');
        $params['conditions']['role_id'] = $roleId;
        if($rolePermissionRoles = $RolePermissionRoleModel->find('all', $params)) {
            foreach($rolePermissionRoles as $rolePermissionRole) {
                $permissions[$rolePermissionRole['RolePermission']['alias']] = $rolePermissionRole['RolePermissionRole']['role_id'] == $roleId;
                $rolePermissionsAssigned[] = $rolePermissionRole['RolePermission']['id'];
            }
        }

        $params = array();
        $params['recursive'] = -1;
        $params['conditions']['id !='] = $rolePermissionsAssigned;
        if($rolePermissions = $RolePermissionModel->find('all', $params)) {
            foreach($rolePermissions as $rolePermission) {
                if(!isset($permissions[$rolePermission['RolePermission']['alias']])) {
                    $permissions[$rolePermission['RolePermission']['alias']] = false;
                }
            }
        }

        return $permissions;
    }
}
