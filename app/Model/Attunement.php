<?php
App::uses('AppModel', 'Model');
class Attunement extends AppModel {
    var $actsAs = array('Containable');

    public $name = 'Attunement';
    public $displayField = 'title';

    public $belongsTo = array(
    );

    public $validate = array(
        'title' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'This field cannot be left blank.',
                'last' => true,
            )
        ),
    );
}