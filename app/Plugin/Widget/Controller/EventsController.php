<?php
class EventsController extends WidgetAppController {
    var $uses = array('Event');

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

        $calStart = date('Y-m-d');
        $calEnd = date('Y-m-d H:i:s', mktime(23, 59, 59, date('n'), date('j') + $widget['Widget']['params']->days, date('Y')));

        $params = array();
        $params['recursive'] = 1;
        $params['order'] = array('Event.time_invitation ASC');
        $params['fields'] = array('Event.id', 'Event.title', 'Event.game_id', 'Event.dungeon_id', 'Event.time_invitation', 'Event.time_start', 'Event.character_level');
        $params['contain']['Game']['fields'] = array('title', 'logo');
        $params['contain']['Dungeon']['fields'] = array('title', 'icon');
        $params['contain']['EventsRole']['RaidsRole'] = array();
        $params['contain']['EventsCharacter']['Character']['Classe'] = array();        
        $params['contain']['EventsCharacter']['Character']['User'] = array();
        $params['conditions']['Event.time_invitation >='] = $calStart;
        $params['conditions']['Event.time_invitation <='] = $calEnd;
        if(!empty($widget['Widget']['params']->game_id)) {
            $params['conditions']['Event.game_id'] = $widget['Widget']['params']->game_id;
        }
        $events = $this->Event->find('all', $params);
        $this->set('events', $events);
        $this->set('widget', $widget);

        if($widget['Widget']['params']->private && !$this->user) {
            $this->layout = 'login';
            return;
        }
    }
}