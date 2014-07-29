<?php
App::uses('AppModel', 'Model');
class Attenuement extends AppModel {
    var $actsAs = array('Containable');

    public $name = 'Attenuement';
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