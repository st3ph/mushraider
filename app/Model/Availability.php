<?php
App::uses('AppModel', 'Model');
class Availability extends AppModel {
    var $actsAs = array('Containable');

    public $name = 'Availability';

    public $order = 'Availability.start ASC';

    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        ),
    );

    public $validate = array(
        'comment' => array(
            'isRequired' => array(
                'rule' => 'notEmpty',
                'required' => 'create',
                'message' => 'An absence reason is mandatory.'
            )
        ),
        'start' => array(
            'isRequired' => array(
                'rule' => 'notEmpty',
                'required' => 'create',
                'message' => 'Please choose a starting date.'
            ),
            'isDate' => array(
                'rule' => array('date', 'ymd'),
                'message' => 'Please choose a valid starting date.'
            ),
            'isNotInPast' => array(
                'rule' => 'notInPast',
            ),
            'isNotOverlapping' => array(
                'rule' => 'noOverlap',
            )
        ),
        'end' => array(
            'isRequired' => array(
                'rule' => 'notEmpty',
                'required' => 'create',
                'message' => 'Please choose an ending date.'
            ),
            'isDate' => array(
                'rule' => array('date', 'ymd'),
                'message' => 'Please choose a valid ending date.'
            ),
            'isGreater' => array(
                'rule' => 'dateGreater',
            ),
            'isNotInPast' => array(
                'rule' => 'notInPast',
            ),
            'isNotOverlapping' => array(
                'rule' => 'noOverlap',
            )
        ),
    );

    /**
     * dateGreater
     *
     * @param string $check
     * @return boolean
     */
    public function dateGreater($check) {
        if(isset($this->data['Availability']['start'])) {
            if($this->data['Availability']['start'] > $check['end']) {
                return __('Ending date must be AFTER the starting date oO.');
            }
        }
        return true;
    }

    /**
     * notInPast
     *
     * @param string $check
     * @return boolean
     */
    public function notInPast($check) {
        if(current($check) < date('Y-m-d')) {
            return __('Absence can\'t be set in past.');
        }

        return true;
    }

    /**
     * noOverlap
     *
     * @param string $check
     * @return boolean
     */
    public function noOverlap($check) {
        $params = array();
        $params['fields'] = array('id');
        $params['recursive'] = -1;
        $params['conditions']['user_id'] = $this->data['Availability']['user_id'];
        $params['conditions']['or'] = array(
                                        array(
                                            'start <=' => $this->data['Availability']['start'],
                                            'end >=' => $this->data['Availability']['start']
                                        ),
                                        array(
                                            'start <=' => $this->data['Availability']['end'],
                                            'end >=' => $this->data['Availability']['end']
                                        ),
                                        array(
                                            'start >=' => $this->data['Availability']['start'],
                                            'end <=' => $this->data['Availability']['end']
                                        )
                                      );
        if(!empty($this->data['Availability']['id'])) {
            $params['conditions']['id !='] = $this->data['Availability']['id'];
        }
        if($this->find('first', $params)) {
            if(isset($check['start'])) {
                return __('You are already marked as absent at the starting date.');
            }else {
                return __('You are already marked as absent at the ending date.');
            }
        }

        return true;
    }

    public function afterSave($created, $options) {
        // Mark the user as absent in all his games
        // Get characters, so we can also have the game list
        App::uses('Character', 'Model');
        $Character = new Character(); 
        $params = array();       
        $params['recursive'] = -1;
        $params['fields'] = array('Character.id', 'Character.game_id', 'Character.level', 'Character.default_role_id');
        $params['group'] = 'game_id';
        $params['conditions']['user_id'] = $this->data['Availability']['user_id'];
        $params['conditions']['main'] = 1;
        if($characters = $Character->find('all', $params)) {
            App::uses('Event', 'Model');
            $Event = new Event();
            $Event->Behaviors->detach('Commentable');
            App::uses('EventsCharacter', 'Model');
            $EventsCharacter = new EventsCharacter();
            foreach($characters as $character) {
                // Get events for this period
                $params = array();       
                $params['recursive'] = -1;
                $params['fields'] = array('Event.id');
                $params['conditions']['game_id'] = $character['Character']['game_id'];
                $params['conditions']['character_level <='] = $character['Character']['level'];
                $params['conditions']['time_start >='] = $this->data['Availability']['start'].' 00:00:00';
                $params['conditions']['time_start <='] = $this->data['Availability']['end'].' 23:59:59';
                if($events = $Event->find('all', $params)) {
                    foreach($events as $event) {
                        $toSave = array();
                        $toSave['event_id'] = $event['Event']['id'];
                        $toSave['user_id'] = $this->data['Availability']['user_id'];
                        $toSave['character_id'] = $character['Character']['id'];
                        $toSave['raids_role_id'] = $character['Character']['default_role_id'];
                        $toSave['comment'] = $this->data['Availability']['comment'];
                        $toSave['status'] = 0;
                        $EventsCharacter->__add($toSave);
                    }
                }
            }
        }

        return true;
    }
}