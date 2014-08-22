 <?php
class AjaxController extends AdminAppController {
    var $uses = array();

    function beforeFilter() {
        parent::beforeFilter();

        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $this->autoRender = false;
    }

    function updateOrder() {
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

    function importGame() {
        $this->Session->delete('ajaxProgress');
        $jsonMessage = array();

        if(!empty($this->request->query['slug'])) {
            App::uses('Game', 'Model');
            $GameModel = new Game();

            $slug = $this->request->query['slug'];
            $this->Session->write('ajaxProgress', 10);

            // Maybe the game already exist ?
            $params = array();
            $params['recursive'] = -1;
            $params['fields'] = array('id', 'title');
            $params['conditions']['slug'] = $slug;
            if($game = $GameModel->find('first', $params)) {
                $jsonMessage['type'] = 'warning';
                $jsonMessage['msg'] = __('Import failed : The game %s already exists in your installation', $game['Game']['title']);
                return json_encode($jsonMessage);
            }

            $this->Session->write('ajaxProgress', 20);

            App::uses('RaidheadSource', 'Model/Datasource');
            $RaidHead = new RaidheadSource();
            $game = $RaidHead->get($slug);
            prd($game);

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
            if(!$GameModel->save($toSave)) {
                $jsonMessage['type'] = 'important';
                $jsonMessage['msg'] = __('Save failed : An error occur while saving the game');
                return json_encode($jsonMessage);
            }
            $gameId = $GameModel->getLastInsertId();

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
                    $DungeonModel->create();
                    $DungeonModel->save($toSaveDungeons);
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
                    $RaceModel->create();
                    $RaceModel->save($toSaveRaces);
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
                    $toSaveClasses['icon'] = $classe['icon_64'];

                    $defaultColor = '#000000';
                    $color = !empty($classe['color'])?$classe['color']:$defaultColor;
                    $color = strlen($color) < 6?$defaultColor:$color;

                    $toSaveClasses['color'] = $color;
                    $ClasseModel->create();
                    $ClasseModel->save($toSaveClasses);
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

    function ajaxProgress() {
        $progress = 0;
        if($this->Session->check('ajaxProgress')) {
            $progress = $this->Session->read('ajaxProgress');            
        }

        return $progress;
    }
}