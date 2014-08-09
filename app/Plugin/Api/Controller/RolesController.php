<?php
class RolesController extends ApiAppController {
    var $uses = array('Role');

    public function index() {
        $params = array();
        $params['recursive'] = -1;
        $params['order'] = 'Role.title';
        $params['fields'] = array('title', 'alias', 'description');
        $roles = $this->Role->find('all', $params);
        $this->set(array(
            'roles' => $roles,
            '_serialize' => array('roles')
        )); 
    }
}