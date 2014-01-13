<?php
 App::uses('CakeEmail', 'Network/Email');

class EmailingComponent extends Component {

    var $email;

    public function __construct() {
        // Default values
        $this->email = new CakeEmail();
                
        $host = substr_count($_SERVER['HTTP_HOST'], '.') > 1?substr($_SERVER['HTTP_HOST'], strpos($_SERVER['HTTP_HOST'], '.') + 1):$_SERVER['HTTP_HOST'];

        // Remove port if present on unusual configurations
        if(strpos($host, ':') !== false) {
            $host = substr($host, 0, strpos($host, ':'));
        }

        $this->email->emailFormat('html');
        $this->email->delivery = 'debug';
        $this->email->from('mushraider@'.$host);
    }

    function signup($dest) {
        $this->email->to($dest);
        $this->email->subject(__('Welcome to MushRaider'));
        $this->email->template('signup', 'default');

        $this->email->viewVars(array(
            'dest' => $dest
        ));
        $this->email->send();
    }

    function recovery($dest, $hash) {
        $this->email->to($dest);
        $this->email->subject(__('[MushRaider] Recover your password'));
        $this->email->template('recovery', 'default');

        $this->email->viewVars(array(
            'dest' => $dest,
            'token' => $hash
        ));
        $this->email->send();
    }

    function eventCancel($dest, $event) {
        $this->email->to($dest);
        $this->email->subject(__('[MushRaider] Event cancelled'));
        $this->email->template('event_cancel', 'default');

        $this->email->viewVars(array(
            'event' => $event
        ));
        $this->email->send();
    }

    function eventNew($dest, $event) {
        $this->email->to($dest);
        $this->email->subject(__('[MushRaider] New Event has been added'));
        $this->email->template('event_new', 'default');

        $this->email->viewVars(array(            
            'event' => $event
        ));
        $this->email->send();
    }

    function eventValidate($dest, $event) {
        $this->email->to($dest);
        $this->email->subject(__('[MushRaider] You have been validate to an event'));
        $this->email->template('event_validate', 'default');

        $this->email->viewVars(array(            
            'event' => $event
        ));
        $this->email->send();
    }
}