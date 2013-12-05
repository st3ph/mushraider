<?php
class UsersController extends AdminAppController {
    public $components = array();
    public $uses = array('EventsCharacter', 'Character');

    var $paginate = array(
        'User' => array(
            'limit' => 20,
            'recursive' => 1,
            'contain' => array('Role', 'Character'),
            'order' => array('username' => 'asc')
        )
    );

    function beforeFilter() {
        parent::beforeFilter();
    }

    public function index() {
        $conditions = array();
        $conditions['User.status'] = 1;
        $users = $this->paginate('User', $conditions);        
        $this->set('users', $users);
    }

    public function waiting() {
        $conditions = array();
        $conditions['User.status'] = 0;
        $users = $this->paginate('User', $conditions);        
        $this->set('users', $users);

        $this->render('index');
    }

    public function edit($id = null) {
        if(!$id) {
            $this->redirect('/admin/users');
        }

        $params = array();
        $params['recursive'] = 1;
        $params['contain']['Role'] = array();     
        $params['conditions']['User.id'] = $id;
        $params['conditions']['User.status'] = array(0, 1);
        if(!$user = $this->User->find('first', $params)) {
            $this->Session->setFlash(__('MushRaider is unable to find this user oO'), 'flash_error');
            $this->redirect('/admin/users');
        }

        if(!empty($this->request->data['User']) && $this->request->data['User']['id'] == $id) {
            $toSave = array();
            $toSave['id'] = $this->request->data['User']['id'];            
            $toSave['status'] = $this->request->data['User']['status'];
            if($this->user['User']['isAdmin']) {
                $toSave['role_id'] = $this->request->data['User']['role_id'];            
            }

            if($this->User->save($toSave)) {
                $this->Session->setFlash(__('User %s has been updated', $user['User']['username']), 'flash_success');
                $this->redirect('/admin/users');
            }

            $this->Session->setFlash(__('Something goes wrong'), 'flash_error');

            $user['User'] = array_merge($user['User'], $this->request->data['User']);
        }

        $roles = $this->Role->find('all', array('order' => 'id ASC'));        
        $this->set('roles', $roles);
        $rolesList = array();
        if(!empty($roles)) {
            foreach($roles as $role) {
                $rolesList[$role['Role']['id']] = $role['Role']['title'];
            }
        }
        $this->set('rolesList', $rolesList);

        $this->request->data['User'] = $user['User'];
    }

    public function delete($id = null) {
        if($id) {
            $params = array();
            $params['fields'] = array('id');
            $params['recursive'] = -1;
            $params['conditions']['id'] = $id;
            $params['conditions']['status'] = 0;
            if(!$user = $this->User->find('first', $params)) {
                $this->Session->setFlash(__('This user is still active, you have to disable him before deleting.'), 'flash_warning');
            }elseif($this->User->delete($id)) {
                $deleteCond = array('EventsCharacter.user_id' => $id);
                $this->EventsCharacter->deleteAll($deleteCond);
                $deleteCond = array('Character.user_id' => $id);
                $this->Character->deleteAll($deleteCond);
                $this->Session->setFlash(__('The user has been deleted'), 'flash_success');
            }else {
                $this->Session->setFlash(__('Something goes wrong'), 'flash_error');
            }
        }

        $this->redirect('/admin/users/waiting');
    }
}