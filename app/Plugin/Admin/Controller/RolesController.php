<?php
class RolesController extends AdminAppController {
    public $components = array();
    public $uses = array('RolePermission', 'RolePermissionRole');

    var $adminOnly = true;

    public function index() {
        $params = array();
        $params['recursive'] = 1;
        $params['order'] = array('id', 'title');
        $params['contain']['RolePermissionRole']['RolePermission'] = array();
        $roles = $this->Role->find('all', $params);
        $this->set('roles', $roles);
    }

    public function add() {
        if(!empty($this->request->data['Role'])) {
            $toSave = array();
            $toSave['title'] = trim($this->request->data['Role']['title']);
            $toSave['alias'] = $this->Tools->slugMe($toSave['title']);
            $toSave['description'] = trim($this->request->data['Role']['description']);
            if($this->Role->save($toSave)) {
                $roleId = $this->Role->getLastInsertId();
                if(!empty($this->request->data['Role']['permission'])) {
                    foreach($this->request->data['Role']['permission'] as $permissionId => $permissionStatus) {
                        $conds = array('role_id' => $roleId, 'role_permission_id' => $permissionId);
                        if($permissionStatus) {
                            $this->RolePermissionRole->__add($conds);
                        }else {
                            $this->RolePermissionRole->deleteAll($conds);
                        }
                    }
                }
                $this->Session->setFlash(__('%s has been added to your groups list', $toSave['title']), 'flash_success');
                return $this->redirect('/admin/roles');
            }

            $this->Session->setFlash(__('Something goes wrong'), 'flash_error');
        }

        $params = array();
        $params['recursive'] = -1;
        $rolePermissions = $this->RolePermission->find('all', $params);
        $this->set('rolePermissions', $rolePermissions);
    }

    public function edit($id = null) {
        if(!$id) {
            return $this->redirect('/admin/roles');
        }

        if($id == 1) {
            $this->Session->setFlash(__('Sorry, you can\'t edit group "admin"'), 'flash_error');
            return $this->redirect('/admin/roles');
        }

        $params = array();
        $params['recursive'] = 1;
        $params['contain']['RolePermissionRole']['RolePermission'] = array();
        $params['conditions']['Role.id'] = $id;
        if(!$role = $this->Role->find('first', $params)) {
            $this->Session->setFlash(__('MushRaider is unable to find this group oO'), 'flash_error');
            return $this->redirect('/admin/roles');
        }

        if(!empty($this->request->data['Role']) && $this->request->data['Role']['id'] == $id) {
            $toSave = array();
            $toSave['id'] = $this->request->data['Role']['id'];
            $toSave['title'] = trim($this->request->data['Role']['title']);
            $toSave['description'] = trim($this->request->data['Role']['description']);
            if($this->Role->save($toSave)) {
                $roleId = $this->request->data['Role']['id'];
                if(!empty($this->request->data['Role']['permission'])) {
                    foreach($this->request->data['Role']['permission'] as $permissionId => $permissionStatus) {
                        $conds = array('role_id' => $roleId, 'role_permission_id' => $permissionId);
                        if($permissionStatus) {
                            $this->RolePermissionRole->__add($conds);
                        }else {
                            $this->RolePermissionRole->deleteAll($conds);
                        }
                    }
                }
                $this->Session->setFlash(__('Group %s has been updated', $role['Role']['title']), 'flash_success');
                return $this->redirect('/admin/roles');
            }

            $this->Session->setFlash(__('Something goes wrong'), 'flash_error');
            $role = array_merge($role, $this->request->data);
        }

        $this->request->data = $role;

        $params = array();
        $params['recursive'] = -1;
        $rolePermissions = $this->RolePermission->find('all', $params);
        $this->set('rolePermissions', $rolePermissions);
    }
}