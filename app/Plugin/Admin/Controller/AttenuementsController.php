<?php
class AttenuementsController extends AdminAppController {
    public $uses = array('Attenuement');

    var $paginate = array(
        'Attenuement' => array(
            'limit' => 20,
            'recursive' => 1,
            'order' => array('Attenuement.rank' => 'asc')
        )
    );

    function beforeFilter() {
        parent::beforeFilter();
    }

    public function index() {
        $conditions = array();
        $attenuements = $this->paginate('Attenuement', $conditions);                
        $this->set('attenuements', $attenuements);
    }

    public function add() {
        if(!empty($this->request->data['Attenuement'])) {            
            $toSave = array();
            $toSave['title'] = ucfirst($this->request->data['Attenuement']['title']);
            $toSave['rank'] = $this->request->data['Attenuement']['rank'];

            if($this->Attenuement->save($toSave)) {
                if($this->request->is('ajax')) {
                    Configure::write('debug', 0);
                    $this->layout = 'ajax';
                    $this->autoRender = false;
                    $dungeon = array('id' => $this->Attenuement->getLastInsertId(), 'title' => $toSave['title']);
                    return json_encode($dungeon);
                }else {
                    $this->Session->setFlash(__('%s has been added to your dungeons list', $toSave['title']), 'flash_success');
                    return $this->redirect('/admin/attenuements');
                }
            }
        }

        if($this->request->is('ajax')) {
            Configure::write('debug', 0);
            $this->layout = 'ajax';
            $this->autoRender = false;
            $this->render('Elements/AddAttenuements');
            return;
        }
    }

    public function edit($id = null) {
        if(!$id) {
            return $this->redirect('/admin/attenuements');
        }

        $params = array();
        $params['recursive'] = 1;
        if(!$dungeon = $this->Attenuement->find('first', $params)) {
            $this->Session->setFlash(__('MushRaider is unable to find this attenuement oO'), 'flash_error');
            return $this->redirect('/admin/attenuements');
        }

        if(!empty($this->request->data['Attenuement']) && $this->request->data['Attenuement']['id'] == $id) {
            $toSave = array();
            $toSave['id'] = $this->request->data['Attenuement']['id'];            
            $toSave['title'] = ucfirst($this->request->data['Attenuement']['title']);
            $toSave['rank'] = ucfirst($this->request->data['Attenuement']['rank']);

            if($this->Attenuement->save($toSave)) {
                $this->Session->setFlash(__('Attenuement %s has been updated', $dungeon['Attenuement']['title']), 'flash_success');
                return $this->redirect('/admin/attenuements');
            }

            $this->Session->setFlash(__('Something goes wrong'), 'flash_error');

            $dungeon['Attenuement'] = array_merge($dungeon['Attenuement'], $this->request->data['Attenuement']);
        }

        $this->request->data['Attenuement'] = $dungeon['Attenuement'];
    }

    public function delete($id = null) {
        if($id) {
            $params = array();
            $params['fields'] = array('id');
            $params['recursive'] = -1;
            $params['conditions']['id'] = $id;
            if(!$attenuement = $this->Attenuement->find('first', $params)) {
                $this->Session->setFlash(__('This attenuement is linked to a game, you can\'t delete it.'), 'flash_warning');
            }elseif($this->Attenuement->delete($id)) {
                $this->Session->setFlash(__('The attenuement has been deleted'), 'flash_success');
            }else {
                $this->Session->setFlash(__('Something goes wrong'), 'flash_error');
            }
        }
 
        return $this->redirect('/admin/attenuements');
    }
}