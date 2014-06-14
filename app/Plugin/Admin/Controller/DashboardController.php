<?php
class DashboardController extends AdminAppController {
    public $uses = array('Character', 'Dungeon', 'Event', 'Game');

    function beforeFilter() {
        parent::beforeFilter();
    }

    public function index() {
        $params = array();
        $params['fields'] = array('id');
        $params['recursive'] = -1;

        $totalUsers = $this->User->find('count', $params);
        $this->set('totalUsers', $totalUsers);

        $totalCharacters = $this->Character->find('count', $params);
        $this->set('totalCharacters', $totalCharacters);

        $totalDungeons = $this->Dungeon->find('count', $params);
        $this->set('totalDungeons', $totalDungeons);

        $totalGames = $this->Game->find('count', $params);
        $this->set('totalGames', $totalGames);

        $totalEvents = $this->Event->find('count', $params);
        $this->set('totalEvents', $totalEvents);

        // Waiting users
        $params['conditions']['status'] = 0;
        $waitingUsers = $this->User->find('count', $params);
        $this->set('waitingUsers', $waitingUsers);
    }
}