<?php
 App::uses('CakeEmail', 'Network/Email');

class EmailingComponent extends Component {

    var $email;

    public function startup(Controller $controller) {
        $emailSettings = Configure::read('Config.email');

        $emailConfig = null;
        if($emailSettings->transport == 'Smtp') {
            $emailConfig = array();
            $emailConfig['transport'] = $emailSettings->transport;
            $emailConfig['host'] = $emailSettings->host;
            $emailConfig['port'] = $emailSettings->port;
            $emailConfig['username'] = $emailSettings->username;
            $emailConfig['password'] = $emailSettings->password;
        }
        $this->email = new CakeEmail($emailConfig);
        $this->email->emailFormat('html');
        $this->email->from(array($emailSettings->from => $emailSettings->name));
        if($emailSettings->encoding == 'utf8') {
            $this->email->charset($emailSettings->encoding);
        }
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