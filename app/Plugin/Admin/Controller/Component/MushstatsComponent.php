<?php
App::uses('Setting', 'Model');
class MushstatsComponent extends Component {
    var $controller;
	var $SettingModel;

	public function initialize(Controller $controller) {
		$this->controller = &$controller;

        if($this->controller->name != 'Patcher') {
            $this->SettingModel = new Setting();

            $Mushstats = $this->SettingModel->getOption('Mushstats');
            $lastUpdate = new Datetime();
            $lastUpdate->setTimestamp($Mushstats);
            if($lastUpdate->diff(new Datetime())->d > 6) {
                $this->sendStats();
                $this->SettingModel->setOption('Mushstats', time());
            }
        }
	}

    private function sendStats() {
        $stats = array();

        // Website
        $stats['Website']['url'] = Router::url('/', true);
        $stats['Website']['version'] = Configure::read('mushraider.version');
        $stats['Website']['php'] = phpversion();
        $stats['Website']['lang'] = Configure::read('Settings.language');

        // Events
        App::uses('Event', 'Model');
        $EventModel = new Event();

        $params = array();
        $params['recursive'] = -1;
        $params['order'] = array('time_start ASC');
        if($firstEvent = $EventModel->find('first', $params)) {
            $stats['Event']['first'] = $firstEvent['Event']['time_start'];
        }else {
            $stats['Event']['first'] = null;
        }

        $params['conditions']['time_start <='] = date('Y-m-d');
        $params['order'] = array('time_start DESC');
        if($lastEvent = $EventModel->find('first', $params)) {
            $stats['Event']['last'] = $lastEvent['Event']['time_start'];
        }else {
            $stats['Event']['last'] = null;
        }

        $params = array();
        $params['recursive'] = -1;
        $params['conditions']['time_start <='] = date('Y-m-d');
        $countEvents = $EventModel->find('count', $params);
        $stats['Event']['total'] = $countEvents;

        // Reports
        App::uses('Report', 'Model');
        $ReportModel = new Report();

        $params = array();
        $params['recursive'] = -1;
        $countReports = $ReportModel->find('count', $params);
        $stats['Report']['total'] = $countReports;

        // Users
        App::uses('User', 'Model');
        $UserModel = new User();

        $params = array();
        $params['recursive'] = -1;
        $countUsers = $UserModel->find('count', $params);
        $stats['User']['total'] = $countUsers;

        // Characters
        App::uses('Character', 'Model');
        $CharacterModel = new Character();

        $params = array();
        $params['recursive'] = -1;
        $countCharacters = $CharacterModel->find('count', $params);
        $stats['Character']['total'] = $countCharacters;

        // Games
        App::uses('Game', 'Model');
        $GameModel = new Game();

        $params = array();
        $params['recursive'] = -1;
        if($games = $GameModel->find('all', $params)) {
            foreach($games as $game) {
                $stats['Game'][$game['Game']['slug']]['title'] = $game['Game']['title'];
                $stats['Game'][$game['Game']['slug']]['imported'] = $game['Game']['import_modified'] > 0?1:0;
                $params = array();
                $params['recursive'] = -1;
                $params['conditions']['game_id'] = $game['Game']['id'];
                $params['group'] = array('user_id');
                $countCharacters = $CharacterModel->find('count', $params);
                $stats['Game'][$game['Game']['slug']]['players'] = $countCharacters;
            }
        }

        // Bridge
        $bridgeSetting = json_decode($this->SettingModel->getOption('bridge'));
        $stats['Bridge']['enabled'] = (!empty($bridgeSetting) && $bridgeSetting->enabled)?1:0;

        // Widgets
        App::uses('Widget', 'Model');
        $WidgetModel = new Widget();

        $params = array();
        $params['recursive'] = -1;
        if($widgets = $WidgetModel->find('all', $params)) {
            foreach($widgets as $widget) {
                if(!isset($stats['Widget'][$widget['Widget']['controller']])) {
                    $stats['Widget'][$widget['Widget']['controller']]['total'] = 1;
                }else {
                    $stats['Widget'][$widget['Widget']['controller']]['total']++;
                }
            }
        }

        App::uses('HttpSocket', 'Network/Http');
        $this->http = new HttpSocket();
        $this->http->post('http://stats.mushraider.com/acquire', $stats);
    }
}