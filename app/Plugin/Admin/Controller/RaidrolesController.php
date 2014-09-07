<?php
class RaidrolesController extends AdminAppController {
    public $uses = array('RaidsRole', 'Character', 'EventsCharacter', 'EventsRole', 'EventsTemplatesRole');

    var $adminOnly = true;

    function beforeFilter() {
        parent::beforeFilter();
    }

    public function index() {
        $params = array();
        $params['recursive'] = -1;
        $params['order'] = array('order ASC', 'title');
        $raidRoles = $this->RaidsRole->find('all', $params);
        $this->set('raidRoles', $raidRoles);
    }

    public function add() {
        if(!empty($this->request->data['RaidsRole'])) {
            if($this->RaidsRole->__add($this->request->data['RaidsRole']['title'])) {
                $this->Session->setFlash(__('%s has been added to your roles list', $toSave['title']), 'flash_success');
                return $this->redirect('/admin/raidroles');
            }
        }
    }

    public function edit($id = null) {
        if(!$id) {
            return $this->redirect('/admin/raidroles');
        }

        $params = array();
        $params['recursive'] = -1;
        $params['conditions']['RaidsRole.id'] = $id;

        if(!$raidRole = $this->RaidsRole->find('first', $params)) {
            $this->Session->setFlash(__('MushRaider is unable to find this role oO'), 'flash_error');
            return $this->redirect('/admin/raidroles');
        }

        if(!empty($this->request->data['RaidsRole']) && $this->request->data['RaidsRole']['id'] == $id) {
            $toSave = array();
            $toSave['id'] = $this->request->data['RaidsRole']['id'];
            $toSave['title'] = $this->request->data['RaidsRole']['title'];

            if($this->RaidsRole->save($toSave)) {
                $this->Session->setFlash(__('Role %s has been updated', $raidRole['RaidsRole']['title']), 'flash_success');
                return $this->redirect('/admin/raidroles');
            }

            $this->Session->setFlash(__('Something goes wrong'), 'flash_error');

            $raidRole['RaidsRole'] = array_merge($raidRole['RaidsRole'], $this->request->data['RaidsRole']);
        }

        $this->request->data['RaidsRole'] = $raidRole['RaidsRole'];
    }

    public function delete($id) {
        // Get
        $params = array();
        $params['recursive'] = -1;
        $params['conditions']['RaidsRole.id'] = $id;
        if(!$raidRole = $this->RaidsRole->find('first', $params)) {
            $this->Session->setFlash(__('MushRaider is unable to find this role oO'), 'flash_error');
            return $this->redirect('/admin/raidroles');
        }

        // Delete
        if(!$id || (!empty($this->request->data['RaidsRole']) && !empty($this->request->data['RaidsRole']['id']))) {
            if($this->request->data['RaidsRole']['id'] == $id) {
                $this->Session->setFlash(__('Something goes wrong'), 'flash_error');
                return $this->redirect('/admin/raidroles/delete/'.$id);
            }

            // Test if the new role exist
            $params = array();
            $params['recursive'] = -1;
            $params['fields'] = array('id', 'title');
            $params['conditions']['RaidsRole.id'] = $this->request->data['RaidsRole']['id'];
            if(!$newRole = $this->RaidsRole->find('first', $params)) {
                $this->Session->setFlash(__('MushRaider is unable to find the replacement role oO'), 'flash_error');
                return $this->redirect('/admin/raidroles/delete/'.$id);
            }

            // Replacements
            // Character table
            $toUpdate = array();
            $toUpdate['fields']['default_role_id'] = $this->request->data['RaidsRole']['id'];
            $toUpdate['conditions']['default_role_id'] = $id;
            $this->Character->updateAll($toUpdate['fields'], $toUpdate['conditions']);

            // EventsCharacter table
            $toUpdate = array();
            $toUpdate['fields']['raids_role_id'] = $this->request->data['RaidsRole']['id'];
            $toUpdate['conditions']['raids_role_id'] = $id;
            $this->EventsCharacter->updateAll($toUpdate['fields'], $toUpdate['conditions']);

            // EventsRole table
            $this->EventsRole->deleteAll(array('raids_role_id' => $id), false);

            // EventsTemplatesRole table
            $this->EventsTemplatesRole->deleteAll(array('raids_role_id' => $id), false);

            // RaidsRole
            $this->RaidsRole->delete($id);

            $this->Session->setFlash(__('Role %s has been deleted and replaced with %s', $raidRole['RaidsRole']['title'], $newRole['RaidsRole']['title']), 'flash_success');
            return $this->redirect('/admin/raidroles');
        }

        // Replace with...
        $params = array();
        $params['recursive'] = -1;
        $params['conditions']['id !='] = $id;
        $raidRoles = $this->RaidsRole->find('list', $params);
        $this->set('raidRoles', $raidRoles);

        $this->request->data['RaidsRole'] = $raidRole['RaidsRole'];
    }
}