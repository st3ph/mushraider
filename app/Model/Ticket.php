<?php
App::uses('AppModel', 'Model');
class Ticket extends AppModel {
    public $name = 'Ticket';

    var $hashKey = 'V5z08!';

    /*
    * @name __create
    * @desc Create a new ticket
    * @params string $email corresponding email 
    * @params string $type type of ticket
    * @return mixed
    */
    function __create($email, $type) {
        $this->__garbage($email, $type);
        $toSaveTicket = array();
        $toSaveTicket['email'] = $email;
        $toSaveTicket['hash'] = md5($email.$this->hashKey.microtime());
        $toSaveTicket['type'] = $type;
        if($this->save($toSaveTicket)) {
            return $toSaveTicket['hash'];
        }
        
        return false;
    }

    /*
    * @name __getByHash
    * @desc retrieve a ticket
    * @params string $hash hash of ticket
    * @params string $type type of ticket
    * @params string $email corresponding email (optionnal)
    * @return array
    */
    function __getByHash($hash, $type, $email = null) {
        $contraintes = array();
        $contraintes['recursive'] = -1;
        $contraintes['fields'] = array('email');
        $contraintes['conditions']['hash'] = $hash;
        $contraintes['conditions']['type'] = $type;
        if($email) {
            $contraintes['conditions']['email'] = $email;
        }
        return $this->find('first', $contraintes);
    }
    
    /*
    * @name __garbage
    * @desc delete tickets corresponding to an email & a type
    * @params string $email corresponding email
    * @params string $type type of ticket
    */
    function __garbage($email, $type) {
        $contraintes = array();
        $contraintes['recursive'] = -1;
        $contraintes['fields'] = array('id');
        $contraintes['conditions']['email'] = $email;
        $contraintes['conditions']['type'] = $type;
        if($tickets = $this->find('all', $contraintes)) {
            foreach($tickets as $ticket) {
                $this->delete($ticket['Ticket']['id']);
            }
        }
    }
}
