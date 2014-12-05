<?php
class DungeonsController extends AdminAppController {
    public $components = array('Image');
    public $uses = array('Game', 'Dungeon', 'RaidsSize');

    var $adminOnly = true;

    function beforeFilter() {
        parent::beforeFilter();
    }

    public function index() {
        $params = array();
        $params['recursive'] = 1;
        $params['order'] = array('Dungeon.game_id' => 'asc', 'Dungeon.title' => 'asc');
        $params['contain']['Game'] = array();
        $params['contain']['RaidsSize'] = array();
        $params['conditions']['game_id'] = null;
        $this->set('dungeonsWithoutGame', $this->Dungeon->find('all', $params));

        unset($params['conditions']['game_id']);
        $params['conditions']['game_id !='] = null;
        $this->set('dungeons', $this->Dungeon->find('all', $params));
    }

    public function disabled() {
        $params = array();
        $params['limit'] = 20;
        $params['recursive'] = 1;
        $params['order'] = array('Dungeon.game_id' => 'asc', 'Dungeon.title' => 'asc');
        $params['contain']['Game'] = array();
        $params['contain']['RaidsSize'] = array();
        $params['conditions']['Dungeon.status'] = '0';
        $params['conditions']['game_id'] = null;
        $this->set('dungeonsWithoutGame', $this->Dungeon->find('all', $params));

        unset($params['conditions']['game_id']);
        $params['conditions']['game_id !='] = null;
        $this->set('dungeons', $this->Dungeon->find('all', $params));
    }

    public function add() {
        if(!empty($this->request->data['Dungeon'])) {            
            $toSave = array();
            $toSave['title'] = ucfirst($this->request->data['Dungeon']['title']);
            $toSave['slug'] = $this->Tools->slugMe($toSave['title']);
            $toSave['game_id'] = !empty($this->request->data['Dungeon']['game_id'])?$this->request->data['Dungeon']['game_id']:null;
            if(!empty($this->request->data['Dungeon']['customraidssize'])) {
                $toSave['raidssize_id'] = $this->RaidsSize->__add($this->request->data['Dungeon']['customraidssize']);
            }elseif(!empty($this->request->data['Dungeon']['raidssize_id'])) {
                $toSave['raidssize_id'] = $this->request->data['Dungeon']['raidssize_id'];
            }
            $toSave['level_required'] = $this->request->data['Dungeon']['level_required'];

            if(!empty($this->request->data['Dungeon']['icon'])) {
                $imageName = $this->Image->__add($this->request->data['Dungeon']['icon'], 'files/icons/dungeons', 'dungeon_', 64, 64);
                $toSave['icon'] = $imageName['name'];
            }

            if($this->Dungeon->save($toSave)) {
                if($this->request->is('ajax')) {
                    Configure::write('debug', 0);
                    $this->layout = 'ajax';
                    $this->autoRender = false;
                    $dungeon = array('id' => $this->Dungeon->getLastInsertId(), 'title' => $toSave['title']);
                    return json_encode($dungeon);
                }else {
                    $this->Session->setFlash(__('%s has been added to your dungeons list', $toSave['title']), 'flash_success');
                    return $this->redirect('/admin/dungeons');
                }
            }
        }

        $raidssizeList = $this->RaidsSize->find('list', array('order' => 'size ASC'));
        $this->set('raidssizeList', $raidssizeList);

        if($this->request->is('ajax')) {
            Configure::write('debug', 0);
            $this->layout = 'ajax';
            $this->autoRender = false;
            $this->render('Elements/addDungeon');
            return;
        }else {
            $gamesList = $this->Game->find('list', array('order' => 'title ASC'));        
            $this->set('gamesList', $gamesList);  
        }
    }

    public function edit($id = null) {
        if(!$id) {
            return $this->redirect('/admin/dungeons');
        }

        $params = array();
        $params['recursive'] = 1;
        $params['contain']['RaidsSize'] = array();     
        $params['conditions']['Dungeon.id'] = $id;
        $params['conditions']['Dungeon.status'] = array('0', '1');
        if(!$dungeon = $this->Dungeon->find('first', $params)) {
            $this->Session->setFlash(__('MushRaider is unable to find this dungeon oO'), 'flash_error');
            return $this->redirect('/admin/dungeons');
        }

        if(!empty($this->request->data['Dungeon']) && $this->request->data['Dungeon']['id'] == $id) {
            $toSave = array();
            $toSave['id'] = $this->request->data['Dungeon']['id'];            
            $toSave['title'] = ucfirst($this->request->data['Dungeon']['title']);
            $toSave['slug'] = $this->Tools->slugMe($toSave['title']);
            $toSave['game_id'] = $this->request->data['Dungeon']['game_id'];
            if(!empty($this->request->data['Dungeon']['customraidssize'])) {
                $toSave['raidssize_id'] = $this->RaidsSize->__add($this->request->data['Dungeon']['customraidssize']);
            }elseif(!empty($this->request->data['Dungeon']['raidssize_id'])) {
                $toSave['raidssize_id'] = $this->request->data['Dungeon']['raidssize_id'];
            }
            $toSave['level_required'] = $this->request->data['Dungeon']['level_required'];
            $imageName = $this->Image->__add($this->request->data['Dungeon']['icon'], 'files/icons/dungeons', 'dungeon_', 64, 64);
            if(!empty($imageName['name'])) {
                $toSave['icon'] = $imageName['name'];
            }elseif($this->request->data['Dungeon']['deleteIcon']) {
                $toSave['icon'] = '';
            }

            if($this->Dungeon->save($toSave)) {
                $this->Session->setFlash(__('Dungeon %s has been updated', $dungeon['Dungeon']['title']), 'flash_success');
                return $this->redirect('/admin/dungeons');
            }

            $this->Session->setFlash(__('Something goes wrong'), 'flash_error');

            $dungeon['Dungeon'] = array_merge($dungeon['Dungeon'], $this->request->data['Dungeon']);
        }

        $gamesList = $this->Game->find('list', array('order' => 'title ASC'));        
        $this->set('gamesList', $gamesList);

        $raidssizeList = $this->RaidsSize->find('list', array('order' => 'size ASC'));
        $this->set('raidssizeList', $raidssizeList);        

        $this->request->data['Dungeon'] = $dungeon['Dungeon'];
    }

    public function delete($id = null) {
        if($id) {
            $params = array();
            $params['fields'] = array('id');
            $params['recursive'] = -1;
            $params['conditions']['id'] = $id;
            $params['conditions']['status'] = array('0', '1');
            if(!$dungeon = $this->Dungeon->find('first', $params)) {
                $this->Session->setFlash(__('This dungeon is linked to a game, you can\'t delete it.'), 'flash_warning');
            }elseif($this->Dungeon->delete($id)) {
                $this->Session->setFlash(__('The dungeon has been deleted'), 'flash_success');
            }else {
                $this->Session->setFlash(__('Something goes wrong'), 'flash_error');
            }
        }
 
        return $this->redirect('/admin/dungeons');
    }

    public function disable($id = null) {
        if($id) {
            $toSave = array();
            $toSave['id'] = $id;
            $toSave['status'] = 0;
            if($this->Dungeon->save($toSave)) {
                $this->Session->setFlash(__('The dungeon has been disable'), 'flash_success');
            }else {
                $this->Session->setFlash(__('Something goes wrong'), 'flash_error');
            }
        }
 
        return $this->redirect('/admin/dungeons');
    }

    public function enable($id = null) {
        if($id) {
            $toSave = array();
            $toSave['id'] = $id;
            $toSave['status'] = 1;
            if($this->Dungeon->save($toSave)) {
                $this->Session->setFlash(__('The dungeon has been enable'), 'flash_success');
            }else {
                $this->Session->setFlash(__('Something goes wrong'), 'flash_error');
            }
        }
 
        return $this->redirect('/admin/dungeons');
    }
}