<?php
App::uses('AppModel', 'Model');
class Setting extends AppModel {
    public $name = 'Setting';

    public function getOption($option = null) {
        if(!$option) {
            return null;
        }

        $params = array();
        $params['fields'] = array('value');
        $params['recursive'] = -1;
        $params['conditions']['option'] = $option;
        if($setting = $this->find('first', $params)) {
            return $setting['Setting']['value'];
        }

        return null;
    }

    public function setOption($option = null, $value) {
        if(!$option) {
            return null;
        }

        $toUpdate = array();
        $toUpdate['value'] = $value;

        $params = array();
        $params['fields'] = array('id');
        $params['recursive'] = -1;
        $params['conditions']['option'] = $option;
        if($setting = $this->find('first', $params)) {
            $toUpdate['id'] = $setting['Setting']['id'];
            return $this->save($toUpdate);
        }else {
            $toUpdate['option'] = $option;
            $this->create();
            return $this->save($toUpdate);
        }
    }
}