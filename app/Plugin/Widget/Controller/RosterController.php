<?php
class RosterController extends WidgetAppController {    
    var $uses = array('Character');

    var $paginate = array(
        'User' => array(
            'limit' => 20,
            'recursive' => 2,
            'contain' => array(
                'Role' => array('fields' => array('title')), 
                'Character' => array(
                    'fields' => array('title', 'level', 'status'),
                    'Game' => array('fields' => array('title')), 
                    'Classe' => array('fields' => array('title', 'color')), 
                    'Race' => array('fields' => array('title'))
                )
            ),
            'order' => array('username' => 'asc'),
            'fields' => array('User.id', 'User.username')
        )
    );

    public function index($widgetId = null) {
        if(!$widgetId) {
            $this->layout = 'noWidget';
            return;
        }

        $params = array();
        $params['recursive'] = -1;
        $params['conditions']['Widget.id'] = $widgetId;
        if(!$widget = $this->Widget->find('first', $params)) {
            $this->layout = 'noWidget';
            return;
        }
        $widget['Widget']['params'] = json_decode($widget['Widget']['params']);

        $this->pageTitle = $widget['Widget']['title'];

        $params = array();
        $params['recursive'] = 1;
        $params['order'] = array('Character.title');
        $params['fields'] = array('Character.title', 'Character.level');
        $params['contain']['Game']['fields'] = array('title', 'logo');
        $params['contain']['Classe']['fields'] = array('title', 'icon', 'color');
        $params['contain']['Race']['fields'] = array('title');
        $params['contain']['User']['fields'] = array('username');
        $params['contain']['RaidsRole']['fields'] = array('title');
        if(!empty($widget['Widget']['params']->game_id)) {
            $params['conditions']['Character.game_id'] = $widget['Widget']['params']->game_id;
        }
        $characters = $this->Character->find('all', $params);
        $this->set('characters', $characters);
        $this->set('widget', $widget);

        if($widget['Widget']['params']->private && !$this->user) {
            $this->layout = 'login';
            return;
        }
    }
}