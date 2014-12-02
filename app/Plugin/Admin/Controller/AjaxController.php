<?php
class AjaxController extends AdminAppController {
    var $uses = array();

    public function beforeFilter() {
        parent::beforeFilter();

        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $this->autoRender = false;
    }

    public function updateOrder() {
        $jsonMessage = array();

        if(!empty($this->request->query['m']) && !empty($this->request->query['orderdata'])) {
            $modelName = $this->request->query['m'];
            App::uses($modelName, 'Model');
            $myModel = new $modelName();

            foreach($this->request->query['orderdata'] as $order => $id) {
                $params = array();
                $params['id'] = $id;
                $params['order'] = $order;
                $myModel->create();
                $myModel->save($params);
            }

            $jsonMessage['type'] = 'success';
            $jsonMessage['msg'] = __('New order saved successfully');
        }else {
            $jsonMessage['type'] = 'important';
            $jsonMessage['msg'] = __('An error occur while saving the order');
        }

        return json_encode($jsonMessage);
    }

    public function importGame() {
        $this->Session->delete('ajaxProgress');
        $jsonMessage = array();

        if(!empty($this->request->query['slug'])) {
            App::uses('Game', 'Model');
            $GameModel = new Game();

            $slug = $this->request->query['slug'];
            $this->Session->write('ajaxProgress', 10);

            App::uses('RaidheadSource', 'Model/Datasource');
            $RaidHead = new RaidheadSource();
            $game = $RaidHead->get($slug);

            // Check API error
            if($game['error']) {
                $jsonMessage['type'] = 'important';
                switch($game['error']) {
                    case 401:
                        $jsonMessage['msg'] = __('Import failed : Game not found');
                        break;
                    default:
                        $jsonMessage['msg'] = __('Import failed : An error occur while importing the game');
                }
                return json_encode($jsonMessage);
            }

            $this->Session->write('ajaxProgress', 30);

            $toSave = array();
            $toSave['title'] = $game['game']['title'];
            $toSave['slug'] = $game['game']['short'];
            $toSave['logo'] = $game['game']['icon_64'];
            $toSave['import_slug'] = $game['game']['short'];
            $toSave['import_modified'] = $game['lastupdate'];
            if(!$gameId = $GameModel->__add($toSave)) {
                $jsonMessage['type'] = 'important';
                $jsonMessage['msg'] = __('Save failed : An error occur while saving the game');
                return json_encode($jsonMessage);
            }

            $this->Session->write('ajaxProgress', 50);

            // Dungeons
            if($game['game']['has_dungeon'] && !empty($game['dungeons'])) {
                App::uses('Dungeon', 'Model');
                $DungeonModel = new Dungeon();
                App::uses('RaidsSize', 'Model');
                $RaidsSizeModel = new RaidsSize();

                foreach($game['dungeons'] as $dungeonSlug => $dungeon) {
                    $toSaveDungeons = array();
                    $toSaveDungeons['game_id'] = $gameId;
                    $toSaveDungeons['title'] = $dungeon['title'];
                    $toSaveDungeons['slug'] = $dungeonSlug;
                    $toSaveDungeons['raidssize_id'] = $RaidsSizeModel->__add($dungeon['max_players']);
                    $DungeonModel->__add($toSaveDungeons, array('game_id' => $gameId));
                }
            }

            $this->Session->write('ajaxProgress', 65);

            // Races
            if($game['game']['has_race'] && !empty($game['races'])) {
                App::uses('Race', 'Model');
                $RaceModel = new Race();

                foreach($game['races'] as $raceSlug => $race) {
                    $toSaveRaces = array();
                    $toSaveRaces['game_id'] = $gameId;
                    $toSaveRaces['title'] = $race['title'];
                    $toSaveRaces['slug'] = $raceSlug;
                    $RaceModel->__add($toSaveRaces, array('game_id' => $gameId));
                }
            }

            $this->Session->write('ajaxProgress', 80);

            // Classes
            if($game['game']['has_classe'] && !empty($game['classes'])) {
                App::uses('Classe', 'Model');
                $ClasseModel = new Classe();
                foreach($game['classes'] as $classeSlug => $classe) {
                    $toSaveClasses = array();
                    $toSaveClasses['game_id'] = $gameId;
                    $toSaveClasses['title'] = $classe['title'];
                    $toSaveClasses['slug'] = $classeSlug;
                    if(!empty($classe['icon_64'])) {
                        $toSaveClasses['icon'] = $classe['icon_64'];
                    }

                    $defaultColor = '#000000';
                    $color = !empty($classe['color'])?$classe['color']:$defaultColor;
                    $color = strlen($color) < 6?$defaultColor:$color;

                    $toSaveClasses['color'] = $color;
                    $ClasseModel->__add($toSaveClasses, array('game_id' => $gameId));
                }
            }
            
            $this->Session->write('ajaxProgress', 100);

            $jsonMessage['type'] = 'success';
            $jsonMessage['msg'] = __('Game imported successfully, you are now redirected to games list');
        }else {
            $jsonMessage['type'] = 'important';
            $jsonMessage['msg'] = __('Import failed : An error occur while importing the game');
        }

        return json_encode($jsonMessage);
    }

    public function ajaxProgress() {
        $progress = 0;
        if($this->Session->check('ajaxProgress')) {
            $progress = $this->Session->read('ajaxProgress');            
        }

        return $progress;
    }

    public function checkUpdates() {
        $jsonList = array();

        App::uses('Game', 'Model');
        $GameModel = new Game();

        App::uses('RaidheadSource', 'Model/Datasource');
        $RaidHead = new RaidheadSource();
        $apiGames = $RaidHead->gets();

        $params = array();        
        $params['recursive'] = -1;
        $params['fields'] = array('import_slug', 'import_modified');
        $params['conditions']['import_slug !='] = null;
        if($games = $GameModel->find('all', $params)) {
            foreach($games as $game) {
                if(!empty($apiGames[$game['Game']['import_slug']])) {
                    if($apiGames[$game['Game']['import_slug']]['lastupdate'] > $game['Game']['import_modified']) {
                        $jsonList[] = $game['Game']['import_slug'];
                    }
                }
            }
        }

        return json_encode($jsonList);
    }
}