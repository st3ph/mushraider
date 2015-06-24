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
        if($widgets = $WidgetModel->find('list', array('order' => 'title asc'))) {
            $json['widgets'] = $widgets;
        }else {
            $json['msg'] = __('Vous n\'avez aucun widget à insérer');
        }

        return json_encode($json);
    }
}