<?php
App::uses('AppModel', 'Model');
class Page extends AppModel {
    var $actsAs = array('Containable');

    public $name = 'Page';
    public $displayField = 'title';

    public $validate = array(
        'title' => array(
            'notEmpty' => array(
                'rule' => 'notBlank',
                'message' => 'Title cannot be empty.'
            )
        ),
        'slug' => array(
            'notEmpty' => array(
                'rule' => 'notBlank',
                'message' => 'Slug cannot be empty.'
            )
        ),
        'content' => array(
            'notEmpty' => array(
                'rule' => 'notBlank',
                'message' => 'Content cannot be empty.'
            )
        )
    );

    public function beforeFind($params) {
        if(!isset($params['conditions']['published']) && !isset($params['conditions']['Page.published'])) {
            $params['conditions']['Page.published'] = 1;
        }

        return $params;
    }
}