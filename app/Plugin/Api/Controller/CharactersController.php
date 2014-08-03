<?php
class CharactersController extends ApiAppController {
    var $uses = array('Character');

    var $paginate = array(
        'Character' => array(
            'limit' => 1,
            'recursive' => 1,
            'contain' => array(
                'RaidsRole' => array('fields' => array('title')), 
                'Game' => array('fields' => array('title')), 
                'Classe' => array('fields' => array('title', 'color')), 
                'Race' => array('fields' => array('title')),
                'User' => array('fields' => array('username', 'status'))
            ),
            'order' => array('title' => 'asc'),
            'fields' => array('Character.title', 'Character.level')
        )
    );

    public function index() {
        $conditions = array();
        if(!empty($this->request->params['named']['game'])) {
            $conditions['Character.game_id'] = $this->request->params['named']['game'];
        }
        $characters = $this->paginate('Character', $conditions);        
        $this->set(array(
            'characters' => $characters,
            '_serialize' => array('characters')
        ));
    }

    public function view($id) {
        $params = array();
        $params['recursive'] = 1;
        $params['fields'] = array('Character.id', 'Character.title', 'Character.level');
        $params['contain']['RaidsRole']['fields'] = array('title');
        $params['contain']['Game']['fields'] = array('title');
        $params['contain']['Classe']['fields'] = array('title', 'color');
        $params['contain']['Race']['fields'] = array('title');
        $params['contain']['User']['fields'] = array('username', 'status');
        $params['conditions']['User.id'] = $id;
        $character = $this->Character->find('first', $params);
        $this->set(array(
            'character' => $character,
            '_serialize' => array('character')
        ));
    }
}