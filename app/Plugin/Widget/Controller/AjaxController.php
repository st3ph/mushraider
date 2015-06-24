<?php
class AjaxController extends WidgetAppController {
    var $uses = array();

    public function beforeFilter() {
        parent::beforeFilter();

        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $this->autoRender = false;
    }

    public function getList() {
        $json = array();

        App::uses('Widget', 'Model');
        $WidgetModel = new Widget();
        $params = array();
        $params['recursive'] = -1;
        $params['fields'] = array('id', 'controller', 'action', 'title');
        $params['order'] = 'title asc';
        if($widgets = $WidgetModel->find('all', $params)) {
            $json['widgets'] = $widgets;
        }else {
            $json['msg'] = __('Vous n\'avez aucun widget à insérer');
        }

        return json_encode($json);
    }
}