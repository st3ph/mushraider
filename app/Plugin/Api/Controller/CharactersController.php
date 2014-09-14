<?php
class CharactersController extends ApiAppController {
    var $uses = array('Character');

    public function index() {
        $params = array();
        $params['recursive'] = 1;
        $params['order'] = array('Character.title' => 'asc');
        $params['fields'] = array('Character.id', 'Character.title', 'Character.level');
        $params['contain']['RaidsRole']['fields'] = array('title');
        $params['contain']['Game']['fields'] = array('title', 'logo');
        $params['contain']['Classe']['fields'] = array('title', 'color', 'icon');
        $params['contain']['Race']['fields'] = array('title');
        $params['contain']['User']['fields'] = array('username', 'status');
        if(!empty($this->request->params['named']['game'])) {
            $params['conditions']['Character.game_id'] = $this->request->params['named']['game'];
        }
        $characters = $this->Character->find('all', $params);        
        $this->set(array(
            'characters' => $characters,
            '_serialize' => array('characters')
        ));
    }

    public function view() {
        if(empty($this->request->params['named']['character'])) {
            $this->set(array(
                'character' => array(),
                '_serialize' => array('character')
            ));
            return;
        }

        $params = array();
        $params['recursive'] = 1;
        $params['fields'] = array('Character.id', 'Character.title', 'Character.level');
        $params['contain']['RaidsRole']['fields'] = array('title');
        $params['contain']['Game']['fields'] = array('title');
        $params['contain']['Classe']['fields'] = array('title', 'color');
        $params['contain']['Race']['fields'] = array('title');
        $params['contain']['User']['fields'] = array('username', 'status');
        $params['conditions']['Character.id'] = $this->request->params['named']['character'];
        $character = $this->Character->find('first', $params);
        $this->set(array(
            'character' => $character,
            '_serialize' => array('character')
        ));
    }
}