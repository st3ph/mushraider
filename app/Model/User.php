<?php
App::uses('AppModel', 'Model');
class User extends AppModel {
    var $actsAs = array('Containable');

    public $name = 'User';
    public $displayField = 'username';

    public $order = 'User.username ASC';

    public $belongsTo = array('Role');
    public $hasMany = array(
        'EventsCharacter' => array(
            'className' => 'EventsCharacter',
            'foreignKey' => 'user_id',
            'dependent'=> true
        ),
        'Character' => array(
            'className' => 'Character',
            'foreignKey' => 'user_id',
            'dependent'=> true
        )
    );


    public $validate = array(
        'username' => array(
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'The username has already been taken.',
                'last' => true,
            ),
            'notEmpty' => array(
                'rule' => 'notBlank',
                'message' => 'This field cannot be left blank.',
                'last' => true,
            ),
            'validAlias' => array(
                'rule' => '/^([0-9a-zA-Z_\-]{3,20})$/',
                'message' => 'Username can be composed of letters and numbers only, between 3 and 20 chars length',
                'last' => true,
            ),
        ),
        'email' => array(
            'email' => array(
                'rule' => 'email',
                'message' => 'Please provide a valid email address.',
                'last' => true,
            ),
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'Email address already in use.',
                'last' => true,
            ),
        ),
        'password' => array(
            'rule' => array('minLength', 6),
            'message' => 'Passwords must be at least 6 characters long.',
        ),
        'verify_password' => array(
            'rule' => 'validIdentical',
        )
    );

    /**
     * _identical
     *
     * @param string $check
     * @return boolean
     * @deprecated Protected validation methods are no longer supported
     */
    protected function _identical($check) {
        return $this->validIdentical($check);
    }

    /**
     * validIdentical
     *
     * @param string $check
     * @return boolean
     */
    public function validIdentical($check) {
        if (isset($this->data['User']['password'])) {
            if ($this->data['User']['password'] != $check['verify_password']) {
                return __('Passwords do not match. Please, try again.');
            }
        }
        return true;
    }

    public function beforeFind($params) {
        if(!isset($params['conditions']['status']) && !isset($params['conditions']['User.status'])) {
            $params['conditions']['User.status'] = 1;
        }

        return $params;
    }

    public function beforeSave($options = array()) {
        if(!isset($this->data['User']['id']) || empty($this->data['User']['id'])) {
            if(!isset($this->data['User']['activation_key']) || empty($this->data['User']['activation_key'])) {
                $this->data['User']['activation_key'] = md5(uniqid());
            }

            if(!isset($this->data['User']['calendar_key']) || empty($this->data['User']['calendar_key'])) {
                $this->data['User']['calendar_key'] = uniqid();
            }
        }
        
        return true;
    }
}