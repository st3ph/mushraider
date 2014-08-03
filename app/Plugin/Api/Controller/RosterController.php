<?php
class RosterController extends ApiAppController {    
    var $uses = array('Character');

    var $paginate = array(
        'User' => array(
            'limit' => 20,
            'recursive' => 2,
            'contain' => array(
                'Role' => array('fields' => array('title')), 
                'Character' => array(
                    'fields' => array('title', 'level', 'status'),
                    'Game' => array('fields' => array('title')), 
                    'Classe' => array('fields' => array('title', 'color')), 
                    'Race' => array('fields' => array('title'))
                )
            ),
            'order' => array('username' => 'asc'),
            'fields' => array('User.id', 'User.username')
        )
    );

    public function index() {
        $conditions = array();
        $conditions['User.status'] = 1;
        $users = $this->paginate('User', $conditions);        
        $this->set(array(
            'users' => $users,
            '_serialize' => array('users')
        ));
    }

    public function view($id) {
        $params = array();
        $params['recursive'] = 2;
        $params['fields'] = array('User.id', 'User.username');
        $params['contain']['Role']['fields'] = array('title');
        $params['contain']['Character']['fields'] = array('id', 'title', 'level', 'status');
        $params['contain']['Character']['Game']['fields'] = array('title');
        $params['contain']['Character']['Classe']['fields'] = array('title');
        $params['contain']['Character']['Race']['fields'] = array('title');
        $params['conditions']['User.id'] = $id;
        $user = $this->User->find('first', $params);
        $this->set(array(
            'user' => $user,
            '_serialize' => array('user')
        ));   
    }
}