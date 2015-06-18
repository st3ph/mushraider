<?php
class RacesController extends AdminAppController {
    public $uses = array('Game', 'Race');

    var $paginate = array(
        'Race' => array(
            'limit' => 20,
            'recursive' => 1,
            'contain' => array('Game'),
            'order' => array('Race.game_id' => 'asc', 'Race.title' => 'asc')
        )
    );

    var $adminOnly = true;

    function beforeFilter() {
        parent::beforeFilter();
    }

    public function index() {
        $params = array();
        $params['recursive'] = 1;
        $params['order'] = array('Race.game_id' => 'asc', 'Race.title' => 'asc');
        $params['contain']['Game'] = array();
        $params['conditions']['game_id'] = null;
        $this->set('racesWithoutGame', $this->Race->find('all', $params));

        unset($params['conditions']['game_id']);
        $params['conditions']['game_id !='] = null;
        $this->set('races', $this->Race->find('all', $params));
    }

    public function add() {
        if(!empty($this->request->data['Race'])) {            
            $toSave = array();
            $toSave['title'] = ucfirst($this->request->data['Race']['title']);
            $toSave['slug'] = $this->Tools->slugMe($toSave['title']);
            $toSave['game_id'] = !empty($this->request->data['Race']['game_id'])?$this->request->data['Race']['game_id']:null;

            if($this->Race->save($toSave)) {
                if($this->request->is('ajax')) {
                    Configure::write('debug', 0);
                    $this->layout = 'ajax';
                    $this->autoRender = false;
                    $dungeon = array('id' => $this->Race->getLastInsertId(), 'title' => $toSave['title']);
                    return json_encode($dungeon);
                }else {
                    $this->Session->setFlash(__('%s has been added to your races list', $toSave['title']), 'flash_success');
                    $this->redirect('/admin/races');
                }
            }
        }


        if($this->request->is('ajax')) {
            Configure::write('debug', 0);
            $this->layout = 'ajax';
            $this->autoRender = false;
            $this->render('Elements/addRace');
            return;
        }else {
            $gamesList = $this->Game->find('list', array('order' => 'title ASC'));        
            $this->set('gamesList', $gamesList);  
        }
    }

    public function edit($id = null) {
        if(!$id) {
            $this->redirect('/admin/races');
        }

        $params = array();
        $params['recursive'] = 1;
        $params['contain']['Game'] = array();     
        $params['conditions']['Race.id'] = $id;
        if(!$race = $this->Race->find('first', $params)) {
            $this->Session->setFlash(__('MushRaider is unable to find this race oO'), 'flash_error');
            $this->redirect('/admin/races');
        }

        if(!empty($this->request->data['Race']) && $this->request->data['Race']['id'] == $id) {
            $toSave = array();
            $toSave['id'] = $this->request->data['Race']['id'];            
            $toSave['title'] = ucfirst($this->request->data['Race']['title']);
            $toSave['slug'] = $this->Tools->slugMe($toSave['title']);
            $toSave['game_id'] = $this->request->data['Race']['game_id'];

            if($this->Race->save($toSave)) {
                $this->Session->setFlash(__('Race %s has been updated', $race['Race']['title']), 'flash_success');
                $this->redirect('/admin/races');
            }

            $this->Session->setFlash(__('Something goes wrong'), 'flash_error');

            $race['Race'] = array_merge($race['Race'], $this->request->data['Race']);
        }

        $gamesList = $this->Game->find('list', array('order' => 'title ASC'));        
        $this->set('gamesList', $gamesList);      

        $this->request->data['Race'] = $race['Race'];
    }

    public function delete($id = null) {
        if($id) {
            $params = array();
            $params['fields'] = array('id');
            $params['recursive'] = -1;
            $params['conditions']['id'] = $id;
            if(!$race = $this->Race->find('first', $params)) {
                $this->Session->setFlash(__('MushRaider is unable to find this race oO'), 'flash_error');
            }elseif($this->Race->delete($id)) {
                $toDelete = array();
                $toDelete['race_id'] = $id;  
                $this->Character->deleteAll($toDelete, true);
                $this->Session->setFlash(__('The race has been deleted'), 'flash_success');
            }else {
                $this->Session->setFlash(__('Something goes wrong'), 'flash_error');
            }
        }
 
        $this->redirect('/admin/races');
    }
}