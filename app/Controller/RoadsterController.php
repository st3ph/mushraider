<?php
class RoadsterController extends AppController {    
    var $helpers = array();
    var $uses = array('Game', 'Character', 'Classe', 'Race', 'RaidsRole', 'EventsCharacter', 'Attenuement');

    public function beforeFilter() {
        parent::beforeFilter();

        $this->breadcrumb[] = array('title' => __('My MushRaider account'), 'url' => '/account');

        $this->bridge = json_decode($this->Setting->getOption('bridge'));
        $this->set('bridge', $this->bridge);
    }

    public function index() {
      $this->pageTitle = __('MushRaider Rodster').' - '.$this->pageTitle;

        $this->breadcrumb[] = array('title' => __('Roadster'), 'url' => '');

        // Get all the characters
        $params = array();
        $params['recursive'] = 1;
        $params['order'] = 'Character.status DESC, Attenuement.rank ASC, Character.title ASC';
        $params['contain']['Classe'] = array();
        $params['contain']['User'] = array();
        $params['contain']['Race'] = array();
        $params['contain']['Attenuement'] = array();
        $params['contain']['RaidsRole'] = array();
        $params['conditions']['Character.user_id'] = $this->user['User']['id'];        
        $params['conditions']['Character.status'] = array(0, 1);
        $characters = $this->Character->find('all', $params);        
        $this->set('characters', $characters);
    }
}