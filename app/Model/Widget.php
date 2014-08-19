<?php
App::uses('AppModel', 'Model');
class Widget extends AppModel {
    public $name = 'Widget';
    public $displayField = 'title';

    var $actsAs = array('Containable');

    public $validate = array(
        'title' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Title cannot be empty.'
            )
        ),
        'controller' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Controller cannot be empty.'
            )
        ),
        'action' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Action cannot be empty.'
            )
        )
    );

    public function getAvailableWidgets() {
        return array(
            'events_index' => __('Incoming events list'),
            'roster_index' => __('Players list')
        );
    }
}
