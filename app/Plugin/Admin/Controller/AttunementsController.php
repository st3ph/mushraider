<?php
class AttunementsController extends AdminAppController {
    public $uses = array('Attunement');

    var $paginate = array(
        'Attunement' => array(
            'limit' => 20,
            'recursive' => 1,
            'order' => array('Attunement.rank' => 'asc')
        )
    );

    function beforeFilter() {
        parent::beforeFilter();
    }

    public function index() {
        $conditions = array();
        $attunements = $this->paginate('Attunement', $conditions);                
        $this->set('attunements', $attunements);
    }

    public function add() {
        if(!empty($this->request->data['Attunement'])) {            
            $toSave = array();
            $toSave['title'] = ucfirst($this->request->data['Attunement']['title']);
            $toSave['rank'] = $this->request->data['Attunement']['rank'];

            if($this->Attunement->save($toSave)) {
                if($this->request->is('ajax')) {
                    Configure::write('debug', 0);
                    $this->layout = 'ajax';
                    $this->autoRender = false;
                    $dungeon = array('id' => $this->Attunement->getLastInsertId(), 'title' => $toSave['title']);
                    return json_encode($dungeon);
                }else {
                    $this->Session->setFlash(__('%s has been added to your dungeons list', $toSave['title']), 'flash_success');
                    return $this->redirect('/admin/attunements');
                }
            }
        }

        if($this->request->is('ajax')) {
            Configure::write('debug', 0);
            $this->layout = 'ajax';
            $this->autoRender = false;
            $this->render('Elements/AddAttunements');
            return;
        }
    }

    public function edit($id = null) {
        if(!$id) {
            return $this->redirect('/admin/attunements');
        }

        $params = array();
        //$params['fields'] = array('id');
       	$params['recursive'] = 1;
        $params['conditions']['Attunement.id'] = $id;
        if(!$attunement = $this->Attunement->find('first', $params)) {
            $this->Session->setFlash(__('MushRaider is unable to find this attunement oO'), 'flash_error');
            return $this->redirect('/admin/attunements');
        }

        if(!empty($this->request->data['Attunement']) && $this->request->data['Attunement']['id'] == $id) {
            $toSave = array();
            $toSave['id'] = $this->request->data['Attunement']['id'];            
            $toSave['title'] = ucfirst($this->request->data['Attunement']['title']);
            $toSave['rank'] = ucfirst($this->request->data['Attunement']['rank']);

            if($this->Attunement->save($toSave)) {
                $this->Session->setFlash(__('Attunement %s has been updated', $attunement['Attunement']['title']), 'flash_success');
                return $this->redirect('/admin/attunements');
            }

            $this->Session->setFlash(__('Something goes wrong'), 'flash_error');

            $attunement['Attunement'] = array_merge($attunement['Attunement'], $this->request->data['Attunement']);
        }
        
         Debugger::dump($attunement);

        $this->request->data['Attunement'] = $attunement['Attunement'];
    }

    public function delete($id = null) {
        if($id) {
            $params = array();
            $params['fields'] = array('id');
            $params['recursive'] = -1;
            $params['conditions']['id'] = $id;
            if(!$attunement = $this->Attunement->find('first', $params)) {
                $this->Session->setFlash(__('This attunement is linked to a game, you can\'t delete it.'), 'flash_warning');
            }elseif($this->Attunement->delete($id)) {
                $this->Session->setFlash(__('The attunement has been deleted'), 'flash_success');
            }else {
                $this->Session->setFlash(__('Something goes wrong'), 'flash_error');
            }
        }
 
        return $this->redirect('/admin/attunements');
    }
}