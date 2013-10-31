<?php
App::uses('AppModel', 'Model');
class RolesUser extends AppModel {

    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
        ),
        'Role' => array(
            'className' => 'Role',
        ),
    );
}
