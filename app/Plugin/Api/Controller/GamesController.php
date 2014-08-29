<?php
class GamesController extends ApiAppController {    
    var $uses = array('Game', 'Dungeon', 'Classe', 'Race');

    public function index() {
        $params = array();
        $params['recursive'] = -1;
        $params['order'] = 'Game.title';
        $params['fields'] = array('id', 'title', 'slug', 'logo');
        $games = $this->Game->find('all', $params);
        $this->set(array(
            'games' => $games,
            '_serialize' => array('games')
        )); 
    }

    public function view() {
        if(empty($this->request->params['named']['game'])) {
            $this->set(array(
                'game' => array(),
                '_serialize' => array('game')
            ));
            return;
        }

        $params = array();
        $params['recursive'] = 2;
        $params['fields'] = array('Game.id', 'Game.title', 'Game.slug', 'Game.logo');
        $params['contain']['Dungeon']['fields'] = array('title', 'icon');
        $params['contain']['Classe']['fields'] = array('title', 'icon');
        $params['contain']['Race']['fields'] = array('title');
        $params['conditions']['Game.id'] = $this->request->params['named']['game'];
        $game = $this->Game->find('first', $params);
        $this->set(array(
            'game' => $game,
            '_serialize' => array('game')
        ));   
    }
}