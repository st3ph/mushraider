<?php
App::uses('AppModel', 'Model');
class RolesUser extends AppModel {
	var $actsAs = array('Containable');
	
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
        ),
        'Role' => array(
            'className' => 'Role',
        ),
    );
}
